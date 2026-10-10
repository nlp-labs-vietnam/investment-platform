@extends(template().'layouts.user')
@section('title',trans($title))
@section('content')

    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-datepicker.css') }}"/>
    @endpush

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Referral Bonus')</li>
            </ol>
        </nav>
    </div>


    <div class="row g-4 mb-5">
        <div class="col-xxl-3 col-sm-6 card-item">
            <div class="box-card">
                <div class="icon-box">
                    <i class="fa-regular fa-sack-dollar"></i>
                </div>
                <div class="text-box">
                    <p class="mb-1">@lang('Total Bonus')</p>
                    <h4>{{trans(basicControl()->currency_symbol)}}{{ fractionNumber(getAmount($totalReferralTransaction['total_referral_bonous'])) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 card-item">
            <div class="box-card">
                <div class="icon-box">
                    <i class="fa-sharp fa-regular fa-hands-holding-dollar"></i>
                </div>
                <div class="text-box">
                    <p class="mb-1">@lang('Joining Bonus')</p>
                    <h4>{{trans(basicControl()->currency_symbol)}}{{  array_key_exists("joining_bonus",$totalReferralTransaction) ? fractionNumber(getAmount($totalReferralTransaction['joining_bonus'])) : 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 card-item">
            <div class="box-card">
                <div class="icon-box">
                    <i class="fa-regular fa-circle-dollar"></i>
                </div>
                <div class="text-box">
                    <p class="mb-1">@lang('Deposit Bonus')</p>
                    <h4>{{trans(basicControl()->currency_symbol)}}{{ array_key_exists("deposit",$totalReferralTransaction) ? fractionNumber(getAmount($totalReferralTransaction['deposit'])) : 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 card-item">
            <div class="box-card">
                <div class="icon-box">
                    <i class="fa-regular fa-chart-line-up"></i>
                </div>
                <div class="text-box">
                    <p class="mb-1">@lang('Invest Bonus')</p>
                    <h4>{{trans(basicControl()->currency_symbol)}}{{ array_key_exists("invest",$totalReferralTransaction) ? fractionNumber(getAmount($totalReferralTransaction['invest'])) : 0 }}</h4>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6 card-item">
            <div class="box-card">
                <div class="icon-box">
                    <i class="fa-regular fa-chart-line-up"></i>
                </div>
                <div class="text-box">
                    <p class="mb-1">@lang('Profit Bonus')</p>
                    <h4>{{trans(basicControl()->currency_symbol)}}{{ array_key_exists("profit_commission",$totalReferralTransaction) ? fractionNumber(getAmount($totalReferralTransaction['profit_commission'])) : 0 }}</h4>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6 card-item">
            <div class="box-card">
                <div class="icon-box">
                    <i class="fa-regular fa-chart-line-up"></i>
                </div>
                <div class="text-box">
                    <p class="mb-1">@lang('Rank Bonus')</p>
                    <h4>{{trans(basicControl()->currency_symbol)}}{{ array_key_exists("badge_commission",$totalReferralTransaction) ? fractionNumber(getAmount($totalReferralTransaction['badge_commission'])) : 0 }}</h4>
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header d-flex justify-content-between pb-0 border-0">
            <h4>@lang('Referral Bonus')</h4>
            <div class="btn-area">
                <button type="button" class="cmn-btn" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">@lang('Filter')<i
                        class="fa-regular fa-filter"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="cmn-table">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
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
                            <tr>
                                <td colspan="100%" class="dataTables_empty">
                                    <div class="text-center p-4">
                                        <img class="dataTables-image mb-3 empty-state-img"
                                             src="{{ asset(template(true).'img/oc-error.svg') }}"
                                             alt="Image Description" data-hs-theme-appearance="default">
                                        <p class="mb-0">@lang('No data to show')</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center mt-4">
                            {{ $transactions->appends($_GET)->links(template().'partials.pagination') }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

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
                        <button type="submit" class="cmn-btn"><i class="fa-regular fa-magnifying-glass"></i>@lang('Search')</button>
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
