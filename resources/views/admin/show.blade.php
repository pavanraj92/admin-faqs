@extends('admin::admin.layouts.master')

@section('title', 'Faqs Management')

@section('page-title', 'Faq Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ route('admin.faqs.index') }}">Faq Manager</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Faq Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Header with Back button -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">{{ $faq->question ?? 'N/A' }} - Faq #{{ $faq->id }}</h4>
                            <div>
                                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary ml-2">
                                    Back
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Faq Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Faq Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Question:</label>
                                            <p>{{ $faq->question ?? 'N/A' }}</p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold">Answer:</label>
                                            <p>{!! $faq->answer ?? 'N/A' !!}</p>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Status:</label>
                                                    <p>{!! config('faq.constants.aryStatusLabel.' . $faq->status, 'N/A') !!}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Created At:</label>
                                                    <p>
                                                        {{ $faq->created_at
                                                            ? $faq->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                            : '—' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            @admincan('faqs_manager_edit')
                                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-warning mb-2">
                                                <i class="mdi mdi-pencil"></i> Edit Faq
                                            </a>
                                            @endadmincan

                                            @admincan('faqs_manager_delete')
                                                <button type="button" class="btn btn-danger delete-btn delete-record"
                                                    title="Delete this record"
                                                    data-url="{{ route('admin.faqs.destroy', $faq) }}"
                                                    data-redirect="{{ route('admin.faqs.index') }}"
                                                    data-text="Are you sure you want to delete this record?"
                                                    data-method="DELETE">
                                                    <i class="mdi mdi-delete"></i> Delete Faq
                                                </button>
                                            @endadmincan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- row end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
@endsection
