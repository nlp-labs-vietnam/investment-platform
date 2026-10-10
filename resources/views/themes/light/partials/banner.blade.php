<style>

    .banner-section {
        background: linear-gradient(170deg, rgb(255, 255, 255, .93), rgb(255, 255, 255, .2)), url({{$pageSeo['breadcrumb_image'] ?? ''}});
    }
</style>



<!-- banner section -->
<section class="banner-section">
    <div class="overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3>
                        @if(isset($pageSeo['page_title']))
                            @lang($pageSeo['page_title'])
                        @else
                            @yield('title')
                        @endif
                    </h3>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('page','/') }}">@lang('Home')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                @if(isset($pageSeo['page_title']))
                                    @lang($pageSeo['page_title'])
                                @else
                                    @yield('title')
                                @endif
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
