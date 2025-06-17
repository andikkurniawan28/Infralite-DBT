<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BackupLog;
use Yajra\DataTables\Facades\DataTables;

class BackupLogController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {
            $logs = BackupLog::with(['database_connection', 'user']); // eager load relasi

            return DataTables::of($logs)
                ->addIndexColumn()
                ->addColumn('db_name', function ($row) {
                    return $row->database_connection->db_name.'@'.$row->database_connection->host.'-'.$row->database_connection->database_type->brand ?? '-';
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user->name ?? '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['db_name', 'user_name']) // kalau kamu ingin render HTML nanti
                ->make(true);
        }

        return view('backup_log.index');
    }
}
