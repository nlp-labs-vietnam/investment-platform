@extends(template().'layouts.app')
@section('title',__('Reset Password'))

@section('content')
    <!-- Forget Password Section -->
    <section class="login-section">
        <div class="container h-100">
            <div class="row h-50 justify-content-center">
                <div class="col-lg-7">
                    <div class="img-box">
                        <img src="{{ asset(template(true).'img/login.png') }}" alt="@lang('image')" class="img-fluid" />
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-wrapper d-flex align-items-center h-100">
                        <div class="form-box">
                            <form action="{{ route('user.password.email') }}" method="post">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        @if (session('status'))
                                            <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                                                {{ trans(session('status')) }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endif
                                        <h4>@lang('Recover Password')</h4>
                                    </div>
                                    <div class="input-box col-12">
                                        <input type="email"
                                               name="email"
                                               class="form-control"
                                               value="{{old('email')}}"
                                               placeholder="@lang('Enter Your Email Address')" />
                                        @error('email')<span class="text-danger mt-1">{{ trans($message) }}</span>@enderror
                                    </div>
                                    <div class="input-box col-12">
                                        <button class="btn-custom" type="submit">@lang('Submit')</button>
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

