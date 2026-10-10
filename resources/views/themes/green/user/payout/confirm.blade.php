@extends(template().'layouts.user')
@section('title',__('Payout'))

@section('content')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Payout With') {{ $payoutMethod->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-header">
                <h3>@lang('Payout')</h3>
            </div>
            <div class="card-body profile-setting">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="">
                            <form action="{{ route('user.payout.confirm',$payout->trx_id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    @if($payoutMethod->supported_currency)
                                        <div class="col-md-12 mb-2">
                                            <label for="from_wallet">@lang('Select Bank Currency')</label>
                                            <input type="text" name="currency_code"
                                                   placeholder="Selected"
                                                   autocomplete="off"
                                                   value="{{ $payout->payout_currency_code }}"
                                                   class="form-control transfer-currency @error('currency_code') is-invalid @enderror">

                                            @error('currency_code')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    @endif

                                    @if($payoutMethod->code == 'paypal')
                                        <div class="row">
                                            <div class="col-md-12 mt-4">
                                                <div class="form-group search-currency-dropdown">
                                                    <label
                                                        for="from_wallet">@lang('Select Recipient Type')</label>
                                                    <select id="from_wallet" name="recipient_type"
                                                            class="form-control form-control-sm" required>
                                                        <option value="" disabled=""
                                                                selected="">@lang('Select Recipient')</option>
                                                        <option value="EMAIL">@lang('Email')</option>
                                                        <option value="PHONE">@lang('phone')</option>
                                                        <option value="PAYPAL_ID">@lang('Paypal Id')</option>
                                                    </select>
                                                    @error('recipient_type')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    @if(isset($payoutMethod->inputForm))

                                        @foreach($payoutMethod->inputForm as $key => $value)
                                            @if($value->type == 'text')
                                                <div class="col-md-12 mt-3">
                                                    <label
                                                        for="{{ $value->field_name }}">@lang($value->field_label)</label>
                                                    <input type="text" name="{{ $value->field_name }}"
                                                           placeholder="{{ __(snake2Title($value->field_name)) }}"
                                                           autocomplete="off"
                                                           value="{{ old(snake2Title($value->field_name)) }}"
                                                           class="form-control @error($value->field_name) is-invalid @enderror">
                                                    <div class="invalid-feedback">
                                                        @error($value->field_name) @lang($message) @enderror
                                                    </div>
                                                </div>
                                            @elseif($value->type == 'textarea')
                                                <div class="col-md-12">
                                                    <label
                                                        for="{{ $value->field_name }}">@lang($value->field_label)</label>
                                                    <textarea
                                                        class="form-control @error($value->field_name) is-invalid @enderror"
                                                        name="{{$value->field_name}}"
                                                        rows="5">{{ old($value->field_name) }}</textarea>
                                                    <div
                                                        class="invalid-feedback">@error($value->field_name) @lang($message) @enderror</div>
                                                </div>
                                            @elseif($value->type == 'file')
                                                <div class="col-md-12 mt-3 mb-4">
                                                    <div id="image-preview" class="image-preview">
                                                        <label for="image-upload"
                                                               id="image-label">@lang($value->field_label)</label>
                                                        <input type="file" name="{{ $value->field_name }}"
                                                               class="form-control @error($value->field_name) is-invalid @enderror"
                                                               id="image-upload"/>
                                                    </div>
                                                    <div class="invalid-feedback d-block">
                                                        @error($value->field_name) @lang($message) @enderror
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    <div class="input-box col-12">
                                        <button type="submit" class="cmn-btn">submit</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div id="tab1" class="content active">
                            <form action="">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>@lang('Payout Method')</span>
                                        <span class="text-info">{{ __($payoutMethod->name) }} </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>@lang('Request Amount')</span>
                                        <span
                                            class="text-success">{{ (getAmount($payout->amount)) }} {{ $payout->payout_currency_code }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>@lang('Percentage')</span>
                                        <span
                                            class="text-success">{{ (getAmount($payout->percentage)) }} {{ $payout->payout_currency_code }}</span>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>@lang('Charge Percentage')</span>
                                        <span
                                            class="text-success">{{ (getAmount($payout->charge_percentage)) }} {{ $payout->payout_currency_code }}</span>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>@lang('Charge Fixed')</span>
                                        <span
                                            class="text-success">{{ (getAmount($payout->charge_fixed)) }} {{ $payout->payout_currency_code }}</span>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>@lang('Charge')</span>
                                        <span
                                            class="text-success">{{ (getAmount($payout->charge)) }} {{ $payout->payout_currency_code }}</span>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>@lang('Amount In Base Currency')</span>
                                        <span
                                            class="text-success">{{ (getAmount($payout->amount_in_base_currency)) }} {{ $payout->base_currency }}</span>
                                    </li>

                                </ul>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('extra_scripts')
    <script src="{{ asset('assets/dashboard/js/jquery.uploadPreview.min.js') }}"></script>
@endpush

@push('script')
    <script type="text/javascript">

    </script>

    @if ($errors->any())
        @php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        @endphp
        <script>
            "use strict";
            @foreach ($errors as $error)
            Notiflix.Notify.Failure("{{ trans($error) }}");
            @endforeach
        </script>
    @endif
@endpush
