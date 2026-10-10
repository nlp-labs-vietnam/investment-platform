@if(isset($statistics['single']) && $statistics['multiple'])
    <!-- Commission section start -->
    <section class="commission-section">
        <div class="container">
            @if($statistics['single'])
                <div class="row g-4 align-items-center">
                    <div class="col-md-6  text-center text-md-start">
                        <div class="section-subtitle" data-aos="fade-up" data-aos-duration="500"><img
                                src="{{ asset(template(true).'img/sparkle.png') }}"
                                alt=""> @lang($statistics['single']['title'])
                        </div>
                        <h2 data-aos="fade-up" data-aos-duration="700">@lang($statistics['single']['sub_title'])</h2>
                    </div>
                    <div class="col-md-6  text-center text-md-start" data-aos="fade-up" data-aos-duration="900">
                        {!! $statistics['single']['short_details'] !!}
                    </div>
                </div>
            @endif

            @if(isset($statistics['multiple']))
                <div class="mt-50">
                    @if(count($statistics['multiple']) > 0)
                        <div class="working-steps row g-4 g-sm-5 g-lg-4 justify-content-center">
                            @foreach($statistics['multiple'] as $k => $data)
                                <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-duration="500">
                                    <div class="commission-box">
                                        <div class="icon-area">
                                            <i class="{{ $data['font_icon'] }}"></i>
                                            <div class="number">{{ ++$k }}</div>
                                        </div>
                                        <div class="text-area">
                                            <p class="subtitle">@lang($data['description'])</p>
                                            <h3 class="title">@lang($data['title'])</h3>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="workflow-progress">
                                <div class="dot">
                                    <img src="{{ asset(template(true).'img/dot.png') }}" alt="">
                                </div>
                                <div class="map-icon">
                                    <img src="{{ asset(template(true).'img/location2.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
    <!-- Commission section end -->
@endif
