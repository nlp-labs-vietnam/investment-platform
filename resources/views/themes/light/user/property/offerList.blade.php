@extends(template().'layouts.user')
@section('title', trans('Offer List'))
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-datepicker.css') }}"/>
    @endpush
    <!-- Invest history -->
    <section class="transaction-history">
        <div class="container-fluid">
            <div class="row mt-4 mb-2">
                <div class="col ms-2">
                    <div class="header-text-full">
                        <h3 class="dashboard_breadcurmb_heading mb-1">@lang('Offer List')</h3>
                        <nav aria-label="breadcrumb" class="ms-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('user.dashboard') }}">
                                        @lang('Dashboard')
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">@lang('Offer List')</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- search area -->
            <div class="search-bar mt-3 me-2 ms-2 p-0">
                <form action="" method="get" enctype="multipart/form-data">
                    <div class="row g-3 align-items-end">
                        <div class="input-box col-lg-2">
                            <label for="">@lang('Property')</label>
                            <input
                                type="text"
                                name="property"
                                value="{{request()->property}}"
                                class="form-control"
                                placeholder="@lang('Search property')"/>
                        </div>

                        <div class="input-box col-lg-2">
                            <label for="">@lang('Sort By')</label>
                            <select class="form-select" name="sort_by" aria-label="Default select example">
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

                        <div class="input-box col-lg-2">
                            <label for="">@lang('Status')</label>
                            <select class="form-select" name="status" aria-label="Default select example">
                                <option value="">@lang('All')</option>
                                <option value="0"
                                        @if(request()->status == '0') selected @endif>@lang('Pending')</option>
                                <option value="1"
                                        @if(request()->status == '1') selected @endif>@lang('Accepted')</option>
                                <option value="2"
                                        @if(request()->status == '2') selected @endif>@lang('Rejected')</option>
                            </select>
                        </div>

                        <div class="input-box col-lg-2">
                            <label class="form-label" for="from_date">@lang('From Date')</label>
                            <input
                                type="text" class="form-control datepicker from_date" name="from_date"
                                value="{{ old('from_date',request()->from_date) }}" placeholder="@lang('From date')"
                                autocomplete="off" readonly/>
                        </div>

                        <div class="input-box col-lg-2">
                            <label for="to_date">@lang('To Date')</label>
                            <input
                                type="text" class="form-control datepicker to_date" name="to_date"
                                value="{{ old('to_date', request()->to_date) }}" placeholder="@lang('To date')"
                                autocomplete="off" readonly disabled="true"/>
                        </div>

                        <div class="input-box col-lg-2">
                            <button class="btn-custom w-100" type="submit"><i class="fal fa-search"></i>@lang('Search')
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-parent table-responsive me-2 ms-2 mt-4">
                <table class="table table-striped">
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
                            <td data-label="@lang('SL')">{{loopIndex($allOfferList) + $key}}</td>

                            <td class="company-logo" data-label="@lang('Property')">
                                <div>
                                    <a href="{{ route('property.details',[optional($offerList->property)->id, slug(optional($offerList->property)->title)]) }}"
                                       target="_blank">
                                        <img
                                            src="{{ getFile(optional($offerList->property)->driver, optional($offerList->property)->thumbnail) }}">
                                    </a>
                                </div>

                                <div>
                                    <a href="{{ route('property.details',[optional($offerList->property)->id, slug(optional($offerList->property)->title)]) }}"
                                       target="_blank">@lang(Str::limit(optional($offerList->property)->title, 30))</a>
                                    <br>
                                </div>
                            </td>

                            <td data-label="@lang('Offered From')">
                                <a href="{{ route('investor.profile', [optional($offerList->user)->id, slug(optional($offerList->user)->username)]) }}"
                                   target="_blank">
                                    <div class="d-flex no-block align-items-center">
                                        <div class="me-2">
                                            <img
                                                src="{{getFile(optional($offerList->user)->image_driver, optional($offerList->user)->image) }}"
                                                alt="user" class="rounded-circle" width="45" height="45">
                                        </div>
                                        <div class="">
                                            <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang(optional($offerList->user)->fullname)</h5>
                                            <span class="text-muted font-14 text-lowercase"><span></span>@lang(optional($offerList->user)->email)</span>
                                        </div>
                                    </div>
                                </a>
                            </td>

                            <td data-label="@lang('Sell Amount')">
                                {{ basicControl()->currency_symbol }}{{ $offerList->sell_amount }}
                            </td>

                            <td data-label="@lang('Offer Amount')">
                                {{ basicControl()->currency_symbol }}{{ $offerList->amount }}
                            </td>

                            <td data-label="@lang('Status')">
                                <span
                                    class="badge {{ ($offerList->status == 0) ? 'bg-warning' : (($offerList->status == 1) ? 'bg-success'  : 'bg-danger') }}">
                                    {{ ($offerList->status == 0) ? __('Pending') : (($offerList->status == 1) ? __('Accepted')  : __('Rejected')) }}
                                </span>
                            </td>


                            <td data-label="Action">
                                <div class="sidebar-dropdown-items">
                                    <button
                                        type="button"
                                        class="dropdown-toggle"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fal fa-cog"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        @if(optional($offerList->propertyShare)->status == 0 && $offerList->payment_status == 0)
                                            <li>
                                                <a class="dropdown-item btn disabled">
                                                    <i class="fal fa-shopping-cart"></i> @lang('Sold out')
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item btn notiflix-confirm"
                                                   data-bs-toggle="modal" data-bs-target="#accept-modal"
                                                   data-route="{{ route('user.offerAccept', $offerList->id) }}">
                                                    <i class="fal fa-check-circle"></i> @lang('Accept')
                                                </a>
                                            </li>
                                        @endif

                                        @if($offerList->lockInfo() && optional($offerList->offerlock)->status == 1 && $offerList->lockInfo()->status == 1)
                                            <li>
                                                <a href="{{ route('user.offerConversation', $offerList->id) }}"
                                                   class="dropdown-item"> <i
                                                        class="fal fa-envelope"></i> @lang('Conversation') </a>
                                            </li>
                                        @else

                                            <li>
                                                <a href="{{ route('user.offerConversation', $offerList->id) }}"
                                                   class="dropdown-item"> <i
                                                        class="fal fa-envelope"></i> @lang('Conversation') </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item btn notiflix-confirm"
                                                   data-bs-toggle="modal" data-bs-target="#reject-modal"
                                                   data-route="{{ route('user.offerReject', $offerList->id) }}">
                                                    <i class="fal fa-times-circle"></i> @lang('Reject')
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item btn notiflix-confirm"
                                                   data-bs-toggle="modal" data-bs-target="#delete-modal"
                                                   data-route="{{route('user.propertyOfferRemove', $offerList->id)}}">
                                                    <i class="fal fa-trash-alt"></i> @lang('Remove')
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
    </section>

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
