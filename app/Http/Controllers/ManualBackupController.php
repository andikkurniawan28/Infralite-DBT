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

        $conn = DatabaseConnection::findOrFail($request->database_connection_id);

        // Buat nama file backup
        $fileName = 'backup_' . Str::slug($conn->db_name) . '_' . date('Ymd_His');

        // Siapkan path berdasarkan tipe driver
        if ($conn->database_type->driver === 'sqlite') {
            $fileName .= '.sqlite';
        } else {
            $fileName .= '.sql';
        }

        $path = 'storage/app/tmp/' . $fileName;

        // Jalankan Artisan Command sesuai tipe driver
        if ($conn->database_type->driver === "mysql") {
            $exitCode = Artisan::call('native:mysqldump', [
                '--host' => $conn->host,
                '--user' => $conn->username,
                '--pass' => $conn->password,
                '--db'   => $conn->db_name,
                '--path' => $path,
            ]);
        } elseif ($conn->database_type->driver === "pgsql") {
            $exitCode = Artisan::call('native:pgdump', [
                '--host' => $conn->host,
                '--port' => $conn->port,
                '--user' => $conn->username,
                '--pass' => $conn->password,
                '--db'   => $conn->db_name,
                '--path' => $path,
            ]);
        } elseif ($conn->database_type->driver === "sqlite") {
            $exitCode = Artisan::call('native:sqlitedump', [
                '--source' => $conn->host, // `host` menyimpan full path file .sqlite
                '--path'   => $path,
            ]);
        } else {
            return back()->with('fail', 'Sorry, this driver is not supported.');
        }

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
