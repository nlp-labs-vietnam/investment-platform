@extends(template().'layouts.user')
@section('title',trans('Property Market'))

@section('content')
    <!-- Property Market -->
    <div class="container-fluid shop-section py-0">
        <div class="main row">

            <div class="col">
                <div class="header-text-full">
                    <h3 class="dashboard_breadcurmb_heading mb-1">@lang('Property Market')</h3>

                    <ul class="nav nav-pills my-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-custom {{($type == 'investment-properties') ? 'active':''}}"
                                    id="pills-all-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all"
                                    aria-selected="true">@lang('Investment Properties')</button>
                        </li>
                        @if(basicControl()->is_share_investment == 1)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link btn-custom {{($type == 'property-share-market') ? 'active':''}}"
                                        id="pills-allshare-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-allshare" type="button" role="tab"
                                        aria-controls="pills-allshare"
                                        aria-selected="false">@lang('Share Market')</button>
                            </li>
                        @endif

                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link btn-custom {{($type == 'my-investment-properties') ? 'active':''}}"
                                id="pills-myproperty-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-myproperty" type="button" role="tab"
                                aria-controls="pills-myproperty"
                                aria-selected="false">@lang('My Properties')</button>
                        </li>

                        @if(basicControl()->is_share_investment == 1)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link btn-custom {{($type == 'my-shared-properties') ? 'active':''}}"
                                        id="pills-myshareproperty-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-myshareproperty" type="button" role="tab"
                                        aria-controls="pills-myshareproperty"
                                        aria-selected="false">@lang('My Shared Properties')</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link btn-custom {{($type == 'my-offered-properties') ? 'active':''}}"
                                        id="pills-myofferproperty-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-myofferproperty" type="button" role="tab"
                                        aria-controls="pills-myofferproperty"
                                        aria-selected="false">@lang('Send Offer')</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link btn-custom {{($type == 'receive-offered-properties') ? 'active':''}}"
                                    id="pills-receiveofferedproperties-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-receiveofferedproperties" type="button" role="tab"
                                    aria-controls="pills-receiveofferedproperties"
                                    aria-selected="false">@lang('Receive Offer')
                                </button>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>


            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade {{($type == 'investment-properties') ? 'show active':''}}" id="pills-all"
                     role="tabpanel" aria-labelledby="pills-all-tab"
                     tabindex="0">
                    @include(template().'user.property.allProperty')
                </div>

                @if(basicControl()->is_share_investment == 1)
                    <div class="tab-pane fade {{($type == 'property-share-market') ? 'show active':''}}"
                         id="pills-allshare"
                         role="tabpanel"
                         aria-labelledby="pills-allshare-tab"
                         tabindex="0">
                        @include(template().'user.property.shareProperty')
                    </div>
                @endif

                <div class="tab-pane fade {{($type == 'my-investment-properties') ? 'show active':''}}"
                     id="pills-myproperty" role="tabpanel" aria-labelledby="pills-myproperty-tab"
                     tabindex="0">
                    @include(template().'user.property.myProperty')
                </div>

                @if(basicControl()->is_share_investment == 1)
                    <div class="tab-pane fade {{($type == 'my-shared-properties') ? 'show active':''}}"
                         id="pills-myshareproperty" role="tabpanel"
                         aria-labelledby="pills-myshareproperty-tab" tabindex="0">
                        @include(template().'user.property.myShareProperty')
                    </div>

                    <div class="tab-pane fade {{($type == 'my-offered-properties') ? 'show active':''}}"
                         id="pills-myofferproperty" role="tabpanel"
                         aria-labelledby="pills-myofferproperty-tab" tabindex="0">
                        @include(template().'user.property.myOfferProperty')
                    </div>

                    <div class="tab-pane fade {{($type == 'receive-offered-properties') ? 'show active':''}}"
                         id="pills-receiveofferedproperties" role="tabpanel"
                         aria-labelledby="pills-receiveofferedproperties-tab" tabindex="0">
                        @include(template().'user.property.receiveOfferProperty')
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('loadModal')
        {{--  Send offer modal --}}
        <div class="modal fade" id="sendOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <form action="" method="post" id="invest-form" class="login-form send_offer_modal">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Set Share Amount')</h5>
                            <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="card">
                                <div class="m-3 mb-0 payment-method-details property-title font-weight-bold">
                                </div>

                                <div class="card-body">
                                    <div class="row g-3 investModalPaymentForm">
                                        <div class="input-box col-12">
                                            <input type="hidden" name="investment_id" class="investment_id">
                                            <label for="">@lang('Share Amount')</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    class="invest-amount form-control @error('amount') is-invalid @enderror"
                                                    name="amount" id="amount"
                                                    value="{{old('amount')}}"
                                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                    autocomplete="off"
                                                    placeholder="@lang('Enter amount')" required>
                                                <button class="show-currency" type="button"></button>
                                            </div>
                                            @error('amount')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-custom btn2 btn-secondary close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn-custom investModalSubmitBtn">@lang('Share')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Update Share modal --}}
        <div class="modal fade" id="updateShareModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <form action="" method="post" id="invest-form" class="login-form update_share">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Update Share Amount')</h5>
                            <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="card">
                                <div class="m-3 mb-0 payment-method-details property-title font-weight-bold"></div>
                                <div class="card-body">
                                    <div class="row g-3 investModalPaymentForm">
                                        <div class="input-box col-12">
                                            <label for="">@lang('Share Amount')</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    class="invest-amount form-control amount @error('amount') is-invalid @enderror"
                                                    name="amount" id="amount"
                                                    value="{{old('amount')}}"
                                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                    autocomplete="off"
                                                    placeholder="@lang('Enter amount')" required>
                                                <button class="show-currency" type="button"></button>
                                            </div>
                                            @error('amount')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-custom btn2 btn-secondary close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn-custom investModalSubmitBtn">@lang('Share')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Update Offer modal --}}
        <div class="modal fade" id="updateOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <form action="" method="post" id="invest-form"
                      class="login-form update_offer_form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Property Offer Details')</h5>
                            <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="card">
                                <div class="m-3 mb-0 payment-method-details property-title font-weight-bold">
                                </div>

                                <div class="card-body">
                                    <div class="row g-3 investModalPaymentForm">
                                        <div class="input-box col-12">
                                            <label for="">@lang('Property Owner')</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    class="form-control property_owner"
                                                    value=""
                                                    autocomplete="off"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="input-box col-12">
                                            <label for="">@lang('Offer Amount')</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    class="invest-amount amount form-control @error('amount') is-invalid @enderror"
                                                    name="amount"
                                                    value=""
                                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                    autocomplete="off"
                                                    placeholder="@lang('Enter amount')" required>
                                                <button class="show-currency" type="button"></button>
                                            </div>
                                            @error('amount')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="input-box col-12">
                                            <label for="">@lang('Description')</label>
                                            <div class="input-group">
                                                <textarea name="description"
                                                          class="form-control details @error('description') is-invalid @enderror"
                                                          cols="10" rows="3"></textarea>
                                            </div>
                                            @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-custom btn2 btn-secondary close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn-custom investModalSubmitBtn">@lang('Update Offer')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Make offer modal --}}
        <div class="modal fade" id="makeOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <form action="" method="post" id="invest-form"
                      class="login-form make_offer_form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Make Offer Details')</h5>
                            <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="card">
                                <div class="m-3 mb-0 payment-method-details property-title font-weight-bold">
                                </div>

                                <div class="card-body">
                                    <div class="row g-3 investModalPaymentForm">
                                        <div class="input-box col-12">
                                            <label for="">@lang('Property Owner')</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    class="form-control property_owner"
                                                    name="property_owner" id="property_owner"
                                                    value=""
                                                    autocomplete="off"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="input-box col-12">
                                            <label for="">@lang('Offer Amount')</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    class="invest-amount form-control @error('amount') is-invalid @enderror"
                                                    name="amount" id="amount"
                                                    value="{{old('amount')}}"
                                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                    autocomplete="off"
                                                    placeholder="@lang('Enter amount')" required>
                                                <button class="show-currency" type="button"></button>
                                            </div>
                                            @error('amount')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="input-box col-12">
                                            <label for="">@lang('Description')</label>
                                            <div class="input-group">
                                                <textarea name="description"
                                                          class="form-control @error('description') is-invalid @enderror"
                                                          id="description" cols="10" rows="3"></textarea>
                                            </div>
                                            @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-custom btn2 btn-secondary close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn-custom investModalSubmitBtn">@lang('Send Offer')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @endpush
