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
                    <h4>{{ __('Pay with ').__(optional($deposit->gateway)->name) }}</h4>
                    <div class="card card-primary shadow">
                        <div class="card-header">@lang('Payment Preview')</div>
                        <div class="card-body text-center">
                            <h4 class="text-color"> @lang('PLEASE SEND EXACTLY') <span
                                    class="text-success"> {{ getAmount($data->amount) }}</span> {{ __($data->currency) }}
                            </h4>
                            <h5>@lang('TO')</h5>

                            <div class="col-md-6 mx-auto">
                                <div class="input-group">
                                    <input type="text" id="copy-code" value="{{ $data->sendto }}" class="form-control">
                                    <div class="input-group-text" onclick="copyFunction()"><i
                                            class="fa-light fa-copy"></i></div>
                                </div>
                            </div>

                            <img src="{{ $data->img }}">
                            <h4 class="text-color bold">@lang('SCAN TO SEND')</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        function copyFunction() {
            let copyText = document.getElementById("copy-code");
            navigator.clipboard.writeText(copyText.value).then(function () {
                Notiflix.Notify.success(`Copied: ${copyText.value}`);
            });
        }
    </script>
@endpush
