<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatabaseConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ManualBackupController extends Controller
{
    public function index(){
        $connections = DatabaseConnection::all();
        return view('manual_backup.index', compact('connections'));
    }

    public function process(Request $request){
        $request->validate([
            'database_connection_id' => 'required|exists:database_connections,id',
        ]);

        $conn = DatabaseConnection::findOrFail($request->database_connection_id);

        $this->setupTempDatabaseConnection($conn);

        try {
            $fileName = $this->generateBackupFile($conn);

            return response()->download(
                storage_path("app/tmp/{$fileName}"),
                $fileName
            )->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }

    private function setupTempDatabaseConnection($conn){
        config([
            'database.connections.temp_dump' => [
                'driver'    => $conn->database_type->driver,
                'host'      => $conn->host,
                'database'  => $conn->db_name,
                'username'  => $conn->username,
                'password'  => $conn->password,
                'charset'   => $conn->charset ?? 'utf8mb4',
                'collation' => $conn->collation ?? 'utf8mb4_unicode_ci',
            ]
        ]);

        DB::purge('temp_dump');
    }

    private function generateBackupFile($conn){
        $dbName = $conn->db_name;
        $tables = DB::connection('temp_dump')->select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $dbName;

        $fileName = 'backup_' . $dbName . '_' . date('Ymd_His') . '.sql';
        $filePath = storage_path("app/tmp/{$fileName}");

        Storage::makeDirectory('tmp');
        $handle = fopen($filePath, 'w');

        fwrite($handle, "-- Manual SQL Dump for {$dbName} --\n\n");

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            $this->writeCreateTable($handle, $tableName);
            $this->writeTableData($handle, $tableName);
        }

        fclose($handle);

        return $fileName;
    }

    private function writeCreateTable($handle, $tableName){
        $create = DB::connection('temp_dump')->select("SHOW CREATE TABLE `$tableName`");
        fwrite($handle, $create[0]->{'Create Table'} . ";\n");
    }

    private function writeTableData($handle, $tableName){
        DB::connection('temp_dump')
            ->table($tableName)
            ->orderBy(DB::raw('1'))
            ->chunk(500, function ($rows) use ($handle, $tableName) {
                foreach ($rows as $row) {
                    $values = array_map(function ($val) {
                        return is_null($val) ? 'NULL' : "'" . addslashes($val) . "'";
                    }, (array)$row);

                    fwrite($handle, "INSERT INTO `$tableName` VALUES (" . implode(', ', $values) . ");\n");
                }
            });

        fwrite($handle, "\n");
    }
}
