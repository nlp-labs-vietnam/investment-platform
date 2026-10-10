@php
    if (getTheme() != basicControl()->theme) {
        $data = footerData();
        $extraInfo = $data['extraInfo'];
        $languages = $data['languages'];
    }
@endphp
<!-- footer section -->
<footer class="footer-section">
    <div class="overlay">
        <div class="container">
            <div class="row gy-5 gy-lg-0">
                <div class="col-lg-3 col-md-6 pe-lg-5">
                    <div class="footer-box">
                        <a class="navbar-brand" href="{{route('page')}}"> <img
                                src="{{getFile(basicControl()->logo_driver,basicControl()->logo)}}"
                                alt=""/></a>

                        @if(isset($extraInfo['contact'][0]->description))
                            {!! @$extraInfo['contact'][0]->description->footer_short_details !!}
                        @endif

                        @if(isset($extraInfo['social']) && count($extraInfo['social']) > 0)
                            <div class="social-links">
                                @foreach($extraInfo['social'] as $social)
                                    <a href="{{@$social->description->link}}" target="_blank" class="facebook">
                                        <i class="{{@$social->description->font_icon}}"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-box">
                        <h4>{{trans('Useful Links')}}</h4>
                        <ul>
                            @if(getFooterMenuData('useful_link') != null)
                                @foreach(getFooterMenuData('useful_link') as $list)
                                    {!! $list !!}
                                @endforeach
                            @endif

                            @if(getFooterMenuData('support_link') != null)
                                @foreach(getFooterMenuData('support_link') as $list)
                                    {!! $list !!}
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-box">
                        <h4>@lang('Contact us')</h4>
                        <ul>
                            <li><i class="fal fa-map-marker-alt"></i>
                                <span>@lang(@$extraInfo['contact'][0]->description->address)</span></li>
                            <li><i class="fal fa-envelope"></i>
                                <span>@lang(@$extraInfo['contact'][0]->description->email)</span></li>
                            <li><i class="fal fa-phone-alt"></i>
                                <span>@lang(@$extraInfo['contact'][0]->description->phone)</span></li>
                        </ul>
                    </div>
                </div>

                @if(isset($extraInfo['contact'][0]->description))
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-box">
                            <h4>@lang('Subscribe Us')</h4>
                            {!! @$extraInfo['contact'][0]->description->subscriber_message !!}
                            <form action="{{route('subscribe')}}" method="post">
                                @csrf
                                <div class="input-group">
                                    <input type="email" name="email" class="form-control"
                                           placeholder="@lang('Email Address')">
                                    <button type="submit"><i class="fal fa-paper-plane" aria-hidden="true"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
            <div class="d-flex flex-wrap copyright justify-content-between">
                <div>
                    <span> @lang('Copyright') &copy; {{date('Y')}} <a
                            href="javascript:void(0)">@lang(basicControl()->site_title)</a> @lang('All Rights Reserved') </span>
                </div>

                @if(isset($languages))
                    <div class="language {{(session()->get('rtl') == 1) ? 'text-md-start': 'text-md-end'}}">
                        @foreach($languages as $item)
                            <a href="{{ route('language', $item->short_name) }}"
                               class="language {{ session('lang') == $item->short_name ? 'active_language' : '' }}">@lang($item->name)</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</footer>
