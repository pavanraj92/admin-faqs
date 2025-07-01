@extends('admin::admin.layouts.master')

@section('title', 'Faqs Management')
@section('meta_description')
Manage FAQs in the admin panel. Create, edit, or update FAQ questions, answers, and status.
@endsection

@section('page-title', 'Manage Faqs')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Manage Faqs</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Faq Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="card-title">Filter</h4>
                    <form action="{{ route('admin.faqs.index') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="question">Question</label>
                                    <input type="text" name="keyword" id="keyword" class="form-control"
                                        value="{{ app('request')->query('keyword') }}" placeholder="Enter question">                                   
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="">All</option>
                                        <option value="0" {{ app('request')->query('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                        <option value="1" {{ app('request')->query('status') == '1' ? 'selected' : '' }}>Active</option>
                                    </select>                                   
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" form="filterForm" class="btn btn-primary mb-3">Filter</button>
                            <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary mb-3">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-right">
                            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary mb-3">Create New Faq</a>
                        </div>
                    
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Question</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($faqs) && $faqs->count() > 0)
                                        @php
                                            $i = ($faqs->currentPage() - 1) * $faqs->perPage() + 1;
                                        @endphp
                                        @foreach ($faqs as $faq)
                                            <tr>
                                                <th scope="row">{{ $i }}</th>
                                                <td>{{ $faq->question }}</td>
                                                <td>
                                                    <!-- create update status functionality-->
                                                    @if ($faq->status == '1')
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top"
                                                            title="Click to change status to inactive"
                                                            data-url="{{ route('admin.faqs.updateStatus') }}"
                                                            data-method="POST" data-status="0" data-id="{{ $faq->id }}"
                                                            class="btn btn-success btn-sm update-status">Active</a>
                                                    @else
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top"
                                                            title="Click to change status to active"
                                                            data-url="{{ route('admin.faqs.updateStatus') }}"
                                                            data-method="POST" data-status="1"
                                                            data-id="{{ $faq->id }}"
                                                            class="btn btn-warning btn-sm update-status">InActive</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $faq->created_at->format('Y-m-d H:i:s') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.faqs.edit', $faq) }}"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="Edit this record"
                                                        class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                                    <a href="{{ route('admin.faqs.show', $faq) }}" 
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="View this record"
                                                        class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                                    <a href="javascript:void(0)" 
                                                        data-toggle="tooltip" 
                                                        data-placement="top"
                                                        title="Delete this record" 
                                                        data-url="{{ route('admin.faqs.destroy', $faq) }}"
                                                        data-text="Are you sure you want to delete this record?"                                                    
                                                        data-method="DELETE"
                                                        class="btn btn-danger btn-sm delete-record" ><i class="mdi mdi-delete"></i></a>
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No faqs found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!--pagination move the right side-->
                            @if ($faqs->count() > 0)
                                {{ $faqs->links('admin::pagination.custom-admin-pagination') }}
                            @endif                        
                            
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- End faq Content -->
    </div>
    <!-- End Container fluid  -->
@endsection
