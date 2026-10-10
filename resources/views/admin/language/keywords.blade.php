@extends('admin.layouts.app')
@section('page_title', __("$pageTitle"))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Language Setting')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang($language->name. ' Keywords')</h1>
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
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="card-title h3 mt-2">@lang($language->name. ' Keywords')</h4>
                            <div class="dropdown">
                                <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle"
                                        id="reportsOverviewDropdown1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end mt-1"
                                     aria-labelledby="reportsOverviewDropdown1">
                                    <span class="dropdown-header">@lang("Settings")</span>
                                    <button type="button" class="dropdown-item" data-bs-target="#addModal"
                                            data-bs-toggle="modal">
                                        <i class="bi bi-file-earmark-plus dropdown-item-icon"></i>@lang("Add Keyword")
                                    </button>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#importModal">
                                        <i class="bi-download dropdown-item-icon"></i> @lang("Import Now")
                                    </button>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#automatic_translate_modal">
                                        <i class="bi-alt dropdown-item-icon"></i> @lang("Automatic Translate Keyword")
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2 mb-md-0">
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend input-group-text">
                                </div>
                                <input id="datatableSearch" type="search" class="form-control"
                                       placeholder="Search Keywords" aria-label="Search Keywords" autocomplete="off">
                            </div>
                        </div>

                        <div class="table-responsive datatable-custom">
                            <table
                                class="js-datatable table table-borderless table-nowrap table-align-middle card-table"
                                data-hs-datatables-options='{
                                               "order": [],
                                               "info": {
                                                 "totalQty": "#datatableEntriesInfoTotalQty"
                                               },
                                               "ordering": false,
                                               "search": "#datatableSearch",
                                               "entries": "#datatableEntries",
                                               "isResponsive": false,
                                               "isShowPaging": false,
                                               "pagination": "datatableEntriesPagination"
                                             }'>
                                <thead class="thead-light">
                                <tr>
                                    <th>@lang('Sl.')</th>
                                    <th>@lang('Key')</th>
                                    <th>{{ __($language->name) }}</th>
                                    <th>@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($keywords as $key => $keyword)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $key }}</td>
                                        <td>{{ $keyword }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a class="btn btn-white btn-sm edit-keyword-btn"
                                                   data-key="{{ $key }}"
                                                   data-value="{{ $keyword }}"
                                                   data-route="{{ route('admin.update.language.keyword',[$language->short_name, urlencode($key)]) }}"
                                                   data-bs-toggle="modal" data-bs-target="#editKeywordModal">
                                                    <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                                </a>
                                                <div class="btn-group">
                                                    <button type="button"
                                                            class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                            id="keywordEditDropdown" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end mt-1"
                                                         aria-labelledby="keywordEditDropdown">
                                                        <a class="dropdown-item deleteKey" href="javascript:void(0)"
                                                           data-bs-toggle="modal"
                                                           data-keyword="{{ $keyword }}"
                                                           data-bs-target="#deleteModal">
                                                            <i class="bi-trash dropdown-item-icon"></i> @lang('Delete')
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <div class="text-center p-4">
                                            <img class="dataTables-image mb-3"
                                                 src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                                 alt="Image Description" data-hs-theme-appearance="default">
                                            <img class="dataTables-image mb-3"
                                                 src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                                 alt="Image Description" data-hs-theme-appearance="dark">
                                            <p class="mb-0">@lang("No data to show")</p>
                                        </div>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                                <div class="col-sm mb-2 mb-sm-0">
                                    <div
                                        class="d-flex justify-content-center justify-content-sm-start align-items-center">
                                        <span class="me-2">@lang('Showing:')</span>
                                        <div class="tom-select-custom">
                                            <select id="datatableEntries"
                                                    class="js-select form-select form-select-borderless w-auto"
                                                    autocomplete="off"
                                                    data-hs-tom-select-options='{
                                                                "searchInDropdown": false,
                                                                "hideSearch": true
                                                              }'>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20" selected>20</option>
                                                <option value="30">30</option>
                                            </select>
                                        </div>
                                        <span class="text-secondary me-2">@lang('of')</span>
                                        <span id="datatableEntriesInfoTotalQty"></span>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex justify-content-center justify-content-sm-end">
                                        <nav id="datatableEntriesPagination" aria-label="Activity pagination"></nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Keyword Modal -->
    @include('admin.language.components.add_keyword_modal')
    <!-- End Keyword Modal -->

    <!-- Edit Keyword Modal -->
    @include('admin.language.components.edit_keyword_modal')
    <!-- End Edit Keyword Modal -->

    <!-- Delete Keyword Modal -->
    @include('admin.language.components.delete_keyword_modal');
    <!-- End Delete Keyword Modal -->

    <!-- Import Keyword Modal -->
    @include('admin.language.components.import_keyword_modal');
    <!-- End Import Keyword Modal -->

    <!-- Auto Translate Keyword Modal -->
    @include('admin.language.components.auto_translate_keyword_modal');
    <!-- End Auto Translate Keyword Modal -->

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {

            $(document).on('click', '.deleteKey', function () {
                let keyword = $(this).data('keyword');
                $(".keyword").text(keyword);
            });

            var key = "";
            var value = "";
            $(document).on('click', '.edit-keyword-btn', function () {
                key = $(this).data('key');
                value = $(this).data('value');
                let route = $(this).data('route');

                $('.edit-key').text(key);
                $('.edit-value').val(value);
                $('.edit-keyword-form').attr('action', route);
            });

            $(document).on('input', '.input-field', function () {
                let val = $(this).val();
                if (val.length) {
                    $(this).siblings('.text-danger').text('');
                }
            });

            $(document).on('submit', '.add-keyword-form, .edit-keyword-form', function (e) {
                e.preventDefault();
                let formData = new FormData($(this)[0]);
                let url = $(this).attr('action');
                sendRequest(url, formData, $(this)[0]);
            });

            function sendRequest(url, formData, _this) {
                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('.value-error').text();
                        Notiflix.Block.pulse('.pulse-loader');
                    },
                    success: function (response) {
                        if (response.url) {
                            location.reload();
                        }
                    },
                    error: function (response) {
                        let errors = response.responseJSON.errors;
                        for (let error in errors) {
                            $(_this).find(`.${error}-error`).text(response.responseJSON.errors[error][0]);
                        }
                    },
                    complete: function () {
                        Notiflix.Block.Remove('.pulse-loader');
                    }
                });
            }

            $(document).on('click', '.translate_btn', function () {
                $('.edit-value').val("");
                let shortName = "{{$language->short_name}}";
                let route = $(this).data('route');
                $.ajax({
                    type: "post",
                    url: route,
                    data: {
                        shortName: shortName,
                        key: key,
                        value: value,
                        _token: "{{csrf_token()}}"
                    },
                    success: function (data) {
                        $('.edit-value').val(data.translatedText);
                        Notiflix.Notify.success(data.message);
                    },
                    error: function (res) {

                    }
                });
            })
        });

        (function () {
            HSCore.components.HSTomSelect.init('.js-select')
            HSCore.components.HSDatatables.init($('.js-datatable'), {
                language: {
                    zeroRecords: `<div class="text-center p-4">
                          <img class="dataTables-image mb-3" src="{{ asset("assets/admin/img/oc-error.svg") }}" alt="Image Description" data-hs-theme-appearance="default">
                          <img class="dataTables-image mb-3" src="{{ asset("assets/admin/img/oc-error-light.svg") }}" alt="Image Description" data-hs-theme-appearance="dark">
                        <p class="mb-0">{{ trans("No data to show") }}</p>
                        </div>`
                }
            });
        })()

    </script>
@endpush



