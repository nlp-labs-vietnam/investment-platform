<div class="property-box">
    <div class="rate-area badges">
        <a href="javascript:void(0)" class="item save wishList" id="{{$key}}" data-property="{{ $property->id }}">
            @if($property->favoritedByUser)
                <i class="fas fa-heart save{{$key}}"></i>
            @else
                <i class="fal fa-heart save{{$key}}"></i>
            @endif
        </a>
    </div>

    <div class="img-box">
        <a href="{{ route('property.details',[$property->id, slug($property->title)]) }}">
            <img src="{{ getFile($property->driver, $property->thumbnail) }}"
                 alt="@lang('property thumbnail')">
        </a>

        <div class="tag-box">
            @if($property->is_invest_type == 1)
                <div class="single-tag">@lang('Fixed Invest')</div>
                @if($property->is_installment == 1)
                    <div class="single-tag">@lang('Installment')</div>
                @else
                    <div class="single-tag">@lang('No Installment')</div>
                @endif
            @else
                <div class="single-tag">@lang('Invest Range')</div>
                @if($property->is_installment == 1)
                    <div class="single-tag">@lang('Installment')</div>
                @else
                    <div class="single-tag">@lang('No Installment')</div>
                @endif
            @endif
        </div>
    </div>


    <div class="text-box">
        <div class="d-flex justify-content-between align-items-center">
            <p class="mb-0 contact-item align-items-center gap-1"><i
                    class="fa-regular fa-location-dot"></i> @lang(optional($property->address)->title) </p>
            <h4 class="mb-0">{{ $property->investmentAmount }}</h4>
        </div>
        <a class="title"
           href="{{ route('property.details',[$property->id, slug($property->title)]) }}">{{ \Str::limit($property->title, 30)  }}</a>
        <div class="meta-data mt-10">
            <div class="review">
                <ul class="reviews d-flex align-items-center gap-2">
                    @include(template().'partials.propertyReview')
                </ul>
            </div>
        </div>


        <div class="amenities mt-15">
            @foreach($property->limitamenity as $key => $amenity)
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
                    @if($property->profit_type == 1)
                        <div class="plan-title">{{ fractionNumber(getAmount($property->profit)) }}% (@lang('Fixed'))</div>
                    @else
                        <div
                            class="plan-title">{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount($property->profit)) }}
                            (@lang('Fixed'))
                        </div>
                    @endif
                    <p class="mb-0">@lang('Profit Range')</p>
                </div>

                <div class="item">
                    @if($property->is_return_type == 1)
                        <div class="plan-title">@lang('Lifetime')</div>
                    @else
                        <div class="plan-title">{{ optional($property->managetime)->time }} @lang(optional($property->managetime)->time_type)</div>
                    @endif
                    <p class="mb-0">@lang('Return Interval')</p>
                </div>

                <div class="item">
                    <div class="plan-title">{{ @$property->is_capital_back == 1 ? 'Yes' : 'No' }}</div>
                    <p class="mb-0">@lang('Capital back')</p>
                </div>
            </div>

            <button type="button" class="cmn-btn2 w-100 mt-15 investNow"
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
                    <i class="fa-light fa-sack-dollar"></i>
                    @lang('Invest Now')
                @endif
            </button>
        </div>
    </div>
</div>
