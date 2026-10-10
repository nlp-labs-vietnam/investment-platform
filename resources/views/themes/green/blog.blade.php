@extends(template() . 'layouts.app')
@section('title',trans('Blog'))
@section('content')
    <!-- Blog Section start -->
    <section class="blog-section">
        <div class="container">
            <div class="row align-items-center text-center text-md-start">
                <div class="col-md-6">
                    <div class="section-subtitle" data-aos="fade-up"
                         data-aos-duration="500">@lang(optional($blogSingleContent->description)->heading)</div>
                    <h2 data-aos="fade-up" data-aos-duration="700">
                        @lang(optional($blogSingleContent->description)->sub_heading)
                    </h2>
                </div>

                <div class="col-md-6" data-aos="fade-up" data-aos-duration="900">
                    <p>
                       {!! optional($blogSingleContent->description)->short_details !!}
                    </p>
                </div>

            </div>
            @if (isset($blogs) && count($blogs) > 0)
                <div class="mt-40">
                    <div class="row g-4 g-lg-5">
                        @foreach ($blogs as $blog)
                            <div class="col-lg-6" data-aos="fade-up" data-aos-duration="700">
                                <div class="blog-box">
                                    <a href="{{route('blog.details',[$blog->id, $blog->details?->slug])}}">
                                        <div class="img-box">
                                            <img src="{{ getFile($blog->blog_image_driver, $blog->blog_image) }}"
                                                 alt="">
                                        </div>
                                    </a>
                                    <div class="text-box">
                                        <h5 class="title">
                                            <a class="border-effect" href="{{route('blog.details',[$blog->id, slug(@$blog->details->title)])}}">
                                                {{ $blog->details?->title }}
                                            </a></h5>
                                        <ul class="meta">
                                            <li class="item">
                                                <a href="javascript:void(0)"><i
                                                        class="fa-regular fa-user"></i> @lang('admin') </a>
                                            </li>
                                            <li class="item">
                                                <span class="icon"><i class="fa-regular fa-calendar-days"></i></span>
                                                <span>{{ dateTime($blog->created_at, 'M d, Y') }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- Blog Section end -->

@endsection

