<!-- Policy section start -->
@if(isset($privacy_policy['single']))
    <section class="privacy-policy">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="header-text text-center">
                        <h5>@lang($privacy_policy['single']['heading'])</h5>
                        <h2>@lang($privacy_policy['single']['short_title'])</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-4 getLink-details">
                    {!! $privacy_policy['single']['description'] !!}
                </div>
            </div>
        </div>
        <div class="shapes">
            <img src="{{ asset(template(true).'img/dot-square.png') }}" alt="" class="shape-1"/>
            <img src="{{ asset(template(true).'img/dot-square.png') }}" alt="" class="shape-2"/>
        </div>
    </section>
@endif

<!-- Policy section end -->
