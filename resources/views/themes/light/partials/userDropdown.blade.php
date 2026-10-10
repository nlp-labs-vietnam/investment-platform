<div class="user-panel d-none d-lg-inline-block">
       <span class="profile">
          <img src="{{getFile(auth()->user()->image_driver, auth()->user()->image)}}"
               class="img-fluid"
               alt="@lang('user img')"/>
       </span>
    <ul class="user-dropdown">
        <li>
            <a href="{{route('user.dashboard')}}">
                <i class="fal fa-border-all"></i> {{trans('Dashboard')}}
            </a>
        </li>

        <li>
            <a href="{{ route('user.profile') }}">
                <i class="fal fa-user"></i> @lang('My Profile')
            </a>
        </li>

        <li>
            <a href="{{route('user.twostep.security')}}">
                <i class="fal fa-lock"></i> @lang('2FA Security')
            </a>
        </li>

        <li>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fal fa-sign-out-alt"></i> @lang('Logout')
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
