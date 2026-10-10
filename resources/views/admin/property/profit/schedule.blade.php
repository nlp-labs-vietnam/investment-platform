@extends('admin.layouts.app')
@section('page_title',__('Profit Schedule'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Dashboard")</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Manage Property")</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Profit Schedule")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("All Profit Schedules")</h1>
                </div>


                @if(adminAccessRoute(config('permissionList.Manage_Property.Profit_Schedule.permission.add')))
                    <div class="col-sm-auto">
                        <a class="btn btn-primary" href="javascript:void(0)"
                           data-bs-toggle="modal" data-bs-target="#createScheduleModal">
                            <i class="fas fa-plus-circle me-1"></i>
                            @lang('Create Schedule')
                        </a>
                    </div>
                @endif
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="shadow p-3 mb-5 alert alert-soft-dark mb-4 mb-lg-7" role="alert">
                    <div class="alert-box d-flex flex-wrap align-items-center">
                        <div class="flex-shrink-0">
                            <img class="avatar avatar-xl"
                                 src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                                 alt="Image Description" data-hs-theme-appearance="default">
                            <img class="avatar avatar-xl"
                                 src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                                 alt="Image Description" data-hs-theme-appearance="dark">
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h3 class=" mb-1">@lang("Attention!")</h3>
                            <div class="d-flex flex-wrap align-items-center">
                                <p class="mb-0 text-body "> @lang('You can now set how often you will return the profits to the investors. If you want to give daily profit return to your investors then create 1 day schedule. And select 1 day while creating investment property.')
                                    <br>
                                @lang('Then your investors will get daily profit return')
                                </p>


                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header card-header-content-md-between">
                        <div class="mb-2 mb-md-0">
                            <div class="input-group input-group-merge navbar-input-group">
                                <div class="input-group-prepend input-group-text">
                                    <i class="bi-search"></i>
                                </div>
                                <input type="search" id="datatableSearch"
                                       class="search form-control form-control-sm"
                                       placeholder="@lang('Search..')"
                                       aria-label="@lang('Search..')"
                                       autocomplete="off">
                                <a class="input-group-append input-group-text" href="javascript:void(0)">
                                    <i id="clearSearchResultsIcon" class="bi-x d-none"></i>
                                </a>
                            </div>
                        </div>

                        <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                            <div class="dropdown">
                                <button type="button" class="btn btn-white btn-sm w-100"
                                        id="dropdownMenuClickable" data-bs-auto-close="false"
                                        id="usersFilterDropdown"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="bi-filter me-1"></i> @lang('Filter')
                                </button>

                                <div
                                    class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered filter_dropdown"
                                    aria-labelledby="dropdownMenuClickable">
                                    <div class="card">
                                        <div class="card-header card-header-content-between">
                                            <h5 class="card-header-title">@lang('Filter')</h5>
                                            <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm ms-2"
                                                    id="filter_close_btn">
                                                <i class="bi-x-lg"></i>
                                            </button>
                                        </div>

                                        <div class="card-body">
                                            <form id="filter_form">
                                                <div class="row">
                                                    <div class="mb-4">
                                                        <span class="text-cap text-body">@lang('Duration')</span>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <input type="text" class="form-control"
                                                                       id="duration_filter_input"
                                                                       autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="mb-4">
                                                        <span class="text-cap text-body">@lang('Duration Type')</span>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <input type="text" class="form-control"
                                                                       id="duration_type_filter_input"
                                                                       autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm mb-4">
                                                        <small class="text-cap text-body">@lang('Status')</small>
                                                        <div class="tom-select-custom">
                                                            <select
                                                                class="js-select js-datatable-filter form-select form-select-sm"
                                                                id="filter_status"
                                                                data-target-column-index="4" data-hs-tom-select-options='{
                                                                  "placeholder": "Any status",
                                                                  "searchInDropdown": false,
                                                                  "hideSearch": true,
                                                                  "dropdownWidth": "10rem"
                                                                }'>
                                                                <option value="all"
                                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>All Status</span>'>
                                                                    @lang('All Status')
                                                                </option>
                                                                <option value="1"
                                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>Active</span>'>
                                                                    @lang('Active')
                                                                </option>
                                                                <option value="0"
                                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Deactive</span>'>
                                                                    @lang('Inactive')
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row gx-2">
                                                    <div class="col">
                                                        <div class="d-grid">
                                                            <button type="button" id="clear_filter"
                                                                    class="btn btn-white">@lang('Clear Filters')</button>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="d-grid">
                                                            <button type="button" class="btn btn-primary"
                                                                    id="filter_button"><i
                                                                    class="bi-search"></i> @lang('Apply')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

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
                                       "pageLength": 20,
                                       "isResponsive": false,
                                       "isShowPaging": false,
                                       "pagination": "datatablePagination"
                                     }'>
                            <thead class="thead-light">

                            <tr>
                                <th scope="col">@lang('Duration')</th>
                                <th scope="col">@lang('Duration Type')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>

                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                            <div class="col-sm mb-2 mb-sm-0">
                                <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
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
                                            <option value="30">50</option>
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

    <!-- Create Schedule Modal -->
    <div class="modal fade" id="createScheduleModal" tabindex="-1" role="dialog"
         aria-labelledby="loginAsUserModalLabel" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="loginAsManagerModalLabel"><i
                            class="fas fa-plus-circle me-1"></i> @lang('Add New Schedule')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.profit.schedule.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="time" class="form-label">@lang('Schedule Time')</label>
                                    <div class="input-group">
                                        <input type="text"
                                               onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                               class="form-control @error('shipping_days') is-invalid @enderror"
                                               name="time" placeholder="@lang('1')" min="1" required/>

                                        <div class="input-group-append">
                                            <select name="time_type" id="time_type" class="form-control">
                                                <option value="days">Day(s)</option>
                                                <option value="months">Months(s)</option>
                                                <option value="years">Year(s)</option>
                                            </select>
                                        </div>
                                        @error('shipping_days')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="row form-check form-switch my-4"
                                           for="status">
                                            <span class="col-8 col-sm-9 ms-0">
                                              <span class="d-block text-dark">@lang("Status")</span>
                                              <span
                                                  class="d-block fs-5">@lang("Display the status of the shipping date (active or Inactive)")</span>
                                            </span>
                                        <span class="col-4 col-sm-3 text-end">
                                                    <input type="hidden" value="0" name="status"/>
                                                    <input
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        type="checkbox" name="status"
                                                        id="status" value="1"
                                                        {{old('status') == '1' ? 'checked':''}}>
                                                </span>
                                        @error('status')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn-primary">@lang('Create')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!-- Edit Parcel Type Modal -->
    <div class="modal fade" id="updateScheduleModal" tabindex="-1" role="dialog"
         aria-labelledby="loginAsUserModalLabel" data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="loginAsManagerModalLabel"><i
                            class="fas fa-plus-circle me-1"></i> @lang('Update Schedule')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="" enctype="multipart/form-data" class="updateScheduleForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="time" class="form-label">@lang('Schedule Time')</label>
                                    <div class="input-group">
                                        <input type="text"
                                               onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                               class="form-control edit_time @error('shipping_days') is-invalid @enderror"
                                               name="time" min="1" required/>

                                        <div class="input-group-append">
                                            <select name="time_type" id="edit_time_type"
                                                    class="form-control edit_time_type">
                                                <option value="days">Day(s)</option>
                                                <option value="months">Months(s)</option>
                                                <option value="years">Year(s)</option>
                                            </select>
                                        </div>
                                        @error('edit_time')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="row form-check form-switch my-4"
                                           for="status">
                                            <span class="col-8 col-sm-9 ms-0">
                                              <span class="d-block text-dark">@lang("Status")</span>
                                              <span
                                                  class="d-block fs-5">@lang("Display the status of the shipping date (active or Inactive)")</span>
                                            </span>
                                        <span class="col-4 col-sm-3 text-end">
                                                    <input type="hidden" value="0" name="status"/>
                                                    <input
                                                        class="form-check-input @error('status') is-invalid @enderror statusCheck"
                                                        type="checkbox" name="status"
                                                        id="status" value="1">
                                                </span>
                                        @error('status')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn-primary">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!-- Delete Schedule Modal -->
    <div class="modal fade" id="deleteScheduleModal" tabindex="-1" role="dialog"
         aria-labelledby="loginAsUserModalLabel"
         data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="loginAsManagerModalLabel"><i
                            class="fas fa-plus-circle me-1"></i> @lang('Delete Confirmation')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" enctype="multipart/form-data" class="deleteScheduleForm">
                    @csrf
                    @method('delete')
                    <div class="modal-body">

                        <p>@lang('Are you sure to delete this schedule?')</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('No')</button>
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
@endpush

