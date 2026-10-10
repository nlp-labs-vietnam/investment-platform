@extends('admin.layouts.app')
@section('page_title', __('Create Page'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item">@lang('Frontend')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("Create Page")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Create Page")</h1>
                </div>
            </div>
        </div>

        <div class="alert alert-soft-dark mb-5" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <img class="avatar avatar-xl alert_image"
                         src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="default">
                    <img class="avatar avatar-xl alert_image"
                         src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="dark">
                </div>

                <div class="flex-grow-1 ms-3">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">@lang("You are creating page for `$defaultLanguage->name` version in `".stringToTitle($theme)."`")</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route("admin.create.page.store", $theme) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Create Page")</h2>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="themes" value="{{ $theme }}">
                                <input type="hidden" name="language_id" value="{{ $defaultLanguage->id }}">
                                <div class="row mb-4">
                                    <label for="NameLabel" class="form-label">@lang("Name")</label>
                                    <div class="input-group input-group-sm-vertical">
                                        <input type="text" class="form-control change_name_input" name="name"
                                               id="NameLabel" value="{{ old("name") }}"
                                               placeholder="@lang("Name")" autocomplete="off">
                                    </div>
                                    @error("name")
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mt-4 laterShowSlug mb-2">
                                    <div class="">
                                        <label for="permalinkLabel" class="form-label">@lang("Permalink")</label>
                                        <div class="d-inline-flex">
                                            <div class="default-slug d-flex justify-content-end align-items-center">
                                                <span class="ps-3">{{ $url }}</span>
                                                <input type="text" class="form-control set-slug" name="slug"
                                                       id="newSlug" placeholder="@lang("Slug")" autocomplete="off" value="{{ old("slug") }}">
                                            </div>
                                        </div>
                                    </div>
                                    @error("slug")
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="NameLabel" class="form-label mb-0">@lang("Content")</label>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#blockModal">@lang("Insert Section")</button>
                                        </div>
                                        <textarea class="form-control" name="page_content" id="summernote" rows="20">{{ old("page_content") }}</textarea>
                                        @error("page_content")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-grid gap-3 gap-lg-5 res-order">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Publish")</h2>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-start gap-2">
                                    <button type="submit" class="btn btn-primary" name="status"
                                            value="1">@lang("Save & Publish")</button>
                                    <button type="submit" class="btn btn-info" name="status"
                                            value="0">@lang("Save & Draft")</button>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Breadcrumb Status")</h2>
                            </div>
                            <div class="card-body">
                                <label class="row form-check form-switch" for="breadcrumbSwitch">
                                    <span class="col-8 col-sm-9 ms-0">
                                      <span class="text-dark">@lang("Breadcrumb Status")
                                          <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                             data-bs-placement="top"
                                             aria-label="@lang("Enable status for page publish")"
                                             data-bs-original-title="@lang("Enable breadcrumb image this page")"></i></span>
                                    </span>
                                    <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="breadcrumb_status" value="0">
                                        <input type="checkbox" class="form-check-input" name="breadcrumb_status"
                                               id="breadcrumb" value="1">
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Breadcrumb Image")</h2>
                            </div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-8 mb-3 mb-md-0">
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset("assets/admin/img/oc-browse-file.svg") }}"
                                                 alt="@lang("Breadcrumb Image")"
                                                 data-hs-theme-appearance="default">

                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset("assets/admin/img/oc-browse-file-light.svg") }}"
                                                 alt="@lang("Breadcrumb Image")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="breadcrumb_image" id="logoUploader"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#logoImg",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg"]
                                           }'>
                                        </label>
                                        @error("breadcrumb_image")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="blockModalLabel">@lang("Select Section")</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="tom-select-custom">
                        <select class="js-select form-select" id="customBlockSelect" data-hs-tom-select-options='{
                                    "searchInDropdown": false,
                                    "hideSearch": true
                                  }'>
                            @foreach($sections as $sectionName => $sectionData)
                                <option value="[[{{ $sectionName }}]]"
                                        data-section="{{ json_encode($sectionData) }}"
                                        data-preview="{{ asset($sectionData['preview']) }}"
                                >{{ $sectionData['optionText'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <a href="" id="sectionImg" class="btn btn-info" data-fancybox="gallery">
                        <i class="fa-light fa-eye mx-1"></i>@lang('Preview')
                    </a>
                    <div>
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang("Close")</button>
                        <button id="insertCustomBlockButton" type="button" class="btn btn-primary">@lang("Insert Section")</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')

    <script>
        $(document).ready(function () {
            function updateHref() {
                let previewUrl = $('#customBlockSelect option:selected').data('preview');
                $('#sectionImg').attr('href', previewUrl || '#');
            }
            updateHref();
            $('#customBlockSelect').change(updateHref);
            $('#sectionImg').on('click', function(e) {
                if ($(this).attr('href') === '#') {
                    e.preventDefault();
                } else {
                    $.fancybox.open({
                        src: $(this).attr('href'),
                        type: 'image'
                    });
                    e.preventDefault();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select');
            new HSFileAttach('.js-file-attach')
            $(document).on('input', ".change_name_input", function (e) {
                let inputValue = $(this).val();
                let final_value = inputValue.toLowerCase().replace(/\s+/g, '-');
                $('.set-slug').val(final_value);
            });
            $('.dropdown-toggle').dropdown();
        });
    </script>
    <script src="{{ asset("assets/admin/js/hs-file.min.js") }}"></script>
@endpush








