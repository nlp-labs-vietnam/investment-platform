@extends(template().'layouts.app')
@section('title',__('Register'))


@section('content')
    <!-- login-signup section start -->
    <section class="login-signup-section">
        <div class="container">
            <div class="row">
                <div class="col-xxl-10 col-lg-12 col-md-10 mx-auto">
                    <div class="login-signup-box">
                        <div class="row g-0 justify-content-center">
                            <div class="col-xl-6 col-lg-7">
                                <div class="login-signup-form">
                                    <form action="{{ route('register') }}" method="post">
                                        @csrf
                                        <div class="section-header">
                                            <h3>@lang('Sign Up For New Account')</h3>
                                        </div>

                                        <div class="row g-4">
                                            @if(session()->get('sponsor') != null)
                                                <div class="col-md-12">
                                                    <label for="" class="form-label">@lang('Referral Username')</label>
                                                    <input type="text" name="sponsor" id="sponsor" class="form-control"
                                                           placeholder="{{trans('Referral By') }}"
                                                           value="{{session()->get('sponsor')}}" readonly/>
                                                    @error('sponsor')<span
                                                        class="text-danger mt-1">@lang($message)</span>@enderror
                                                </div>
                                            @endif

                                            <div class="col-md-6">
                                                <input type="text" name="firstname" class="form-control"
                                                       value="{{old('firstname')}}" placeholder="@lang('First Name')"/>
                                                @error('firstname')<span
                                                    class="text-danger mt-1">@lang($message)</span>@enderror
                                            </div>

                                            <div class="col-md-6">
                                                <input type="text" name="lastname" class="form-control"
                                                       value="{{old('lastname')}}" placeholder="@lang('Last Name')"/>
                                                @error('lastname')<span
                                                    class="text-danger mt-1">@lang($message)</span>@enderror
                                            </div>

                                            <div class="col-md-6">
                                                <input type="text" name="username" class="form-control"
                                                       value="{{old('username')}}" placeholder="@lang('Username')"/>
                                                @error('username')<span
                                                    class="text-danger mt-1">@lang($message)</span>@enderror
                                            </div>

                                            <div class="col-md-6">
                                                <input type="text" name="email" class="form-control"
                                                       value="{{old('email')}}"
                                                       placeholder="@lang('Email Address')"/>
                                                @error('email')<span
                                                    class="text-danger mt-1">@lang($message)</span>@enderror
                                            </div>

                                            <div class="col-md-12">
                                                <input id="telephone" class="form-control" name="phone" type="text"
                                                       value="{{old('phone')}}" placeholder="@lang('Phone Number')">
                                                @error('phone')
                                                <span class="text-danger mt-1">@lang($message)</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 d-none">
                                                <input type="hidden" name="phone_code"
                                                       value="{{old('phone_code')}}" class="countryPhoneCode">
                                            </div>

                                            <div class="col-md-6">
                                                <div class="password-box">
                                                    <input type="password" name="password" class="form-control"
                                                           placeholder="@lang('Password')"/>
                                                    <i class="password-icon fa-regular fa-eye"></i>

                                                </div>
                                                @error('password')<span
                                                    class="text-danger mt-1">@lang($message)</span>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <div class="password-box">
                                                    <input type="password" name="password_confirmation"
                                                           class="form-control"
                                                           placeholder="@lang('Confirm Password')"/>
                                                    <i class="password-icon fa-regular fa-eye"></i>

                                                </div>
                                                @error('password_confirmation')<span
                                                    class="text-danger mt-1">@lang($message)</span>@enderror
                                            </div>

                                            @if(basicControl()->manual_recaptcha && basicControl()->manual_recaptcha_register)
                                                <div class="input-box mb-4">
                                                    <input type="text" tabindex="2"
                                                           class="form-control @error('captcha') is-invalid @enderror"
                                                           name="captcha" id="captcha" autocomplete="off"
                                                           placeholder="@lang('Enter captcha code')" required>
                                                    @error('captcha')
                                                    <span class="text-danger">{{$message}}</span>
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
                                                            <i class="fa-solid fa-rotate  "></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if((basicControl()->google_recaptcha == 1) && (basicControl()->google_recaptcha_register))
                                                <div class="form-group">
                                                    {!! NoCaptcha::renderJs() !!}
                                                    {!! NoCaptcha::display() !!}
                                                    @error('g-recaptcha-response')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            @endif

                                            <div class="col-12">
                                                 <div class="form-check d-flex justify-content-between flex-wrap gap-2">
                                                     <div class="check">
                                                         <input type="checkbox" class="form-check-input"
                                                                id="registerTermsCheck">
                                                         <label class="form-check-label"
                                                                for="registerTermsCheck">@lang('I Agree with the') <a href="{{ route('page', 'terms-and-conditions') }}" target="_blank">@lang('Terms & conditions')</a></label>
                                                     </div>
                                                 </div>
                                             </div>
                                        </div>
                                        <button type="submit" class="cmn-btn mt-30 w-100"><span>@lang('sign up')</span>
                                        </button>
                                        <div class="pt-20 text-center">
                                            @lang('Already have an account?')
                                            <p class="mb-0 highlight mt-1"><a
                                                    href="{{ route('login') }}">@lang('Login here')</a></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-5 d-none d-lg-block">
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

@push('css-lib')
    <link rel="stylesheet" href="{{ asset(template(true).'css/intlTelInput.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset(template(true).'js/intlTelInput.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            setDialCode();
            $(document).on('click', '#iti-0__country-listbox', function () {
                setDialCode();
            });

            function setDialCode() {
                let currency = $('.iti__selected-dial-code').text();
                $('.countryPhoneCode').val(currency);
            }
        });

    </script>
@endpush
