<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MediCare Pro')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @if(isset($useCharts) && $useCharts)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif
    @stack('styles')
</head>
<body>
    <div class="app">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <svg viewBox="0 0 36 36" fill="none"><rect width="36" height="36" rx="8" fill="#0891b2"/><path d="M18 8v20M8 18h20" stroke="#fff" stroke-width="3" stroke-linecap="round"/></svg>
                    <div>
                        <h1>MediCare</h1>
                        <span>@yield('sidebar-subtitle', 'Gestion Hospitalière')</span>
                    </div>
                </div>
            </div>
            <nav class="sidebar-nav">
                @yield('sidebar-nav')
            </nav>
            <div class="sidebar-footer">
                <div class="user-box">
                    <div class="user-avatar" style="background:@yield('user-color', '#0891b2');">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <div class="user-info">
                        <h4>{{ Auth::user()->name }}</h4>
                        <span>{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm" style="width:100%;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main -->
        <main class="main">
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                    </button>
                    <h1 class="header-title">@yield('header-title', 'Tableau de bord')</h1>
                </div>
                <div class="header-right">
                    @yield('header-right')
                </div>
            </header>

            <div class="page">
                @if(session('success'))
                <div class="alert-item" style="background:var(--success-light);margin-bottom:20px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    <div class="alert-text"><div class="alert-title">{{ session('success') }}</div></div>
                </div>
                @endif

                @if(session('error'))
                <div class="alert-item" style="margin-bottom:20px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                    <div class="alert-text"><div class="alert-title">{{ session('error') }}</div></div>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const toggleSidebar = () => document.getElementById('sidebar').classList.toggle('open');

        // Close modal on overlay click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) e.target.classList.remove('active');
        });

        const openModal = (id) => document.getElementById(id).classList.add('active');
        const closeModal = (id) => document.getElementById(id).classList.remove('active');

        // CSRF token for AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    </script>
    @stack('scripts')
</body>
</html>
