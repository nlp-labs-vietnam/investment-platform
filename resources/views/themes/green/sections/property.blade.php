<!-- Featured section start -->
<section class="featured-section">
    <div class="container">
        @if(isset($property['single']))
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="section-subtitle mb-0" data-aos="fade-up" data-aos-duration="500"><img
                            src="{{ template(true).'img/sparkle.png' }}" alt=""> {{ $property['single']['title'] }}
                    </div>
                    <h2 class="section-title" data-aos="fade-up"
                        data-aos-duration="700">{{ $property['single']['sub_title'] }}</h2>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end" data-aos="fade-up" data-aos-duration="900">
                    <a href="{{ route('page', 'property') }}" class="kew-btn mt-20">
                        <span class="kew-text">@lang('View all properties')</span>
                        <div class="kew-arrow">
                            <div class="kt-one"><i class="fa-regular fa-arrow-right-long"></i></div>
                            <div class="kt-two"><i class="fa-regular fa-arrow-right-long"></i></div>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        <div class="mt-30">
            <div class="row g-4 g-xxl-5 justify-content-center">
                @if(isset($property['multiple']) && count($property['multiple']) > 0)
                    @foreach($property['multiple']->take(3) as $key => $property)
                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="500">
                            @include(template().'partials.propertyBox')
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>

@include(template().'partials.investNowModal')

<script src="{{asset(template(true).'js/jquery.min.js')}}"></script>
<script src="{{ template(true).'js/investNow.js' }}"></script>
<script>

    var isAuthenticate = '{{ Auth::check() }}';

    $(document).ready(function () {
        $('.wishList').on('click', function () {
            var _this = this.id;
            let property_id = $(this).data('property');
            if (isAuthenticate == 1) {
                wishList(property_id, _this);
            } else {
                window.location.href = '{{route('login')}}';
            }
        });
    });

    function wishList(property_id = null, id = null) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('user.wishList') }}",
            type: "POST",
            data: {
                property_id: property_id,
            },
            success: function (data) {
                if (data.data == 'added') {
                    $(`.save${id}`).removeClass("fal fa-heart");
                    $(`.save${id}`).addClass("fas fa-heart");
                    Notiflix.Notify.Success("Wishlist added");
                }
                if (data.data == 'remove') {
                    $(`.save${id}`).removeClass("fas fa-heart");
                    $(`.save${id}`).addClass("fal fa-heart");
                    Notiflix.Notify.Success("Wishlist removed");
                }
            },
        });
    }
</script>
