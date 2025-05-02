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
        <div class="section-header d-flex justify-content-between align-items-center">
            <div>
                <h1>Time Off Request Detail</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('time_offs.index') }}">Time Off</a></div>
                    <div class="breadcrumb-item">Time Off Request Detail</div>
                </div>
            </div>
                {{-- <a href="{{ route('time_offs.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-1">
                        </i> Back to Time Off
                </a> --}}
            </div>
        <div>

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
                                {{-- <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('time_offs.index') }}" class="btn btn-primary">
                                            <i class="fas fa-arrow-left"></i> Back to List
                                        </a>
                                    </div>
                                </div> --}}
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
