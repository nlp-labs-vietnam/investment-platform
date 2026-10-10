@extends(template().'layouts.app')
@section('title', trans('Property Details'))

@section('content')
    <!-- property details -->
    <section class="property-details">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row info-box">
                            <div class="col-lg-8">
                                <h3 class="title">
                                    @lang($singlePropertyDetails->title)
                                </h3>

                                <p class="address mb-2">
                                    <i class="fas fa-map-marker-alt"></i>
                                    @lang(optional($singlePropertyDetails->address)->title)
                                </p>
                                <div class="review">
                                    @include(template().'partials.review')
                                    <a href="#reviews">
                                        ({{ $totalReview <= 1 ? ($totalReview. trans(' review')) : ($totalReview. trans(' reviews')) }})
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="right-side">
                                    <h3 class="price"> {{ $singlePropertyDetails->investmentAmount }}
                                        <span>{{ $singlePropertyDetails->is_invest_type == 0 ? trans('(Fixed Invest)') : trans('(Invest Range)') }}</span>
                                    </h3>
                                    @if($singlePropertyDetails->available_funding == 0 && $singlePropertyDetails->expire_date > now() && $singlePropertyDetails->is_available_funding == 1)
                                        <span class="invest-completed-details"><i class="fad fa-check-circle"></i> @lang('Investment Completed')</span>
                                    @elseif($singlePropertyDetails->expire_date < now())
                                        <span class="invest-completed-details bg-danger"><i
                                                class="fad fa-times-circle"></i> @lang('Investment Time Expired')</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-lg-5">
                    <div class="col-lg-8">
                        <div class="gallery-box">
                            <div id="mainCarousel" class="carousel mx-auto main_carousel">
                                @foreach($singlePropertyDetails->image as $img)
                                    <div
                                        class="carousel__slide"
                                        data-src="{{ getFile($img->driver, $img->image) }}"
                                        data-fancybox="gallery"
                                        data-caption="">
                                        <img class="img-fluid" src="{{ getFile($img->driver, $img->image) }}"/>
                                    </div>
                                @endforeach
                            </div>

                            <div id="thumbCarousel" class="carousel max-w-xl mx-auto thumb_carousel">
                                @if(count($singlePropertyDetails->image) > 0)
                                    @foreach($singlePropertyDetails->image as $img)
                                        <div class="carousel__slide">
                                            <img class="panzoom__content img-fluid"
                                                 src="{{ getFile($img->driver, $img->image) }}"/>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="">
                                        <img class="panzoom__content img-fluid"
                                             src="{{ getFile($singlePropertyDetails->driver, $singlePropertyDetails->thumbnail) }}"/>
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div id="description" class="description-box">
                            <h4>@lang('Description')</h4>
                            <p class="property__description">
                                {!! $singlePropertyDetails->details !!}
                            </p>
                        </div>
                        <div id="amenities" class="amenities-box">
                            <h4 class="mb-4">@lang('Amenities')</h4>
                            <div class="row gy-4">
                                @foreach($singlePropertyDetails->allamenity as $amenity)
                                    <div class="col-3 col-md-2">
                                        <div class="amenity-box">
                                            <i class="{{ @$amenity->icon }}"></i>
                                            <h6>@lang($amenity->title)</h6>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div id="amenities" class="video-box">
                            <h4 class="mb-4">@lang('Video')</h4>
                            <div class="row gy-4">
                                <iframe width="100%" height="100%"
                                        src="{{ $singlePropertyDetails->video }}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            </div>
                        </div>


                        <div class="map-box">
                            <h4>@lang('Location')</h4>
                            <iframe
                                src="{{ $singlePropertyDetails->location }}"
                                width="100%"
                                height="400"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>

                        @if($singlePropertyDetails->faq != null)
                            <div class="faq-box" class="accordion" id="accordionExample">
                                @php
                                    $faq_key = 0;
                                @endphp
                                @foreach($singlePropertyDetails->faq as $key => $faq)
                                    @php
                                        $faq_key++;
                                    @endphp
                                    <div class="accordion-item">
                                        <h5 class="accordion-header" id="headingOne{{ @$faq_key }}">
                                            <button
                                                class="accordion-button {{ $faq_key == 1 ? '' : 'collapsed' }}"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne{{ @$faq_key }}"
                                                aria-expanded="false"
                                                aria-controls="collapseOne">
                                                @lang(@$faq->field_name)
                                            </button>
                                        </h5>

                                        <div
                                            id="collapseOne{{ @$faq_key }}"
                                            class="accordion-collapse collapse {{ @$faq_key == 1 ? 'show' : '' }}"
                                            aria-labelledby="headingOne{{ @$faq_key }}"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                @lang(@$faq->field_value)
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div id="review-app">
                            <div id="reviews" class="reviews">
                                <div class="customer-review">
                                    <h4>@lang('Reviews')</h4>

                                    <div class="review-box" v-for="(obj, index) in item.feedArr">
                                        <div class="text">
                                            <img :src="obj.review_user_info.imgPath"/>
                                            <span class="name" v-cloak="">@{{obj.review_user_info.fullname}}</span>
                                            <p class="mt-3" v-cloak="">
                                                @{{ obj.review }}
                                            </p>
                                        </div>

                                        <div class="review-date">
                                         <span class="review rating-group">
                                              <div id="half-stars-example">
                                                  <i class="fas fa-star" v-for="i in obj.rating2" :key="i"
                                                     v-cloak=""></i>
                                              </div>
                                          </span>
                                            <br/>
                                            <span class="date" v-cloak="">@{{obj.date_formatted}}</span>
                                        </div>
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
                                </div>

                                @guest
                                    <div class="add-review mb-5 add__review__login" v-if="item.reviewDone < 1">
                                        <div class="d-flex justify-content-between">
                                            <h4>@lang('Add Review')</h4>
                                        </div>
                                        <a href="{{ route('login') }}"
                                           class="btn btn-review-custom btn-sm h-25 text-white">@lang('Login to review')</a>
                                    </div>

                                @else
                                    @if($reviewDone <= 0 && in_array(\Auth::user()->id, $investor))
                                        <div class="add-review mb-5" v-if="item.reviewDone < 1">
                                            <div>
                                                <h4>@lang('Add Review')</h4>
                                            </div>
                                            <form>
                                                <div class="mb-3">
                                                    <div id="half-stars-example">
                                                        <div class="rating-group ms-1">

                                                            <label
                                                                aria-label="1 star"
                                                                class="rating__label"
                                                                for="rating2-10"
                                                            ><i
                                                                    class="rating__icon rating__icon--star fa fa-star"
                                                                    aria-hidden="true"
                                                                ></i
                                                                ></label>
                                                            <input
                                                                class="rating__input"
                                                                name="rating2"
                                                                id="rating2-10"
                                                                value="1"
                                                                @click="rate(1)"
                                                                type="radio"
                                                            />
                                                            <label
                                                                aria-label="2 stars"
                                                                class="rating__label"
                                                                for="rating2-20"
                                                            ><i
                                                                    class="rating__icon rating__icon--star fa fa-star"
                                                                    aria-hidden="true"
                                                                ></i
                                                                ></label>
                                                            <input
                                                                class="rating__input"
                                                                name="rating2"
                                                                id="rating2-20"
                                                                value="2"
                                                                @click="rate(2)"
                                                                type="radio"
                                                            />
                                                            <label
                                                                aria-label="3 stars"
                                                                class="rating__label"
                                                                for="rating2-30"
                                                            ><i
                                                                    class="rating__icon rating__icon--star fa fa-star"
                                                                    aria-hidden="true"
                                                                ></i
                                                                ></label>
                                                            <input
                                                                class="rating__input"
                                                                name="rating2"
                                                                id="rating2-30"
                                                                value="3"
                                                                @click="rate(3)"
                                                                type="radio"
                                                            />
                                                            <label
                                                                aria-label="4 stars"
                                                                class="rating__label"
                                                                for="rating2-40"
                                                            ><i
                                                                    class="rating__icon rating__icon--star fa fa-star"
                                                                    aria-hidden="true"
                                                                ></i
                                                                ></label>
                                                            <input
                                                                class="rating__input"
                                                                name="rating2"
                                                                id="rating2-40"
                                                                value="4"
                                                                @click="rate(4)"
                                                                type="radio"
                                                            />
                                                            <label
                                                                aria-label="5 stars"
                                                                class="rating__label"
                                                                for="rating2-50"
                                                            ><i
                                                                    class="rating__icon rating__icon--star fa fa-star"
                                                                    aria-hidden="true"
                                                                ></i
                                                                ></label>
                                                            <input
                                                                class="rating__input"
                                                                name="rating2"
                                                                id="rating2-50"
                                                                value="5"
                                                                checked=""
                                                                type="radio"
                                                                @click="rate(5)"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label
                                                        for="exampleFormControlTextarea1"
                                                        class="form-label"
                                                    >@lang('Your message')</label>
                                                    <textarea
                                                        class="form-control text-dark"
                                                        id="exampleFormControlTextarea1"
                                                        name="review"
                                                        v-model="item.feedback"
                                                        rows="5"></textarea>
                                                    <span class="text-danger"
                                                          v-cloak="">@{{ error.feedbackError }}</span>
                                                </div>
                                                <button class="btn-custom mt-3"
                                                        @click.prevent="addFeedback">@lang('Submit now')</button>
                                            </form>
                                        </div>
                                    @endif
                                @endguest

                            </div>
                        </div>
                    </div>

                    <!-- sidebar start -->

                    <div class="col-lg-4">
                        <div class="side-bar">
                            <form action="{{route('user.invest-property', $singlePropertyDetails->id)}}" method="post">
                                @csrf
                                <div class="side-box">
                                    <div class="d-flex justify-content-between">
                                        <h4>@lang('Invest Amount')</h4>
                                    </div>
                                    @if($singlePropertyDetails->is_available_funding == 1)
                                        <p class="primary_color">@lang('Available for funding'):
                                            @if($singlePropertyDetails->available_funding < $singlePropertyDetails->minimum_amount && $singlePropertyDetails->available_funding !=0)
                                                {{ basicControl()->currency_symbol }}{{ getAmount($singlePropertyDetails->minimum_amount) }}
                                            @else
                                                <span>{{ basicControl()->currency_symbol }}{{ getAmount($singlePropertyDetails->available_funding) }}</span>
                                            @endif
                                        </p>
                                    @endif

                                    <ul class="profit-calculation">
                                        <li>@lang('Invest Amount'):
                                            <span>
                                                @if($singlePropertyDetails->fixed_amount > $singlePropertyDetails->available_funding && $singlePropertyDetails->available_funding > 0)
                                                    {{ basicControl()->currency_symbol }}{{ getAmount($singlePropertyDetails->available_funding) }}
                                                @else
                                                    @if($singlePropertyDetails->available_funding < $singlePropertyDetails->minimum_amount && $singlePropertyDetails->available_funding !=0)
                                                        {{ basicControl()->currency_symbol }}{{ getAmount($singlePropertyDetails->minimum_amount) }}
                                                    @else
                                                        {{ $singlePropertyDetails->investmentAmount }}
                                                    @endif
                                                @endif

                                            </span></li>
                                        <li>@lang('Profit'):
                                            <span>{{ $singlePropertyDetails->profit_type == 1 ? fractionNumber(getAmount($singlePropertyDetails->profit)).'%' : currencyPosition($singlePropertyDetails->profit) }}</span>
                                        </li>

                                        <li>@lang('Return Interval'):
                                            <span>
                                                {{ $singlePropertyDetails->how_many_times == null ? optional($singlePropertyDetails->managetime)->time.' '.optional($singlePropertyDetails->managetime)->time_type.' '.'(Lifetime)' :  optional($singlePropertyDetails->managetime)->time.' '.optional($singlePropertyDetails->managetime)->time_type.' '.'('.$singlePropertyDetails->how_many_times. ' '. 'times'. ')' }}
                                            </span>
                                        </li>

                                        @if($singlePropertyDetails->fixed_amount < $singlePropertyDetails->available_funding && $singlePropertyDetails->available_funding > 0)
                                            @if($singlePropertyDetails->is_installment == 1)
                                                <li>@lang('Total Installments')
                                                    <span>{{ $singlePropertyDetails->total_installments }}</span></li>
                                                <li>@lang('Installment Duration')
                                                    <span>{{ $singlePropertyDetails->installment_duration }} @lang($singlePropertyDetails->installment_duration_type)</span>
                                                </li>
                                                <li>@lang('Installment Late Fee')
                                                    <span>
                                                        {{ currencyPosition($singlePropertyDetails->installment_late_fee) }}
                                                    </span>
                                                </li>
                                            @endif
                                        @endif

                                        <li>@lang('Capital Back'):
                                            <span>{{ $singlePropertyDetails->is_capital_back == 1 ? 'Yes' : 'No' }}</span>
                                        </li>

                                        <li>@lang('Expire'):
                                            <span
                                                class="primary_color">{{ dateTime($singlePropertyDetails->expire_date) }}</span>
                                        </li>
                                        @if($singlePropertyDetails->available_funding != 0 && $singlePropertyDetails->expire_date > now())
                                            <hr/>
                                        @endif
                                    </ul>

                                    @auth
                                        @if($singlePropertyDetails->available_funding != 0 && $singlePropertyDetails->expire_date > now())
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
                                            @if($singlePropertyDetails->fixed_amount < $singlePropertyDetails->available_funding && $singlePropertyDetails->available_funding > 0 && $singlePropertyDetails->is_installment==1)
                                                <div class="input-box col-12 payInstallment mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="0"
                                                               name="pay_installment" id="pay_installment"
                                                               data-installmentamount="{{ getAmount($singlePropertyDetails->installment_amount) }}"
                                                               data-fixedamount="{{ getAmount($singlePropertyDetails->fixed_amount) }}"/>
                                                        <label class="form-check-label"
                                                               for="pay_installment">@lang('Pay Installment')</label>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="input-box col-12 mt-2 mb-2">
                                                <label for="@lang('Amount')">@lang('Amount')</label>
                                                <input class="form-control invest-amount" type="text"
                                                       value="{{ $singlePropertyDetails->investableAmount() }}"
                                                       {{ $singlePropertyDetails->is_invest_type == 0 ? 'readonly' : '' }} placeholder="@lang('Enter amount')"
                                                       onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                       name="amount" id="amount"/>
                                            </div>
                                            <div>
                                                <button type="submit"
                                                        class="btn-custom w-100">{{ trans('Invest Now') }}</button>
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
                                                                class="branch branch-1">@lang('Sign In / Sign Up')</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <div class="branch branch-2"><a href="{{ route('login') }}"
                                                                                            class="text-decoration-underline">@lang('Login')</a>
                                                            </div>
                                                            <div class="branch branch-3"><a
                                                                    href="{{ route('register') }}"
                                                                    class="text-decoration-underline">@lang('Register')</a>
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
                                <div class="side-box">
                                    <h4>@lang('Investor')</h4>
                                    <div class="owl-carousel property-agents">
                                        @foreach($investors as $investor)
                                            <div class="agent-box-wrapper">
                                                <div class="agent-box">
                                                    <div class="img-box">
                                                        <img
                                                            src="{{ getFile($investor->image_driver, $investor->image) }}"
                                                            class="img-fluid profile"
                                                            alt="@lang('Not Found')"
                                                        />
                                                    </div>
                                                    <div class="text-box">
                                                        <a href="{{ route('investor.profile', [$investor->id, @slug($investor->username)]) }}"
                                                           class="agent-name">
                                                            {{ $investor->fullname }}
                                                        </a>
                                                        <span>@lang('Agent of Property')</span>
                                                    </div>
                                                </div>

                                                <ul>
                                                    <li>
                                                        <i class="fal fa-building"></i>
                                                        <span>
                                                            {{ $investor->invests_count }}
                                                            @if($investor->invests_count == 1)
                                                                @lang('Property')
                                                            @else
                                                                @lang('Properties')
                                                            @endif
                                                        </span>
                                                    </li>

                                                    @if($investor->address)
                                                        <li>
                                                            <i class="fal fa-map-marker-alt" aria-hidden="true"></i>
                                                            <span>{{ $investor->address }}</span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="side-box">
                                <h4>@lang('Latest Properties')</h4>
                                @foreach($latestProperties as $property)
                                    <div class="property-side-box">
                                        <div class="img-box">
                                            <img class="img-fluid"
                                                 src="{{ getFile($property->driver, $property->thumbnail) }}"
                                                 alt=""/>
                                        </div>
                                        <div class="text-box">
                                            <a href="{{ route('property.details',[$property->id, @slug($property->title)]) }}"
                                               class="title">{{ \Illuminate\Support\Str::limit($property->title, 20)  }}</a>
                                            <p class="address"><i class="fal fa-map-marker-alt"></i>
                                                @lang(optional($property->address)->title)
                                            </p>
                                            <h5 class="price">{{ @$property->investmentAmount }}</h5>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset(template(true).'css/owl.carousel.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset(template(true).'css/owl.theme.default.min.css') }}"/>
@endpush

@push('extra-js')
    <!-- fancybox slider -->
    <script src="{{ asset(template(true).'js/fancybox.umd.js') }}"></script>
@endpush

@push('script')
    <script src="{{ asset(template(true).'js/carousel.js') }}"></script>
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
                            console.log(app.links);
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
