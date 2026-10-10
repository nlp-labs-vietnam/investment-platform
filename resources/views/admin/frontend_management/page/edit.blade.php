@extends('admin.layouts.app')
@section('page_title', __('Edit Page'))
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
                                aria-current="page">@lang("Edit Page")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Edit Page")</h1>
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
                        <p class="mb-0">@lang("You are editing page for `$pageEditableLanguage->name` version in `".stringToTitle($theme)."`")</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route("admin.update.page", [$page->id, $theme]) }}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Edit Page")</h2>
                            </div>
                            <div class="card-body">

                                <input type="hidden" name="language_id" value="{{ $pageEditableLanguage->id }}">
                                <div class="row mb-4">
                                    <div class="col-md-12 mb-5">
                                        <label for="NameLabel" class="form-label">@lang("Name")</label>
                                        <div class="input-group input-group-sm-vertical">
                                            <input type="text" class="form-control change_name_input" name="name"
                                                   id="NameLabel"
                                                   value="{{ old("name", optional($page->details)->name) }}"
                                                   placeholder="@lang("Name")" autocomplete="off">
                                        </div>
                                        @error("name")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-md-12  ">
                                        <div class="firstShowSlug">

                                            <label for="permalinkLabel" class="form-label">@lang("Permalink:")</label>
                                            <div class="d-inline-block">
                                            <span class="default-slug">{{ url('/') }}/
                                                <span id="editable-post-name">{{ $page->slug }}</span>
                                            </span>
                                                <span id="edit-slug-buttons">
                                               <button class="btn btn-white btn-sm ms-1" id="change_slug" type="button">Edit</button>
                                           </span>
                                            </div>
                                            @error("slug")
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror

                                        </div>

                                        <div class="laterShowSlug d-none">
                                            <label for="permalinkLabel" class="form-label">@lang("Permalink")</label>
                                            <div class="d-inline-flex">
                                                <div class="default-slug d-flex justify-content-end align-items-center">
                                                    <span class="ps-3">{{ url('/') }}</span>
                                                    <input type="text" class="form-control" name="slug" id="newSlug"
                                                           value="{{ $page->slug }}" placeholder="@lang("Slug")">
                                                </div>
                                                <button class="save btn btn-white btn-sm ms-1" id="btn-ok"
                                                        type="button">
                                                    @lang("OK")
                                                </button>
                                                <button class="cancel btn btn-white btn-sm ms-1" id="btn-cancel"
                                                        type="button">
                                                    @lang("Cancel")
                                                </button>
                                            </div>
                                        </div>
                                        @error("slug")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                        <span class="newSlug"></span>
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="NameLabel" class="form-label mb-0">@lang("Content")</label>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#blockModal">@lang("Insert Section")</button>
                                        </div>
                                        <textarea class="form-control" name="page_content" id="summernote" rows="20">{!! optional($page->details)->content !!}</textarea>
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

                        <div class="card language_card">
                            <div class="card-header">
                                <h4 class="card-title">@lang("Language")</h4>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush list-group-no-gutters">
                                    @foreach($allLanguage as $language)
                                        @if($pageEditableLanguage->name !==  $language->name)
                                            <div class="list-group-item custom-list">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img class="avatar avatar-xss avatar-square me-2"
                                                             src="{{ getFile($language->flag_driver, $language->flag) }}"
                                                             alt="{{ ucwords($language->name) }} Flag">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col-sm mb-2 mb-sm-0">
                                                                <h5 class="mb-0">@lang($language->name)</h5>
                                                            </div>
                                                            <div class="col-sm-auto">
                                                                <a class="text-secondary"
                                                                   href="{{ route('admin.edit.page', [$page->id, $theme, $language->id]) }}"><i
                                                                        class="bi bi-pencil-square"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Breadcrumb")</h2>
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
                                               id="breadcrumbSwitch" value="1" {{ old("breadcrumb_status", $page->breadcrumb_status == 1 ? 'checked' : '') }}>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Breadcrumb Background")</h2>
                            </div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-8 mb-3 mb-md-0">
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($page->breadcrumb_image_driver, $page->breadcrumb_image) }}"
                                                 alt="@lang("Breadcrumb Image")"
                                                 data-hs-theme-appearance="default">

                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($page->breadcrumb_image_driver, $page->breadcrumb_image) }}"
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
            new HSFileAttach('.js-file-attach')
            var slug = "{{ $page->slug }}";
            $(document).on("click", "#change_slug", function () {
                $('#newSlug').val(slug)
                $('.firstShowSlug').addClass('d-none');
                $('.laterShowSlug').removeClass('d-none');
            });
            $(document).on("click", "#btn-ok", function () {
                let newSlug = $('#newSlug').val();
                $('#editable-post-name').text(newSlug);
                $('.laterShowSlug').addClass('d-none');
                $('.firstShowSlug').removeClass('d-none');

                let pageId = "{{ $page->id }}";


                $.ajax({
                    url: "{{ route('admin.update.slug') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        pageId,
                        newSlug
                    },
                    success: function (response) {
                        if (response.errors) {
                            for (let item in response.errors) {
                                $('.newSlug').removeClass('text-success');
                                $('.newSlug').addClass('text-danger');
                                $('.newSlug').text(response.errors[item][0])
                            }
                            setTimeout(function () {
                                $('.newSlug').text('')
                            }, 3000)
                            return 0;
                        }
                        $('.newSlug').text('')
                        slug = response.slug
                    },
                    error: function (error) {
                    }
                });
            });
            $(document).on("click", "#btn-cancel", function () {
                $('.laterShowSlug').addClass('d-none');
                $('.firstShowSlug').removeClass('d-none');
            });
            $(document).on('input', ".change_name_input", function (e) {
                let inputValue = $(this).val();
                let final_value = inputValue.toLowerCase().replace(/\s+/g, '-');
                $('.set-link').val(final_value);
            });
            HSCore.components.HSTomSelect.init('.js-select');

            $('.dropdown-toggle').dropdown();
        });
    </script>

    <script src="{{ asset("assets/admin/js/hs-file.min.js") }}"></script>
@endpush








