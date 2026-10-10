@if(count($properties) > 0)
    <div class="row g-4">
        @foreach($properties as $key => $property)
            <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="900">
                @include(template().'partials.propertyBox')
            </div>
        @endforeach
    </div>
    <nav aria-label="Page navigation example mt-5">
        <ul class="pagination justify-content-center mt-4">
            {{ $properties->appends($_GET)->links() }}
        </ul>
    </nav>
@else
    <div class="card">
        <div class="car-body">
            <div class="text-center p-4">
                <img class="dataTables-image mb-3 empty-state-img"
                     src="{{ asset(template(true).'img/oc-error.svg') }}"
                     alt="Image Description" data-hs-theme-appearance="default">
                <p class="mb-0">@lang('No data to show')</p>
            </div>
        </div>
    </div>
@endif

@push('loadModal')
    @include(template().'partials.investNowModal')
@endpush

@push('script')
    <script src="{{ asset(template(true).'js/investNow.js') }}"></script>
@endpush
