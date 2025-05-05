@extends('layouts.app')

@section('title', 'Create Broadcast')

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
        .document-preview {
            margin-top: 10px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Create Broadcast</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('broadcasts.index') }}">Broadcasts</a></div>
                        <div class="breadcrumb-item">Create Broadcast</div>
                    </div>
                </div>
                {{-- <a href="{{ route('broadcasts.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Broadcasts
                </a> --}}
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
                                <h4>Broadcast Form</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('broadcasts.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="title">Broadcast Title <span class="text-danger">*</span></label>
                                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="message">Message <span class="text-danger">*</span></label>
                                        <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" style="height: 150px;" required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="alert alert-info recipient-info">
                                        <i class="fas fa-info-circle mr-1"></i> You can choose to send this broadcast to all users or select specific recipients.
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="send_to_all" name="send_to_all" value="1" {{ old('send_to_all') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="send_to_all">Send to all users</label>
                                            <small class="form-text text-muted">This will send the broadcast to all users in the system ({{ \App\Models\User::count() }} users)</small>
                                        </div>
                                    </div>

                                    <div class="form-group" id="recipients-container">
                                        <label for="recipients">Recipients <span class="text-danger">*</span></label>
                                        <select id="recipients" name="recipients[]" class="form-control select2 @error('recipients') is-invalid @enderror" multiple>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ in_array($user->id, old('recipients', [])) ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Select the users who should receive this broadcast</small>
                                        @error('recipients')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="document">Attachment (Optional)</label>
                                        <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror">
                                        <small class="form-text text-muted">Format yang diperbolehkan: jpeg, png, jpg, pdf, doc, docx, xls, xlsx. Maksimal 10MB.</small>
                                        @error('file')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Send Immediately</option>
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
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Select recipients",
                allowClear: true
            });

            // Toggle recipients field based on send_to_all checkbox
            function toggleRecipientsContainer() {
                const sendToAll = $('#send_to_all').is(':checked');

                if (sendToAll) {
                    $('#recipients-container').addClass('hidden');
                    $('#recipients').prop('required', false);
                } else {
                    $('#recipients-container').removeClass('hidden');
                    $('#recipients').prop('required', true);
                }
            }

            // Toggle recipients on checkbox change
            $('#send_to_all').on('change', function() {
                toggleRecipientsContainer();

                // Clear the select2 selection when switching to "send to all"
                if ($(this).is(':checked')) {
                    $('#recipients').val(null).trigger('change');
                }
            });

            // Initialize on page load
            toggleRecipientsContainer();
        });
    </script>
@endpush
