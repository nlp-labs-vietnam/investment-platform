@extends(template().'layouts.app')
@section('title',$page_title)

@section('content')
    <!-- Sms Verification Password -->
    <section class="login-signup-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 col-lg-12 col-md-10 mx-auto">
                    <div class="login-signup-box">
                        <div class="row g-0 justify-content-center">
                            <div class="col-lg-6">
                                <div class="login-signup-form">
                                    <form action="{{route('user.sms.verify')}}" method="post">
                                        @csrf
                                        <div class="section-header">
                                            <h3>@lang('Enter Your Code')</h3>
                                            <p class="d-flex flex-wrap">@lang("Your Mobile Number is") {!! maskString(auth()->user()->phone) !!}</p>
                                        </div>

                                        <div class="row g-4">
                                            <div class="col-12">
                                                <input type="text"
                                                       name="code"
                                                       class="form-control"
                                                       value="{{old('code')}}"
                                                       placeholder="@lang('Enter Your Code')"
                                                       autocomplete="off"/>
                                                @error('code')<span class="text-danger mt-1">{{ trans($message) }}</span>@enderror
                                                @error('error')<span class="text-danger mt-1">{{ trans($message) }}</span>@enderror
                                            </div>

                                        </div>

                                        <button type="submit" class="cmn-btn mt-30 w-100"><span>@lang('Submit')</span>
                                        </button>

                                        <div class="pt-20 text-center">
                                            @lang("Didn't get Code? Click to")
                                            <p class="mb-0 highlight mt-1"><a
                                                    href="{{route('user.resend.code')}}?type=mobile">@lang('Resend code')</a>
                                            </p>
                                            @error('resend')
                                            <p class="text-danger mt-1">{{ trans($message) }}</p>
                                            @enderror
                                        </div>
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
