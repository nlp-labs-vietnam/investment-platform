@extends('admin.layouts.app')
@section('page_title', __('Create Blog'))
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
                                aria-current="page">@lang("Create Blog")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Create Blog")</h1>
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
                        <p class="mb-0">@lang("You are creating blog for `$defaultLanguage->name` version.")</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route("admin.blogs.store") }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Create Blog")</h2>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="language_id" value="{{ $defaultLanguage->id }}">
                                <div class="row mb-4">

                                    <div class="col-md-6">
                                        <label for="NameLabel" class="form-label">@lang("Title")</label>
                                        <div class="input-group input-group-sm-vertical">
                                            <input type="text" class="form-control change_name_input" name="title"
                                                   id="NameLabel" value="{{ old("title") }}"
                                                   placeholder="@lang("title")" autocomplete="off">
                                        </div>
                                        @error("title")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-md-6">
                                        <label for="NameLabel" class="form-label">@lang("Category")</label>
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" autocomplete="off" name="category_id"
                                                    data-hs-tom-select-options='{
                                                      "placeholder": "Select a category",
                                                      "hideSearch": true
                                                    }'>
                                                <option value="">Select a category</option>
                                                @foreach($blogCategory as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- End Select -->
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4 laterShowSlug mb-2">
                                    <div class="">
                                        <label for="permalinkLabel" class="form-label">@lang("Permalink")</label>
                                        <div class="d-inline-flex">
                                            <div class="default-slug d-flex justify-content-end align-items-center">
                                                <span class="ps-3">{{ url('/') }}</span>
                                                <input type="text" class="form-control set-slug" name="slug"
                                                       id="newSlug" placeholder="@lang("Slug")" autocomplete="off"
                                                       value="{{ old("slug") }}">
                                            </div>
                                        </div>
                                    </div>
                                    @error("slug")
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="" class="form-label">@lang("Description")</label>
                                        <textarea class="form-control" name="description" id="summernote"
                                                  rows="20">{{ old("description") }}</textarea>
                                        @error("description")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label for="" class="form-label">@lang("Description Image")</label>
                                        <label class="form-check form-check-dashed" for="ImageUploader">
                                            <img id="BlogImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset("assets/admin/img/oc-browse-file.svg") }}"
                                                 alt="@lang("Breadcrumb Image")"
                                                 data-hs-theme-appearance="default">

                                            <img id="BlogImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset("assets/admin/img/oc-browse-file-light.svg") }}"
                                                 alt="@lang("Breadcrumb Image")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="description_image" id="ImageUploader"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#BlogImg",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
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
                                <h2 class="card-title h4">@lang("Status")</h2>
                            </div>
                            <div class="card-body">
                                <label class="row form-check form-switch" for="breadcrumbSwitch">
                                    <span class="col-8 col-sm-9 ms-0">
                                      <span class="text-dark">@lang("Status")
                                          <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                             data-bs-placement="top"
                                             aria-label="@lang("Enable status for page publish")"
                                             data-bs-original-title="@lang("Enable blog status")"></i>
                                      </span>
                                    </span>

                                    <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" class="form-check-input" name="status"
                                               id="status" value="1" checked>
                                    </span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
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
            HSCore.components.HSTomSelect.init('.js-select');
            new HSFileAttach('.js-file-attach')
            $(document).on('input', ".change_name_input", function (e) {
                let inputValue = $(this).val();
                let final_value = inputValue.toLowerCase().replace(/\s+/g, '-');
                $('.set-slug').val(final_value);
            });

            $('#summernote').summernote({
                placeholder: 'Describe how to make a manual payment.',
                height: 160,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                },
            });
        });
    </script>
@endpush








