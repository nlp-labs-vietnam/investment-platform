@extends(template().'layouts.app')
@section('title', trans('Investor Profile'))

@section('content')
    <!-- Investor profile section -->
    <!-- Investor profile section start -->
    <section class="investor-profile-section">
        <div class="container">
            <div class="row g-4 g-xxl-5">
                <div class="col-lg-4">
                    <div class="sidebar">

                        <div class="investor-profile-box h-25">
                            <div class="img-box">
                                <img src="{{ getFile($investorInfo->image_driver, $investorInfo->image) }}" alt="">
                            </div>
                            <div class="text-box">
                                <h4>@lang($investorInfo->fullname)</h4>
                                <p>@lang('Member since') {{ $investorInfo->created_at->format('M Y') }}</p>
                                @if($investorInfo->address)
                                    <p class="mb-0 contact-item align-items-center justify-content-center gap-1"><i
                                            class="fa-regular fa-location-dot"></i>@lang($investorInfo->address)</p>
                                @endif
                                <div class="total-property-count mt-10">@lang('Total Properties')
                                    ({{ $investorInfo->countTotalInvestment() }})
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-widget-area mt-30">
                            <h4 class="widget-title">@lang('Send a Message')</h4>
                            <form action="{{ route('user.sendMessageToPropertyInvestor') }}" method="post">
                                @csrf
                                <input type="hidden" name="investor_id" value="{{ $investorInfo->id }}">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <input type="text" name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="exampleFormControlInput1"
                                               @if(\Auth::check() == true)
                                                   @if(\Auth::user()->id == $investorInfo->id)
                                                       placeholder="@lang('Full name')"
                                               @else
                                                   value="@lang(\Illuminate\Support\Facades\Auth::user()->fullname)"
                                               @endif
                                               @else
                                                   placeholder="@lang('Full name')"
                                            @endif>
                                    </div>

                                    <div class="col-12">
                                    <textarea class="form-control @error('message') is-invalid @enderror"
                                              id="exampleFormControlTextarea1"
                                              placeholder="@lang('Your message')">
                                    </textarea>
                                        <div class="invalid-feedback">
                                            @error('message') @lang($message) @enderror
                                        </div>
                                    </div>

                                    <button type="submit" class="kew-btn aos-init aos-animate" data-aos="fade-up"
                                            data-aos-duration="500">
                                        <span class="kew-text">@lang('Submit')</span>
                                        <div class="kew-arrow">
                                            <div class="kt-one"><i class="fa-regular fa-arrow-right-long"></i></div>
                                            <div class="kt-two"><i class="fa-regular fa-arrow-right-long"></i></div>
                                        </div>
                                    </button>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="col-lg-8">
                    <!-- Cmn tabs start -->
                    <div class="cmn-tabs2">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="Description-plan-tab" data-bs-toggle="pill"
                                        data-bs-target="#Description-plan" type="button" role="tab"
                                        aria-controls="Description-plan" aria-selected="false">
                                    @lang('Description')
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="Running-Properties-plan-tab" data-bs-toggle="pill"
                                        data-bs-target="#Running-Properties-plan" type="button" role="tab"
                                        aria-controls="Running-Properties-plan" aria-selected="false">
                                    @lang('Running Properties') ({{ count($properties) }})
                                </button>
                            </li>

                        </ul>
                    </div>
                    <!-- Cmn tabs end -->

                    <!-- Cmn tab content start -->
                    <div class="tab-content mt-30" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="Description-plan" role="tabpanel"
                             aria-labelledby="Description-plan-tab" tabindex="0">
                            <p>
                                @lang($investorInfo->bio)
                            </p>
                        </div>

                        <div class="tab-pane fade" id="Running-Properties-plan" role="tabpanel"
                             aria-labelledby="Running-Properties-plan-tab" tabindex="0">
                            <div class="row g-4 justify-content-center">
                                @foreach($properties as $key => $property)
                                    <div class="col-12">
                                        @include(template().'partials.propertyBox')
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Cmn tab content end -->
                </div>
            </div>
        </div>
    </section>
    <!-- Investor profile section end -->

    <!-- Modal -->
    @push('frontendModal')
        @include(template().'partials.investNowModal')
    @endpush
@endsection

@push('script')
    <script src="{{ asset(template(true).'js/investNow.js') }}"></script>
    <script>
        'use strict'
        var isAuthenticate = '{{ Auth::check() }}';

        $(document).ready(function () {
            $('.wishList').on('click', function () {
                var _this = this.id;
                let property_id = $(this).data('property');
                if (isAuthenticate == 1) {
                    wishList(property_id, _this);
                } else {
                    window.location.href = '{{route('login')}}';
                }
            });
        });

        function wishList(property_id = null, id = null) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('user.wishList') }}",
                type: "POST",
                data: {
                    property_id: property_id,
                },
                success: function (data) {
                    if (data.data == 'added') {
                        $(`.save${id}`).removeClass("fal fa-heart");
                        $(`.save${id}`).addClass("fas fa-heart");
                        Notiflix.Notify.Success("Wishlist added");
                    }
                    if (data.data == 'remove') {
                        $(`.save${id}`).removeClass("fas fa-heart");
                        $(`.save${id}`).addClass("fal fa-heart");
                        Notiflix.Notify.Success("Wishlist removed");
                    }
                },
            });
        }
    </script>
@endpush
