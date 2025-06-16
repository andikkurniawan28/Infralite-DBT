<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #e0f7fa, #e1f5fe, #e8eaf6);
        }

        main {
            flex: 1;
        }

        .navbar-gradient {
            background: linear-gradient(to right, #0d47a1, #1976d2);
        }

        .footer-gradient {
            background: linear-gradient(to right, #0d47a1, #1976d2);
        }

        .card-menu:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: 0.3s ease;
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 2.5rem;
            color: #1976d2;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-gradient shadow-sm">
        <div class="container-fluid">
            <i class="bi bi-shield-lock-fill fs-1 text-white"></i>
            <a class="navbar-brand fw-bold" href="{{ route('welcome') }}">{{ env('APP_NAME') }}</a>
            {{-- <span id="current-time" class="text-white ms-3 small"></span> --}}
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-4 me-2"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf @method('POST')
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-fluid py-3">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-gradient text-white text-center py-3 mt-auto">
        <small>&copy; 2025 {{ env('APP_NAME') }}. All rights reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 1000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                timer: 1000,
                showConfirmButton: false
            });
        @endif

        @if ($errors->any())
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += `- {{ $error }}\n`;
            @endforeach
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: errorMessages,
                customClass: {
                    popup: 'text-start'
                }
            });
        @endif
    </script>
    <script>
        function checkScheduledBackup() {
            fetch(`{{ route('scheduled_backup.process') }}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'done') {
                        if (data.results.length === 0) {
                            showToast('info', 'No scheduled backups executed.');
                            return;
                        }

                        data.results.forEach(result => {
                            if (result.status === 'success') {
                                showToast(
                                    'success',
                                    `✅ Backup completed for schedule #${result.schedule_id}`,
                                    `<a href="${result.download_url}" class="btn btn-sm btn-light mt-2" onclick="Swal.close()">Download</a>`,
                                    false // ❌ Jangan auto-close
                                );
                            } else if (result.status === 'fail') {
                                showToast('error', `❌ Backup failed for schedule #${result.schedule_id}`, '', true);
                            } else if (result.status === 'skipped') {
                                showToast('info', `⏭️ Backup skipped for schedule #${result.schedule_id}`, '', true);
                            }
                        });
                    } else if (data.status === 'skipped') {
                        showToast('info', data.message || 'No scheduled backups to run.', '', true);
                    } else {
                        showToast('error', data.message || 'Failed to process backup', '', true);
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('error', 'Failed to connect to backup route.', '', true);
                });
        }

        /**
         * Menampilkan toast dengan pilihan apakah auto-close atau tidak
         * @param {string} icon - success | error | info
         * @param {string} title - judul toast
         * @param {string} htmlContent - konten HTML tambahan
         * @param {boolean} autoClose - apakah toast ditutup otomatis
         */
        function showToast(icon, title, htmlContent = '', autoClose = false) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: title,
                html: htmlContent,
                showConfirmButton: !autoClose,
                confirmButtonText: 'Close',
                allowOutsideClick: !autoClose,
                allowEscapeKey: true,
                timer: autoClose ? 3000 : undefined,
                timerProgressBar: autoClose
            });
        }

        // Jalankan pertama kali
        checkScheduledBackup();
        // Jalankan ulang setiap 1 menit
        setInterval(checkScheduledBackup, 60 * 1000);
    </script>

    @yield('script')

</body>

</html>
