<!-- Feature section start -->
@if(isset($feature['multiple']) || isset($feature['single']))
    @php
        $featureLinks = [
            'NLP Energy'       => route('nlp-energy'),
            'NLP EV Charging'  => route('nlp-ev-charging'),
            'NLP Technology'   => route('nlp-technology'),
            'NLP ESG & Carbon' => route('nlp-esg-carbon'),
        ];
    @endphp
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
                    @php $link = $featureLinks[$value['title']] ?? route('ecosystem'); @endphp
                    <div class="col-lg-3 col-sm-6 feature-item">
                        <a href="{{ $link }}" style="text-decoration:none; color:inherit;">
                            <div class="feature-box" data-aos="fade-up" data-aos-duration="500" style="cursor:pointer; transition: transform .2s, box-shadow .2s;">
                                <div class="img-box">
                                    <img
                                        src="{{ getFile(optional($value['media']->image)->driver, optional($value['media']->image)->path) }}"
                                        alt="{{ $value['title'] }}">
                                </div>
                                <div class="text-box">
                                    <h5 class="mb-2">@lang($value['title'])</h5>
                                    <p class="mb-0">@lang($value['short_details'])</p>
                                    <span style="display:inline-block; margin-top:10px; font-size:.82rem; font-weight:600; color:#2d7a4f;">
                                        @lang('Tìm hiểu thêm') <i class="fa-regular fa-arrow-right-long ms-1"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Điều hướng hệ sinh thái --}}
            <div class="text-center mt-40" data-aos="fade-up" data-aos-duration="700">
                <a href="{{ route('ecosystem') }}" class="kew-btn mx-auto d-inline-flex">
                    <span class="kew-text">@lang('Xem toàn bộ hệ sinh thái NLP')</span>
                    <div class="kew-arrow">
                        <div class="kt-one"><i class="fa-regular fa-arrow-right-long"></i></div>
                        <div class="kt-two"><i class="fa-regular fa-arrow-right-long"></i></div>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endif
<!-- Feature section end -->
