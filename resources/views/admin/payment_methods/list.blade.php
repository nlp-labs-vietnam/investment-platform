@extends('admin.layouts.app')
@section('page_title', __('Payment Gateway'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang("Dashboard")</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Payment Settings")</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Payment Gateway")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Payment Gateway")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-header-title">@lang('Payment Methods')</h4>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive datatable-custom">
                            <table
                                class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                data-hs-datatables-options='{
                                   "order": [],
                                   "info": {
                                     "totalQty": "#datatableEntriesInfoTotalQty"
                                   },
                                   "ordering":false,
                                   "pageLength": 40,
                                   "entries": "#datatableEntries",
                                   "isResponsive": false,
                                   "isShowPaging": false,
                                   "pagination": "datatableWithPaginationPagination"
                                 }'>
                                <thead class="thead-light">
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Gateway Currencies')</th>
                                    <th>@lang('Supported Currency')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody class="js-sortable" data-hs-sortable-options='{
                                      "animation": 150,
                                      "handle": ".sortablejs-custom-handle"
                                    }'>
                                @forelse($paymentGateways as $method)
                                    <tr data-code="{{ $method->code }}">
                                        <td>
                                            <a class="d-flex align-items-center"
                                               href="{{ route("admin.edit.payment.methods", $method->id) }}">
                                                <div class="list-group-item">
                                                    <i class="sortablejs-custom-handle bi-grip-horizontal list-group-icon"></i>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="avatar avatar-circle">
                                                        <img class="avatar-img"
                                                             src="{{ getFile($method->driver, $method->image) }}"
                                                             alt="Image Description">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <span class="h5 text-inherit">
                                                        @lang($method->name)
                                                    </span>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-soft-dark text-dark">{{$method->countGatewayCurrency()}}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-soft-dark text-dark">@lang(count($method->supported_currency ?? 0))</span>
                                        </td>
                                        <td>
                                            {{ Str::limit($method->description, 32) }}
                                        </td>
                                        <td>
                                            @if($method->status == 1)
                                                <span class="badge bg-soft-success text-success">
                                                    <span class="legend-indicator bg-success"></span>@lang('Active')
                                                </span>
                                            @else
                                                <span class="badge bg-soft-danger text-danger">
                                                    <span class="legend-indicator bg-danger"></span>@lang('Inactive')
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a class="btn btn-white btn-sm"
                                                   href="{{ route('admin.edit.payment.methods', $method->id) }}">
                                                    <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                                </a>
                                                <div class="btn-group">
                                                    <button type="button"
                                                            class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                            id="productsEditDropdown" data-bs-toggle="dropdown"
                                                            aria-expanded="false"></button>
                                                    <div class="dropdown-menu dropdown-menu-end mt-1"
                                                         aria-labelledby="productsEditDropdown"
                                                         data-popper-placement="bottom-end">
                                                        <a class="dropdown-item disableBtn" href="javascript:void(0)"
                                                           data-code="{{ $method->code }}"
                                                           data-status="{{ $method->status }}"
                                                           data-message="{{($method->status == 0)?'enable':'disable'}}"
                                                           data-bs-toggle="modal" data-bs-target="#statusModal">
                                                            <i class="fa-light fa-{{($method->status == 0)?'check':'ban'}} dropdown-item-icon"></i> {{($method->status == 0)?'Mark As Enable':'Mark As Disable'}}
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
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="accountAddCardModalLabel"
         data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="statusModalLabel"><i
                            class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.payment.methods.deactivate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="code">
                        <p>@lang("Do you want to") <span class="messageShow"></span> @lang('this payment gateway?')</p>
                        <div class="d-flex justify-content-end gap-3 pt-2">
                            <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang("Close")</button>
                            <button type="submit" class="btn btn-primary">@lang("Confirm")</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sortable.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            HSCore.components.HSSortable.init('.js-sortable')
            HSCore.components.HSDatatables.init($('.js-datatable'), {
                language: {
                    zeroRecords: `<div class="text-center p-4">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <p class="mb-0">No data to show</p>
                    </div>`,
                    processing: `<div><div></div><div></div><div></div><div></div></div>`
                },

            })

            $('.js-sortable').on('change', function () {
                let methods = [];
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

            $(document).on('click', '.disableBtn', function () {
                let status = $(this).data('status');
                $('.messageShow').text($(this).data('message'));
                let modal = $('#statusModal');
                modal.find('input[name=code]').val($(this).data('code'));
            })

            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

        });


    </script>
@endpush




