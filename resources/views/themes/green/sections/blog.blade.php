@if(isset($blog))
    <!-- Blog Section start -->
    <section class="blog-section">
        <div class="container">
            @if(isset($blog['single']))
                <div class="row align-items-center text-center text-md-start">
                    <div class="col-md-6">
                        <div class="section-subtitle" data-aos="fade-up" data-aos-duration="500"><img
                                src="{{ asset(template(true).'img/sparkle.png') }}"
                                alt=""> @lang($blog['single']['heading'])
                        </div>
                        <h2 data-aos="fade-up" data-aos-duration="700">@lang($blog['single']['sub_heading'])</h2>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-duration="900">
                        {!! $blog['single']['short_details'] !!}
                    </div>
                </div>
            @endif


            @if(isset($blog['multiple']) && count($blog['multiple']) > 0)
                <div class="mt-40">
                    <div class="row g-4 g-lg-5">
                        @foreach(collect($blog['multiple'])->take(6) as $key => $blog)
                            <div class="col-lg-6" data-aos="fade-up" data-aos-duration="700">
                                <div class="blog-box">
                                    <a href="{{route('blog.details',[$blog->id, slug(optional($blog->details)->title)])}}">
                                        <div class="img-box">
                                            <img src="{{ getFile($blog->blog_image_driver,$blog->blog_image) }}" alt="">
                                        </div>
                                    </a>
                                    <div class="text-box">
                                        <h5 class="title"><a class="border-effect" href="{{route('blog.details',[$blog->id, slug(optional($blog->details)->title)])}}">
                                                {{ \Illuminate\Support\Str::limit(optional($blog->details)->title, 80) }}
                                            </a></h5>
                                        <ul class="meta">
                                            <li class="item">
                                                <a href="javascript:void(0)"><i class="fa-regular fa-user"></i> @lang('admin')</a>
                                            </li>
                                            <li class="item">
                                                <span class="icon"><i class="fa-regular fa-calendar-days"></i></span>
                                                <span>{{ dateTime($blog['created_at'], 'M d, Y') }}</span>
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
@endif

