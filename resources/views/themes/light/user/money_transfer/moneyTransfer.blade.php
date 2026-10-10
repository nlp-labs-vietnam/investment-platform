@extends(template().'layouts.user')
@section('title', 'Money Transfer')

@section('content')
    <!-- Transfer Money -->
    <div class="container-fluid">
        <div class="main row">
            <div class="row mt-2">
                <div class="col">
                    <div class="header-text-full">
                        <h3 class="dashboard_breadcurmb_heading mb-1">{{trans('Money Transfer')}}</h3>
                        <nav aria-label="breadcrumb" class="">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">@lang('Balance Transfer')</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="col">
                <!-- Transfer Money -->
                <section class="profile-setting">
                    <div class="row g-4 g-lg-5">
                        <div class="col-lg-12">
                            <div id="tab1"
                                 class="content p-0 {{ $errors->has('email') ? ' active' : (($errors->has('amount') || $errors->has('wallet_type') || $errors->has('password')) ? '' :  ' active') }}">
                                <form class="form-row" action="{{ route('user.money.transfer.store') }}" method="post">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="input-box col-md-12">
                                            <label for="firstname">@lang('Receiver Email Address')</label>
                                            <input type="email" name="email" value="{{old('email')}}" placeholder="@lang('Receiver Email Address')" class="form-control" id="email"/>

                                            @error('email')
                                            <div class="error text-danger">@lang($message) </div>
                                            @enderror
                                        </div>

                                        <div class="input-box col-md-12">
                                            <label for="lastname">@lang('Amount')</label>
                                            <input type="text" name="amount" value="{{old('amount')}}" placeholder="@lang('Enter Amount')"  onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" id="lastname" class="form-control"/>

                                            @error('amount')
                                            <div class="error text-danger">@lang($message) </div>
                                            @enderror
                                        </div>

                                        <div class="input-box col-md-12">
                                            <label for="language_id">@lang('Select Wallet')</label>
                                            <select class="form-select"
                                                    name="wallet_type" id="wallet_type"
                                                    aria-label="Default select example">
                                                <option value="balance">{{trans('Main balance')}}</option>
                                                <option value="interest_balance">{{trans('Interest Balance')}}</option>
                                            </select>

                                            @error('wallet_type')
                                            <div class="error text-danger">@lang($message) </div>
                                            @enderror
                                        </div>


                                        <div class="input-box col-md-12">
                                            <label for="email">@lang('Enter Password')</label>
                                            <input type="password" name="password" value="{{old('password')}}" placeholder="@lang('Your Password')"
                                                   class="form-control"/>
                                            @error('password')
                                            <div class="error text-danger">@lang($message) </div>
                                            @enderror
                                        </div>

                                        <div class="input-box col-12">
                                            <button class="btn-custom" type="submit">@lang('Transfer')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

