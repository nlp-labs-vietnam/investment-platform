<style>

    .banner-section {
        background-image: url({{$pageSeo['breadcrumb_image'] ?? ''}});
    }
</style>

<!-- Banner section start -->
<div class="banner-section">
    <div class="banner-section-inner">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-area">
                        <h3>
                            @if(isset($pageSeo['page_title']))
                                @lang($pageSeo['page_title'])
                            @else
                                @yield('title')
                            @endif
                        </h3>

                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('page','/') }}"><i class="fa-light fa-house"></i>
                                    @lang('Home')
                                </a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                @if(isset($pageSeo['page_title']))
                                    @lang($pageSeo['page_title'])
                                @else
                                    @yield('title')
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Banner section end -->
