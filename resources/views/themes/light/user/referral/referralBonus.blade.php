@extends(template().'layouts.user')
@section('title',trans($title))
@section('content')

    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-datepicker.css') }}"/>
    @endpush

    <section class="transaction-history">
        <div class="container-fluid">
            <div class="main row mt-4 mb-2">
                <div class="col ms-2">
                    <div class="header-text-full">
                        <h3 class="dashboard_breadcurmb_heading mb-1">@lang('Referral Bonus')</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans($title) }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="main row g-4 mb-4">
                <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 box">
                    <div class="dashboard-box">
                        <h5>@lang('Total Bonus')</h5>
                        <h3>
                            <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{ $totalReferralTransaction['total_referral_bonous'] }}</span>
                        </h3>
                        <i class="far fa-funnel-dollar text-success " aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 box">
                    <div class="dashboard-box">
                        <h5>@lang('Joining Bonus')</h5>
                        <h3>
                            <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{  array_key_exists("joining_bonus",$totalReferralTransaction) ? $totalReferralTransaction['joining_bonus'] : 0 }}</span>
                        </h3>
                        <i class="far fa-sack-dollar text-info" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 box">
                    <div class="dashboard-box">
                        <h5>@lang('Deposit Bonus')</h5>
                        <h3>
                            <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{ array_key_exists("deposit",$totalReferralTransaction) ? $totalReferralTransaction['deposit'] : 0 }}</span>
                        </h3>
                        <i class="fal fa-usd-circle text-warning" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 box">
                    <div class="dashboard-box">
                        <h5>@lang('Invest Bonus')</h5>
                        <h3>
                            <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{ array_key_exists("invest",$totalReferralTransaction) ? $totalReferralTransaction['invest'] : 0 }}</span>
                        </h3>
                        <i class="far fa-badge-dollar text-orange" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 box">
                    <div class="dashboard-box">
                        <h5>@lang('Profit Bonus')</h5>
                        <h3>
                            <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{ array_key_exists("profit_commission",$totalReferralTransaction) ? $totalReferralTransaction['profit_commission'] : 0 }}</span>
                        </h3>
                        <i class="far fa-sack-dollar text-info" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 box">
                    <div class="dashboard-box">
                        <h5>@lang('Rank Bonus')</h5>
                        <h3>
                            <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{ array_key_exists("badge_commission",$totalReferralTransaction) ? $totalReferralTransaction['badge_commission'] : 0 }}</span>
                        </h3>
                        <i class="far fa-sack-dollar text-info" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end px-1">
                <button type="button" class="btn-custom " data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">@lang('Filter')
                    <i class="fa-regular fa-filter"></i>
                </button>
            </div>



            <div class="main row mt-4">
                <div class="col">
                    <div class="table-parent table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">@lang('SL No.')</th>
                                <th scope="col">@lang('Bonus From')</th>
                                <th scope="col">@lang('Amount')</th>
                                <th scope="col">@lang('Remarks')</th>
                                <th scope="col">@lang('Bonus Type')</th>
                                <th scope="col">@lang('Time')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td data-label="@lang('SL')">{{loopIndex($transactions) + $loop->index}}</td>
                                    <td data-label="@lang('Bonus From')">{{optional(@$transaction->bonusBy)->fullname}}</td>
                                    <td data-label="@lang('Amount')">
                                        <span
                                            class="font-weight-bold text-success">{{fractionNumber(getAmount($transaction->amount))}} {{ basicControl()->base_currency }}</span>
                                    </td>
                                    <td data-label="@lang('Remarks')"><span>@lang($transaction->remarks)</span></td>
                                    <td data-label="@lang('Bonus Type')"><span>@lang($transaction->type)</span></td>
                                    <td data-label="@lang('Time')">{{ dateTime($transaction->created_at, 'd M Y h:i A') }}</td>
                                </tr>

                            @empty
                                <tr class="text-center">
                                    <td colspan="100%">{{__('No Data Found!')}}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end m-0">
                                {{ $transactions->appends($_GET)->links(template().'partials.pagination') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Offcanvas sidebar start -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">@lang('Filter Referral Bonus')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('user.referral.bonus.search') }}" method="get">
                <div class="row g-4">
                    <div>
                        <label for="search_user" class="form-label">@lang('User')</label>
                        <input type="text" name="search_user"
                               value="{{request()->property}}"
                               class="form-control"
                               placeholder="@lang('Search User')"/>
                    </div>

                    <div id="formModal">
                        <label class="form-label">@lang('Bonus Type')</label>
                        <input type="text" name="type"
                               value="{{request()->type}}"
                               class="form-control"
                               placeholder="@lang('Bonus Type')"/>
                    </div>

                    <div>
                        <label for="search_user" class="form-label">@lang('Remark')</label>
                        <input type="text" name="remark"
                               value="{{request()->remark}}"
                               class="form-control"
                               placeholder="@lang('Remark')"/>
                    </div>

                    <div>
                        <label class="form-label" for="from_date">@lang('From Date')</label>
                        <input
                            type="text" class="form-control datepicker from_date" name="from_date"
                            value="{{ old('from_date',request()->from_date) }}" placeholder="@lang('From date')"
                            autocomplete="off" readonly/>
                    </div>

                    <div>
                        <label class="form-label" for="from_date">@lang('To Date')</label>
                        <input
                            type="text" class="form-control datepicker to_date" name="to_date"
                            value="{{ old('to_date', request()->to_date) }}" placeholder="@lang('To date')"
                            autocomplete="off" readonly disabled="true"/>
                    </div>

                    <div class="btn-area">
                        <button type="submit" class="btn-custom">@lang('Search')</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <!-- Offcanvas sidebar end -->

@endsection

@push('script')

    <script>
        'use strict'
        $(document).ready(function () {

            $('.from_date').on('change', function () {
                $('.to_date').removeAttr('disabled');
            });
        });
    </script>
@endpush
