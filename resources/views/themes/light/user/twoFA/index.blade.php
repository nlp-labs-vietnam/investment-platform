@extends(template().'layouts.user')
@section('title',__('2 Step Security'))

@section('content')
    <section class="transaction-history twofactor">
        <div class="container-fluid">
            <div class="row mt-2 ms-1">
                <div class="col">
                    <div class="header-text-full mt-2">
                        <h3 class="dashboard_breadcurmb_heading mb-1">@lang('2 Step Security')</h3>
                        <nav aria-label="breadcrumb" class="ms-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                                <li class="breadcrumb-item active" aria-current="page">@lang('2 Step Security')</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="row ms-1">
                @if(auth()->user()->two_fa == 1)
                    <div class="col-lg-6 col-md-6 mb-3 coin-box-wrapper">
                        <div class="card text-center bg-dark py-2 two-factor-authenticator">
                            <h3 class="card-title golden-text">@lang('Two Factor Authenticator')</h3>

                            <div class="card-box-header mb-3 d-flex justify-content-center align-items-center">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#regenerateModal"
                                        class="btn-custom">
                                    @lang("Regenerate")</button>
                            </div>

                            <div class="card-body">
                                <div class="box refferal-link">
                                    <div class="input-group mt-0">
                                        <input
                                            type="text"
                                            value="{{$secret}}"
                                            class="form-control"
                                            id="referralURL"
                                            readonly
                                        />
                                        <button class="gold-btn copytext" id="copyBoard" onclick="copyFunction()"><i
                                                class="fal fa-copy"></i></button>
                                    </div>
                                </div>

                                <div class="form-group mx-auto text-center py-4">
                                    <img class="mx-auto" src="{{$qrCodeUrl}}">
                                </div>

                                <div class="form-group mx-auto text-center">
                                    <a href="javascript:void(0)" class="btn btn-bg btn-lg btn-custom-authenticator"
                                       data-bs-toggle="modal"
                                       data-bs-target="#disableModal">@lang('Disable Two Factor Authenticator')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-lg-6 col-md-6 mb-3 coin-box-wrapper">
                        <div class="card text-center bg-dark py-2 two-factor-authenticator">
                            <h3 class="card-title golden-text mt-4">@lang('Two Factor Authenticator')</h3>

                            <div class="card-box-header mb-3 d-flex justify-content-center align-items-center">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#regenerateModal"
                                        class="btn-custom">@lang("Regenerate")</button>
                            </div>
                            <div class="card-body">
                                <div class="box refferal-link">

                                    <div class="input-group mt-0">
                                        <input
                                            type="text"
                                            value="{{$secret}}"
                                            class="form-control"
                                            id="referralURL"
                                            readonly
                                        />
                                        <button class="gold-btn copytext" id="copyBoard" onclick="copyFunction()"><i
                                                class="fal fa-copy"></i></button>
                                    </div>
                                </div>

                                <div class="form-group mx-auto text-center py-4">
                                    <img class="mx-auto" src="{{$qrCodeUrl}}">
                                </div>

                                <div class="form-group mx-auto text-center">
                                    <a href="javascript:void(0)" class="btn btn-bg btn-lg btn-custom-authenticator"
                                       data-bs-toggle="modal"
                                       data-bs-target="#enableModal">@lang('Enable Two Factor Authenticator')</a>
                                </div>
                            </div>

                        </div>
                    </div>

                @endif


                <div class="col-lg-6 col-md-6 mb-3">
                    <div class="card text-center bg-dark py-2 two-factor-authenticator h-100">
                        <h3 class="card-title golden-text pt-4">@lang('Google Authenticator')</h3>
                        <div class="card-body">

                            <h6 class="text-uppercase my-3">@lang('Use Google Authenticator to Scan the QR code  or use the code')</h6>

                            <p class="py-3">@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.')</p>
                            <a class="btn btn btn-bg btn-md mt-3 btn-custom-authenticator"
                               href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                               target="_blank">@lang('DOWNLOAD APP')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Enable Modal -->
    <div class="modal fade" id="enableModal" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="planModalLabel">@lang('Verify Your OTP')</h4>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>

                <form action="{{route('user.twoStepEnable')}}" method="POST" class="m-0">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="input-box col-12">
                                <input type="hidden" name="key" value="{{$secret}}">
                                <input type="text" class="form-control" name="code"
                                       placeholder="@lang('Enter Google Authenticator Code')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom close__btn"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <button class="btn-custom" type="submit">@lang('Verify')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Disable Modal -->
    <div class="modal fade" id="disableModal" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="planModalLabel">@lang('Verify Your OTP to Disable')</h4>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="{{route('user.twoStepDisable')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="password-box">
                            <input name="password" type="text" class="form-control password"
                                   id="currentPassword"
                                   value="{{ old('password') }}"
                                   placeholder="{{ trans('Enter Your Password') }}"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class=" btn-custom2"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <button class="btn-custom" type="submit">@lang('Verify')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- regenerate Modal -->
    <div class="modal fade" id="regenerateModal" tabindex="-1" aria-labelledby="regenerateModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="planModalLabel">@lang('Re-generate Confirmation')</h4>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="{{route('user.twoStepRegenerate')}}" method="POST" class="m-0">
                    @csrf
                    <div class="modal-body">
                        @lang("Are you want to Re-generate Authenticator ?")
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn-danger"
                                data-bs-dismiss="modal">@lang('No')</button>
                        <button class="btn-custom" type="submit">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection



@push('script')
    <script>
        function copyFunction() {
            var copyText = document.getElementById("referralURL");
            navigator.clipboard.writeText(copyText.value).then(function () {
                Notiflix.Notify.success(`Copied: ${copyText.value}`);
            });
        }
    </script>
@endpush

