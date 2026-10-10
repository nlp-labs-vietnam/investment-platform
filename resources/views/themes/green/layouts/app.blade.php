<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Favicons -->
    <link href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" rel="icon">

    <title> {{basicControl()->site_title}} @if(isset($pageSeo['page_title']))
            | {{str_replace(basicControl()->site_title, ' ',$pageSeo['page_title'])}}
        @else
            | @yield('title')
        @endif
    </title>

    @include(template() . 'partials.seo')

    <link rel="stylesheet" href="{{asset(template(true).'css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/slick.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/slick-theme.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/fancybox.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/aos.css')}}">

    @stack('css-lib')

    <!-- Template Main CSS File -->
    <link rel="stylesheet" href="{{asset(template(true).'css/style.css')}}">
    <script src="{{asset(template(true).'js/fontawesomepro.js')}}"></script>
    <script src="{{asset(template(true).'js/modernizr.custom.js')}}"></script>
    @stack('style')
</head>

{{--<body onload="preloaderFunction()" class="">--}}
<body  class="">
<!-- Preloader section start -->
{{--<div id="preloader">--}}
{{--    <img src="{{ asset(template(true).'img/preloader/house.png') }}" alt="">--}}
{{--</div>--}}
<!-- Preloader section end -->

@include(template().'partials.header')

@include(template().'partials.frontend_bottom_nav')

@if(basicControl()->theme == 'light')
    @if(!Str::contains(request()->url(), 'developer') && url('/') != request()->url())
        @include(template().'partials.banner')
    @endif
@else
    @if(
    !Str::contains(request()->url(), 'developer') &&
    url('/') != request()->url() &&
    Route::currentRouteName() != 'property.details'
)
        @include(template().'partials.banner')
    @endif
@endif


<!-- #main -->
@yield('content')
<!-- End #main -->


@include(template().'partials.footer')


@stack('frontendModal')

@include('plugins')
<script src="{{asset(template(true).'js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset(template(true).'js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset(template(true).'js/owl.carousel.min.js')}}"></script>
<script src="{{asset(template(true).'js/swiper-bundle.min.js')}}"></script>
<script src="{{asset(template(true).'js/slick.min.js')}}"></script>
<script src="{{asset(template(true).'js/select2.min.js')}}"></script>
<script src="{{asset(template(true).'js/fancybox.umd.js')}}"></script>
<script src="{{asset(template(true).'js/parallax-scroll.js')}}"></script>
<script src="{{asset(template(true).'js/jquery.counterup.min.js')}}"></script>
<script src="{{asset(template(true).'js/jquery.waypoints.min.js')}}"></script>
<script src="{{asset(template(true).'js/isotope.pkgd.min.js')}}"></script>
<script src="{{asset(template(true).'js/isotope.js')}}"></script>
<script src="{{asset(template(true).'js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset(template(true).'js/aos.js')}}"></script>

@stack('extra-js')

@stack('js-lib')

<script src="{{asset('assets/global/js/pusher.min.js')}}"></script>
<script src="{{asset('assets/global/js/vue.min.js')}}"></script>
<script src="{{asset('assets/global/js/axios.min.js')}}"></script>

<script src="{{asset(template(true).'js/main.js')}}"></script>

<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

@stack('script')

@auth
    <script>
        'use strict';
        let pushNotificationArea = new Vue({
            el: "#pushNotificationArea",
            data: {
                items: [],
            },
            beforeMount() {
                this.getNotifications();
                this.pushNewItem();
            },
            methods: {
                getNotifications() {
                    let app = this;
                    axios.get("{{ route('user.push.notification.show') }}")
                        .then(function (res) {
                            app.items = res.data;
                        })
                },
                readAt(id, link) {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAt', 0) }}";
                    url = url.replace(/.$/, id);
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.getNotifications();
                                if (link != '#') {
                                    window.location.href = link
                                }
                            }
                        })
                },
                readAll() {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAll') }}";
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.items = [];
                            }
                        })
                },
                pushNewItem() {
                    let app = this;
                    // Pusher.logToConsole = true;
                    let pusher = new Pusher("{{ config('pusher.app_key') }}", {
                        encrypted: true,
                        cluster: "{{ config('pusher.app_cluster') }}"
                    });
                    let channel = pusher.subscribe('user-notification.' + "{{ Auth::id() }}");
                    channel.bind('App\\Events\\UserNotification', function (data) {
                        app.items.unshift(data.message);
                    });
                    channel.bind('App\\Events\\UpdateUserNotification', function (data) {
                        app.getNotifications();
                    });
                }
            }
        });
    </script>
@endauth

@if (session()->has('success'))
    <script>
        Notiflix.Notify.success("@lang(session('success'))");
    </script>
@endif

@if (session()->has('error'))
    <script>
        Notiflix.Notify.failure("@lang(session('error'))");
    </script>
@endif

@if (session()->has('warning'))
    <script>
        Notiflix.Notify.warning("@lang(session('warning'))");
    </script>
@endif


</body>

</html>


