@extends('template.master')

@section('breadcrumb')

@endsection

@section('content')
    <main class="container py-1">

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded px-3 py-2 shadow-sm">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('welcome') }}">
                        Home
                    </a>
                </li>
                {{-- <li class="breadcrumb-item active" aria-current="page">
                    Database Connection
                </li> --}}
            </ol>
        </nav>

        <div class="row g-4">

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-hdd-network-fill"></i></div>
                        <h5 class="card-title">Database Connection</h5>
                        <p class="card-text">Manage your database connections.</p>
                        <a href="{{ route('database_connection.index') }}" class="btn btn-primary">Manage</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-download"></i></div>
                        <h5 class="card-title">Manual Backup</h5>
                        <p class="card-text">Perform backup manually anytime.</p>
                        <a href="#" class="btn btn-primary">Backup</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-clock-history"></i></div>
                        <h5 class="card-title">Scheduled Backup</h5>
                        <p class="card-text">Set up automatic scheduled backups.</p>
                        <a href="#" class="btn btn-primary">Schedule</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-journal-text"></i></div>
                        <h5 class="card-title">Backup Log</h5>
                        <p class="card-text">View backup history and status.</p>
                        <a href="#" class="btn btn-primary">View Logs</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-person-gear"></i></div>
                        <h5 class="card-title">User</h5>
                        <p class="card-text">Manage application users.</p>
                        <a href="#" class="btn btn-primary">Manage</a>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
