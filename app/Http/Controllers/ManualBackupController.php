<?php

namespace App\Http\Controllers;

use App\Models\BackupLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DatabaseConnection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class ManualBackupController extends Controller
{
    public function index()
    {
        $connections = DatabaseConnection::all();
        return view('manual_backup.index', compact('connections'));
    }

    public function process(Request $request)
    {
        set_time_limit(0); // unlimited execution time

        $request->validate([
            'database_connection_id' => 'required|exists:database_connections,id',
        ]);

        // Jalankan Backup
        $result = BackupController::run($request->database_connection_id);
        $exitCode = $result['exit_code'];
        $path = $result['path'];

        // Simpan log dan respon hasil
        BackupLog::insert([
            'user_id' => Auth::id(),
            'database_connection_id' => $request->database_connection_id,
            'status' => $exitCode === 0 ? 'success' : 'fail',
            'method' => 'manual',
        ]);

        if ($exitCode === 0) {
            return response()->download(base_path($path))->deleteFileAfterSend(true);
        } else {
            return back()->with('error', 'Backup failed. Check the logs for more details.');
        }
    }


}
