<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fishing Management System</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
            /* Transisi halus saat ganti tema */
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .sidebar {
            width: 220px;
            min-height: 100vh;
            z-index: 1000;
            transition: all 0.1s;
        }
        
        .content {
            margin-left: 220px;
            transition: margin-left .3s;
            min-height: 100vh;
        }

        /* Responsive Sidebar */
        .sidebar.collapsing,
        .sidebar.collapse:not(.show) {
            width: 0;
        }
        .sidebar.collapse:not(.show) + .content {
            margin-left: 0;
        }

        /* Nav Link Styling */
        .sidebar .nav-link {
            transition: all 0.2s;
            color: var(--bs-body-color); /* Otomatis ikut tema */
            display: flex;
            align-items: center;
        }

        /* Hover effect adaptif */
        .sidebar .nav-link:hover {
            background-color: var(--bs-tertiary-bg);
            color: var(--bs-primary) !important;
            border-radius: 6px; 
        }

        /* Active state */
        .sidebar .nav-link.active {
            background-color: var(--bs-primary) !important;
            color: #fff !important;
            font-weight: 600;
            border-radius: 6px;
        }

        .navbar {
            transition: background-color 0.1s ease;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-body-tertiary"> {{-- SIDEBAR --}}
@auth
<div class="sidebar bg-body border-end position-fixed collapse show" id="sidebarMenu">
    <div class="p-3 border-bottom text-center">
        <strong class="text-body">Admin System</strong>
        <div class="small text-muted">Fishing Pro</div>
    </div>

    <div class="p-2">
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2 {{ request()->routeIs('dashboard') ? '' : 'opacity-75' }}"></i> 
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('spot.index') }}"
                class="nav-link {{ request()->routeIs('spot.*') ? 'active' : '' }}">
                    <i class="fas fa-table me-2 {{ request()->routeIs('spot.*') ? '' : 'opacity-75' }}"></i> 
                    <span>Spot</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('produk.index') }}"
                class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                    <i class="fas fa-utensils me-2 {{ request()->routeIs('produk.*') ? '' : 'opacity-75' }}"></i> 
                    <span>Produk</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('transaksi.histori') }}"
                class="nav-link {{ request()->routeIs('transaksi.histori') ? 'active' : '' }}">
                    <i class="fas fa-history me-2 {{ request()->routeIs('transaksi.histori') ? '' : 'opacity-75' }}"></i> 
                    <span>Transaksi</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('payment-methods.index') }}"
                class="nav-link {{ request()->routeIs('payment-methods.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card me-2 {{ request()->routeIs('payment-methods.*') ? '' : 'opacity-75' }}"></i> 
                    <span>Payment</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('members.index') }}"
                class="nav-link {{ request()->routeIs('members.index') ? 'active' : '' }}">
                    <i class="fas fa-users me-2 {{ request()->routeIs('members.index') ? '' : 'opacity-75' }}"></i> 
                    <span>Members</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('analytics.index') }}"
                class="nav-link {{ request()->routeIs('analytics.index') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar me-2 {{ request()->routeIs('analytics.index') ? '' : 'opacity-75' }}"></i> 
                    <span>Analytics</span>
                </a>
            </li>

            <li class="nav-item mt-2">
                <a href="{{ route('settings.index') }}"
                class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cogs me-2 {{ request()->routeIs('settings.*') ? '' : 'opacity-75' }}"></i> 
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="mt-auto p-3 border-top">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger btn-sm w-100">
                <i class="fas fa-sign-out-alt me-1"></i> Keluar
            </button>
        </form>
    </div>
</div>
@endauth

{{-- CONTENT --}}
<div class="content">

    {{-- TOP NAVBAR --}}
    <nav class="navbar bg-body border-bottom px-3 sticky-top">
        @auth
        <button class="btn btn-outline-secondary border-0 btn-sm"
                data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu">
            <i class="fas fa-bars"></i>
        </button>

        <div class="d-flex align-items-center ms-3">
            <h6 class="mb-0 me-2 text-body d-none d-md-block">{{ auth()->user()->name }}</h6>
            @if(auth()->user()->profile_image)
                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" 
                    alt="Foto Profil" 
                    class="rounded-circle border" 
                    width="32" height="32" style="object-fit: cover;">
            @else
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
        </div>

        <div class="ms-auto">
            <button id="theme-toggle" class="btn btn-link text-body shadow-none">
                <i class="fas fa-moon" id="theme-icon"></i>
            </button>
        </div>
        @endauth

        @guest
        <div class="ms-auto">
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-4">Login</a>
        </div>
        @endguest
    </nav>

    <main class="container-fluid py-4">
        @yield('content')
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

<script>
    (function() {
        // Ambil tema dari Laravel (Database)
        const userTheme = "{{ auth()->check() ? auth()->user()->theme : 'light' }}";
        const htmlElement = document.documentElement;
        const themeIcon = document.getElementById('theme-icon');

        function applyTheme(theme) {
            let targetTheme = theme;
            if (theme === 'system') {
                targetTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            htmlElement.setAttribute('data-bs-theme', targetTheme);
            
            // Update Icon
            if (themeIcon) {
                if (targetTheme === 'dark') {
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                } else {
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                }
            }
        }

        // Jalankan saat load
        applyTheme(userTheme);

        const themeToggleBtn = document.getElementById('theme-toggle');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                const current = htmlElement.getAttribute('data-bs-theme');
                const next = current === 'dark' ? 'light' : 'dark';
                
                applyTheme(next);
                
                // Simpan ke database via AJAX
                fetch("{{ route('settings.updateTheme') }}", {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ theme: next })
                }).then(response => {
                    if(!response.ok) console.error("Gagal simpan ke database");
                });
            });
        }
    })();
</script>
</body>
</html>