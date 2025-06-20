@extends('admin::admin.layouts.master')

@section('title', 'Faqs Management')

@section('page-title', 'Create Faq')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.faqs.index') }}">Manage Faqs</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create Faq</li>
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
                                        <option value="0" {{ (($page?->status ?? old('status')) == '0') ? 'selected' : '' }}>InActive</option>
                                        <option value="1" {{ (($page?->status ?? old('status')) == '1') ? 'selected' : '' }}>Active</option>
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
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End faq Content -->
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Custom CSS for the faq -->
    <link rel="stylesheet" href="{{ asset('backend/custom.css') }}">           
@endpush

@push('scripts')
    <!-- Then the jQuery Validation plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- Include the CKEditor script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <!-- Select2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Initialize CKEditor -->
    <script>
     let ckEditorInstance;

    ClassicEditor
    .create(document.querySelector('#answer'))
    .then(editor => {
        ckEditorInstance = editor;

        // optional styling
        editor.ui.view.editable.element.style.minHeight = '250px';
        editor.ui.view.editable.element.style.maxHeight = '250px';
        editor.ui.view.editable.element.style.overflowY = 'auto';

        // 🔥 Trigger validation on typing
        editor.model.document.on('change:data', () => {
            const answerVal = editor.getData();
            $('#answer').val(answerVal); // keep textarea updated
            $('#answer').trigger('keyup'); // trigger validation manually
        });
    })
    .catch(error => {
        console.error(error);
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
                    if (ckEditorInstance) {
                        $('#answer').val(ckEditorInstance.getData());
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
