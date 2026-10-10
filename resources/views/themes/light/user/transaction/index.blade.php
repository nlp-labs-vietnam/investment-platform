@extends(template().'layouts.user')
@section('title')
    @lang('Transaction')
@endsection

@section('content')
    <section class="transaction-history">
        <div class="container-fluid">
            <div class="main row  ">
                <div class="col ms-2">
                    <div class="header-text-full d-flex justify-content-between align-items-center">
                        <nav aria-label="breadcrumb">
                        <h3 class="dashboard_breadcurmb_heading mb-1">@lang('Transaction History')</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">@lang('transaction')</li>
                            </ol>
                        </nav>
                        <button type="button" class="btn-custom" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">@lang('Filter')
                            <i class="fa-regular fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="main row ">
                <div class="col">
                    <div class="table-parent table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>@lang('SL No.')</th>
                                <th>@lang('Transaction ID')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Charge')</th>
                                <th>@lang('Remarks')</th>
                                <th>@lang('Time')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{loopIndex($transactions) + $loop->index}}</td>
                                    <td>@lang($transaction->trx_id)</td>
                                    <td>
                                        <span class="fw-bold text-{{ $transaction->trx_type == '+' ? 'success' : 'danger' }}">
                                            {{$transaction->trx_type}}{{ currencyPosition($transaction->amount) }} </span>
                                    </td>
                                    <td class="text-danger">{{ currencyPosition($transaction->charge) }}</td>
                                    <td>@lang($transaction->remarks)</td>
                                    <td>{{ dateTime($transaction->created_at, 'd M Y h:i A') }}</td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="100%">{{__('No Data Found!')}}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- pagination -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end m-0">
                            {{ $transactions->appends($_GET)->links(template().'partials.pagination') }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Offcanvas sidebar start -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">@lang('Filter Transaction List')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('user.transaction.search') }}" method="get">
                <div class="row g-4">
                    <div>
                        <label for="transaction_id" class="form-label">@lang('Transaction ID')</label>
                        <input type="text" name="transaction_id"
                               value="{{@request()->transaction_id}}"
                               class="form-control"
                               placeholder="@lang('Transaction ID')"/>
                    </div>

                    <div id="formModal">
                        <label class="form-label">@lang('Remark')</label>
                        <input type="text" name="remark" value="{{@request()->remark}}"
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
                        <button type="submit" class="btn-custom">@lang('Filter')</button>
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
