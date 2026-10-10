@extends(template().'layouts.user')
@section('title', trans('Investment History'))
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-datepicker.css') }}"/>
    @endpush
    <!-- Invest history -->
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Invest History')</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between pb-0 border-0">
            <h4>@lang('Invest History')</h4>
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
                            <th scope="col">@lang('SL')</th>
                            <th scope="col">@lang('Property')</th>
                            <th scope="col">@lang('Investment Amount')</th>
                            <th scope="col">@lang('Profit')</th>
                            <th scope="col">@lang('Upcoming Payment')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($investments as $key => $invest)
                            <tr>
                                <td data-label="@lang('SL')"><span>{{loopIndex($investments) + $key}}</span></td>

                                <td data-label="@lang('Property')">
                                    <span>@lang(optional($invest->property)->title)</span>
                                </td>

                                <td data-label="@lang('Invest Amount')">
                                    <span>{{ optional($invest->property)->investmentAmount }}</span>
                                </td>

                                <td data-label="@lang('Profit')">
                                    @if($invest->invest_status == 1)
                                        {{  $invest->profit_type == 0 ? basicControl()->currency_symbol.fractionNumber(getAmount($invest->profit)) : basicControl()->currency_symbol.fractionNumber(getAmount($invest->net_profit)) }}
                                    @else
                                        <span class="text-danger">@lang('N/A')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Upcoming Payment')">
                                    @if($invest->invest_status == 0)
                                        <span class="badge text-bg-info">@lang('After Installments complete')</span>
                                    @elseif($invest->invest_status == 1 && $invest->return_date == null && $invest->status == 1)
                                        <span class="badge text-bg-success">@lang('All completed')</span>
                                    @else
                                        {{ dateTime($invest->return_date) }}
                                    @endif
                                </td>

                                <td data-label="Action">
                                    <div class="dropdown">
                                        <button class="action-btn-secondary" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-regular fa-ellipsis-stroke-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('user.invest-history-details', $invest->id) }}">@lang('Details')</a>
                                            </li>

                                            @if($invest->invest_status == 0)
                                                <li>
                                                    <a class="dropdown-item duePayment"
                                                       href="javascript:void(0)"
                                                       data-route="{{route('user.completeDuePayment', $invest->id)}}"
                                                       data-property="{{ optional($invest->property)->title }}"
                                                       data-fixedamount="{{ getAmount(optional($invest->property)->fixed_amount) }}"
                                                       data-previouspay="{{ $invest->amount }}"
                                                       data-investtype="{{ optional($invest->property)->is_invest_type }}"
                                                       data-isinstallment="{{ $invest->is_installment }}"
                                                       data-totalinstallments="{{ $invest->total_installments }}"
                                                       data-dueinstallments="{{ $invest->due_installments }}"
                                                       data-installmentamount="{{ getAmount(optional($invest->property)->installment_amount) }}"
                                                       data-installmentduration="{{ optional($invest->property)->installment_duration }}"
                                                       data-installmentlastdate="{{ dateTime($invest->next_installment_date_end) }}"
                                                       data-getinstallmentdate="{{ $invest->getInstallmentDate() }}"
                                                       data-installmentlatefee="{{ fractionNumber(getAmount(optional($invest->property)->installment_late_fee)) }}"
                                                       data-installmentdurationtype="{{ optional($invest->property)->installment_duration_type }}">
                                                        @lang('Due Make Payment')
                                                    </a>
                                                </li>


                                            @endif

                                            @if($invest->invest_status == 1 && $invest->status == 0)
                                                @if($invest->propertyShare)
                                                    <li>
                                                        <a class="dropdown-item updateOffer"
                                                           href="javascript:void(0)"
                                                           data-route="{{route('user.propertyShareUpdate', optional($invest->propertyShare)->id)}}"
                                                           data-amount="{{ optional($invest->propertyShare)->amount }}"
                                                           data-property="{{ optional($invest->property)->title }}">
                                                            @lang('Update Share')
                                                        </a>
                                                    </li>
                                                @else
                                                    @if(optional($invest->property)->is_investor == 1 && basicControl()->is_share_investment == 1)
                                                        <li>
                                                            <a class="dropdown-item sendOffer"
                                                               href="javascript:void(0)"
                                                               data-route="{{route('user.propertyShareStore', $invest->id)}}"
                                                               data-property="{{ optional($invest->property)->title }}">
                                                                @lang('Sell Share')
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
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
                            {{ $investments->appends($_GET)->links(template().'partials.pagination') }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <!-- Offcanvas sidebar start -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">@lang('Filter Invest History')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="" method="get" enctype="multipart/form-data">
                <div class="row g-4">
                    <div>
                        <label for="ProductName" class="form-label">@lang('Property')</label>
                        <input type="text" name="property"
                               value="{{request()->property}}"
                               class="form-control"
                               placeholder="@lang('Search property')"/>
                    </div>

                    <div id="formModal">
                        <label class="form-label">@lang('Invest Status')</label>
                        <select class="modal-select" name="invest_status">
                            <option value="">@lang('All')</option>
                            <option value="1"
                                    @if(@request()->invest_status == '1') selected @endif>@lang('Complete')</option>
                            <option value="0"
                                    @if(@request()->invest_status == '0') selected @endif>@lang('Due')</option>
                        </select>
                    </div>

                    <div id="formModal">
                        <label class="form-label">@lang('Return Status')</label>
                        <select class="modal-select" name="return_status">
                            <option value="">@lang('All')</option>
                            <option value="0"
                                    @if(@request()->return_status == '0') selected @endif>@lang('Running')</option>
                            <option value="1"
                                    @if(@request()->return_status == '1') selected @endif>@lang('Completed')</option>
                        </select>
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

    @push('loadModal')
        <!-- Modal -->
        {{--  due make payment modal --}}
        <div class="modal fade" id="duePaymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <form action="" method="post" id="invest-form" class="login-form due_payment_modal">
                    @csrf
                    @method('put')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="staticBackdropLabel">@lang('Due Make Payment')</h4>
                            <button type="button" class="cmn-btn-close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fa-light fa-xmark"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <h5 class="property-title"></h5>

                            <div class="cmn-list mt-10">
                                <div class="item" id="available_funding">
                                    <span>@lang('Fixed Invest')</span>
                                    <h6 class="fixed_invest"></h6>
                                </div>
                                <div class="item totalInstallment">
                                    <span>@lang('Total Installment')</span>
                                    <h6 class="total_installments"></h6>
                                </div>
                                <div class="item dueInstallment">
                                    <span>@lang('Due Installment')</span>
                                    <h6 class="due_installments"></h6>
                                </div>
                                <div class="item">
                                    <span>@lang('Installment Amount')</span>
                                    <h6 class="installment_amount"></h6>
                                </div>
                                <div class="item installmentDuration">
                                    <span>@lang('Installment Duration')</span>
                                    <h6 class="installment_duration"></h6>
                                </div>
                                <div class="item installmentLastDate">
                                    <span>@lang('Installment Last Date')</span>
                                    <h6 class="installment_last_date"></h6>
                                </div>
                                <div class="item installmentLateFee">
                                    <span>@lang('Installment Late Fee')</span>
                                    <h6 class="installment_late_fee"></h6>
                                </div>
                                <div class="item previousTotalPay">
                                    <span>@lang('Previous Total Pay')</span>
                                    <h6 class="previous_total_pay"></h6>
                                </div>
                                <div class="item previousTotalPay">
                                    <span>@lang('Due Amount')</span>
                                    <h6 class="total_due_amount"></h6>
                                </div>
                            </div>

                            <hr class="cmn-hr2">

                            <div id="formModal" class="investModalPaymentForm">
                                <label class="form-label">@lang('Select Wallet')</label>
                                <select class="form-select" name="balance_type">
                                    @auth
                                        <option
                                            value="balance">@lang('Deposit Balance - '.basicControl()->currency_symbol.getAmount(auth()->user()->balance))</option>
                                        <option
                                            value="interest_balance">@lang('Interest Balance -'.basicControl()->currency_symbol.getAmount(auth()->user()->interest_balance))</option>
                                    @endauth
                                </select>
                            </div>

                            <div class="payInstallment d-none">
                                <div class="form-check mt-10">
                                    <input type="hidden" value="" class="set_installment_amount">
                                    <input type="hidden" value="" class="set_totalDue_amount">
                                    <input type="hidden" value="" class="set_get_installment_date">
                                    <input type="hidden" value="" class="set_total_due_amount">
                                    <input type="hidden" value="" class="set_installment_late_fee">
                                    <input class="form-check-input" type="checkbox" value="0"
                                           name="pay_installment" id="pay_installment"/>
                                    <label class="form-check-label" for="pay_installment">@lang('Pay Installment')</label>
                                </div>

                                <div class="callout mt-10">
                                    <i class="fa-regular fa-info-circle"></i>
                                    @lang('N.B: If you pay in installments then you have to pay the next installments from the invest history of your dashboard')
                                </div>

                                <div class="input-group mt-20">
                                    <input type="text" class="invest-amount form-control" name="amount"
                                           id="amount"
                                           value="{{old('amount')}}"
                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                           autocomplete="off"
                                           placeholder="@lang('Enter amount')">
                                    <span class="input-group-text show-currency" id="basic-addon2"></span>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="cmn-btn2"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="cmn-btn investModalSubmitBtn">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Send offer modal --}}
        <div class="modal fade" id="sendOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <form action="" method="post" id="invest-form" class="login-form send_offer_modal">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Set Share Amount')</h5>
                            <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-3 investModalPaymentForm">
                                        <div id="formModal" class="investModalPaymentForm">
                                            <label for="">@lang('Share Amount')</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    class="invest-amount form-control @error('amount') is-invalid @enderror"
                                                    name="amount" id="amount"
                                                    value="{{old('amount')}}"
                                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                    autocomplete="off"
                                                    placeholder="@lang('Enter amount')" required>
                                                <button class="input-group-text show-currency" type="button"></button>
                                            </div>
                                            @error('amount')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="cmn-btn2 btn2 btn-secondary close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="cmn-btn investModalSubmitBtn">@lang('Share')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Update offer modal --}}
        <div class="modal fade" id="updateOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <form action="" method="post" id="invest-form" class="login-form update_offer_modal">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Update Share Amount')</h5>
                            <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-3 investModalPaymentForm">
                                        <div id="formModal" class="investModalPaymentForm">
                                            <label for="">@lang('Share Amount')</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    class="invest-amount form-control amount @error('amount') is-invalid @enderror"
                                                    name="amount" id="amount"
                                                    value="{{old('amount')}}"
                                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                    autocomplete="off"
                                                    placeholder="@lang('Enter amount')" required>
                                                <span class="input-group-text show-currency" id="basic-addon2"></span>
                                            </div>
                                            @error('amount')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="cmn-btn2 btn2 btn-secondary close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="cmn-btn investModalSubmitBtn">@lang('Update')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endpush
