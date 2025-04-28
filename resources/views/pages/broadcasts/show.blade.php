@extends('layouts.app')

@section('title', 'Broadcast Details')

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

        .broadcast-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .broadcast-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 15px;
        }

        .broadcast-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .broadcast-meta {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .broadcast-status {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 20px;
            margin-left: 10px;
        }

        .broadcast-status.sent {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .broadcast-status.draft {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .broadcast-content {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
        }

        .broadcast-message {
            white-space: pre-line;
            line-height: 1.6;
        }

        .file-attachment {
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: rgba(99, 102, 241, 0.05);
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid rgba(99, 102, 241, 0.1);
        }

        .file-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-right: 15px;
        }

        .file-info {
            flex: 1;
        }

        .file-name {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .file-meta {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .file-actions {
            display: flex;
            align-items: center;
        }

        .file-download {
            padding: 8px 15px;
            border-radius: 6px;
            background-color: var(--primary-color);
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .file-download:hover {
            background-color: var(--primary-hover);
            color: white;
            text-decoration: none;
        }

        .file-download i {
            margin-right: 8px;
        }

        .recipients-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .recipient-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background-color: rgba(99, 102, 241, 0.05);
            border-radius: 20px;
            border: 1px solid rgba(99, 102, 241, 0.1);
        }

        .recipient-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            margin-right: 8px;
        }

        .recipient-name {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .recipient-read {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-left: 8px;
        }

        .recipient-read.read {
            background-color: var(--success-color);
        }

        .recipient-read.unread {
            background-color: var(--warning-color);
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Broadcast Details</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('broadcasts.index') }}">Broadcasts</a></div>
                        <div class="breadcrumb-item">Broadcast Details</div>
                    </div>
                </div>
                <div>
                    @if($broadcast->status === 'draft')
                        <a href="{{ route('broadcasts.edit', $broadcast->id) }}" class="btn btn-primary mr-2">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('broadcasts.send', $broadcast->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success mr-2" onclick="return confirm('Are you sure you want to send this broadcast?')">
                                <i class="fas fa-paper-plane mr-1"></i> Send Now
                            </button>
                        </form>
                    @endif
                    {{-- <a href="{{ route('broadcasts.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a> --}}
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="broadcast-header">
                                    <div class="broadcast-icon">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                    <div>
                                        <div class="broadcast-title">{{ $broadcast->title }}</div>
                                        <div class="broadcast-meta">
                                            Sent by {{ $broadcast->sender->name }}
                                            <span class="broadcast-status {{ $broadcast->status }}">
                                                {{ ucfirst($broadcast->status) }}
                                            </span>
                                            @if($broadcast->status === 'sent')
                                                <div class="mt-1">
                                                    Sent on {{ $broadcast->sent_at->format('M d, Y \a\t H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="broadcast-content">
                                    <div class="broadcast-message">{{ $broadcast->message }}</div>

                                    @if($broadcast->file_path)
                                        <div class="file-attachment">
                                            <div class="file-icon">
                                                @php
                                                    $iconClass = 'fas fa-file';
                                                    $fileType = strtolower($broadcast->file_type);

                                                    if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                        $iconClass = 'fas fa-file-image';
                                                    } elseif (in_array($fileType, ['mp4', 'avi', 'mov'])) {
                                                        $iconClass = 'fas fa-file-video';
                                                    } elseif (in_array($fileType, ['mp3', 'wav'])) {
                                                        $iconClass = 'fas fa-file-audio';
                                                    } elseif ($fileType === 'pdf') {
                                                        $iconClass = 'fas fa-file-pdf';
                                                    } elseif (in_array($fileType, ['doc', 'docx'])) {
                                                        $iconClass = 'fas fa-file-word';
                                                    } elseif (in_array($fileType, ['xls', 'xlsx'])) {
                                                        $iconClass = 'fas fa-file-excel';
                                                    }
                                                @endphp
                                                <i class="{{ $iconClass }}"></i>
                                            </div>
                                            <div class="file-info">
                                                <div class="file-name">{{ $broadcast->file_name }}</div>
                                                <div class="file-meta">{{ strtoupper($broadcast->file_type) }} file</div>
                                            </div>
                                            <div class="file-actions">
                                                <a href="{{ Storage::url($broadcast->file_path) }}" class="file-download" target="_blank">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>



                    {{-- <div class="mb-3">
                        <div class="text-muted mb-1">Recipients</div>
                        <div>
                            @if($broadcast->send_to_all)
                                <span class="badge badge-primary">All Users</span>
                            @else
                                {{ $broadcast->recipients->count() }} selected users
                            @endif
                        </div>
                    </div> --}}


                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Recipients ({{ $broadcast->recipients->count() }})</h4>
                            </div>
                            <div class="card-body">
                                <div class="recipients-list">
                                    @foreach($broadcast->recipients as $recipient)
                                        <div class="recipient-item">
                                            <div class="recipient-avatar">
                                                {{ substr($recipient->name, 0, 1) }}
                                            </div>
                                            <div class="recipient-name">{{ $recipient->name }}</div>
                                            @if($broadcast->status === 'sent')
                                                <div class="recipient-read {{ $recipient->pivot->read_at ? 'read' : 'unread' }}"
                                                    title="{{ $recipient->pivot->read_at ? 'Read on ' . \Carbon\Carbon::parse($recipient->pivot->read_at)->format('M d, Y H:i') : 'Not read yet' }}">
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>Broadcast Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="text-muted mb-1">Created</div>
                                    <div>{{ $broadcast->created_at->format('M d, Y H:i') }}</div>
                                </div>

                                @if($broadcast->updated_at->ne($broadcast->created_at))
                                    <div class="mb-3">
                                        <div class="text-muted mb-1">Last Updated</div>
                                        <div>{{ $broadcast->updated_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endif

                                @if($broadcast->status === 'sent')
                                    <div class="mb-3">
                                        <div class="text-muted mb-1">Sent</div>
                                        <div>{{ $broadcast->sent_at->format('M d, Y H:i') }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="text-muted mb-1">Read Status</div>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $readCount = $broadcast->recipients()->wherePivot('read_at', '!=', null)->count();
                                                $totalCount = $broadcast->recipients->count();
                                                $readPercentage = $totalCount > 0 ? round(($readCount / $totalCount) * 100) : 0;
                                            @endphp
                                            <div class="mr-2">{{ $readCount }}/{{ $totalCount }} ({{ $readPercentage }}%)</div>
                                            <div class="progress" style="height: 6px; flex: 1;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $readPercentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <div class="text-muted mb-1">Actions</div>
                                    <div class="d-flex">
                                        @if($broadcast->status === 'draft')
                                            <a href="{{ route('broadcasts.edit', $broadcast->id) }}" class="btn btn-primary btn-sm mr-2">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <form action="{{ route('broadcasts.send', $broadcast->id) }}" method="POST" class="d-inline mr-2">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to send this broadcast?')">
                                                    <i class="fas fa-paper-plane mr-1"></i> Send
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('broadcasts.destroy', $broadcast->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this broadcast?')">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
