<?php
// This is a standalone script to check if the country is blocked

// Function to get visitor's IP address
function getIpAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Function to get country code from IP
function getCountryCode($ip) {
    // For testing purposes, force country code to EG (Egypt)
    return 'EG';
    
    /* Uncomment this for production use
    try {
        // Using ip-api.com (free tier)
        $response = file_get_contents("http://ip-api.com/json/{$ip}");
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['countryCode'])) {
                return $data['countryCode'];
            }
        }
        
        // Fallback to ipapi.co if ip-api.com fails
        $response = file_get_contents("https://ipapi.co/{$ip}/json/");
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['country_code'])) {
                return $data['country_code'];
            }
        }
    } catch (Exception $e) {
        // Log error if needed
    }
    return null;
    */
}

// List of blocked countries
$blockedCountries = ['EG']; // Add more country codes as needed

// Get IP and country
$ip = getIpAddress();
$countryCode = getCountryCode($ip);

// Check if country is blocked
$isBlocked = false;
if ($countryCode && in_array($countryCode, $blockedCountries)) {
    $isBlocked = true;
}

// Return result as JSON
header('Content-Type: application/json');
echo json_encode([
    'ip' => $ip,
    'country_code' => $countryCode,
    'is_blocked' => $isBlocked
]);
