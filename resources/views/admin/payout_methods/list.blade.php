@extends('admin.layouts.app')
@section('page_title', __('Withdraw Methods'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Withdraw Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Withdraw Methods')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Withdraw Methods')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        @if(adminAccessRoute(config('permissionList.Settings_Panel.Withdraw_Settings.permission.add')))
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h2 class="card-title h4 mt-2">@lang('Withdraw Methods')</h2>
                                <a href="{{ route('admin.payout.method.create') }}" class="btn btn-sm btn-primary">
                                    @lang('Add Method')</a>
                            </div>
                        @endif


                        <div class=" table-responsive datatable-custom">
                            <table id="datatable"
                                   class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                   data-hs-datatables-options='{
                                       "columnDefs": [{
                                          "targets": [0, 3],
                                          "orderable": false
                                        }],
                                        "ordering": false,
                                       "order": [],
                                       "info": {
                                         "totalQty": "#datatableWithPaginationInfoTotalQty"
                                       },
                                       "search": "#datatableSearch",
                                       "entries": "#datatableEntries",
                                       "pageLength": 15,
                                       "isResponsive": false,
                                       "isShowPaging": false,
                                       "pagination": "datatablePagination"
                                     }'>
                                <thead class="thead-light">
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                                </thead>

                                <tbody class="js-sortable sortablejs-custom">
                                @forelse($payoutMethods as $method)
                                    <tr data-code="{{ $method->code }}">
                                        <td>
                                            <a class="d-flex align-items-center"
                                               href="{{ route("admin.payout.method.edit", $method->id) }}">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar avatar-circle">
                                                        <img class="avatar-img"
                                                             src="{{ getFile($method->driver, $method->logo) }}"
                                                             alt="Image Description">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <span class="h5 text-inherit">
                                                        @lang($method->name)
                                                    </span>
                                                    <div>
                                                        <span class="font-weight-bold font-14 text-success">
                                                            {{ $method->is_automatic == 1 ? 'Automatic':'Manual' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            @lang($method->description)
                                        </td>
                                        <td>
                                            @if($method->is_active)
                                                <span class="badge bg-soft-success text-info">
                                                    <span class="legend-indicator bg-success"></span>@lang('Active')
                                                </span>
                                            @else
                                                <span class="badge bg-soft-danger text-danger">
                                                    <span class="legend-indicator bg-danger"></span>@lang('Inactive')
                                                </span>
                                            @endif
                                        </td>
                                        @if(adminAccessRoute(config('permissionList.Settings_Panel.Withdraw_Settings.permission.edit')))
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a class="btn btn-white btn-sm"
                                                       href="{{ route('admin.payout.method.edit', $method->id) }}">
                                                        <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                                    </a>
                                                    <div class="btn-group">
                                                        <button type="button"
                                                                class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                                id="payoutEditDropdown" data-bs-toggle="dropdown"
                                                                aria-expanded="false"></button>
                                                        <div class="dropdown-menu dropdown-menu-end mt-1"
                                                             aria-labelledby="payoutEditDropdown"
                                                             data-popper-placement="bottom-end">
                                                            <a class="dropdown-item disableBtn"
                                                               href="javascript:void(0)"
                                                               data-bs-target="#activeDeactivateModal"
                                                               data-code="{{ $method->code }}"
                                                               data-status="{{ $method->is_active }}"
                                                               data-message="{{($method->is_active == 0)?'enable':'disable'}}"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#activeDeactivateModal">
                                                                <i class="fa-light fa-{{($method->is_active == 0)?'check':'ban'}} dropdown-item-icon"></i> {{($method->is_active == 0)?'Mark As Enable':'Mark As Disable'}}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
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

                        @if(count($payoutMethods) > 15)
                            <div class="card-footer">
                                <div
                                    class="row justify-content-center justify-content-sm-between align-items-sm-center">
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
                                                    <option value="10">10</option>
                                                    <option value="15" selected>15</option>
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="activeDeactivateModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
         aria-labelledby="activeDeactivateModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="activeDeactivateModalLabel"><i
                            class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payout.active.deactivate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="code">
                    <div class="modal-body">
                        @lang('Do you want to') <span class="messageShow"></span> @lang('this payout gateway?')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush


@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sortable.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $('.js-sortable').on('change', function () {
            var methods = [];
            $('.js-sortable tr').each(function (key, val) {
                let methodCode = $(val).data('code');
                methods.push(methodCode);
            });
            $.ajax({
                'url': "{{ route('admin.sort.payment.methods') }}",
                'method': "POST",
                'data': {sort: methods}
            })
        })

        $('.disableBtn').on('click', function () {
            let status = $(this).data('status');
            $('.messageShow').text($(this).data('message'));
            let modal = $('#activeDeactivateModal');
            modal.find('input[name=code]').val($(this).data('code'));
        });

        (function () {
            window.onload = function () {

                HSCore.components.HSDatatables.init($('#datatable'), {
                    language: {
                        zeroRecords: `<div class="text-center p-4">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <p class="mb-0">No data to show</p>
                    </div>`,
                        processing: `<div><div></div><div></div><div></div><div></div></div>`
                    },

                })
                HSCore.components.HSSortable.init('.js-sortable')
                HSBsDropdown.init()
                HSCore.components.HSFlatpickr.init('.js-flatpickr')
                HSCore.components.HSTomSelect.init('.js-select', {
                    maxOptions: 250,
                })
            }
        })();
    </script>
@endpush





