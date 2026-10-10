@extends(template().'layouts.app')
@section('title', trans('Property Details'))

@section('content')
    <div class="banner-slider-section">
        <div class="owl-carousel owl-theme banner-slider magnific-popup">
            @if(isset($singlePropertyDetails->image) && count($singlePropertyDetails->image) > 0)
                @foreach($singlePropertyDetails->image as $key => $value)
                    <div class="item">
                        <a href="{{ getFile($value->driver, $value->image) }}" rel="prettyPhoto[{{ $key }}]"
                           class="cursorimage">
                            <img src="{{ getFile($value->driver, $value->image) }}"/>
                        </a>
                    </div>
                @endforeach

            @else
                <div class="item">
                    <a href="{{ getFile($singlePropertyDetails->driver, $singlePropertyDetails->thumbnail) }}"
                       rel="prettyPhoto[gallery1]"
                       class="cursorimage">
                        <img src="{{ getFile($singlePropertyDetails->driver, $singlePropertyDetails->thumbnail) }}"/>
                    </a>
                </div>
            @endif

        </div>
    </div>

    <!-- Products header start -->
    <div class="products-header-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-8">
                    <h3 class="text-capitalize"> @lang($singlePropertyDetails->title) </h3>
                    <p class=" mb-1 contact-item"><i class="fa-regular fa-location-dot"></i>
                        @lang(optional($singlePropertyDetails->address)->title)
                    </p>
                    <div class="review mt-10">
                        <ul class="reviews d-flex align-items-center gap-2">
                            @include(template().'partials.propertyReview')
                        </ul>
                    </div>

                </div>
                <div class="col-md-4 align-items-md-end d-flex flex-column gap-3">

                    <h3 class="invest-price">
                        {{ $singlePropertyDetails->investmentAmount }}
                        <span>{{ $singlePropertyDetails->is_invest_type == 0 ? trans('(Fixed Invest)') : trans('(Invest Range)') }}</span>
                    </h3>
                    @if($singlePropertyDetails->available_funding == 0 && $singlePropertyDetails->expire_date > now() && $singlePropertyDetails->is_available_funding == 1)
                        <span class="invest-completed-details"><i class="fa-regular fa-check-circle"></i> @lang('Investment Completed')</span>
                    @elseif($singlePropertyDetails->expire_date < now())
                        <span class="invest-completed-details bg-danger"><i
                                class="fa-regular fa-times-circle"></i> @lang('Investment Time Expired')</span>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- Products header end -->


    <!-- Product details section start -->
    <section class="product-details-section">
        <div class="container">
            <div class="row g-4 g-xxl-5">
                <div class="col-lg-8">
                    <div class="card mb-30">
                        <div class="card-header">
                            <h4> @lang('Description') </h4>
                        </div>
                        <div class="card-body">
                            {!! $singlePropertyDetails->details !!}
                        </div>
                    </div>
                    <div class="card mb-30">
                        <div class="card-header">
                            <h4> @lang('Amenities') </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                @foreach($singlePropertyDetails->allamenity as $amenity)
                                    <div class=" col-md-4 col-sm-6">
                                        <div class="cmn-box2">
                                            <div class="icon-box">
                                                <i class="{{ @$amenity->icon }}"></i>
                                            </div>
                                            <div class="content-box">
                                                <h6>@lang($amenity->title)</h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card mb-30">
                        <div class="card-header">
                            <h4>@lang('Map Location')</h4>
                        </div>
                        <div class="card-body">
                            <div class="map-section">
                                <iframe
                                    src="{{ $singlePropertyDetails->location }}"
                                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                    @if($singlePropertyDetails->faq != null)
                        <div class="card mb-30">
                            <div class="card-header">
                                <h4>@lang('General Query')</h4>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="accordionExample">
                                    @php
                                        $faq_key = 0;
                                    @endphp

                                    @foreach($singlePropertyDetails->faq as $key => $faq)
                                        @php
                                            $faq_key++;
                                        @endphp
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne{{ $faq_key }}" aria-expanded="true"
                                                        aria-controls="collapseOne{{ $faq_key }}">
                                                    @lang($faq->field_name)
                                                </button>
                                            </h2>
                                            <div id="collapseOne{{ $faq_key }}"
                                                 class="accordion-collapse collapse {{ @$faq_key == 1 ? 'show' : '' }}"
                                                 data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    @lang(@$faq->field_value)
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card mb-30" id="review-app">
                        <div class="card-header d-flex justify-content-between">
                            <h4>@lang('Reviews')</h4>
                            @guest
                                <a href="{{ route('login') }}"
                                   class="cmn-btn ">@lang('Login to review')</a>
                            @endguest
                        </div>

                        <div class="card-body">
                            <div class="review-item mb-20" v-for="(obj, index) in item.feedArr">
                                <div class="d-flex justify-content-between flex-wrap gap-2 mb-15">
                                    <div class="author-profile">
                                        <a href="" class="img-box"><img src="{{ asset(template(true).'img/Person/1.jpg') }}"
                                                                        alt=""></a>
                                        <div class="text-box">
                                            <h5 class="mb-0">@{{obj.review_user_info.fullname}}</h5>
                                            <small>@{{obj.date_formatted}}</small>
                                        </div>
                                    </div>
                                    <ul class="reviews d-flex align-items-center gap-3">
                                        <li>
                                            <i class="active fa-solid fa-star" v-for="i in obj.rating2" :key="i"
                                               v-cloak=""></i>
                                        </li>
                                    </ul>
                                </div>
                                <p class="mb-0">
                                    @{{ obj.review }}
                                </p>
                            </div>

                            <div class="frontend-not-data-found" v-if="item.feedArr.length<1" v-cloak="">
                                <p class="text-center not-found-times" v-cloak="">
                                    <i class="fad fa-file-times not-found-times" v-cloak=""></i>
                                </p>
                                <h5 class="text-center m-0 " v-cloak="">@lang("No Review Found")</h5>

                            </div>

                            <div class="row mt-5">
                                <div class="col d-flex justify-content-center" v-cloak="">
                                    @include(template().'partials.vuePaginate')
                                </div>
                            </div>

                            @auth
                                @if($reviewDone <= 0 && in_array(\Auth::user()->id, $investor))
                                    <div class="review-box mt-30" v-if="item.reviewDone < 1">
                                        <h4>@lang('Add Review')</h4>
                                        <div class="ratings">
                                            <input class="rating__input" name="rating2" id="star1" value="1"
                                                   @click="rate(1)" type="radio"/>
                                            <label for="star1" title="text" class="rating__label"></label>

                                            <input class="rating__input" name="rating2" id="star2" value="2"
                                                   @click="rate(2)" type="radio"/>
                                            <label for="star2" title="text" class="rating__label"></label>

                                            <input class="rating__input" name="rating2" id="star3" value="3"
                                                   @click="rate(3)" type="radio"/>
                                            <label for="star3" title="text" class="rating__label"></label>

                                            <input class="rating__input" name="rating2" id="star4" value="4"
                                                   @click="rate(4)" type="radio"/>
                                            <label for="star4" title="text" class="rating__label"></label>

                                            <input class="rating__input" name="rating2" id="star5" value="5"
                                                   @click="rate(5)" type="radio"/>
                                            <label for="star5" title="text" class="rating__label"></label>
                                        </div>

                                        <textarea class="form-control mt-20" id="exampleFormControlTextarea1"
                                                  name="review"
                                                  v-model="item.feedback"
                                                  placeholder="Type here..." rows="5"></textarea>
                                        <span class="text-danger"
                                              v-cloak="">@{{ error.feedbackError }}</span>
                                        <button class="cmn-btn mt-20"
                                                @click.prevent="addFeedback">@lang('Submit now')</button>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>


                <div class="col-lg-4">
                    <form action="{{route('user.invest-property', $singlePropertyDetails->id)}}" method="post">
                        @csrf
                        <div class="sidebar-widget-area">
                            <h4 class="widget-title">@lang('Invest Amount')</h4>
                            @if($singlePropertyDetails->is_available_funding == 1)
                                <h6>@lang('Available for funding'):
                                    @if($singlePropertyDetails->available_funding < $singlePropertyDetails->minimum_amount && $singlePropertyDetails->available_funding !=0)
                                        {{ currencyPosition($singlePropertyDetails->minimum_amount) }}
                                    @else
                                        <span>{{currencyPosition($singlePropertyDetails->available_funding)}}</span>
                                    @endif
                                </h6>
                            @endif

                            <div class="cmn-list mt-10">
                                <div class="item">
                                    <span>@lang('Invest Amount'):</span>
                                    <span>
                                    @if($singlePropertyDetails->fixed_amount > $singlePropertyDetails->available_funding && $singlePropertyDetails->available_funding > 0)
                                            {{ currencyPosition($singlePropertyDetails->available_funding) }}
                                        @else
                                            @if($singlePropertyDetails->available_funding < $singlePropertyDetails->minimum_amount && $singlePropertyDetails->available_funding !=0)
                                                {{ currencyPosition($singlePropertyDetails->minimum_amount) }}
                                            @else
                                                {{ $singlePropertyDetails->investmentAmount }}
                                            @endif
                                        @endif
                                </span>
                                </div>

                                <div class="item">
                                    <span>@lang('Profit'):</span>
                                    <span>{{ $singlePropertyDetails->profit_type == 1 ? fractionNumber(getAmount($singlePropertyDetails->profit)).'%' : currencyPosition($singlePropertyDetails->profit) }}</span>
                                </div>

                                <div class="item">
                                    <span>@lang('Return Interval'):</span>
                                    <span>
                                    {{ $singlePropertyDetails->how_many_times == null ? optional($singlePropertyDetails->managetime)->time.' '.optional($singlePropertyDetails->managetime)->time_type.' '.'(Lifetime)' :  optional($singlePropertyDetails->managetime)->time.' '.optional($singlePropertyDetails->managetime)->time_type.' '.'('.$singlePropertyDetails->how_many_times. ' '. 'times'. ')' }}
                                </span>
                                </div>

                                @if($singlePropertyDetails->fixed_amount < $singlePropertyDetails->available_funding && $singlePropertyDetails->available_funding > 0)
                                    @if($singlePropertyDetails->is_installment == 1)
                                        <div class="item">
                                            <span>@lang('Total Installments'):</span>
                                            <span>
                                            {{ $singlePropertyDetails->total_installments }}
                                        </span>
                                        </div>

                                        <div class="item">
                                            <span>@lang('Installment Duration'):</span>
                                            <span>
                                            {{ $singlePropertyDetails->installment_duration }} @lang($singlePropertyDetails->installment_duration_type)
                                        </span>
                                        </div>

                                        <div class="item">
                                            <span>@lang('Installment Late Fee'):</span>
                                            <span>
                                            {{ currencyPosition($singlePropertyDetails->installment_late_fee) }}
                                        </span>
                                        </div>
                                    @endif
                                @endif

                                <div class="item">
                                    <span>@lang('Capital Back'):</span>
                                    <span>
                                    {{ $singlePropertyDetails->is_capital_back == 1 ? 'Yes' : 'No' }}
                                </span>
                                </div>
                                <div class="item">
                                    <span>@lang('Expire'):</span>
                                    <span>
                                   {{ dateTime($singlePropertyDetails->expire_date) }}
                                </span>
                                </div>
                            </div>

                            @if($singlePropertyDetails->available_funding != 0 && $singlePropertyDetails->expire_date > now())
                                <hr class="cmn-hr2">
                            @endif

                            @auth
                                @if($singlePropertyDetails->available_funding != 0 && $singlePropertyDetails->expire_date > now())
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div id="formModal">
                                                <label class="form-label">@lang('Select Wallet')</label>
                                                <select class="modal-select" name="balance_type">
                                                    @auth
                                                        <option
                                                            value="balance">@lang('Deposit Balance - '.currencyPosition(auth()->user()->balance))</option>
                                                        <option
                                                            value="interest_balance">@lang('Interest Balance -'.currencyPosition(auth()->user()->interest_balance))</option>
                                                    @endauth
                                                </select>
                                            </div>
                                        </div>


                                        @if($singlePropertyDetails->fixed_amount < $singlePropertyDetails->available_funding && $singlePropertyDetails->available_funding > 0 && $singlePropertyDetails->is_installment==1)
                                            <div class="col-12">
                                                <div id="formModal" class="payInstallment">
                                                    <div class="form-check mt-10">
                                                        <input class="form-check-input" type="checkbox" value="0"
                                                               data-installmentamount="{{ $singlePropertyDetails->installment_amount }}"
                                                               data-fixedamount="{{ $singlePropertyDetails->fixed_amount }}"
                                                               name="pay_installment" id="pay_installment"/>

                                                        <label class="form-check-label"
                                                               for="pay_installment">@lang('Pay Installment')</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-12">
                                            <div>
                                                <label for="Amount" class="form-label">@lang('Amount')</label>
                                                <input type="text" class="form-control invest-amount"
                                                       value="{{ $singlePropertyDetails->investableAmount() }}"
                                                       name="amount" id="amount"
                                                       onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                       {{ $singlePropertyDetails->is_invest_type == 0 ? 'readonly' : '' }} placeholder="@lang('Enter amount')">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="kew-btn">
                                                <span class="kew-text">{{ trans('Invest Now') }}</span>
                                                <div class="kew-arrow">
                                                    <div class="kt-one"><i class="fa-regular fa-arrow-right-long"></i>
                                                    </div>
                                                    <div class="kt-two"><i class="fa-regular fa-arrow-right-long"></i>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6 class="text-center font-weight-bold">@lang('First Log In To Your Account For Invest')</h6>
                                            <div class="tree">
                                                <div class="d-flex justify-content-center">
                                                    <div
                                                        class="branch branch-1">
                                                        <a href="{{ route('login') }}"
                                                           class="">@lang('Sign In')</a>
                                                        /
                                                        <a href="{{ route('register') }}" class="">@lang('Sign Up')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </form>

                    @if($investors->count() > 0)
                        <div class="sidebar-widget-area">
                            <h4 class="widget-title">@lang('Investor')</h4>
                            <div class="owl-carousel investor-carousel">
                                @foreach($investors as $investor)
                                    <div class="item">
                                        <div class="investor-box">
                                            <a href="{{ route('investor.profile', [$investor->id, @slug($investor->username)]) }}" class="img-box">
                                                <img src="{{ getFile($investor->image_driver, $investor->image) }}" alt="">
                                            </a>
                                            <div class="text-box">
                                                <a href="{{ route('investor.profile', [$investor->id, @slug($investor->username)]) }}" class="title">
                                                    {{ $investor->fullname }}
                                                </a>
                                                <p>@lang('Agent of Property')</p>

                                                <div class="meta-list">
                                                    <div class="meta-list-item">
                                                        <i class="fa-regular fa-building"></i>
                                                        {{ $investor->invests_count }}
                                                        @if($investor->invests_count == 1)
                                                            @lang('Property')
                                                        @else
                                                            @lang('Properties')
                                                        @endif
                                                    </div>

                                                    @if($investor->address)
                                                        <div class="meta-list-item">
                                                            <i class="fa-regular fa-location-dot"></i>
                                                            @lang($investor->address)
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="sidebar-widget-area">
                        <h4 class="widget-title">@lang('Latest Properties')</h4>
                        @foreach($latestProperties as $property)
                            <div class="property-box-sidebar">
                                <a href="{{ route('property.details',[$property->id, @slug($property->title)]) }}"
                                   class="img-box">
                                    <img src="{{ getFile($property->driver, $property->thumbnail) }}" alt="">
                                </a>
                                <div class="text-box">
                                    <a href="{{ route('property.details',[$property->id, @slug($property->title)]) }}"
                                       class="title">@lang($property->title)</a>
                                    <p class="mb-0 contact-item align-items-center gap-1"><i
                                            class="fa-regular fa-location-dot"></i>@lang(optional($property->address)->title)
                                    </p>
                                    <h5 class="price">{{ $property->investmentAmount }}</h5>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Product details section end -->
