@extends('admin.layouts.app')
@section('page_title', __('In App Notification'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.dashboard')  }}">@lang("Dashboard")</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Settings")</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("In App Notification Setting")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("In App Notification Setting")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.in-app-notification'), 'suffix' => ''])
            </div>

            <div class="col-lg-6 seo-setting">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang("Pusher Configuration")</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.pusher.config.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <label for="pusherAppIdLabel" class="form-label">@lang('Pusher App Id')</label>
                                        <input
                                            type="text"
                                            class="form-control @error('pusher_app_id') is-invalid @enderror"
                                            name="pusher_app_id"
                                            id="pusherAppIdLabel"
                                            autocomplete="off"
                                            placeholder="Pusher App Id"
                                            aria-label="Pusher App Id"
                                            value="{{ old('pusher_app_id', $pusherAppId) }}"
                                        />
                                        @error('pusher_app_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="pusherAppKeyLabel" class="form-label">@lang("Pusher App Key")</label>
                                        <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                            <input
                                                type="password"
                                                class="js-toggle-password form-control @error('pusher_app_key') is-invalid @enderror"
                                                name="pusher_app_key"
                                                id="pusherAppKeyLabel"
                                                autocomplete="off"
                                                placeholder="Pusher App Key"
                                                aria-label="Pusher App Key"
                                                value="{{ old('pusher_app_key', $pusherAppKey) }}"
                                                data-hs-toggle-password-options='{
                                                    "target": "#pusherAppKey",
                                                    "defaultClass": "bi-eye-slash",
                                                    "showClass": "bi-eye",
                                                    "classChangeTarget": "#pusherAppKeyIcon"
                                                    }'/>
                                            <button type="button" id="pusherAppKey" class="input-group-append input-group-text">
                                                <i id="pusherAppKeyIcon" class="bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('pusher_app_key')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <label for="pusherAppSecretLabel" class="form-label">@lang('Pusher App Secret')</label>
                                        <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                            <input
                                                type="password"
                                                class="js-toggle-password form-control @error('pusher_app_secret') is-invalid @enderror"
                                                name="pusher_app_secret"
                                                id="pusherAppSecretLabel"
                                                autocomplete="off"
                                                placeholder="Pusher App Secret"
                                                aria-label="Pusher App Secret"
                                                value="{{ old('pusher_app_secret', $pusherAppSecret) }}"
                                                data-hs-toggle-password-options='{
                                                    "target": "#pusherAppSecret",
                                                    "defaultClass": "bi-eye-slash",
                                                    "showClass": "bi-eye",
                                                    "classChangeTarget": "#pusherAppSecretIcon"
                                                    }'/>
                                            <button type="button" id="pusherAppSecret" class="input-group-append input-group-text">
                                                <i id="pusherAppSecretIcon" class="bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('pusher_app_secret')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="pusherAppClusterLabel" class="form-label">@lang('Pusher App Cluster')</label>
                                        <input
                                            type="text"
                                            class="form-control @error('pusher_app_cluster') is-invalid @enderror"
                                            name="pusher_app_cluster"
                                            id="pusherAppClusterLabel"
                                            placeholder="@lang("Pusher App Cluster")"
                                            aria-label="@lang("Pusher App Cluster")"
                                            autocomplete="off"
                                            value="{{ old('pusher_app_cluster', $pusherAppCluster) }}"/>
                                        @error('pusher_app_cluster')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <label class="row form-check form-switch mb-4" for="in_app_notification">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="d-block text-dark">@lang("In App Notification")</span>
                                          <span
                                              class="d-block fs-5">
                                               @lang("In-app notifications appear within the app interface.")
                                          </span>
                                        </span>
                                    <span class="col-4 col-sm-3 text-end">
                                           <input type="hidden" value="0" name="in_app_notification" />
                                            <input class="form-check-input @error('in_app_notification') is-invalid @enderror" type="checkbox" name="in_app_notification" id="in_app_notification" value="1"
                                                {{ $basicControl->in_app_notification == 1 ? 'checked' : '' }} >
                                        </span>
                                    @error('in_app_notification')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </label>


                                <div class="d-flex justify-content-start">
                                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div id="emailSection" class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h2 class="card-title h4 mt-2">@lang("Instruction")</h2>
                    </div>
                    <div class="card-body">
                        @lang("Pusher Channels provides realtime communication between servers, apps and devices. When something happens in your system, it can update web-pages, apps and devices. When an event happens on an app, the app can
                        notify all other apps and your system
                        <br />
                        <br />
                        Get your free API keys")
                        <a href="https://dashboard.pusher.com/accounts/sign_up" target="_blank">@lang('Create an account') <i class="fas fa-external-link-alt"></i></a>
                        @lang(", then create a Channels app. Go to the 'Keys' page for that app, and make a note of your app_id, key, secret and cluster.")
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-toggle-password.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            new HSTogglePassword('.js-toggle-password')
        });
    </script>
@endpush


