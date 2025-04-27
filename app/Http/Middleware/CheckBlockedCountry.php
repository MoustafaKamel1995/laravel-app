<?php

namespace App\Http\Middleware;

use App\Models\BlockedCountry;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedCountry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for country block page or if route is null
        $route = $request->route();

        // Log the current route for debugging
        Log::info('Current route: ' . ($route ? $route->getName() : 'null') . ', Path: ' . $request->path());

        // Only allow access to the blocked country page
        if ($route === null || $route->getName() === 'country.blocked' || $request->path() === 'country-blocked') {
            return $next($request);
        }

        // Get visitor's IP address
        $ip = $request->ip();

        // For local development, you might want to skip the check
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return $next($request);
        }

        // Clear the cache for testing purposes
        Cache::forget('ip_country_' . $ip);

        // For testing purposes, let's force the country code to be EG (Egypt)
        // This will help us test if the blocking mechanism works
        $countryCode = 'EG';
        Log::info('Forcing country code to EG for testing');

        // Uncomment the below code when you want to use real IP detection
        /*
        // Get country code from IP (cache for 24 hours to reduce API calls)
        $countryCode = Cache::remember('ip_country_' . $ip, 60 * 24, function () use ($ip) {
            try {
                // Try multiple services for IP geolocation

                // First try ipapi.co
                $response = Http::get("https://ipapi.co/{$ip}/json/");
                Log::info('ipapi.co Response for ' . $ip . ': ' . $response->body());

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['country_code'])) {
                        Log::info('Country detected from ipapi.co: ' . $data['country_code'] . ' for IP: ' . $ip);
                        return $data['country_code'];
                    }
                }

                // If ipapi.co fails, try ip-api.com
                $response = Http::get("http://ip-api.com/json/{$ip}");
                Log::info('ip-api.com Response for ' . $ip . ': ' . $response->body());

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['countryCode'])) {
                        Log::info('Country detected from ip-api.com: ' . $data['countryCode'] . ' for IP: ' . $ip);
                        return $data['countryCode'];
                    }
                }

                return null;
            } catch (\Exception $e) {
                Log::error('Error detecting country from IP: ' . $e->getMessage());
                return null;
            }
        });
        */

        // If country code couldn't be determined, allow access
        if (!$countryCode) {
            Log::warning('Country code could not be determined for IP: ' . $ip);
            return $next($request);
        }

        // Check if country is blocked
        $isBlocked = BlockedCountry::isCountryBlocked($countryCode);
        Log::info('Country ' . $countryCode . ' is ' . ($isBlocked ? 'blocked' : 'not blocked'));

        if ($isBlocked) {
            Log::info('Redirecting to blocked page for country: ' . $countryCode);

            // Force logout if user is authenticated
            if (auth()->check()) {
                Log::info('User is authenticated, logging out before redirect');
                auth()->logout();
                session()->invalidate();
                session()->regenerateToken();
            }

            // Redirect to blocked page with 403 status code
            return redirect()->route('country.blocked')->with('blocked_country', $countryCode);
        }

        return $next($request);
    }
}
