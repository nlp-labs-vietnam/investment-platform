@extends('admin.layouts.app')
@section('page_title', __('SMS Settings'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0);">@lang("Dashboard")</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Settings")</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("SMS Setting")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang(ucwords($method) . " Configuration")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.sms'), 'suffix' => ''])
            </div>
            <div class="col-lg-9">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang(ucwords($method) . " Configuration")</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.sms.config.update', $method) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="urlLabel" class="form-label">@lang('Method Name')</label>
                                        <input
                                            type="text"
                                            class="form-control @error('method_name') is-invalid @enderror"
                                            name="method_name"
                                            id="method_name"
                                            placeholder="@lang("Method Name")"
                                            aria-label="@lang("Method Name")"
                                            value="{{ old('method_name', ucwords($method)) }}"
                                            readonly
                                        />
                                        @error('url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @foreach($smsMethodParameters as $key => $parameter)
                                        <div class="col-sm-6 mb-3">
                                            <label for="{{$key}}"
                                                   class="form-label">@lang(stringToTitle($key))</label>
                                            <div class="input-group input-group-merge"
                                                 data-hs-validation-validate-class>
                                                <input type="{{ $parameter['is_protected'] ? 'password' : 'text' }}"
                                                       class="js-toggle-password form-control @error($key) is-invalid @enderror"
                                                       name="{{ $key }}" id="method_name"
                                                       placeholder="@lang(snake2Title($key))"
                                                       value="{{ old($key ,$parameter['value']) }}" autocomplete="off"
                                                       data-hs-toggle-password-options='{ "target": "#{{$key.'id'}}", "defaultClass": "bi-eye-slash", "showClass":"bi-eye", "classChangeTarget": "#{{$key}}" }'/>
                                                @if($parameter['is_protected'])
                                                    <button type="button" id="{{$key.'id'}}"
                                                            class="input-group-append input-group-text">
                                                        <i id="{{$key}}" class="bi-eye"></i>
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
                                                                    class="d-block text-dark">@lang("SMS Notification")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("SMS notification is a brief text message sent to your customer to convey important information.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" value="0"
                                                                           name="sms_notification"/>
                                                                    <input
                                                                        class="form-check-input @error('sms_notification') is-invalid @enderror"
                                                                        type="checkbox" name="sms_notification"
                                                                        id="smsNotificationLabel" value="1"
                                                                        {{($basicControl->sms_notification == 1) ? 'checked' : ''}}> @error('sms_notification')
                                                                    <span
                                                                        class="invalid-feedback d-block">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End List Item -->

                                            <!-- List Item -->
                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <span
                                                                    class="d-block text-dark">@lang("SMS Verification")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("SMS Verification is a brief text message sent to your customer to convey important information.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" value="0"
                                                                           name="sms_verification"/>
                                                                    <input
                                                                        class="form-check-input @error('sms_verification') is-invalid @enderror"
                                                                        type="checkbox" name="sms_verification"
                                                                        id="smsVerificationLabel" value="1"
                                                                        {{($basicControl->sms_verification == 1) ? 'checked' : ''}}> @error('sms_verification')
                                                                    <span
                                                                        class="invalid-feedback d-block">{{ $message }}</span>
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

                                <div class="d-flex justify-content-end">
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

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-toggle-password.js") }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            new HSTogglePassword('.js-toggle-password')
            HSCore.components.HSTomSelect.init('.js-select')
        });
    </script>
@endpush
