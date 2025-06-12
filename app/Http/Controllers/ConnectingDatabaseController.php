<?php

namespace App\Http\Controllers;

use App\Models\DatabaseConnection;
use PDO;
use Exception;

class ConnectingDatabaseController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id)
    {
        $conn = DatabaseConnection::with('database_type')->findOrFail($id);

        try {
            // Siapkan DSN berdasarkan driver
            switch ($conn->database_type->driver) {
                case 'mysql':
                case 'mariadb':
                    $dsn = "mysql:host={$conn->host};dbname={$conn->db_name};charset=" . ($conn->charset ?? 'utf8mb4');
                    break;

                case 'pgsql':
                    $dsn = "pgsql:host={$conn->host};dbname={$conn->db_name};options='--client_encoding=" . ($conn->charset ?? 'UTF8') . "'";
                    break;

                case 'sqlite':
                    $dsn = "sqlite:{$conn->host}"; // Untuk SQLite, `host` adalah path ke file `.sqlite`
                    break;

                default:
                    return response()->json(['status' => 'fail', 'error' => 'Unsupported driver']);
            }

            // Lakukan koneksi langsung menggunakan PDO
            $pdo = new PDO($dsn, $conn->username, $conn->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            return response()->json(['status' => 'success']);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