@endsection

@push('script')

    <script>
        'use strict'
        $(document).ready(function () {

            $('.from_date').on('change', function () {
                $('.to_date').removeAttr('disabled');
            });

            $(document).on('click', '.duePayment', function () {
                var propertyInvestModal = new bootstrap.Modal(document.getElementById('duePaymentModal'))
                propertyInvestModal.show();

                let symbol = "{{trans(basicControl()->currency_symbol)}}";
                let dataRoute = $(this).data('route');
                let dataProperty = $(this).data('property');
                let dataFixedAmount = $(this).data('fixedamount');
                let dataPreviousPay = $(this).data('previouspay');
                let isInstallment = $(this).data('isinstallment');
                let totalInstallments = $(this).data('totalinstallments');
                let totalDueInstallments = $(this).data('dueinstallments');
                let installmentAmount = $(this).data('installmentamount');
                let installmentDuration = $(this).data('installmentduration') + ' ' + $(this).data('installmentdurationtype');
                let installmentLastDate = $(this).data('installmentlastdate');
                let getInstallmentDate = $(this).data('getinstallmentdate');
                let installmentLateFee = $(this).data('installmentlatefee');
                $('.show-currency').text("{{ basicControl()->base_currency }}");

                $('.due_payment_modal').attr('action', dataRoute);
                $('.property-title').text(`Property: ${dataProperty}`);
                $('.fixed_invest').text(`${symbol}${dataFixedAmount}`);
                $('.total_installments').text(totalInstallments);
                $('.due_installments').text(totalDueInstallments);
                $('.installment_duration').text(installmentDuration);
                $('.installment_last_date').text(installmentLastDate);
                $('.installment_last_date').text(installmentLastDate);
                $('.installment_late_fee').text(`${symbol}${installmentLateFee}`);
                $('.previous_total_pay').text(`${symbol}${parseInt(dataPreviousPay)}`);
                let totalDueAmount = parseInt(dataFixedAmount) - parseInt(dataPreviousPay);
                $('.total_due_amount').text(`${symbol}${totalDueAmount}`);
                $('.installment_amount').text(`${symbol}${installmentAmount}`);


                if (isInstallment == 1 && totalDueInstallments > 1) {
                    $('.set_installment_amount').val(installmentAmount);
                    $('.set_totalDue_amount').val(totalDueAmount);
                    $('.set_get_installment_date').val(getInstallmentDate);
                    $('.set_total_due_amount').val(totalDueAmount);
                    $('.set_installment_late_fee').val(installmentLateFee);
                    $('.payInstallment').removeClass('d-none');
                }

                if (getInstallmentDate == 0) {
                    let dueAmountWithLateFee = parseInt(totalDueAmount) + parseInt(installmentLateFee);
                    $('.invest-amount').val(dueAmountWithLateFee);
                    $('#amount').attr('readonly', true);
                } else {
                    $('.invest-amount').val(totalDueAmount);
                    $('#amount').attr('readonly', true);
                }
            });


            $(document).on('click', '#pay_installment', function () {
                let getInstallmentDate = $('.set_get_installment_date').val();
                let installmentAmount = $('.set_installment_amount').val();
                let installmentLateFee = $('.set_installment_late_fee').val();
                let totalDueAmount = $('.set_total_due_amount').val();

                if ($(this).prop("checked") == true) {
                    $(this).val(1);
                    if (getInstallmentDate == 0) {
                        let dueAmountWithLateFee = parseInt(installmentAmount) + parseInt(installmentLateFee);
                        $('.invest-amount').val(dueAmountWithLateFee);
                        $('#amount').attr('readonly', true);
                    } else {
                        $('.invest-amount').val(installmentAmount);
                        $('#amount').attr('readonly', true);
                    }

                } else {
                    $(this).val(0);
                    if (getInstallmentDate == 0) {
                        let dueAmountWithLateFee = parseInt(totalDueAmount) + parseInt(installmentLateFee);
                        $('.invest-amount').val(dueAmountWithLateFee);
                        $('#amount').attr('readonly', true);
                    } else {
                        $('.invest-amount').val(totalDueAmount);
                        $('#amount').attr('readonly', true);
                    }
                }
            });

            $(document).on('click', '.close_invest_modal', function () {
                if ($('#pay_installment').prop("checked") == true) {
                    $("#pay_installment").prop("checked", false);
                }
            });

            $(document).on('click', '.sendOffer', function () {
                var propertysendOfferModal = new bootstrap.Modal(document.getElementById('sendOfferModal'))
                propertysendOfferModal.show();

                let dataRoute = $(this).data('route');
                let dataProperty = $(this).data('property');

                $('.send_offer_modal').attr('action', dataRoute);
                $('.property-title').text(`Property: ${dataProperty}`);
                $('.show-currency').text("{{ basicControl()->base_currency }}");

            });

            $(document).on('click', '.updateOffer', function () {
                var propertyupdateOfferModal = new bootstrap.Modal(document.getElementById('updateOfferModal'))
                propertyupdateOfferModal.show();

                let dataRoute = $(this).data('route');
                let dataProperty = $(this).data('property');
                let amount = $(this).data('amount');
                $('.show-currency').text("{{ basicControl()->base_currency }}");

                $('.update_offer_modal').attr('action', dataRoute);
                $('.property-title').text(`Property: ${dataProperty}`);
                $('.amount').val(amount);
            });
        });
    </script>
@endpush
