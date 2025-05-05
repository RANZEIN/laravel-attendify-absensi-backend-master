@extends('layouts.app')

@section('title', 'Broadcast Details')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('css/app-style.css') }}">
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
            </div>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $broadcast->title }}</h4>
                            <div class="card-header-action">
                                @if($broadcast->status === 'draft')
                                    <a href="{{ route('broadcasts.edit', $broadcast->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('broadcasts.send', $broadcast->id) }}" method="POST" class="d-inline ml-2">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to send this broadcast?')">
                                            <i class="fas fa-paper-plane"></i> Send Now
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar mr-3">
                                        {{ substr($broadcast->sender->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $broadcast->sender->name }}</div>
                                        <div class="text-muted small">
                                            {{ $broadcast->created_at->format('d M Y, H:i') }}
                                            @if($broadcast->status === 'sent')
                                                • Sent {{ $broadcast->sent_at->diffForHumans() }}
                                            @else
                                                • <span class="badge badge-warning">Draft</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="broadcast-message mb-4">
                                {{ $broadcast->message }}
                            </div>

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
                                        <a href="{{ Storage::url($broadcast->file_path) }}" class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Broadcast Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="text-muted mb-1">Status</div>
                                <div>
                                    @if($broadcast->status === 'draft')
                                        <span class="badge badge-warning">Draft</span>
                                    @else
                                        <span class="badge badge-success">Sent</span>
                                        <div class="mt-1 small">{{ $broadcast->sent_at->format('d M Y, H:i') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="text-muted mb-1">Recipients</div>
                                <div>
                                    @if($broadcast->send_to_all)
                                        <span class="badge badge-success">All Users</span>
                                    @else
                                        <span class="badge badge-success">{{ $broadcast->recipients->count() }} recipients</span>

                                        <div class="recipients-list mt-2">
                                            @foreach($broadcast->recipients->take(10) as $recipient)
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

                                            @if($broadcast->recipients->count() > 10)
                                                <div class="recipient-item">
                                                    <div class="recipient-name">+{{ $broadcast->recipients->count() - 10 }} more</div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="text-muted mb-1">Created</div>
                                <div>{{ $broadcast->created_at->format('d M Y, H:i') }}</div>
                            </div>

                            @if($broadcast->updated_at->ne($broadcast->created_at))
                                <div class="mb-3">
                                    <div class="text-muted mb-1">Last Updated</div>
                                    <div>{{ $broadcast->updated_at->format('d M Y, H:i') }}</div>
                                </div>
                            @endif

                            @if($broadcast->status === 'sent')
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
        </section>
    </div>
@endsection
