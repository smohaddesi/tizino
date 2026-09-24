<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'پنل دانش‌آموز') - تیزینو</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --tz-sidebar-bg: #0f3d3d;
            --tz-sidebar-bg-active: #17605e;
            --tz-sidebar-text: #cfe8e6;
            --tz-sidebar-text-muted: #86b3b0;
            --tz-accent: #fd7e14;
            --tz-sidebar-width: 270px;
        }

        body {
            background-color: #f3f5f6;
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        .app-sidebar {
            width: var(--tz-sidebar-width);
            background: var(--tz-sidebar-bg);
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1.1rem;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 1040;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            font-weight: 800;
            font-size: 1.2rem;
            color: #fff;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .sidebar-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding-bottom: 1.25rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .sidebar-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--tz-accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: .6rem;
            overflow: hidden;
        }

        .sidebar-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-profile-name {
            color: #fff;
            font-weight: 600;
            font-size: .95rem;
        }

        .sidebar-profile-role {
            color: var(--tz-sidebar-text-muted);
            font-size: .78rem;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: .35rem;
            flex: 1;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .65rem 1rem;
            border-radius: .6rem;
            color: var(--tz-sidebar-text);
            text-decoration: none;
            font-weight: 500;
            font-size: .92rem;
            transition: background .15s, color .15s;
        }

        .sidebar-nav a i {
            font-size: 1.05rem;
            width: 1.3rem;
            text-align: center;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, .06);
            color: #fff;
        }

        .sidebar-nav a.active {
            background: var(--tz-sidebar-bg-active);
            color: #fff;
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, .08);
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .sidebar-footer form button {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }

        .app-main {
            flex: 1;
            margin-right: var(--tz-sidebar-width);
            min-width: 0;
        }

        .app-topbar {
            display: none;
            align-items: center;
            gap: 1rem;
            padding: .75rem 1rem;
            background: var(--tz-sidebar-bg);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .app-topbar-brand {
            font-weight: 700;
            color: #fff;
        }

        #sidebarToggle {
            background: none;
            border: none;
            font-size: 1.4rem;
            line-height: 1;
            color: #fff;
        }

        .app-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .4);
            z-index: 1035;
        }

        .app-backdrop.show {
            display: block;
        }

        @media (max-width: 991.98px) {
            .app-sidebar {
                transform: translateX(100%);
            }

            .app-sidebar.open {
                transform: translateX(0);
            }

            .app-main {
                margin-right: 0;
            }

            .app-topbar {
                display: flex;
            }
        }

        @stack('styles')
    </style>
</head>
<body>
    <div class="app-shell">
        <aside class="app-sidebar" id="appSidebar">
            <div class="sidebar-brand">تیزینو</div>

            <div class="sidebar-profile">
                <div class="sidebar-avatar">
                    @if (auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="عکس پروفایل">
                    @else
                        {{ \Illuminate\Support\Str::of(auth()->user()->name)->substr(0, 1) }}
                    @endif
                </div>
                <div class="sidebar-profile-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-profile-role">دانش‌آموز</div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> داشبورد
                </a>
                <a href="{{ route('bank.index') }}" class="{{ request()->routeIs('bank.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-bookmark"></i> بانک سؤال
                </a>
                <a href="{{ route('subscriptions.plans') }}" class="{{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i> اشتراک من
                </a>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i> پروفایل من
                </a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> خروج
                    </button>
                </form>
            </div>
        </aside>

        <div class="app-backdrop" id="appBackdrop"></div>

        <div class="app-main">
            <header class="app-topbar">
                <button type="button" id="sidebarToggle" aria-label="باز کردن منو">☰</button>
                <span class="app-topbar-brand">تیزینو</span>
            </header>

            <main class="app-content">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('appSidebar');
        const backdrop = document.getElementById('appBackdrop');
        const toggleBtn = document.getElementById('sidebarToggle');

        function closeSidebar() {
            sidebar.classList.remove('open');
            backdrop.classList.remove('show');
        }

        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('show');
        });

        backdrop?.addEventListener('click', closeSidebar);
    </script>

    @stack('scripts')
</body>
</html>
