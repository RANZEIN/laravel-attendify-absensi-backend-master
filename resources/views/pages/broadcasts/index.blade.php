@extends('layouts.app')

@section('title', 'Broadcasts')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-style.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Broadcasts Management</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Broadcasts</div>
                    </div>
                </div>
                <a href="{{ route('broadcasts.create') }}" class="btn btn-primary-header">
                    <i class="fas fa-plus-circle mr-1">
                        </i> New Broadcast
                </a>
            </div>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Broadcasts</h4>
                            <div class="card-header-form">
                                <form method="GET" action="{{ route('broadcasts.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search" name="search" value="{{ request('search') }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="draft-tab" data-toggle="tab" href="#draft" role="tab" aria-controls="draft" aria-selected="false">Draft</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="sent-tab" data-toggle="tab" href="#sent" role="tab" aria-controls="sent" aria-selected="false">Sent</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Status</th>
                                                    <th>Recipients</th>
                                                    <th>Attachment</th>
                                                    <th>Created</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($broadcasts as $broadcast)
                                                    <tr class="broadcast-item {{ $broadcast->status }}">
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar mr-2" style="width: 32px; height: 32px;">
                                                                    {{ substr($broadcast->sender->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="font-weight-bold">{{ $broadcast->title }}</div>
                                                                    <div class="text-muted small">By: {{ $broadcast->sender->name }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge {{ $broadcast->status === 'sent' ? 'badge-success' : 'badge-warning' }}">
                                                                {{ ucfirst($broadcast->status) }}
                                                            </span>
                                                            @if($broadcast->status === 'sent')
                                                                <div class="text-muted small">{{ $broadcast->sent_at ? $broadcast->sent_at->format('M d, Y H:i') : 'Not sent yet' }}</div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($broadcast->send_to_all)
                                                                <span class="badge badge-success">All Users</span>
                                                            @else
                                                                <span class="badge badge-warning">{{ $broadcast->recipients->count() }} users</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($broadcast->file_path)
                                                                <div class="file-attachment" style="margin-top: 0; padding: 8px 12px;">
                                                                    <div class="file-icon" style="width: 24px; height: 24px; margin-right: 8px; font-size: 12px;">
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
                                                            <div class="action-buttons">
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
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6">
                                                            <div class="empty-state">
                                                                <i class="fas fa-bullhorn"></i>
                                                                <p>No broadcasts found</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Draft Tab -->
                                <div class="tab-pane fade" id="draft" role="tabpanel" aria-labelledby="draft-tab">
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Status</th>
                                                    <th>Recipients</th>
                                                    <th>Attachment</th>
                                                    <th>Created</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($broadcasts->where('status', 'draft') as $broadcast)
                                                    <tr class="broadcast-item draft">
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar mr-2" style="width: 32px; height: 32px;">
                                                                    {{ substr($broadcast->sender->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="font-weight-bold">{{ $broadcast->title }}</div>
                                                                    <div class="text-muted small">By: {{ $broadcast->sender->name }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-warning">Draft</span>
                                                        </td>
                                                        <td>
                                                            @if($broadcast->send_to_all)
                                                                <span class="badge badge-success">All Users</span>
                                                            @else
                                                                <span class="badge badge-success">{{ $broadcast->recipients->count() }} users</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($broadcast->file_path)
                                                                <div class="file-attachment" style="margin-top: 0; padding: 8px 12px;">
                                                                    <div class="file-icon" style="width: 24px; height: 24px; margin-right: 8px; font-size: 12px;">
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
                                                            <div class="action-buttons">
                                                                <a href="{{ route('broadcasts.show', $broadcast->id) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('broadcasts.edit', $broadcast->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('broadcasts.send', $broadcast->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to send this broadcast?')">
                                                                        <i class="fas fa-paper-plane"></i>
                                                                    </button>
                                                                </form>
                                                                <form action="{{ route('broadcasts.destroy', $broadcast->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this broadcast?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                        <tr>
                                                            <td colspan="6">
                                                                <div class="empty-state">
                                                                    <i class="fas fa-file-alt"></i>
                                                                    <p>No draft broadcasts found</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Sent Tab -->
                                <div class="tab-pane fade" id="sent" role="tabpanel" aria-labelledby="sent-tab">
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Status</th>
                                                    <th>Recipients</th>
                                                    <th>Attachment</th>
                                                    <th>Sent At</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($broadcasts->where('status', 'sent') as $broadcast)
                                                    <tr class="broadcast-item">
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar mr-2" style="width: 32px; height: 32px;">
                                                                    {{ substr($broadcast->sender->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="font-weight-bold">{{ $broadcast->title }}</div>
                                                                    <div class="text-muted small">By: {{ $broadcast->sender->name }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-success">Sent</span>
                                                        </td>
                                                        <td>
                                                            @if($broadcast->send_to_all)
                                                                <span class="badge badge-success">All Users</span>
                                                            @else
                                                                <span class="badge badge-success">{{ $broadcast->recipients->count() }} users</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($broadcast->file_path)
                                                                <div class="file-attachment" style="margin-top: 0; padding: 8px 12px;">
                                                                    <div class="file-icon" style="width: 24px; height: 24px; margin-right: 8px; font-size: 12px;">
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
                                                            <div>{{ $broadcast->sent_at->format('M d, Y') }}</div>
                                                            <div class="text-muted small">{{ $broadcast->sent_at->format('H:i') }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ route('broadcasts.show', $broadcast->id) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <form action="{{ route('broadcasts.destroy', $broadcast->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this broadcast?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6">
                                                            <div class="empty-state">
                                                                <i class="fas fa-paper-plane"></i>
                                                                <p>No sent broadcasts found</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="float-right mt-3">
                                {{ $broadcasts->withQueryString()->links() }}
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
        $(document).ready(function() {
            // Handle tab persistence
            const activeTab = localStorage.getItem('broadcast_active_tab');
            if (activeTab) {
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }

            // Store the active tab in localStorage
            $('#myTab a').on('shown.bs.tab', function (e) {
                localStorage.setItem('broadcast_active_tab', $(e.target).attr('href'));
            });

            // Confirm delete
            $('form').on('submit', function(e) {
                if ($(this).find('button[type="submit"]').hasClass('confirm-delete')) {
                    if (!confirm('Are you sure you want to delete this broadcast?')) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
@endpush
