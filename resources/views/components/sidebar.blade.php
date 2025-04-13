<!-- resources/views/partials/sidebar.blade.php -->
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/stisla-fill.svg') }}" alt="Attendify" width="30" class="mr-2">
                ATTENDIFY
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/stisla-fill.svg') }}" alt="Attendify" width="30">
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
                    {{-- <span class="badge badge-info badge-pill ml-1">New</span> --}}
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('permissions.*') ? 'active' : '' }}">
                <a href="{{ route('permissions.index') }}" class="nav-link">
                    <i class="fas fa-key"></i>
                    <span>Permission</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('qr_absens.*') ? 'active' : '' }}">
                <a href="{{ route('qr_absens.index') }}" class="nav-link">
                    <i class="fas fa-qrcode"></i>
                    <span>QR Absen</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('time_offs.*') ? 'active' : '' }}">
                <a href="{{ route('time_offs.index') }}" class="nav-link">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Time Off</span>
                </a>
            </li>

            <li class="menu-header">Reports</li>
            <li class="nav-item {{ Request::routeIs('analytics.*') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
            </li>
            <li class="nav-item {{ Request::routeIs('reports.*') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i class="fas fa-file-export"></i>
                    <span>Export Reports</span>
                </a>
            </li>

            <li class="menu-header">Account</li>
            <li class="nav-item {{ Request::routeIs('settings.*') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
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

        <div class="sidebar-footer mt-5 p-3 text-center">
            <div class="version text-muted">Version 2.3.0</div>
        </div>
    </aside>
</div>
