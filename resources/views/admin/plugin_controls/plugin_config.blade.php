@extends('admin.layouts.app')
@section('page_title', __('Plugin Controls'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a class="breadcrumb-link" href="{{ route('admin.settings') }}">@lang('Settings')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Plugin Controls')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Plugin Controls')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.plugin'), 'suffix' => ''])
            </div>
            <div class="col-lg-7">
                <div class="d-grid gap-3 gap-lg-5">
                    <div id="socialAccountsSection" class="card">
                        <div class="card-header">
                            <h4 class="card-title">@lang("Plugin Configuration")</h4>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-lg list-group-flush list-group-no-gutters">
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3 list-group-icon"
                                                     src="{{ asset('assets/admin/plugin/tawk.png') }}"
                                                     alt="Plugin Image">
                                            </div>

                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h4 class="mb-0">@lang('Tawk')</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("Message your customers,they'll love you for it")</p>
                                                    </div>
                                                    <div class="col-sm-auto">
                                                        <a class="btn btn-white btn-sm"
                                                           href="{{ route('admin.tawk.configuration') }}">
                                                            <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Item -->

                                    <!-- Item -->
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3 list-group-icon"
                                                     src="{{ asset('assets/admin/plugin/messenger.png') }}"
                                                     alt="FB Image">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h4 class="mb-0">@lang("FB Messenger")</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("Message your customers,they'll love you for it")</p>
                                                    </div>
                                                    <div class="col-sm-auto">
                                                        <a class="btn btn-white btn-sm"
                                                           href="{{ route('admin.fb.messenger.configuration') }}">
                                                            <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Item -->

                                    <!-- Item -->
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3 list-group-icon"
                                                     src="{{ asset('assets/admin/plugin/reCaptcha.png') }}"
                                                     alt="Plugin Image">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h4 class="mb-0">@lang("Google Recaptcha")</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("reCAPTCHA protects your website from fraud and abuse.")</p>
                                                    </div>
                                                    <div class="col-sm-auto d-flex align-items-center">
                                                            <div class="form-check form-switch form-switch-google">
                                                                <input type="hidden" name="google_recaptcha" value="0">
                                                                <input class="form-check-input" name="google_recaptcha"
                                                                       type="checkbox" id="activeRecaptchaGoogle"
                                                                       value="1" {{ $basicControl->google_recaptcha == 1 ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                       for="activeRecaptchaGoogle"></label>
                                                                @error('active_recaptcha')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <a class="btn btn-white btn-sm"
                                                               href="{{ route('admin.google.recaptcha.configuration') }}">
                                                                <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                                            </a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Item -->

                                    <!-- Item -->
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3 list-group-icon"
                                                     src="{{ asset('assets/admin/plugin/manual_recaptcha.svg') }}"
                                                     alt="Google Analytics Image">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h4 class="mb-0">@lang('Manual Recaptcha')</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("reCAPTCHA protects your website from fraud and abuse.")</p>
                                                    </div>
                                                    <div class="col-sm-auto d-flex align-items-center">

                                                            <div class="form-check form-switch form-switch-manual">
                                                                <input type="hidden" name="manual_recaptcha" value="0">
                                                                <input class="form-check-input" name="manual_recaptcha"
                                                                       type="checkbox" id="activeRecaptchaManual"
                                                                       value="1" {{ $basicControl->manual_recaptcha == 1 ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                       for="activeRecaptchaManual"></label>
                                                                @error('active_recaptcha')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <a class="btn btn-white btn-sm"
                                                               href="{{ route('admin.manual.recaptcha') }}">
                                                                <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                                            </a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Item -->
                                    <!-- Item -->
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3 list-group-icon"
                                                     src="{{ asset('assets/admin/plugin/analytics.png') }}"
                                                     alt="Google Analytics Image">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h4 class="mb-0">@lang('Google Analytics')</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("Google Analytics is a web analytics service offered by Google")</p>
                                                    </div>
                                                    <div class="col-sm-auto">
                                                        <a class="btn btn-white btn-sm"
                                                           href="{{ route('admin.google.analytics.configuration') }}">
                                                            <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Item -->
                                </div>
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
        $(document).ready(function () {
            $(document).on('click', '.form-switch-google, .form-switch-manual', function () {

                const googleRecaptcha = $('#activeRecaptchaGoogle').prop('checked') ? '1' : '0';
                const manualRecaptcha = $('#activeRecaptchaManual').prop('checked') ? '1' : '0';
                $.ajax({
                    url: "{{ route('admin.active.recaptcha') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        googleRecaptcha,
                        manualRecaptcha
                    },
                    success: function (response) {
                        if (response.success) {
                            Notiflix.Notify.success(response.message);
                        }
                        if (response.success === false) {
                            Notiflix.Notify.warning(response.message);
                        }
                    },
                    error: function (error) {
                        console.error('AJAX Error:', error);
                    }
                });
            });

        });
    </script>
@endpush




