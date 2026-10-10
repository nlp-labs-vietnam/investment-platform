@extends(template() . 'layouts.app')
@section('title',trans('Blog Details'))
@section('content')
    <!-- Blog details section start -->
    <section class="blog-details-section">
        <div class="container">
            <div class="row g-4 g-sm-5">
                <div class="col-lg-7 order-2 order-lg-1">
                    <div class="blog-box-large">
                        <div class="thumbs-area">
                            <img src="{{ getFile($singleBlog->blog_image_driver, $singleBlog->blog_image) }}">
                        </div>
                        <div class="content-area mt-20">
                            <ul class="meta mb-15">
                                <li class="item">
                                    <a href="javascript:void(0)"><span class="icon"><i
                                                class="fa-regular fa-user"></i></span>
                                        <span>@lang('by Admin')</span></a>
                                </li>

                                <li class="item">
                                    <span class="icon"><i class="fal fa-clock"></i></span>
                                    <span>{{ dateTime($singleBlog->created_at) }}</span>
                                </li>

                            </ul>
                            <h3 class="blog-title">
                                @lang(optional($singleBlog->details)->title)
                            </h3>

                            <div class="para-text">
                                {!! optional($singleBlog->details)->description !!}
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-5 order-1 order-lg-2">
                    <div class="blog-sidebar">
                        <form action="{{ route('blog.search') }}" method="get">
                            <div class="sidebar-widget-area">
                                <div class="widget-title">
                                    <h4>@lang('Search')</h4>
                                </div>
                                <div class="search-box">
                                    <input type="text" name="search" id="search" class="form-control"
                                           placeholder="@lang('Search here')...">
                                    <button type="submit" class="search-btn"><i class="far fa-search"></i></button>
                                </div>
                            </div>
                        </form>

                        <div class="sidebar-widget-area">
                            <div class="sidebar-categories-area">
                                <div class="categories-header">
                                    <div class="widget-title">
                                        <h4>@lang('Categories')</h4>
                                    </div>
                                </div>
                                <ul class="categories-list">

                                    @foreach ($categories as $category)
                                        <li>
                                            <a href="{{ route('Category.wise.blog', [$category->id, slug(@$category->name)]) }}">
                                                <span>@lang(@$category->name)</span> <span class="highlight">({{ $category->blog_count }})</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        @if (isset($relatedBlogs) && count($relatedBlogs) > 0)
                            <div class="sidebar-widget-area">
                                <div class="widget-title">
                                    <h4>@lang('Recent Post')</h4>
                                </div>

                                @foreach ($relatedBlogs as $blog)
                                    <a href="{{route('blog.details',[$blog->id, slug(@$blog->details->title)])}}"
                                       class="sidebar-widget-item">
                                        <div class="image-area">
                                            <img src="{{ getFile($blog->blog_image_driver, $blog->blog_image) }}">
                                        </div>
                                        <div class="content-area">
                                            <div class="title">
                                                {{ \Illuminate\Support\Str::limit(optional(@$blog->details)->title, 100) }}
                                            </div>
                                            <div class="widget-date">
                                                <i class="fa-regular fa-calendar-days"></i>
                                                {{ dateTime(@$blog->created_at) }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog details Section End -->
@endsection
