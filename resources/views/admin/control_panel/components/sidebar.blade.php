<div class="navbar-expand-lg navbar-vertical mb-3 mb-lg-5">
    <!-- Navbar Toggle -->
    <div class="d-grid">
        <button type="button" class="navbar-toggler btn btn-white mb-3"
                data-bs-toggle="collapse" data-bs-target="#navbarVerticalNavMenu"
                aria-label="Toggle navigation" aria-expanded="false"
                aria-controls="navbarVerticalNavMenu">
                <span class="d-flex justify-content-between align-items-center">
                    <span class="text-dark">@lang('Menu')</span>
                    <span class="navbar-toggler-default">
                        <i class="bi-list"></i>
                    </span>
                    <span class="navbar-toggler-toggled">
                        <i class="bi-x"></i>
                    </span>
                </span>
        </button>
    </div>
    <!-- End Navbar Toggle -->
    <div id="navbarVerticalNavMenu" class="collapse navbar-collapse">
        <ul id="navbarSettings"
            class="js-sticky-block js-scrollspy card card-navbar-nav nav nav-tabs nav-lg nav-vertical"
            data-hs-sticky-block-options='{
                                "parentSelector": "#navbarVerticalNavMenu",
                                "targetSelector": "#header",
                                "breakpoint": "lg",
                                "startPoint": "#navbarVerticalNavMenu",
                                "endPoint": "#stickyBlockEndPoint",
                                "stickyOffsetTop": 20
                        }'>
            <h4 class="ms-3">@lang("Jump To")</h4>

            @foreach($settings as $key => $setting)
                <li class="nav-item">
                    <a class="nav-link {{ isMenuActive($setting['route']) }}"
                       href="{{ getRoute($setting['route'], $setting['route_segment'] ?? null) }}">
                        <i class="{{ $setting['icon'] }} nav-icon"></i>
                        {{ __(getTitle($key.' '.$suffix)) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>


