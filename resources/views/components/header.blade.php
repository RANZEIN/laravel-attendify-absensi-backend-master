<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg sidebar-toggle"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown user-dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-user">
                <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle mr-1 user-avatar">
                <div class="d-sm-none d-lg-inline-block user-name">{{ auth()->user()->name }}</div>
                {{-- <i class="fas fa-chevron-down d-none d-lg-inline-block ml-1 dropdown-icon"></i> --}}
            </a>
            <div class="dropdown-menu dropdown-menu-right user-dropdown-menu">
                <div class="dropdown-header">
                    <img src="{{ asset('img/avatar/avatar-1.png') }}" alt="User" class="dropdown-header-image">
                    <div class="dropdown-header-info">
                        <div class="dropdown-header-name">{{ auth()->user()->name }}</div>
                        <div class="dropdown-header-email">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <a href="#" class="dropdown-item has-icon dropdown-item-animate">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="#" class="dropdown-item has-icon dropdown-item-animate">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item has-icon text-danger dropdown-item-animate" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>

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
        --transition-speed: 0.3s;
    }

    /* Navbar Styles */
    .navbar-bg {
        background-color: var(--primary);
        background-image: linear-gradient(135deg, #2d3a7b 0%, var(--secondary) 100%);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        /* height: 70px; */
        z-index: -1;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .main-navbar {
        background-color: transparent;
        /* height: 70px; */
        z-index: 890;
        position: relative;
        top: 0;
        left: 0;
        right: 0;
        padding: 0 20px;
        transition: all var(--transition-speed) ease;
    }

    /* .main-navbar.scrolled {
        background-color: var(--light);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        animation: slideDown 0.5s ease forwards;
    } */

    @keyframes slideDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
/*
    .main-navbar.scrolled .navbar-bg {
        opacity: 0;
    }

    .main-navbar.scrolled .sidebar-toggle,
    .main-navbar.scrolled .nav-link-user {
        color: var(--primary);
    } */

    .sidebar-toggle {
        color: var(--light);
        font-size: 20px;
        background: transparent;
        border: none;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-speed) ease;
        position: relative;
        overflow: hidden;
    }

    .sidebar-toggle::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: scale(0);
        transition: transform 0.3s ease;
    }

    .sidebar-toggle:hover::before {
        transform: scale(1);
    }

    .sidebar-toggle i {
        position: relative;
        z-index: 2;
        transition: transform 0.3s ease;
    }

    .sidebar-toggle:hover i {
        transform: rotate(90deg);
    }

    /* User Dropdown */
    .navbar-right {
        display: flex;
        align-items: center;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .user-dropdown {
        position: relative;
    }

    .nav-link-user {
        position: relative;
        color: var(--light);
        background: rgba(255, 255, 255, 0.1);
        border: none;
        padding: 6px 15px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        /* gap: 10px; */
        cursor: pointer;
        transition: all var(--transition-speed) ease;
        font-weight: 600;
        overflow: hidden;
    }

    .nav-link-user::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .nav-link-user:hover::before {
        transform: translateX(0);
    }

    .nav-link-user > * {
        position: relative;
        z-index: 2;
    }

    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50% !important;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.5);
        transition: all var(--transition-speed) ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .nav-link-user:hover .user-avatar {
        transform: scale(1.05);
    }

    .user-name {
        font-size: 14px;
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dropdown-icon {
        font-size: 12px;
        transition: transform 0.3s ease;
    }

    .user-dropdown.show .dropdown-icon {
        transform: rotate(180deg);
    }

    .user-dropdown-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 15px);
        min-width: 240px;
        background: var(--light);
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: none;
        padding: 15px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px) scale(0.95);
        transition: all 0.25s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        z-index: 891;
        overflow: hidden;
    }

    .user-dropdown-menu::before {
        content: '';
        position: absolute;
        top: -5px;
        right: 20px;
        width: 10px;
        height: 10px;
        background: var(--light);
        transform: rotate(45deg);
        box-shadow: -2px -2px 5px rgba(0, 0, 0, 0.03);
    }

    .user-dropdown.show .user-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    .dropdown-header {
        display: flex;
        align-items: center;
        padding: 15px;
        margin-bottom: 10px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .dropdown-header-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        border: 2px solid var(--primary);
    }

    .dropdown-header-info {
        flex: 1;
    }

    .dropdown-header-name {
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 2px;
        color: var(--secondary);
    }

    .dropdown-header-email {
        font-size: 12px;
        color: #999;
    }

    .dropdown-divider {
        height: 1px;
        background-color: rgba(0, 0, 0, 0.05);
        margin: 10px 0;
    }

    .dropdown-item {
        padding: 10px 15px;
        color: var(--secondary);
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .dropdown-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.03);
        z-index: -1;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .dropdown-item:hover::before {
        transform: translateX(0);
    }

    .dropdown-item i {
        font-size: 16px;
        width: 20px;
        text-align: center;
    }

    .dropdown-item.text-danger {
        color: var(--danger);
    }

    .dropdown-item.text-danger:hover {
        background-color: rgba(252, 84, 75, 0.05);
    }

    /* Animation classes */
    .dropdown-item-animate {
        opacity: 0;
        transform: translateY(10px);
        animation: fadeInUp 0.3s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-item-animate:nth-child(2) { animation-delay: 0.05s; }
    .dropdown-item-animate:nth-child(3) { animation-delay: 0.1s; }
    .dropdown-item-animate:nth-child(4) { animation-delay: 0.15s; }
    .dropdown-item-animate:nth-child(5) { animation-delay: 0.2s; }

    /* Responsiveness */
    @media (max-width: 768px) {
        .user-name {
            display: none !important;
        }

        .dropdown-icon {
            display: none !important;
        }

        .nav-link-user {
            padding: 6px;
        }

        .main-navbar {
            padding: 0 15px;
        }
    }

    @media (max-width: 576px) {
        .main-navbar {
            padding: 0 10px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
        }

        .user-dropdown-menu {
            width: calc(100vw - 30px);
            left: 50%;
            right: auto;
            transform: translateX(-50%) translateY(10px) scale(0.95);
        }

        .user-dropdown.show .user-dropdown-menu {
            transform: translateX(-50%) translateY(0) scale(1);
        }

        .dropdown-menu::before {
            right: calc(50% - 80px);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User dropdown toggle
        const userDropdown = document.querySelector('.user-dropdown');
        const userDropdownToggle = document.querySelector('.nav-link-user');

        if (userDropdownToggle && userDropdown) {
            userDropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }

        // Scroll effect for navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.main-navbar');
            if (navbar) {
                if (window.scrollY > 20) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }
        });
    });
</script>
