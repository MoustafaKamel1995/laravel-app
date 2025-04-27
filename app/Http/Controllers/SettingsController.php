<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $serialCheckEnabled = $settings['serial_check_enabled'] ?? '1';
        return view('settings.index', compact('settings', 'serialCheckEnabled'));
    }

    public function store(Request $request)
    {
        foreach ($request->settings as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        if ($request->has('serial_check_enabled')) {
            Setting::updateOrCreate(['key' => 'serial_check_enabled'], ['value' => $request->input('serial_check_enabled')]);
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }

    public function clearCache(Request $request)
    {
        if ($request->input('key') !== env('CLEAR_CACHE_KEY')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Artisan::call('optimize:clear');
        return response()->json(['message' => 'Cache cleared successfully']);
    }

    public function apiGetSettings()
    {
        try {
            $settings = Setting::all()->pluck('value', 'key');
            return response()->json($settings);
        } catch (\Exception $e) {
            Log::error('Failed to get settings: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get settings'], 500);
        }
    }
}
