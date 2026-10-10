<li class="nav-item dropdown">
    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="{{route('user.dashboard')}}"
       data-bs-toggle="dropdown">
        <img src="{{getFile(auth()->user()->image_driver, auth()->user()->image)}}" alt="Profile"
             class="rounded-circle">
        <span class="d-none d-xl-block dropdown-toggle ps-2">@lang(auth()->user()->fullname)</span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header d-flex justify-content-center align-items-center text-start">
            <div class="profile-thum">
                <img src="{{getFile(auth()->user()->image_driver, auth()->user()->image)}}" alt="NLP Group">
            </div>
            <div class="profile-content">
                <h6>@lang(auth()->user()->fullname)</h6>
                <span>@lang(auth()->user()->username)</span>
            </div>
        </li>

        <li>
            <a class="dropdown-item d-flex align-items-center" href="{{route('user.dashboard')}}">
                <i class="fal fa-border-all"></i>
                <span>{{trans('Dashboard')}}</span>
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('user.profile') }}">
                <i class="fa-light fa-user"></i>
                <span>@lang('My Profile')</span>
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center" href="{{route('user.twostep.security')}}">
                <i class="fal fa-lock"></i>
                <span>@lang('2FA Security')</span>
            </a>
        </li>

        <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-light fa-right-from-bracket"></i>
                <span>@lang('Logout')</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</li>
