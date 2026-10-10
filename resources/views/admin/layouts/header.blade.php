<!-- ========== HEADER ========== -->
<header id="header"
        class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-container navbar-bordered bg-white">
    <div class="navbar-nav-wrap">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}" aria-label="{{ $basicControl->site_title }}">
            <img class="navbar-brand-logo"
                 src="{{ getFile($basicControl->admin_logo_driver, $basicControl->admin_logo, true) }}"
                 alt="{{ $basicControl->site_title }} Logo"
                 data-hs-theme-appearance="default">
            <img class="navbar-brand-logo"
                 src="{{ getFile($basicControl->admin_logo_driver, $basicControl->admin_logo, true) }}"
                 alt="{{ $basicControl->site_title }} Logo"
                 data-hs-theme-appearance="dark">
            <img class="navbar-brand-logo-mini"
                 src="{{ getFile($basicControl->admin_logo_driver, $basicControl->admin_logo, true) }}"
                 alt="{{ $basicControl->site_title }} Logo"
                 data-hs-theme-appearance="default">
            <img class="navbar-brand-logo-mini"
                 src="{{ getFile($basicControl->admin_logo_driver, $basicControl->admin_logo, true) }}"
                 alt="Logo"
                 data-hs-theme-appearance="dark">
        </a>

        <div class="navbar-nav-wrap-content-start">
            <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                <i class="bi-arrow-bar-left navbar-toggler-short-align"
                   data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                   data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
                <i class="bi-arrow-bar-right navbar-toggler-full-align"
                   data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                   data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
            </button>


            <div class="dropdown ms-2">
                <div class="d-none d-lg-block">
                    <div
                        class="input-group input-group-merge input-group-borderless input-group-hover-light navbar-input-group">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>

                        <input type="search" class="js-form-search form-control global-search"
                               placeholder="@lang("Search for a menu")"
                               aria-label="@lang("Search for a menu")" data-hs-form-search-options='{
                               "clearIcon": "#clearSearchResultsIcon",
                               "dropMenuElement": "#searchDropdownMenu",
                               "dropMenuOffset": 20,
                               "toggleIconOnFocus": true,
                               "activeClass": "focus"
                             }'>
                        <a class="input-group-append input-group-text" href="javascript:void(0)">
                            <i id="clearSearchResultsIcon" class="bi-x-lg d-none"></i>
                        </a>
                    </div>
                </div>

                <button
                    class="js-form-search js-form-search-mobile-toggle btn btn-ghost-secondary btn-icon rounded-circle d-lg-none"
                    type="button" data-hs-form-search-options='{
                       "clearIcon": "#clearSearchResultsIcon",
                       "dropMenuElement": "#searchDropdownMenu",
                       "dropMenuOffset": 20,
                       "toggleIconOnFocus": true,
                       "activeClass": "focus"
                     }'>
                    <i class="bi-search"></i>
                </button>
                <!-- End Input Group -->

                <!-- Card Search Content -->
                <div id="searchDropdownMenu"
                     class="hs-form-search-menu-content dropdown-menu dropdown-menu-form-search navbar-dropdown-menu-borderless">
                    <div class="card">
                        <!-- Body -->
                        <div class="card-body-height search-result">
                            <div class="d-lg-none">
                                <div class="input-group input-group-merge navbar-input-group mb-5">
                                    <div class="input-group-prepend input-group-text">
                                        <i class="bi-search"></i>
                                    </div>

                                    <input type="search" class="form-control global-search"
                                           placeholder="@lang("Search for a menu")"
                                           aria-label="@lang("Search for a menu")">
                                    <a class="input-group-append input-group-text" href="javascript:void(0);">
                                        <i class="bi-x-lg"></i>
                                    </a>
                                </div>
                            </div>

                            <span class="dropdown-header">@lang("Result")</span>

                            <div class="dropdown-divider"></div>

                            <div class="content">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Notification -->
        <div class="navbar-nav-wrap-content-end" id="pushNotificationArea">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <div class="">
                        <a href="{{ url('/') }}" class="btn btn-icon btn-ghost-secondary rounded-circle" target="_blank">
                            <i class="bi-globe"></i>
                        </a>
                    </div>
                </li>
                @if(adminAccessRoute(config('permissionList.Settings_Panel.Control_Panel.permission.view')))
                    <li class="nav-item">
                        <a class="btn btn-icon btn-ghost-secondary rounded-circle"
                           href="{{ route('admin.settings') }}" data-placement="left">
                            <i class="bi bi-gear nav-icon"></i>
                        </a>
                    </li>
                @endif

                @if(basicControl()->in_app_notification)
                    <li class="nav-item d- d-sm-inline-block">
                        <div class="dropdown">
                            <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle"
                                    id="navbarNotificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                    data-bs-auto-close="outside">
                                <i class="bi-bell"></i>
                                <span class="btn-status btn-sm-status btn-status-danger" v-if="items.length > 0"
                                      v-cloak></span>
                            </button>
                            <div
                                class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless navbarNotificationsDropdown data-bs-dropdown-animation"
                                aria-labelledby="navbarNotificationsDropdown">
                                <div class="card ">
                                    <div class="card-header card-header-content-between">
                                        <h4 class="card-title mb-0">@lang('Notifications')</h4>
                                    </div>
                                    <div class="card-body-height">
                                        <div id="notificationTabContent">
                                            <ul class="list-group list-group-flush navbar-card-list-group">
                                                <li class="list-group-item form-check-select"
                                                    v-for="(item, index) in items">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           id="notificationCheck6">
                                                                    <label class="form-check-label"
                                                                           for="notificationCheck6"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-circle">
                                                                    <img class="avatar-img"
                                                                         :src="item.description.image"
                                                                         alt="Image Description">
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">@{{ item.description.name }}</h5>
                                                            <p class="text-body fs-5">@{{ item.description.text }}</p>
                                                            <small class="col-auto text-muted text-cap" v-cloak>@{{
                                                                item.formatted_date }}</small>
                                                        </div>
                                                    </div>
                                                    <a class="stretched-link" :href="item.description.link"></a>
                                                </li>
                                            </ul>
                                            <div class="text-center p-4" v-if="items.length < 1">
                                                <img class="dataTables-image mb-3"
                                                     src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                                     alt="Image Description" data-hs-theme-appearance="default">
                                                <img class="dataTables-image mb-3"
                                                     src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                                     alt="Image Description" data-hs-theme-appearance="dark">
                                                <p class="mb-0">@lang("No Notifications Found")</p>
                                            </div>
                                        </div>

                                    </div>
                                    <a class="card-footer text-center" href="javascript:void(0)" v-if="items.length > 0"
                                       @click.prevent="readAll">
                                        @lang("Clear all notifications") <i class="bi-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                @endif


                <li class="nav-item">
                    <div class="dropdown">
                        <a class="navbar-dropdown-account-wrapper" href="javascript:void(0)" id="accountNavbarDropdown"
                           data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside"
                           data-bs-dropdown-animation>
                            <div class="avatar avatar-sm avatar-circle">
                                <img class="avatar-img"
                                     src="{{getFile(Auth::guard('admin')->user()->image_driver, Auth::guard('admin')->user()->image)}}"
                                     alt="Image Description">
                                <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                            </div>
                        </a>

                        <div
                            class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-account admin_dropdown_account"
                            aria-labelledby="accountNavbarDropdown">
                            <div class="dropdown-item-text">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm avatar-circle">
                                        <img class="avatar-img"
                                             src="{{getFile(Auth::guard('admin')->user()->image_driver, Auth::guard('admin')->user()->image)}}"
                                             alt="Image Description">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-0">{{auth()->user()->name}}</h5>
                                        <p class="card-text text-body">{{auth()->user()->email}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item"
                               href="{{ route("admin.profile") }}">@lang("Profile &amp; account")</a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ route('admin.logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                @lang("Sign out")
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
<!-- ========== END HEADER ========== -->


@push('script')
    <script>
        'use strict'
        let pushNotificationArea = new Vue({
            el: "#pushNotificationArea",
            data: {
                items: [],
            },
            mounted() {
                this.getNotifications();
                this.pushNewItem();
            },
            methods: {
                getNotifications() {
                    let app = this;
                    axios.get("{{ route('admin.push.notification.show') }}")
                        .then(function (res) {
                            app.items = res.data;
                        })
                },
                readAt(id, link) {
                    let app = this;
                    let url = "{{ route('admin.push.notification.readAt', 0) }}";
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
                    let url = "{{ route('admin.push.notification.readAll') }}";
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
                    let channel = pusher.subscribe('admin-notification.' + "{{ Auth::id() }}");
                    channel.bind('App\\Events\\AdminNotification', function (data) {
                        app.items.unshift(data.message);
                    });
                    channel.bind('App\\Events\\UpdateAdminNotification', function (data) {
                        app.getNotifications();
                    });
                }
            }
        });
    </script>
@endpush

