@extends(template().'layouts.user')
@section('title', trans('Offer List'))
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-datepicker.css') }}"/>
    @endpush
    <!-- Invest history -->


    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Offer List')</li>
            </ol>
        </nav>
    </div>

    <!-- Cmn table section start -->
    <div class="card">
        <div class="card-header d-flex justify-content-between pb-0 border-0">
            <h4>@lang('Offer List')</h4>
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
                            <th scope="col">@lang('Property')</th>
                            <th scope="col">@lang('Offered From')</th>
                            <th scope="col">@lang('Sell Amount')</th>
                            <th scope="col">@lang('Offer Amount')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($allOfferList as $key => $offerList)
                            <tr>
                                <td data-label="@lang('SL')"><span>{{loopIndex($allOfferList) + $key}}</span></td>
                                <td data-label="@lang('Property')">
                                    <span>@lang(optional($offerList->property)->title)</span>
                                </td>

                                <td data-label="@lang('Offered From')">
                                    <span>@lang(optional($offerList->user)->fullname)</span>
                                </td>


                                <td data-label="@lang('Sell Amount')">
                                    <span>{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount($offerList->sell_amount)) }}</span>
                                </td>

                                <td data-label="@lang('Offer Amount')">
                                    <span>{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount($offerList->amount)) }}</span>
                                </td>

                                <td data-label="@lang('Status')">
                                    <span
                                        class="badge text-bg-primary {{ ($offerList->status == 0) ? 'text-bg-warning' : (($offerList->status == 1) ? 'text-bg-success'  : 'text-bg-danger') }}">
                                         {{ ($offerList->status == 0) ? __('Pending') : (($offerList->status == 1) ? __('Accepted')  : __('Rejected')) }}
                                    </span>
                                </td>

                                <td data-label="Action">
                                    <div class="dropdown">
                                        <button class="action-btn-secondary" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-regular fa-ellipsis-stroke-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(optional($offerList->propertyShare)->status == 0 && $offerList->payment_status == 0)
                                                <li>
                                                    <a class="dropdown-item disabled"
                                                       href="javascript:void(0)">@lang('Sold out')</a>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="dropdown-item notiflix-confirm"
                                                       data-bs-toggle="modal" data-bs-target="#accept-modal"
                                                       data-route="{{ route('user.offerAccept', $offerList->id) }}"
                                                       href="javascript:void(0)">
                                                        @lang('Accept')
                                                    </a>
                                                </li>
                                            @endif

                                            @if($offerList->lockInfo() && optional($offerList->offerlock)->status == 1 && $offerList->lockInfo()->status == 1)
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('user.offerConversation', $offerList->id) }}">
                                                        @lang('Conversation')
                                                    </a>
                                                </li>
                                            @else

                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('user.offerConversation', $offerList->id) }}">
                                                        @lang('Conversation')
                                                    </a>
                                                </li>

                                                <li>
                                                    <a class="dropdown-item notiflix-confirm"
                                                       data-bs-toggle="modal" data-bs-target="#reject-modal"
                                                       data-route="{{ route('user.offerReject', $offerList->id) }}"
                                                       href="javascript:void(0)">
                                                        @lang('Reject')
                                                    </a>
                                                </li>

                                                <li>
                                                    <a class="dropdown-item notiflix-confirm"
                                                       data-bs-toggle="modal" data-bs-target="#delete-modal"
                                                       data-route="{{route('user.propertyOfferRemove', $offerList->id)}}"
                                                       href="javascript:void(0)">
                                                        @lang('Remove')
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="text-center">
                                <td colspan="100%" class="text-danger text-center">{{trans('No Data Found!')}}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Cmn table section end -->

    <!-- Offcanvas sidebar start -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">@lang('Filter Offer List')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="" method="get" enctype="multipart/form-data">
                <div class="row g-4">
                    <div>
                        <label for="ProductName" class="form-label">@lang('Property')</label>
                        <input type="text" name="property"
                               value="{{request()->property}}"
                               class="form-control"
                               placeholder="@lang('Search property')"/>
                    </div>

                    <div id="formModal">
                        <label class="form-label">@lang('Sort By')</label>
                        <select class="modal-select" name="sort_by">
                            <option value="">@lang('All')</option>
                            <option value="1"
                                    @if(request()->sort_by == '1') selected @endif>@lang('Newest first')</option>
                            <option value="2"
                                    @if(request()->sort_by == '2') selected @endif>@lang('Oldest first')</option>
                            <option value="3"
                                    @if(request()->sort_by == '3') selected @endif>@lang('Offer Amount High to Low')</option>
                            <option value="4"
                                    @if(request()->sort_by == '4') selected @endif>@lang('Offer Amount Low to High')</option>
                        </select>
                    </div>

                    <div id="formModal">
                        <label class="form-label">@lang('Status')</label>
                        <select class="modal-select" name="status">
                            <option value="">@lang('All')</option>
                            <option value="0"
                                    @if(request()->status == '0') selected @endif>@lang('Pending')</option>
                            <option value="1"
                                    @if(request()->status == '1') selected @endif>@lang('Accepted')</option>
                            <option value="2"
                                    @if(request()->status == '2') selected @endif>@lang('Rejected')</option>
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

    @push('loadModal')
        {{--  Accept Offer modal --}}
        <div class="modal fade" id="accept-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">@lang('Accept Confirmation')</h5>
                        <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>@lang('Are you sure to Accept this?')</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2 btn-secondary close_invest_modal close__btn"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <form action="" method="get" class="deleteRoute">
                            @csrf
                            <button type="submit" class="btn-custom">@lang('Accept')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{--  Reject Offer modal --}}
        <div class="modal fade" id="reject-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">@lang('Reject Confirmation')</h5>
                        <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>@lang('Are you sure to reject this?')</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2 btn-secondary close_invest_modal close__btn"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <form action="" method="get" class="deleteRoute">
                            @csrf
                            <button type="submit" class="btn-custom">@lang('Reject')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{--  Remove Offer modal --}}
        <div class="modal fade" id="delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">@lang('Remove Confirmation')</h5>
                        <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>@lang('Are you sure to remove this?')</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2 btn-secondary close_invest_modal close__btn"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <form action="" method="post" class="deleteRoute">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn-custom">@lang('Remove')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endpush

@endsection

@push('script')
    <script>
        'use strict'
        $(document).ready(function () {

            $('.from_date').on('change', function () {
                $('.to_date').removeAttr('disabled');
            });
        });

        $('.notiflix-confirm').on('click', function () {
            var route = $(this).data('route');
            $('.deleteRoute').attr('action', route)
        })
    </script>
@endpush
