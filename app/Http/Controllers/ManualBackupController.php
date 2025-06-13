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
        set_time_limit(0); // 0 artinya unlimited waktu eksekusi

        $request->validate([
            'database_connection_id' => 'required|exists:database_connections,id',
        ]);

        $conn = DatabaseConnection::findOrFail($request->database_connection_id);

        // Buat nama file
        $fileName = 'backup_' . Str::slug($conn->db_name) . '_' . date('Ymd_His') . '.sql';
        $path = 'storage/app/tmp/' . $fileName;

        // Jalankan Artisan Command
        $exitCode = Artisan::call('native:mysqldump', [
            '--host' => $conn->host,
            '--user' => $conn->username,
            '--pass' => $conn->password,
            '--db'   => $conn->db_name,
            '--path' => $path,
        ]);

        if ($exitCode === 0) {
            BackupLog::insert([
                'user_id' => Auth::user()->id,
                'database_connection_id' => $request->database_connection_id,
                'status' => 'success',
            ]);
            return response()->download(base_path($path))->deleteFileAfterSend(true);
        } else {
            BackupLog::insert([
                'user_id' => Auth::user()->id,
                'database_connection_id' => $request->database_connection_id,
                'status' => 'fail',
            ]);
            return back()->with('error', 'Backup gagal. Lihat log untuk detail.');
        }
    }

}
