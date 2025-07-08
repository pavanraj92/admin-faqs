@extends('admin::admin.layouts.master')

@section('title', 'Faqs Management')

@section('page-title', 'Faq Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.faqs.index') }}">Manage Faqs</a></li>
    <li class="breadcrumb-item active" aria-current="page">Faq Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Faq Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">                    
                    <div class="table-responsive">
                         <div class="card-body">      
                            <table class="table table-responsive-lg table-no-border">
                                <tbody>
                                    <tr>
                                        <th scope="row">Question</th>
                                        <td scope="col">{{ $faq->question ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Answer</th>
                                        <td scope="col">{!! $faq->answer ?? 'N/A' !!}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Status</th>
                                        <td scope="col"> {!! config('admin.constants.aryStatusLabel.' . $faq->status, 'N/A') !!}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Created At</th>
                                        <td scope="col">{{ $faq->created_at
                                            ? $faq->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                            : '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                                             
                            <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End faq Content -->
    </div>
    <!-- End Container fluid  -->
@endsection
