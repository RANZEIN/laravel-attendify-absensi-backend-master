@extends('layouts.app')

@section('title', 'Users Management')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Users Management</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Users Management</div>
                    </div>
                </div>
                <a href="{{ route('users.create') }}"class="btn btn-primary-header">
                    <i class="fas fa-plus-circle"></i> Create User Profile
                </a>
            </div>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Total Users</span>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $users->total() }}</div>
                    <div class="stat-change positive">
                        <i class="fas fa-user-check"></i>
                        <span>Active Users</span>
                    </div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-header">
                        <span class="stat-title">Administrators</span>
                        <div class="stat-icon warning">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $users->where('role', 'admin')->count() }}</div>
                    <div class="stat-change">
                        <i class="fas fa-percentage"></i>
                        <span class="stat-change-text">{{ $users->count() > 0 ? round(($users->where('role', 'admin')->count() / $users->count()) * 100) : 0 }}% of total</span>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-header">
                        <span class="stat-title">Staff Members</span>
                        <div class="stat-icon success">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $users->where('role', 'staff')->count() }}</div>
                    <div class="stat-change">
                        <i class="fas fa-briefcase"></i>
                        <span class="stat-change-text">Regular employees</span>
                    </div>
                </div>

                <div class="stat-card info">
                    <div class="stat-header">
                        <span class="stat-title">Supervisors</span>
                        <div class="stat-icon info">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $users->where('role', 'supervisor')->count() }}</div>
                    <div class="stat-change">
                        <i class="fas fa-sitemap"></i>
                        <span class="stat-change-text">Management team</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-users mr-2"></i>All Users</h4>
                            <div class="card-header-form">
                                <form method="GET" action="{{ route('users.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search by name or email..." name="search" value="{{ request('search') }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>USER</th>
                                                    <th>CONTACT INFO</th>
                                                    <th>POSITION</th>
                                                    <th>ROLE</th>
                                                    <th>BALANCE</th>
                                                    <th>CREATED</th>
                                                    <th class="text-center">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($users->count() > 0)
                                                    @foreach ($users as $user)
                                                        <tr>
                                                            <td>
                                                                <div class="user-info">
                                                                    <div class="avatar">
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
                                                                <span class="badge badge-warning">{{ $user->position }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-success {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                                                            </td>
                                                            <td>
                                                                <div class="leave-balance">
                                                                    <div class="leave-info">
                                                                        <span class="leave-value">
                                                                            {{ $user->leave_stats['leaveRemaining'] }} / {{ $user->leave_stats['totalAllowance'] }} days
                                                                        </span>
                                                                    </div>

                                                                    @php
                                                                        $total = $user->leave_stats['totalAllowance'];
                                                                        $remaining = $user->leave_stats['leaveRemaining'];
                                                                        $percentage = $total > 0 ? ($remaining / $total) * 100 : 0;
                                                                        $colorClass = $percentage > 50 ? 'good' : ($percentage > 25 ? 'warning' : 'danger');
                                                                    @endphp

                                                                    <div class="leave-progress">
                                                                        <div class="leave-progress-bar {{ $colorClass }}" style="width: {{ $percentage }}%"></div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div>{{ $user->created_at->format('M d, Y') }}</div>
                                                                <div class="text-muted small">{{ $user->created_at->format('h:i A') }}</div>
                                                            </td>
                                                            <td>
                                                                <div class="action-buttons">
                                                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="7">
                                                            <div class="empty-state">
                                                                <i class="fas fa-user-slash"></i>
                                                                <p>No users found</p>
                                                                <a href="{{ route('users.create') }}" class="btn btn-primary mt-3">
                                                                    <i class="fas fa-plus"></i> Add New User
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                </div>
                            <div class="float-right mt-3">
                                {{ $users->withQueryString()->links() }}
                            </div>
                        </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Handle tab persistence
            const activeTab = localStorage.getItem('users_active_tab');
            if (activeTab) {
                $('#usersTab a[href="' + activeTab + '"]').tab('show');
            }

            // Store the active tab in localStorage
            $('#usersTab a').on('shown.bs.tab', function (e) {
                localStorage.setItem('users_active_tab', $(e.target).attr('href'));
            });

            // Filter functionality
            $('.filter-dropdown').on('change', function() {
                const searchParams = new URLSearchParams(window.location.search);

                if (this.value) {
                    searchParams.set(this.name, this.value);
                } else {
                    searchParams.delete(this.name);
                }

                window.location.search = searchParams.toString();
            });
        });
    </script>
@endpush
