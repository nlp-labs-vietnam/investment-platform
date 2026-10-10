@if(isset($hero['single']))
    <section class="home-section">
        <div class="overlay h-100">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-xl-7">
                        <div class="text-box">
                            <h4> @lang(@$hero['single']['title']) </h4>
                            <h2> @lang(@$hero['single']['sub_title']) </h2>
                            <h2 class="primary_color">
                                @lang(@$hero['single']['another_sub_title'])
                            </h2>
                            <p>
                                {!! @$hero['single']['short_description'] !!}
                            </p>
                            <a class="btn-custom mt-4 btn text-white"
                               href="#">@lang(@$hero['single']['button_name'])</a>
                        </div>
                    </div>

                    <div class="col-xl-5 d-none d-xl-block">
                        <div class="img-main-wrapper">
                            <div class="img-wrapper">
                                <div class="img-box img-1">
                                    <img
                                        src="{{$hero['image']}}"
                                        alt=""/>
                                </div>
                                <div class="img-box img-2">
                                    <img
                                        src="{{$hero['image2']}}"
                                        alt=""/>
                                </div>
                                <div class="img-box img-3">
                                    <img
                                        src="{{$hero['image3']}}"
                                        alt=""/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
