<!-- Policy section start -->
@if(isset($privacy_policy['single']))
    <!-- Policy section start -->
    <section class="policy-section">
        <img class="shape2" src="{{ asset(template(true).'img/net-shape.png') }}" alt="NLP Group">
        <img class="shape3" src="{{ asset(template(true).'img/net-left.png') }}" alt="NLP Group">
        <div class="container">
            <div class="row">
                <div class="policy-section-inner">
                    <h3>@lang($privacy_policy['single']['heading'])</h3>
                    <small>@lang('last updated on') {{ dateTime($privacy_policy['single']['created_at']) }}</small>
                    <br>
                    <br>
                    <p>
                        @lang($privacy_policy['single']['short_title'])
                    </p>
                    <br>
                    {!! $privacy_policy['single']['description'] !!}
                </div>
            </div>
        </div>
    </section>
    <!-- Policy section end -->
@endif

<!-- Policy section end -->
