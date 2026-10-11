@extends(template().'layouts.app')
@section('title', trans('property'))

@section('content')
    <section class="products-section">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-lg-3">
                    <form action="{{ route('property') }}" method="get">
                        <div class="d-none d-lg-block">
                            <div class="sidebar-widget-area" data-aos="fade-up" data-aos-duration="500">
                                <h5 class="widget-title">@lang('Tìm kiếm dự án')</h5>
                                <div class="search-box">
                                    <input type="text" class="form-control" name="name"
                                           value="{{ old('name', request()->name) }}" autocomplete="off"
                                           placeholder="@lang('Tên dự án, từ khoá...')"/>
                                </div>
                                <div class="mt-15">
                                    <select class="cmn-select2" name="location">
                                        <option selected disabled>@lang('Chọn khu vực')</option>
                                        @foreach($addresses as $address)
                                            <option value="{{ $address->id }}"
                                                    @if(request()->location == $address->id) selected @endif>@lang($address->title)</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-15">
                                    <select class="cmn-select2" name="type">
                                        <option value="">@lang('Tất cả loại dự án')</option>
                                        <option value="solar" @if(request()->type == 'solar') selected @endif>☀️ @lang('Điện mặt trời')</option>
                                        <option value="ev" @if(request()->type == 'ev') selected @endif>🔋 @lang('Trạm sạc EV')</option>
                                        <option value="bess" @if(request()->type == 'bess') selected @endif>⚡ @lang('Lưu trữ BESS')</option>
                                        <option value="esg" @if(request()->type == 'esg') selected @endif>🌱 @lang('ESG & Carbon')</option>
                                        <option value="epc" @if(request()->type == 'epc') selected @endif>🏗️ @lang('Tổng thầu EPC')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sidebar-widget-area" data-aos="fade-up" data-aos-duration="500">
                                <h5 class="widget-title">@lang('Tính năng dự án')</h5>
                                <div class="checkbox-categories-area">
                                    <div class="section-inner">
                                        <div class="categories-list">
                                            @foreach($amenities as $amenity)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="amenity_id[]"
                                                           @if(isset(request()->amenity_id))
                                                               @foreach(request()->amenity_id as $data)
                                                                   @if($data == $amenity->id) checked @endif
                                                           @endforeach
                                                           @endif
                                                           id="amenity{{ $amenity->id }}">
                                                    <label class="form-check-label" for="amenity{{ $amenity->id }}">
                                                        <span class="icon-box"><i
                                                                class="{{ $amenity->icon }}"></i></span>
                                                        <span>@lang($amenity->title)</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sidebar-widget-area" data-aos="fade-up" data-aos-duration="500">
                                <h5 class="widget-title">@lang('Lọc theo đánh giá')</h5>
                                <div class="checkbox-categories-area">
                                    <div class="section-inner">
                                        <div class="categories-list">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       @if(isset(request()->rating))
                                                           @foreach(request()->rating as $data)
                                                               @if($data == 5) checked @endif
                                                       @endforeach
                                                       @endif
                                                       value="5" name="rating[]" id="rating1">

                                                <label class="form-check-label" for="rating1">
                                                    <ul class="star-list">
                                                        <li>
                                                            <i class="active fa-solid fa-star"></i>
                                                            <i class="active fa-solid fa-star"></i>
                                                            <i class="active fa-solid fa-star"></i>
                                                            <i class="active fa-solid fa-star"></i>
                                                            <i class="active fa-solid fa-star"></i>
                                                        </li>
                                                    </ul>
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       @if(isset(request()->rating))
                                                           @foreach(request()->rating as $data)
                                                               @if($data == 4) checked @endif
                                                       @endforeach
                                                       @endif
                                                       name="rating[]" value="4" id="rating2">
                                                <label class="form-check-label" for="rating2">
                                                    <ul class="star-list">
                                                        <li>
                                                            <i class="active fa-solid fa-star"></i>
                                                            <i class="active fa-solid fa-star"></i>
                                                            <i class="active fa-solid fa-star"></i>
                                                            <i class="active fa-solid fa-star"></i>
                                                        </li>
                                                    </ul>
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       @if(isset(request()->rating))
                                                           @foreach(request()->rating as $data)
                                                               @if($data == 3) checked @endif
                                                       @endforeach
                                                       @endif
                                                       value="3" name="rating[]" id="rating3">

                                                <label class="form-check-label" for="rating3">
                                                    <ul class="star-list">
                                                        <li>
                                                            <i class="active fa-solid fa-star"></i>
                                                            <i class="active fa-solid fa-star"></i>
                                                            <i class="active fa-solid fa-star"></i>
                                                        </li>
                                                    </ul>
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       @if(isset(request()->rating))
                                                           @foreach(request()->rating as $data)
                                                               @if($data == 2) checked @endif
                                                       @endforeach
                                                       @endif
                                                       value="2" name="rating[]" id="rating4">
                                                <label class="form-check-label" for="rating4">
                                                    <ul class="star-list">
                                                        <li>
                                                            <i class="active fa-solid fa-star"></i>
                                                            <i class="active fa-solid fa-star"></i>
                                                        </li>
                                                    </ul>
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       @if(isset(request()->rating))
                                                           @foreach(request()->rating as $data)
                                                               @if($data == 1) checked @endif
                                                       @endforeach
                                                       @endif
                                                       value="1" name="rating[]" id="rating5">
                                                <label class="form-check-label" for="rating5">
                                                    <ul class="star-list">
                                                        <li>
                                                            <i class="active fa-solid fa-star"></i>
                                                        </li>
                                                    </ul>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="kew-btn" data-aos="fade-up" data-aos-duration="500">
                                <span class="kew-text">@lang('Lọc dự án')</span>
                                <div class="kew-arrow">
                                    <div class="kt-one"><i class="fa-regular fa-arrow-right-long"></i></div>
                                    <div class="kt-two"><i class="fa-regular fa-arrow-right-long"></i></div>
                                </div>
                            </button>
                        </div>
                    </form>
                    <div class="mobile-filter-bar d-lg-none">
                        <button class="cmn-btn w-100" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
                            <i class="fa-regular fa-filter-list"></i> @lang('Filters')
                        </button>

                        <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1"
                             id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">@lang('Backdrop with
                                    scrolling')</h5>
                                <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas"
                                        aria-label="Close">
                                    <i class="fa-regular fa-arrow-left"></i></button>
                            </div>
                            <form action="{{ route('property') }}" method="get">
                                <div class="offcanvas-body">
                                    <div class="sidebar-widget-area">
                                        <h5 class="widget-title">@lang('Tìm kiếm dự án')</h5>
                                        <div class="search-box">
                                            <input type="text" class="form-control" name="name"
                                                   value="{{ old('name', request()->name) }}" autocomplete="off"
                                                   placeholder="@lang('Tên dự án, từ khoá...')"/>
                                        </div>
                                        <div class="mt-15">
                                            <select class="cmn-select2" name="location">
                                                <option selected disabled>@lang('Chọn khu vực')</option>
                                                @foreach($addresses as $address)
                                                    <option value="{{ $address->id }}"
                                                            @if(request()->location == $address->id) selected @endif>@lang($address->title)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mt-15">
                                            <select class="cmn-select2" name="type">
                                                <option value="">@lang('Tất cả loại dự án')</option>
                                                <option value="solar" @if(request()->type == 'solar') selected @endif>☀️ @lang('Điện mặt trời')</option>
                                                <option value="ev" @if(request()->type == 'ev') selected @endif>🔋 @lang('Trạm sạc EV')</option>
                                                <option value="bess" @if(request()->type == 'bess') selected @endif>⚡ @lang('Lưu trữ BESS')</option>
                                                <option value="esg" @if(request()->type == 'esg') selected @endif>🌱 @lang('ESG & Carbon')</option>
                                                <option value="epc" @if(request()->type == 'epc') selected @endif>🏗️ @lang('Tổng thầu EPC')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="sidebar-widget-area" data-aos="fade-up" data-aos-duration="500">
                                        <h5 class="widget-title">@lang('Tính năng dự án')</h5>
                                        <div class="checkbox-categories-area">
                                            <div class="section-inner">
                                                <div class="categories-list">
                                                    @foreach($amenities as $amenity)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="amenity_id[]"
                                                                   @if(isset(request()->amenity_id))
                                                                       @foreach(request()->amenity_id as $data)
                                                                           @if($data == $amenity->id) checked @endif
                                                                   @endforeach
                                                                   @endif
                                                                   id="amenity{{ $amenity->id }}">
                                                            <label class="form-check-label"
                                                                   for="amenity{{ $amenity->id }}">
                                                        <span class="icon-box"><i
                                                                class="{{ $amenity->icon }}"></i></span>
                                                                <span>@lang($amenity->title)</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sidebar-widget-area" data-aos="fade-up" data-aos-duration="500">
                                        <h5 class="widget-title">@lang('Lọc theo đánh giá')</h5>
                                        <div class="checkbox-categories-area">
                                            <div class="section-inner">
                                                <div class="categories-list">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               @if(isset(request()->rating))
                                                                   @foreach(request()->rating as $data)
                                                                       @if($data == 5) checked @endif
                                                               @endforeach
                                                               @endif
                                                               value="5" name="rating[]" id="rating1">

                                                        <label class="form-check-label" for="rating1">
                                                            <ul class="star-list">
                                                                <li>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                </li>
                                                            </ul>
                                                        </label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               @if(isset(request()->rating))
                                                                   @foreach(request()->rating as $data)
                                                                       @if($data == 4) checked @endif
                                                               @endforeach
                                                               @endif
                                                               name="rating[]" value="4" id="rating2">
                                                        <label class="form-check-label" for="rating2">
                                                            <ul class="star-list">
                                                                <li>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                </li>
                                                            </ul>
                                                        </label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               @if(isset(request()->rating))
                                                                   @foreach(request()->rating as $data)
                                                                       @if($data == 3) checked @endif
                                                               @endforeach
                                                               @endif
                                                               value="3" name="rating[]" id="rating3">

                                                        <label class="form-check-label" for="rating3">
                                                            <ul class="star-list">
                                                                <li>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                </li>
                                                            </ul>
                                                        </label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               @if(isset(request()->rating))
                                                                   @foreach(request()->rating as $data)
                                                                       @if($data == 2) checked @endif
                                                               @endforeach
                                                               @endif
                                                               value="2" name="rating[]" id="rating4">
                                                        <label class="form-check-label" for="rating4">
                                                            <ul class="star-list">
                                                                <li>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                </li>
                                                            </ul>
                                                        </label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               @if(isset(request()->rating))
                                                                   @foreach(request()->rating as $data)
                                                                       @if($data == 1) checked @endif
                                                               @endforeach
                                                               @endif
                                                               value="1" name="rating[]" id="rating5">
                                                        <label class="form-check-label" for="rating5">
                                                            <ul class="star-list">
                                                                <li>
                                                                    <i class="active fa-solid fa-star"></i>
                                                                </li>
                                                            </ul>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="kew-btn">
                                        <span class="kew-text">@lang('Lọc dự án')</span>
                                        <div class="kew-arrow">
                                            <div class="kt-one"><i class="fa-regular fa-arrow-right-long"></i></div>
                                            <div class="kt-two"><i class="fa-regular fa-arrow-right-long"></i></div>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    @if(isset($properties) && count($properties) > 0)
                        <div class="row g-4 justify-content-center">
                            @foreach($properties as $key => $property)
                                @if(!$property->rud()['runningProperties'])
                                    <div class="col-xl-6" data-aos="fade-up" data-aos-duration="500">
                                        @include(template().'partials.propertyBox')
                                    </div>
                                @endif
                            @endforeach
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end mt-4">
{{--                                    {{ $properties->appends($_GET)->links(template().'partials.pagination') }}--}}
                                </ul>
                            </nav>
                        </div>
                    @else
                        <div class="custom-not-found">
                            <img src="{{ asset(template(true).'img/no_data_found.png') }}" alt="not found"
                                 class="img-fluid">
                        </div>
                    @endif

                </div>
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
