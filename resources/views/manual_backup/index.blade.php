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
                <li class="breadcrumb-item active" aria-current="page">Manual Backup</li>
            </ol>
        </nav>

        <h4 class="mb-3">Manual Backup</h4>

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
                                    {{ $connection->db_name }}
                                    @php echo '@';@endphp
                                    {{ $connection->host }}
                                    -
                                    {{ $connection->database_type->brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-outline-dark" id="backupButton">
                        <i class="bi bi-play-circle me-1"></i> Start Backup
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
            let isBackingUp = false;

            // Prevent tab from being closed during backup process
            window.addEventListener("beforeunload", function(e) {
                if (isBackingUp) {
                    const confirmationMessage =
                        "Backup is in progress. Do not close or refresh this page.";
                    e.returnValue = confirmationMessage;
                    return confirmationMessage;
                }
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const connId = document.getElementById('database_connection_id').value;
                if (!connId) {
                    alert('Please select a database connection.');
                    return;
                }

                // Notification before backup starts
                Swal.fire({
                    icon: 'info',
                    title: 'Backup Started',
                    html: `
                        This process may take some time depending on the size of the database.<br>
                        Please ensure that the <strong>database credentials</strong> are valid.<br><br>
                        <em>Please wait until the process is complete and the file is downloaded automatically.</em>
                    `,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timer: 4000
                });

                isBackingUp = true;
                button.disabled = true;
                button.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Processing...';

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

                    button.innerHTML = '<i class="bi bi-check-circle me-1"></i> Done';
                } catch (err) {
                    alert('Backup failed: ' + err.message);
                } finally {
                    isBackingUp = false;
                    setTimeout(() => {
                        button.disabled = false;
                        button.innerHTML =
                            '<i class="bi bi-play-circle me-1"></i> Start Backup';
                    }, 3000);
                }
            });
        });
    </script>
@endsection
