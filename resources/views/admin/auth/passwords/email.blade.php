@extends('admin.layouts.login')
@section('page_title', __('Admin Forget Password'))
@section('content')
    <div class="card card-lg mt-lg-5 mb-5">
        <div class="card-body">
            @if(Session::has('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="fw-semibold">@lang("We will send a link to reset your password.")</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="post" action="{{ route('admin.password.email') }}">
                @csrf
                <div class="text-center">
                    <div class="mb-5">
                        <h1 class="display-5">@lang("Forgot password?")</h1>
                        <p>@lang("Enter the email address you used when you joined and we'll send you instructions to reset
                            your password.")</p>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="resetPasswordSrEmail" tabindex="0">@lang("Your email")</label>
                    <input type="email" class="form-control form-control-lg" name="email" id="resetPasswordSrEmail"
                           tabindex="1" placeholder="@lang("Enter your email address")" aria-label="@lang("Enter your email address")"
                           autocomplete="off" required>
                    <span class="invalid-feedback">@lang("Please enter a valid email address.")</span>
                    @error('email')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">@lang("Submit")</button>
                    <div class="text-center">
                        <a class="btn btn-link" href="{{ route('admin.login') }}">
                            <i class="bi-chevron-left"></i> @lang("Back to Sign in")
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
