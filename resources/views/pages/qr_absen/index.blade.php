@extends('layouts.app')

@section('title', 'QR Absen')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <style>
        .section-header {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            background-color: #fff;
            border-radius: 3px;
            border: none;
            position: relative;
            margin-bottom: 30px;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .section-header h1 {
            margin-bottom: 0;
            font-weight: 700;
            display: inline-block;
            font-size: 24px;
            margin-top: 3px;
            color: #34395e;
        }

        .section-header-breadcrumb {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .section-header-breadcrumb .breadcrumb-item {
            font-size: 12px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            background-color: #fff;
            border-radius: 3px;
            border: none;
            position: relative;
            margin-bottom: 30px;
        }

        .card-header {
            border-bottom-color: #f9f9f9;
            line-height: 30px;
            align-items: center;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
        }

        .card-header h4 {
            font-size: 16px;
            line-height: 28px;
            padding-right: 10px;
            margin-bottom: 0;
            font-weight: 600;
            color: #34395e;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            color: #212529;
            background-color: transparent;
            border-collapse: collapse;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table th {
            padding: 12px 15px;
            border-top: none;
            font-weight: 600;
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-top: 1px solid #e3eaef;
        }

        .btn-primary {
            background-color: #6777ef;
            border-color: #6777ef;
            color: white;
        }

        .btn-info {
            background-color: #3abaf4;
            border-color: #3abaf4;
            color: white;
        }

        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-item {
            flex: 1;
            min-width: 200px;
        }

        .filter-item label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            margin-bottom: 5px;
        }

        .filter-item select, .filter-item input {
            width: 100%;
            padding: 8px 12px;
            border-radius: 3px;
            border: 1px solid #e3eaef;
            font-size: 13px;
        }

        .qr-code-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            background-color: #eef2ff;
            color: #6777ef;
        }

        .qr-code-badge.check-in {
            background-color: #e3f2fd;
            color: #2196f3;
        }

        .qr-code-badge.check-out {
            background-color: #fff8e1;
            color: #ffc107;
        }
    </style>
@endpush

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
                <a href="{{ route('qr_absens.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1"></i> Generate QR
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
                            <div class="card-header">
                                <h4>All QR Codes</h4>
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
                                <div class="filters">
                                    <div class="filter-item">
                                        <label for="month-filter">Filter by Month</label>
                                        <input type="month" id="month-filter" class="form-control">
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>DATE</th>
                                                <th>QR CHECK-IN</th>
                                                <th>QR CHECK-OUT</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($qrAbsen) && $qrAbsen->count() > 0)
                                            @foreach ($qrAbsen as $qr)
                                                <tr>
                                                    <td>{{ $qr->id }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($qr->date)->format('M d, Y') }}</td>
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
                                                        <div class="d-flex">
                                                            <a href='{{ route('qr_absens.show', $qr->id) }}'
                                                                class="btn btn-sm btn-info mr-2">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href='{{ route('qr_absens.download', $qr->id) }}'
                                                                class="btn btn-sm btn-primary">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <i class="fas fa-qrcode mb-3" style="font-size: 40px; color: #cdd3d8;"></i>
                                                    <h5 style="font-size: 16px; color: #6c757d;">No QR Codes Found</h5>
                                                    <p style="color: #6c757d; max-width: 400px; margin: 0 auto;">There are no QR codes matching your search criteria.</p>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>

                                @if(isset($qrAbsen) && $qrAbsen->count() > 0)
                                    <div class="float-right mt-4">
                                        {{ $qrAbsen->withQueryString()->links() }}
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
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Month filter functionality
            const monthFilter = document.getElementById('month-filter');
            monthFilter.addEventListener('change', function() {
                filterTable();
            });

            function filterTable() {
                const monthValue = monthFilter.value;
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    let showRow = true;

                    // Month filtering
                    if (monthValue) {
                        const dateCell = row.cells[1].textContent.trim();
                        const rowDate = new Date(dateCell);
                        const filterDate = new Date(monthValue + '-01');

                        if (rowDate.getMonth() !== filterDate.getMonth() ||
                            rowDate.getFullYear() !== filterDate.getFullYear()) {
                            showRow = false;
                        }
                    }

                    row.style.display = showRow ? '' : 'none';
                });
            }
        });
    </script>
@endpush
