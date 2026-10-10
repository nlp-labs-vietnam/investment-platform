
<div class="property-box">
    <div class="img-box">
        <img class="img-fluid"
             src="{{ getFile($property->driver, $property->thumbnail) }}"
             alt="@lang('property thumbnail')"/>
        <div class="content">
            @if($property->is_invest_type == 0)
                <div class="tag">@lang('Fixed Invest')</div>
                @if($property->is_installment == 1)
                    <div class="tag2">@lang('Installment')</div>
                @else
                    <div class="tag2">@lang('No Installment')</div>
                @endif
            @else
                <div class="tag">@lang('Invest Range')</div>
                @if($property->is_installment == 1)
                    <div class="tag2">@lang('Installment')</div>
                @else
                    <div class="tag2">@lang('No Installment')</div>
                @endif
            @endif

            <div class="badges">
                <button class="save wishList" type="button" id="{{$key}}" data-property="{{ $property->id }}">
                    @if($property->favoritedByUser)
                        <i class="fas fa-heart save{{$key}}"></i>
                    @else
                        <i class="fal fa-heart save{{$key}}"></i>
                    @endif
                </button>
            </div>

            <h4 class="price">{{ $property->investmentAmount }}</h4>
            @if($property->is_available_funding == 1 && $property->available_funding == 0)
                <span class="invest-completed"><i class="fad fa-check-circle"></i> @lang(' Completed')</span>
            @endif
        </div>
    </div>

    <div class="text-box">
        <div class="review">
            @include(template().'partials.propertyReview')
        </div>
        <a class="title" href="{{ route('property.details',[$property->id, slug($property->title)]) }}">
            {{ \Str::limit($property->title, 30)  }}</a>
        <p class="address">
            <i class="fas fa-map-marker-alt"></i>
            @lang(optional($property->address)->title)
        </p>

        <div class="aminities">
            @foreach($property->limitamenity as $key => $amenity)
                <span><i class="{{ $amenity->icon }}"></i>{{ $amenity->title  }}</span>
            @endforeach
        </div>

        <div class="invest-btns d-flex justify-content-between">
            <button type="button" class="investNow"
                    {{ $property->rud()['upcomingProperties'] ? 'disabled' : '' }}
                    data-route="{{route('user.invest-property', $property->id)}}"
                    data-property="{{ $property }}"
                    data-expired="{{ dateTime($property->expire_date) }}"
                    data-symbol="{{ basicControl()->currency_symbol }}"
                    data-currency="{{ basicControl()->base_currency }}">
                @if($property->rud()['upcomingProperties'])
                    <span class="text-info">@lang('Coming in ') <span
                            class="text-success">@lang($property->rud()['difference']->d.'D '. $property->rud()['difference']->h.'H '. $property->rud()['difference']->i.'M ')</span></span>
                @else
                    @lang('Invest Now')
                @endif
            </button>

            <a href="{{ route('page', 'contact') }}">
                @lang('Contact Us')
            </a>
        </div>

        <div class="plan d-flex justify-content-between">
            <div>
                @if($property->profit_type == 1)
                    <h5>{{ fractionNumber($property->profit) }}% (@lang('Fixed'))</h5>
                @else
                    <h5>{{ currencyPosition($property->profit) }}(@lang('Fixed'))</h5>
                @endif
                <span>@lang('Profit Range')</span>
            </div>

            <div>
                @if($property->is_return_type == 1)
                    <h5>@lang('Lifetime')</h5>
                @else
                    <h5>{{ $property->return_time }} @lang($property->return_time_type)</h5>
                @endif
                <span>@lang('Return Interval')</span>
            </div>
            <div>
                <h5>{{ @$property->is_capital_back == 1 ? 'Yes' : 'No' }}</h5>
                <span>@lang('Capital back')</span>
            </div>
        </div>
    </div>
</div>

