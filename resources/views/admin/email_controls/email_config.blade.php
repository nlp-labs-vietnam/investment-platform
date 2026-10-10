@extends('admin.layouts.app')
@section('page_title', __('Email Configuration'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="{{ route('admin.dashboard') }}">@lang("Dashboard")</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Settings")</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("Email Configuration")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang(ucfirst($method) . " Configuration")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.email'), 'suffix' => ''])
            </div>
            <div class="col-lg-9 seo-setting">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang(ucfirst($method) . " Configuration")</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.email.config.update', $method) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <label for="mailFromLabel" class="form-label">@lang('Sender Email')</label>
                                        <input type="email"
                                               class="form-control  @error('sender_email') is-invalid @enderror"
                                               name="sender_email" id="mailFromLabel" autocomplete="off"
                                               placeholder="Sender Email" aria-label="Sender Email"
                                               value="{{ old('sender_email', env('MAIL_FROM_ADDRESS')) }}">
                                        @error('sender_email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="mailFromLabel" class="form-label">@lang('Email Method')</label>
                                        <input type="text"
                                               class="form-control  @error('email_method') is-invalid @enderror"
                                               name="email_method" id="mailFromLabel" autocomplete="off"
                                               placeholder="Email Method" aria-label="Email Method"
                                               value="{{ old('email_method', $method) }}" readonly>
                                        @error('email_method')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    @foreach($mailParameters as $key => $parameter)
                                        <div class="col-sm-6 mb-3">
                                            <label for="@lang($key)"
                                                   class="form-label">{{ __(snake2Title($key)) }}</label>
                                            <div class="input-group input-group-merge"
                                                 data-hs-validation-validate-class>
                                                <input type="{{ $parameter['is_protected'] ? 'password' : 'text' }}"
                                                       id="{{ $key }}"
                                                       class="js-toggle-password form-control @error($key) is-invalid @enderror"
                                                       name="{{ $key }}" placeholder="@lang(snake2Title($key))" value="{{ old($key ,$parameter['value']) }}"/>
                                                @if($parameter['is_protected'])
                                                    <button type="button" id="{{ $key }}" class="input-group-append input-group-text clickShowPassword">
                                                        <i class="bi-eye-slash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @error($key)
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <div class="list-group list-group-lg list-group-flush list-group-no-gutters">
                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <span
                                                                    class="d-block text-dark">@lang("Email Notification")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("If you want to request the user to enable email notifications through a direct message.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type='hidden' value='0'
                                                                           name='email_notification'>
                                                                    <input
                                                                        class="form-check-input @error('email_notification') is-invalid @enderror"
                                                                        type="checkbox"
                                                                        name="email_notification"
                                                                        id="emailNotificationLabel"
                                                                        value="1" {{ $basicControl->email_notification == 1 ? 'checked' : "" }}>
                                                                    <label class="form-check-label"
                                                                           for="emailNotificationLabel"></label>
                                                                    @error('email_notification')
                                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- List Item -->
                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <span
                                                                    class="d-block text-dark">@lang("Email Verification")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("If you're referring to sending an email for email verification during user registration or account setup.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type='hidden' value='0'
                                                                           name='email_verification'>
                                                                    <input
                                                                        class="form-check-input @error('email_verification') is-invalid @enderror"
                                                                        type="checkbox"
                                                                        name="email_verification"
                                                                        id="emailVerificationLabel"
                                                                        value="1" {{ $basicControl->email_verification == 1 ? 'checked' : "" }}>
                                                                    <label class="form-check-label"
                                                                           for="emailVerificationLabel"></label>
                                                                    <label class="form-check-label"
                                                                           for="email_verification"></label>
                                                                    @error('email_verification')
                                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start">
                                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        $(document).on('click', '.clickShowPassword', function () {
            let passInput = $(this).closest('.input-group-merge').find('input');
            if (passInput.attr('type') === 'password') {
                $(this).children().removeClass('bi-eye-slash');
                $(this).children().addClass('bi-eye');
                passInput.attr('type', 'text');
            } else {
                $(this).children().removeClass('bi-eye');
                $(this).children().addClass('bi-eye-slash');
                passInput.attr('type', 'password');
            }
        })
    </script>
@endpush



