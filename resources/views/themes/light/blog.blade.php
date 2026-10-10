@extends(template() . 'layouts.app')
@section('title',trans('Blog'))
@section('content')
    @if (count($blogs) > 0)
        <!-- blog section  -->
        <section class="blog-page blog-details">
            <div class="container">
                <div class="row g-lg-5">
                    <div class="col-lg-8">
                        @foreach ($blogs as $blog)
                            <div class="blog-box">
                                <div class="img-box">
                                    <img src="{{ getFile($blog->blog_image_driver, $blog->blog_image) }}"
                                         class="img-fluid" alt="@lang('blog image')"/>
                                </div>

                                <div class="text-box">
                                    <div class="date-author">
                                        <span><i class="fal fa-clock"></i> {{ dateTime(@$blog->created_at, 'M d, Y') }} </span>
                                        <span><i class="fal fa-user-circle"></i> @lang('admin') </span>
                                    </div>

                                    <a href="{{route('blog.details',[$blog->id, slug(@$blog->details->title)])}}"
                                       class="title">{{ \Illuminate\Support\Str::limit(optional(@$blog->details)->title, 100) }}</a>
                                    <p>
                                        {{Illuminate\Support\Str::limit(strip_tags(optional(@$blog->details)->description),300)}}
                                    </p>
                                    <a href="{{route('blog.details',[$blog->id, slug(@$blog->details->title)])}}"
                                       class="btn-custom mt-3">@lang('Read more')</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-lg-4">
                        <div class="side-bar">
                            <div class="side-box">
                                <form action="{{ route('blog.search') }}" method="get">
                                    <h4>@lang('Search here')</h4>

                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" id="search"
                                               placeholder="@lang('search')"
                                               value="{{ old('value', request()->search) }}"/>
                                        <button type="submit"><i class="fal fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </form>
                            </div>

                            <div class="side-box">
                                <h4>@lang('Recent Blogs')</h4>
                                @foreach ($blogs as $blog)
                                    <div class="blog-box">
                                        <div class="img-box">
                                            <img class="img-fluid"
                                                 src="{{ getFile($blog->blog_image_driver, $blog->blog_image) }}"
                                                 alt="@lang('blog image')"/>
                                        </div>
                                        <div class="text-box">
                                            <span class="date">{{ dateTime(@$blog->created_at, 'M d, Y') }}</span>
                                            <a href="{{route('blog.details',[$blog->id, slug(@$blog->details->title)])}}"
                                               class="title">{{ \Illuminate\Support\Str::limit(optional(@$blog->details)->title, 40) }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="side-box">
                                <h4>@lang('Categories')</h4>
                                <ul class="links">
                                    @foreach ($categories as $category)
                                        <li>
                                            <a href="{{ route('Category.wise.blog', [$category->id, slug(@$category->name)]) }}">@lang(@$category->name)</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <div class="custom-not-found">
            <img src="{{ asset(template(true).'img/no_data_found.png') }}" alt="not found"
                 class="img-fluid">
        </div>
    @endif
@endsection

