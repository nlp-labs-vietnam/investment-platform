@if(count($sharedProperties) > 0)
    <div class="col-lg-12">
        <div class="row g-4">
            @foreach($sharedProperties as $key => $shareProperty)
                <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="900">
                    <div class="property-box">
                        <div class="img-box">
                            <a href="{{ route('property.details',[$shareProperty->id, slug($shareProperty->property->title)]) }}">
                                <img
                                    src="{{ getFile(optional($shareProperty->property)->driver, optional($shareProperty->property)->thumbnail) }}">
                            </a>
                            <div class="tag-box">
                                <div class="single-tag">@lang('Buy')</div>
                            </div>
                        </div>
                        <div class="text-box">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0 contact-item align-items-center gap-1"><i
                                        class="fa-regular fa-location-dot"></i> @lang(optional(optional($shareProperty->property)->address)->title)
                                </p>
                                <h4 class="mb-0">{{ currencyPosition($shareProperty->amount) }}</h4>
                            </div>
                            <a class="title"
                               href="{{ route('property.details',[optional($shareProperty->property)->id, @slug(optional($shareProperty->property)->title)]) }}">{{ \Illuminate\Support\Str::limit(optional($shareProperty->property)->title, 30)  }}</a>

                            <div class="meta-data mt-10">
                                <div class="review">
                                    <ul class="reviews d-flex align-items-center gap-2">
                                        @include(template().'partials.propertyReview',['property' => $shareProperty?->property])
                                    </ul>
                                </div>
                            </div>

                            <div class="amenities mt-15">
                                @foreach(optional($shareProperty->property)->limitamenity as $key => $amenity)
                                    <div class="item">
                                        <i class="{{ $amenity->icon }}"></i>
                                        {{ $amenity->title  }}
                                    </div>
                                @endforeach
                            </div>

                            <hr class="cmn-hr2">

                            <div class="bottom-info">
                                <div class="plan-box">
                                    <div class="item">
                                        @if(optional($shareProperty->property)->profit_type == 1)
                                            <div
                                                class="plan-title">{{ fractionNumber(getAmount(optional($shareProperty->property)->profit)) }}
                                                %
                                                (@lang('Fixed'))
                                            </div>
                                        @else
                                            <div
                                                class="plan-title">{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount(optional($shareProperty->property)->profit)) }}
                                                (@lang('Fixed'))
                                            </div>
                                        @endif
                                        <p class="mb-0">@lang('Profit Range')</p>
                                    </div>

                                    <div class="item">
                                        @if(optional($shareProperty->property)->is_return_type == 1)
                                            <div class="plan-title">@lang('Lifetime')</div>
                                        @else
                                            <div
                                                class="plan-title">{{ optional(optional($shareProperty->property)->managetime)->time }} @lang(optional(optional($shareProperty->property)->managetime)->time_type)</div>
                                        @endif
                                        <p class="mb-0">@lang('Return Interval')</p>
                                    </div>

                                    <div class="item">
                                        <div
                                            class="plan-title">{{ optional($shareProperty->property)->is_capital_back == 1 ? 'Yes' : 'No' }}</div>
                                        <p class="mb-0">@lang('Capital back')</p>
                                    </div>
                                </div>

                                <div class="mt-15 d-flex justify-content-between align-items-center">
                                    <div class="dropdown">
                                        <button class="single-btn" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fa-regular fa-gear"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($shareProperty->propertyOffer)
                                                <li>
                                                    <button class="dropdown-item btn disabled" href="javascript:void(0)"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal"><i
                                                            class="fa-regular fa-paper-plane"></i>
                                                        @lang('Already Offered')
                                                    </button>
                                                </li>
                                            @else
                                                <li>
                                                    <button
                                                        class="dropdown-item makeOffer btn {{ optional($shareProperty->user)->id == Auth::id() ? 'disabled' : '' }}"
                                                        data-route="{{route('user.propertyMakeOfferStore', $shareProperty->id)}}"
                                                        data-propertyowner="{{ optional($shareProperty->user)->fullname }}"
                                                        data-property="{{ optional($shareProperty->property)->title }}">
                                                        <i class="fa-regular fa-paper-plane"></i>
                                                        @lang('Make Offer')
                                                    </button>
                                                </li>
                                            @endif

                                            @if(($shareProperty->propertyOffer && optional($shareProperty->propertyOffer)->offerlock) && (optional(optional($shareProperty->propertyOffer)->offerlock)->status == 0) || $shareProperty->forAllLock)
                                                <li>
                                                    <button class="dropdown-item btn disabled"
                                                            href="javascript:void(0)">
                                                        <i class="fa-regular fa-envelope"></i>@lang('Share Locked')
                                                    </button>
                                                </li>
                                            @else
                                                <li>
                                                    <button
                                                        class="dropdown-item btn buyShare directBuyShare {{ optional($shareProperty->user)->id == Auth::id() ? 'disabled' : '' }}"
                                                        data-route="{{route('user.directBuyShare', $shareProperty->id)}}"
                                                        data-payableamount="{{ $shareProperty->amount }}"
                                                        data-propertyowner="{{ optional($shareProperty->user)->fullname }}"
                                                        data-route="{{route('user.propertyMakeOfferStore', $shareProperty->id)}}"
                                                        data-property="{{ optional($shareProperty->property)->title }}">
                                                        <i class="fa-regular fa-paper-plane"></i>
                                                        @lang('Direct Buy Share')
                                                    </button>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                    <a href="{{ route('investor.profile', [optional($shareProperty->user)->id, @slug(optional($shareProperty->user)->username)]) }}"
                                       class="contact-owner-btn">@lang('Contact Owner')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{ $sharedProperties->appends($_GET)->links() }}
            </ul>
        </nav>
    </div>
