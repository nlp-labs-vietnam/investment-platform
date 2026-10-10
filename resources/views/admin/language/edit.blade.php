@extends('admin.layouts.app')
@section('page_title', __('Edit Language'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang("Dashboard")</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Settings")</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Language Setting")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Edit " . $language->name .  " Language")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>

            <div class="col-lg-9 seo-setting">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Edit ' . $language->name .  ' Language')</h2>
                        </div>
                        <div class="card-body">
                            <!-- Form -->
                            <form action="{{ route('admin.language.update', $language->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-4">
                                    <label for="nameLabel"
                                           class="col-sm-3 col-form-label form-label">@lang("Name")</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel"
                                               placeholder="@lang("Name")"
                                               value="{{ old('name', $language->name) }}"
                                               autocomplete="off">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="shortNameLabel"
                                           class="col-sm-3 col-form-label form-label">@lang("Short Name")</label>
                                    <div class="col-sm-9">
                                        <div class="tom-select-custom">
                                            <select
                                                class="js-select form-select @error('short_name') is-invalid @enderror"
                                                id="shortNameLabel" name="short_name">
                                                @if($shortNames)
                                                    @foreach($shortNames as $key => $shortName)

                                                        <option
                                                            value="{{ $key }}" {{ old('short_name', $language->short_name) == $key ? 'selected' : '' }}>@lang(strtoupper($key)) - @lang($shortName)</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('short_name')
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
                                                                <span class="d-block text-dark">@lang("RTL")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("Enable RTL the language orientation for a more user-friendly experience.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type='hidden' value='0' name='rtl'>
                                                                    <input
                                                                        class="form-check-input @error('rtl') is-invalid @enderror"
                                                                        type="checkbox"
                                                                        name="rtl"
                                                                        id="rtlLabel"
                                                                        value="1" {{ old('rtl', $language->rtl) == 1 ? 'checked' : ''}}>
                                                                    <label class="form-check-label"
                                                                           for="rtlLabel"></label>
                                                                    @error('rtl')
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
                                                                    class="d-block text-dark">@lang("Language Status")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("Empower users to display their language preferences by enabling a language status feature.")</p>
                                                            </div>

                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type='hidden' value='0' name='status'>
                                                                    <input
                                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                                        type="checkbox"
                                                                        name="status"
                                                                        id="status"
                                                                        value="1" {{ old('rtl', $language->status) == 1 ? 'checked' : ''}}>
                                                                    <label class="form-check-label"
                                                                           for="status"></label>
                                                                    @error('status')
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
                                                                    class="d-block text-dark">@lang("Default Language")</span>
                                                                <p class="fs-5 text-body mb-0">
                                                                    @lang("Select " .$language->name. " as the default language for your preferred setting.")
                                                                </p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type='hidden' value='0' name='default_lang'>
                                                                    <input
                                                                        class="form-check-input @error('default_lang') is-invalid @enderror"
                                                                        type="checkbox"
                                                                        name="default_lang"
                                                                        id="default_lang"
                                                                        value="1" {{ old('default_lang', $language->default_status) == 1 ? 'checked' : ''}}>
                                                                    <label class="form-check-label"
                                                                           for="default_lang"></label>
                                                                    @error("Default Language")
                                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End List Item -->
                                        </div>
                                    </div>
                                </div>


                                <div class="row mb-2">
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="flagImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($language->flag_driver, $language->flag) }}"
                                                 alt="@lang("Flag")"
                                                 data-hs-theme-appearance="default">

                                            <img id="flagImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($language->flag_driver, $language->flag) }}"
                                                 alt="@lang("Flag")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="flag" id="logoUploader"
                                                   data-hs-file-attach-options='{
                                                      "textTarget": "#flagImg",
                                                      "mode": "image",
                                                      "targetAttr": "src",
                                                      "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                                   }'>
                                        </label>
                                        @error("flag")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
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
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })
            new HSFileAttach('.js-file-attach')
        })
    </script>
@endpush

