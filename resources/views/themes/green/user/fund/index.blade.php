@extends(template().'layouts.user')
@section('title',trans('Fund History'))
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-datepicker.css') }}"/>
    @endpush


    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Fund History')</li>
            </ol>
        </nav>
    </div>


    <div class="card">
        <div class="card-header d-flex justify-content-between pb-0 border-0">
            <h4>@lang('Fund History')</h4>
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
                            <th scope="col">@lang('SL')</th>
                            <th scope="col">@lang('Method')</th>
                            <th scope="col">@lang('Transaction ID')</th>
                            <th scope="col">@lang('Amount')</th>
                            <th scope="col">@lang('Charge')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Created time')</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($funds as $key => $value)
                            <tr>
                                <td data-label="@lang('SL')"><span>{{loopIndex($funds) + $key}}</span></td>
                                <td class="td-semibold">
                                    {{ __(optional($value->gateway)->name) ?? __('N/A') }}
                                </td>
                                <td>
                                    {{ __($value->trx_id) }}
                                </td>
                                <td class="td-semibold">
                                    {{ currencyPosition(getAmount($value->payable_amount_in_base_currency)) }}
                                </td>
                                <td class="text-danger">
                                    {{ currencyPosition(getAmount($value->base_currency_charge)) }}
                                </td>
                                <td data-label="@lang('Status')">
                                    @if($value->status == 0)
                                        <span class="badge text-bg-warning">@lang('Pending')</span>
                                    @elseif($value->status == 1)
                                        <span class="badge text-bg-success">@lang('Success')</span>
                                    @elseif($value->status == 2)
                                        <span class="badge text-bg-dark">@lang('Requested')</span>
                                    @elseif($value->status == 3)
                                        <span class="badge text-bg-danger">@lang('Rejected')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Created Time')">
                                    {{ dateTime($value->created_at)}}
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
                            {{ $funds->appends($_GET)->links(template().'partials.pagination') }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <!-- Offcanvas sidebar start -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">@lang('Filter Fund List')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('user.fund.history.search') }}" method="get">
                <div class="row g-4">
                    <div>
                        <label for="name" class="form-label">@lang('Property')</label>
                        <input type="text" name="name"
                               value="{{request()->name}}"
                               class="form-control"
                               placeholder="@lang('Transaction ID')"/>
                    </div>

                    <div id="formModal">
                        <label class="form-label">@lang('Status')</label>
                        <select class="modal-select" name="status">
                            <option value="">@lang('All')</option>
                            <option value="1"
                                    @if(@request()->status == '1') selected @endif>@lang('Complete Payment')</option>
                            <option value="2"
                                    @if(@request()->status == '2') selected @endif>@lang('Pending Payment')</option>
                            <option value="3"
                                    @if(@request()->status == '3') selected @endif>@lang('Cancel Payment')</option>
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
