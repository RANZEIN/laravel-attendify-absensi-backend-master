@extends('layouts.app')

@section('title', 'Edit Broadcast')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <style>

    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Edit Broadcast</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('broadcasts.index') }}">Broadcasts</a></div>
                        <div class="breadcrumb-item">Edit Broadcast</div>
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
                                <form action="{{ route('broadcasts.update', $broadcast->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="title">Broadcast Title <span class="text-danger">*</span></label>
                                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $broadcast->title) }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="message">Message <span class="text-danger">*</span></label>
                                        <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" style="height: 150px;" required>{{ old('message', $broadcast->message) }}</textarea>
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
                                            <input type="checkbox" class="custom-control-input" id="send_to_all" name="send_to_all" value="1" {{ old('send_to_all', $broadcast->send_to_all) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="send_to_all">Send to all users</label>
                                            <small class="form-text text-muted">This will send the broadcast to all users in the system ({{ \App\Models\User::count() }} users)</small>
                                        </div>
                                    </div>

                                    <div class="form-group" id="recipients-container">
                                        <label for="recipients">Recipients <span class="text-danger">*</span></label>
                                        <select id="recipients" name="recipients[]" class="form-control select2 @error('recipients') is-invalid @enderror" multiple>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ in_array($user->id, old('recipients', $selectedRecipients)) ? 'selected' : '' }}>
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
                                        <label for="file">Attachment (Optional)</label>
                                        <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror">
                                        <small class="form-text text-muted">Format yang diperbolehkan: jpeg, png, jpg, pdf, doc, docx, xls, xlsx. Maksimal 10MB.</small>

                                        @if ($broadcast->file_path)
                                            <div class="document-preview mt-2">
                                                <p>Current attachment:
                                                    <a href="{{ Storage::url($broadcast->file_path) }}" target="_blank">
                                                        {{ $broadcast->file_name }}
                                                    </a>
                                                </p>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="remove_file" name="remove_file" value="1">
                                                    <label class="custom-control-label" for="remove_file">Remove current attachment</label>
                                                </div>
                                                <small class="text-muted">Upload a new file to replace the current one or check the box to remove it.</small>
                                            </div>
                                        @endif

                                        @error('file')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="draft" {{ old('status', $broadcast->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="sent" {{ old('status', $broadcast->status) == 'sent' ? 'selected' : '' }}>Send Immediately</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            Update
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


@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>

    <!-- Page Specific JS File -->
@endpush
