<!-- latest property section start -->
<section class="latest-property-section">
    <div class="container">
        @if(isset($latest_property['single']))
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="section-subtitle" data-aos="fade-up" data-aos-duration="500"><img
                            src="{{ asset(template(true).'img/sparkle.png') }}"
                            alt=""> @lang($latest_property['single']['title'])
                    </div>
                    <h2 class="section-title mb-0" data-aos="fade-up"
                        data-aos-duration="700">@lang($latest_property['single']['sub_title'])</h2>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end" data-aos="fade-up" data-aos-duration="900">
                    <a href="{{ route('page', 'property') }}" class="kew-btn">
                        <span class="kew-text">@lang('View all properties')</span>
                        <div class="kew-arrow">
                            <div class="kt-one"><i class="fa-regular fa-arrow-right-long"></i></div>
                            <div class="kt-two"><i class="fa-regular fa-arrow-right-long"></i></div>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        @if($latest_property['multiple'] && count($latest_property['multiple']) > 0)
            <div class="mt-30">
                <div class="row g-4">
                    @foreach($latest_property['multiple'] as $key => $property)
                        <div class="col-lg-6" data-aos="fade-up" data-aos-duration="900">
                            @include(template().'partials.propertyBox')
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
<!-- latest property section end -->


