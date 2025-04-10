@extends('layouts.app')

@section('title', 'Time Off Request Detail')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Time Off Request Detail</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('time_offs.index') }}">Time Off</a></div>
                    <div class="breadcrumb-item">Time Off Request Detail</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Time Off Request Detail</h2>
                <p class="section-lead">
                    Information about employee time off request.
                </p>

                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Employee Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Employee Name</label>
                                        <p>{{ $timeOff->user->name }}</p>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Employee Phone</label>
                                        <p>{{ $timeOff->user->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Position</label>
                                        <p>{{ $timeOff->user->position ?? 'N/A' }}</p>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Department</label>
                                        <p>{{ $timeOff->user->department ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>Time Off Request Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Type</label>
                                        <p>
                                            @if($timeOff->type == 'annual_leave')
                                                <span class="badge badge-success">Annual Leave</span>
                                            @elseif($timeOff->type == 'sick_leave')
                                                <span class="badge badge-danger">Sick Leave</span>
                                            @elseif($timeOff->type == 'unpaid_leave')
                                                <span class="badge badge-warning">Unpaid Leave</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Status</label>
                                        <p>
                                            @if($timeOff->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($timeOff->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($timeOff->status == 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Start Date</label>
                                        <p>{{ \Carbon\Carbon::parse($timeOff->start_date)->format('d M Y') }}</p>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>End Date</label>
                                        <p>{{ \Carbon\Carbon::parse($timeOff->end_date)->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Total Days</label>
                                        <p>{{ $timeOff->days }} days</p>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Created At</label>
                                        <p>{{ $timeOff->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Reason</label>
                                        <p>{{ $timeOff->reason }}</p>
                                    </div>
                                </div>
                                @if($timeOff->document_url)
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Supporting Document</label>
                                        <div>
                                            @php
                                                $extension = pathinfo($timeOff->document_url, PATHINFO_EXTENSION);
                                            @endphp

                                            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                <img src="{{ asset('storage/' . $timeOff->document_url) }}"
                                                    alt="Supporting Document" class="img-thumbnail mb-3" style="max-width: 300px;">
                                            @else
                                                <a href="{{ asset('storage/' . $timeOff->document_url) }}" class="btn btn-primary" target="_blank">
                                                    <i class="fas fa-file-download"></i> Download Document
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="card-footer bg-whitesmoke">
                                @if($timeOff->status == 'pending')
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="{{ route('time_offs.approve', $timeOff->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-lg btn-block" onclick="return confirm('Are you sure you want to approve this request?')">
                                                <i class="fas fa-check"></i> Approve Request
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="{{ route('time_offs.reject', $timeOff->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger btn-lg btn-block" onclick="return confirm('Are you sure you want to reject this request?')">
                                                <i class="fas fa-times"></i> Reject Request
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <a href="{{ route('time_offs.edit', $timeOff->id) }}" class="btn btn-primary btn-block">
                                            <i class="fas fa-edit"></i> Edit Request
                                        </a>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('time_offs.index') }}" class="btn btn-primary">
                                            <i class="fas fa-arrow-left"></i> Back to List
                                        </a>
                                    </div>
                                </div>
                                @endif
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
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>

    <!-- Page Specific JS File -->
@endpush