@endsection

@push('script')
    <script>
        'use strict'
        var newApp = new Vue({
            el: "#review-app",
            data: {
                item: {
                    feedback: "",
                    propertyId: '',
                    feedArr: [],
                    reviewDone: "",
                    rating: "",
                },

                pagination: [],
                links: [],
                error: {
                    feedbackError: ''
                }
            },
            beforeMount() {
                let _this = this;
                _this.getReviews()
            },
            mounted() {
                let _this = this;
                _this.item.propertyId = "{{$singlePropertyDetails->id}}"
                _this.item.reviewDone = "{{$reviewDone}}"
                _this.item.rating = "5";
            },
            methods: {
                rate(rate) {
                    this.item.rating = rate;
                },
                addFeedback() {
                    let item = this.item;
                    this.makeError();
                    axios.post("{{route('user.review.push')}}", this.item)
                        .then(function (response) {
                            if (response.data.status == 'success') {
                                item.feedArr.unshift({
                                    review: response.data.data.review,
                                    review_user_info: response.data.data.review_user_info,
                                    rating2: parseInt(response.data.data.rating2),
                                    date_formatted: response.data.data.date_formatted,
                                });
                                item.reviewDone = 5;
                                item.feedback = "";
                                Notiflix.Notify.Success("Review done");
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                makeError() {
                    if (!this.item.feedback) {
                        this.error.feedbackError = "Your review message field is required"
                    }
                },

                getReviews() {
                    var app = this;
                    axios.get("{{ route('api-propertyReviews',[$singlePropertyDetails->id]) }}")
                        .then(function (res) {
                            app.item.feedArr = res.data.data.data;
                            app.pagination = res.data.data;
                            app.links = res.data.data.links;
                            app.links = app.links.slice(1, -1);
                        })

                },
                updateItems(page) {
                    var app = this;
                    if (page == 'back') {
                        var url = this.pagination.prev_page_url;
                    } else if (page == 'next') {
                        var url = this.pagination.next_page_url;
                    } else {
                        var url = page.url;
                    }
                    axios.get(url)
                        .then(function (res) {
                            app.item.feedArr = res.data.data.data;
                            app.pagination = res.data.data;
                            app.links = res.data.data.links;
                        })
                },
            }
        })

        $(document).ready(function () {
            $(document).on('click', '#pay_installment', function () {
                if ($(this).prop("checked") == true) {
                    $(this).val(1);
                    let installmentAmount = $(this).data('installmentamount');
                    $('.invest-amount').val(installmentAmount);
                    $('#amount').attr('readonly', true);
                } else {
                    let fixedAmount = $(this).data('fixedamount');
                    $('.invest-amount').val(fixedAmount);
                    $('#amount').attr('readonly', true);
                    $(this).val(0);
                }

            });
        });
    </script>
@endpush
