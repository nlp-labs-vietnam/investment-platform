@extends(template().'layouts.user')
@section('title',__('Payout History'))

@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-datepicker.css') }}"/>
    @endpush

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Payout History')</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between pb-0 border-0">
            <h4>@lang('Payout History')</h4>
            <div class="btn-area">
                <button type="button" class="cmn-btn" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">@lang('Filter')<i
                        class="fa-regular fa-filter"></i></button>
            </div>
        </div>

        <div class="card-body">
            <div class="cmn-table">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Transaction ID')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Created time')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($payouts as $key => $value)
                            <tr>
                                <td data-label="@lang('SL')"><span>{{ $loop->index + 1 }}</span></td>

                                <td data-label="@lang('Amount')">{{ currencyPosition($value->net_amount_in_base_currency) }}</td>

                                <td data-label="@lang('Transaction ID')">{{ __($value->trx_id) }}</td>

                                <td data-label="@lang('Status')">
                                    {!! $value->getStatusBadge() !!}
                                </td>
                                <td data-label="@lang('Created time')"> {{ dateTime($value->created_at)}} </td>
                                <td data-label="@lang('Action')" class="text-center">
                                    @if($value->status == 0)
                                        <a href="{{ route('user.payout.confirm', $value->trx_id) }}" target="_blank"
                                           class="cmn-btn btn-sm">@lang('Confirm')</a>
                                    @else
                                        @php
                                            $details = [];
                                            if ($value->information) {
                                                foreach ($value->information as $k => $v) {
                                                    $field_name = snake2Title($v->field_name);
                                                    $field_value = $v->type == "file" ? getFile(config('filesystems.default'), $v->field_value) : (@$v->field_value ?? $v->field_name);
                                                    $details[kebab2Title($k)] = [
                                                        'type' => $v->type,
                                                        'field_name' => $field_name,
                                                        'field_value' => $field_value
                                                    ];
                                                }
                                            }
                                            $amount = getAmount($value->amount, 2).' '.$value->payout_currency_code;
                                            $status =  $value->getStatusBadge() ;
                                        @endphp

                                        <button class="display showInfo"
                                                data-detailsinfo="{{ json_encode($details) }}"
                                                data-amount="{{ $amount }}"
                                                data-method="{{ optional($value->method)->name }}"
                                                data-gatewayimage="{{ getFile(optional($value->method)->driver, optional($value->method)->logo) }}"
                                                data-status="{{ $status }}"
                                                data-feedback="{{ $value->feedback }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#infoViewModal">
                                            <i class="fa-regular fa-display"></i>
                                        </button>

                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="dataTables_empty">
                                    <div class="text-center p-4">
                                        <img class="dataTables-image mb-3 empty-state-img"
                                             src="{{ asset(template(true).'img/oc-error.svg') }}"
                                             alt="Image Description" data-hs-theme-appearance="default">
                                        <p class="mb-0">@lang('No data to show')</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center mt-4">
                            {{ $payouts->appends($_GET)->links(template().'partials.pagination') }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <!-- Offcanvas sidebar start -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">@lang('Filter Payout List')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('user.payout.history.search') }}" method="get">
                <div class="row g-4">
                    <div>
                        <label for="name" class="form-label">@lang('Property')</label>
                        <input type="text" name="trx_id"
                               value="{{request()->trx_id}}"
                               class="form-control"
                               placeholder="@lang('Transaction ID')"/>
                    </div>

                    <div id="formModal">
                        <label class="form-label">@lang('Status')</label>
                        <select class="modal-select" name="status">
                            <option value="">@lang('All')</option>
                            <option value="0"
                                    @if(request()->status == '0') selected @endif>@lang('Pending')</option>
                            <option value="1"
                                    @if(request()->status == '1') selected @endif>@lang('Generate')</option>
                            <option value="2"
                                    @if(request()->status == '2') selected @endif>@lang('Success')</option>
                            <option value="3"
                                    @if(request()->status == '3') selected @endif>@lang('Cancel')</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label" for="from_date">@lang('From Date')</label>
                        <input
                            type="text" class="form-control datepicker from_date" name="from_date"
                            value="{{ old('from_date',request()->from_date) }}" placeholder="@lang('From date')"
                            autocomplete="off" readonly/>
                    </div>

                    <div>
                        <label class="form-label" for="from_date">@lang('To Date')</label>
                        <input
                            type="text" class="form-control datepicker to_date" name="to_date"
                            value="{{ old('to_date', request()->to_date) }}" placeholder="@lang('To date')"
                            autocomplete="off" readonly disabled="true"/>
                    </div>

                    <div class="btn-area">
                        <button type="submit" class="cmn-btn"><i class="fa-regular fa-magnifying-glass"></i>@lang('Filter')</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <!-- Offcanvas sidebar end -->

@endsection


@push('script')

    <script>
        'use strict'
        $(document).ready(function () {

            $('.from_date').on('change', function () {
                $('.to_date').removeAttr('disabled');
            });
        });
    </script>
@endpush

@push('loadModal')
    <div class="modal fade" id="infoViewModal" tabindex="-1" role="dialog" aria-hidden="true"
         data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mb-5">
                        <h3 class="mb-1">@lang('Payout Information')</h3>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-4">
                            <small class="text-cap mb-0">@lang('Payout method:')</small>
                            <div class="d-flex align-items-center">
                                <img class=" me-2 gateway_modal_image rounded-circle" style="height: 30px;width: 30px;"
                                     src="" alt="Payout Method Image">
                                <span class="text-dark method"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-cap mb-0">@lang('Payout amount:')</small> <br>
                            <span class="amount fw-semibold vertical-align-middle"></span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-cap mb-0">@lang('Status:')</small><br>
                            <span class="text-dark status">@lang('Generated')</span>
                        </div>
                    </div>

                    <div class="fw-semibold mb-2 mt-4">@lang('Summary')</div>

                    <ul class="list-container mb-4 payout_info"></ul>

                    <div class="note">
                        <div class="fw-semibold mb-2 mt-4">@lang('Feed Back')</div>
                        <span class="feedback">N/A</span>
                    </div>

                    <div class="modal-footer-text mt-3">
                        <div class="d-flex justify-content-end gap-3 status-buttons">
                            <button type="button" class="delete-btn" data-bs-dismiss="modal">@lang('Close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        $('.showInfo').on('click',function() {
            const { amount, method, gatewayimage, detailsinfo,status,feedback } = this.dataset;

            $('.gateway_modal_image').attr('src', gatewayimage);

            const list = Object.entries(JSON.parse(detailsinfo)).map(([key, { field_name, field_value, type }]) => `
            <li class="item text-dark py-2">
                    <span>${field_name}</span>
                    <span>${type === 'file' ? `<a href="${field_value}" target="_blank">
                        <img src="${field_value}" class="rounded-1" style="height: 60px;width: 100px;" alt="" ></a>` : field_value}</span>
            </li>`
            ).join('');

            $('.payout_info').html(list);
            $('.amount').html(amount);
            $('.method').html(method);
            $('.status').html(status);

            const feedBack = $('.feedback');
            if (!feedback) {
                feedBack.parent().hide();
            } else {
                feedBack.parent().show();
                feedBack.text(feedback);
            }

            $('#infoViewModal').modal('show');
        });

    </script>
@endpush
