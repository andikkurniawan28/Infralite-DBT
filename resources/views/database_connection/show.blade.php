@extends('template.master')

@section('content')
<main class="container py-3">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white rounded px-3 py-2 shadow-sm">
            <li class="breadcrumb-item">
                <a href="{{ route('welcome') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('database_connection.index') }}">Database Connection</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Database Connection Info</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Database Connection Info</h5>
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Type</div>
                <div class="col-md-9">
                    <img src="{{ $connection->database_type->icon }}" alt="{{ $connection->database_type->brand }}" style="height: 24px;" class="me-2">
                    {{ $connection->database_type->brand }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Description</div>
                <div class="col-md-9">{{ $connection->description ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Host</div>
                <div class="col-md-9">{{ $connection->host }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Username</div>
                <div class="col-md-9">{{ $connection->username }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Database Name</div>
                <div class="col-md-9">{{ $connection->db_name }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Charset</div>
                <div class="col-md-9">{{ $connection->charset ?? $connection->database_type->default_charset }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Collation</div>
                <div class="col-md-9">{{ $connection->collation ?? $connection->database_type->default_collation }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Schema</div>
                <div class="col-md-9">{{ $connection->schema ?? $connection->database_type->default_schema }}</div>
            </div>

            <div class="text-end">
                <a href="{{ route('database_connection.index') }}" class="btn btn-secondary">Back</a>
            </div>

        </div>
    </div>

</main>
@endsection
