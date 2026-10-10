@if(isset($faq['single']) && $faq['multiple'])
    <!-- Faq section start -->
    <section class="faq-section">
        <div class="container">
            <div class="row g-4 g-sm-5 align-items-center">
                @if(isset($faq['single']))
                    <div class="col-lg-6" data-aos="fade-up" data-aos-duration="500">
                        <div class="text-center text-lg-start">
                            <div class="section-subtitle" data-aos="fade-up" data-aos-duration="500"><img
                                    src="{{ asset(template(true).'img/sparkle.png') }}" alt="">
                                @lang($faq['single']['title'])
                            </div>
                            <h2 data-aos="fade-up" data-aos-duration="900">
                                @lang($faq['single']['sub_title'])
                            </h2>
                            <p class="mb-0 cmn-para-text" data-aos="fade-up" data-aos-duration="1100">
                                {!! $faq['single']['short_details'] !!}
                            </p>
                        </div>
                    </div>
                @endif

                @if(isset($faq['multiple']))
                    <div class="col-lg-6">
                        <div class="faq-content">
                            <div class="accordion" id="accordionExample3">
                                @foreach($faq['multiple'] as $k => $data)
                                    <div class="accordion-item" data-aos="fade-up" data-aos-duration="500">
                                        <h2 class="accordion-header {{(session()->get('rtl') == 1) ? 'isRtl': ''}}" id="headinSeven{{$k}}">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseSeven{{$k}}" aria-expanded="{{($k == 0) ? 'true' : 'false'}}"
                                                    aria-controls="collapseSeven{{$k}}">
                                                @lang($data['title'])
                                            </button>
                                        </h2>
                                        <div id="collapseSeven{{$k}}" class="accordion-collapse collapse {{($k == 0) ? 'show' : ''}}"
                                             aria-labelledby="headinSeven{{$k}}" data-bs-parent="#accordionExample3">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <p>
                                                        @lang($data['description'])
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- Faq section end -->
@endif



