@extends('template.master')

@section('content')
<main class="container py-4">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white rounded px-3 py-2 shadow-sm">
            <li class="breadcrumb-item">
                <a href="{{ route('welcome') }}">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Manual Backup</li>
        </ol>
    </nav>

    <h4 class="mb-3">Manual Database Backup</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('manual_backup.process') }}" method="POST">
                @csrf @method('POST')

                <div class="mb-3">
                    <label for="connection_id" class="form-label">Select Database Connection</label>
                    <select name="database_connection_id" id="database_connection_id" class="form-select" required>
                        <option value="">-- Choose Database Connection --</option>
                        @foreach ($connections as $connection)
                            <option value="{{ $connection->id }}">
                                {{ $connection->database_type->brand }} - {{ $connection->host }} / {{ $connection->db_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="mb-3">
                    <label for="destination" class="form-label">Save To</label>
                    <select name="destination" id="destination" class="form-select" required>
                        <option value="local">Local Storage</option>
                        <option value="google_drive">Google Drive</option>
                    </select>
                </div> --}}

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-play-circle me-1"></i> Process Backup
                </button>
            </form>
        </div>
    </div>

</main>
@endsection
