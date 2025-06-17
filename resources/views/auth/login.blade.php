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
    <main class="container py-3">
        <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="card shadow-sm p-4" style="min-width: 350px; max-width: 400px; width: 100%;">
                <div class="text-center mb-4">
                    {{-- <i class="bi bi-shield-lock-fill fs-1 text-primary"></i> --}}
                    <i class="bi bi-hdd-fill me-1 fs-1 text-primary"></i>
                    <h5 class="mt-2 fw-bold">{{ env('APP_NAME') }}</h5>
                </div>

                <form method="POST" action="{{ route('login_process') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="Enter email"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Enter password"
                            required
                        >
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">© 2025 {{ env('APP_NAME') }}</small>
                </div>
            </div>
        </div>
    </main>
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
</body>
