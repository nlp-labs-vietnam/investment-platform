@extends(template().'layouts.app')
@section('title',__('Login'))

@section('content')
    <!-- login-signup section start -->
    <section class="login-signup-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 col-lg-12 col-md-10 mx-auto">
                    <div class="login-signup-box">
                        <div class="row g-0 justify-content-center">
                            <div class="col-lg-6">
                                <div class="login-signup-form">
                                    <form action="{{ route('login') }}" method="post">
                                        @csrf

                                        <input type="hidden"
                                               name="timezone"
                                               class="form-control timezone"
                                               placeholder="@lang('timezone')"/>

                                        <div class="section-header">
                                            <h3>@lang('Login To Your Account')</h3>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <input type="text"
                                                       name="username" value="{{ old('username', config('demo.IS_DEMO') ? (request()->username ?? 'demouser') : '') }}"
                                                       class="form-control"
                                                       placeholder="@lang('Email Or Username')"/>
                                                @error('username')<span
                                                    class="text-danger float-left">@lang($message)</span>@enderror
                                                @error('email')<span
                                                    class="text-danger float-left">@lang($message)</span>@enderror
                                            </div>

                                            <div class="col-12">
                                                <div class="password-box">
                                                    <input type="password"
                                                           name="password" value="{{ old('password', config('demo.IS_DEMO') ? (request()->password ?? 'demouser') : '') }}"
                                                           id="passwordInput"
                                                           class="form-control"
                                                           placeholder="Password"/>
                                                    <i id="passwordIcon" class="password-icon fa-regular fa-eye"></i>
                                                    <!-- Added ID -->
                                                </div>
                                            </div>


                                            @if((basicControl()->manual_recaptcha && basicControl()->manual_recaptcha_login))
                                                <div class="input-box mb-4">
                                                    <input type="text" tabindex="2"
                                                           class="form-control @error('captcha') is-invalid @enderror"
                                                           name="captcha" id="captcha" autocomplete="off"
                                                           placeholder="@lang('Enter captcha code')" required>
                                                    @error('captcha')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="mb-4">
                                                    <div
                                                        class="input-group input-group-merge d-flex justify-content-between"
                                                        data-hs-validation-validate-class>
                                                        <img src="{{route('captcha').'?rand='. rand()}}"
                                                             id='captcha_image'>
                                                        <a class="input-group-append input-group-text"
                                                           href='javascript: refreshCaptcha();'>
                                                            <i class="fas fa-sync  "></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if((basicControl()->google_recaptcha == 1) && (basicControl()->google_recaptcha_login == 1))
                                                <div class="form-group">
                                                    {!! NoCaptcha::renderJs() !!}
                                                    {!! NoCaptcha::display() !!}
                                                    @error('g-recaptcha-response')
                                                    <div class="text-danger mt-1 mb-1">@lang($message)</div>
                                                    @enderror
                                                </div>
                                            @endif


                                            <div class="col-12">
                                                <div class="form-check d-flex justify-content-between flex-wrap gap-2">
                                                    <div class="check">
                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               name="remember"
                                                               {{ old('remember') ? 'checked' : '' }}
                                                               id="loginRememberCheck"/>
                                                        <label class="form-check-label" for="loginRememberCheck">
                                                            @lang('Remember me')
                                                        </label>
                                                    </div>
                                                    <div class="forgot highlight">
                                                        <a href="{{ route('password.request') }}">@lang('Forget password?')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="cmn-btn mt-30 w-100"><span>@lang('Log in')</span>
                                        </button>

                                        <div class="pt-20 text-center">
                                            @lang("Don't have an account?")
                                            <p class="mb-0 highlight mt-1"><a
                                                    href="{{ route('register') }}">@lang('Create account')</a>
                                            </p>
                                        </div>
                                    </form>

                                    @if(config('socialite.google_status') || config('socialite.facebook_status') || config('socialite.github_status'))
                                        <hr class="divider">
                                        <div class="social-login-box d-flex justify-content-center flex-wrap gap-3 ">
                                            @if(config('socialite.google_status'))
                                                <a href="{{route('socialiteLogin','google')}}" class="social-btn btn">
                                                    <img src="{{asset('assets/global/images/social-logo/google.png')}}"
                                                         alt="..."> @lang('Google')
                                                </a>
                                            @endif
                                            @if(config('socialite.facebook_status'))
                                                <a href="{{route('socialiteLogin','facebook')}}" class="social-btn btn">
                                                    <img src="{{asset('assets/global/images/social-logo/facebook.png')}}"
                                                         alt="..."> @lang('Facebook')
                                                </a>
                                            @endif
                                            @if(config('socialite.github_status'))
                                                <a href="{{route('socialiteLogin','github')}}" class="social-btn btn">
                                                    <img src="{{asset('assets/global/images/social-logo/github.png')}}"
                                                         alt="..."> @lang('Github')
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="img-box">
                                    <img src="{{ asset(template(true).'img/1.jpg') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- login-signup section end -->
@endsection

@push('js-lib')
    @if((basicControl()->google_recaptcha == 1) && (basicControl()->google_recaptcha_login == 1))
        <script src="{{ asset(template(true) . 'js/google.com_recaptcha_api.js') }}"></script>
    @endif
@endpush

@push('style')
    <style>
        .social-login-box a{
            border-radius: 10px;
            box-shadow: 1px 2px 10px 0 rgba(0,0,0,0.2);
            padding: 10px 30px;
        }
        @media (max-width: 767px){
            .social-login-box a{
                width: 100%;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        'use strict'
        $(document).ready(function () {
            $('.timezone').val(Intl.DateTimeFormat().resolvedOptions().timeZone);
        });


        document.addEventListener("DOMContentLoaded", function () {
            const password = document.getElementById('passwordInput'); // Use ID for password input
            const passwordIcon = document.getElementById('passwordIcon'); // Use ID for password icon

            if (password && passwordIcon) { // Check if elements are found
                passwordIcon.addEventListener("click", function () {
                    if (password.type === 'password') {
                        password.type = 'text';
                        passwordIcon.classList.add('fa-eye-slash');
                        passwordIcon.classList.remove('fa-eye');
                    } else {
                        password.type = 'password';
                        passwordIcon.classList.add('fa-eye');
                        passwordIcon.classList.remove('fa-eye-slash');
                    }
                });
            } else {
                console.error("Password input or icon not found in the DOM.");
            }
        });


        function refreshCaptcha() {
            let img = document.images['captcha_image'];
            img.src = img.src.substring(
                0, img.src.lastIndexOf("?")
            ) + "?rand=" + Math.random() * 1000;
        }
    </script>
@endpush
