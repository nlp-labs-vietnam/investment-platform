@extends('admin.layouts.app')
@section('page_title', __('SMS Templates'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang("Dashboard")
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a class="breadcrumb-link" href="{{ route('admin.settings') }}">@lang('Settings')</a>
                            </li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("SMS Templates")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("SMS Templates")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.'.(url()->previous() == route('admin.settings', 'sms') || url()->previous() == route('admin.sms.controls') ? 'sms' : 'email')), 'suffix' => ''])
            </div>
            <div class="col-lg-9">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header card-header-content-md-between">
                            <div class="mb-2 mb-md-0">
                                <div class="input-group input-group-merge navbar-input-group">
                                    <div class="input-group-prepend input-group-text">
                                        <i class="bi-search"></i>
                                    </div>
                                    <input type="search" id="datatableSearch"
                                           class="search form-control form-control-sm"
                                           placeholder="@lang('Search Notification Templates')"
                                           aria-label="@lang('Search Notification Templates')"
                                           autocomplete="off">
                                    <a class="input-group-append input-group-text" href="javascript:void(0)">
                                        <i id="clearSearchResultsIcon" class="bi-x d-none"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class=" table-responsive datatable-custom  ">
                            <table id="datatable"
                                   class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                   data-hs-datatables-options='{
                                       "columnDefs": [{
                                          "targets": [0, 2],
                                          "orderable": false
                                        }],
                                        "ordering": false,
                                       "order": [],
                                       "info": {
                                         "totalQty": "#datatableWithPaginationInfoTotalQty"
                                       },
                                       "search": "#datatableSearch",
                                       "entries": "#datatableEntries",
                                       "pageLength": 10,
                                       "isResponsive": false,
                                       "isShowPaging": false,
                                       "pagination": "datatablePagination"
                                     }'>
                                <thead class="thead-light">
                                <tr>
                                    <th>@lang('SL.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($smsTemplates as $template)
                                    <tr>
                                        <td>{{ __($loop->index + 1) }} </td>
                                        <td>{{ __($template->name) }} </td>
                                        <td>
                                            <span class="badge bg-soft-{{ $template->status['sms'] == 1 ? "success" :  "danger" }} text-{{ $template->status['sms'] == 1 ? "success" :  "danger" }}">
                                                <span class="legend-indicator bg-{{ $template->status['sms'] == 1 ? "success" :  "danger" }}"></span> {{ __($template->status['sms'] == 1 ? "Active" :  "Inactive") }}
                                            </span>
                                        </td>
                                        <td>
                                            <a class="btn btn-white btn-sm"
                                               href="{{ route('admin.sms.template.edit', $template->id) }}">
                                                <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <div class="text-center p-4">
                                        <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                                        <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
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
                                                <option value="5">5</option>
                                                <option value="10 selected">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                            </select>
                                        </div>
                                        <span class="text-secondary me-2">@lang('of')</span>
                                        <span id="datatableWithPaginationInfoTotalQty"></span>
                                    </div>
                                </div>

                                <div class="col-sm-auto">
                                    <div class="d-flex  justify-content-center justify-content-sm-end">
                                        <nav id="datatablePagination" aria-label="Activity pagination"></nav>
                                    </div>
                                </div>
                            </div>
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
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).on('ready', function () {

            HSCore.components.HSTomSelect.init('.js-select');

            $(document).on('click', '.set', function () {
                let url = $(this).data('route');
                let value = $(this).data('value');
                $('.method_value').val(value);
                $('.setRoute').attr('action', url);
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                language: {
                    zeroRecords: `<div class="text-center p-4">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <p class="mb-0">No data to show</p>
                    </div>`,
                },
            })


            $.fn.dataTable.ext.errMode = 'throw';
        });

    </script>
@endpush



