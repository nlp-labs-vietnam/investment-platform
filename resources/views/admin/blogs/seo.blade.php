@extends('admin.layouts.app')
@section('page_title', __('Blog SEO'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Frontend')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Blog SEO')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Blog SEO')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Edit SEO Meta')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.blog.seo.update', $blog->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-12 mb-3">
                                        <label for="PageTitleLabel" class="form-label">@lang('Page Title')</label>
                                        <input type="text"
                                               class="form-control @error('page_title') is-invalid @enderror"
                                               name="page_title" id="PageTitleLabel"
                                               placeholder="@lang("Page Title")" aria-label="@lang("Page Title")"
                                               value="{{ old('page_title', $blog->page_title) }}" autocomplete="off">
                                        @error('page_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 mb-3">
                                        <label for="SeoTitleLabel" class="form-label">@lang('Meta Title')</label>
                                        <input type="text"
                                               class="form-control @error('meta_title') is-invalid @enderror"
                                               name="meta_title" id="SeoTitleLabel"
                                               placeholder="@lang("Meta Title")" aria-label="@lang("Meta Title")"
                                               value="{{ old('meta_title', $blog->meta_title) }}">
                                        @error('meta_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 mb-3">
                                        <label for="metaKeywordLabel" class="form-label">@lang('Meta Keywords')</label>
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="meta_keywords[]"
                                                    autocomplete="off" multiple
                                                    data-hs-tom-select-options='{
                                                        "create": true,
                                                        "placeholder": "Meta Keywords"
                                                    }'>
                                                @if($blog->meta_keywords)
                                                    @foreach($blog->meta_keywords as $key => $data)
                                                        <option value="@lang($data)" selected>@lang($data)</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error("meta_keywords")
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12 mb-4">
                                        <label for="metaDescription"
                                               class="form-label">@lang('Meta Description')</label>
                                        <textarea id="metaDescription" class="form-control" name="meta_description" placeholder="@lang("Meta Description")" rows="5">{{ old("meta_description", $blog->meta_description) }}</textarea>
                                        @error('meta_description')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-5 mb-3 mb-md-0">
                                        <label class="form-check form-check-dashed" for="imageUploader">
                                            <img id="SeoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($blog->meta_image_driver, $blog->meta_image, true) }}"
                                                 alt="Image Description" data-hs-theme-appearance="default">
                                            <img id="SeoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($blog->meta_image_driver, $blog->meta_image, true) }}"
                                                 alt="Image Description" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   id="imageUploader" name="meta_image"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#SeoImg",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg", ".json"]
                                               }'>
                                        </label>
                                        @error('seo_meta_image')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
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
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSTomSelect.init('.js-select')
        })
    </script>
@endpush
