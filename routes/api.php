<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\SettingsController;
use App\Models\Update;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/system_info', [AuthController::class, 'storeSystemInfo']);
Route::get('/settings', [SettingsController::class, 'apiGetSettings']);
Route::get('/api/settings', [SettingsController::class, 'apiGetSettings']);

Route::middleware(['auth:sanctum', 'api_user'])->group(function () {
    Route::post('/system_info', [AuthController::class, 'storeSystemInfo']);
});

Route::get('/latest-update', function () {
    $versionFilePath = storage_path('app/updates/version.txt');
    if (file_exists($versionFilePath)) {
        $versionData = json_decode(file_get_contents($versionFilePath), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return response()->json($versionData);
        } else {
            return response()->json(['error' => 'Invalid JSON format in version.txt'], 400);
        }
    } else {
        return response()->json(['error' => 'version.txt not found'], 404);
    }
});
