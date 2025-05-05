@extends('layouts.app')

@section('title', 'Time Off Requests')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Time Off Requests</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Time Off Requests</div>
                    </div>
                </div>
                <a href="{{ route('time_offs.create') }}" class="btn btn-primary-header">
                    <i class="fas fa-plus-circle">
                        </i> Create Time Off
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
                            <h4>All Time Off Requests</h4>
                            <div class="card-header-form">
                                <form method="GET" action="{{ route('time_offs.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search by name or type" name="search" value="{{ request('search') }}">
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
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Employee</th>
                                                    <th>Type</th>
                                                    <th>Date Range</th>
                                                    <th>Days</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($allTimeOffs as $timeOff)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar mr-2" style="width: 32px; height: 32px; background-color: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                                                    {{ substr($timeOff->user->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="font-weight-bold">{{ $timeOff->user->name }}</div>
                                                                    <div class="text-muted small">{{ $timeOff->user->position ?? 'No position' }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($timeOff->type == 'cuti_tahunan')
                                                                <span class="badge badge-success">Cuti Tahunan</span>
                                                            @elseif($timeOff->type == 'izin_jam_kerja')
                                                                <span class="badge badge-success">Izin Jam Kerja</span>
                                                            @elseif($timeOff->type == 'izin_sebelum_atau_sesudah_istirahat')
                                                                <span class="badge badge-primary">Izin Sebelum/Sesudah Istirahat</span>
                                                            @elseif($timeOff->type == 'cuti_umroh')
                                                                <span class="badge badge-info">Cuti Umroh</span>
                                                            @elseif($timeOff->type == 'cuti_haji')
                                                                <span class="badge badge-info">Cuti Haji</span>
                                                            @elseif($timeOff->type == 'dinas_dalam_kota')
                                                                <span class="badge badge-secondary">Dinas Dalam Kota</span>
                                                            @elseif($timeOff->type == 'dinas_luar_kota')
                                                                <span class="badge badge-secondary">Dinas Luar Kota</span>
                                                            @elseif($timeOff->type == 'izin_tidak_masuk')
                                                                <span class="badge badge-warning">Izin Tidak Masuk</span>
                                                            @elseif(strpos($timeOff->type, 'sakit') !== false)
                                                                <span class="badge badge-danger">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @elseif(strpos($timeOff->type, 'cuti') !== false)
                                                                <span class="badge badge-success">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @else
                                                                <span class="badge badge-dark">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div>{{ \Carbon\Carbon::parse($timeOff->start_date)->format('d M Y') }}</div>
                                                            <div class="text-muted small">to {{ \Carbon\Carbon::parse($timeOff->end_date)->format('d M Y') }}</div>
                                                        </td>
                                                        <td>
                                                            <span class="font-weight-bold">{{ $timeOff->days }}</span> days
                                                        </td>
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
                                                            <div class="action-buttons">
                                                                <a href="{{ route('time_offs.show', $timeOff->id) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('time_offs.edit', $timeOff->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('time_offs.destroy', $timeOff->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this time off request?')">
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
                                                                <i class="fas fa-calendar-times"></i>
                                                                <p>No time off requests found</p>
                                                                <a href="{{ route('time_offs.create') }}" class="btn btn-primary mt-3">
                                                                    <i class="fas fa-plus"></i> Create Time Off Request
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Pending Tab -->
                                <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Employee</th>
                                                    <th>Type</th>
                                                    <th>Date Range</th>
                                                    <th>Days</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($pendingTimeOffs as $timeOff)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar mr-2" style="width: 32px; height: 32px; background-color: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                                                    {{ substr($timeOff->user->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="font-weight-bold">{{ $timeOff->user->name }}</div>
                                                                    <div class="text-muted small">{{ $timeOff->user->position ?? 'No position' }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($timeOff->type == 'cuti_tahunan')
                                                                <span class="badge badge-success">Cuti Tahunan</span>
                                                            @elseif($timeOff->type == 'izin_jam_kerja')
                                                                <span class="badge badge-success">Izin Jam Kerja</span>
                                                            @elseif($timeOff->type == 'izin_sebelum_atau_sesudah_istirahat')
                                                                <span class="badge badge-primary">Izin Sebelum/Sesudah Istirahat</span>
                                                            @elseif($timeOff->type == 'cuti_umroh')
                                                                <span class="badge badge-info">Cuti Umroh</span>
                                                            @elseif($timeOff->type == 'cuti_haji')
                                                                <span class="badge badge-info">Cuti Haji</span>
                                                            @elseif($timeOff->type == 'dinas_dalam_kota')
                                                                <span class="badge badge-secondary">Dinas Dalam Kota</span>
                                                            @elseif($timeOff->type == 'dinas_luar_kota')
                                                                <span class="badge badge-secondary">Dinas Luar Kota</span>
                                                            @elseif($timeOff->type == 'izin_tidak_masuk')
                                                                <span class="badge badge-warning">Izin Tidak Masuk</span>
                                                            @elseif(strpos($timeOff->type, 'sakit') !== false)
                                                                <span class="badge badge-danger">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @elseif(strpos($timeOff->type, 'cuti') !== false)
                                                                <span class="badge badge-success">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @else
                                                                <span class="badge badge-dark">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div>{{ \Carbon\Carbon::parse($timeOff->start_date)->format('d M Y') }}</div>
                                                            <div class="text-muted small">to {{ \Carbon\Carbon::parse($timeOff->end_date)->format('d M Y') }}</div>
                                                        </td>
                                                        <td>
                                                            <span class="font-weight-bold">{{ $timeOff->days }}</span> days
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-warning">Pending</span>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ route('time_offs.show', $timeOff->id) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('time_offs.edit', $timeOff->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('time_offs.destroy', $timeOff->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this time off request?')">
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
                                                                <i class="fas fa-check-circle"></i>
                                                                <p>No pending time off requests</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Approved Tab -->
                                <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Employee</th>
                                                    <th>Type</th>
                                                    <th>Date Range</th>
                                                    <th>Days</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($approvedTimeOffs as $timeOff)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar mr-2" style="width: 32px; height: 32px; background-color: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                                                    {{ substr($timeOff->user->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="font-weight-bold">{{ $timeOff->user->name }}</div>
                                                                    <div class="text-muted small">{{ $timeOff->user->position ?? 'No position' }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($timeOff->type == 'cuti_tahunan')
                                                                <span class="badge badge-success">Cuti Tahunan</span>
                                                            @elseif($timeOff->type == 'izin_jam_kerja')
                                                                <span class="badge badge-success">Izin Jam Kerja</span>
                                                            @elseif($timeOff->type == 'izin_sebelum_atau_sesudah_istirahat')
                                                                <span class="badge badge-primary">Izin Sebelum/Sesudah Istirahat</span>
                                                            @elseif($timeOff->type == 'cuti_umroh')
                                                                <span class="badge badge-info">Cuti Umroh</span>
                                                            @elseif($timeOff->type == 'cuti_haji')
                                                                <span class="badge badge-info">Cuti Haji</span>
                                                            @elseif($timeOff->type == 'dinas_dalam_kota')
                                                                <span class="badge badge-secondary">Dinas Dalam Kota</span>
                                                            @elseif($timeOff->type == 'dinas_luar_kota')
                                                                <span class="badge badge-secondary">Dinas Luar Kota</span>
                                                            @elseif($timeOff->type == 'izin_tidak_masuk')
                                                                <span class="badge badge-warning">Izin Tidak Masuk</span>
                                                            @elseif(strpos($timeOff->type, 'sakit') !== false)
                                                                <span class="badge badge-danger">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @elseif(strpos($timeOff->type, 'cuti') !== false)
                                                                <span class="badge badge-success">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @else
                                                                <span class="badge badge-dark">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div>{{ \Carbon\Carbon::parse($timeOff->start_date)->format('d M Y') }}</div>
                                                            <div class="text-muted small">to {{ \Carbon\Carbon::parse($timeOff->end_date)->format('d M Y') }}</div>
                                                        </td>
                                                        <td>
                                                            <span class="font-weight-bold">{{ $timeOff->days }}</span> days
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-success">Approved</span>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ route('time_offs.show', $timeOff->id) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('time_offs.edit', $timeOff->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('time_offs.destroy', $timeOff->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this time off request?')">
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
                                                                <i class="fas fa-check-circle"></i>
                                                                <p>No approved time off requests</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Rejected Tab -->
                                <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Employee</th>
                                                    <th>Type</th>
                                                    <th>Date Range</th>
                                                    <th>Days</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($rejectedTimeOffs as $timeOff)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar mr-2" style="width: 32px; height: 32px; background-color: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                                                    {{ substr($timeOff->user->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="font-weight-bold">{{ $timeOff->user->name }}</div>
                                                                    <div class="text-muted small">{{ $timeOff->user->position ?? 'No position' }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($timeOff->type == 'cuti_tahunan')
                                                                <span class="badge badge-success">Cuti Tahunan</span>
                                                            @elseif($timeOff->type == 'izin_jam_kerja')
                                                                <span class="badge badge-success">Izin Jam Kerja</span>
                                                            @elseif($timeOff->type == 'izin_sebelum_atau_sesudah_istirahat')
                                                                <span class="badge badge-primary">Izin Sebelum/Sesudah Istirahat</span>
                                                            @elseif($timeOff->type == 'cuti_umroh')
                                                                <span class="badge badge-info">Cuti Umroh</span>
                                                            @elseif($timeOff->type == 'cuti_haji')
                                                                <span class="badge badge-info">Cuti Haji</span>
                                                            @elseif($timeOff->type == 'dinas_dalam_kota')
                                                                <span class="badge badge-secondary">Dinas Dalam Kota</span>
                                                            @elseif($timeOff->type == 'dinas_luar_kota')
                                                                <span class="badge badge-secondary">Dinas Luar Kota</span>
                                                            @elseif($timeOff->type == 'izin_tidak_masuk')
                                                                <span class="badge badge-warning">Izin Tidak Masuk</span>
                                                            @elseif(strpos($timeOff->type, 'sakit') !== false)
                                                                <span class="badge badge-danger">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @elseif(strpos($timeOff->type, 'cuti') !== false)
                                                                <span class="badge badge-success">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @else
                                                                <span class="badge badge-dark">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div>{{ \Carbon\Carbon::parse($timeOff->start_date)->format('d M Y') }}</div>
                                                            <div class="text-muted small">to {{ \Carbon\Carbon::parse($timeOff->end_date)->format('d M Y') }}</div>
                                                        </td>
                                                        <td>
                                                            <span class="font-weight-bold">{{ $timeOff->days }}</span> days
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-danger">Rejected</span>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ route('time_offs.show', $timeOff->id) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('time_offs.edit', $timeOff->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('time_offs.destroy', $timeOff->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this time off request?')">
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
                                                                <i class="fas fa-times-circle"></i>
                                                                <p>No rejected time off requests</p>
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
                                {{ $allTimeOffs->withQueryString()->links() }}
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
            const activeTab = localStorage.getItem('timeoff_active_tab');
            if (activeTab) {
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }

            // Store the active tab in localStorage
            $('#myTab a').on('shown.bs.tab', function (e) {
                localStorage.setItem('timeoff_active_tab', $(e.target).attr('href'));
            });

            // Confirm delete
            $('form').on('submit', function(e) {
                if ($(this).find('button[type="submit"]').hasClass('confirm-delete')) {
                    if (!confirm('Are you sure you want to delete this time off request?')) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
@endpush