@endsection

@push('script')
    <script>
        "use strict";

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

        var isAuthenticate = '{{ Auth::check() }}';

        $(document).ready(function () {
            $('.wishList').on('click', function () {
                var _this = this.id;
                let property_id = $(this).data('property');
                if (isAuthenticate == 1) {
                    wishList(property_id, _this);
                } else {
                    window.location.href = '{{route('login')}}';
                }
            });
        });

        function wishList(property_id = null, id = null) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('user.wishList') }}",
                type: "POST",
                data: {
                    property_id: property_id,
                },
                success: function (data) {
                    if (data.data == 'added') {
                        $(`.save${id}`).removeClass("fal fa-heart");
                        $(`.save${id}`).addClass("fas fa-heart");
                        Notiflix.Notify.success("Wishlist added");
                    }
                    if (data.data == 'remove') {
                        $(`.save${id}`).removeClass("fas fa-heart");
                        $(`.save${id}`).addClass("fal fa-heart");
                        Notiflix.Notify.success("Wishlist removed");
                    }
                },
            });
        }

        $(document).on('click', '.sendOffer', function () {
            var propertysendOfferModal = new bootstrap.Modal(document.getElementById('sendOfferModal'))
            propertysendOfferModal.show();

            let dataRoute = $(this).data('route');
            let dataProperty = $(this).data('property');

            $('.send_offer_modal').attr('action', dataRoute);
            $('.property-title').text(`Property: ${dataProperty}`);
            $('.show-currency').text("{{basicControl()->base_currency}}");
        });

        $(document).on('click', '.updateShare', function () {
            var propertyUpdateShareModal = new bootstrap.Modal(document.getElementById('updateShareModal'))
            propertyUpdateShareModal.show();

            let dataRoute = $(this).data('route');
            let dataProperty = $(this).data('property');
            let amount = $(this).data('amount');

            $('.update_share').attr('action', dataRoute);
            $('.property-title').text(`Property: ${dataProperty}`);
            $('.amount').val(amount);
            $('.show-currency').text("{{basicControl()->base_currency}}");
        });

        $(document).on('click', '.updateOffer', function () {
            var propertyUpdateOfferModal = new bootstrap.Modal(document.getElementById('updateOfferModal'))
            propertyUpdateOfferModal.show();

            let dataRoute = $(this).data('route');
            let dataProperty = $(this).data('property');
            let dataPropertyOwner = $(this).data('owner');
            let amount = $(this).data('amount');
            let details = $(this).data('details');

            $('.update_offer_form').attr('action', dataRoute);
            $('.property_owner').val(dataPropertyOwner);
            $('.property-title').text(`Property: ${dataProperty}`);
            $('.details').text(details);
            $('.amount').val(amount);
            $('.show-currency').text("{{basicControl()->base_currency}}");
        });

        $(document).on('click', '.makeOffer', function () {

            var propertymakeOfferModal = new bootstrap.Modal(document.getElementById('makeOfferModal'))
            propertymakeOfferModal.show();

            let dataRoute = $(this).data('route');
            $('.make_offer_form').attr('action', dataRoute);
            let dataPropertyOwner = $(this).data('propertyowner');
            let dataProperty = $(this).data('property');

            $('.property_owner').val(dataPropertyOwner);
            $('.property-title').text(`Property: ${dataProperty}`);
            $('.show-currency').text("{{basicControl()->base_currency}}");

        });

    </script>
@endpush
