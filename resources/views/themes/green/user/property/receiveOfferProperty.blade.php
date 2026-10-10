@if(count($receivedOfferedList) > 0)
    <div class="col-lg-12">
        <div class="row g-4 mb-5">
            @foreach($receivedOfferedList as $key => $offerList)
                <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="900">
                    <div class="property-box">
                        <div class="img-box">
                            <a href="{{ route('property.details',[$offerList->id, slug($offerList->property->title)]) }}">
                                <img
                                    src="{{ getFile(optional($offerList->property)->driver, optional($offerList->property)->thumbnail) }}">
                            </a>
                            <div class="tag-box">
                                <div class="single-tag">@lang('Sell')</div>
                            </div>
                        </div>

                        <div class="text-box">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0 contact-item align-items-center gap-1"><i
                                        class="fa-regular fa-location-dot"></i> @lang(optional(optional($offerList->property)->address)->title)
                                </p>
                                <h4 class="mb-0">{{ currencyPosition($offerList->amount) }}</h4>
                            </div>
                            <a class="title"
                               href="{{ route('property.details',[optional($offerList->property)->id, @slug(optional($offerList->property)->title)]) }}">{{ \Illuminate\Support\Str::limit(optional($offerList->property)->title, 30)  }}</a>

                            <div class="meta-data mt-10">
                                <div class="review">
                                    <ul class="reviews d-flex align-items-center gap-2">
                                        @include(template().'partials.propertyReview',['property' => $offerList?->property])
                                    </ul>
                                </div>
                            </div>

                            <div class="amenities mt-15">
                                @foreach(optional($offerList->property)->limitamenity as $key => $amenity)
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
                                        @if(optional($offerList->property)->profit_type == 1)
                                            <div
                                                class="plan-title">{{ fractionNumber(getAmount(optional($offerList->property)->profit)) }}
                                                %
                                                (@lang('Fixed'))
                                            </div>
                                        @else
                                            <div
                                                class="plan-title">{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount(optional($offerList->property)->profit)) }}
                                                (@lang('Fixed'))
                                            </div>
                                        @endif
                                        <p class="mb-0">@lang('Profit Range')</p>
                                    </div>

                                    <div class="item">
                                        @if(optional($offerList->property)->is_return_type == 1)
                                            <div class="plan-title">@lang('Lifetime')</div>
                                        @else
                                            <div
                                                class="plan-title">{{ optional(optional($offerList->property)->managetime)->time }} @lang(optional(optional($offerList->property)->managetime)->time_type)</div>
                                        @endif
                                        <p class="mb-0">@lang('Return Interval')</p>
                                    </div>

                                    <div class="item">
                                        <div
                                            class="plan-title">{{ optional($offerList->property)->is_capital_back == 1 ? 'Yes' : 'No' }}</div>
                                        <p class="mb-0">@lang('Capital back')</p>
                                    </div>
                                </div>

                                <div class="mt-15 d-flex justify-content-between align-items-center">

                                    <a class="contact-owner-btn" href="{{ route('user.offerList', $offerList->property_share_id) }}">
                                        @lang('Offer List') ({{ $offerList->totalOfferList() }})
                                    </a>
                                    <a href="{{ route('page', 'contact') }}"
                                       class="contact-owner-btn">@lang('Contact Us')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{ $receivedOfferedList->appends($_GET)->links() }}
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
