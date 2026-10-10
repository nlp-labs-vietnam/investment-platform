@extends(template().'layouts.app')
@section('title', trans('property'))

@section('content')
    <!-- shop section -->
    <section class="shop-section">
        <div class="container">
            <div class="row g-lg-5">
                <div class="col-lg-3">
                    <form action="{{ route('property') }}" method="get">
                        <div class="filter-area">
                            <div class="filter-box">
                                <h5>@lang('Search Property')</h5>
                                <div class="row g-3">
                                    <div class="input-box col-12">
                                        <input type="text" class="form-control" name="name"
                                               value="{{ old('name', request()->name) }}" autocomplete="off"
                                               placeholder="@lang('What are you looking for?')"/>
                                    </div>

                                    <div class="input-box col-12">
                                        <select class="js-example-basic-single form-control" name="location">
                                            <option selected disabled>@lang('Select Location')</option>
                                            @foreach($addresses as $address)
                                                <option value="{{ $address->id }}"
                                                        @if(request()->location == $address->id) selected @endif>@lang($address->title)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- SEARCH BY Amenities -->
                            <div class="filter-box">
                                <h5>@lang('Amenities')</h5>
                                <div class="check-box searchAmenities">
                                    @foreach($amenities as $amenity)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="amenity_id[]"
                                                   @if(isset(request()->amenity_id))
                                                       @foreach(request()->amenity_id as $data)
                                                           @if($data == $amenity->id) checked @endif
                                                   @endforeach
                                                   @endif
                                                   value="{{ $amenity->id }}" id="amenity{{ $amenity->id }}"/>
                                            <label class="form-check-label" for="amenity{{ $amenity->id }}"><i
                                                    class="{{ $amenity->icon }}"
                                                    aria-hidden="true"></i> @lang($amenity->title)
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                            <div class="filter-box">
                                <h5>@lang('Filter By Ratings')</h5>
                                <div class="check-box">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               @if(isset(request()->rating))
                                                   @foreach(request()->rating as $data)
                                                       @if($data == 5) checked @endif
                                               @endforeach
                                               @endif
                                               value="5" name="rating[]" id="rating1"/>

                                        <label class="form-check-label" for="rating1">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               @if(isset(request()->rating))
                                                   @foreach(request()->rating as $data)
                                                       @if($data == 4) checked @endif
                                               @endforeach
                                               @endif
                                               name="rating[]" value="4" id="rating2"/>
                                        <label class="form-check-label" for="rating2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               @if(isset(request()->rating))
                                                   @foreach(request()->rating as $data)
                                                       @if($data == 3) checked @endif
                                               @endforeach
                                               @endif
                                               value="3" name="rating[]" id="rating3"/>
                                        <label class="form-check-label" for="rating3">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               @if(isset(request()->rating))
                                                   @foreach(request()->rating as $data)
                                                       @if($data == 2) checked @endif
                                               @endforeach
                                               @endif
                                               value="2" name="rating[]" id="rating4"/>
                                        <label class="form-check-label" for="rating4">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               @if(isset(request()->rating))
                                                   @foreach(request()->rating as $data)
                                                       @if($data == 1) checked @endif
                                               @endforeach
                                               @endif
                                               value="1" name="rating[]" id="rating5"/>
                                        <label class="form-check-label" for="rating5">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="filter-box">
                                <button class="btn-custom w-100">@lang('filter now')</button>
                            </div>

                        </div>
                    </form>
                </div>

                @if(isset($properties) && count($properties) > 0)
                    <div class="col-lg-9">
                        <div class="row g-4 mb-5">
                            @foreach($properties as $key => $property)
                                @if(!$property->rud()['runningProperties'])
                                    <div class="col-md-6 col-lg-6">
                                        @include(template().'partials.propertyBox')
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                {{ $properties->appends($_GET)->links() }}
                            </ul>
                        </nav>
                    </div>
                @else
                    <div class="custom-not-found">
                        <img src="{{ asset(template(true).'img/no_data_found.png') }}" alt="not found" class="img-fluid">
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Modal -->
    @push('frontendModal')
        @include(template().'partials.investNowModal')
    @endpush
@endsection

@push('script')
    <script src="{{ asset(template(true).'js/investNow.js') }}"></script>
    <script>
        "use strict";
        var min = '{{$min}}'
        var max = '{{$max}}'
        var minRange = '{{$minRange}}'
        var maxRange = '{{$maxRange}}'

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

            $(".js-range-slider").ionRangeSlider({
                type: "double",
                min: min,
                max: max,
                from: minRange,
                to: maxRange,
                grid: true,
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
@endpush
