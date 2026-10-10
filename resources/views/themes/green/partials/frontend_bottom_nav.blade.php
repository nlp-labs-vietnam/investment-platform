<ul class="nav bottom-nav fixed-bottom d-lg-none">
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="offcanvas" role="button" aria-controls="offcanvasNavbar"
           href="#offcanvasNavbar" aria-current="page"><i class="fa-light fa-list"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ getLastSegment() == 'property' ? 'active' : '' }}" href="{{ route('page', 'property') }}"><i class="fa-regular fa-building"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('page', '/') }}"><i class="fa-light fa-house"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ getLastSegment() == 'blog' ? 'active' : '' }}" href="{{ route('page', 'blog') }}"><i class="fa-light fa-address-book"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ getLastSegment() == 'about' ? 'active' : '' }}" href="{{ route('page', 'about') }}"><i class="fa-light fa-user"></i></a>
    </li>
</ul>
<!-- Bottom Mobile Tab nav section end -->
