@php
    if (getTheme() != basicControl()->theme) {
        $data = footerData();
        $extraInfo = $data['extraInfo'];
        $languages = $data['languages'];
    }
@endphp
<!-- Footer Section start -->
<section class="footer-section">
    <div class="container">
        <div class="row gy-4 gy-sm-5">
            <div class="col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="500">
                <div class="footer-widget">
                    <div class="widget-logo">
                        <a href="{{route('page')}}"><img class="footer-logo"
                                                         src="{{getFile(basicControl()->logo_driver,basicControl()->logo)}}"
                                                         alt="{{ basicControl()->site_title }}"></a>
                    </div>

                    <p>
                        @if(isset($extraInfo['contact'][0]->description))
                            {!! @$extraInfo['contact'][0]->description->footer_short_details !!}
                        @endif
                    </p>



                    @if(isset($extraInfo['social']) && count($extraInfo['social']) > 0)
                        <ul class="social-box mt-30">
                            @foreach($extraInfo['social'] as $social)
                                <li><a href="{{@$social->description->link}}" target="_blank"><i
                                            class="{{@$social->description->font_icon}}"></i></a></li>
                            @endforeach
                        </ul>
                    @endif

                </div>
            </div>
            <div class="col-lg-2 col-sm-6" data-aos="fade-up" data-aos-duration="700">
                <div class="footer-widget">
                    <h5 class="widget-title">{{trans('Useful Links')}}</h5>
                    @if(getFooterMenuData('useful_link') != null)
                        <ul>
                            @foreach(getFooterMenuData('useful_link') as $list)
                                {!! $list !!}
                            @endforeach
                        </ul>
                    @endif

                </div>
            </div>

            <div class="col-lg-3 col-sm-6 pt-sm-0 pt-3 ps-lg-5" data-aos="fade-up" data-aos-duration="900">
                <div class="footer-widget">
                    <h5 class="widget-title">@lang('Company Policy')</h5>
                    @if(getFooterMenuData('support_link') != null)
                        <ul>
                            @foreach(getFooterMenuData('support_link') as $list)
                                {!! $list !!}
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            @if(isset($extraInfo['contact'][0]->description))
                <div class="col-lg-3 col-sm-6 pt-sm-0 pt-3" data-aos="fade-up" data-aos-duration="1100">
                    <div class="footer-widget">
                        <h5 class="widget-title">@lang('Newsletter')</h5>
                        {!! @$extraInfo['contact'][0]->description->subscriber_message !!}
                        <form action="{{route('subscribe')}}" method="post" class="newsletter-form">
                            @csrf
                            <input type="text" class="form-control" placeholder="Your email">
                            <button type="submit" class="subscribe-btn"><i
                                    class="fa-regular fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
        <hr class="cmn-hr">

        <!-- Copyright-area-start -->
        <div class="copyright-area">
            <div class="row gy-4">
                <div class="col-sm-6">
                    <p>@lang('Copyright') &copy; {{date('Y')}} <a class="highlight"
                                                                  href="javascript:void(0)">@lang(basicControl()->site_title)</a> @lang('All Rights Reserved')
                    </p>
                </div>
                @if(isset($languages))
                    <div class="col-sm-6">
                        <div class="language {{(session()->get('rtl') == 1) ? 'text-md-start': 'text-md-end'}}">
                            @foreach($languages as $item)
                                <a href="{{ route('language', $item->short_name) }}"
                                   class="language {{ session('lang') == $item->short_name ? 'active_language' : '' }}">@lang($item->name)</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- Copyright-area-end -->
    </div>
</section>
<!-- Footer Section end -->
