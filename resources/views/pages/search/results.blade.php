{{-- @extends('layouts.app')

@section('title', 'Search Results')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Search Results</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Search Results</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Results for "{{ $query }}"</h2>
            <p class="section-lead">
                Found results across {{ count(array_filter($results, function($result) { return count($result) > 0; })) }} categories
            </p>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter Results</h4>
                            <div class="card-header-action">
                                <a href="{{ route('search', ['q' => $query]) }}" class="btn {{ !$category ? 'btn-primary' : 'btn-light' }}">All</a>
                                <a href="{{ route('search', ['q' => $query, 'category' => 'users']) }}" class="btn {{ $category == 'users' ? 'btn-primary' : 'btn-light' }}">Users</a>
                                <a href="{{ route('search', ['q' => $query, 'category' => 'broadcasts']) }}" class="btn {{ $category == 'broadcasts' ? 'btn-primary' : 'btn-light' }}">Broadcasts</a>
                                <a href="{{ route('search', ['q' => $query, 'category' => 'tasks']) }}" class="btn {{ $category == 'tasks' ? 'btn-primary' : 'btn-light' }}">Tasks</a>
                                <a href="{{ route('search', ['q' => $query, 'category' => 'documents']) }}" class="btn {{ $category == 'documents' ? 'btn-primary' : 'btn-light' }}">Documents</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($results['users']) && count($results['users']) > 0 && (!$category || $category == 'users'))
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Users ({{ count($results['users']) }})</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results['users'] as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($user->profile_photo_path)
                                                        <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="rounded-circle mr-2" width="35">
                                                    @else
                                                        <div class="avatar-sm rounded-circle bg-{{ randomColor() }} text-white mr-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>{{ $user->name }}</div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                                            <td>
                                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-primary btn-sm">View</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($results['broadcasts']) && count($results['broadcasts']) > 0 && (!$category || $category == 'broadcasts'))
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Broadcasts ({{ count($results['broadcasts']) }})</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Sent By</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results['broadcasts'] as $broadcast)
                                        <tr>
                                            <td>{{ $broadcast->title }}</td>
                                            <td>
                                                <span class="badge badge-{{ $broadcast->status == 'sent' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($broadcast->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $broadcast->sender->name }}</td>
                                            <td>{{ $broadcast->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('broadcasts.show', $broadcast->id) }}" class="btn btn-primary btn-sm">View</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($results['tasks']) && count($results['tasks']) > 0 && (!$category || $category == 'tasks'))
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tasks ({{ count($results['tasks']) }})</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Assigned To</th>
                                            <th>Due Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results['tasks'] as $task)
                                        <tr>
                                            <td>{{ $task->title }}</td>
                                            <td>
                                                <span class="badge badge-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'info' : 'warning') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $task->assignee->name ?? 'Unassigned' }}</td>
                                            <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</td>
                                            <td>
                                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-primary btn-sm">View</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($results['documents']) && count($results['documents']) > 0 && (!$category || $category == 'documents'))
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Documents ({{ count($results['documents']) }})</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($results['documents'] as $document)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4>{{ $document->title }}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2 text-muted">{{ $document->description }}</div>
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="mr-2">
                                                    <i class="fas fa-file-{{ $document->file_type == 'pdf' ? 'pdf' : ($document->file_type == 'doc' || $document->file_type == 'docx' ? 'word' : 'alt') }} fa-2x text-{{ $document->file_type == 'pdf' ? 'danger' : ($document->file_type == 'doc' || $document->file_type == 'docx' ? 'primary' : 'info') }}"></i>
                                                </div>
                                                <div>
                                                    <div>{{ $document->file_name }}</div>
                                                    <div class="text-small text-muted">{{ strtoupper($document->file_type) }} · {{ $document->file_size }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="{{ route('documents.show', $document->id) }}" class="btn btn-primary">View Document</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(count(array_filter($results, function($result) { return count($result) > 0; })) == 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="empty-state" data-height="400">
                                <div class="empty-state-icon bg-danger">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h2>No results found</h2>
                                <p class="lead">
                                    We couldn't find any results matching "{{ $query }}"
                                </p>
                                <a href="{{ route('dashboard') }}" class="btn btn-primary mt-4">Back to Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection --}}
