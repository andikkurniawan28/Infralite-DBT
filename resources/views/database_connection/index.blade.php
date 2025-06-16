@extends('template.master')

@section('content')
    <main class="container py-1">

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded px-3 py-2 shadow-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('welcome') }}">
                        <i class="bi bi-house-door"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Database Connection
                </li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Database Connections</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">+ Add Connection</button>
        </div>

        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th>Host</th>
                    <th>DB Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($connections as $connection)
                    <tr>
                        <td>
                            <img src="{{ $connection->database_type->icon }}" alt="{{ $connection->database_type->brand }}" width="20" height="20" class="me-1">
                            {{ $connection->database_type->brand }}
                        </td>
                        <td>{{ $connection->host }}</td>
                        <td>{{ $connection->db_name }}</td>
                        <td class="status-cell" data-id="{{ $connection->id }}">
                            <span class="spinner-border spinner-border-sm text-secondary me-1" role="status"></span>
                            <span>Connecting...</span>
                        </td>
                        <td>
                            <a href="{{ route('database_connection.show', $connection->id) }}" class="btn btn-sm btn-info">Info</a>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $connection->id }}">Edit</button>
                            <form action="{{ route('database_connection.destroy', $connection) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this connection?')">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $connection->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('database_connection.update', $connection) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Connection</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <select name="database_type_id" class="form-select mb-2" required>
                                            @foreach ($databaseTypes as $type)
                                                <option value="{{ $type->id }}"
                                                    data-port="{{ $type->default_port }}"
                                                    data-charset="{{ $type->default_charset }}"
                                                    data-collation="{{ $type->default_collation }}"
                                                    data-schema="{{ $type->default_schema }}"
                                                    {{ isset($connection) && $type->id == $connection->database_type_id ? 'selected' : '' }}>
                                                    {{ $type->brand }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <textarea name="description" class="form-control mb-2" placeholder="Description">{{ $connection->description }}</textarea>
                                        <input type="text" name="host" value="{{ $connection->host }}" class="form-control mb-2" placeholder="Host" required>
                                        <input type="text" name="username" value="{{ $connection->username }}" class="form-control mb-2" placeholder="Username" required>
                                        <input type="password" name="password" value="{{ $connection->password }}" class="form-control mb-2" placeholder="Password" required>
                                        <input type="text" name="db_name" value="{{ $connection->db_name }}" class="form-control mb-2" placeholder="Database Name" required>
                                        <input type="text" name="port" value="{{ $connection->port }}" class="form-control mb-2" placeholder="Port (optional)">
                                        <input type="text" name="charset" value="{{ $connection->charset }}" class="form-control mb-2" placeholder="Charset (optional)">
                                        <input type="text" name="collation" value="{{ $connection->collation }}" class="form-control mb-2" placeholder="Collation (optional)">
                                        <input type="text" name="schema" value="{{ $connection->schema }}" class="form-control mb-2" placeholder="Schema (optional)">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @endforeach
            </tbody>
        </table>

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('database_connection.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">New Database Connection</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <select name="database_type_id" class="form-select mb-2" required>
                                <option value="">-- Select Database Type --</option>
                                @foreach ($databaseTypes as $type)
                                    <option value="{{ $type->id }}"
                                        data-port="{{ $type->default_port }}"
                                        data-charset="{{ $type->default_charset }}"
                                        data-collation="{{ $type->default_collation }}"
                                        data-schema="{{ $type->default_schema }}"
                                        {{ isset($connection) && $type->id == $connection->database_type_id ? 'selected' : '' }}>
                                        {{ $type->brand }}
                                    </option>
                                @endforeach
                            </select>
                            <textarea name="description" class="form-control mb-2" placeholder="Description (optional)"></textarea>
                            <input type="text" name="host" class="form-control mb-2" placeholder="Host" required>
                            <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
                            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                            <input type="text" name="db_name" class="form-control mb-2" placeholder="Database Name" required>
                            <input type="text" name="port" class="form-control mb-2" placeholder="Port (optional)">
                            <input type="text" name="charset" class="form-control mb-2" placeholder="Charset (optional)">
                            <input type="text" name="collation" class="form-control mb-2" placeholder="Collation (optional)">
                            <input type="text" name="schema" class="form-control mb-2" placeholder="Schema (optional)">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.status-cell').forEach(cell => {
                const id = cell.getAttribute('data-id');
                const url = `/connecting_database/${id}`; // Pastikan route ini tersedia

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        cell.innerHTML = '';

                        if (data.status === 'success') {
                            cell.innerHTML = `<span class="text-success">
                                <i class="bi bi-check-circle-fill"></i> Connected
                            </span>`;
                        } else {
                            cell.innerHTML = `<span class="text-danger">
                                <i class="bi bi-x-circle-fill"></i> Failed
                            </span>`;
                        }
                    })
                    .catch(() => {
                        cell.innerHTML = `<span class="text-danger">
                            <i class="bi bi-x-circle-fill"></i> Error
                        </span>`;
                    });
            });
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle create form
        const createSelect = document.querySelector('#createModal select[name="database_type_id"]');
        if (createSelect) {
            createSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];

                // const portInput = document.getElementById('#createModal input[name="port"]');
                const portInput = document.querySelector('#createModal input[name="port"]');
                const charsetInput = document.querySelector('#createModal input[name="charset"]');
                const collationInput = document.querySelector('#createModal input[name="collation"]');
                const schemaInput = document.querySelector('#createModal input[name="schema"]');

                if (portInput) portInput.value = selected.dataset.port || '';
                if (charsetInput) charsetInput.value = selected.dataset.charset || '';
                if (collationInput) collationInput.value = selected.dataset.collation || '';
                if (schemaInput) schemaInput.value = selected.dataset.schema || '';
            });
        }

        // Handle all edit modals
        document.querySelectorAll('div[id^="editModal"] select[name="database_type_id"]').forEach(select => {
            select.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const form = this.closest('form');

                const portInput = form.querySelector('input[name="port"]');
                const charsetInput = form.querySelector('input[name="charset"]');
                const collationInput = form.querySelector('input[name="collation"]');
                const schemaInput = form.querySelector('input[name="schema"]');

                if (portInput) portInput.value = selected.dataset.port || '';
                if (charsetInput) charsetInput.value = selected.dataset.charset || '';
                if (collationInput) collationInput.value = selected.dataset.collation || '';
                if (schemaInput) schemaInput.value = selected.dataset.schema || '';
            });
        });
    });
</script>


@endsection
