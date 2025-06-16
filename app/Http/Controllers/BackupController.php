<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use App\Models\DatabaseConnection;

class BackupController extends Controller
{
    public static function run($database_connection_id)
    {
        // Ambil data Database Connection
        $conn = DatabaseConnection::findOrFail($database_connection_id);

        // Buat nama file backup
        $fileName = 'backup_' . Str::slug($conn->db_name) . '_' . date('Ymd_His');

        // Siapkan path berdasarkan tipe driver
        if ($conn->database_type->driver === 'sqlite') {
            $fileName .= '.sqlite';
        } else {
            $fileName .= '.sql';
        }

        // Tentukan path penyimpanan sementara
        $path = 'storage/app/public/tmp/' . $fileName;

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
            return [
                'exit_code' => 1, // gunakan 1 atau angka selain 0 untuk menyatakan error
                'path' => null,
            ];
        }

        return [
            'exit_code' => $exitCode,
            'path' => $path,
        ];
    }
}
