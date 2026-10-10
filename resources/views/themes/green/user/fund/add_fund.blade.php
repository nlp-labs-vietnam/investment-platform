@extends(template().'layouts.user')
@section('title',trans('Add Fund'))
@section('content')
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Add Fund')</li>
            </ol>
        </nav>
    </div>
    <form action="{{ route('payment.request') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7 col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h4 class="mb-15">@lang('How would you like to pay?')</h4>
                        <div class="payment-section">
                            <ul class="payment-container-list">
                                @forelse($gateways as $method)
                                    <li class="item">
                                        <input class="form-check-input selectPayment"
                                               value="{{ $method->id }}" type="radio"
                                               name="gateway_id"
                                               id="{{ $method->name }}" >
                                        <label class="form-check-label"
                                               for="{{ $method->name }}">
                                            <div class="image-area">
                                                <img
                                                    src="{{ getFile($method->driver,$method->image ) }}"
                                                    alt="">
                                            </div>
                                            <div class="content-area">
                                                <h5>{{$method->name}}</h5>
                                                <span class="gateway-description">{{$method->description}}</span>
                                            </div>
                                        </label>

                                    </li>
                                @empty
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-5 col-md-6 ">
                <div class="side-bar card h-100">
                    <div class="side-box mt-5 card-body">
                        <div class="col-md-12 input-box mb-3 add-select-field" >
                            <select class="js-example-basic-single form-select"
                                    name="supported_currency" id="supported_currency">
                                <option value="">@lang('Select Currency')</option>
                            </select>
                        </div>
                        <div class="col-md-12 input-box">
                            <div class="input-group">
                                <input class="form-control @error('amount') is-invalid @enderror"
                                       name="amount"
                                       type="text" id="amount"
                                       placeholder="@lang('Enter Amount')" autocomplete="off"/>
                                <div class="invalid-feedback">
                                    @error('amount') @lang($message) @enderror
                                </div>
                                <div class="valid-feedback"></div>
                            </div>
                        </div>
                        <div id="paymentSummary">
                            <div class="preview">
                                <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                                <p>@lang('Waiting for payment preview')</p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <div class="paymentModal">
            <!-- Modal section start -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                 aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="staticBackdropLabel">@lang('Payment')</h4>
                            <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa-light fa-xmark"></i>
                            </button>
                        </div>
                        <div class="modal-body" id="paymentModalBody">


                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal section end -->
        </div>
    </form>



@endsection

