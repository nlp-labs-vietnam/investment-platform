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

    <link rel="stylesheet" href="{{asset(template(true).'css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset(template(true).'css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{asset(template(true).'css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{asset(template(true).'css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset(template(true).'css/jquery-ui.theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true).'css/jquery-ui.structure.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true).'css/jquery.lineProgressbar.css') }}">
    <link href="{{asset('assets/global/css/flatpickr.min.css')}}" rel="stylesheet">

    @stack('css-lib')

    <link rel="stylesheet" type="text/css" href="{{asset(template(true).'css/dashboard.css')}}">

    @stack('style')
</head>

<body @if(session()->get('rtl') == 1) class="rtl" @endif>
<div class="dashboard-wrapper">

    <!-- Header section start -->
    <header id="header" class="header">
        <button class="toggle-sidebar-btn d-none d-xl-block"><i class="fa-light fa-list"></i></button>

        <!-- Start Icons Navigation -->
            <nav class="header-nav ms-auto navbar" id="content">
                <ul class="nav-icons" id="pushNotificationArea">
                    @if(basicControl()->in_app_notification == 1)
                        @include(template().'partials.pushNotify')
                    @endif

                    <!-- User panel -->
                    @include(template().'partials.userDropdown')
                </ul>
            </nav>

        <!-- End Icons Navigation -->
    </header>
    <!-- Header section start -->

    <!-- Bottom Mobile Tab nav section start -->
    <ul class="nav bottom-nav fixed-bottom d-xl-none">
        <li class="nav-item">
            <a class="nav-link toggle-sidebar-btn" aria-current="page" title="@lang('Menu')">
                <i class="fa-regular fa-list"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ getLastSegment() == 'investment-properties' ? 'active' : '' }}"
               href="{{ route('user.propertyMarket', 'investment-properties') }}"
               title="@lang('Distribution Center')">
                <i class="fa-regular fa-solar-panel"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ getLastSegment() == 'dashboard' ? 'active' : '' }}"
               href="{{ route('user.dashboard') }}"
               title="@lang('Dashboard')">
                <i class="fa-regular fa-house"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ getLastSegment() == 'add-fund' ? 'active' : '' }}"
               href="{{ route('user.add.fund') }}"
               title="@lang('Payment & Advance')">
                <i class="fa-regular fa-file-invoice-dollar"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ getLastSegment() == 'profile' ? 'active' : '' }}"
               href="{{ route('user.profile') }}"
               title="@lang('Profile')">
                <i class="fa-regular fa-user"></i>
            </a>
        </li>
    </ul>
    <!-- Bottom Mobile Tab nav section end -->

    <!------ sidebar ------->
    @include(template().'partials.sidebar')

    <main id="main" class="main">
        <div class="main-wrapper">
            @yield('content')
        </div>
    </main>

</div>

@stack('loadModal')


<script src="{{asset(template(true).'js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset(template(true).'js/jquery-ui.min.js')}}"></script>
<script src="{{asset(template(true).'js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset(template(true).'js/owl.carousel.min.js')}}"></script>
<script src="{{ asset(template(true).'js/circle-progress.min.js') }}"></script>
<script src="{{ asset(template(true).'js/jquery.lineProgressbar.js') }}"></script>
<script src="{{asset(template(true).'js/select2.min.js')}}"></script>
<script src="{{asset(template(true).'js/apexcharts.min.js')}}"></script>

@stack('extra-js')


<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
<script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
<script src="{{ asset('assets/global/js/vue.min.js') }}"></script>
<script src="{{ asset('assets/global/js/axios.min.js') }}"></script>

<script src="{{ asset('assets/global/js/flatpickr.js') }}"></script>

<!-- custom script -->
<script src="{{asset(template(true).'js/dashboard.js')}}"></script>


@stack('script')


<script>
    // Copy api key
    document.getElementById("copyBtn").addEventListener("click", () => {
        let referralURL = document.getElementById("referralURL");
        referralURL.select();
        navigator.clipboard.writeText(referralURL.value)
        if (referralURL.value) {
            document.getElementById("copyBtn").innerHTML = '<i class="fa-regular fa-circle-check"></i> Copied';
            setTimeout(() => {
                document.getElementById("copyBtn").innerHTML = '<i class="fa-regular fa-copy"></i>copy';
            }, 1000)
        }
    })
</script>

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





