@extends('admin.layouts.app')
@section('page_title', __('Manage Content'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Manage Menu')</a></li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang('Edit Custom Link')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit Custom Link')</h1>
                </div>
            </div>
        </div>


        <div>
            <ul class="nav nav-segment mb-2" role="tablist">
                @foreach($languages as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link @error('errActive') @if($language->id == $message) active @endif @else @if($loop->first) active @endif  @enderror"
                           id="nav-one-eg1-tab"
                           href="#nav-one-{{ $key }}"
                           data-bs-toggle="pill"
                           data-bs-target="#nav-one-{{ $key }}"
                           role="tab" aria-controls="nav-one-{{ $key }}"
                           aria-selected="@error('errActive') @if($language->id == $message) true @else false @endif @else @if($loop->first) true @else false @endif  @enderror">
                            @lang($language->name)
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-content">
            @foreach($languages as $key => $language)
                <div
                    class="tab-pane fade @error('errActive') @if($language->id == $message) show active @endif @else @if($loop->first) show active @endif  @enderror"
                    id="nav-one-{{ $key }}"
                    role="tabpanel" aria-labelledby="nav-one-{{ $key }}-tab">
                    <div class="row justify-content-lg-center">
                        <form action="{{ route('admin.update.custom.link', [$pageId, $language->id]) }}" method="post"
                              id="form_description"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-12">
                                <div class="card card-lg mb-3 mb-lg-4">
                                    <div class="card-body">

                                        <div class="mb-4">
                                            <input type="text" class="form-control" id="editLinkText"
                                                   name="link_text[{{ $language->id }}]"
                                                   placeholder="Link Text"
                                                   value="{{ old('link_text'.'.'.$language->id, isset($customPages[$language->id]) ? @$customPages[$language->id][0]->name : '') }}"
                                                   aria-label="Link Text">
                                            @error('link_text'.'.'.$language->id)
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" class="form-control" id="editLink"
                                                   name="link[{{ $language->id }}]"
                                                   placeholder="https://"
                                                   value="{{ old('link'.'.'.$language->id, isset($customPages[$language->id]) ? @$customPages[$language->id][0]->page->custom_link : '') }}"
                                                   aria-label="https://">
                                            @error('link'.'.'.$language->id)
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="d-flex justify-content-start align-items-center mt-3">
                                            <button type="submit"
                                                    class="btn btn-primary">@lang('Save changes')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">

@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}" defer></script>
@endpush

@push('script')
    <script defer>
        'use strict';
        $(document).ready(function () {

            new HSFileAttach('.js-file-attach')
            HSCore.components.HSFlatpickr.init('.js-flatpickr')

            HSCore.components.HSDatatables.init($('#datatable'), {
                language: {
                    zeroRecords: `
                        <div class="text-center p-4">
                          <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                          <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                            <p class="mb-0">No data to show</p>
                        </div>`
                }
            });

            $('.deleteBtn').on('click', function () {
                let route = $(this).data('route')
                $('.setRoute').attr('action', route);
            });

            $('.summernote').summernote({
                height: 200,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                }
            });
        });


    </script>
@endpush






