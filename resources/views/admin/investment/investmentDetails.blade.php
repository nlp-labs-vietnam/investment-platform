@extends('admin.layouts.app')
@section('page_title')
    @lang('Investment Details')
@endsection
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Dashboard")</a>
                            </li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Manage Investment")</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Investment Details")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Investment Details")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div id="checkoutStepOrderSummary">
                    <!-- Card -->
                    <div class="card mb-3">
                        <!-- Header -->
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">@lang('Property Investment Information')</h4>
                            <a href="{{ route('admin.investments', 'all') }}">@lang('Back')</a>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">

                            <div class="row align-items-center">
                                <span class="col-6 text-dark fw-semibold">@lang('Property'):</span>
                                <h3 class="col-6 text-end text-dark mb-0">@lang(optional($singleInvestDetails->property)->title)</h3>
                            </div>

                            <div class="row align-items-center mt-3">
                                <span class="col-6 text-dark fw-semibold">@lang('Investment Date'):</span>
                                <h4 class="col-6 text-end text-muted mb-0">{{ dateTime($singleInvestDetails->created_at) }}</h4>
                            </div>
                            <hr class="my-4">

                            <div class="row align-items-center mb-3">
                                <span class="col-6">@lang('Transaction Id'):</span>
                                <h4 class="col-6 text-end text-dark mb-0">#{{ $singleInvestDetails->trx }}</h4>
                            </div>

                            <div class="row align-items-center mb-3">
                                <span class="col-6">{{trans('Invest')}}:</span>
                                <h4 class="col-6 text-end text-dark mb-0">{{ currencyPosition($singleInvestDetails->amount) }}</h4>
                            </div>

                            <div class="row align-items-center mb-3">
                                <span class="col-6">{{trans('Profit')}}:</span>
                                <h4 class="col-6 text-end text-dark mb-0">{{ currencyPosition($singleInvestDetails->net_profit) }}</h4>
                            </div>

                            @if($singleInvestDetails->is_installment == 1)
                                <div class="row align-items-center mb-3">
                                    <span class="col-6">{{trans('Total Installment')}}:</span>
                                    <h4 class="col-6 text-end text-dark mb-0">{{ $singleInvestDetails->total_installments }}</h4>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <span class="col-6">{{trans('Due Installment')}}:</span>
                                    <h4 class="col-6 text-end text-dark mb-0">{{ $singleInvestDetails->due_installments }}</h4>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <span class="col-6">{{trans('Next Installment Date Start')}}:</span>
                                    <h4 class="col-6 text-end text-dark mb-0">{{ $singleInvestDetails->next_installment_date_start }}</h4>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <span class="col-6">{{trans('Next Installment Date End')}}:</span>
                                    <h4 class="col-6 text-end text-dark mb-0">{{ $singleInvestDetails->next_installment_date_end }}</h4>
                                </div>
                            @endif

                            <div class="row align-items-center mb-3">
                                <span class="col-6">{{trans('Return Interval')}}:</span>
                                <h4 class="col-6 text-end text-dark mb-0">{{ $singleInvestDetails->return_time }} {{ $singleInvestDetails->return_time_type }}</h4>
                            </div>

                            <div class="row align-items-center mb-3">
                                <span class="col-6"> @lang('Return How Many Times'):</span>
                                <h4 class="col-6 text-end text-dark mb-0"><span
                                        class="badge bg-soft-primary text-primary">
                                    <span class="bg-primary"></span>{{ $singleInvestDetails->how_many_times == null ? 'Lifetime' : $singleInvestDetails->how_many_times }}
                                </span></h4>
                            </div>

                            <div class="row align-items-center mb-3">
                                <span class="col-6"> @lang('Next Profit Return Date'):</span>
                                @if($singleInvestDetails->invest_status == 0)
                                    <h4 class="col-6 text-end text-dark mb-0">
                                        <span class="badge bg-soft-info text-info">
                                            <span class="bg-info"></span>
                                            @lang('After All Installment completed')
                                        </span>
                                    </h4>
                                @elseif($singleInvestDetails->invest_status == 1 && $singleInvestDetails->return_date == null && $singleInvestDetails->status == 1)
                                    <h4 class="col-6 text-end text-dark mb-0">
                                        <span class="badge bg-soft-success text-success">
                                            <span class="bg-success"></span>
                                            @lang('Completed')
                                        </span>
                                    </h4>
                                @else
                                    <h4 class="col-6 text-end text-dark mb-0">{{ dateTime($singleInvestDetails->return_date) }}</h4>
                                @endif
                            </div>

                            <div class="row align-items-center mb-3">
                                <span class="col-6">{{trans('Last Profit Return Date')}}:</span>
                                <h4 class="col-6 text-end text-dark mb-0">{{ $singleInvestDetails->last_return_date != null ? dateTime($singleInvestDetails->last_return_date) : 'N/A' }}</h4>
                            </div>

                            <div class="row align-items-center mb-3">
                                <span class="col-6"> @lang('Investment Payment Status'):</span>
                                @if($singleInvestDetails->invest_status == 1)
                                    <h4 class="col-6 text-end text-dark mb-0">
                                        <span class="badge bg-soft-success text-success">
                                            <span class="bg-success"></span>
                                            @lang('Complete')
                                        </span>
                                    </h4>
                                @else
                                    <h4 class="col-6 text-end text-dark mb-0">
                                        <span class="badge bg-soft-warning text-warning">
                                            <span class="bg-warning"></span>
                                            @lang('Due')
                                        </span>
                                    </h4>
                                @endif
                            </div>


                            <div class="row align-items-center mb-3">
                                <span class="col-6"> @lang('Profit Return Status'):</span>
                                @if($singleInvestDetails->status == 1 && $singleInvestDetails->invest_status == 1)
                                    <h4 class="col-6 text-end text-dark mb-0">
                                        <span class="badge bg-soft-success text-success">
                                            <span class="bg-success"></span>
                                            @lang('Completed')
                                        </span>
                                    </h4>
                                @elseif($singleInvestDetails->status == 0 && $singleInvestDetails->invest_status == 0)
                                    <h4 class="col-6 text-end text-dark mb-0">
                                        <span class="badge bg-soft-warning text-warning">
                                            <span class="bg-warning"></span>
                                            @lang('Upcoming')
                                        </span>
                                    </h4>
                                @elseif($singleInvestDetails->status == 0 && $singleInvestDetails->invest_status == 1)
                                    <h4 class="col-6 text-end text-dark mb-0">
                                        <span class="badge bg-soft-primary text-primary">
                                            <span class="bg-primary"></span>
                                            @lang('Running')
                                        </span>
                                    </h4>
                                @endif
                            </div>

                            <div class="row align-items-center mb-3">
                                <span class="col-6"> @lang('Investment Status'):</span>
                                @if($singleInvestDetails->is_active == 1)
                                    <h4 class="col-6 text-end text-dark mb-0">
                                        <span class="badge bg-soft-success text-success">
                                            <span class="bg-success"></span>
                                            @lang('Active')
                                        </span>
                                    </h4>
                                @else
                                    <h4 class="col-6 text-end text-dark mb-0">
                                        <span class="badge bg-soft-danger text-danger">
                                            <span class="bg-danger"></span>
                                            @lang('Due')
                                        </span>
                                    </h4>
                                @endif
                            </div>
                        </div>
                        <!-- Body -->
                    </div>
                    <!-- End Card -->

                </div>
            </div>
        </div>
    </div>

@endsection


@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush


@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
@endpush

@push('script')

    <script>
        'use strict';


    </script>
@endpush



