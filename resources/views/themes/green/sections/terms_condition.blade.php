<!-- Policy section start -->
@if(isset($terms_condition['single']))
    <!-- Policy section start -->
    <section class="policy-section">
        <img class="shape2" src="{{ asset(template(true).'img/net-shape.png') }}" alt="NLP Group">
        <img class="shape3" src="{{ asset(template(true).'img/net-left.png') }}" alt="NLP Group">
        <div class="container">
            <div class="row">
                <div class="policy-section-inner">
                    <h3>@lang($terms_condition['single']['heading'])</h3>
                    <small>@lang('last updated on') {{ dateTime($terms_condition['single']['created_at']) }}</small>
                    <br>
                    <br>

                    <p>
                        @lang($terms_condition['single']['short_title'])
                    </p>
                    <br>
                    {!! $terms_condition['single']['description'] !!}
                </div>
            </div>
        </div>
    </section>
    <!-- Policy section end -->
@endif

