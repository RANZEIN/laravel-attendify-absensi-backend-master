@extends('layouts.app')

@section('title', 'Broadcasts')

@push('style')
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

        .broadcast-item {
            border-left: 3px solid var(--primary-color);
            transition: all 0.2s;
        }

        .broadcast-item:hover {
            background-color: rgba(99, 102, 241, 0.05);
        }

        .broadcast-item.draft {
            border-left-color: var(--warning-color);
        }

        .broadcast-status {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 20px;
        }

        .broadcast-status.sent {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .broadcast-status.draft {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .file-attachment {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background-color: rgba(99, 102, 241, 0.05);
            border-radius: 6px;
            margin-top: 10px;
        }

        .file-icon {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .file-info {
            flex: 1;
            overflow: hidden;
        }

        .file-name {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-meta {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Broadcasts</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Broadcasts</div>
                    </div>
                </div>
                <a href="{{ route('broadcasts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1">
                        </i> New Broadcast
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
                                <h4>All Broadcasts</h4>
                                <div class="card-header-form">
                                    <form method="GET" action="{{ route('broadcasts.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search by title" name="search" value="{{ request('search') }}">
                                            <div class="input-group-btn">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Recipients</th>
                                                <th>Attachment</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($broadcasts as $broadcast)
                                                <tr class="broadcast-item {{ $broadcast->status }}">
                                                    <td>
                                                        <div class="font-weight-bold">{{ $broadcast->title }}</div>
                                                        <div class="text-muted small">By: {{ $broadcast->sender->name }}</div>
                                                    </td>
                                                    <td>
                                                        <span class="broadcast-status {{ $broadcast->status }}">
                                                            {{ ucfirst($broadcast->status) }}
                                                        </span>
                                                        @if($broadcast->status === 'sent')
                                                            <div class="text-muted small">{{ $broadcast->sent_at ? $broadcast->sent_at->format('M d, Y H:i') : 'Not sent yet' }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($broadcast->send_to_all)
                                                            <span class="badge badge-primary">All Users</span>
                                                        @else
                                                            <span class="badge badge-primary">{{ $broadcast->recipients->count() }} users
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($broadcast->file_path)
                                                            <div class="file-attachment">
                                                                <div class="file-icon">
                                                                    <i class="fas fa-file"></i>
                                                                </div>
                                                                <div class="file-info">
                                                                    <div class="file-name">{{ $broadcast->file_name }}</div>
                                                                    <div class="file-meta">{{ strtoupper($broadcast->file_type) }}</div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">No attachment</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div>{{ $broadcast->created_at->format('M d, Y') }}</div>
                                                        <div class="text-muted small">{{ $broadcast->created_at->format('H:i') }}</div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('broadcasts.show', $broadcast->id) }}" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        @if($broadcast->status === 'draft')
                                                            <a href="{{ route('broadcasts.edit', $broadcast->id) }}" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                            </a>

                                                            <form action="{{ route('broadcasts.send', $broadcast->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to send this broadcast?')">
                                                                    <i class="fas fa-paper-plane"></i>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        <form action="{{ route('broadcasts.destroy', $broadcast->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this broadcast?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5">
                                                        <div class="empty-state">
                                                            <i class="fas fa-bullhorn mb-3" style="font-size: 3rem; color: #ccc;"></i>
                                                            <p>No broadcasts found</p>
                                                            <a href="{{ route('broadcasts.create') }}" class="btn btn-primary mt-3">
                                                                {{-- <i class="fas fa-plus"> --}}
                                                                    </i> Create New Broadcast
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
                                {{ $broadcasts->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
