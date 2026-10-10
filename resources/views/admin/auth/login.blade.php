@extends('admin.layouts.login')
@section('page_title', __('Admin | Login'))
@section('content')
    <div class="card card-lg mt-lg-5">
        <div class="card-body">
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="fw-semibold">{{ Session::get('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="post" action="{{ route('admin.login.submit') }}" class="js-validate needs-validation"
                  novalidate>
                @csrf
                <div class="text-center">
                    <div class="mb-5">
                        <h1 class="display-5">@lang('Sign in')</h1>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="signinSrEmail">@lang('Email or Username')</label>
                    <input type="text"
                           class="form-control form-control-lg @error('username') is-invalid @enderror @error('email') is-invalid @enderror"
                           name="username" value="{{ old('username', config('demo.IS_DEMO') ? (request()->username ?? 'admin') : '') }}" id="signinSrEmail" autocomplete="off"
                           tabindex="0" placeholder="Enter Email or Username" required>
                    @error('username')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- End Form -->

                <!-- Form -->
                <div class="mb-2">
                    <label class="form-label w-100" for="signupSrPassword">
                        <span>@lang("Password")</span>
                    </label>
                    <div class="input-group input-group-merge" data-hs-validation-validate-class>
                        <input type="password"
                               tabindex="1"
                               class="js-toggle-password form-control form-control-lg @error('password') is-invalid @enderror"
                               name="password"  value="{{ old('password', config('demo.IS_DEMO') ? (request()->password ?? 'admin') : '') }}" id="signupSrPassword"
                               placeholder="Enter Password"
                               data-hs-toggle-password-options='
                               {
                                "target": "#changePassTarget",
                                "defaultClass": "bi-eye-slash",
                                "showClass": "bi-eye",
                                "classChangeTarget": "#changePassIcon"
                                }'>
                        <a id="changePassTarget" class="input-group-append input-group-text"
                           href="javascript:void(0);">
                            <i id="changePassIcon" class="bi-eye"></i>
                        </a>
                    </div>
                    @error('password')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                    <span class="d-flex justify-content-end align-items-center">
                    <a class="form-label-link mb-0" href="{{ route('admin.password.request') }}">
                        @lang("Forgot Password?")</a>
                    </span>
                </div>
                @if($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_admin_login == 1)
                    <div class="row mt-4 mb-4">
                        <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror"
                             data-sitekey="{{ config('google.recaptcha_site_key') }}"></div>
                        @error('g-recaptcha-response')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                @if($basicControl->manual_recaptcha === 1 && $basicControl->manual_recaptcha_admin_login === 1)
                    <div class="mb-4">
                        <label class="form-label" for="captcha">@lang('Captcha Code')</label>
                        <input type="text" tabindex="2"
                               class="form-control form-control-lg @error('captcha') is-invalid @enderror"
                               name="captcha" id="captcha" autocomplete="off"
                               placeholder="Enter Captcha" required>
                        @error('captcha')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="input-group input-group-merge" data-hs-validation-validate-class>
                            <img src="{{route('captcha').'?rand='. rand()}}" id='captcha_image'>
                            <a class="input-group-append input-group-text"
                               href='javascript: refreshCaptcha();'>
                                <i class="bi-arrow-repeat fs-1 text-primary"></i>
                            </a>
                        </div>
                    </div>
                @endif

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember_me" value=""
                           id="termsCheckbox" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="termsCheckbox">
                        @lang('Remember me')
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">@lang('Sign in')</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('script')
    <script>
        'use strict';

        function refreshCaptcha() {
            let img = document.images['captcha_image'];
            img.src = img.src.substring(
                0, img.src.lastIndexOf("?")
            ) + "?rand=" + Math.random() * 1000;
        }
    </script>
@endpush

