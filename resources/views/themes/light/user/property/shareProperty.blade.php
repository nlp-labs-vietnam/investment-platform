@if(count($sharedProperties) > 0)
    <div class="col-lg-12">
        <div class="row g-4 mb-5">
            @foreach($sharedProperties as $key => $shareProperty)
                <div class="col-md-4 col-lg-4">
                    <div class="property-box">
                        <div class="img-box">
                            <img class="img-fluid"
                                 src="{{ getFile(optional($shareProperty->property)->driver, optional($shareProperty->property)->thumbnail) }}"
                                 alt="@lang('property thumbnail')"/>
                            <div class="content">
                                <div class="tag">@lang('Buy')</div>
                                <h4 class="price">{{ currencyPosition($shareProperty->amount) }}</h4>
                            </div>
                        </div>

                        <div class="text-box">
                            <div class="review-header d-flex justify-content-between">
                                <div class="review">
                                    @include(template().'partials.propertyReview',['property' => $shareProperty?->property])
                                </div>
                                <div class="owner">
                                    @lang('Owner: ') @lang(optional($shareProperty->user)->fullname)
                                </div>
                            </div>

                            <a class="title"
                               href="{{ route('property.details',[optional($shareProperty->property)->id, @slug(optional($shareProperty->property)->title)]) }}">{{ \Illuminate\Support\Str::limit(optional($shareProperty->property)->title, 30)  }}</a>
                            <p class="address">
                                <i class="fas fa-map-marker-alt"></i>
                                @lang(optional(optional($shareProperty->property)->address)->title)
                            </p>

                            <div class="aminities">
                                @foreach(optional($shareProperty->property)->limitamenity as $key => $amenity)
                                    <span><i class="{{ $amenity->icon }}"></i>{{ $amenity->title  }}</span>
                                @endforeach
                            </div>


                            <div class="invest-btns d-flex justify-content-between">
                                <div class="sidebar-dropdown-items">
                                    <button
                                        type="button"
                                        class="dropdown-toggle"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fal fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        @if($shareProperty->propertyOffer)
                                            <li>
                                                <a class="dropdown-item btn disabled">
                                                    <i class="fal fa-check-circle"></i> @lang('Already Offered')
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item btn makeOffer {{ optional($shareProperty->user)->id == Auth::id() ? 'disabled' : '' }}"
                                                   data-route="{{route('user.propertyMakeOfferStore', $shareProperty->id)}}"
                                                   data-propertyowner="{{ optional($shareProperty->user)->fullname }}"
                                                   data-property="{{ optional($shareProperty->property)->title }}">
                                                    <i class="fal fa-paper-plane"></i> @lang('Make Offer')
                                                </a>
                                            </li>
                                        @endif

                                        @if(($shareProperty->propertyOffer && optional($shareProperty->propertyOffer)->offerlock) && (optional(optional($shareProperty->propertyOffer)->offerlock)->status == 0) || $shareProperty->forAllLock)
                                            <li>
                                                <a class="dropdown-item btn disabled">
                                                    <i class="fal fa-lock"></i> @lang('Share Locked')
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item btn buyShare directBuyShare {{ optional($shareProperty->user)->id == Auth::id() ? 'disabled' : '' }}"
                                                   data-route="{{route('user.directBuyShare', $shareProperty->id)}}"
                                                   data-payableamount="{{ $shareProperty->amount }}"
                                                   data-propertyowner="{{ optional($shareProperty->user)->fullname }}"
                                                   data-property="{{ optional($shareProperty->property)->title }}">
                                                    <i class="far fa-sack-dollar"></i> @lang('Direct Buy Share')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <a href="{{ route('investor.profile', [optional($shareProperty->user)->id, @slug(optional($shareProperty->user)->username)]) }}"
                                   target="_blank">
                                    @lang('Contact Owner')
                                </a>
                            </div>


                            <div class="plan d-flex justify-content-between">
                                <div>
                                    @if(optional($shareProperty->property)->profit_type == 1)
                                        <h5>{{ fractionNumber(optional($shareProperty->property)->profit) }}% (@lang('Fixed'))</h5>
                                    @else
                                        <h5>{{ currencyPosition(optional($shareProperty->property)->profit) }}
                                            (@lang('Fixed'))
                                        </h5>
                                    @endif
                                    <span>@lang('Profit Range')</span>
                                </div>
                                <div>
                                    @if(optional($shareProperty->property)->is_return_type == 1)
                                        <h5>@lang('Lifetime')</h5>
                                    @else
                                        <h5>{{ optional($shareProperty->property->managetime)->time }} @lang(optional($shareProperty->property->managetime)->time_type)</h5>
                                    @endif
                                    <span>@lang('Return Interval')</span>
                                </div>
                                <div>
                                    <h5>{{ optional($shareProperty->property)->is_capital_back == 1 ? 'Yes' : 'No' }}</h5>
                                    <span>@lang('Capital back')</span>
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
    <div class="custom-not-found mt-5">
        <img src="{{ asset(template(true).'img/no_data_found.png') }}" alt="@lang('not found')" class="img-fluid">
    </div>
@endif

@push('loadModal')
    {{--  Direct Buy share modal --}}
    <div class="modal fade" id="directBuyShareModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form action="" method="post" id="invest-form"
                  class="login-form direct_share_payment_form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">@lang('Buy Share')</h5>
                        <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="card">
                            <div class="m-3 mb-0 payment-method-details property_title font-weight-bold">
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
                                        <label for="">@lang('Select Wallet')</label>
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

                                    <div class="input-box col-12">
                                        <label for="">@lang('Payable Amount')</label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="invest-amount payable_amount form-control @error('amount') is-invalid @enderror"
                                                name="amount" id="payable_amount"
                                                value="{{old('amount')}}"
                                                onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                autocomplete="off"
                                                placeholder="@lang('Enter amount')" required readonly>
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
                        <button type="submit" class="btn-custom">@lang('Pay Now')</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endpush

@push('script')
    <script>
        'use strict'
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
