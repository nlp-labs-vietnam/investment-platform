@extends(template().'layouts.user')
@section('page_title')
	{{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@section('content')
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Pay With') {{ optional($deposit->gateway)->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card card-primary shadow">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-3">
                                    <img
                                        src="{{getFile(optional($deposit->gateway)->driver,optional($deposit->gateway)->image)}}"
                                        class="card-img-top gateway-img">
                                </div>
                                <div class="col-md-6">
                                    <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                                    <button type="button"
                                            class="btn btn-primary"
                                            id="payment-button">@lang('Pay with Khalti')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
	<script
		src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
	<script>

		$(document).ready(function () {
			$('body').addClass('antialiased')
		});

		var config = {
			// replace the publicKey with yours
			"publicKey": "{{$data->publicKey}}",
			"productIdentity": "{{$data->productIdentity}}",
			"productName": "Payment",
			"productUrl": "{{url('/')}}",
			"paymentPreference": [
				"KHALTI",
				"EBANKING",
				"MOBILE_BANKING",
				"CONNECT_IPS",
				"SCT",
			],
			"eventHandler": {
				onSuccess(payload) {
					// hit merchant api for initiating verfication
					$.ajax({
						type: 'POST',
						url: "{{ route('khalti.verifyPayment',[$deposit->trx_id]) }}",
						data: {
							token: payload.token,
							amount: payload.amount,
							"_token": "{{ csrf_token() }}"
						},
						success: function (res) {
							$.ajax({
								type: "POST",
								url: "{{ route('khalti.storePayment') }}",
								data: {
									response: res,
									"_token": "{{ csrf_token() }}"
								},
								success: function (res) {
									window.location.href = "{{route('success')}}"
								}
							});
						}
					});
				},
				onError(error) {
				},
				onClose() {
				}
			}
		};
		var checkout = new KhaltiCheckout(config);
		var btn = document.getElementById("payment-button");
		btn.onclick = function () {
			// minimum transaction amount must be 10, i.e 1000 in paisa.
			checkout.show({amount: "{{$data->amount *100}}"});
		}
	</script>
@endpush
