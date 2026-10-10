@extends(template().'layouts.app')
@section('title',__('Reset Password'))

@section('content')
    <section class="login-section">
        <div class="container h-100">
            <div class="row h-50 justify-content-center">
                <div class="col-lg-7">
                    <div class="img-box">
                        <img src="{{ asset(template(true).'img/login.png') }}" alt="@lang('image')" class="img-fluid"/>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-wrapper d-flex align-items-center h-100">
                        <div class="form-box">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <div class="row g-4">
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="input-box col-12">
                                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                        <input type="email" name="email" class="form-control"
                                               value="{{ $email ?? old('email') }}" required autocomplete="email"
                                               autofocus
                                               placeholder="@lang('Enter Your Email Address')"/>
                                        @error('email')<span
                                            class="text-danger mt-1">{{ trans($message) }}</span>@enderror
                                    </div>

                                    <div class="input-box col-12 mt-2">
                                        <label for="email" class="form-label">{{ __('Password') }}</label>
                                        <input type="password" name="password" class="form-control" value="" required
                                               placeholder="@lang('Enter a new password')"/>
                                        @error('password')<span
                                            class="text-danger mt-1">{{ trans($message) }}</span>@enderror
                                    </div>

                                    <div class="input-box col-12 mt-2">
                                        <label for="email" class="form-label">{{ __('Confirm Password') }}</label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                               value="" required
                                               placeholder="@lang('Enter confirm password')"/>
                                    </div>
                                    <div class="input-box col-12 mt-3">
                                        <button type="submit" class="btn btn-primary w-100">
                                            {{ __('Reset Password') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
