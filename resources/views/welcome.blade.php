<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa; /* Light background for welcome page */
            color: #212529;
        }
        .navbar {
            background-color: #ffffff; /* White background for navbar */
            box-shadow: 0 2px 4px rgba(0,0,0,.04);
        }
        .welcome-section {
            flex-grow: 1; /* Allows the main content to take up available space */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
            text-align: center;
        }
        .btn-primary {
            background-color: #6610f2; /* Custom primary color (indigo-like) */
            border-color: #6610f2;
        }
        .btn-primary:hover {
            background-color: #560ce0;
            border-color: #560ce0;
        }
        .btn-outline-primary {
            color: #6610f2;
            border-color: #6610f2;
        }
        .btn-outline-primary:hover {
            background-color: #6610f2;
            color: #fff;
            border-color: #6610f2;
        }
        .footer {
            padding: 20px 0;
            background-color: #e9ecef; /* Light gray background for footer */
            color: #6c757d;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid container-xl d-flex justify-content-end">
            @if (Route::has('login'))
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary me-2">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary me-2">Log in</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                            </li>
                        @endif
                    @endauth
                </ul>
            @endif
        </div>
    </nav>

    <div class="welcome-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    {{-- Ganti dengan logo aplikasi Anda, atau biarkan kosong jika tidak ada --}}
                    <svg class="mx-auto mb-4" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>

                    <h1 class="display-4 fw-bold mb-3">Selamat Datang!</h1>
                    <p class="lead text-muted mb-4">
                        Aplikasi manajemen restoran Anda yang modern dan efisien. Login untuk memulai pengelolaan meja, transaksi, dan produk Anda.
                    </p>

                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5">
                            <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 1a.75.75 0 01.75.75v10.5a.75.75 0 01-1.5 0V1.75A.75.75 0 0110 1z"/>
                                <path fill-rule="evenodd" d="M3.25 10a.75.75 0 01.75-.75h1.25a.75.75 0 010 1.5H4a.75.75 0 01-.75-.75zm12.5-.75h1.25a.75.75 0 010 1.5h-1.25a.75.75 0 01-.75-.75zm-7-1.25a.75.75 0 01.75-.75h4.5a.75.75 0 010 1.5h-4.5a.75.75 0 01-.75-.75zM15 15.25a.75.75 0 01-.75.75H5.75a.75.75 0 010-1.5h8.5a.75.75 0 01.75.75z"/>
                            </svg>
                            Login Sekarang
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-5">
                                Daftar Baru
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer text-center mt-auto">
        <div class="container">
            Dibuat dengan ❤️ oleh Tim Anda. &copy; {{ date('Y') }}
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>