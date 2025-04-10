@extends('layouts.app')

@section('title', 'Edit User')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
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

        .form-group label {
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 6px;
            border-color: var(--border-color);
            padding: 0.5rem 0.75rem;
            height: calc(2.25rem + 6px);
            font-size: 0.875rem;
            transition: all 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }

        .input-group-text {
            border-radius: 6px 0 0 6px;
            border-color: var(--border-color);
            background-color: #f8fafc;
        }

        .selectgroup {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .selectgroup-item {
            margin-bottom: 0;
            margin-right: 0;
        }

        .selectgroup-button {
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background-color: #fff;
            transition: all 0.2s;
            font-size: 14px;
        }

        .selectgroup-input:checked + .selectgroup-button {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
            z-index: 1;
        }

        .selectgroup-input:focus + .selectgroup-button {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }

        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(239, 68, 68, 0.25);
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-light {
            background-color: #fff;
            border-color: var(--border-color);
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
        }

        .bg-light {
            background-color: var(--light-bg) !important;
        }

        .form-section {
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .form-section-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-main);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "•";
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            padding: 1rem 0;
            margin-top: 1rem;
            border-top: 1px solid var(--border-color);
            gap: 10px;
        }

        .form-help-text {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .password-info {
            background-color: rgba(99, 102, 241, 0.05);
            border-left: 3px solid var(--primary-color);
            padding: 10px 15px;
            border-radius: 0 6px 6px 0;
            margin-top: 10px;
            font-size: 0.8rem;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .user-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-left: 10px;
        }

        .user-status.admin {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .user-status.supervisor {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .user-status.staff {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .last-login {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Edit User</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></div>
                        <div class="breadcrumb-item">Edit User</div>
                    </div>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Users
                </a>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-2">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Edit User Information</h4>
                                <span class="last-login">Last updated: {{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <div class="user-avatar mx-auto">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <h5 class="mb-0">{{ $user->name }}</h5>
                                    <div class="mt-1">
                                        <span class="user-status {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                                    </div>
                                </div>

                                <form action="{{ route('users.update', $user) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-section">
                                        <div class="form-section-title">Personal Information</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Full Name</label>
                                                    <input type="text" id="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ $user->name }}">
                                                    @error('name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email Address</label>
                                                    <input type="email" id="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ $user->email }}">
                                                    @error('email')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">New Password</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-lock"></i>
                                                            </div>
                                                        </div>
                                                        <input type="password" id="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            name="password" placeholder="Leave blank to keep current">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-light toggle-password">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="password-info mt-2">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Leave password field empty if you don't want to change it.
                                                    </div>
                                                    @error('password')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Phone Number</label>
                                                    <input type="text" id="phone" class="form-control"
                                                        name="phone" value="{{ $user->phone }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-section">
                                        <div class="form-section-title">Job Information</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="position">Position</label>
                                                    <input type="text" id="position"
                                                        class="form-control @error('position') is-invalid @enderror"
                                                        name="position" value="{{ $user->position }}">
                                                    @error('position')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="department">Department</label>
                                                    <input type="text" id="department"
                                                        class="form-control @error('department') is-invalid @enderror"
                                                        name="department" value="{{ $user->department }}">
                                                    @error('department')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="d-block">User Role</label>
                                            <div class="selectgroup selectgroup-pills">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="role" value="admin" class="selectgroup-input"
                                                        @if ($user->role == 'admin') checked @endif>
                                                    <span class="selectgroup-button">
                                                        <i class="fas fa-shield-alt mr-1"></i> Admin
                                                    </span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="role" value="supervisor" class="selectgroup-input"
                                                        @if ($user->role == 'supervisor') checked @endif>
                                                    <span class="selectgroup-button">
                                                        <i class="fas fa-user-tie mr-1"></i> Supervisor
                                                    </span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="role" value="staff" class="selectgroup-input"
                                                        @if ($user->role == 'staff') checked @endif>
                                                    <span class="selectgroup-button">
                                                        <i class="fas fa-user mr-1"></i> Staff
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-footer">
                                        <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Update User
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Danger Zone Card -->
                        <div class="card bg-light">
                            <div class="card-header bg-light">
                                <h4 class="text-danger">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Danger Zone
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Delete this user</h6>
                                        <p class="text-muted mb-0 small">Once deleted, this user will be permanently removed.</p>
                                    </div>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash mr-1"></i> Delete User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle password visibility
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
@endpush
