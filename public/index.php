<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Country Blocking System
|--------------------------------------------------------------------------
|
| This code checks if the visitor's country is blocked. If it is, the visitor
| is redirected to a blocked page. This check happens before the application
| is loaded to ensure that blocked countries cannot access any part of the site.
|
*/

// Function to get visitor's IP address
function getIpAddress() {
    // Check for CloudFlare IP
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    // Check for proxy IPs
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // HTTP_X_FORWARDED_FOR can contain multiple IPs, get the first one
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }

    // Default to REMOTE_ADDR
    return $_SERVER['REMOTE_ADDR'];
}

// Function to get country code from IP
function getCountryCode($ip) {
    try {
        // First try ip-api.com (free tier)
        $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode");
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['countryCode'])) {
                return $data['countryCode'];
            }
        }

        // Fallback to ipapi.co if ip-api.com fails
        $response = @file_get_contents("https://ipapi.co/{$ip}/country/");
        if ($response && strlen($response) === 2) {
            return $response;
        }

        // Last resort, try ipinfo.io
        $response = @file_get_contents("https://ipinfo.io/{$ip}/country");
        if ($response && strlen($response) === 2) {
            return trim($response);
        }
    } catch (Exception $e) {
        // Silently fail and allow access
    }

    return null;
}

// List of blocked countries (country codes in ISO 3166-1 alpha-2 format)
$blockedCountries = ['EG']; // Add more country codes as needed

// Skip check for blocked page
if ($_SERVER['REQUEST_URI'] !== '/country-blocked') {
    $ip = getIpAddress();

    // Skip check for localhost
    if ($ip !== '127.0.0.1' && $ip !== '::1') {
        $countryCode = getCountryCode($ip);

        if ($countryCode && in_array($countryCode, $blockedCountries)) {
            // Redirect to blocked page
            header('Location: /country-blocked');
            exit;
        }
    }
}

// The country blocking code has been moved to the top of the file

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
