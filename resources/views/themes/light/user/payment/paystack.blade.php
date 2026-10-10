@extends(template().'layouts.user')
@section('page_title')
	{{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@section('content')
    <div class="container-fluid mt-lg-5">
        <div class="main row">
            <div class="col-12">
                <div class="row g-4 justify-content-center">
                    <div class="col-6">
                        <div class="card-box p-0">
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-3">
                                        <img
                                            src="{{getFile(optional($deposit->gateway)->driver,optional($deposit->gateway)->image)}}"
                                            class="card-img-top gateway-img">
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                                        <button type="button" class="btn btn-primary"
                                                id="btn-confirm">@lang('Pay Now')</button>
                                        <form
                                            action="{{ route('ipn', [optional($deposit->gateway)->code, $deposit->utr]) }}"
                                            method="POST">
                                            @csrf
                                            <script src="//js.paystack.co/v1/inline.js"
                                                    data-key="{{ $data->key }}"
                                                    data-email="{{ $data->email }}"
                                                    data-amount="{{$data->amount}}"
                                                    data-currency="{{$data->currency}}"
                                                    data-ref="{{ $data->ref }}"
                                                    data-custom-button="btn-confirm">
                                            </script>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

