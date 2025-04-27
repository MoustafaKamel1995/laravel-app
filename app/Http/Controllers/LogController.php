<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = [];
        $timezone = $request->input('timezone', config('app.timezone')); // ������� ������� ������� �� ����� �� ����������

        if (File::exists($logPath)) {
            $logContent = File::get($logPath);
            $logEntries = explode("\n", $logContent);

            foreach ($logEntries as $entry) {
                if (!empty(trim($entry))) {
                    preg_match('/^\[([^\]]+)\] (\w+)\.([^\:]+)\: (.+)$/', $entry, $matches);
                    if (isset($matches[1], $matches[2], $matches[3], $matches[4])) {
                        try {
                            // ����� ����� ��� ������� ������� ��������
                            $date = Carbon::parse($matches[1])->setTimezone($timezone);
                            $logs[] = [
                                'date' => $date->format('Y-m-d H:i:s'),
                                'type' => $matches[2],
                                'message' => $matches[4],
                                'email' => 'N/A', // ���� ����� ��� ����� ��� ���� �������� �����
                                'ip' => 'N/A', // ���� ����� ��� ����� ��� ���� �������� �����
                            ];
                        } catch (\Exception $e) {
                            // ����� ������� ���� ����� ��� ����� �� ������� �������
                            continue;
                        }
                    }
                }
            }
        }

        // ����� ������� ���� ���� ������ �����
        $logs = array_reverse($logs);

        // ������� ���������� �� 15 ��� ��� ����
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;
        $currentItems = array_slice($logs, ($currentPage - 1) * $perPage, $perPage);
        $paginatedLogs = new LengthAwarePaginator($currentItems, count($logs), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        return view('logs.index', ['logs' => $paginatedLogs, 'timezone' => $timezone]);
    }

    public function deleteAllLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, ''); // ��� ������� ��� �����
        }

        return redirect()->route('logs.index')->with('success', 'All logs have been deleted successfully.');
    }
}