@else
    <div class="card">
        <div class="car-body">
            <div class="text-center p-4">
                <img class="dataTables-image mb-3 empty-state-img"
                     src="{{ asset(template(true).'img/oc-error.svg') }}"
                     alt="Image Description" data-hs-theme-appearance="default">
                <p class="mb-0">@lang('No data to show')</p>
            </div>
        </div>
    </div>
@endif


@push('loadModal')
    {{--  Make offer modal --}}
    <div class="modal fade" id="makeOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form action="" method="post" class="make_offer_form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">@lang('Make Offer')</h4>
                        <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-light fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="property-title"></h5>
                        <div id="formModal" class="investModalPaymentForm mt-3">
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

                        <div id="formModal" class="investModalPaymentForm mt-3">
                            <label for="">@lang('Amount')</label>
                            <div class="input-group">
                                <input type="text" class="invest-amount form-control" name="amount"
                                       id="amount"
                                       value="{{old('amount')}}"
                                       onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                       autocomplete="off"
                                       placeholder="@lang('Enter amount')">
                                <span class="input-group-text show-currency" id="basic-addon2"></span>
                            </div>
                            @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="formModal" class="investModalPaymentForm mt-3">
                            <label for="">@lang('Details')</label>
                            <div class="input-group">
                                <textarea name="description" class="form-control " id="description" cols="10"
                                          rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="cmn-btn2" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="cmn-btn">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{--  Direct Buy share modal --}}
    <div class="modal fade" id="directBuyShareModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form action="" method="post" class="direct_share_payment_form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">@lang('Buy Share')</h4>
                        <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-light fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="property_title"></h5>
                        <div id="formModal" class="investModalPaymentForm mt-3">
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


                        <div id="formModal" class="investModalPaymentForm mt-3">
                            <label for="">@lang('Select Wallet')</label>
                            <div class="input-box col-12">
                                <select class="form-control form-select" id="exampleFormControlSelect1"
                                        name="balance_type">
                                    @auth
                                        <option
                                            value="balance">@lang('Deposit Balance - '.basicControl()->currency_symbol.getAmount(auth()->user()->balance))</option>
                                        <option
                                            value="interest_balance">@lang('Interest Balance -'.basicControl()->currency_symbol.getAmount(auth()->user()->interest_balance))</option>
                                    @endauth
                                </select>
                            </div>
                        </div>

                        <div id="formModal" class="investModalPaymentForm mt-3">
                            <label for="">@lang('Payable Amount')</label>
                            <div class="input-group">
                                <input type="text"
                                       class="payable_amount form-control @error('amount') is-invalid @enderror"
                                       name="amount"
                                       id="amount"
                                       value="{{old('amount')}}"
                                       onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                       autocomplete="off"
                                       placeholder="@lang('Enter amount')" readonly>
                                <span class="input-group-text show-currency" id="basic-addon2"></span>
                            </div>
                            @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>

                    <div class="modal-footer">
                        <button type="button" class="cmn-btn2" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="cmn-btn">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('script')
    <script>
        'use strict'

        $(document).on('click', '.makeOffer', function () {

            var propertymakeOfferModal = new bootstrap.Modal(document.getElementById('makeOfferModal'))
            propertymakeOfferModal.show();

            let dataRoute = $(this).data('route');
            $('.make_offer_form').attr('action', dataRoute);
            let dataPropertyOwner = $(this).data('propertyowner');
            let dataProperty = $(this).data('property');

            $('.property_owner').val(dataPropertyOwner);
            $('.property-title').text(`Property: ${dataProperty}`);
            $('.show-currency').text("{{ basicControl()->base_currency }}");
        });

        $(document).on('click', '.directBuyShare', function () {
            var directBuyShare = new bootstrap.Modal(document.getElementById('directBuyShareModal'))
            directBuyShare.show();

            let dataRoute = $(this).data('route');
            let payableAmount = $(this).data('payableamount');
            let dataPropertyOwner = $(this).data('propertyowner');
            let dataProperty = $(this).data('property');

            $('.payable_amount').val(payableAmount);
            $('.property_owner').val(dataPropertyOwner);
            $('.property_title').text(`Property: ${dataProperty}`);
            $('.direct_share_payment_form').attr('action', dataRoute);
            $('.show-currency').text("{{ basicControl()->base_currency }}");
        });
    </script>
@endpush
