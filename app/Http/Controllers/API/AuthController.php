<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Models\LoginLog;
use App\Models\Setting;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $apiStatus = Setting::where('key', 'api_status')->first()->value ?? 1;
        if ($apiStatus == 0) {
            return response()->json([
                'message' => 'API is currently disabled.'
            ], 503);
        }

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'disk_info' => 'required|string',
            'memory_info' => 'required|string'
        ]);

        $email = $request->input('email');
        $ipAddress = $request->ip();
        $time = now()->toDateTimeString();

        $loginAttemptsLimit = Setting::where('key', 'login_attempts')->first()->value ?? 5;
        $strictIpCheck = Setting::where('key', 'strict_ip_check')->first()->value ?? 1;
        $lockoutDuration = Setting::where('key', 'lockout_duration')->first()->value ?? 60;
        $serialCheckEnabled = Setting::where('key', 'serial_check_enabled')->first()->value ?? 1;

        if (RateLimiter::tooManyAttempts('login:'.$email, $loginAttemptsLimit)) {
            return response()->json([
                'message' => 'Too many login attempts. Please try again in ' . $lockoutDuration . ' minutes.'
            ], 429);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit('login:'.$email, $lockoutDuration * 60);
            Log::warning('Failed login attempt', [
                'email' => $email,
                'ip' => $ipAddress,
                'time' => $time
            ]);

            LoginLog::create([
                'email' => $email,
                'ip' => $ipAddress,
                'status' => 'failed',
                'time' => $time,
                'message' => 'Invalid credentials'
            ]);

            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        RateLimiter::clear('login:'.$email);
        $user = Auth::user();

        if ($user->role !== 'api_user') {
            Auth::logout();
            Log::info('Unauthorized login attempt by user role', [
                'email' => $email,
                'role' => $user->role,
                'ip' => $ipAddress,
                'time' => $time
            ]);

            LoginLog::create([
                'email' => $email,
                'ip' => $ipAddress,
                'status' => 'failed',
                'time' => $time,
                'message' => 'You are not allowed to log in from the API.'
            ]);

            return response()->json([
                'message' => 'You are not allowed to log in from the API.'
            ], 403);
        }

        if (!$user->is_active) {
            Auth::logout();
            Log::info('Login attempt for inactive account', [
                'email' => $email,
                'ip' => $ipAddress,
                'time' => $time
            ]);

            LoginLog::create([
                'email' => $email,
                'ip' => $ipAddress,
                'status' => 'failed',
                'time' => $time,
                'message' => 'Your account is not active.'
            ]);

            return response()->json([
                'message' => 'Your account is not active.'
            ], 403);
        }

        if ($serialCheckEnabled) {
            if (!$user->disk_serial || !$user->memory_serial) {
                $user->disk_serial = Crypt::encrypt($request->input('disk_info'));
                $user->memory_serial = Crypt::encrypt($request->input('memory_info'));
                $user->save();

                Log::info('Stored new serials for user ID: ' . $user->id, [
                    'disk_serial' => $request->input('disk_info'),
                    'memory_serial' => $request->input('memory_info')
                ]);
            } else {
                try {
                    $diskSerial = Crypt::decrypt($user->disk_serial);
                    $memorySerial = Crypt::decrypt($user->memory_serial);
                    Log::info('Decrypted serials for user ID: ' . $user->id, [
                        'disk_serial' => $diskSerial,
                        'memory_serial' => $memorySerial
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to decrypt serials for user ID: ' . $user->id . ' with error: ' . $e->getMessage());
                    return response()->json(['message' => 'Decryption error.'], 500);
                }

                if ($diskSerial !== $request->input('disk_info') || $memorySerial !== $request->input('memory_info')) {
                    Log::info('Unauthorized device check for user ID: ' . $user->id, [
                        'stored_disk_serial' => $diskSerial,
                        'provided_disk_serial' => $request->input('disk_info'),
                        'stored_memory_serial' => $memorySerial,
                        'provided_memory_serial' => $request->input('memory_info')
                    ]);
                    Auth::logout();
                    return response()->json(['message' => 'Unauthorized device.'], 403);
                }
            }
        }

        if ($strictIpCheck && $user->ip_address && $user->ip_address !== $ipAddress) {
            Log::info('Access denied from IP', [
                'email' => $email,
                'ip' => $ipAddress,
                'time' => $time,
                'current_ip' => $user->ip_address,
                'new_ip' => $ipAddress
            ]);

            LoginLog::create([
                'email' => $email,
                'ip' => $ipAddress,
                'status' => 'failed',
                'time' => $time,
                'message' => 'Access denied from this IP address.'
            ]);

            Auth::logout();
            return response()->json([
                'message' => 'Access denied from this IP address.'
            ], 403);
        }

        $user->ip_address = $ipAddress;
        Log::info('User data before saving', $user->toArray());
        try {
            $user->save();
            Log::info('User data after saving', $user->toArray());
        } catch (\Exception $e) {
            Log::error('Failed to update IP address for user ID: ' . $user->id . ' with error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update IP address.'], 500);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('Successful login', [
            'email' => $email,
            'ip' => $ipAddress,
            'time' => $time
        ]);

        LoginLog::create([
            'email' => $email,
            'ip' => $ipAddress,
            'status' => 'successful',
            'time' => $time,
            'message' => 'Login successful.'
        ]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expiry_date' => $user->expiry_date
        ]);
    }

    public function storeSystemInfo(Request $request)
    {
        $apiStatus = Setting::where('key', 'api_status')->first()->value ?? 1;
        if ($apiStatus == 0) {
            return response()->json([
                'message' => 'API is currently disabled.'
            ], 503);
        }

        $user = Auth::user();

        $request->validate([
            'disk_info' => 'required|string',
            'memory_info' => 'required|string',
            'time_local' => 'required|string',
        ]);

        Log::info('Received system info:', $request->all());

        if (!$user->disk_serial && !$user->memory_serial) {
            $user->disk_serial = Crypt::encrypt($request->input('disk_info'));
            $user->memory_serial = Crypt::encrypt($request->input('memory_info'));
            $user->save();
            return response()->json(['message' => 'System info saved successfully'], 200);
        }

        try {
            $diskSerial = Crypt::decrypt($user->disk_serial);
            $memorySerial = Crypt::decrypt($user->memory_serial);
            Log::info('Decrypted serials for user ID: ' . $user->id, [
                'disk_serial' => $diskSerial,
                'memory_serial' => $memorySerial
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to decrypt serials for user ID: ' . $user->id . ' with error: ' . $e->getMessage());
            return response()->json(['message' => 'Decryption error.'], 500);
        }

        if ($diskSerial !== $request->input('disk_info') || $memorySerial !== $request->input('memory_info')) {
            Log::info('Unauthorized device check for user ID: ' . $user->id, [
                'stored_disk_serial' => $diskSerial,
                'provided_disk_serial' => $request->input('disk_info'),
                'stored_memory_serial' => $memorySerial,
                'provided_memory_serial' => $request->input('memory_info')
            ]);
            return response()->json(['message' => 'Unauthorized device.'], 403);
        }

        return response()->json(['message' => 'System info already matches'], 200);
    }
}
