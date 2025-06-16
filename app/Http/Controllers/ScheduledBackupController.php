<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\BackupLog;
use App\Models\DatabaseConnection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class ScheduledBackupController extends Controller
{
    public function process()
    {
        set_time_limit(0);

        $nowDay = date('l');
        $nowHour = date('H:i');

        $schedules = Schedule::where('day', $nowDay)
            ->where('hour', $nowHour)
            ->get();

        if ($schedules->isEmpty()) {
            return response()->json([
                'status' => 'skipped',
                'message' => "No scheduled backups for {$nowDay} at {$nowHour}."
            ]);
        }

        $results = [];

        foreach ($schedules as $schedule) {
            $conn = DatabaseConnection::find($schedule->database_connection_id);
            if (!$conn) continue;

            $alreadyBackedUp = BackupLog::whereDate('created_at', now()->toDateString())
                ->where('database_connection_id', $conn->id)
                ->where('method', 'scheduled')
                ->exists();

            if ($alreadyBackedUp) {
                $results[] = [
                    'schedule_id' => $schedule->id,
                    'status' => 'skipped',
                    'message' => 'Already backed up today',
                ];
                continue;
            }

            $result = BackupController::run($conn->id);
            $exitCode = $result['exit_code'];
            $filePath = $result['path'] ?? null;

            BackupLog::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'database_connection_id' => $conn->id,
                'status' => $exitCode === 0 ? 'success' : 'fail',
                'method' => 'scheduled',
            ]);

            $downloadUrl = $exitCode === 0 && $filePath
                ? URL::route('scheduled_backup.download', ['filename' => basename($filePath)])
                : null;

            $results[] = [
                'schedule_id' => $schedule->id,
                'status' => $exitCode === 0 ? 'success' : 'fail',
                'file' => basename($filePath),
                'download_url' => $downloadUrl,
            ];
        }

        return response()->json([
            'status' => 'done',
            'results' => $results,
        ]);
    }

    public function download($filename)
    {
        $path = storage_path("app/public/tmp/{$filename}");

        if (!file_exists($path)) {
            abort(404, 'Backup file not found.');
        }

        return response()->download($path);
    }
}