@push('extra-js')
    <script>
        'use strict';


        $(document).ready(function () {


            let amountField = $('#amount');
            let amountStatus = false;
            let selectedGateway = "";

            $('.js-example-basic-single').select2()

            function clearMessage(fieldId) {
                $(fieldId).removeClass('is-valid')
                $(fieldId).removeClass('is-invalid')
                $(fieldId).closest('div').find(".invalid-feedback").html('');
                $(fieldId).closest('div').find(".is-valid").html('');
            }

            $(document).on('click', '.selectPayment', function () {
                let id = this.id;
                $('#paymentModalBody').html('');
                $('.js-example-basic-single').select2()
                selectedGateway = $(this).val();
                let updatedWidth = window.innerWidth;
                window.addEventListener('resize', () => {
                    updatedWidth = window.innerWidth;
                });
                let html =
                    `<div class="side-box mt-5 card-body">
                        <div class="col-md-12 input-box mb-3 add-select-field" >
                           <select class="js-example-basic-single form-select"
                                   name="supported_currency" id="supported_currency">
                               <option value="">@lang('Select Currency')</option>
                           </select>
                        </div>
                        <div class="col-md-12 input-box">
                           <div class="input-group">
                               <input class="form-control @error('amount') is-invalid @enderror"
                                      name="amount" type="text" id="amount"
                                      placeholder="@lang('Enter Amount')" autocomplete="off"/>
                               <div class="invalid-feedback"> @error('amount') @lang($message) @enderror </div>
                               <div class="valid-feedback"></div>
                           </div>
                        </div>
                        <div id="paymentSummary">
                            <div class="preview">
                                <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                                <p>@lang('Waiting for payment preview')</p>
                            </div>
                        </div>
                    </div> `;

                if(updatedWidth <= 991){
                    $('.side-bar').html('');
                    $('#paymentModalBody').html(html);
                    let paymentModal =  new bootstrap.Modal(document.getElementById('staticBackdrop'));
                    paymentModal.show()
                }else {
                    $('.side-bar').html(html)
                }
                $('.js-example-basic-single').select2();

                supportCurrency(selectedGateway);
            });

            function supportCurrency(selectedGateway) {
                if (!selectedGateway) {
                    console.error('Selected Gateway is undefined or null.');
                    return;
                }

                $('#supported_currency').empty();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('supported.currency') }}",
                    data: {gateway: selectedGateway},
                    type: "GET",
                    success: function (response) {

                        $('.add-select-field').empty();

                        if (response.data === "") {
                            let markup = `<option value="USD">USD</option>`;
                            $('#supported_currency').append(markup);
                        }

                        if (response.currencyType == 1) {
                            let select =
                                `<select class="js-example-basic-single form-select" name="supported_currency" id="supported_currency">
                                        <option value="">`+'{{trans('Select Currency')}}'+`</option>
                                </select>`;
                            $('.add-select-field').append(select);

                            $(response.data).each(function (index, value) {
                                let markup = `<option value="${value}">${value}</option>`;
                                $('#supported_currency').append(markup);
                            });
                        }

                        let markup2 = '<option value="">'+'{{trans('Select Crypto Currency')}}'+'</option>';
                        $('#supported_crypto_currency').append(markup2);
                        $('.js-example-basic-single').select2();

                        if (response.currencyType == 0){
                            let markup2 = `
                                        <select class="js-example-basic-single form-select"
                                                name="supported_crypto_currency"
                                                id="supported_crypto_currency">
                                              <option value="">`+'{{trans('Select Crypto Currency')}}'+`</option>
                                        </select>`;
                            $('.add-select-field').append(markup2);

                            $(response.data).each(function (index, value) {
                                let markupOption = `<option value="${value}">${value}</option>`;
                                $('#supported_crypto_currency').append(markupOption);
                            });
                        }
                    },
                    error: function (error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }


            $(document).on('change, input', "#amount, #supported_currency, .selectPayment, #supported_crypto_currency", function (e) {

                let amount =  $('#amount').val();
                let selectedCurrency = $('#supported_currency').val();
                let selectedCryptoCurrency = $('#supported_crypto_currency').val();
                let currency_type = 1;

                if (!isNaN(amount) && amount > 0) {

                    let fraction = amount.split('.')[1];
                    let limit = currency_type == 0 ? 8 : 2;

                    if (fraction && fraction.length > limit) {
                        amount = (Math.floor(amount * Math.pow(10, limit)) / Math.pow(10, limit)).toFixed(limit);
                        amountField.val(amount);
                    }

                    checkAmount(amount, selectedCurrency, selectedGateway, selectedCryptoCurrency)


                    if (selectedCurrency != null) {

                    }
                } else {
                    clearMessage(amountField)
                    $('#paymentSummary').html(`<div id="paymentSummary">
                            <div class="preview">
                                <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                                <p>@lang('Waiting for payment preview')</p>
                            </div>
                        </div>`)
                }
            });

            function checkAmount(amount, selectedCurrency, selectGateway, selectedCryptoCurrency = null) {
                $.ajax({
                    method: "GET",
                    url: "{{ route('deposit.checkAmount') }}",
                    dataType: "json",
                    data: {
                        'amount': amount,
                        'selected_currency': selectedCurrency,
                        'select_gateway': selectGateway,
                        'selectedCryptoCurrency': selectedCryptoCurrency,
                    }
                }).done(function (response) {

                    let amountField = $('#amount');
                    if (response.status) {
                        clearMessage(amountField);
                        $(amountField).addClass('is-valid');
                        $(amountField).closest('div').find(".valid-feedback").html(response.message);
                        amountStatus = true;
                        let base_currency = "{{basicControl()->base_currency}}"
                        showCharge(response, base_currency);
                    } else {
                        amountStatus = false;
                        $('#paymentSummary').html(`<div id="paymentSummary">
                            <div class="preview">
                                <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                                <p>@lang('Waiting for payment preview')</p>
                            </div>
                        </div>`);
                        clearMessage(amountField);
                        $(amountField).addClass('is-invalid');
                        $(amountField).closest('div').find(".invalid-feedback").html(response.message);
                    }


                });
            }


            function showCharge(response, currency) {

                let txnDetails =  ` <div class="side-box mt-2">
                    <h5 class="mt-3">@lang('Deposit Summary')</h5>
                    <div class="showCharge">
                        <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Amount In') }} ${response.currency} </span>
                            <span class="text-success"> ${response.amount} ${response.currency}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Charge') }}</span>
                            <span class="text-danger">  ${response.charge} ${response.currency}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Payable Amount') }}</span>
                            <span class=""> ${response.payable_amount} ${response.currency}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('In Base Currency') }}</span>
                            <span class=""> ${response.amount_in_base_currency} ${currency}</span>
                        </li>

                    </ul>
                    </div>
                </div>

                <button type="submit" class="cmn-btn ms-2 mt-3">@lang('Make Payment') <span></span> </button>`
                ;
                $('#paymentSummary').html(txnDetails)
            }

        });


    </script>
@endpush

@push('style')
    <style>
        #paymentSummary .preview{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        #paymentSummary .preview img{
            max-width: 280px;
            width: 100%;
            height: 280px;
        }
        .paymentModal {
            display: none!important;
        }
        @media only screen and (max-width: 991px) {


            .paymentModal{
                display: block !important;
            }
        }
        @media only screen and (max-width: 767px){
            .side-bar {
                display: none !important;
            }
        }
        @media only screen and (max-width: 420px) {

            .gateway-description{
                opacity: .7;
                font-size: 15px;
                font-weight: 500;
                line-height: normal;
                margin-top: 7px;
                margin-bottom: 25px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        }

    </style>
@endpush






