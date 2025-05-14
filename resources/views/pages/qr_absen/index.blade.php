@extends('layouts.app')

@section('title', 'QR Absen')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>QR Absen</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">QR Absen</div>
                    </div>
                </div>
                <a href="{{ route('qr_absens.create') }}" class="btn btn-primary-header">
                    <i class="fas fa-plus-circle mr-1">
                        </i> Generate QR
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
                            <h4>QR Codes</h4>
                            <div class="card-header-form">
                                <form method="GET" action="{{ route('qr_absens.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search by date" name="search" value="{{ request('search') }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="qrTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-controls="active" aria-selected="false">Active</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="expired-tab" data-toggle="tab" href="#expired" role="tab" aria-controls="expired" aria-selected="false">Expired</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="qrTabsContent">
                                <!-- All QR Codes Tab -->
                                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                                    <div class="filters-container mt-3 mb-3">
                                        <div class="filter-item">
                                            <label for="month-filter">Filter by Month</label>
                                            <input type="month" id="month-filter" class="form-control" value="{{ request('month') }}">
                                        </div>
                                    </div>

                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>DATE</th>
                                                    <th>QR CHECK-IN</th>
                                                    <th>QR CHECK-OUT</th>
                                                    <th>STATUS</th>
                                                    <th class="text-center">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($qrAbsen->count() > 0)
                                                    @foreach ($qrAbsen as $qr)
                                                        <tr>
                                                            <td>
                                                                <div class="font-weight-bold">{{ \Carbon\Carbon::parse($qr->date)->format('d M Y') }}</div>
                                                                <div class="text-muted small">{{ \Carbon\Carbon::parse($qr->date)->format('l') }}</div>
                                                            </td>
                                                            <td>
                                                                <div class="badge badge-success">
                                                                    <i class="fas fa-qrcode mr-1"></i>
                                                                    {{ $qr->qr_checkin }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="badge badge-warning">
                                                                    <i class="fas fa-qrcode mr-1"></i>
                                                                    {{ substr($qr->qr_checkout, 0, 12) }}...
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @if(\Carbon\Carbon::parse($qr->date)->isToday())
                                                                    <span class="badge badge-success">Active Today</span>
                                                                @elseif(\Carbon\Carbon::parse($qr->date)->isPast())
                                                                    <span class="badge badge-danger">Expired</span>
                                                                @else
                                                                    <span class="badge badge-info">Upcoming</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="action-buttons">
                                                                    <a href="{{ route('qr_absens.show', $qr->id) }}" class="btn btn-info btn-sm">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('qr_absens.download', $qr->id) }}" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            <div class="empty-state">
                                                                <i class="fas fa-qrcode"></i>
                                                                <h5>No QR Codes Found</h5>
                                                                <p>There are no QR codes matching your search criteria.</p>
                                                                <a href="{{ route('qr_absens.create') }}" class="btn btn-primary">
                                                                    <i class="fas fa-plus"></i> Generate QR Code
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Active QR Codes Tab -->
                                <div class="tab-pane fade" id="active" role="tabpanel" aria-labelledby="active-tab">
                                    <div class="filters-container mt-3 mb-3">
                                        <div class="filter-item">
                                            <label for="active-month-filter">Filter by Month</label>
                                            <input type="month" id="active-month-filter" class="form-control">
                                        </div>
                                    </div>

                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>DATE</th>
                                                    <th>QR CHECK-IN</th>
                                                    <th>QR CHECK-OUT</th>
                                                    <th>STATUS</th>
                                                    <th class="text-center">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $activeQrs = $qrAbsen->filter(function($qr) { return \Carbon\Carbon::parse($qr->date)->isToday(); }); @endphp
                                                @if($activeQrs->count() > 0)
                                                    @foreach ($activeQrs as $qr)
                                                        <tr>
                                                            <td>
                                                                <div class="font-weight-bold">{{ \Carbon\Carbon::parse($qr->date)->format('d M Y') }}</div>
                                                                <div class="text-muted small">{{ \Carbon\Carbon::parse($qr->date)->format('l') }}</div>
                                                            </td>
                                                            <td>
                                                                <div class="qr-code-badge check-in">
                                                                    <i class="fas fa-qrcode mr-1"></i>
                                                                    {{ substr($qr->qr_checkin, 0, 12) }}...
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="qr-code-badge check-out">
                                                                    <i class="fas fa-qrcode mr-1"></i>
                                                                    {{ substr($qr->qr_checkout, 0, 12) }}...
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-success">Active Today</span>
                                                            </td>
                                                            <td>
                                                                <div class="action-buttons">
                                                                    <a href="{{ route('qr_absens.show', $qr->id) }}" class="btn btn-info btn-sm">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('qr_absens.download', $qr->id) }}" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center">
                                                            <div class="empty-state">
                                                                <i class="fas fa-qrcode"></i>
                                                                <h5>There are no active QR codes</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Expired QR Codes Tab -->
                                <div class="tab-pane fade" id="expired" role="tabpanel" aria-labelledby="expired-tab">
                                    <div class="filters-container mt-3 mb-3">
                                        <div class="filter-item">
                                            <label for="expired-month-filter">Filter by Month</label>
                                            <input type="month" id="expired-month-filter" class="form-control">
                                        </div>
                                    </div>

                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>DATE</th>
                                                    <th>QR CHECK-IN</th>
                                                    <th>QR CHECK-OUT</th>
                                                    <th>STATUS</th>
                                                    <th class="text-center">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $expiredQrs = $qrAbsen->filter(function($qr) { return \Carbon\Carbon::parse($qr->date)->isPast() && !\Carbon\Carbon::parse($qr->date)->isToday(); }); @endphp
                                                @if($expiredQrs->count() > 0)
                                                    @foreach ($expiredQrs as $qr)
                                                        <tr>
                                                            <td>
                                                                <div class="font-weight-bold">{{ \Carbon\Carbon::parse($qr->date)->format('d M Y') }}</div>
                                                                <div class="text-muted small">{{ \Carbon\Carbon::parse($qr->date)->format('l') }}</div>
                                                            </td>
                                                            <td>
                                                                <div class="qr-code-badge check-in">
                                                                    <i class="fas fa-qrcode mr-1"></i>
                                                                    {{ substr($qr->qr_checkin, 0, 12) }}...
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="qr-code-badge check-out">
                                                                    <i class="fas fa-qrcode mr-1"></i>
                                                                    {{ substr($qr->qr_checkout, 0, 12) }}...
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-danger">Expired</span>
                                                            </td>
                                                            <td>
                                                                <div class="action-buttons">
                                                                    <a href="{{ route('qr_absens.show', $qr->id) }}" class="btn btn-info btn-sm">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('qr_absens.download', $qr->id) }}" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            <div class="empty-state">
                                                                <i class="fas fa-qrcode"></i>
                                                                <h5>No Expired QR Codes</h5>
                                                                <p>There are no expired QR codes in the system.</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @if($qrAbsen->count() > 0)
                                <div class="float-right mt-3">
                                    {{ $qrAbsen->withQueryString()->links() }}
                                </div>
                            @endif
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
            const activeTab = localStorage.getItem('qr_active_tab');
            if (activeTab) {
                $('#qrTabs a[href="' + activeTab + '"]').tab('show');
            }

            // Store the active tab in localStorage
            $('#qrTabs a').on('shown.bs.tab', function (e) {
                localStorage.setItem('qr_active_tab', $(e.target).attr('href'));
            });

            // Month filter functionality
            $('.filter-item input[type="month"]').on('change', function() {
                const monthValue = $(this).val();
                const searchParams = new URLSearchParams(window.location.search);

                if (monthValue) {
                    searchParams.set('month', monthValue);
                } else {
                    searchParams.delete('month');
                }

                window.location.search = searchParams.toString();
            });
        });
    </script>
@endpush
