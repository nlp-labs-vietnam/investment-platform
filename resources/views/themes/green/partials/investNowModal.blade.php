<!-- Modal section start -->
<div class="modal fade" id="investNowModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form action="" method="post" id="invest-form" class="login-form invest_now_modal">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title investHeading" id="staticBackdropLabel"></h4>
                    <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="property-title"></h5>
                    <div class="cmn-list mt-10">
                        <div class="item" id="available_funding">
                            <span>@lang('Available fund for investment')</span>
                            <h6 class="available_funding"></h6>
                        </div>
                        <div class="item">
                            <span>@lang('Investment Amount')</span>
                            <h6 class="data_invest"></h6>
                        </div>
                        <div class="item">
                            <span>@lang('Profit Return')</span>
                            <h6 class="data_profit"></h6>
                        </div>
                        <div class="item">
                            <span>@lang('Profit Return Interval')</span>
                            <h6 class="data_return"></h6>
                        </div>
                        <div class="item totalInstallment">
                            <span>@lang('Total Installment')</span>
                            <h6 class="total_installment"></h6>
                        </div>
                        <div class="item installmentDuration">
                            <span>@lang('Installment Duration')</span>
                            <h6 class="installment_duration"></h6>
                        </div>
                        <div class="item installmentLateFee">
                            <span>@lang('Installment Late Fee')</span>
                            <h6 class="installment_late_fee"></h6>
                        </div>
                        <div class="item">
                            <span>@lang('Investment Period')</span>
                            <h6 id="investmentDuration"></h6>
                        </div>
                    </div>
                    <div class="callout mt-10">
                        <i class="fa-regular fa-info-circle mr-2"></i>
                        <span class="profit_return_message"></span>
                    </div>
                    <hr class="cmn-hr2">
                    @guest
                        <div class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="text-center font-weight-bold">@lang('First Log In To Your Account For Invest')</h6>
                                    <div class="tree">
                                        <div class="d-flex justify-content-center">
                                            <div class="branch branch-1">
                                                <span>
                                                    <a href="{{ route('login') }}">@lang('Sign In')</a>
                                                </span>
                                                /
                                                <span>
                                                    <a href="{{ route('register') }}">@lang('Sign Up')</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div id="formModal" class="investModalPaymentForm">
                            <label class="form-label">@lang('Select Wallet')</label>
                            <select class="modal-select" name="balance_type">
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
                                <input type="hidden" value="" class="set_fixedInvest_amount">
                                <input class="form-check-input" type="checkbox" value="0"
                                       name="pay_installment" id="pay_installment"/>

                                <label class="form-check-label" for="pay_installment">@lang('Pay Installment')</label>
                            </div>

                            <div class="callout mt-10">
                                <i class="fa-regular fa-info-circle"></i>
                                @lang('N.B: If you pay in installments then you have to pay the next installments from the invest history of your dashboard')
                            </div>
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
                    @endguest
                </div>

                <div class="modal-footer">
                    <button type="button" class="cmn-btn2" data-bs-dismiss="modal">@lang('Close')</button>
                    @auth
                        <button type="submit" class="cmn-btn">@lang('Submit')</button>
                    @endauth
                </div>
            </div>
        </form>
    </div>
</div>
