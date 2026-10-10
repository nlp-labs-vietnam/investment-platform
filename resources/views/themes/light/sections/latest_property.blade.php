<!-- latest property -->
<section class="latest-property">
    <div class="container">
        <div class="row">
            @if(isset($latest_property['single']))
                <div class="col-12">
                    <div class="header-text text-center">
                        <h5>@lang($latest_property['single']['title'])</h5>
                        <h2>@lang($latest_property['single']['sub_title'])</h2>
                    </div>
                </div>
            @endif
        </div>

        @if($latest_property['multiple'] && count($latest_property['multiple']) > 0)
            <div class="row">
                @foreach($latest_property['multiple'] as $key => $property)
                    <div class="col-lg-6 mb-4">
                        @include(template().'partials.propertyBox')
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>


