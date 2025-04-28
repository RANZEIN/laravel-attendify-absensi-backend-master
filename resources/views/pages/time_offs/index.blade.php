@extends('layouts.app')

@section('title', 'Time Off Requests')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Time Off Request</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Time Off Request</div>
                    </div>
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
                                            <input type="text" class="form-control" placeholder="Search by title" name="search" value="{{ request('search') }}">
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
                                                            @if($timeOff->type == 'cuti_tahunan')
                                                                <span class="badge badge-success">Cuti Tahunan</span>
                                                            @elseif($timeOff->type == 'izin_jam_kerja')
                                                                <span class="badge badge-primary">Izin Jam Kerja</span>
                                                            @elseif($timeOff->type == 'izin_sebelum_atau_sesudah_istirahat')
                                                                <span class="badge badge-primary">Izin Sebelum atau Sesudah Istirahat</span>
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
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_12_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 12 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_4_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 4 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_8_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 8 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_diatas_12_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit Diatas 12 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_dengan_surat_dokter')
                                                                <span class="badge badge-danger">Sakit dengan Surat Dokter</span>
                                                            @elseif($timeOff->type == 'sakit_tanpa_surat_dokter')
                                                                <span class="badge badge-danger">Sakit tanpa Surat Dokter</span>
                                                            @elseif($timeOff->type == 'cuti_menikah')
                                                                <span class="badge badge-success">Cuti Menikah</span>
                                                            @elseif($timeOff->type == 'cuti_menikahkan_anak')
                                                                <span class="badge badge-success">Cuti Menikahkan Anak</span>
                                                            @elseif($timeOff->type == 'cuti_khitanan_anak')
                                                                <span class="badge badge-success">Cuti Khitanan Anak</span>
                                                            @elseif($timeOff->type == 'cuti_istri_melahirkan_atau_keguguran')
                                                                <span class="badge badge-success">Cuti Istri Melahirkan atau Keguguran</span>
                                                            @elseif($timeOff->type == 'cuti_keluarga_meninggal')
                                                                <span class="badge badge-dark">Cuti Keluarga Meninggal</span>
                                                            @elseif($timeOff->type == 'cuti_anggota_keluarga_dalam_satu_rumah_meninggal')
                                                                <span class="badge badge-dark">Cuti Anggota Keluarga Dalam Satu Rumah Meninggal</span>
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
                                                            @if($timeOff->type == 'cuti_tahunan')
                                                                <span class="badge badge-success">Cuti Tahunan</span>
                                                            @elseif($timeOff->type == 'izin_jam_kerja')
                                                                <span class="badge badge-primary">Izin Jam Kerja</span>
                                                            @elseif($timeOff->type == 'izin_sebelum_atau_sesudah_istirahat')
                                                                <span class="badge badge-primary">Izin Sebelum atau Sesudah Istirahat</span>
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
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_12_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 12 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_4_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 4 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_8_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 8 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_diatas_12_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit Diatas 12 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_dengan_surat_dokter')
                                                                <span class="badge badge-danger">Sakit dengan Surat Dokter</span>
                                                            @elseif($timeOff->type == 'sakit_tanpa_surat_dokter')
                                                                <span class="badge badge-danger">Sakit tanpa Surat Dokter</span>
                                                            @elseif($timeOff->type == 'cuti_menikah')
                                                                <span class="badge badge-success">Cuti Menikah</span>
                                                            @elseif($timeOff->type == 'cuti_menikahkan_anak')
                                                                <span class="badge badge-success">Cuti Menikahkan Anak</span>
                                                            @elseif($timeOff->type == 'cuti_khitanan_anak')
                                                                <span class="badge badge-success">Cuti Khitanan Anak</span>
                                                            @elseif($timeOff->type == 'cuti_istri_melahirkan_atau_keguguran')
                                                                <span class="badge badge-success">Cuti Istri Melahirkan atau Keguguran</span>
                                                            @elseif($timeOff->type == 'cuti_keluarga_meninggal')
                                                                <span class="badge badge-dark">Cuti Keluarga Meninggal</span>
                                                            @elseif($timeOff->type == 'cuti_anggota_keluarga_dalam_satu_rumah_meninggal')
                                                                <span class="badge badge-dark">Cuti Anggota Keluarga Dalam Satu Rumah Meninggal</span>
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
                                                            @if($timeOff->type == 'cuti_tahunan')
                                                                <span class="badge badge-success">Cuti Tahunan</span>
                                                            @elseif($timeOff->type == 'izin_jam_kerja')
                                                                <span class="badge badge-primary">Izin Jam Kerja</span>
                                                            @elseif($timeOff->type == 'izin_sebelum_atau_sesudah_istirahat')
                                                                <span class="badge badge-primary">Izin Sebelum atau Sesudah Istirahat</span>
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
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_12_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 12 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_4_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 4 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_8_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 8 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_diatas_12_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit Diatas 12 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_dengan_surat_dokter')
                                                                <span class="badge badge-danger">Sakit dengan Surat Dokter</span>
                                                            @elseif($timeOff->type == 'sakit_tanpa_surat_dokter')
                                                                <span class="badge badge-danger">Sakit tanpa Surat Dokter</span>
                                                            @elseif($timeOff->type == 'cuti_menikah')
                                                                <span class="badge badge-success">Cuti Menikah</span>
                                                            @elseif($timeOff->type == 'cuti_menikahkan_anak')
                                                                <span class="badge badge-success">Cuti Menikahkan Anak</span>
                                                            @elseif($timeOff->type == 'cuti_khitanan_anak')
                                                                <span class="badge badge-success">Cuti Khitanan Anak</span>
                                                            @elseif($timeOff->type == 'cuti_istri_melahirkan_atau_keguguran')
                                                                <span class="badge badge-success">Cuti Istri Melahirkan atau Keguguran</span>
                                                            @elseif($timeOff->type == 'cuti_keluarga_meninggal')
                                                                <span class="badge badge-dark">Cuti Keluarga Meninggal</span>
                                                            @elseif($timeOff->type == 'cuti_anggota_keluarga_dalam_satu_rumah_meninggal')
                                                                <span class="badge badge-dark">Cuti Anggota Keluarga Dalam Satu Rumah Meninggal</span>
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
                                                            @if($timeOff->type == 'cuti_tahunan')
                                                                <span class="badge badge-success">Cuti Tahunan</span>
                                                            @elseif($timeOff->type == 'izin_jam_kerja')
                                                                <span class="badge badge-primary">Izin Jam Kerja</span>
                                                            @elseif($timeOff->type == 'izin_sebelum_atau_sesudah_istirahat')
                                                                <span class="badge badge-primary">Izin Sebelum atau Sesudah Istirahat</span>
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
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_12_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 12 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_4_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 4 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_8_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit 8 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_berkepanjangan_diatas_12_bulan_pertama')
                                                                <span class="badge badge-danger">Sakit Diatas 12 Bulan Pertama</span>
                                                            @elseif($timeOff->type == 'sakit_dengan_surat_dokter')
                                                                <span class="badge badge-danger">Sakit dengan Surat Dokter</span>
                                                            @elseif($timeOff->type == 'sakit_tanpa_surat_dokter')
                                                                <span class="badge badge-danger">Sakit tanpa Surat Dokter</span>
                                                            @elseif($timeOff->type == 'cuti_menikah')
                                                                <span class="badge badge-success">Cuti Menikah</span>
                                                            @elseif($timeOff->type == 'cuti_menikahkan_anak')
                                                                <span class="badge badge-success">Cuti Menikahkan Anak</span>
                                                            @elseif($timeOff->type == 'cuti_khitanan_anak')
                                                                <span class="badge badge-success">Cuti Khitanan Anak</span>
                                                            @elseif($timeOff->type == 'cuti_istri_melahirkan_atau_keguguran')
                                                                <span class="badge badge-success">Cuti Istri Melahirkan atau Keguguran</span>
                                                            @elseif($timeOff->type == 'cuti_keluarga_meninggal')
                                                                <span class="badge badge-dark">Cuti Keluarga Meninggal</span>
                                                            @elseif($timeOff->type == 'cuti_anggota_keluarga_dalam_satu_rumah_meninggal')
                                                                <span class="badge badge-dark">Cuti Anggota Keluarga Dalam Satu Rumah Meninggal</span>
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
