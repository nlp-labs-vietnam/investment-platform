@extends(template().'layouts.user')
@section('title')
    @lang('Transaction')
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-datepicker.css') }}"/>
@endpush

@section('content')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Transactions')</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between pb-0 border-0">
            <h4>@lang('Transaction List')</h4>
            <div class="btn-area">
                <button type="button" class="cmn-btn" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">@lang('Filter')<i
                        class="fa-regular fa-filter"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="cmn-table transaction-table">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th>@lang('SL.')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Transaction ID')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Remarks')</th>
                            <th>@lang('Time')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($transactions as $transaction)
                            @php
                                $amount = currencyPosition($transaction->amount);
                                $charge = currencyPosition($transaction->charge);
                            @endphp
                            <tr>
                                <td>{{loopIndex($transactions) + $loop->index}}</td>
                                <td data-label="Type">
                                    <div class="type">
                                        <span class="icon {{ $transaction->trx_type == '-' ? 'icon-sent' : 'icon-received' }}">
                                            <i class="fa-regular {{ $transaction->trx_type == '-' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                        </span>
                                        <span>{{ $transaction->trx_type == '-' ? trans('Deducted') : trans('Credited') }}</span>
                                    </div>
                                </td>

                                <td class="fw-semibold">@lang($transaction->trx_id)</td>
                                <td>
                                    <span class="fw-bold text-{{ $transaction->trx_type == '+' ? 'success' : 'danger' }}">
                                        {{$transaction->trx_type}}{{ $amount }}
                                    </span>
                                </td>
                                <td>
                                    {{ \Illuminate\Support\Str::limit($transaction->remarks, 35) }}
                                </td>
                                <td>{{ dateTime($transaction->created_at, 'd M Y h:i A') }}</td>

                                <td data-label="Action">
                                    <a class="cmn-btn3 display  showInfo" href="#"
                                       data-trx_type="{{ $transaction->trx_type }}" data-remarks="{{ $transaction->remarks }}"
                                       data-amount="{{ $amount }}" data-charge="{{ $charge }}"
                                       data-trxid="{{ $transaction->trx_id }}"
                                       data-trx_date="{{ dateTime($transaction->created_at) }}"
                                       data-note="{{ $transaction->note }}"
                                       data-bs-toggle="modal"
                                       data-bs-target="#infoViewModal">
                                        <i class="fa-regular fa-display"></i>
                                    </a>
                                </td>
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
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">@lang('Filter Transaction List')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('user.transaction.search') }}" method="get">
                <div class="row g-4">
                    <div>
                        <label for="ProductName" class="form-label">@lang('Property')</label>
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
                        <button type="submit" class="cmn-btn"><i class="fa-regular fa-magnifying-glass"></i>@lang('Filter')</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <!-- Offcanvas sidebar end -->

@endsection

@push('loadModal')
    <div class="modal fade" id="infoViewModal" tabindex="-1" role="dialog" aria-hidden="true"
         data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mb-2">
                        <h4 class="mt-3 mb-1">@lang('Transaction Details')</h4>
                    </div>

                    <div class="row mb-6">
                        <div class="transaction-list mt-2">
                            <div class="item">
                                <div class="left-side">
                                    <div class="icon">
                                        <i class="fa-regular"></i>
                                    </div>
                                    <span class="remarks"></span>
                                </div>
                                <div class="d-flex gap-2">
                                    <strong class="trxId"></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="title mb-2 mt-4 fw-semibold">@lang('Summary')</div>
                    <ul class="list-container mb-4 ">
                        <li class="item py-2">
                            <span>@lang('Amount')</span>
                            <span class=" fw-bold amount"></span>
                        </li>
                        <li class="item py-2">
                            <span>@lang('Charge')</span>
                            <span class=" fw-semibold text-danger charge"></span>
                        </li>
                        <li class="item py-2">
                            <span>@lang('Transaction Date')</span>
                            <span class=" fw-semibold trx_date"></span>
                        </li>
                        <li class="item py-2 note-area d-none">
                            <span>@lang('Note')</span>
                            <span class=" fw-semibold note"></span>
                        </li>
                    </ul>
                    <div class="modal-footer-text mt-3">
                        <div class="d-flex justify-content-end gap-3 status-buttons">
                            <button type="button" class="cmn-btn3" data-bs-dismiss="modal">@lang('Close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush


@push('script')

    <script>
        $('.showInfo').on('click', function () {
            const {amount, charge, trxid, trx_type, trx_date, remarks, note} = this.dataset;

            $('.amount').html(amount);
            $('.trxId').html('#' + trxid);
            $('.charge').html(charge);
            $('.trx_date').html(trx_date);

            if (note){
                $('.note-area').removeClass('d-none');
                $('.note').html(note);
            } else {
                $('.note-area').addClass('d-none');
            }

            const iconClass = trx_type === '-' ? 'icon-sent' : 'icon-received';
            const icon = trx_type === '-' ? 'fa-arrow-up' : 'fa-arrow-down';

            $('.transaction-list .icon').attr('class', `icon ${iconClass}`);
            $('.transaction-list .icon i').attr('class', `fa-regular ${icon}`);
            $('.transaction-list .left-side span').html(remarks);

            $('#infoViewModal').modal('show');
        });

    </script>
@endpush


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
