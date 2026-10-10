<!-- Feature section start -->
@if(isset($feature['multiple']) || isset($feature['single']))
    <section class="feature-section">
        <div class="container">
            @if(isset($feature['single']))
                <div class="row g-4 align-items-center">
                    <div class="col-md-6">
                        <h2 class="section-title text-center text-md-start mb-0" data-aos="fade-up"
                            data-aos-duration="500">
                            @lang($feature['single']['title'])
                        </h2>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-0 text-center text-md-start" data-aos="fade-up" data-aos-duration="700">
                            {!!  $feature['single']['short_details'] !!}
                        </p>
                    </div>
                </div>
            @endif

            <div class="row g-4 mt-20">
                @foreach($feature['multiple'] as $key => $value)
                    <div class="col-lg-3 col-sm-6 feature-item">
                        <div class="feature-box" data-aos="fade-up" data-aos-duration="500">
                            <div class="img-box">
                                <img
                                    src="{{ getFile(optional($value['media']->image)->driver, optional($value['media']->image)->path) }}"
                                    alt="">
                            </div>
                            <div class="text-box">
                                <h5 class="mb-2">@lang($value['title'])</h5>
                                <p class="mb-0">@lang($value['short_details'])</p>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
<!-- Feature section end -->
