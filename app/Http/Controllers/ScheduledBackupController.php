<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\BackupLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DatabaseConnection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class ScheduledBackupController extends Controller
{
    public function process($schedule_id)
    {
        set_time_limit(0);

        $schedule = Schedule::findOrFail($schedule_id);
        $conn = DatabaseConnection::findOrFail($schedule->database_connection_id);

        $fileName = 'backup_' . Str::slug($conn->db_name) . '_' . date('Ymd_His');

        $driver = $conn->database_type->driver;
        $fileName .= $driver === 'sqlite' ? '.sqlite' : '.sql';
        $path = 'storage/app/tmp/' . $fileName;

        if ($driver === 'mysql') {
            $exitCode = Artisan::call('native:mysqldump', [
                '--host' => $conn->host,
                '--user' => $conn->username,
                '--pass' => $conn->password,
                '--db'   => $conn->db_name,
                '--path' => $path,
            ]);
        } elseif ($driver === 'pgsql') {
            $exitCode = Artisan::call('native:pgdump', [
                '--host' => $conn->host,
                '--port' => $conn->port,
                '--user' => $conn->username,
                '--pass' => $conn->password,
                '--db'   => $conn->db_name,
                '--path' => $path,
            ]);
        } elseif ($driver === 'sqlite') {
            $exitCode = Artisan::call('native:sqlitedump', [
                '--source' => $conn->host,
                '--path'   => $path,
            ]);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'Driver not supported'], 422);
        }

        BackupLog::insert([
            'user_id' => Auth::check() ? Auth::id() : null,
            'database_connection_id' => $schedule->database_connection_id,
            'status' => $exitCode === 0 ? 'success' : 'fail',
            'method' => 'scheduled',
        ]);

        if ($exitCode === 0 && file_exists(base_path($path))) {
            return response()->json(['status' => 'success', 'file' => $fileName]);
        }

        return response()->json(['status' => 'fail', 'message' => 'Backup failed. Check the logs.']);
    }
}