@push('script')

    @if($errors->has('time') || $errors->has('time_type'))
        <script>
            var myModal = new bootstrap.Modal(document.getElementById("createScheduleModal"), {});
            document.onreadystatechange = function () {
                myModal.show();
            };
        </script>
    @endif

    <script>
        'use strict';

        $(document).on('ready', function () {
            HSCore.components.HSFlatpickr.init('.js-flatpickr')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("admin.profit.schedule.search") }}",
                },

                columns: [
                    {data: 'duration', name: 'duration'},
                    {data: 'duration_type', name: 'duration_type'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ],

                language: {
                    zeroRecords: `<div class="text-center p-4">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <p class="mb-0">No data to show</p>
                    </div>`,
                    processing: `<div><div></div><div></div><div></div><div></div></div>`
                },
            })

            $.fn.dataTable.ext.errMode = 'throw';

            document.getElementById("filter_close_btn").addEventListener("click", function () {
                let dropdownMenu = document.querySelector(".dropdown-menu.show");
                if (dropdownMenu) {
                    dropdownMenu.classList.remove("show");
                }
            });

            document.getElementById("filter_button").addEventListener("click", function () {
                let filterDuration = $('#duration_filter_input').val();
                let filterDurationType = $('#duration_type_filter_input').val();
                let filterStatus = $('#filter_status').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route('admin.profit.schedule.search') }}" + "?filterDuration=" + filterDuration + "&filterDurationType=" + filterDurationType + "&filterStatus=" + filterStatus).load();
            });

            document.getElementById("clear_filter").addEventListener("click", function () {
                document.getElementById("filter_form").reset();
            });


            $(document).on('click', '.updateSchedule', function () {
                let dataSchedule = $(this).data('schedule');
                let dataRoute = $(this).data('route');

                if (dataSchedule.status == 1) {
                    $('.statusCheck').attr('checked', 'true')
                }

                $('.edit_time').val(dataSchedule.time);
                $('.edit_time_type').val(dataSchedule.time_type);

                $('.updateScheduleForm').attr('action', dataRoute);
            });

            $(document).on('click', '.deleteSchedule', function () {
                let dataRoute = $(this).data('route');
                $('.deleteScheduleForm').attr('action', dataRoute);
            });

        });

    </script>
@endpush



