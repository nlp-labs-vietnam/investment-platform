@if(count($properties) > 0)
    <div class="col-lg-12">
        <div class="row g-4 mb-5">
            @foreach($properties as $key => $property)
                <div class="col-md-4 col-lg-4">
                    @include(template().'partials.propertyBox')
                </div>
            @endforeach
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{ $properties->appends($_GET)->links() }}
            </ul>
        </nav>
    </div>
@else
    <div class="custom-not-found mt-5">
        <img src="{{ asset(template(true).'img/no_data_found.png') }}" alt="@lang('not found')" class="img-fluid">
    </div>
@endif


@push('loadModal')
    @include(template().'partials.investNowModal')
@endpush


@push('script')
    <script src="{{ asset(template(true).'js/investNow.js') }}"></script>
@endpush
