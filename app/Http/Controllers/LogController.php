<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Carbon\Carbon;

class LogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $filePath = storage_path('logs/laravel.log');

        // Check if the log file exists
        if (File::exists($filePath)) {
            $logs = File::get($filePath);

            // Optionally, you can split the logs by line for better UI presentation
            $logLines = explode("\n", $logs);


            // 1 week logic
            // Filter the log lines for entries from the last 7 days
            $filteredLogs = [];
            $sevenDaysAgo = Carbon::now()->subDays(7);
            $previousLogDate = null;

            foreach ($logLines as $line) {
                // Attempt to extract date from log line (Laravel logs typically start with a date)
                if (preg_match('/\[(.*?)\]/', $line, $matches)) {
                    try {
                        $logDate = Carbon::parse($matches[1]);

                        // Only include logs from the last 7 days
                        if ($logDate->greaterThanOrEqualTo($sevenDaysAgo)) {
                            $filteredLogs[] = $line;
                            $previousLogDate = $logDate; // Log date found, set as previous
                        } else {
                            $previousLogDate = null; // Skip logs older than 7 days
                        }
                    } catch (\Exception $e) {
                        // Handle invalid date format, treat this as a non-date line
                        if ($previousLogDate) {
                            // If the previous log had a date, treat this as part of it
                            $filteredLogs[] = $line;
                        }
                    }
                } else {
                    // If no date is found, treat it as a part of the previous log
                    if ($previousLogDate) {
                        $filteredLogs[] = $line;
                    }
                }
            }

            // return view('logs.index', compact('logLines'));
            return view('admin.logs.index', compact('filteredLogs'));
        } else {
            return view('admin.logs.index')->withErrors(['Log file does not exist.']);
        }
    }

    public function clear()
    {
        // Path to the log file
        $logPath = storage_path('logs/laravel.log');

        // Clear the log file content
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }

        // Add a session flash message to show confirmation
        return redirect()->back()->with('success', 'Logs cleared successfully!');
    }
}
