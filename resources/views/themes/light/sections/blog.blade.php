@if(isset($blog))
    <section class="blog-section">
        <div class="container">
            @if(isset($blog['single']))
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5>@lang($blog['single']['heading'])</h5>
                            <h2>@lang($blog['single']['sub_heading'])</h2>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($blog['multiple']) && count($blog['multiple']) > 0)
                <div class="row g-4 g-lg-5">
                    @foreach(collect($blog['multiple'])->take(3) as $key => $blog)
                        <div class="col-lg-4 col-md-6">
                            <div
                                class="blog-box"
                                data-aos="fade-up"
                                data-aos-duration="1000"
                                data-aos-anchor-placement="center-bottom"
                            >
                                <div class="img-box">
                                    <img class="img-fluid"
                                         src="{{ getFile($blog->blog_image_driver,$blog->blog_image) }}"/>
                                </div>
                                <div class="text-box">
                                    <div class="date">
                                        <span>{{ dateTime($blog['created_at']) }}</span>
                                    </div>
                                    <div class="author">
                                        <span><i class="fal fa-user-circle"></i> @lang('Admin') </span>
                                    </div>
                                    <a href="{{route('blog.details',[$blog->id, slug(optional($blog->details)->title)])}}"
                                       class="title">{{ Str::limit(optional($blog->details)->title, 80) }}</a>
                                    <p>{{ Str::limit(strip_tags($blog->details?->description) ,80)}}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>
@endif

