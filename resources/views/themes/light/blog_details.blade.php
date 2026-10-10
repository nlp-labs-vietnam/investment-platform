@extends(template() . 'layouts.app')
@section('title',trans('Blog Details'))
@section('content')

    <!-- blog section  -->
    <section class="blog-page blog-details">
        <div class="container">
            <div class="row g-lg-5">
                <div class="col-lg-8">
                    <div class="blog-box">
                        <div class="img-box">
                            <img src="{{ getFile($singleBlog->blog_image_driver, $singleBlog->blog_image) }}"
                                 alt="@lang('not found')" class="img-fluid"/>
                        </div>
                        <div class="text-box">
                            <div class="date-author">
                                <span><i class="fal fa-clock"></i> {{ dateTime($singleBlog->created_at) }} </span>
                                <span><i class="fal fa-user-circle"></i> @lang('admin') </span>
                            </div>
                            <h5 class="title">@lang(@$singleBlog->details->title)</h5>
                            <p>
                               {!! optional($singleBlog->details)->description !!}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="side-bar">
                        <div class="side-box">
                            <form action="{{ route('blog.search') }}" method="get">
                                <h4>@lang('Search here')</h4>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" id="search"
                                           placeholder="@lang('search')"/>
                                    <button type="submit"><i class="fal fa-search" aria-hidden="true"></i></button>
                                </div>
                            </form>
                        </div>

                        @if (count($relatedBlogs) > 0)
                            <div class="side-box">
                                <h4>@lang('Related Blogs')</h4>
                                @foreach ($relatedBlogs as $blog)
                                    <div class="blog-box">
                                        <div class="img-box">
                                            <img class="img-fluid"
                                                 src="{{ getFile($blog->blog_image_driver, $blog->blog_image) }}"
                                                 alt="@lang('not found')"/>
                                        </div>
                                        <div class="text-box">
                                            <span class="date">{{ dateTime(@$blog->created_at) }}</span>
                                            <a href="{{route('blog.details',[$blog->id, slug(@$blog->details->title)])}}"
                                               class="title"
                                            >{{ \Illuminate\Support\Str::limit(optional(@$blog->details)->title, 100) }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="side-box">
                            <h4>@lang('categories')</h4>
                            <ul class="links">
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="{{ route('Category.wise.blog', [$category->id, slug(@$category->name), ]) }}">@lang(@$category->name)</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
