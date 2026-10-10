@extends(template().'layouts.app')
@section('title',__('Reset Password'))

@section('content')
    <!-- Forget Password -->
    <section class="login-signup-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 col-lg-12 col-md-10 mx-auto">
                    <div class="login-signup-box">
                        <div class="row g-0 justify-content-center">
                            <div class="col-lg-6">
                                <div class="login-signup-form">
                                    <form action="{{ route('password.email') }}" method="post">
                                        @csrf

                                        <div class="section-header">
                                            @if (session('status'))
                                                <div class="alert alert-success alert-dismissible fade show w-100"
                                                     role="alert">
                                                    {{ trans(session('status')) }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                            aria-label="Close"></button>
                                                </div>
                                            @endif
                                            <h3>@lang('Recover Password')</h3>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <input type="email"
                                                       name="email"
                                                       class="form-control"
                                                       value="{{old('email')}}"
                                                       placeholder="@lang('Enter Your Email Address')"/>
                                                @error('email')<span
                                                    class="text-danger mt-1">{{ trans($message) }}</span>@enderror
                                            </div>

                                        </div>

                                        <button type="submit" class="cmn-btn mt-30 w-100"><span>@lang('Submit')</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

