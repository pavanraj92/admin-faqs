@extends('admin::admin.layouts.master')

@section('title', 'Faqs Management')

@section('page-title', isset($faq) ? 'Edit Faq' : 'Create Faq')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.faqs.index') }}">Faq Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ isset($faq) ? 'Edit Faq' : 'Create Faq' }}</li>
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Start faq Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <form action="{{ isset($faq) ? route('admin.faqs.update', $faq->id) : route('admin.faqs.store') }}"
                        method="POST" id="faqForm">
                        @if (isset($faq))
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Question<span class="text-danger">*</span></label>
                                    <input type="text" name="question" class="form-control"
                                        value="{{ $faq?->question ?? old('question') }}" required>
                                    @error('question')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status<span class="text-danger">*</span></label>
                                    <select name="status" class="form-control select2" required>
                                        @foreach (config('faq.constants.status', []) as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ (isset($faq) && (string) $faq?->status === (string) $key) || old('status') === (string) $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Answer<span class="text-danger">*</span></label>
                            <textarea name="answer" id="answer" class="form-control answer-editor">{{ $faq?->answer ?? old('answer') }}</textarea>
                            @error('answer')
                                <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"
                                id="saveBtn">{{ isset($faq) ? 'Update' : 'Save' }}</button>
                            <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End faq Content -->
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#answer').summernote({
                height: 250, // ✅ editor height
                minHeight: 250,
                maxHeight: 250,
                toolbar: [
                    // ✨ Add "code view" toggle button
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture']],
                    ['view', ['codeview']],
                    ['undo', ['undo', 'redo']]
                ],
                callbacks: {
                    onChange: function(contents, $editable) {
                        // keep textarea updated
                        $('#answer').val(contents);
                        // trigger validation if needed
                        $('#answer').trigger('keyup');
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for any select elements with the class 'select2'
            $('.select2').select2();

            //jquery validation for the form
            $('#faqForm').validate({
                ignore: [],
                rules: {
                    question: {
                        required: true,
                        minlength: 3
                    },
                    answer: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    question: {
                        required: "Please enter a question",
                        minlength: "Question must be at least 3 characters long"
                    },
                    answer: {
                        required: "Please enter answer",
                        minlength: "Answer must be at least 3 characters long"
                    }
                },
                submitHandler: function(form) {
                    // Update textarea before submit
                    $('#answer').val($('#answer').summernote('code'));

                    const $btn = $('#saveBtn');
                    if (element.attr("id") === "answer") {
                        error.insertAfter($('.note-editor'));
                    } else {
                        $btn.prop('disabled', true).text('Saving...');
                    }

                    // Now submit
                    form.submit();
                },
                errorElement: 'div',
                errorClass: 'text-danger custom-error',
                errorPlacement: function(error, element) {
                    $('.validation-error').hide(); // hide blade errors
                    if (element.attr("id") === "answer") {
                        error.insertAfter($('.ck-editor')); // show below CKEditor UI
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>
@endpush
