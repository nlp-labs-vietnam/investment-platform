@extends(template().'layouts.user')
@section('title',trans($title))
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-datepicker.css') }}"/>
    @endpush
    <!-- main -->
    <div class="container-fluid">
        <div class="main row">
            <div class="col ">
                <div class="header-text-full d-flex justify-content-between align-items-end">
                    <nav aria-label="breadcrumb" class="ms-2">
                    <h3 class="dashboard_breadcurmb_heading mb-1">@lang('Money Transfer History')</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Money Transfer History')</li>
                        </ol>
                    </nav>
                    <div class="btn-area">
                        <button type="button" class="btn-custom" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                            @lang('Filter') <i class="fa-regular fa-filter"></i></button>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="table-parent table-responsive me-2 ms-2 mt-4">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>@lang('SL.')</th>
                            <th>@lang('Transaction ID')</th>
                            <th>@lang('User')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Time')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($balanceTransferHistory as $key => $item)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$item->trx}}</td>
                                <td>
                                    @if(auth()->id() == $item->sender_id)
                                        {{optional($item->receiver)->username}}
                                    @else
                                        {{optional($item->sender)->username}}
                                    @endif
                                </td>
                                <td>
                                    @if(auth()->id() == $item->sender_id)
                                        <span class="badge rounded-pill bg-danger">Send</span>
                                    @else
                                        <span class="badge rounded-pill bg-success">Receive</span>
                                    @endif

                                </td>
                                <td>{{basicControl()->currency_symbol}}{{getAmount($item->amount)}} </td>
                                <td><span>{{dateTime($item->created_at, 'd M Y h:i A')}}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="dataTables_empty " >
                                    <div class="text-center p-4">
                                        <p class="mb-0">@lang('No data to show')</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center m-0">
                            {{ $balanceTransferHistory->appends($_GET)->links(template().'partials.pagination') }}
                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Offcanvas sidebar start -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">@lang('Filter')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="" method="get">
                <div class="row g-4">
                    <div>
                        <label for="ProductName" class="form-label">@lang('Transaction Id')</label>
                        <input type="text" name="trx"
                               value="{{@request()->trx}}"
                               class="form-control"
                               placeholder="@lang('Transaction ID')"/>
                    </div>

                    <div id="formModal">
                        <label class="form-label">@lang('Username')</label>
                        <input type="text" name="username" value="{{@request()->username}}"
                               class="form-control"
                               placeholder="@lang('username')"/>
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
                        <button type="submit" class="btn-custom"><i class="fa-regular fa-magnifying-glass"></i>@lang('Filter')</button>
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
