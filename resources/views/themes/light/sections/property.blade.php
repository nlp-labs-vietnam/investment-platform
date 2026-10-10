<section class="property-section">
    <div class="container">
        @if(isset($property['single']))
            <div class="row">
                <div class="col-12">
                    <div class="header-text text-center">
                        <h5>@lang($property['single']['title'])</h5>
                        <h2>@lang($property['single']['sub_title'])</h2>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($property['multiple']) && count($property['multiple']) > 0)
            <div class="row g-4 g-lg-5 justify-content-center">
                @foreach($property['multiple']->take(3) as $key => $property)
                    <div class="col-md-6 col-lg-4">
                        @include(template().'partials.propertyBox')
                    </div>
                @endforeach
            </div>
        @endif
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
