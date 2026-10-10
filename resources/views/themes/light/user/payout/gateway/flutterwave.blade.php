@extends(template().'layouts.user')
@section('page_title',__('Payout'))
@section('content')
    <div class="container-fluid">
        <div class="main row">
            <div class="section-header">
                <h3>@lang('Payout')</h3>
            </div>
            <div class="col-12">
                <section class="profile-setting">
                    <div class="row g-4 g-lg-5">
                        <div class="col-lg-6">
                            <div class="sidebar-wrapper">
                                <form action="{{ route('user.payout.flutterwave',$payout->trx_id) }}"
                                      method="post"
                                      enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" class="type" name="type" value="">
                                    <div class="row g-4">
                                        @if($payoutMethod->supported_currency)
                                            <div class="col-md-12">
                                                <div class="input-box search-currency-dropdown">
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
                                            </div>
                                        @endif


                                            <div class="col-md-12 input-box mb-2">
                                                <label for="from_wallet">@lang('Select Transfer')</label>
                                                <select id="from_wallet" name="transfer_name"
                                                        class="form-control form-control-sm bank">
                                                    <option value="" disabled=""
                                                            selected="">@lang('Select Transfer')</option>
                                                    @foreach($payoutMethod->banks as $bank)
                                                        <option
                                                            value="{{$bank}}" {{old('transfer_name') == $bank ?'selected':''}}>{{$bank}}</option>
                                                    @endforeach
                                                </select>
                                                @error('transfer_name')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </div>

                                        <div class="col-md-12 input-box dynamic-bank mx-1 d-none mb-2">
                                            <label>@lang('Select Bank')</label>
                                            <select id="dynamic-bank" name="bank"
                                                    class="form-control js-example-basic-single">
                                            </select>
                                            @error('bank')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                            <div class="row dynamic-input mt-4">

                                            </div>

                                        @if($payoutMethod->automatic_payout_permisstion)
                                            <div class="input-box mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="automatic_payout_permisstion" type="checkbox" value="1"/>
                                                    <label class="form-check-label" for="tandc">
                                                        @lang('Automatic Payout')
                                                    </label>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="input-box col-12">
                                            <button type="submit" class="btn-custom">submit</button>
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
                                            <span>@lang('Charge')</span>
                                            <span
                                                class="text-success">{{ (getAmount($payout->charge)) }} {{ $payout->payout_currency_code }}</span>
                                        </li>

                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>@lang('Charge Percentage')</span>
                                            <span
                                                class="text-success">{{ (getAmount($payout->net_amount)) }} {{ $payout->payout_currency_code }}</span>
                                        </li>


                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>@lang('Amount In Base Currency')</span>
                                            <span
                                                class="text-success">{{ (getAmount($payout->net_amount_in_base_currency)) }} {{ $basic->base_currency }}</span>
                                        </li>

                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script type="text/javascript">
        'use strict';

        var bankName = null;
        var payAmount = '{{$payout->amount}}'
        var baseCurrency = "{{config('basic.base_currency_code')}}"
        var transferName = "{{old('transfer_name')}}";
        if (transferName) {
            getBankForm(transferName);
        }


        $(document).ready(function () {
            $(document).on("change", ".bank", function () {
                bankName = $(this).val();
                $('.dynamic-bank').addClass('d-none');
                getBankForm(bankName);
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function getBankForm(bankName) {
            $.ajax({
                url: "{{route('user.payout.getBankForm')}}",
                type: "post",
                data: {
                    bankName,
                },
                success: function (response) {
                    if (response.bank != null) {
                        showBank(response.bank.data)
                    }
                    showInputForm(response.input_form)
                }
            });
        }

        function showBank(bankLists) {
            $('#dynamic-bank').html(``);
            var options = `<option disabled selected>@lang("Select Bank")</option>`;
            for (let i = 0; i < bankLists.length; i++) {
                options += `<option value="${bankLists[i].code}">${bankLists[i].name}</option>`;
            }

            $('.dynamic-bank').removeClass('d-none');
            $('#dynamic-bank').html(options);
        }

        function showInputForm(form_fields) {
            $('.dynamic-input').html(``);
            var output = "";

            for (let field in form_fields) {
                let newKey = field.replace('_', ' ');
                output += `<div class="col-md-6 mt-3">
                         <label>${newKey}</label>
				         <input type="text" name="${field}" value="" class="form-control" required>
			          </div>`
            }
            $('.dynamic-input').html(output);
        }

    </script>

@endpush
