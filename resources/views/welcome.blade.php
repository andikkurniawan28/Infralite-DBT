@extends('template.master')

@section('breadcrumb')

@endsection

@section('content')
    <main class="container py-1">

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded px-3 py-2 shadow-sm">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('welcome') }}">
                        <i class="bi bi-house-door"></i>
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
                        <a href="{{ route('database_connection.index') }}" class="btn btn-dark">Manage</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-download"></i></div>
                        <h5 class="card-title">Manual Backup</h5>
                        <p class="card-text">Perform backup manually anytime.</p>
                        <a href="{{ route('manual_backup.index') }}" class="btn btn-dark">Backup</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-clock-history"></i></div>
                        <h5 class="card-title">Scheduled Backup</h5>
                        <p class="card-text">Set up automatic scheduled backups.</p>
                        <a href="{{ route('schedule.index') }}" class="btn btn-dark">Schedule</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-folder2-open"></i></div>
                        <h5 class="card-title">Backup File</h5>
                        <p class="card-text">Download system backup files easily.</p>
                        <a href="{{ route('backup_file.index') }}" class="btn btn-dark">Open</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-journal-text"></i></div>
                        <h5 class="card-title">Backup Log</h5>
                        <p class="card-text">View backup history and status.</p>
                        <a href="{{ route('backup_log.index') }}" class="btn btn-dark">View</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-person-gear"></i></div>
                        <h5 class="card-title">User</h5>
                        <p class="card-text">Manage application users.</p>
                        <a href="{{ route('user.index') }}" class="btn btn-dark">Manage</a>
                    </div>
                </div>
            </div>


        </div>
    </main>
@endsection

{{-- @section('script')
<script>
    function showFileManager() {
        fetch(`{{ route('backup.files') }}`)
            .then(res => res.json())
            .then(files => {
                if (files.length === 0) {
                    return Swal.fire({
                        icon: 'info',
                        title: 'No Files',
                        text: 'There are no backup files available.',
                        timer: 3000,
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false
                    });
                }

                let html = files.map(f => `
                    <div class="mb-2">
                        <i class="bi bi-file-earmark-arrow-down"></i>
                        <strong>${f.name}</strong><br>
                        <small>${(f.size / 1024).toFixed(1)} KB | ${new Date(f.last_modified * 1000).toLocaleString()}</small><br>
                        <a class="btn btn-sm btn-success mt-1" href="${f.url}" download>Download</a>
                    </div>
                `).join('<hr>');

                Swal.fire({
                    title: 'Backup Files',
                    html,
                    width: 600,
                    showConfirmButton: false,
                    focusConfirm: false,
                });
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load backup files.',
                    timer: 3000,
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false
                });
            });
    }
</script>
@endsection --}}
