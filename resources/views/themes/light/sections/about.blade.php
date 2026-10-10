@if(isset($about['single']))

    <!-- about section -->
    <section class="about-section">
        <div class="container">
            <div class="row gy-5 g-lg-5">
                <div class="col-md-6">
                    <div class="img-wrapper">
                        <div class="img-box img-1">
                            <img src="{{ $about['image'] }}" alt=""/>
                        </div>
                        <div class="img-box img-2">
                            <img src="{{ $about['image2'] }}" alt=""/>
                        </div>
                        <img class="shape" src="{{ asset(template(true).'img/dot-square.png') }}" alt=""/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="header-text mb-4">
                        <h5>@lang($about['single']['title'])</h5>
                        <h2>@lang($about['single']['sub_title'])</h2>
                        <h2>@lang($about['single']['short_title'])</h2>
                    </div>
                    <div class="text-box">
                        <h4>
                            @lang($about['single']['short_title'])
                        </h4>
                        <p>
                            <h2>@lang($about['single']['short_description'])</h2>
                        </p>
                        <a class="btn-custom mt-4 btn text-white" href="{{ route('page', 'about') }}">@lang('Discover More')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
