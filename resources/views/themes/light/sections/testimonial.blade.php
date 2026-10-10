@if(isset($testimonial['single']) && $testimonial['multiple'])
    <!-- testimonial section -->

    <section class="testimonial-section">

        <div class="container">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-6">
                    <div class="testimonial-wrapper">
                        @if(isset($testimonial['single']))
                            <div class="header-text">
                                <h5>@lang($testimonial['single']['title'])</h5>
                                <h3>@lang($testimonial['single']['sub_title'])</h3>
                                <div class="quote">
                                    <img src="{{ asset(template(true).'img/quote-2.png') }} " alt=""/>
                                </div>
                            </div>
                        @endif

                        @if(isset($testimonial['multiple']))
                            <div
                                class="{{(session()->get('rtl') == 1) ? 'testimonials-rtl': 'testimonials'}} owl-carousel">
                                @foreach($testimonial['multiple'] as $key => $data)
                                    <div class="review-box">
                                        <div class="text">
                                            <p>
                                                @lang($data['description'])
                                            </p>

                                            <div class="top">
                                                <h4>
                                                    @lang($data['name'])
                                                </h4>
                                                <span class="title">
                                                    <a class="organization"
                                                       href="javascript:void(0)">
                                                        @lang($data['designation'])
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="client-img">
                        <img
                            src="{{ getFile($testimonial['single']['media']->image->driver, $testimonial['single']['media']->image->path) }}"
                            alt="" class="img-fluid"/>
                        <img class="shape" src="{{ asset(template(true).'img/dot-square.png') }}" alt="@lang('not found')"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif



