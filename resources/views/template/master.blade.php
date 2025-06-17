<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Styles -->
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

        .navbar-gradient,
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

        .floating-clock {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.9rem;
            padding: 6px 12px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            animation: pulse 3s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
            }

            50% {
                box-shadow: 0 0 12px rgba(255, 255, 255, 0.8);
            }

            100% {
                box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
            }
        }

        .navbar-white-gradient {
            background: linear-gradient(to right, #ffffff, #f5f5f5);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid #ddd;
        }

        .navbar-white-gradient .navbar-brand,
        .navbar-white-gradient .nav-link,
        .navbar-white-gradient .dropdown-toggle,
        .navbar-white-gradient .bi {
            color: #222 !important;
        }

        .navbar-white-gradient .nav-link:hover,
        .navbar-white-gradient .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .footer-white-gradient {
            background: linear-gradient(to right, #ffffff, #f5f5f5);
            border-top: 1px solid #ddd;
            color: #333;
        }

        .footer-white-gradient small {
            color: #444;
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.6); /* semi-transparan putih */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            color: #222;
        }
        .glassmorphism small {
            color: #333;
        }

        .bi {
            color: #000 !important;
        }

        /* Override default tombol Bootstrap agar semua menjadi btn-outline-dark */
        .btn {
            background-color: transparent !important;
            color: #212529 !important; /* Bootstrap dark color */
            border: 1px solid #212529 !important;
        }

        .btn:hover {
            background-color: #212529 !important;
            color: #fff !important;
        }

    </style>
</head>

<body>
    {{-- <div class="floating-clock" id="floatingClock">--:--:--</div> --}}

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light glassmorphism shadow-sm">
        <div class="container-fluid">
            <i class="bi bi-hdd-fill me-1 fs-1"></i>
            <a class="navbar-brand fw-bold" href="{{ route('welcome') }}">{{ env('APP_NAME') }}</a>
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
                                @csrf
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
    <footer class="footer-white-gradient text-center py-3 mt-auto">
        <small>&copy; 2025 {{ env('APP_NAME') }}. All rights reserved.</small>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function updateFloatingClock() {
            const now = new Date();
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const day = days[now.getDay()];
            const time = now.toLocaleTimeString();
            const clock = document.getElementById('floatingClock');
            if (clock) {
                clock.innerText = `${day}, ${time}`;
            }
        }

        updateFloatingClock();
        setInterval(updateFloatingClock, 1000);
    </script>

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
                customClass: { popup: 'text-start' }
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
                            showToast(
                                'info',
                                'No scheduled backups executed.',
                                '',
                                true, // auto close
                                1500  // 2 detik
                            );
                            return;
                        }

                        data.results.forEach(result => {
                            if (result.status === 'success') {
                                showToast(
                                    'success',
                                    `✅ Backup completed for schedule #${result.schedule_id}`,
                                    `<a href="${result.download_url}" class="btn btn-sm btn-light mt-2" onclick="Swal.close()">Download</a>`,
                                    false
                                );
                            } else if (result.status === 'fail') {
                                showToast(
                                    'error',
                                    `❌ Backup failed for schedule #${result.schedule_id}`,
                                    '',
                                    true,
                                    3000
                                );
                            } else if (result.status === 'skipped') {
                                showToast(
                                    'info',
                                    `⏭️ Backup skipped for schedule #${result.schedule_id}`,
                                    '',
                                    true,
                                    3000
                                );
                            }
                        });
                    } else if (data.status === 'skipped') {
                        showToast(
                            'info',
                            data.message || 'No scheduled backups to run.',
                            '',
                            true,
                            2000
                        );
                    } else {
                        showToast(
                            'error',
                            data.message || 'Failed to process backup',
                            '',
                            true,
                            3000
                        );
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('error', 'Failed to connect to backup route.', '', true, 3000);
                });
        }

        /**
         * Menampilkan toast dengan pilihan apakah auto-close atau tidak
         * @param {string} icon - success | error | info
         * @param {string} title - judul toast
         * @param {string} htmlContent - konten HTML tambahan
         * @param {boolean} autoClose - apakah toast ditutup otomatis
         * @param {number} timeout - durasi timer dalam ms (default 3000 jika autoClose aktif)
         */
        function showToast(icon, title, htmlContent = '', autoClose = false, timeout = 3000) {
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: icon,
                title: title,
                html: htmlContent,
                showConfirmButton: !autoClose,
                confirmButtonText: 'Close',
                allowOutsideClick: !autoClose,
                allowEscapeKey: true,
                timer: autoClose ? timeout : undefined,
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
