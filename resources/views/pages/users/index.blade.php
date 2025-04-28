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

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            position: relative;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            white-space: nowrap;
        }

        .table th {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 10;
            padding: 15px 20px;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .table td {
            padding: 15px 20px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        /* Card and Button Styles */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
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

        /* User Card Styles */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            margin-right: 12px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 2px;
        }

        .user-department {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Status Badge Styles */
        .status-badge {
            padding: 0.25em 0.6em;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 20px;
            display: inline-block;
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

        /* Leave Balance Styles - Simplified */
        .leave-balance {
            width: 200px;
        }

        .leave-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .leave-label {
            font-size: 0.8rem;
            color: var(--text-main);
            font-weight: 500;
        }

        .leave-value {
            font-size: 0.8rem;
            font-weight: 600;
        }

        .leave-progress {
            height: 6px;
            background-color: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
        }

        .leave-progress-bar {
            height: 100%;
            border-radius: 3px;
        }

        .leave-progress-bar.good {
            background-color: var(--success-color);
        }

        .leave-progress-bar.warning {
            background-color: var(--warning-color);
        }

        .leave-progress-bar.danger {
            background-color: var(--danger-color);
        }

        .leave-details {
            font-size: 0.7rem;
            color: var(--text-secondary);
            margin-top: 5px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-btn {
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 4px;
        }

        /* Search and Filters */
        .filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .search-container {
            flex: 1;
            min-width: 200px;
        }

        .search-input {
            height: 38px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            padding: 0 15px;
            width: 100%;
        }

        .filter-dropdown {
            height: 38px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            padding: 0 15px;
            background-color: #fff;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 0;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .card-header form {
                width: 100%;
                margin-top: 15px;
            }

            .filters-container {
                flex-direction: column;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
            }
        }

        /* Tooltip */
        .tooltip {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #333;
            color: #fff;
            text-align: left;
            border-radius: 6px;
            padding: 8px 12px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Column widths */
        .col-user {
            min-width: 200px;
        }

        .col-contact {
            min-width: 180px;
        }

        .col-position {
            min-width: 120px;
        }

        .col-role {
            min-width: 100px;
        }

        .col-leave {
            min-width: 220px;
        }

        .col-created {
            min-width: 120px;
        }

        .col-actions {
            min-width: 160px;
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
                        <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Users Management</div>
                    </div>
                </div>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    {{-- <i class="fas fa-plus"> --}}
                        </i> Create Users Profile
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

                                <div class="filters-container">
                                    <div class="search-container">
                                        <form method="GET" action="{{ route('users.index') }}">
                                            <input type="text" class="search-input" placeholder="Search by name or email..." name="search" value="{{ request('search') }}">
                                        </form>
                                    </div>

                                    <select class="filter-dropdown">
                                        <option value="">All Roles</option>
                                        <option value="admin">Admin</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="staff">Staff</option>
                                    </select>

                                    <select class="filter-dropdown">
                                        <option value="">All Departments</option>
                                        <option value="engineering">Engineering</option>
                                        <option value="marketing">Marketing</option>
                                        <option value="hr">HR</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="col-user">User</th>
                                                <th class="col-contact">Contact Info</th>
                                                <th class="col-position">Position</th>
                                                <th class="col-role">Role</th>
                                                <th class="col-leave">Time Off Balance</th>
                                                <th class="col-created">Created</th>
                                                <th class="col-actions text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $user)
                                                <tr>
                                                    <td>
                                                        <div class="user-info">
                                                            <div class="user-avatar">
                                                                {{ substr($user->name, 0, 1) }}
                                                            </div>
                                                            <div class="user-details">
                                                                <span class="user-name">{{ $user->name }}</span>
                                                                <span class="user-department">{{ $user->department }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>{{ $user->email }}</div>
                                                        <div class="text-muted small">{{ $user->phone ?? 'No phone' }}</div>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge staff">{{ $user->position }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="leave-balance">
                                                            <div class="leave-info">
                                                                <span class="leave-label tooltip">
                                                                    Jatah Cuti
                                                                    <span class="tooltip-text">
                                                                        Jatah cuti 13 hari yang digunakan untuk:
                                                                        <br>- Cuti Tahunan
                                                                        <br>- Izin Tidak Masuk
                                                                        <br>- Sakit dengan Surat Dokter
                                                                        <br>- Sakit tanpa Surat Dokter
                                                                    </span>
                                                                </span>
                                                                <span class="leave-value">
                                                                    {{ $user->leave_stats['leaveRemaining'] }} / {{ $user->leave_stats['totalAllowance'] }} hari
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

                                                            @if($user->leave_stats['leaveUsed'] > 0)
                                                                <div class="leave-details">
                                                                    <div><strong>Terpakai: {{ $user->leave_stats['leaveUsed'] }} hari</strong></div>
                                                                    @foreach($user->leave_stats['leaveTypes'] as $type => $days)
                                                                        <div>
                                                                            @if($type == 'cuti_tahunan')
                                                                                Cuti Tahunan:
                                                                            @elseif($type == 'izin_tidak_masuk')
                                                                                Izin:
                                                                            @elseif($type == 'sakit_dengan_surat_dokter')
                                                                                Sakit (dgn surat):
                                                                            @elseif($type == 'sakit_tanpa_surat_dokter')
                                                                                Sakit (tanpa surat):
                                                                            @else
                                                                                {{ $type }}:
                                                                            @endif
                                                                            {{ $days }} hari
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>{{ $user->created_at->format('M d, Y') }}</div>
                                                        <div class="text-muted small">{{ $user->created_at->format('h:i A') }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href='{{ route('users.edit', $user->id) }}'
                                                                class="btn btn-info action-btn">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-danger action-btn confirm-delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
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
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script>
        // Confirm delete functionality
        $('.confirm-delete').click(function(e) {
            if (!confirm('Are you sure you want to delete this user?')) {
                e.preventDefault();
            }
        });

        // Filter functionality
        document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
            dropdown.addEventListener('change', function() {
                const searchParams = new URLSearchParams(window.location.search);

                if (this.value) {
                    searchParams.set(this.name || this.getAttribute('name') || 'filter', this.value);
                } else {
                    searchParams.delete(this.name || this.getAttribute('name') || 'filter');
                }

                window.location.search = searchParams.toString();
            });
        });
    </script>
@endpush
