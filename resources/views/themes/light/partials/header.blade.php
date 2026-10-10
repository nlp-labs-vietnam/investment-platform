<!-- ======= Header ======= -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{url('/')}}"> <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"
                alt="{{ basicControl()->site_title }}"/></a>
        <button
            class="navbar-toggler p-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="far fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                {!! renderHeaderMenu(getHeaderMenuData()) !!}

                @guest
                    <li class="nav-item">
                        <a class="nav-link {{Request::routeIs('login') ? 'active' : ''}}"
                           href="{{ route('login') }}">@lang('LOGIN')</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.dashboard')}}">@lang('Dashboard')</a>
                    </li>
                @endguest


            </ul>
        </div>

    </div>
</nav>
<!-- End Header -->
