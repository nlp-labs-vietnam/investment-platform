@if(count($myProperties) > 0)
    <div class="col-lg-12">
        <div class="row g-4 mb-5">
            @foreach($myProperties as $key => $property)
                <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="900">
                    <div class="property-box">
                        <div class="img-box">
                            <a href="{{ route('property.details',[optional($property->property)->id, @slug(optional($property->property)->title)]) }}">
                                <img
                                    src="{{ getFile(optional($property->property)->driver, optional($property->property)->thumbnail) }}">
                            </a>
                            <div class="tag-box">
                                @if($property->status == 0 && $property->invest_status == 1)
                                    <div class="single-tag">@lang('Running')</div>
                                @elseif($property->status == 1 && $property->invest_status == 1)
                                    <div class="single-tag">@lang('Completed')</div>
                                @else
                                    <div class="single-tag">@lang('Due')</div>
                                @endif
                            </div>
                        </div>

                        <div class="text-box">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0 contact-item align-items-center gap-1"><i
                                        class="fa-regular fa-location-dot"></i> @lang(optional(optional($property->property)->address)->title)
                                </p>
                                <h4 class="mb-0">{{ currencyPosition($property->amount) }}</h4>
                            </div>
                            <a class="title"
                               href="{{ route('property.details',[optional($property->property)->id, @slug(optional($property->property)->title)]) }}">{{ \Illuminate\Support\Str::limit(optional($property->property)->title, 30)  }}</a>

                            <div class="meta-data mt-10">
                                <div class="review">
                                    <ul class="reviews d-flex align-items-center gap-2">
                                        @include(template().'partials.propertyReview',['property' => $property?->property])
                                    </ul>
                                </div>
                            </div>

                            <div class="amenities mt-15">
                                @foreach(optional($property->property)->limitamenity as $key => $amenity)
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
                                        @if(optional($property->property)->profit_type == 1)
                                            <div
                                                class="plan-title">{{ fractionNumber(getAmount(optional($property->property)->profit)) }}
                                                %
                                                (@lang('Fixed'))
                                            </div>
                                        @else
                                            <div
                                                class="plan-title">{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount(optional($property->property)->profit)) }}
                                                (@lang('Fixed'))
                                            </div>
                                        @endif
                                        <p class="mb-0">@lang('Profit Range')</p>
                                    </div>

                                    <div class="item">
                                        @if(optional($property->property)->is_return_type == 1)
                                            <div class="plan-title">@lang('Lifetime')</div>
                                        @else
                                            <div
                                                class="plan-title">{{ optional(optional($property->property)->managetime)->time }} @lang(optional(optional($property->property)->managetime)->time_type)</div>
                                        @endif
                                        <p class="mb-0">@lang('Return Interval')</p>
                                    </div>

                                    <div class="item">
                                        <div
                                            class="plan-title">{{ optional($property->property)->is_capital_back == 1 ? 'Yes' : 'No' }}</div>
                                        <p class="mb-0">@lang('Capital back')</p>
                                    </div>
                                </div>

                                <div class="mt-15 d-flex justify-content-between align-items-center">

                                    @if($property->propertyShare)
                                        <button type="button" class="contact-owner-btn disabled">
                                            @lang('Already Shared')
                                        </button>
                                    @else
                                        @if(optional($property->property)->is_investor == 1 && basicControl()->is_share_investment == 1)
                                            <button type="button" class="contact-owner-btn sendOffer
                                             {{ ($property->invest_status == 1 && $property->status == 1) || ($property->invest_status == 0) || (optional($property->property)->is_investor == 0) ? 'disabled' : '' }}"
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
                {{ $myProperties->appends($_GET)->links() }}
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
    <div class="modal fade" id="sendOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form action="" method="post" class="send_offer_modal">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">@lang('Set Share Amount')</h4>
                        <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-light fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="property-title"></h5>

                        <div id="formModal" class="investModalPaymentForm mt-3">
                            <input type="hidden" name="investment_id" class="investment_id">
                            <label for="">@lang('Share Amount')</label>
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

        $(document).on('click', '.sendOffer', function () {
            var propertysendOfferModal = new bootstrap.Modal(document.getElementById('sendOfferModal'))
            propertysendOfferModal.show();

            let dataRoute = $(this).data('route');
            let dataProperty = $(this).data('property');

            $('.send_offer_modal').attr('action', dataRoute);
            $('.property-title').text(`Property: ${dataProperty}`);
            $('.show-currency').text("{{basicControl()->base_currency}}");
        });

    </script>
@endpush
