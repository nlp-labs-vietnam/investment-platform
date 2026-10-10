@if(count($myProperties) > 0)
    <div class="col-lg-12">
        <div class="row g-4 mb-5">
            @foreach($myProperties as $key => $property)
                <div class="col-md-4 col-lg-4">
                    <div class="property-box">
                        <div class="img-box">
                            <img class="img-fluid"
                                 src="{{ getFile(optional($property->property)->driver, optional($property->property)->thumbnail) }}"
                                 alt="@lang('property thumbnail')"/>
                            <div class="content">
                                @if(optional($property->property)->is_invest_type == 0)
                                    <div class="tag">@lang('Fixed Invest')</div>
                                @else
                                    <div class="tag">@lang('Invest Range')</div>
                                @endif

                                <div class="badges">
                                    @if(optional($property->property)->is_installment == 1)
                                        <span class="featured">@lang('Installment facility')</span>
                                    @else
                                        <span class="featured">@lang('No installment')</span>
                                    @endif
                                </div>
                                <h4 class="price">{{ currencyPosition($property->amount) }}</h4>
                                @if($property->status == 0 && $property->invest_status == 1)
                                    <span class="invest-completed"><i class="fad fa-check-circle"></i> @lang('Running')</span>
                                    @elseif($property->status == 1 && $property->invest_status == 1)
                                    <span class="invest-completed"><i class="fad fa-check-circle text-success"></i> @lang('Completed')</span>
                                @else
                                    <span class="invest-completed"><i class="fad fa-times-circle text-danger"></i> @lang('Due')</span>
                                @endif

                            </div>
                        </div>

                        <div class="text-box">
                            <div class="review">
                                @include(template().'partials.propertyReview',['property' => $property?->property])
                            </div>
                            <a class="title"
                               href="{{ route('property.details',[optional($property->property)->id, @slug(optional($property->property)->title)]) }}">{{ \Illuminate\Support\Str::limit(optional($property->property)->title, 30)  }}</a>
                            <p class="address">
                                <i class="fas fa-map-marker-alt"></i>
                                @lang(optional(optional($property->property)->address)->title)
                            </p>

                            <div class="aminities">
                                @foreach(optional($property->property)->limitamenity as $key => $amenity)
                                    <span><i class="{{ $amenity->icon }}"></i>{{ $amenity->title  }}</span>
                                @endforeach
                            </div>

                            <div class="invest-btns d-flex justify-content-between">

                                @if($property->propertyShare)
                                    <button type="button" class="btn text-danger">
                                        @lang('Already Shared')
                                    </button>
                                @else
                                    @if(optional($property->property)->is_investor == 1 && basicControl()->is_share_investment == 1)
                                        <button type="button" class="sendOffer btn {{ ($property->invest_status == 1 && $property->status == 1) || ($property->invest_status == 0) || (optional($property->property)->is_investor == 0) ? 'disabled' : '' }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="@lang('You can sell this investment')"
                                                data-route="{{route('user.propertyShareStore', $property->id)}}"
                                                data-property="{{ optional($property->property)->title }}">
                                            @lang('Sell Share')
                                        </button>
                                    @else
                                        <button class="opacity-0"></button>
                                    @endif
                                @endif

                                <a href="{{ route('page', 'contact') }}">
                                    @lang('Contact Us')
                                </a>
                            </div>

                            <div class="plan d-flex justify-content-between">
                                <div>
                                    @if(optional($property->property)->profit_type == 1)
                                        <h5>{{ fractionNumber(optional($property->property)->profit) }}% (@lang('Fixed'))</h5>
                                    @else
                                        <h5>{{ currencyPosition(optional($property->property)->profit) }}
                                            (@lang('Fixed'))</h5>
                                    @endif
                                    <span>@lang('Profit Range')</span>
                                </div>
                                <div>
                                    @if(optional($property->property)->is_return_type == 1)
                                        <h5>@lang('Lifetime')</h5>
                                    @else
                                        <h5>{{ optional($property->property->managetime)->time }} @lang(optional($property->property->managetime)->time_type)</h5>
                                    @endif
                                    <span>@lang('Return Interval')</span>
                                </div>
                                <div>
                                    <h5>{{ optional($property->property)->is_capital_back == 1 ? 'Yes' : 'No' }}</h5>
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
                {{ $myProperties->appends($_GET)->links() }}
            </ul>
        </nav>
    </div>
@else
    <div class="custom-not-found mt-5">
        <img src="{{ asset(template(true).'img/no_data_found.png') }}" alt="@lang('not found')" class="img-fluid">
    </div>
@endif
