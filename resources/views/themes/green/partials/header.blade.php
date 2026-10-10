<!-- Nav section start -->
<nav class="navbar fixed-top navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand logo" href="{{url('/')}}"><img
                src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"
                alt="{{ basicControl()->site_title }}"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar">
            <i class="fa-light fa-list"></i>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbar">
            <div class="offcanvas-header">
                <a class="navbar-brand" href="{{url('/')}}"><img class="logo"
                                                                 src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"
                                                                 alt="{{ basicControl()->site_title }}"/></a>

                <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
                        class="fa-light fa-arrow-right"></i></button>
            </div>
            <div class="offcanvas-body align-items-center justify-content-between">
                <ul class="navbar-nav m-auto">
                    {!! renderHeaderMenu(getHeaderMenuData()) !!}
                </ul>
            </div>
        </div>


        <div class="nav-right">
            <ul class="custom-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link cmn-btn" href="{{ route('login') }}">
                            <i class="fa-regular fa-user"></i>
                            @lang('Login')
                        </a>
                    </li>
                @else
                    @if(basicControl()->in_app_notification == 1)
                        <li class="nav-item" id="pushNotificationArea">
                            @include(template().'partials.pushNotify')
                        </li>
                    @endif

                    @include(template().'partials.userDropdown')
                @endguest
            </ul>
        </div>
    </div>
</nav>
<!-- Nav section end -->
