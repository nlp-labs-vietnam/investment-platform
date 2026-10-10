@extends('admin.layouts.app')
@section('page_title', __('Property Details'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manage Property')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang('Property Details')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Property Details')</h1>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card mb-3 mb-lg-5">
                    <div class="card-header card-header-content-between">
                        <h4 class="card-header-title">@lang('Property Investment Information')</h4>
                    </div>

                    <div class="card-body">
                        <ul class="list-group list-group-flush list-group-no-gutters">
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>@lang('Property')</h5>
                                    <ul class="list-unstyled list-py-2 text-body">
                                        <li>@lang($property->title)</li>

                                    </ul>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>@lang('Investment Start Date')</h5>
                                    <ul class="list-unstyled list-py-2 text-body">
                                        <li>{{ dateTime($property->start_date) }}</li>

                                    </ul>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>@lang('Investment End Date')</h5>
                                    <ul class="list-unstyled list-py-2 text-body">
                                        <li>{{ dateTime($property->expire_date) }}</li>

                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {

        });
    </script>
@endpush



