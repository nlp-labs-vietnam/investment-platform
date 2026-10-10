@extends(template().'layouts.user')
@section('title',__('Payout History'))

@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-datepicker.css') }}"/>
    @endpush

    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <div class="header-text-full d-flex justify-content-between align-items-end">
                    <nav aria-label="breadcrumb" class="ms-2">
                    <h3 class="dashboard_breadcurmb_heading mb-1">@lang('Payout History')</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Payout History')</li>
                        </ol>
                    </nav>
                    <button type="button" class="btn-custom" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">@lang('Filter')
                        <i class="fa-regular fa-filter"></i>
                    </button>
                </div>


                <div class="col-12">
                    <div class="table-parent table-responsive mt-4">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>@lang('SL')</th>
                                <th>@lang('Method')</th>
                                <th>@lang('Transaction ID')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Charge')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Created time')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($payouts as $key => $value)
                                <tr>
                                    <td data-label="@lang('SL')">{{ $loop->index + 1 }}</td>
                                    <td data-label="@lang('Method')" class="td-semibold">{{ $value->method?->name }}</td>
                                    <td data-label="@lang('Transaction ID')">{{ __($value->trx_id) }}</td>
                                    <td data-label="@lang('Amount')" class="td-semibold">{{ currencyPosition($value->net_amount_in_base_currency) }}</td>
                                    <td data-label="@lang('Charge')" class="text-danger">{{ currencyPosition($value->charge_in_base_currency) }}</td>
                                    <td data-label="@lang('Status')">
                                        @if($value->status == 0)
                                            <span class="badge bg-warning">@lang('Pending')</span>
                                        @elseif($value->status == 1)
                                            <span class="badge bg-info">@lang('Generated')</span>
                                        @elseif($value->status == 2)
                                            <span class="badge bg-success">@lang('Payment Done')</span>
                                        @elseif($value->status == 3)
                                            <span class="badge bg-danger">@lang('Canceled')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Created time')"> {{ dateTime($value->created_at)}} </td>
                                    <td data-label="@lang('Action')" >
                                        @if($value->status == 0)
                                            <a href="{{ route('user.payout.confirm',$value->trx_id) }}" target="_blank"
                                               class="btn btn-sm btn-primary">@lang('Confirm')</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="100%" class="text-center">@lang('No data found')</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- pagination -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end m-0">
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
                        <button type="submit" class="btn-custom">@lang('Filter')</button>
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
