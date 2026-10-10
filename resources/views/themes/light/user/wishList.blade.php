@extends(template().'layouts.user')
@section('title',trans('All WishList'))

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-datepicker.css') }}"/>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">@lang('My WishList')</h3>
                    <button type="button" class="btn-custom" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">@lang('Filter')
                        <i class="fa-regular fa-filter"></i>
                    </button>
                </div>

                <!-- table -->
                <div class="table-parent table-responsive wishlistTable">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">@lang('property')</th>
                                <th scope="col">@lang('Data-Time')</th>
                                <th scope="col" class="text-end">@lang('Action')</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse ($favourite_properties as $key => $wishlist)
                            <tr>
                                <td class="company-logo" data-label="@lang('Property')">
                                    <div>
                                        <a href="{{ route('property.details',[optional($wishlist->get_property)->id, @slug(optional($wishlist->get_property)->title)]) }}"
                                           target="_blank">
                                            <img src="{{ getFile(optional($wishlist->get_property)->driver, optional($wishlist->get_property)->thumbnail) }}">
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('property.details',[optional($wishlist->get_property)->id, @slug(optional($wishlist->get_property)->title)]) }}"
                                           target="_blank">@lang(\Illuminate\Support\Str::limit(optional($wishlist->get_property)->title, 30))</a>
                                        <br>
                                    </div>
                                </td>


                                <td data-label="Date-Time">{{ dateTime($wishlist->created_at) }}</td>

                                <td data-label="Action" class="action d-flex justify-content-end">
                                    <button class="action-btn notiflix-confirm" data-bs-toggle="modal"
                                            data-bs-target="#delete-modal"
                                            data-route="{{ route('user.wishListDelete', $wishlist->id) }}">
                                        <i class="fa fa-trash font-14"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <td class="text-center" colspan="100%"> @lang('No Data Found')</td>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $favourite_properties->appends($_GET)->links() }}
                </div>
            </div>
        </div>

        <!-- Offcanvas sidebar start -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">@lang('Filter Wish List')</h5>
                <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="fa-light fa-arrow-right"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <form action="" method="get" enctype="multipart/form-data">
                    <div class="row g-4">
                        <div>
                            <label for="name" class="form-label">@lang('Property')</label>
                            <input type="text" name="name"
                                   value="{{ old('name',request()->name) }}"
                                   class="form-control"
                                   placeholder="@lang('Search property')"/>
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

        @push('loadModal')
            <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-top modal-md">
                    <div class="modal-content">
                        <div class="modal-header modal-primary modal-header-custom">
                            <h4 class="modal-title" id="editModalLabel">@lang('Delete Confirmation')</h4>
                            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            @lang('Are you sure delete?')
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-custom btn2 btn-secondary close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                            <form action="" method="post" class="deleteRoute">
                                @csrf
                                @method('delete')
                                <button type="submit"
                                        class="btn btn-sm btn-custom addCreateListingRoute text-white">@lang('Confirm')</button>
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

                    $('.notiflix-confirm').on('click', function () {
                        var route = $(this).data('route');
                        $('.deleteRoute').attr('action', route)
                    })
                });
            </script>
    @endpush
