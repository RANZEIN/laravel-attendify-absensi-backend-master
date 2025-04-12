@extends('layouts.app')

@section('title', 'Create Time Off Request')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <style>
        .calendar-container {
            margin-bottom: 20px;
        }
        .date-range-container {
            display: flex;
            gap: 15px;
        }
        .date-input {
            flex: 1;
        }
        .manual-days-input {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }
        .hidden {
            display: none;
        }
        .alert-info {
            background-color: #e3f2fd;
            color: #0c63e4;
            border-color: #b6d4fe;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Create Time Off Request</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('time_offs.index') }}">Time Offs</a></div>
                    <div class="breadcrumb-item">Create Time Off Request</div>
                </div>
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
                                <h4>Time Off Request Form</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('time_offs.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="user_id">Employee</label>
                                        <select name="user_id" id="user_id" class="form-control select2 @error('user_id') is-invalid @enderror">
                                            <option value="">Select Employee</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->position }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="type">Time Off Type</label>
                                        <select name="type" id="type" class="form-control select2 @error('type') is-invalid @enderror">
                                            <option value="">Select Type</option>
                                            <option value="cuti_tahunan" {{ old('type') == 'cuti_tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                                            <option value="izin_jam_kerja" {{ old('type') == 'izin_jam_kerja' ? 'selected' : '' }}>Izin Jam Kerja</option>
                                            <option value="izin_sebelum_atau_sesudah_istirahat" {{ old('type') == 'izin_sebelum_atau_sesudah_istirahat' ? 'selected' : '' }}>Izin Sebelum/Sesudah Istirahat</option>
                                            <option value="cuti_umroh" {{ old('type') == 'cuti_umroh' ? 'selected' : '' }}>Cuti Umroh</option>
                                            <option value="cuti_haji" {{ old('type') == 'cuti_haji' ? 'selected' : '' }}>Cuti Haji</option>
                                            <option value="dinas_dalam_kota" {{ old('type') == 'dinas_dalam_kota' ? 'selected' : '' }}>Dinas Dalam Kota</option>
                                            <option value="dinas_luar_kota" {{ old('type') == 'dinas_luar_kota' ? 'selected' : '' }}>Dinas Luar Kota</option>
                                            <option value="izin_tidak_masuk" {{ old('type') == 'izin_tidak_masuk' ? 'selected' : '' }}>Izin Tidak Masuk</option>
                                            <option value="sakit_berkepanjangan_12_bulan_pertama" {{ old('type') == 'sakit_berkepanjangan_12_bulan_pertama' ? 'selected' : '' }}>Sakit Berkepanjangan (12 Bulan Pertama)</option>
                                            <option value="sakit_berkepanjangan_4_bulan_pertama" {{ old('type') == 'sakit_berkepanjangan_4_bulan_pertama' ? 'selected' : '' }}>Sakit Berkepanjangan (4 Bulan Pertama)</option>
                                            <option value="sakit_berkepanjangan_8_bulan_pertama" {{ old('type') == 'sakit_berkepanjangan_8_bulan_pertama' ? 'selected' : '' }}>Sakit Berkepanjangan (8 Bulan Pertama)</option>
                                            <option value="sakit_berkepanjangan_diatas_12_bulan_pertama" {{ old('type') == 'sakit_berkepanjangan_diatas_12_bulan_pertama' ? 'selected' : '' }}>Sakit Berkepanjangan (>12 Bulan)</option>
                                            <option value="sakit_dengan_surat_dokter" {{ old('type') == 'sakit_dengan_surat_dokter' ? 'selected' : '' }}>Sakit dengan Surat Dokter</option>
                                            <option value="sakit_tanpa_surat_dokter" {{ old('type') == 'sakit_tanpa_surat_dokter' ? 'selected' : '' }}>Sakit tanpa Surat Dokter</option>
                                            <option value="cuti_menikah" {{ old('type') == 'cuti_menikah' ? 'selected' : '' }}>Cuti Menikah</option>
                                            <option value="cuti_menikahkan_anak" {{ old('type') == 'cuti_menikahkan_anak' ? 'selected' : '' }}>Cuti Menikahkan Anak</option>
                                            <option value="cuti_khitanan_anak" {{ old('type') == 'cuti_khitanan_anak' ? 'selected' : '' }}>Cuti Khitanan Anak</option>
                                            <option value="cuti_istri_melahirkan_atau_keguguran" {{ old('type') == 'cuti_istri_melahirkan_atau_keguguran' ? 'selected' : '' }}>Cuti Istri Melahirkan/Keguguran</option>
                                            <option value="cuti_keluarga_meninggal" {{ old('type') == 'cuti_keluarga_meninggal' ? 'selected' : '' }}>Cuti Keluarga Meninggal</option>
                                            <option value="cuti_anggota_keluarga_dalam_satu_rumah_meninggal" {{ old('type') == 'cuti_anggota_keluarga_dalam_satu_rumah_meninggal' ? 'selected' : '' }}>Cuti Anggota Keluarga Serumah Meninggal</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="alert alert-info auto-calculate-info">
                                        <i class="fas fa-info-circle mr-1"></i> Untuk tipe cuti: <strong>Cuti Tahunan, Izin Tidak Masuk, Sakit dengan/tanpa Surat Dokter</strong>, jumlah hari akan dihitung otomatis berdasarkan tanggal yang dipilih.
                                    </div>

                                    <div class="calendar-container">
                                        <div class="form-group">
                                            <label>Tanggal Cuti</label>
                                            <div class="date-range-container">
                                                <div class="date-input">
                                                    <label for="start_date">Tanggal Mulai</label>
                                                    <input type="text" name="start_date" id="start_date" class="form-control datepicker @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                                    @error('start_date')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="date-input">
                                                    <label for="end_date">Tanggal Selesai</label>
                                                    <input type="text" name="end_date" id="end_date" class="form-control datepicker @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                                    @error('end_date')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group manual-days-input" id="manual-days-container">
                                        <label for="days">Jumlah Hari Cuti</label>
                                        <input type="number" name="days" id="days" class="form-control @error('days') is-invalid @enderror" value="{{ old('days') }}" min="1">
                                        <small class="form-text text-muted">Masukkan jumlah hari cuti secara manual untuk tipe cuti ini.</small>
                                        @error('days')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="reason">Alasan</label>
                                        <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" style="height: 100px">{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="document">Dokumen Pendukung (Opsional)</label>
                                        <input type="file" name="document" id="document" class="form-control @error('document') is-invalid @enderror">
                                        <small class="form-text text-muted">Format yang diperbolehkan: jpeg, png, jpg, pdf. Maksimal 2MB.</small>
                                        @error('document')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            Submit
                                        </button>
                                    </div>
                                </form>
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
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Initialize Datepicker
            $('.datepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Auto-calculate days for specific leave types
            const autoCalculateTypes = ['cuti_tahunan', 'izin_tidak_masuk', 'sakit_dengan_surat_dokter', 'sakit_tanpa_surat_dokter'];

            function calculateDays() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                if (startDate && endDate) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);

                    // Calculate the difference in days
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include both start and end dates

                    $('#days').val(diffDays);
                }
            }

            function toggleManualDaysInput() {
                const selectedType = $('#type').val();

                if (autoCalculateTypes.includes(selectedType)) {
                    $('#manual-days-container').addClass('hidden');
                    $('.auto-calculate-info').removeClass('hidden');
                    calculateDays();
                } else {
                    $('#manual-days-container').removeClass('hidden');
                    $('.auto-calculate-info').addClass('hidden');
                }
            }

            // Calculate days when dates change
            $('#start_date, #end_date').on('change', function() {
                const selectedType = $('#type').val();
                if (autoCalculateTypes.includes(selectedType)) {
                    calculateDays();
                }
            });

            // Toggle manual days input when type changes
            $('#type').on('change', toggleManualDaysInput);

            // Initialize on page load
            toggleManualDaysInput();
        });
    </script>
@endpush
