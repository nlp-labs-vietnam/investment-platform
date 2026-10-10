@extends(template().'layouts.user')
@section('page_title')
	{{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@section('section')
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
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>{{ __('Pay with ').__(optional($deposit->gateway)->name) }}</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active">
                                <a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                            </div>
                            <div class="breadcrumb-item">{{ __('Pay with ').__(optional($deposit->gateway)->name) }}</div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-body text-center">
                                    <form
                                        action="{{ route('ipn', [optional($deposit->gateway)->code ?? 'mercadopago', $deposit->utr]) }}"
                                        method="POST">
                                        <script src="https://www.mercadopago.com.co/integrations/v1/web-payment-checkout.js"
                                                data-preference-id="{{ $data->preference }}">
                                        </script>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>


@endsection
