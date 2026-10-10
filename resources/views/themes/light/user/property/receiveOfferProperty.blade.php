@if(count($receivedOfferedList) > 0)
    <div class="col-lg-12">
        <div class="row g-4 mb-5">
            @foreach($receivedOfferedList as $key => $offerList)
                <div class="col-md-4 col-lg-4">
                    <div class="property-box">
                        <div class="img-box">
                            <img class="img-fluid"
                                 src="{{ getFile(optional($offerList->property)->driver, optional($offerList->property)->thumbnail) }}"
                                 alt="@lang('property thumbnail')"/>
                            <div class="content">
                                <div class="tag">@lang('Sell')</div>
                                <h4 class="price">{{ currencyPosition($offerList->propertyShare?->amount) }}</h4>
                            </div>
                        </div>

                        <div class="text-box">
                            <div class="review">
                                @include(template().'partials.propertyReview',['property' => $offerList?->property])
                            </div>

                            <a class="title"
                               href="{{ route('property.details',[optional($offerList->property)->id, slug(optional($offerList->property)->title)]) }}">{{ Str::limit(optional($offerList->property)->title, 30)  }}</a>
                            <p class="address">
                                <i class="fas fa-map-marker-alt"></i>
                                @lang(optional(optional($offerList->property)->address)->title)
                            </p>

                            <div class="aminities">
                                @foreach(optional($offerList->property)->limitamenity as $key => $amenity)
                                    <span><i class="{{ $amenity->icon }}"></i>{{ $amenity->title  }}</span>
                                @endforeach
                            </div>



                            <div class="invest-btns d-flex justify-content-between">
                                <a class="btn" href="{{ route('user.offerList', $offerList->property_share_id) }}">
                                    @lang('Offer List') <span class="badge bg-secondary">{{ $offerList->totalOfferList() }}</span>
                                </a>

                                <a href="{{ route('page', 'contact') }}">
                                    @lang('Contact Us')
                                </a>
                            </div>

                            <div class="plan d-flex justify-content-between">
                                <div>
                                    @if(optional($offerList->property)->profit_type == 1)
                                        <h5>{{ fractionNumber(optional($offerList->property)->profit) }}% (@lang('Fixed'))</h5>
                                    @else
                                        <h5>{{ currencyPosition(optional($offerList->property)->profit) }}
                                            (@lang('Fixed'))
                                        </h5>
                                    @endif
                                    <span>@lang('Profit Range')</span>
                                </div>
                                <div>
                                    @if(optional($offerList->property)->is_return_type == 1)
                                        <h5>@lang('Lifetime')</h5>
                                    @else
                                        <h5>{{ optional($offerList->property->managetime)->time }} @lang(optional($offerList->property->managetime)->time_type)</h5>
                                    @endif
                                    <span>@lang('Return Interval')</span>
                                </div>
                                <div>
                                    <h5>{{ optional($offerList->property)->is_capital_back == 1 ? 'Yes' : 'No' }}</h5>
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
                {{ $receivedOfferedList->appends($_GET)->links() }}
            </ul>
        </nav>
    </div>
@else
    <div class="custom-not-found mt-5">
        <img src="{{ asset(template(true).'img/no_data_found.png') }}" alt="..." class="img-fluid">
    </div>
@endif
