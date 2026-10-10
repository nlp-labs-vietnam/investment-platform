@extends('admin.layouts.app')
@section('page_title', __('Facebook Control'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Socialite Controls')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Socialite Controls')</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])            </div>
            <div class="col-lg-7">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card h-100">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">@lang('Facebook Control')</h4>
                        </div>
                        <!-- Body -->
                        <div class="card-body">
                            <form action="{{ route('admin.facebook.control') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <label for="facebook_client_id"
                                           class="col-sm-3 col-form-label form-label">@lang("Client ID")</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               class="form-control @error('facebook_client_id') is-invalid @enderror"
                                               name="facebook_client_id" id="facebook_client_id"
                                               placeholder="@lang("Client ID")"
                                               value="{{ old('facebook_client_id', env('FACEBOOK_CLIENT_ID')) }}"
                                               autocomplete="off">
                                        @error('facebook_client_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="facebook_client_secret"
                                           class="col-sm-3 col-form-label form-label">@lang("Client Secret")</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               class="form-control @error('facebook_client_secret') is-invalid @enderror"
                                               name="facebook_client_secret" id="facebook_client_secret"
                                               placeholder="@lang("Client Secret")"
                                               value="{{ old('facebook_client_secret', env('FACEBOOK_CLIENT_SECRET')) }}"
                                               autocomplete="off">
                                        @error('facebook_client_secret')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="google_client_secret"
                                           class="col-sm-3 col-form-label form-label">@lang("Redirect Url")</label>
                                    <div class="col-sm-9">
                                        <div class="input-group mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   id="webhook"
                                                   value="{{ route('socialiteCallback','facebook') }}"
                                                   autocomplete="off" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" onclick="webhookCopy()"
                                                        type="button">@lang('copy')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <label class="row form-check form-switch mb-4" for="fb_messenger_status">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="d-block text-dark">@lang("Status")</span>
                                          <span
                                              class="d-block fs-5">@lang("Enable status to allow user login using google.")</span>
                                        </span>
                                    <span class="col-4 col-sm-3 text-end">
                                        <input type='hidden' value='0' name='facebook_status'>
                                          <input type="checkbox" name="facebook_status" id="facebook_status"
                                                 value="1"
                                                 {{ config('socialite.facebook_status') == 1 ? 'checked' : ''}} class="form-check-input">
                                        </span>
                                    @error('facebook_status')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </label>

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
@push('script')
    <script>
        'use strict'

        function webhookCopy() {
            var copyText = document.getElementById("webhook");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            Notiflix.Notify.success(`${copyText.value} Copied`);
        }
    </script>
    @if ($errors->any())
        @php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        @endphp
        <script>
            "use strict";
            @foreach ($errors as $error)
            Notiflix.Notify.failure("{{trans($error)}}");
            @endforeach
        </script>
    @endif
@endpush
