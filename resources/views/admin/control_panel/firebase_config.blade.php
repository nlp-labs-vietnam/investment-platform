@extends('admin.layouts.app')
@section('page_title', __('Push Notification Setting'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{ route('admin.dashboard') }}">@lang("Dashboard")</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Settings")</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("Push Notification Setting")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Push Notification Setting")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.push-notification'), 'suffix' => ''])
            </div>
            <div class="col-lg-6 seo-setting">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Firebase Configuration')</h2>
                        </div>
                        <div class="card-body">
                            <!-- Form -->
                            <form action="{{ route('admin.firebase.config.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label for="appKeyLabel" class="form-label">@lang("Api Key")</label>
                                        <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                            <input
                                                type="password"
                                                class="js-toggle-password form-control @error('api_key') is-invalid @enderror"
                                                name="api_key"
                                                id="appKeyLabel"
                                                autocomplete="off"
                                                placeholder="@lang("Api Key")"
                                                aria-label="@lang("Api Key")"
                                                value="{{ old('api_key', $firebaseNotify['apiKey']) }}"
                                                data-hs-toggle-password-options='{
                                                    "target": "#api_key",
                                                    "defaultClass": "bi-eye-slash",
                                                    "showClass": "bi-eye",
                                                    "classChangeTarget": "#apiKeyIcon"
                                                    }'/>
                                            <button type="button" id="api_key"
                                                    class="input-group-append input-group-text">
                                                <i id="apiKeyIcon" class="bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('api_key')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="vapidKeyLabel" class="form-label">@lang('Vapid Key')</label>
                                        <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                            <input
                                                type="password"
                                                class="js-toggle-password form-control @error('vapid_key') is-invalid @enderror"
                                                name="vapid_key"
                                                id="vapidKeyLabel"
                                                autocomplete="off"
                                                placeholder="@lang("Vapid Key")"
                                                aria-label="@lang("Vapid Key")"
                                                value="{{ old('vapid_key', $firebaseNotify['vapidKey']) }}"
                                                data-hs-toggle-password-options='{
                                                    "target": "#vapid_key",
                                                    "defaultClass": "bi-eye-slash",
                                                    "showClass": "bi-eye",
                                                    "classChangeTarget": "#vapidKeyIcon"
                                                    }'/>
                                            <button type="button" id="vapid_key"
                                                    class="input-group-append input-group-text">
                                                <i id="vapidKeyIcon" class="bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('vapid_key')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label for="authDomainLabel" class="form-label">@lang("Auth Domain")</label>
                                        <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                            <input
                                                type="password"
                                                class="js-toggle-password form-control @error('auth_domain') is-invalid @enderror"
                                                name="auth_domain"
                                                id="authDomainLabel"
                                                autocomplete="off"
                                                placeholder="@lang("Auth Domain")"
                                                aria-label="@lang("Auth Domain")"
                                                value="{{ old('auth_domain', $firebaseNotify['authDomain']) }}"
                                                data-hs-toggle-password-options='{
                                                    "target": "#auth_domain",
                                                    "defaultClass": "bi-eye-slash",
                                                    "showClass": "bi-eye",
                                                    "classChangeTarget": "#authDomainIcon"
                                                    }'/>
                                            <button type="button" id="auth_domain"
                                                    class="input-group-append input-group-text">
                                                <i id="authDomainIcon" class="bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('auth_domain')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="projectIdLabel"
                                               class="form-label">@lang('Project Id')</label>
                                        <input type="text"
                                               class="form-control  @error('project_id') is-invalid @enderror"
                                               name="project_id" id="projectIdLabel"
                                               placeholder="Project Id" aria-label="Project Id"
                                               value="{{ old('project_id', $firebaseNotify['projectId']) }}">
                                        @error('project_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label for="storageBucketLabel"
                                               class="form-label">@lang('Storage Bucket')</label>
                                        <input type="text"
                                               class="form-control  @error('storage_bucket') is-invalid @enderror"
                                               name="storage_bucket" id="storageBucketLabel"
                                               placeholder="Storage Bucket" aria-label="Storage Bucket"
                                               value="{{ old('storage_bucket', $firebaseNotify['storageBucket']) }}">
                                        @error('storage_bucket')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="messagingSenderIdLabel"
                                               class="form-label">@lang('Messaging Sender Id')</label>
                                        <input type="text"
                                               class="form-control  @error('messaging_sender_id') is-invalid @enderror"
                                               name="messaging_sender_id" id="messagingSenderIdLabel"
                                               placeholder="Messaging Sender Id" aria-label="Messaging Sender Id"
                                               value="{{ old('messaging_sender_id', $firebaseNotify['messagingSenderId']) }}">
                                        @error('messaging_sender_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label for="appIdLabel"
                                               class="form-label">@lang('App Id')</label>
                                        <input type="text"
                                               class="form-control  @error('app_id') is-invalid @enderror"
                                               name="app_id" id="appIdLabel"
                                               placeholder="App Id" aria-label="App Id"
                                               value="{{ old('app_id', $firebaseNotify['appId']) }}">
                                        @error('app_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="MeasurementId"
                                               class="form-label">@lang('Measurement Id')</label>
                                        <input type="text"
                                               class="form-control  @error('measurement_id') is-invalid @enderror"
                                               name="measurement_id" id="MeasurementId"
                                               placeholder="Measurement Id" aria-label="Measurement Id"
                                               value="{{ old('measurement_id', $firebaseNotify['measurementId']) }}">
                                        @error('measurement_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
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
                                                                    class="d-block text-dark">@lang("Push Notification")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("Push notifications are messages that pop up while a user is using your app.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" value="0"
                                                                           name="push_notification"/>
                                                                    <input
                                                                        class="form-check-input @error('push_notification') is-invalid @enderror"
                                                                        type="checkbox" name="push_notification"
                                                                        id="push_notification" value="1"
                                                                        {{ $basicControl->push_notification == 1 ? 'checked' : '' }} >
                                                                    <label class="form-check-label"
                                                                           for="push_notification"></label>
                                                                    @error('push_notification')
                                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <span
                                                                    class="d-block text-dark">@lang("User Foreground")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("Push notifications are sent when the user is online.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" value="0"
                                                                           name="user_foreground"/>
                                                                    <input
                                                                        class="form-check-input @error('user_foreground') is-invalid @enderror"
                                                                        type="checkbox" name="user_foreground"
                                                                        id="user_foreground" value="1"
                                                                        {{ $firebaseNotify['user_foreground'] == 1 ? 'checked' : '' }} >
                                                                    <label class="form-check-label"
                                                                           for="user_foreground"></label>
                                                                    @error('user_foreground')
                                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <span
                                                                    class="d-block text-dark">@lang("User Background")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("Push notifications are sent when the user is offline.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" value="0"
                                                                           name="user_background"/>
                                                                    <input
                                                                        class="form-check-input @error('user_background') is-invalid @enderror"
                                                                        type="checkbox" name="user_background"
                                                                        id="user_background" value="1"
                                                                        {{ $firebaseNotify['user_background'] == 1 ? 'checked' : '' }} >
                                                                    <label class="form-check-label"
                                                                           for="user_background"></label>
                                                                    @error('user_background')
                                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <span
                                                                    class="d-block text-dark">@lang("Admin Foreground")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("Push notifications are sent when the admin is online.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" value="0"
                                                                           name="admin_foreground"/>
                                                                    <input
                                                                        class="form-check-input @error('admin_foreground') is-invalid @enderror"
                                                                        type="checkbox" name="admin_foreground"
                                                                        id="admin_foreground" value="1"
                                                                        {{ $firebaseNotify['admin_foreground'] == 1 ? 'checked' : '' }} >
                                                                    <label class="form-check-label"
                                                                           for="admin_foreground"></label>
                                                                    @error('admin_foreground')
                                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <span
                                                                    class="d-block text-dark">@lang("Admin Background")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("Push notifications are sent when the admin is offline.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" value="0"
                                                                           name="admin_background"/>
                                                                    <input
                                                                        class="form-check-input @error('admin_background') is-invalid @enderror"
                                                                        type="checkbox" name="admin_background"
                                                                        id="admin_background" value="1"
                                                                        {{ $firebaseNotify['admin_background'] == 1 ? 'checked' : '' }} >
                                                                    <label class="form-check-label"
                                                                           for="admin_background"></label>
                                                                    @error('admin_background')
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
            <div class="col-lg-3">
                <div id="emailSection" class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h2 class="card-title h4 mt-2">@lang("Service File") <i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="You can find your code in a postal address."
                                data-bs-original-title="@lang('Upload Firebase Service Configuration Json File')"></i>
                        </h2>
                        <a class="text-body" href="{{route('admin.firebase.config.file.download')}}">
                            <i class="bi-download me-1"></i> @lang('Download')</a>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.firebase.config.file.upload')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <label for="basicFormFile" class="js-file-attach form-label"
                                   data-hs-file-attach-options='{
                              "textTarget": "[for=\"customFile\"]"
                             }'>@lang('File input')</label>
                            <input class="form-control" type="file" name="file" id="basicFormFile">
                            @error('file')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-sm btn-primary">@lang('Upload')</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="emailSection" class="card my-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h2 class="card-title h4 mt-2">@lang("Instruction")</h2>
                    </div>
                    <div class="card-body">
                        @lang("Push notification provides realtime communication between servers, apps and devices.
                        When something happens in your system, it can update web-pages, apps and devices.
                        When an event happens on an app, the app can notify all other apps and your system
                        <br><br>
                        Get your free API keys")
                        <a href="https://console.firebase.google.com/"
                           target="_blank">@lang('Create an account') <i class="fas fa-external-link-alt"></i>
                        </a>
                        @lang(', then create a Firebase Project, then create a web app in created Project
                               Go to web app configuration details to get Vapid key, Api key, Auth domain, Project id, Storage bucket, Messaging sender id, App id, Measurement id.')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-toggle-password.js") }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            new HSTogglePassword('.js-toggle-password')
            new HSFileAttach('.js-file-attach')
        });
    </script>
@endpush

