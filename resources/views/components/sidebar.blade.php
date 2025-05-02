<!-- resources/views/partials/sidebar.blade.php -->
<div class="main-sidebar sidebar-style-2" id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}" class="logo-wrapper">
                <img src="{{ asset('img/attendify-white.png') }}" alt="Attendify" width="80" class="mr-2 logo-img">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('home') }}" class="logo-sm-wrapper">
                <img src="{{ asset('img/attendify-home.png') }}" alt="Attendify" width="30" class="logo-sm-img">
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">Main Navigation</li>
            <li class="nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="fas fa-fire"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Management</li>
            <li class="nav-item {{ Request::routeIs('users.*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('companies.*') ? 'active' : '' }}">
                <a href="{{ route('companies.show', 1) }}" class="nav-link">
                    <i class="fas fa-building"></i>
                    <span>Company</span>
                </a>
            </li>

            <li class="menu-header">Attendance</li>
            <li class="nav-item {{ Request::routeIs('attendances.*') ? 'active' : '' }}">
                <a href="{{ route('attendances.index') }}" class="nav-link">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Attendances</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('broadcasts.*') ? 'active' : '' }}">
                <a href="{{ route('broadcasts.index') }}" class="nav-link">
                    <i class="fas fa-broadcast-tower"></i>
                    <span>Broadcast</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('qr_absens.*') ? 'active' : '' }}">
                <a href="{{ route('qr_absens.index') }}" class="nav-link">
                    <i class="fas fa-qrcode"></i>
                    <span>QR Absen</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('holidays.*') ? 'active' : '' }}">
                <a href="{{ route('holidays.index') }}" class="nav-link">
                    <i class="fas fa-calendar-day"></i>
                    <span>Holiday</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('time_offs.*') ? 'active' : '' }}">
                <a href="{{ route('time_offs.index') }}" class="nav-link">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Time Off</span>
                </a>
            </li>

            <li class="menu-header">Account</li>
            <li class="nav-item">
                <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit()">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
                <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>
</div>

