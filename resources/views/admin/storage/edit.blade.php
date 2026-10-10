@extends('admin.layouts.app')
@section('page_title', __('File Storage'))
@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('File Storage')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('File Storage')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>
            <div class="col-lg-9">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Edit File Storage')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.storage.update',$fileStorageMethod->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="nameLabel" class="form-label">Name</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel"
                                               placeholder="Name" aria-label="Name"
                                               value="{{ old('name', $fileStorageMethod->name) }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if($fileStorageMethod->parameters)
                                        @foreach ($fileStorageMethod->parameters as $key => $parameter)
                                            <div class="col-sm-6 mb-3">
                                                <label for="{{ $key }}"
                                                       class="form-label">{{ __(stringToTitle($key)) }}</label>
                                                <input type="text"
                                                       class="form-control  @error($key) is-invalid @enderror"
                                                       name="{{ $key }}"
                                                       id="{{ $key }}"
                                                       placeholder="@lang("Parameter")" aria-label="@lang("Parameter")"
                                                       value="{{ old($key, $parameter) }}">
                                                @error($key)
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="row">
                                    <label class="form-label">@lang('Choose Logo')</label>
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($fileStorageMethod->driver,$fileStorageMethod->logo, true) }}"
                                                 alt="@lang("File Storage Logo")"
                                                 data-hs-theme-appearance="default">

                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($fileStorageMethod->driver,$fileStorageMethod->logo, true) }}"
                                                 alt="@lang("File Storage Logo")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input" name="logo" id="logoUploader"
                                                   data-hs-file-attach-options='{
                                              "textTarget": "#logoImg",
                                              "mode": "image",
                                              "targetAttr": "src",
                                              "allowTypes": [".png", ".jpeg", ".jpg"]
                                           }'>
                                        </label>
                                        @error("logo")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mt-3">
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

@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            new HSFileAttach('.js-file-attach')
        });
    </script>
@endpush





