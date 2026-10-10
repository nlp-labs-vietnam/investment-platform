@if(isset($statistics['single']) && $statistics['multiple'])
    <!-- commission section -->
    <section class="commission-section">
        <div class="container">
            @if($statistics['single'])
                <div class="row">
                    <div class="col">
                        <div class="header-text text-center">
                            <h5>@lang($statistics['single']['title'])</h5>
                            <h2>@lang($statistics['single']['sub_title'])</h2>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($statistics['multiple']))
                @if(count($statistics['multiple']) > 0)
                    <div class="row g-4 g-lg-5">
                        @foreach($statistics['multiple'] as $k => $data)
                            <div class="col-md-6 col-lg-3 box">
                                <div
                                    class="commission-box {{(session()->get('rtl') == 1) ? 'isRtl': 'noRtl'}}"
                                    data-aos="zoom-in"
                                    data-aos-duration="800"
                                    data-aos-anchor-placement="center-bottom">
                                    <h3>@lang($data['title'])</h3>
                                    <h5>@lang($data['description'])</h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif

        </div>
        <div class="shapes">
            <img src="{{ asset(template(true).'img/dot-square.png') }}" alt="" class="shape-1"/>
            <img src="{{ asset(template(true).'img/dot-square.png') }}" alt="" class="shape-2"/>
        </div>
    </section>
@endif
