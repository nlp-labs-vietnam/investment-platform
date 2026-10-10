@if(isset($hero['single']))
    <!-- Hero section start -->
    <div class="hero-section">
        <div class="container">
            <div class="hero-top">
                <h1 class="hero-title" data-aos="fade-up" data-aos-duration="500">@lang(@$hero['single']['title']) <img
                        src="{{$hero['image']}}" alt=""> @lang($hero['single']['sub_title']) <img
                        src="{{$hero['image2']}}" alt="">
                </h1>
            </div>
            <div class="hero-bottom">
                <div class="row g-4">

                    @php
                        $input = [
                            $hero['single']['happy_client_title'],
                            $hero['single']['review_title'],
                        ];

                        // Happy Client Title
                        preg_match('/(\d+)(\D+)/', $input[0], $matches_happy_client);
                        $happy_client_title_number = $matches_happy_client[1] ?? null;
                        $happy_client_title_string = $matches_happy_client[2] ?? null;

                        // Review Title
                        preg_match('/(\d+)(\D+)/', $input[1], $matches_review);
                        $review_title_number = $matches_review[1] ?? null;
                        $review_title_string = $matches_review[2] ?? null;
                    @endphp


                    <div class="col-lg-10">
                        <div class="img-box" data-aos="fade-up" data-aos-duration="700">
                            <img src="{{$hero['image3']}}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="statistics-container" data-aos="fade-up" data-aos-duration="900">
                            <div class="single-box">
                                <h3 class="title"><span
                                        class="statistics-counter">{{ $happy_client_title_number }}</span>{{ $happy_client_title_string }}
                                </h3>
                                <p class="mb-0">@lang($hero['single']['happy_client_sub_title'])</p>
                            </div>
                            <div class="single-box">
                                <h3 class="title"><span
                                        class="statistics-counter">{{ $review_title_number }}</span>{{ $review_title_string }}
                                </h3>
                                <p class="mb-0">@lang($hero['single']['review_sub_title'])</p>
                            </div>


                            <div class="single-box">
                                <h3 class="title">{{ basicControl()->currency_symbol }}<span
                                        class="statistics-counter">{{ $hero['single']['welcome_bonus_title'] }}</span>
                                </h3>
                                <p class="mb-0">@lang($hero['single']['welcome_bonus_sub_title'])</p>
                            </div>
                        </div>
                        <a href="{{ route('page', 'property') }}" class="round-box-content mt-20 mx-auto"
                           data-aos="fade-up"
                           data-aos-duration="1100">
                            <span class="curved-circle">@lang('start explore now•')</span>
                            <div class="inner-icon">
                                <i class="fa-light fa-arrow-up"></i>
                            </div>
                        </a>

                    </div>
                </div>

            </div>

        </div>
    </div>
    <!-- Hero section end -->

@endif
