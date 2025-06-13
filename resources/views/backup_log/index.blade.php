@extends('template.master')

@section('content')
    <div class="container py-1">

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded px-3 py-2 shadow-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('welcome') }}">
                        Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Backup Logs
                </li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Backup Logs</h4>
        </div>

        <div class="card">
            <div class="card-body">
                <table id="backupLogsTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>DB Connection</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#backupLogsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('backup_log.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'db_name',
                        name: 'database_connection.db_name'
                    },
                    {
                        data: 'user_name',
                        name: 'user.name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                ]
            });
        });
    </script>
@endsection
