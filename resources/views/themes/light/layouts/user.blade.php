<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" rel="icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>  @lang(basicControl()->site_title) | @yield('title')  </title>

    <link rel="stylesheet" type="text/css" href="{{asset(template(true).'css/bootstrap.min.css')}}"/>
    <link href="{{asset('assets/global/css/select2.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset(template(true).'css/animate.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/owl.carousel.min.css')}}"/>
    <link rel="stylesheet" href="{{asset(template(true).'css/owl.theme.default.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset(template(true).'css/range-slider.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset(template(true).'css/fancybox.css')}}">

    <link rel="stylesheet" href="{{ asset(template(true).'css/all.min.css') }}">
    <link href="{{asset('assets/global/css/flatpickr.min.css')}}" rel="stylesheet">

    @stack('css-lib')

    <link rel="stylesheet" type="text/css" href="{{asset(template(true).'css/style.css')}}">
    <script src="{{asset(template(true).'js/fontawesomepro.js')}}"></script>
    <script src="{{asset(template(true).'js/modernizr.custom.js')}}"></script>

    @stack('style')
</head>

<body @if(session()->get('rtl') == 1) class="rtl" @endif>
<!------- Nav + Content ---------->

<div class="bottom-nav fixed-bottom d-lg-none">
    <div class="link-item {{menuActive(['user.propertyMarket'])}}">
        <a href="{{ route('user.propertyMarket', 'investment-properties') }}">
            <i class="fal fa-project-diagram" aria-hidden="true"></i>
            <span>@lang('Invest')</span>
        </a>
    </div>

    <div class="link-item {{menuActive(['user.add.fund', 'user.addFund.confirm'])}}">
        <a href="{{ route('user.add.fund') }}">
            <i class="fal fa-funnel-dollar" aria-hidden="true"></i>
            <span>@lang('Deposit')</span>
        </a>
    </div>

    <div class="link-item {{menuActive('user.dashboard')}}">
        <a href="{{ route('user.dashboard') }}">
            <i class="fal fa-home-lg-alt"></i>
            <span>@lang('Home')</span>
        </a>
    </div>

    <div class="link-item {{menuActive(['user.payout','user.payout.preview'])}}">
        <a href="{{ route('user.payout') }}">
            <i class="fal fa-hand-holding-usd" aria-hidden="true"></i>
            <span>@lang('Withdraw')</span>
        </a>
    </div>

    <div class="link-item {{menuActive(['user.profile'])}}">
        <button onclick="toggleSideMenu()">
            <i class="fal fa-ellipsis-v-alt"></i>
            <span>@lang('Menu')</span>
        </button>
    </div>
</div>

<div class="wrapper">
    <!------ sidebar ------->
    @include(template().'partials.sidebar')

    <!-- content -->
    <div id="content">
        <div class="overlay">
            <!-- navbar -->
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand d-lg-none" href="{{route('user.dashboard')}}">
                        <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"
                             alt="{{ basicControl()->site_title }}">
                    </a>
                    <button class="sidebar-toggler d-none d-lg-block" onclick="toggleSideMenu()">
                        <i class="far fa-bars"></i>
                    </button>

                    <!-- navbar text -->
                    <span class="navbar-text" id="pushNotificationArea">
                            <!-- notification panel -->
                            @if(basicControl()->in_app_notification == 1)
                            @include(template().'partials.pushNotify')
                        @endif
                        <!-- User panel -->
                        @include(template().'partials.userDropdown')
                        </span>
                </div>
            </nav>
            @yield('content')
        </div>
    </div>
</div>

@stack('loadModal')

<script src="{{asset(template(true).'js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset(template(true).'js/jquery.min.js')}}"></script>
<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
<script src="{{asset(template(true).'js/owl.carousel.min.js')}}"></script>
<script src="{{asset(template(true).'js/range-slider.min.js')}}"></script>
<script src="{{asset(template(true).'js/socialSharing.js')}}"></script>
<script src="{{asset(template(true).'js/fancybox.umd.js')}}"></script>
<script src="{{asset(template(true).'js/apexcharts.min.js')}}"></script>

@stack('extra-js')


<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
<script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
<script src="{{ asset('assets/global/js/vue.min.js') }}"></script>
<script src="{{ asset('assets/global/js/axios.min.js') }}"></script>

<script src="{{ asset('assets/global/js/flatpickr.js') }}"></script>

<!-- custom script -->
<script src="{{asset(template(true).'js/script.js')}}"></script>


@stack('script')


<script>
    'use strict';

    $(".card-boxes").owlCarousel({
        loop: true,
        margin: -25,
        rtl: false,
        nav: false,
        dots: false,
        autoplay: false,
        autoplayTimeout: 3000,
        responsive: {
            0: {
                items: 1,
            },
            576: {
                items: 2,
            },
        },
    });

    // dashboard sidebar
    window.onload = function () {
        var el = document.getElementById('sidebarCollapse');
        if (el == null) {
            return 0;
        } else {

            el.addEventListener("click", () => {
                document.getElementById("sidebar").classList.toggle("active");
                document.getElementById("content").classList.toggle("active");
            });
        }

        // for datepicker
        $(function () {
            $("#datepicker").datepicker({
                dateFormat: "yy-mm-dd"
            });
            $("#salutation").selectmenu();
        });
    }

    @if(basicControl()->push_notification)
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
                            if (link !== '#') {
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
                Pusher.logToConsole = false;
                let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                    encrypted: true,
                    cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
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
    @endif

</script>

@include('plugins', ['fromUser' => true])

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





