@extends('layouts.app')

@section('title', 'Time Off Requests')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Time Off Requests</h1>
                <div class="section-header-button">
                    <a href="{{ route('time_offs.create') }}" class="btn btn-primary">Add New</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Time Off</a></div>
                    <div class="breadcrumb-item">All Time Off Requests</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Time Off Requests</h2>
                <p class="section-lead">
                    You can manage all time off requests, such as editing, approving, rejecting and more.
                </p>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>All Time Off Requests</h4>
                                <div class="card-header-form">
                                    <form method="GET" action="{{ route('time_offs.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search by name" name="name">
                                            <div class="input-group-append">
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
                                        <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false">Pending</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="false">Approved</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false">Rejected</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                                        <div class="table-responsive mt-3">
                                            <table class="table-striped table">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Days</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                @foreach ($allTimeOffs as $timeOff)
                                                    <tr>
                                                        <td>{{ $timeOff->user->name }}</td>
                                                        <td>
                                                            @if($timeOff->type == 'annual_leave')
                                                                <span class="badge badge-success">Annual Leave</span>
                                                            @elseif($timeOff->type == 'sick_leave')
                                                                <span class="badge badge-danger">Sick Leave</span>
                                                            @elseif($timeOff->type == 'unpaid_leave')
                                                                <span class="badge badge-warning">Unpaid Leave</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($timeOff->start_date)->format('d M Y') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($timeOff->end_date)->format('d M Y') }}</td>
                                                        <td>{{ $timeOff->days }}</td>
                                                        <td>
                                                            @if($timeOff->status == 'pending')
                                                                <span class="badge badge-warning">Pending</span>
                                                            @elseif($timeOff->status == 'approved')
                                                                <span class="badge badge-success">Approved</span>
                                                            @elseif($timeOff->status == 'rejected')
                                                                <span class="badge badge-danger">Rejected</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href='{{ route('time_offs.show', $timeOff->id) }}'
                                                                    class="btn btn-sm btn-info btn-icon mr-1">
                                                                    <i class="fas fa-eye"></i>
                                                                    Detail
                                                                </a>

                                                                @if($timeOff->status == 'pending')
                                                                <a href='{{ route('time_offs.edit', $timeOff->id) }}'
                                                                    class="btn btn-sm btn-primary btn-icon mr-1">
                                                                    <i class="fas fa-edit"></i>
                                                                    Edit
                                                                </a>
                                                                @endif

                                                                <form action="{{ route('time_offs.destroy', $timeOff->id) }}"
                                                                    method="POST" class="ml-1">
                                                                    <input type="hidden" name="_method" value="DELETE" />
                                                                    <input type="hidden" name="_token"
                                                                        value="{{ csrf_token() }}" />
                                                                    <button class="btn btn-sm btn-danger btn-icon confirm-delete">
                                                                        <i class="fas fa-times"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if(count($allTimeOffs) == 0)
                                                    <tr>
                                                        <td colspan="7" class="text-center">No time off requests found</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                                        <div class="table-responsive mt-3">
                                            <table class="table-striped table">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Days</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                @foreach ($pendingTimeOffs as $timeOff)
                                                    <tr>
                                                        <td>{{ $timeOff->user->name }}</td>
                                                        <td>
                                                            @if($timeOff->type == 'annual_leave')
                                                                <span class="badge badge-success">Annual Leave</span>
                                                            @elseif($timeOff->type == 'sick_leave')
                                                                <span class="badge badge-danger">Sick Leave</span>
                                                            @elseif($timeOff->type == 'unpaid_leave')
                                                                <span class="badge badge-warning">Unpaid Leave</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($timeOff->start_date)->format('d M Y') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($timeOff->end_date)->format('d M Y') }}</td>
                                                        <td>{{ $timeOff->days }}</td>
                                                        <td>
                                                            <span class="badge badge-warning">Pending</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href='{{ route('time_offs.show', $timeOff->id) }}'
                                                                    class="btn btn-sm btn-info btn-icon mr-1">
                                                                    <i class="fas fa-eye"></i>
                                                                    Detail
                                                                </a>

                                                                <a href='{{ route('time_offs.edit', $timeOff->id) }}'
                                                                    class="btn btn-sm btn-primary btn-icon mr-1">
                                                                    <i class="fas fa-edit"></i>
                                                                    Edit
                                                                </a>

                                                                <form action="{{ route('time_offs.destroy', $timeOff->id) }}"
                                                                    method="POST" class="ml-1">
                                                                    <input type="hidden" name="_method" value="DELETE" />
                                                                    <input type="hidden" name="_token"
                                                                        value="{{ csrf_token() }}" />
                                                                    <button class="btn btn-sm btn-danger btn-icon confirm-delete">
                                                                        <i class="fas fa-times"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if(count($pendingTimeOffs) == 0)
                                                    <tr>
                                                        <td colspan="7" class="text-center">No pending time off requests found</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                                        <div class="table-responsive mt-3">
                                            <table class="table-striped table">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Days</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                @foreach ($approvedTimeOffs as $timeOff)
                                                    <tr>
                                                        <td>{{ $timeOff->user->name }}</td>
                                                        <td>
                                                            @if($timeOff->type == 'annual_leave')
                                                                <span class="badge badge-success">Annual Leave</span>
                                                            @elseif($timeOff->type == 'sick_leave')
                                                                <span class="badge badge-danger">Sick Leave</span>
                                                            @elseif($timeOff->type == 'unpaid_leave')
                                                                <span class="badge badge-warning">Unpaid Leave</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($timeOff->start_date)->format('d M Y') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($timeOff->end_date)->format('d M Y') }}</td>
                                                        <td>{{ $timeOff->days }}</td>
                                                        <td>
                                                            <span class="badge badge-success">Approved</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href='{{ route('time_offs.show', $timeOff->id) }}'
                                                                    class="btn btn-sm btn-info btn-icon mr-1">
                                                                    <i class="fas fa-eye"></i>
                                                                    Detail
                                                                </a>

                                                                <form action="{{ route('time_offs.destroy', $timeOff->id) }}"
                                                                    method="POST" class="ml-1">
                                                                    <input type="hidden" name="_method" value="DELETE" />
                                                                    <input type="hidden" name="_token"
                                                                        value="{{ csrf_token() }}" />
                                                                    <button class="btn btn-sm btn-danger btn-icon confirm-delete">
                                                                        <i class="fas fa-times"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if(count($approvedTimeOffs) == 0)
                                                    <tr>
                                                        <td colspan="7" class="text-center">No approved time off requests found</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                                        <div class="table-responsive mt-3">
                                            <table class="table-striped table">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Days</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                @foreach ($rejectedTimeOffs as $timeOff)
                                                    <tr>
                                                        <td>{{ $timeOff->user->name }}</td>
                                                        <td>
                                                            @if($timeOff->type == 'annual_leave')
                                                                <span class="badge badge-success">Annual Leave</span>
                                                            @elseif($timeOff->type == 'sick_leave')
                                                                <span class="badge badge-danger">Sick Leave</span>
                                                            @elseif($timeOff->type == 'unpaid_leave')
                                                                <span class="badge badge-warning">Unpaid Leave</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($timeOff->start_date)->format('d M Y') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($timeOff->end_date)->format('d M Y') }}</td>
                                                        <td>{{ $timeOff->days }}</td>
                                                        <td>
                                                            <span class="badge badge-danger">Rejected</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href='{{ route('time_offs.show', $timeOff->id) }}'
                                                                    class="btn btn-sm btn-info btn-icon mr-1">
                                                                    <i class="fas fa-eye"></i>
                                                                    Detail
                                                                </a>

                                                                <form action="{{ route('time_offs.destroy', $timeOff->id) }}"
                                                                    method="POST" class="ml-1">
                                                                    <input type="hidden" name="_method" value="DELETE" />
                                                                    <input type="hidden" name="_token"
                                                                        value="{{ csrf_token() }}" />
                                                                    <button class="btn btn-sm btn-danger btn-icon confirm-delete">
                                                                        <i class="fas fa-times"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if(count($rejectedTimeOffs) == 0)
                                                    <tr>
                                                        <td colspan="7" class="text-center">No rejected time off requests found</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="float-right mt-3">
                                    {{ $allTimeOffs->withQueryString()->links() }}
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
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
@endpush
