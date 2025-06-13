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
            <form id="backupForm" action="{{ route('manual_backup.process') }}" method="POST">
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

                <button type="submit" class="btn btn-primary" id="backupButton">
                    <i class="bi bi-play-circle me-1"></i> Process Backup
                </button>
            </form>

        </div>
    </div>

</main>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('backupForm');
    const button = document.getElementById('backupButton');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const connId = document.getElementById('database_connection_id').value;
        if (!connId) {
            alert('Please select a database connection.');
            return;
        }

        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Processing...';

        const formData = new FormData();
        formData.append('database_connection_id', connId);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            const res = await fetch(`{{ route('manual_backup.process') }}`, {
                method: 'POST',
                body: formData
            });

            if (!res.ok) throw new Error('Backup failed.');

            const blob = await res.blob();
            const disposition = res.headers.get("Content-Disposition");
            const filename = disposition?.match(/filename="?([^"]+)"?/)?.[1] || 'backup.sql';

            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            button.innerHTML = '<i class="bi bi-check-circle me-1"></i> Selesai';
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-play-circle me-1"></i> Process Backup';
            }, 3000);
        } catch (err) {
            alert('Gagal melakukan backup: ' + err.message);
            button.disabled = false;
            button.innerHTML = '<i class="bi bi-play-circle me-1"></i> Process Backup';
        }
    });
});
</script>
@endsection