<style>
    :root {
        --primary: #6366f1;
        --primary-hover: #4f46e5;
        --secondary: #2f3c7e;
        --dark: #0f172a;
        --light: #f8fafc;
        --grey-light: #f1f5f9;
        --grey: #e2e8f0;
        --danger: #ef4444;
        --sidebar-width: 250px;
        --sidebar-mini-width: 70px;
        --transition-speed: 0.3s;
    }

    /* Sidebar Styles */
    .main-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: var(--sidebar-width);
        background: linear-gradient(180deg, var(--secondary) 0%, var(--dark) 100%);
        z-index: 890;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all var(--transition-speed) ease;
    }

    .main-sidebar .sidebar-brand {
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        height: 70px;
        transition: all var(--transition-speed) ease;
    }

    .main-sidebar .sidebar-brand a {
        color: var(--light);
        text-decoration: none;
        font-size: 1.2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
    }

    .logo-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition-speed) ease;
    }

    .logo-img {
        transition: all var(--transition-speed) ease;
        filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.2));
    }

    .sidebar-brand-sm {
        display: none;
    }

    .sidebar-menu {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .menu-header {
        padding: 15px 20px 5px;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .sidebar-menu .nav-item {
        position: relative;
    }

    .sidebar-menu .nav-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background-color: var(--primary);
        border-radius: 0 4px 4px 0;
    }

    .sidebar-menu .nav-link {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        color: rgba(255, 255, 255, 0.7);
        transition: all var(--transition-speed) ease;
        position: relative;
        overflow: hidden;
    }

    .sidebar-menu .nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        background-color: rgba(255, 255, 255, 0.05);
        transform: translateX(-100%);
        transition: transform var(--transition-speed) ease;
    }

    .sidebar-menu .nav-link:hover::before {
        transform: translateX(0);
    }

    .sidebar-menu .nav-link i {
        width: 20px;
        margin-right: 10px;
        font-size: 16px;
        position: relative;
        z-index: 2;
        transition: all var(--transition-speed) ease;
    }

    .sidebar-menu .nav-link span {
        position: relative;
        z-index: 2;
        transition: all var(--transition-speed) ease;
    }

    .sidebar-menu .nav-item.active .nav-link {
        color: var(--light);
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar-menu .nav-link:hover {
        color: var(--light);
    }

    .sidebar-menu .nav-link.text-danger {
        color: var(--danger) !important;
    }

    .sidebar-menu .nav-link.text-danger:hover {
        background-color: rgba(239, 68, 68, 0.1);
    }

    /* Sidebar Mini */
    .sidebar-mini .main-sidebar {
        width: var(--sidebar-mini-width);
    }

    .sidebar-mini .main-sidebar .sidebar-brand {
        justify-content: center;
        padding: 15px;
    }

    .sidebar-mini .main-sidebar .sidebar-brand-sm {
        display: block;
    }

    .sidebar-mini .main-sidebar .sidebar-brand {
        display: none;
    }

    .sidebar-mini .main-sidebar .sidebar-menu .menu-header {
        padding: 15px 0;
        text-align: center;
        white-space: nowrap;
    }

    .sidebar-mini .main-sidebar .sidebar-menu .nav-link span {
        opacity: 0;
        transform: translateX(10px);
    }

    .sidebar-mini .main-sidebar .sidebar-menu .nav-link {
        padding: 12px 0;
        justify-content: center;
    }

    .sidebar-mini .main-sidebar .sidebar-menu .nav-link i {
        margin-right: 0;
    }

    .sidebar-mini .main-sidebar:hover {
        width: var(--sidebar-width);
    }

    .sidebar-mini .main-sidebar:hover .sidebar-brand {
        display: flex;
    }

    .sidebar-mini .main-sidebar:hover .sidebar-brand-sm {
        display: none;
    }

    .sidebar-mini .main-sidebar:hover .sidebar-menu .menu-header {
        padding: 15px 20px 5px;
        text-align: left;
    }

    .sidebar-mini .main-sidebar:hover .sidebar-menu .nav-link {
        padding: 12px 20px;
        justify-content: flex-start;
    }

    .sidebar-mini .main-sidebar:hover .sidebar-menu .nav-link i {
        margin-right: 10px;
    }

    .sidebar-mini .main-sidebar:hover .sidebar-menu .nav-link span {
        opacity: 1;
        transform: translateX(0);
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .sidebar-menu .nav-item {
        animation: fadeIn 0.3s ease forwards;
    }

    .sidebar-menu .nav-item:nth-child(1) { animation-delay: 0.05s; }
    .sidebar-menu .nav-item:nth-child(2) { animation-delay: 0.1s; }
    .sidebar-menu .nav-item:nth-child(3) { animation-delay: 0.15s; }
    .sidebar-menu .nav-item:nth-child(4) { animation-delay: 0.2s; }
    .sidebar-menu .nav-item:nth-child(5) { animation-delay: 0.25s; }
    .sidebar-menu .nav-item:nth-child(6) { animation-delay: 0.3s; }
    .sidebar-menu .nav-item:nth-child(7) { animation-delay: 0.35s; }
    .sidebar-menu .nav-item:nth-child(8) { animation-delay: 0.4s; }
    .sidebar-menu .nav-item:nth-child(9) { animation-delay: 0.45s; }
    .sidebar-menu .nav-item:nth-child(10) { animation-delay: 0.5s; }

    /* Responsive */
    @media (max-width: 1024px) {
        .main-sidebar {
            left: -var(--sidebar-width);
        }

        .sidebar-open .main-sidebar {
            left: 0;
        }

        .sidebar-open .main-content {
            margin-left: var(--sidebar-width);
        }
    }

    @media (min-width: 1025px) {
        .main-content {
            /* margin-left: var(--sidebar-width); */
            transition: all var(--transition-speed) ease;
        }

        .sidebar-mini .main-content {
            margin-left: var(--sidebar-mini-width);
        }
    }
</style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar mini
            const toggleMiniBtn = document.querySelector('.sidebar-toggle');
            if (toggleMiniBtn) {
                toggleMiniBtn.addEventListener('click', function() {
                    document.body.classList.toggle('sidebar-mini');
                });
            }

            // Toggle sidebar on mobile
            const sidebarToggle = document.querySelector('[data-toggle="sidebar"]');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.body.classList.toggle('sidebar-open');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 1025 &&
                    document.body.classList.contains('sidebar-open') &&
                    !e.target.closest('.main-sidebar') &&
                    !e.target.closest('[data-toggle="sidebar"]')) {
                    document.body.classList.remove('sidebar-open');
                }
            });
        });
    </script>
