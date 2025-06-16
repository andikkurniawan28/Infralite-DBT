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
                Backup Files
            </li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Backup Files</h4>
        <div class="btn-group" role="group" aria-label="Basic example">
            <button class="btn btn-danger me-2" onclick="deleteSelected()">🗑 Delete Selected</button>
            <button class="btn btn-outline-danger" onclick="deleteAll()">🗑 Delete All</button>
            <button class="btn btn-outline-secondary ms-2" onclick="loadFiles()">⟳ Refresh</button>
        </div>
    </div>

    <table class="table table-bordered bg-white shadow-sm" id="files-table">
        <thead class="table-light">
            <tr>
                <th><input type="checkbox" id="select-all" onchange="toggleSelectAll(this)"></th>
                <th>Filename</th>
                <th>Size</th>
                <th>Last Modified</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            {{-- File rows will be injected here --}}
        </tbody>
    </table>

</main>
@endsection

@section('script')
<script>
    function formatBytes(bytes) {
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        if (bytes === 0) return '0 Byte';
        const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }

    function formatDate(timestamp) {
        const date = new Date(timestamp * 1000);
        return date.toLocaleString();
    }

    function loadFiles() {
        fetch('{{ route('backup_file.data') }}')
            .then(response => response.json())
            .then(files => {
                const tbody = document.querySelector('#files-table tbody');
                tbody.innerHTML = '';
                files.forEach(file => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                        <td><input type="checkbox" class="file-checkbox" value="${file.name}"></td>
                        <td>${file.name}</td>
                        <td>${formatBytes(file.size)}</td>
                        <td>${formatDate(file.last_modified)}</td>
                        <td>
                            <a href="${file.url}" class="btn btn-sm btn-success" download>
                                <i class="bi bi-download"></i> Download
                            </a>
                        </td>
                    `;

                    tbody.appendChild(tr);
                });
            })
            .catch(err => {
                alert('Failed to load backup files.');
                console.error(err);
            });
    }

    function toggleSelectAll(source) {
        document.querySelectorAll('.file-checkbox').forEach(cb => cb.checked = source.checked);
    }

    function deleteSelected() {
        const selected = Array.from(document.querySelectorAll('.file-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return alert('No files selected.');

        if (!confirm(`Are you sure you want to delete ${selected.length} selected files?`)) return;

        fetch('{{ route('backup_file.bulk_delete') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ files: selected })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            loadFiles();
        })
        .catch(err => console.error(err));
    }

    function deleteAll() {
        if (!confirm('Are you sure you want to delete ALL backup files?')) return;

        fetch('{{ route('backup_file.delete_all') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            loadFiles();
        })
        .catch(err => console.error(err));
    }

    document.addEventListener('DOMContentLoaded', loadFiles);
</script>
@endsection
