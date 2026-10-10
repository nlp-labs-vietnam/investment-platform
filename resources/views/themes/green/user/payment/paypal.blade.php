@extends(template().'layouts.user')
@section('page_title', __('Pay with PAYPAL'))
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
                <div class="col-md-7">
                    <div class="card custom-card pt-3">
                        <div class="card-body">
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://www.paypal.com/sdk/js?client-id={{ $data->cleint_id }}">
    </script>
    <script>
        paypal.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [
                        {
                            description: "{{ $data->description }}",
                            custom_id: "{{ $data->custom_id }}",
                            amount: {
                                currency_code: "{{ $data->currency }}",
                                value: "{{ $data->amount }}",
                                breakdown: {
                                    item_total: {
                                        currency_code: "{{ $data->currency }}",
                                        value: "{{ $data->amount }}"
                                    }
                                }
                            }
                        }
                    ]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    var trx = "{{ $data->custom_id }}";
                    window.location = '{{ url('payment/paypal')}}/' + trx + '/' + details.id
                });
            }
        }).render('#paypal-button-container');
    </script>
@endpush
