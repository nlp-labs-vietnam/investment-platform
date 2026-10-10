@extends(template().'layouts.user')
@section('title',trans('Invest Details'))
@section('content')
    <!-- main -->
    <div class="container-fluid">
        <div class="row mt-4 mb-2">
            <div class="col ms-2">
                <div class="header-text-full">
                    <h4 class="dashboard_breadcurmb_heading mb-1">@lang('Investment Details')</h4>
                    <nav aria-label="breadcrumb" class="ms-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Invest Details')</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="p-3 ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="border-bottom">
                                <div class="investmentDate d-flex justify-content-between">
                                    <h6 class="font-weight-bold text-dark"><i
                                            class="far fa-calendar-check me-2 text-primary"></i> @lang('Investment Date')
                                        : </h6>
                                    <p>{{ dateTime($singleInvestDetails->created_at) }}</p>
                                </div>
                                <div class="property d-flex justify-content-between">
                                    <h6 class="font-weight-bold text-dark"><i
                                            class="far fa-building me-2 text-success"></i> @lang('Property')
                                        :</h6>
                                    <p class="float-end">
                                        <a href="{{ route('property.details',[optional($singleInvestDetails->property)->id, @slug(optional($singleInvestDetails->property)->title)]) }}">
                                            @lang(optional($singleInvestDetails->property)->title)
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <ul class="invest-history-details-ul">
                                <li class="my-3 list-style-none">
                                                        <span><i class="fal fa-check-circle site__color"
                                                                 aria-hidden="true"></i> @lang('Transaction Id') : <span
                                                                class="font-weight-bold text-dark">{{ $singleInvestDetails->trx }}</span></span>
                                </li>

                                <li class="my-3 list-style-none">
                                                        <span><i class="fal fa-check-circle site__color text-primary"
                                                                 aria-hidden="true"></i> @lang('Invest') : <span
                                                                class="font-weight-bold text-primary">{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount($singleInvestDetails->amount)) }}</span></span>
                                </li>

                                <li class="my-3 list-style-none">
                                                        <span><i class="fal fa-check-circle site__color text-primary"
                                                                 aria-hidden="true"></i> @lang('Profit') : <span
                                                                class="font-weight-bold text-primary">{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount($singleInvestDetails->net_profit)) }}</span></span>
                                </li>


                                @if($singleInvestDetails->is_installment == 1)
                                    <li class="my-3">
                                                            <span class="font-weight-bold"><i
                                                                    class="fal fa-check-circle site__color text-success"
                                                                    aria-hidden="true"></i> @lang('Total Installment') : <span
                                                                    class="font-weight-bold text-success">{{ $singleInvestDetails->total_installments }}</span></span>
                                    </li>

                                    <li class="my-3">
                                                            <span class="font-weight-bold"><i
                                                                    class="fal fa-times-circle text-warning"
                                                                    aria-hidden="true"></i> @lang('Due Installment') : <span
                                                                    class="font-weight-bold text-success">{{ $singleInvestDetails->due_installments }}</span></span>
                                    </li>

                                    <li class="my-3">
                                                            <span class="font-weight-bold"><i
                                                                    class="fal fa-check-circle site__color text-purple"
                                                                    aria-hidden="true"></i> @lang('Next Installment Start') : <span
                                                                    class="font-weight-bold text-purple">{{ dateTime($singleInvestDetails->next_installment_date_start) }}</span></span>
                                    </li>

                                    <li class="my-3">
                                                            <span class="font-weight-bold"><i
                                                                    class="fal fa-check-circle site__color text-purple"
                                                                    aria-hidden="true"></i> @lang('Next Installment End') : <span
                                                                    class="font-weight-bold text-purple">{{ dateTime($singleInvestDetails->next_installment_date_end) }}</span></span>
                                    </li>
                                @endif

                                <li class="my-3">
                                                        <span><i class="fal fa-check-circle site__color"
                                                                 aria-hidden="true"></i> @lang('Profit Return Interval') : <span
                                                                class="font-weight-bold text-primary">{{ $singleInvestDetails->return_time }} {{ $singleInvestDetails->return_time_type }}</span></span>
                                </li>

                                <li class="my-3">
                                                        <span><i
                                                                class="fal fa-check-circle site__color {{ $singleInvestDetails->how_many_times == 0 ? 'text-danger' : 'text-success' }}"
                                                                aria-hidden="true"></i> @lang('Return How Many Times') : <span
                                                                class="font-weight-bold text-success">{{ $singleInvestDetails->how_many_times == null ? 'Lifetime' : $singleInvestDetails->how_many_times }}</span></span>
                                </li>

                                <li class="my-3">
                                                            <span><i class="fal fa-check-circle site__color"
                                                                     aria-hidden="true"></i> @lang('Next Profit Return Date') :
                                                                <span class="font-weight-bold">
                                                                    @if($singleInvestDetails->invest_status == 0)
                                                                        <span
                                                                            class="badge text-bg-danger">@lang('After All Installment completed')</span>
                                                                    @elseif($singleInvestDetails->invest_status == 1 && $singleInvestDetails->return_date == null && $singleInvestDetails->status == 1)
                                                                        <span
                                                                            class="badge text-bg-success">@lang('Completed')</span>
                                                                    @else
                                                                        {{ dateTime($singleInvestDetails->return_date) }}
                                                                    @endif
                                                                </span>
                                                            </span>
                                </li>

                                <li class="my-3">
                                                        <span><i
                                                                class="{{ $singleInvestDetails->last_return_date != null ? 'fal fa-check-circle text-success' : 'fal fa-times-circle text-danger' }}"
                                                                aria-hidden="true"></i> @lang('Last Profit Return Date') : <span
                                                                class="{{ $singleInvestDetails->last_return_date != null ? 'text-dark font-weight-bold' : 'text-danger font-weight-bold' }}">{{ $singleInvestDetails->last_return_date != null ? dateTime($singleInvestDetails->last_return_date) : 'N/A' }}</span></span>
                                </li>

                                <li class="my-3">
                                                        <span><i
                                                                class="fal {{ $singleInvestDetails->invest_status == 1 ? 'fa-check-circle' : 'fa-times-circle' }} site__color"
                                                                aria-hidden="true"></i> @lang('Investment Payment Status') : <span
                                                                class="badge {{ $singleInvestDetails->invest_status == 1 ? 'text-bg-success' : 'text-bg-warning' }}">{{ $singleInvestDetails->invest_status == 1 ? trans('Complete'): trans('Due') }}</span></span>
                                </li>

                                <li class="my-3">
                                                        <span><i class="fal fa-check-circle site__color"
                                                                 aria-hidden="true"></i> @lang('Profit Return Status') : <span
                                                                class="badge
                                                        @if($singleInvestDetails->status == 1 && $singleInvestDetails->invest_status == 1)
                                                            text-bg-success
                                                        @elseif($singleInvestDetails->status == 0 && $singleInvestDetails->invest_status == 0)
                                                            text-bg-warning
                                                        @elseif($singleInvestDetails->status == 0 && $singleInvestDetails->invest_status == 1)
                                                            text-bg-primary
                                                        @endif">
                                                            @if($singleInvestDetails->status == 1 && $singleInvestDetails->invest_status == 1)
                                                                    @lang('Completed')
                                                                @elseif($singleInvestDetails->status == 0 && $singleInvestDetails->invest_status == 0)
                                                                    @lang('Upcoming')
                                                                @elseif($singleInvestDetails->status == 0 && $singleInvestDetails->invest_status == 1)
                                                                    @lang('Running')
                                                                @endif
                                                            </span>
                                                        </span>
                                </li>

                                <li class="my-3">
                                                        <span><i class="fal fa-check-circle site__color"
                                                                 aria-hidden="true"></i> @lang('Investment Status') : <span
                                                                class="badge {{ $singleInvestDetails->is_active == 1 ? 'text-bg-success' : 'text-bg-danger' }}">{{ $singleInvestDetails->is_active == 1 ? trans('Active') : trans('Deactive') }}</span></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
