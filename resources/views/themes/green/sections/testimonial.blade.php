@if(isset($testimonial['single']) && $testimonial['multiple'])
    <!-- Testimonial section start -->
    <style>
        .testimonial-section::after {
            background-image: linear-gradient(rgb(var(--primary-color), 0.4), rgb(var(--primary-color), 0.4)), url({{ $testimonial['image'] }});
        }
    </style>

    <section class="testimonial-section">
        <div class="container">
            <div class="row g-4 align-items-center justify-content-between">
                <div class="col-lg-6">
                    <div class="right-side">
                        @if(isset($testimonial['single']))
                            <div class="text-center text-md-start">
                                <div class="section-subtitle" data-aos="fade-up" data-aos-duration="500"><img
                                        src="{{ asset(template(true).'img/sparkle.png') }}"
                                        alt=""> @lang($testimonial['single']['title'])
                                </div>
                                <h2 class="section-title" data-aos="fade-up" data-aos-duration="700">
                                    @lang($testimonial['single']['sub_title'])
                                </h2>
                                <p class="mt-10 mb-10 cmn-para-text" data-aos="fade-up" data-aos-duration="900">
                                    @lang($testimonial['single']['short_description'])
                                </p>
                            </div>
                        @endif

                        @if(isset($testimonial['multiple']))
                            <div
                                class="owl-carousel owl-theme testimonial-carousel {{(session()->get('rtl') == 1) ? 'testimonials-rtl': 'testimonials'}}">
                                @foreach($testimonial['multiple'] as $key => $data)

                                    <div class="item" data-aos="fade-up" data-aos-duration="500">
                                        <div class="testimonial-box">
                                            <ul class="reviews">
                                                <li>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="fa-solid fa-star {{ $i <= $data['review'] ? 'active' : '' }}"></i>
                                                    @endfor
                                                </li>
                                            </ul>

                                            <div class="quote-area">
                                                <p>{!! $data['description'] !!}</p>
                                            </div>
                                            <div class="profile-box">
                                                <div class="profile-thumbs">
                                                    <img
                                                        src="{{ getFile($data['media']->image->driver, $data['media']->image->path) }}"
                                                        alt=""/>
                                                </div>
                                                <div class="profile-title">
                                                    <h6 class="mb-0">@lang($data['name'])</h6>
                                                    <p class="mb-0">@lang($data['designation'])</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="left-side text-center" data-aos="fade-up" data-aos-duration="900">
                        <h3>
                            @lang($testimonial['single']['background_title'])
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial section end -->
@endif



