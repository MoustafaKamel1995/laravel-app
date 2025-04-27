<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class UpdateController extends Controller
{
    public function show()
    {
        return view('update');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'update_file' => 'required|file|mimes:zip',
            'version' => 'required|string',
        ]);

        try {
            $file = $request->file('update_file');
            $version = $request->input('version');

            // ÊÍÞÞ ãä æÌæÏ ÇáãÌáÏ æÅÐÇ áã íßä ãæÌæÏðÇ¡ ÃäÔÆå
            $updateFolderPath = storage_path('app/updates');
            if (!File::exists($updateFolderPath)) {
                File::makeDirectory($updateFolderPath, 0755, true);
            }

            // ÊÃßÏ ãä æÌæÏ ÃÐæäÇÊ ÇáßÊÇÈÉ Úáì ÇáãÌáÏ
            if (!is_writable($updateFolderPath)) {
                Log::error('Upload directory is not writable: ' . $updateFolderPath);
                return response()->json(['error' => 'Upload directory is not writable'], 500);
            }

            // ÇÍÝÙ ÇáãáÝ ÇáãÖÛæØ Ýí ÇáÝæáÏÑ ÇáãÍÏÏ
            $filePath = $file->storeAs('updates', 'update.zip');

            // ÊÃßÏ ãä Ãä ÇáãáÝ Êã ÍÝÙå ÈäÌÇÍ
            if (!Storage::exists($filePath)) {
                Log::error('Failed to store the update file at: ' . $filePath);
                return response()->json(['error' => 'Failed to store the update file'], 500);
            }

            // ÃäÔÆ ãáÝ version.txt
            $versionData = [
                'version' => $version,
                'update_url' => url('updates/update.zip'), // ÊÚÏíá ÇáãÓÇÑ áíßæä ÑÇÈØðÇ ßÇãáÇð
            ];
            Storage::disk('local')->put('updates/version.txt', json_encode($versionData, JSON_PRETTY_PRINT));

            // ÊÃßÏ ãä Ãä ãáÝ version.txt Êã ÅäÔÇÄå ÈäÌÇÍ
            if (!Storage::exists('updates/version.txt')) {
                Log::error('Failed to create version.txt');
                return response()->json(['error' => 'Failed to create version.txt'], 500);
            }

            return response()->json(['success' => 'Update uploaded and version.txt created successfully!']);
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
        }
    }

    public function getLatestUpdate()
    {
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
    }
}
