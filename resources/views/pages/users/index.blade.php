<!-- INDEX PAGE (index.blade.php) -->
@extends('layouts.app')

@section('title', 'Users Management')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --success-color: #10b981;
            --info-color: #3b82f6;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #1e293b;
            --text-secondary: #64748b;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            color: var(--text-main);
            background-color: var(--light-bg);
        }

        .section-header {
            padding: 20px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .section-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 15px 20px;
        }

        .card-header h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table th {
            border-top: none;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.025em;
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            color: var(--text-main);
            border-top: 1px solid var(--border-color);
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
            transition: all 0.2s;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: var(--primary-color);
            border-color: var(--border-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .input-group .form-control {
            border-radius: 6px 0 0 6px;
            border-color: var(--border-color);
        }

        .input-group-append .btn {
            border-radius: 0 6px 6px 0;
        }

        .search-input {
            height: 38px;
            box-shadow: none;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            margin-right: 10px;
        }

        .user-position {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .empty-state {
            text-align: center;
            padding: 40px 0;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "•";
        }

        .status-badge {
            padding: 0.25em 0.6em;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 20px;
        }

        .status-badge.admin {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .status-badge.supervisor {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .status-badge.staff {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .animate-hover {
            transition: all 0.2s;
        }

        .animate-hover:hover {
            transform: translateY(-2px);
        }

        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 999;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .floating-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .responsive-table {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Users Management</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Users</a></div>
                        <div class="breadcrumb-item">All Users</div>
                    </div>
                </div>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New User
                </a>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>All Users</h4>
                                <form method="GET" action="{{ route('users.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control search-input" placeholder="Search by name..." name="name">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="card-body p-0">
                                <div class="responsive-table">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Contact Info</th>
                                                <th>Position</th>
                                                <th>Role</th>
                                                <th>Created</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $user)
                                                <tr class="animate-hover">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="user-avatar">
                                                                {{ substr($user->name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="font-weight-bold">{{ $user->name }}</div>
                                                                <div class="text-muted small">{{ $user->department }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>{{ $user->email }}</div>
                                                        <div class="text-muted small">{{ $user->phone ?? 'No phone' }}</div>
                                                    </td>
                                                    <td>
                                                        <span class="user-position">{{ $user->position }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                                                    </td>
                                                    <td>
                                                        <div>{{ $user->created_at->format('M d, Y') }}</div>
                                                        <div class="text-muted small">{{ $user->created_at->format('h:i A') }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href='{{ route('users.edit', $user->id) }}'
                                                                class="btn btn-sm btn-info btn-icon mr-1">
                                                                <i class="fas fa-edit"></i>
                                                                Edit
                                                            </a>
                                                            <form action="{{ route('users.destroy', $user->id) }}"
                                                                method="POST">
                                                                <input type="hidden" name="_method" value="DELETE" />
                                                                <input type="hidden" name="_token"
                                                                    value="{{ csrf_token() }}" />
                                                                <button class="btn btn-sm btn-danger btn-icon confirm-delete">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="empty-state">
                                                            <i class="fas fa-user-slash"></i>
                                                            <p>No users found</p>
                                                            <a href="{{ route('users.create') }}" class="btn btn-primary mt-3">
                                                                <i class="fas fa-plus"></i> Add New User
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                {{ $users->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Floating action button for mobile -->
    <a href="{{ route('users.create') }}" class="floating-btn btn-primary d-md-none">
        <i class="fas fa-plus"></i>
    </a>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
    <script>
        // Confirm delete functionality
        $('.confirm-delete').click(function(e) {
            if (!confirm('Are you sure you want to delete this user?')) {
                e.preventDefault();
            }
        });
    </script>
@endpush
