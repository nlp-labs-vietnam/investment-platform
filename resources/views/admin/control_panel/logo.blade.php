@extends('admin.layouts.app')
@section('page_title',__('Logo Setting'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Logo Settings')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Logo Settings')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>

            <div class="col-lg-7">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Logo, Favicon & Breadcrumb Settings')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.logo.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="col-form-label">@lang('Website Logo')</label>
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 object-fit-contain"
                                                 src="{{ getFile($basicControl->logo_driver, $basicControl->logo, true) }}"
                                                 alt="@lang("Logo")"
                                                 data-hs-theme-appearance="default">

                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 object-fit-contain"
                                                 src="{{ getFile($basicControl->logo_driver, $basicControl->logo, true) }}"
                                                 alt="@lang("Logo")" data-hs-theme-appearance="dark">
                                            <span class="d-block mb-3">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach-logo form-check-input"
                                                   name="logo" id="logoUploader"
                                                   data-hs-file-attach-options='{
                                                      "textTarget": "#logoImg",
                                                      "mode": "image",
                                                      "targetAttr": "src",
                                                      "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                                   }'>
                                        </label>
                                        @error("logo")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label">@lang('Favicon')</label>
                                        <label class="form-check form-check-dashed" for="faviconUploader">
                                            <img id="faviconImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 object-fit-contain"
                                                 src="{{ getFile($basicControl->favicon_driver, $basicControl->favicon, true) }}"
                                                 alt="@lang("Favicon")"
                                                 data-hs-theme-appearance="default">

                                            <img id="faviconImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 object-fit-contain"
                                                 src="{{ getFile($basicControl->favicon_driver, $basicControl->favicon, true) }}"
                                                 alt="@lang("Favicon")" data-hs-theme-appearance="dark">
                                            <span class="d-block mb-3">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach-favicon form-check-input"
                                                   name="favicon" id="faviconUploader"
                                                   data-hs-file-attach-options='{
                                                      "textTarget": "#faviconImg",
                                                      "mode": "image",
                                                      "targetAttr": "src",
                                                      "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                                   }'>
                                        </label>
                                        @error("favicon")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">@lang('Admin Default Logo')</label>
                                        <label class="form-check form-check-dashed" for="adminLogoUploader">
                                            <img id="adminLogoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 object-fit-contain"
                                                 src="{{ getFile($basicControl->admin_logo_driver, $basicControl->admin_logo, true) }}"
                                                 alt="@lang("Admin Logo")"
                                                 data-hs-theme-appearance="default">

                                            <img id="adminLogoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 object-fit-contain"
                                                 src="{{ getFile($basicControl->admin_logo_driver, $basicControl->admin_logo, true) }}"
                                                 alt="@lang("Admin Logo")" data-hs-theme-appearance="dark">
                                            <span class="d-block mb-3">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach-admin-logo form-check-input"
                                                   name="admin_logo" id="adminLogoUploader"
                                                   data-hs-file-attach-options='{
                                                      "textTarget": "#adminLogoImg",
                                                      "mode": "image",
                                                      "targetAttr": "src",
                                                      "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                                   }'>
                                        </label>
                                        @error("admin_logo")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="col-form-label">@lang('Admin Logo Dark Mode')</label>
                                        <label class="form-check form-check-dashed adminDarkLogoUploader" for="adminDarkLogoUploader">
                                            <img id="adminDarkVersionLogo"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 object-fit-contain"
                                                 src="{{ getFile($basicControl->admin_dark_mode_logo_driver, $basicControl->admin_dark_mode_logo, true) }}"
                                                 alt="@lang("Admin Dark Version Logo")"
                                                 data-hs-theme-appearance="default">

                                            <img id="adminDarkVersionLogo"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 object-fit-contain"
                                                 src="{{ getFile($basicControl->admin_dark_mode_logo_driver, $basicControl->admin_dark_mode_logo, true) }}"
                                                 alt="@lang("Admin Dark Version Logo")" data-hs-theme-appearance="dark">
                                            <span class="d-block mb-3">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach-admin-dark-logo form-check-input"
                                                   name="admin_dark_mode_logo" id="adminDarkLogoUploader"
                                                   data-hs-file-attach-options='{
                                                      "textTarget": "#adminDarkVersionLogo",
                                                      "mode": "image",
                                                      "targetAttr": "src",
                                                      "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                                   }'>
                                        </label>
                                        @error("admin_dark_mode_logo")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mt-lg-5">
                                    <button type="submit" class="btn btn-primary">@lang("Save changes")</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection


@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {

            new HSFileAttach('.js-file-attach-logo', {
                textTarget: "#logoImg"
            });
            new HSFileAttach('.js-file-attach-favicon', {
                textTarget: "#faviconImg"
            });
            new HSFileAttach('.js-file-attach-admin-logo', {
                textTarget: "#adminLogoImg"
            });

            new HSFileAttach('.js-file-attach-admin-dark-logo', {
                textTarget: "#adminDarkVersionLogo"
            });

        })
    </script>
@endpush


