@if(isset($about['single']))
    <style>
        .about-section::after {
            background-image: linear-gradient(rgb(var(--primary-color), 0.4), rgb(var(--primary-color), 0.4)), url({{ $about['image'] }});
        }
    </style>
    <!-- About section start -->
    <section class="about-section">
        <div class="container">
            <div class="row g-4 justify-content-between align-items-center">
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="left-side">
                        <h3 data-aos="fade-up" data-aos-duration="500">{{ $about['single']['background_title'] }}</h3>
                        <h2 class="mt-15" data-aos="fade-up"
                            data-aos-duration="700">{{ $about['single']['background_short_title'] }}</h2>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="right-side">
                        <div class="section-subtitle" data-aos="fade-up" data-aos-duration="500"><img
                                src="{{ asset(template(true).'img/sparkle.png') }}" alt="">{{ $about['single']['title'] }}</div>
                        <h2 class="mb-15 mx-lg-start mx-auto" data-aos="fade-up"
                            data-aos-duration="700">{{ $about['single']['sub_title'] }}
                        </h2>
                        <h5 data-aos="fade-up" data-aos-duration="900">{{ $about['single']['short_title'] }}</h5>
                        <p class="mt-10" data-aos="fade-up"
                           data-aos-duration="900"> {!! $about['single']['short_description'] !!}</p>

                        <div data-aos="fade-up" data-aos-duration="900">
                            <a href="{{ route('page', 'about') }}" class="kew-btn mt-20">
                                <span class="kew-text">@lang('Discover more')</span>
                                <div class="kew-arrow">
                                    <div class="kt-one"><i class="fa-regular fa-arrow-right-long"></i></div>
                                    <div class="kt-two"><i class="fa-regular fa-arrow-right-long"></i></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About section end -->
@endif
