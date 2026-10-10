@extends(template().'layouts.user')
@section('title', 'Money Transfer')

@section('content')
    <!-- Transfer Money -->

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Money Transfer')</li>
            </ol>
        </nav>
    </div>

    <div class="account-settings-profile-section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-row" action="{{ route('user.money.transfer.store') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label">@lang('Receiver Email Address')</label>
                                    <input type="email" name="email" value="{{old('email')}}"
                                           placeholder="@lang('Receiver Email Address')" class="form-control"
                                           id="email"/>
                                    @error('email')
                                        <div class="error text-danger">@lang($message) </div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="amount" class="form-label">@lang('Amount')</label>
                                    <input type="text" name="amount" value="{{old('amount')}}"
                                           placeholder="@lang('Enter Amount')"
                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                           id="lastname" class="form-control"/>

                                    @error('amount')
                                    <div class="error text-danger">@lang($message) </div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="wallet_type">@lang('Select Wallet')</label>
                                    <select class="cmn-select2"
                                            name="wallet_type" id="wallet_type"
                                            aria-label="Default select example">
                                        <option value="balance">{{trans('Main balance')}} ({{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount(auth()->user()->balance)) }})</option>
                                        <option value="interest_balance">{{trans('Interest Balance')}} ({{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount(auth()->user()->interest_balance)) }})</option>
                                    </select>

                                    @error('wallet_type')
                                    <div class="error text-danger">@lang($message) </div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">@lang('Enter Password')</label>
                                    <input type="password" name="password" value="{{old('password')}}"
                                           placeholder="@lang('Your Password')"
                                           class="form-control"/>
                                    @error('password')
                                    <div class="error text-danger">@lang($message) </div>
                                    @enderror
                                </div>

                                <div class="btn-area">
                                    <button type="submit" class="cmn-btn">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

