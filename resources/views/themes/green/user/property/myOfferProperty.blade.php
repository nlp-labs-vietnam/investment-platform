@if(count($myOfferedProperties) > 0)
    <div class="col-lg-12">
        <div class="row g-4 mb-5">
            @foreach($myOfferedProperties as $key => $offerProperty)
                <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="900">
                    <div class="property-box">
                        <div class="img-box">
                            <a href="{{ route('property.details',[$offerProperty->id, slug($offerProperty->property->title)]) }}">
                                <img
                                    src="{{ getFile(optional($offerProperty->property)->driver, optional($offerProperty->property)->thumbnail) }}">
                            </a>
                            <div class="tag-box">
                                @if($offerProperty->status == 0 && $offerProperty->payment_status == 0)
                                    <div class="single-tag">@lang('Offer Pending')</div>
                                @elseif($offerProperty->status == 1 && $offerProperty->payment_status == 0)
                                    <div class="single-tag">@lang('Offer Accepted')</div>
                                @elseif($offerProperty->status == 2 && $offerProperty->payment_status == 0)
                                    <div class="single-tag">@lang('Offer Rejected')</div>
                                @elseif($offerProperty->status == 1 && $offerProperty->payment_status == 1)
                                    <div class="single-tag">@lang('Offer Completed')</div>
                                @endif
                            </div>
                        </div>

                        <div class="text-box">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0 contact-item align-items-center gap-1"><i
                                        class="fa-regular fa-location-dot"></i> @lang(optional(optional($offerProperty->property)->address)->title)
                                </p>
                                <h4 class="mb-0">{{ currencyPosition($offerProperty->amount) }}</h4>
                            </div>
                            <a class="title"
                               href="{{ route('property.details',[optional($offerProperty->property)->id, @slug(optional($offerProperty->property)->title)]) }}">{{ \Illuminate\Support\Str::limit(optional($offerProperty->property)->title, 30)  }}</a>

                            <div class="meta-data mt-10">
                                <div class="review">
                                    <ul class="reviews d-flex align-items-center gap-2">
                                        @include(template().'partials.propertyReview',['property' => $offerProperty?->property])
                                    </ul>
                                </div>
                            </div>

                            <div class="amenities mt-15">
                                @foreach(optional($offerProperty->property)->limitamenity as $key => $amenity)
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
                                        @if(optional($offerProperty->property)->profit_type == 1)
                                            <div
                                                class="plan-title">{{ fractionNumber(getAmount(optional($offerProperty->property)->profit)) }}
                                                %
                                                (@lang('Fixed'))
                                            </div>
                                        @else
                                            <div
                                                class="plan-title">{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount(optional($offerProperty->property)->profit)) }}
                                                (@lang('Fixed'))
                                            </div>
                                        @endif
                                        <p class="mb-0">@lang('Profit Range')</p>
                                    </div>

                                    <div class="item">
                                        @if(optional($offerProperty->property)->is_return_type == 1)
                                            <div class="plan-title">@lang('Lifetime')</div>
                                        @else
                                            <div
                                                class="plan-title">{{ optional(optional($offerProperty->property)->managetime)->time }} @lang(optional(optional($offerProperty->property)->managetime)->time_type)</div>
                                        @endif
                                        <p class="mb-0">@lang('Return Interval')</p>
                                    </div>

                                    <div class="item">
                                        <div
                                            class="plan-title">{{ optional($offerProperty->property)->is_capital_back == 1 ? 'Yes' : 'No' }}</div>
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
                                            @if($offerProperty->status == 0 && optional($offerProperty->propertyShare)->status == 1)
                                                <li>
                                                    <button
                                                        class="dropdown-item updateOffer btn"
                                                        data-route="{{route('user.propertyOfferUpdate', $offerProperty->id)}}"
                                                        data-owner="{{ optional($offerProperty->owner)->fullname }}"
                                                        data-amount="{{ $offerProperty->amount }}"
                                                        data-details="{{ $offerProperty->description }}"
                                                        data-property="{{ (optional($offerProperty->property)->title) }}">
                                                        <i class="fa-regular fa-paper-plane"></i>
                                                        @lang('Update Offer')
                                                    </button>
                                                </li>
                                            @elseif(optional($offerProperty->propertyShare)->status == 0 && $offerProperty->payment_status == 0)
                                                <li>
                                                    <a class="dropdown-item btn disabled">
                                                        <i class="fal fa-shopping-cart"></i> @lang('Sold out')
                                                    </a>
                                                </li>
                                            @endif

                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('user.offerConversation', $offerProperty->id) }}">
                                                    <i class="fal fa-envelope"
                                                       aria-hidden="true"></i> @lang('Conversation')
                                                </a>
                                            </li>

                                            <li>
                                                <button
                                                    class="dropdown-item btn notiflix-confirm"
                                                    data-bs-toggle="modal" data-bs-target="#delete-modal"
                                                    data-route="{{route('user.propertyOfferRemove', $offerProperty->id)}}">
                                                    <i class="fal fa-trash"></i>
                                                    @lang('Remove')
                                                </button>
                                            </li>

                                        </ul>
                                    </div>
                                    <a href="{{ route('investor.profile', [optional($offerProperty->owner)->id, slug(optional($offerProperty->owner)->username)]) }}"
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
                {{ $myOfferedProperties->appends($_GET)->links() }}
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
    {{--  Remove Share modal --}}
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
                            <div class="card-body">
                                <h5 class="property-title"></h5>
                                <div class="row g-3 investModalPaymentForm">
                                    <div id="formModal" class="investModalPaymentForm">
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
                                        <label for="">@lang('Offer Amount')</label>
                                        <div class="input-group">
                                            <input type="text"
                                                   class="invest-amount amount form-control @error('amount') is-invalid @enderror"
                                                   name="amount"
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
                                            <textarea name="description" class="form-control details" id="description"
                                                      cols="10" rows="3"></textarea>
                                            @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-custom cmn-btn2 btn-secondary close_invest_modal close__btn"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="cmn-btn investModalSubmitBtn">@lang('Update Offer')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('script')
    <script>
        'use strict'
        $('.notiflix-confirm').on('click', function () {
            var route = $(this).data('route');
            $('.deleteRoute').attr('action', route)
        })

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
    </script>
@endpush
