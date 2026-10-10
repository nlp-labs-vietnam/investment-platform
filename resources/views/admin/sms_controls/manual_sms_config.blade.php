@extends('admin.layouts.app')
@section('page_title', __('SMS Setting'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manual SMS Setting')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Manual SMS Setting')</h1>
                </div>
            </div>
        </div>


        @php
            if (old()){
                $headerData = array_combine(old('headerDataKeys'),old('headerDataValues'));
                $paramData = array_combine(old('paramKeys'),old('paramValues'));
                $formData = array_combine(old('formDataKeys'),old('formDataValues'));
                $headerData = (empty(array_filter($headerData))) ? null : json_encode($headerData);
                $paramData = (empty(array_filter($paramData))) ? null : json_encode($paramData);
                $formData = (empty(array_filter($formData))) ? null : json_encode($formData);
            } else {
                $headerData = $manualSMSMethod['header_data'];
                $paramData = $manualSMSMethod['param_data'];
                $formData = $manualSMSMethod['form_data'];
            }
            $headerActive = false;
            $paramActive = false;
            $formActive = false;
            if ($errors->has('headerDataKeys.*') || $errors->has('headerDataValues.*')) {
                $headerActive = true;
            }elseif ($errors->has('paramKeys.*') || $errors->has('paramValues.*')) {
                $paramActive = true;
            } elseif ($errors->has('formDataKeys.*') || $errors->has('formDataValues.*')) {
                $formActive = true;
            } else {
                $headerActive = true;
            }
        @endphp
        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.sms'), 'suffix' => ''])
            </div>
            <div class="col-lg-9">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Manual SMS Configuration')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.manual.sms.method.update', $method) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-5">
                                    <div class="col-sm-6">
                                        <label for="methodLabel" class="form-label">@lang('Method')</label>
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" autocomplete="off" name="actionMethod"
                                                    data-hs-tom-select-options='{
                                                      "placeholder": "Select a method",
                                                      "hideSearch": true
                                                    }'>
                                                <option value="" selected>@lang("Select a method")</option>
                                                <option
                                                    value="GET" {{ (old('actionMethod',$manualSMSMethod->action_method) == 'GET') ? 'selected' : '' }}>
                                                    @lang("GET")
                                                </option>
                                                <option
                                                    value="POST" {{ (old('actionMethod',$manualSMSMethod->action_method) == 'POST') ? 'selected' : '' }}>
                                                    @lang("POST")
                                                </option>
                                            </select>
                                            <span class="invalid-feedback d-block">
                                                @error('actionMethod') @lang($message) @enderror
                                            </span>
                                        </div>
                                        @error('method')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="urlLabel" class="form-label">@lang('URL')</label>
                                        <input type="url"
                                               class="form-control  @error('actionUrl') is-invalid @enderror"
                                               name="actionUrl" id="urlLabel"
                                               placeholder="URl" aria-label="URL"
                                               value="{{ old('actionUrl',$manualSMSMethod->action_url) }}">
                                        @error('actionUrl')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <label class="row form-check form-switch" for="smsNotificationLabel">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="text-dark">@lang("SMS Notification")</span>
                                            <p class="fs-5 text-body mb-0">@lang("To enable SMS Notification in your application")</p>
                                        </span>
                                            <span class="col-4 col-sm-3 text-end">
                                                <input type='hidden' value='0' name='sms_notification'>
                                            <input
                                                class="form-check-input @error('sms_notification') is-invalid @enderror"
                                                type="checkbox" name="sms_notification"
                                                id="smsNotificationLabel"
                                                value="1" {{($basicControl->sms_notification == 1) ? 'checked' : ''}}>
                                        </span>
                                        </label>
                                        @error('sms_notification')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="row form-check form-switch" for="smsVerificationLabel">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="text-dark">@lang("SMS Verification")</span>
                                            <p class="fs-5 text-body mb-0">@lang("To enable SMS verification in your application")</p>
                                        </span>
                                            <span class="col-4 col-sm-3 text-end">
                                                <input type='hidden' value='0' name='sms_verification'>
                                            <input
                                                class="form-check-input @error('sms_verification') is-invalid @enderror"
                                                type="checkbox" name="sms_verification"
                                                id="smsVerificationLabel"
                                                value="1" {{($basicControl->sms_verification == 1) ? 'checked' : ''}}>
                                        </span>
                                        </label>
                                        @error('sms_verification')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul class="nav nav-segment mb-4" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="nav-one-eg1-tab" href="#nav-one-eg1"
                                                   data-bs-toggle="pill" data-bs-target="#nav-one-eg1" role="tab"
                                                   aria-controls="nav-one-eg1"
                                                   aria-selected="true">@lang("Headers")</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="nav-two-eg1-tab" href="#nav-two-eg1"
                                                   data-bs-toggle="pill" data-bs-target="#nav-two-eg1" role="tab"
                                                   aria-controls="nav-two-eg1"
                                                   aria-selected="false">@lang("Params")</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="nav-three-eg1-tab" href="#nav-three-eg1"
                                                   data-bs-toggle="pill" data-bs-target="#nav-three-eg1" role="tab"
                                                   aria-controls="nav-three-eg1"
                                                   aria-selected="false">@lang("From Data")</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="nav-one-eg1" role="tabpanel"
                                                 aria-labelledby="nav-one-eg1-tab">
                                                <div class="js-add-field headerDataWrapper">
                                                    @if(is_null($headerData))
                                                        <div class="row mb-4 headers-html">
                                                            <div class="col-sm-4 mb-2 mb-sm-0">
                                                                <input type="text" class="form-control"
                                                                       name="headerDataKeys[]"
                                                                       placeholder="Key" aria-label="Key">
                                                                <span class="invalid-feedback">
                                                                        @error("headerDataKeys") @lang($message)@enderror
                                                                    </span>
                                                            </div>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control"
                                                                       name="headerDataValues[]"
                                                                       placeholder="Value" aria-label="Value">
                                                            </div>
                                                            <div class="col-sm-1">
                                                                <button type="button"
                                                                        class="btn btn-soft-primary btn-sm headers-btn addHeaderData">
                                                                    <i class="bi bi-plus-circle"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @foreach(json_decode($headerData) as $key => $value)
                                                            <div class="row mb-4 headers-html">
                                                                <div class="col-sm-4 mb-2 mb-sm-0">
                                                                    <input type="text"
                                                                           class="form-control  @error('headerDataKeys.'.$loop->index) is-invalid @enderror"
                                                                           name="headerDataKeys[]" value="{{$key}}"
                                                                           placeholder="Key" aria-label="Key"
                                                                           autocomplete="off">
                                                                    @error('headerDataKeys.'.$loop->index)
                                                                    <span class="invalid-feedback">
                                                                    {{ __($message) }}
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-sm-7">
                                                                    <input type="text"
                                                                           class="form-control @error('headerDataValues.'.$loop->index) is-invalid @enderror"
                                                                           name="headerDataValues[]" value="{{$value}}"
                                                                           placeholder="Value" aria-label="Value"
                                                                           autocomplete="off">
                                                                    <span class="invalid-feedback">
                                                                        @error("headerDataValues.".$loop->index) @lang($message)@enderror
                                                                    </span>
                                                                </div>

                                                                @if($loop->first)
                                                                    <div class="col-sm-1">
                                                                        <a href="javascript:void(0);"
                                                                           class="btn btn-soft-primary btn-sm headers-btn addHeaderData">
                                                                            <i class="bi bi-plus-circle"></i>
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    <div class="col-sm-1">
                                                                        <a href="javascript:void(0);"
                                                                           class="btn btn-ghost-secondary btn-sm headers-btn removeDiv">
                                                                            <i class="bi-trash"></i>
                                                                        </a>
                                                                    </div>
                                                                @endif

                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="nav-two-eg1" role="tabpanel"
                                                 aria-labelledby="nav-two-eg1-tab">
                                                <div class="paramsWrapper">
                                                    @if(is_null($paramData))
                                                        <div class="row mb-4 params-html">
                                                            <div class="col-sm-4 mb-2 mb-sm-0">
                                                                <input type="text" class="form-control"
                                                                       name="paramKeys[]"
                                                                       placeholder="Key" aria-label="Key">
                                                            </div>

                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control"
                                                                       name="paramValues[]"
                                                                       placeholder="Value" aria-label="Value">
                                                            </div>
                                                            <div class="col-sm-1">
                                                                <button type="button"
                                                                        class="btn btn-soft-primary btn-sm params-btn addParams">
                                                                    <i class="bi bi-plus-circle"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @foreach(json_decode($paramData) as $key => $value)
                                                            <div class="row mb-4 params-html">
                                                                <div class="col-sm-4 mb-2 mb-sm-0">
                                                                    <input type="text"
                                                                           class="form-control  @error('paramKeys.'.$loop->index) is-invalid @enderror"
                                                                           name="paramKeys[]" value="{{$key}}"
                                                                           placeholder="@lang("Key")"
                                                                           aria-label="@lang("Key")"
                                                                           autocomplete="off">
                                                                    <span class="invalid-feedback">
                                                                        @error("paramKeys.".$loop->index) @lang($message)
                                                                        @enderror
                                                                    </span>
                                                                </div>

                                                                <div class="col-sm-6">
                                                                    <input type="text"
                                                                           class="form-control @error('paramValues.'.$loop->index) is-invalid @enderror"
                                                                           name="paramValues[]" value="{{$value}}"
                                                                           placeholder="Value" aria-label="Value"
                                                                           autocomplete="off">
                                                                    <span class="invalid-feedback">
                                                                        @error("paramValues.".$loop->index) @lang($message)
                                                                        @enderror
                                                                    </span>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    @if($loop->first)
                                                                        <button type="button"
                                                                                class="btn btn-soft-primary btn-sm params-btn addParams">
                                                                            <i class="bi bi-plus-circle"></i>
                                                                        </button>
                                                                    @else
                                                                        <button type="button"
                                                                                class="btn btn-soft-danger btn-sm params-btn removeDiv">
                                                                            <i class="bi-trash"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="nav-three-eg1" role="tabpanel"
                                                 aria-labelledby="nav-three-eg1-tab">
                                                <div class="formDataWrapper">
                                                    @if(is_null($formData))
                                                        <div class="row mb-4 form-data-html">
                                                            <div class="col-sm-4 mb-2 mb-sm-0">
                                                                <input type="text" class="form-control"
                                                                       name="formDataKeys[]"
                                                                       placeholder="Key" aria-label="Key">
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control"
                                                                       name="formDataValues[]"
                                                                       placeholder="Value" aria-label="Value">
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <button type="button"
                                                                        class="btn btn-soft-primary form-data-btn addFormData">
                                                                    <i class="bi bi-plus-circle"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @foreach(json_decode($formData) as $key => $value)
                                                            <div class="row mb-4 form-data-html">
                                                                <div class="col-sm-4 mb-2 mb-sm-0">
                                                                    <input type="text"
                                                                           class="form-control  @error('formDataKeys.'.$loop->index) is-invalid @enderror"
                                                                           name="formDataKeys[]" value="{{$key}}"
                                                                           placeholder="Key" aria-label="Key"
                                                                           autocomplete="off">
                                                                    <span class="invalid-feedback">
                                                                        @error("formDataKeys.".$loop->index) @lang($message)
                                                                        @enderror
                                                                    </span>
                                                                </div>
                                                                <div class="col-sm-7">
                                                                    <input type="text"
                                                                           class="form-control @error('formDataValues.'.$loop->index) is-invalid @enderror"
                                                                           name="formDataValues[]" value="{{$value}}"
                                                                           placeholder="Value" aria-label="Value"
                                                                           autocomplete="off">
                                                                    <span class="invalid-feedback">
                                                                        @error("formDataValues.".$loop->index) @lang($message)
                                                                        @enderror
                                                                    </span>
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    @if($loop->first)
                                                                        <button type="button"
                                                                                class="btn btn-soft-primary btn-sm form-data-btn addFormData">
                                                                            <i class="bi bi-plus-circle"></i>
                                                                        </button>
                                                                    @else
                                                                        <button type="button"
                                                                                class="btn btn-soft-danger btn-sm form-data-btn removeDiv">
                                                                            <i class="bi-trash"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
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

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            let availableTags = ["Accept", "Accept-CH", "Accept-CH-Lifetime", "Accept-Charset", "Accept-Encoding", "Accept-Language", "Accept-Patch", "Accept-Post", "Accept-Ranges", "Access-Control-Allow-Credentials", "Access-Control-Allow-Headers", "Access-Control-Allow-Methods", "Access-Control-Allow-Origin", "Access-Control-Expose-Headers", "Access-Control-Max-Age", "Access-Control-Request-Headers", "Access-Control-Request-Method", "Age", "Allow", "Alt-Svc", "Authorization", "Cache-Control", "Clear-Site-Data", "Connection", "Content-Disposition", "Content-Encoding", "Content-Language", "Content-Length", "Content-Location", "Content-Range", "Content-Security-Policy", "Content-Security-Policy-Report-Only", "Content-Type", "Cookie", "Cookie2", "Cross-Origin-Embedder-Policy", "Cross-Origin-Opener-Policy", "Cross-Origin-Resource-Policy", "DNT", "DPR", "Date", "Device-Memory", "Digest", "ETag", "Early-Data", "Expect", "Expect-CT", "Expires", "Feature-Policy", "Forwarded", "From", "Host", "If-Match", "If-Modified-Since", "If-None-Match", "If-Range", "If-Unmodified-Since", "Index", "Keep-Alive", "Large-Allocation", "Last-Modified", "Link", "Location", "NEL", "Origin", "Pragma", "Proxy-Authenticate", "Proxy-Authorization", "Public-Key-Pins", "Public-Key-Pins-Report-Only", "Range", "Referer", "Referrer-Policy", "Retry-After", "Save-Data", "Sec-Fetch-Dest", "Sec-Fetch-Mode", "Sec-Fetch-Site", "Sec-Fetch-User", "Sec-WebSocket-Accept", "Server", "Server-Timing", "Set-Cookie", "Set-Cookie2", "SourceMap", "Strict-Transport-Security", "TE", "Timing-Allow-Origin", "Tk", "Trailer", "Transfer-Encoding", "Upgrade", "Upgrade-Insecure-Requests", "User-Agent", "Vary", "Via", "WWW-Authenticate", "Want-Digest", "Warning", "X-Content-Type-Options", "X-DNS-Prefetch-Control", "X-Forwarded-For", "X-Forwarded-Host", "X-Forwarded-Proto", "X-Frame-Options", "X-XSS-Protection"];

            $(".headerDataKeys").autocomplete({
                autoFocus: true,
                source: function (request, response) {
                    var results = $.ui.autocomplete.filter(availableTags, request.term);
                    response(results.slice(0, 10));
                }
            });

            $(document).on('click', '.addHeaderData', function () {
                let paramsMarkup = $('.headers-html:eq(0)').clone();
                setMarkup(paramsMarkup, '.headers-btn', 'addHeaderData', '.headerDataWrapper');
                $(".headerDataKeys").autocomplete({
                    autoFocus: true,
                    source: function (request, response) {
                        var results = $.ui.autocomplete.filter(availableTags, request.term);
                        response(results.slice(0, 10));
                    }
                });
            });

            $(document).on('click', '.addParams', function () {
                let paramsMarkup = $('.params-html:eq(0)').clone();
                setMarkup(paramsMarkup, '.params-btn', 'addParams', '.paramsWrapper');
            });

            $(document).on('click', '.addFormData', function () {
                let formDataMarkup = $('.form-data-html:eq(0)').clone();
                setMarkup(formDataMarkup, '.form-data-btn', 'addFormData', '.formDataWrapper');
            });

            $(document).on('click', '.removeDiv', function (e) {
                e.preventDefault();
                $(this).closest('.row').remove();
            });

            function setMarkup(markup, btnName, eventName, tragetWrap) {
                markup.find('input').val('');
                markup.find(btnName).addClass('btn btn-soft-danger removeDiv').removeClass('btn-success ' + eventName);
                markup.find(btnName + ' i').addClass('bi-trash').removeClass('fa-plus');
                $(tragetWrap).append(markup);
            }
        });

    </script>
@endpush

