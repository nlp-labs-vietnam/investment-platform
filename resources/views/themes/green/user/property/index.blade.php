@extends(template().'layouts.user')
@section('title',trans('Property Market'))

@section('content')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item">@lang('Property Market')</li>

                <li class="breadcrumb-item active">
                    @lang('Investment Properties')
                </li>
            </ol>
        </nav>
    </div>

    <!-- Cmn tabs start -->
    <div class="cmn-tabs">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{($type == 'investment-properties') ? 'active':''}}"
                        id="Investment-Properties-plan-tab" data-bs-toggle="pill"
                        data-bs-target="#Investment-Properties-plan" type="button" role="tab"
                        aria-controls="Investment-Properties-plan" aria-selected="false">
                    @lang('Investment Properties')
                </button>
            </li>

            @if(basicControl()->is_share_investment == 1)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($type == 'property-share-market') ? 'active':''}}"
                            id="Share-Market-plan-tab" data-bs-toggle="pill"
                            data-bs-target="#Share-Market-plan" type="button" role="tab"
                            aria-controls="Share-Market-plan" aria-selected="false">
                        @lang('Share Market')
                    </button>
                </li>
            @endif

            <li class="nav-item" role="presentation">
                <button class="nav-link {{($type == 'my-investment-properties') ? 'active':''}}"
                        id="my-property-plan-tab" data-bs-toggle="pill"
                        data-bs-target="#my-property-plan" type="button" role="tab"
                        aria-controls="my-property-plan" aria-selected="false">
                    @lang('My Properties')
                </button>
            </li>

            @if(basicControl()->is_share_investment == 1)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($type == 'my-shared-properties') ? 'active':''}}"
                            id="my-shared-property-plan-tab" data-bs-toggle="pill"
                            data-bs-target="#my-shared-property-plan" type="button" role="tab"
                            aria-controls="my-shared-property-plan" aria-selected="false">
                        @lang('My Shared Properties')
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($type == 'my-offered-properties') ? 'active':''}}"
                            id="send-offer-plan-tab" data-bs-toggle="pill"
                            data-bs-target="#send-offer-plan" type="button" role="tab"
                            aria-controls="send-offer-plan" aria-selected="false">
                        @lang('Send Offer')
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($type == 'receive-offered-properties') ? 'active':''}}"
                            id="receive-offer-plan-tab" data-bs-toggle="pill"
                            data-bs-target="#receive-offer-plan" type="button" role="tab"
                            aria-controls="receive-offer-plan" aria-selected="false">
                        @lang('Receive Offer')
                    </button>
                </li>

            @endif
        </ul>
    </div>
    <!-- Cmn tabs end -->

    <!-- Cmn tab content start -->
    <div class="tab-content mt-30" id="pills-tabContent">
        <div class="tab-pane fade {{($type == 'investment-properties') ? 'show active':''}}"
             id="Investment-Properties-plan" role="tabpanel"
             aria-labelledby="Investment-Properties-plan-tab" tabindex="0">
            @include(template().'user.property.allProperty')
        </div>

        @if(basicControl()->is_share_investment == 1)
            <div class="tab-pane fade {{($type == 'property-share-market') ? 'show active':''}}" id="Share-Market-plan"
                 role="tabpanel"
                 aria-labelledby="Share-Market-plan-tab" tabindex="0">
                @include(template().'user.property.shareProperty')
            </div>
        @endif

        <div class="tab-pane fade {{($type == 'my-investment-properties') ? 'show active':''}}" id="my-property-plan"
             role="tabpanel"
             aria-labelledby="my-property-plan-tab" tabindex="0">
            @include(template().'user.property.myProperty')
        </div>

        @if(basicControl()->is_share_investment == 1)
            <div class="tab-pane fade {{($type == 'my-shared-properties') ? 'show active':''}}"
                 id="my-shared-property-plan" role="tabpanel"
                 aria-labelledby="my-shared-property-plan-tab" tabindex="0">
                @include(template().'user.property.myShareProperty')
            </div>

            <div class="tab-pane fade {{($type == 'my-offered-properties') ? 'show active':''}}" id="send-offer-plan"
                 role="tabpanel"
                 aria-labelledby="send-offer-plan-tab" tabindex="0">
                <div class="row g-4">
                    @include(template().'user.property.myOfferProperty')
                </div>
            </div>

            <div class="tab-pane fade {{($type == 'receive-offered-properties') ? 'show active':''}}" id="receive-offer-plan"
                 role="tabpanel"
                 aria-labelledby="receive-offer-plan-tab" tabindex="0">
                <div class="row g-4">
                    @include(template().'user.property.receiveOfferProperty')
                </div>
            </div>
        @endif
    </div>
    <!-- Cmn tab content end -->

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
