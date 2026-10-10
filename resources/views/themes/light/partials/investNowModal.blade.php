<div class="modal fade" id="investNowModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form action="" method="post" id="invest-form" class="login-form invest_now_modal">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title investHeading" id="staticBackdropLabel"></h5>
                    <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                            aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="">
                        <div class="payment-method-details property-title primary_color mb-2 fw-bold">
                        </div>

                        <div class="">
                            <div class="estimation-box">
                                <div class="details_list">
                                    <ul>
                                        <li class="d-flex justify-content-between" id="available_funding">
                                            <span>@lang('Available fund for investment')</span> <span
                                                class="available_funding"></span></li>
                                        <li class="d-flex justify-content-between"><span>@lang('Investment Amount')</span>
                                            <span class="data_invest"></span></li>
                                        <li class="d-flex justify-content-between"><span>@lang('Profit Return')</span>
                                            <span class="data_profit"></span></li>
                                        <li class="d-flex justify-content-between">
                                            <span>@lang('Profit Return Interval')</span>
                                            <span class="data_return"></span></li>
                                        <li class="d-flex justify-content-between totalInstallment">
                                            <span>@lang('Total Installment')</span><span
                                                class="total_installment"></span></li>
                                        <li class="d-flex justify-content-between installmentDuration">
                                            <span>@lang('Installment Duration')</span> <span
                                                class="installment_duration"></span></li>
                                        <li class="d-flex justify-content-between installmentLateFee">
                                            <span>@lang('Installment Late Fee')</span><span
                                                class="installment_late_fee"></span></li>
                                        <li class="d-flex justify-content-between">
                                            <span>@lang('Investment Period')</span>
                                            <span class="primary_color" id="investmentDuration"></span></li>
                                        <div class="bd-callout bd-callout-warning m-0 mb-4">
                                            <i class="fas fa-info-circle mr-2"></i> <span class="profit_return_message"></span> </div>
                                    </ul>
                                </div>
                            </div>

                            @auth
                                <div class="row g-3 investModalPaymentForm">
                                    <div class="input-box col-12">
                                        <label for="">@lang('Select Wallet')</label>
                                        <select class="form-control form-select" id="exampleFormControlSelect1"
                                                name="balance_type">
                                            @auth
                                                <option
                                                    value="balance">@lang('Deposit Balance - '.basicControl()->currency_symbol.getAmount(auth()->user()->balance))</option>
                                                <option
                                                    value="interest_balance">@lang('Interest Balance -'.basicControl()->currency_symbol.getAmount(auth()->user()->interest_balance))</option>
                                            @endauth
                                        </select>
                                    </div>

                                    <div class="input-box col-12 payInstallment d-none">
                                        <div class="form-check">
                                            <input type="hidden" value="" class="set_installment_amount">
                                            <input type="hidden" value="" class="set_fixedInvest_amount">
                                            <input class="form-check-input" type="checkbox" value="0"
                                                   name="pay_installment" id="pay_installment"/>
                                            <label class="form-check-label"
                                                   for="pay_installment">@lang('Pay Installment')</label>
                                        </div>
                                        <div class="bd-callout bd-callout-warning m-0 mb-4">
                                            <i class="fas fa-info-circle mr-2"></i> @lang('N.B: If you pay in installments then you have to pay the next installments from the invest history of your dashboard') </div>
                                    </div>

                                    <div class="input-box col-12">
                                        <label for="">@lang('Amount')</label>
                                        <div class="input-group">
                                            <input
                                                type="text" class="invest-amount form-control" name="amount"
                                                id="amount"
                                                value="{{old('amount')}}"
                                                onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                autocomplete="off"
                                                placeholder="@lang('Enter amount')">
                                            <button type="button" class="show-currency"></button>
                                        </div>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>

                <div
                    class="modal-footer {{ \Auth::check() == true ? '' : 'd-block' }}">
                    @auth
                        <button type="button"
                                class="btn-custom btn2 btn-secondary close_invest_modal close__btn"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn-custom investModalSubmitBtn">@lang('Invest')</button>
                    @else

                        <div class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="text-center font-weight-bold">@lang('First Log In To Your Account For Invest')</h6>
                                    <div class="tree">
                                        <div class="d-flex justify-content-center">
                                            <div class="branch branch-1">@lang('Sign In / Sign Up')</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="branch branch-2"><a href="{{ route('login') }}"
                                                                            class="text-decoration-underline">@lang('Login')</a>
                                            </div>
                                            <div class="branch branch-3"><a href="{{ route('register') }}"
                                                                            class="text-decoration-underline">@lang('Register')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </form>
    </div>
</div>
