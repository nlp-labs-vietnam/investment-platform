@extends('admin.layouts.app')
@section('page_title',__('Payment Request'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Payment Request')</a></li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Payment Request')</h1>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-md-0">
                    <div class="input-group input-group-merge navbar-input-group">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input type="search" id="datatableSearch"
                               class="search form-control form-control-sm"
                               placeholder="@lang('Search here')"
                               aria-label="@lang('Search here')"
                               autocomplete="off">
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
                                                <span class="text-cap text-body">@lang('Transaction ID')</span>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control"
                                                               id="transaction_id_filter_input"
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
                                                                data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>@lang("All Status")</span>'>
                                                            @lang('All Status')
                                                        </option>
                                                        <option value="2"
                                                                data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>@lang("Pending")</span>'>
                                                            @lang('Pending')
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 mb-4">
                                                <span class="text-cap text-body">@lang('Method')</span>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select" id="filter_method">
                                                        <option value="all"
                                                                data-option-template='<span class="d-flex align-items-center"><img class="avatar avatar-xss avatar-circle me-2" src="{{ asset("assets/upload/gateway/all_gateway.png")  }}" alt="..." /><span class="text-truncate">@lang("All Gateway")</span></span>'
                                                        @forelse($methods as $method)
                                                            <option value="@lang($method->id)"
                                                                    data-option-template='<span class="d-flex align-items-center"><img class="avatar avatar-xss avatar-circle me-2" src="{{getFile($method->driver, $method->image)}}" alt="Flag" /><span class="text-truncate">{{ $method->name }}</span></span>'>
                                                                @lang($method->name)
                                                            </option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12 mb-4">
                                                <span class="text-cap text-body">@lang('Date Range')</span>
                                                <div class="input-group mb-3 custom">
                                                    <input type="text" id="filter_date_range"
                                                           class="js-flatpickr form-control"
                                                           placeholder="Select dates"
                                                           data-hs-flatpickr-options='{
                                                                 "dateFormat": "d/m/Y",
                                                                 "mode": "range"
                                                               }' aria-describedby="flatpickr_filter_date_range">
                                                    <span class="input-group-text" id="flatpickr_filter_date_range">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </span>
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

            <div class=" table-responsive datatable-custom  ">
                <table id="datatable"
                       class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       data-hs-datatables-options='{
                       "columnDefs": [{
                          "targets": [0, 9],
                          "orderable": false
                        }],
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
                        <th>@lang('No.')</th>
                        <th>@lang('Transaction ID')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Method')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('Payable Amount')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Action')</th>
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
                            <!-- Select -->
                            <div class="tom-select-custom">
                                <select id="datatableEntries"
                                        class="js-select form-select form-select-borderless w-auto" autocomplete="off"
                                        data-hs-tom-select-options='{
                                            "searchInDropdown": false,
                                            "hideSearch": true
                                          }'>
                                    <option value="10">10</option>
                                    <option value="15" selected>15</option>
                                    <option value="20">20</option>
                                </select>
                            </div>
                            <span class="text-secondary me-2">of</span>
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


    @include('admin.user_management.components.payment_information_modal')


@endsection


@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush


@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
@endpush


@push('script')
    <script>
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
                    url: "{{ route("admin.payment.request") }}",
                },

                columns: [
                    {data: 'no', name: 'no'},
                    {data: 'trx', name: 'trx'},
                    {data: 'name', name: 'name'},
                    {data: 'method', name: 'method'},
                    {data: 'amount', name: 'amount'},
                    {data: 'charge', name: 'charge'},
                    {data: 'payable', name: 'payable'},
                    {data: 'status', name: 'status'},
                    {data: 'date', name: 'date'},
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

            });
            $.fn.dataTable.ext.errMode = 'throw';


            document.getElementById("filter_button").addEventListener("click", function () {

                let filterTransactionId = $('#transaction_id_filter_input').val();
                let filterStatus = $('#filter_status').val();
                let filterMethod = $('#filter_method').val();
                let filterDate = $('#filter_date_range').val();


                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route('admin.payment.request') }}" + "?filterTransactionID=" + filterTransactionId + "&filterStatus=" + filterStatus + "&filterMethod=" + filterMethod +
                    "&filterDate=" + filterDate).load();
            });


            $(document).on("click", '.edit_btn', function (e) {

                let id = $(this).data('id');
                let amount = $(this).data('amount');
                let status = $(this).data('status');
                let method = $(this).data('method');

                let date = $(this).data('datepaid');
                let feedback = $(this).data('feedback');
                let gatewayImage = $(this).data('gatewayimage');
                $('.gateway_modal_image').attr('src', gatewayImage)


                if (status == 1) {
                    $(".status-buttons button[name='status']").hide();
                }


                $(".action_id").val(id);
                $(".actionRoute").attr('action', $(this).data('action'));

                let details = Object.entries($(this).data('detailsinfo'));
                let list = details.map(([key, value]) => {

                    let field_name = value.field_name;
                    let field_value = value.field_value;

                    if (value.type === 'file') {
                        return `<li class="list-group-item text-dark">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>${field_name}</span>
                                        <a href="${field_value}" target="_blank"><img src="${field_value}" alt="Image Description" class="rounded-1" width="100"></a>
                                    </div>
                                </li>`;
                    } else {
                        return `<li class="list-group-item text-dark">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>${field_name}</span>
                                        <span>${field_value}</span>
                                    </div>
                                </li>`;
                    }
                })

                let feedbackField = "";
                if (feedback == '') {
                    feedbackField = `<div class="mb-3">
                                        <small class="text-cap mb-2">@lang('Send You Feedback')</small>
                                        <textarea name="feedback" class="form-control feedback" placeholder="Feedback" rows="3">{{old('feedback')}}</textarea>
                                         <span class="message text-danger"></span>
                                     </div>`;

                } else {
                    feedbackField = `<div class="mb-3">
                                        <small class="text-cap mb-2">@lang('Feedback')</small>
                                        <p>${feedback}</p>
                                     </div>`;

                }

                var feedbackValue = $.trim($('.feedback').val());
                if (!feedbackValue) {
                    $('.message').text("This field is required");
                }

                $('.get-feedback').html(feedbackField)

                $('.payment_information').html(list);
                $('.image').html(list);
                $('.amount').html(amount);
                $('.method').html(method);
                $('.date').html(date);

            });


        });

    </script>

@endpush






