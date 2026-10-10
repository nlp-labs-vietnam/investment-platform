<!-- sidebar -->
@php
    $user = \Illuminate\Support\Facades\Auth::user();
    $user_ranks = \App\Models\Rank::where('id', @$user->last_level)->first();
    $segments = request()->segments();
    $last  = end($segments);
    $propertyMarketSegments = ['investment-properties', 'property-share-market', 'my-investment-properties', 'my-shared-properties', 'my-offered-properties', 'receive-offered-properties', 'offer-conversation'];
    $inMarket = in_array($last, $propertyMarketSegments) || (isset($segments[1]) && in_array($segments[1], $propertyMarketSegments));
@endphp
<div id="sidebar" class="">
    <div class="sidebar-top">
        <a class="navbar-brand d-none d-lg-block" href="{{url('/')}}"> <img
                src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"/></a>
        <div class="mobile-user-area d-lg-none">
            <div class="thumb">
                <img class="img-fluid user-img" src="{{getFile(auth()->user()->image_driver, auth()->user()->image)}}"
                     alt="...">
                @if(optional($user->userRank)->rank_icon)
                    <img src="{{ getFile(optional($user->userRank)->driver, optional($user->userRank)->rank_icon) }}" alt=""
                         class="rank-badge">
                @endif
            </div>
            <div class="content">
                <h5 class="mt-1 mb-1">{{ __(auth()->user()->fullname) }}</h5>
                <span class="">{{ __(auth()->user()->username) }}</span>
                @if(@$user->last_level != null && $user_ranks)
                    <p class="text-small mb-0">@lang(optional($user->userRank)->rank_name) -
                        (@lang((optional($user->userRank)->rank_level)))</p>
                @endif
            </div>
        </div>
        <button class="sidebar-toggler d-lg-none" onclick="toggleSideMenu()">
            <i class="fal fa-times"></i>
        </button>
    </div>

    <ul class="main">

        {{-- ── Bảng Điều Khiển ─────────────────────────────── --}}
        <li>
            <a class="{{menuActive(['user.dashboard'])}}" href="{{ route('user.dashboard') }}">
                <i class="fal fa-house-flood"></i>@lang('Dashboard')
            </a>
        </li>

        {{-- ── Phân Phối & Tổng Thầu EPC ──────────────────── --}}
        <li>
            <a class="dropdown-toggle {{ $inMarket ? 'propertyMarketActive' : '' }}"
                data-bs-toggle="collapse"
                href="#dropdownCollapsible"
                role="button"
                aria-expanded="{{ $inMarket ? 'true' : 'false' }}"
                aria-controls="dropdownCollapsible">
                <i class="fal fa-solar-panel"></i>@lang('Solar Distribution & EPC')
            </a>
            <div class="collapse {{ $inMarket ? 'show' : '' }} dropdownCollapsible" id="dropdownCollapsible">
                <ul class="">
                    <li>
                        <a class="{{ ($last == 'investment-properties') ? 'active' : '' }}"
                           href="{{ route('user.propertyMarket', 'investment-properties') }}">
                            <i class="fal fa-boxes-stacked"></i>@lang('Distribution Center')
                        </a>
                    </li>
                    @if(basicControl()->is_share_investment == 1)
                        <li>
                            <a class="{{ ($last == 'property-share-market') ? 'active' : '' }}"
                               href="{{ route('user.propertyMarket', 'property-share-market') }}">
                                <i class="fal fa-helmet-safety"></i>@lang('EPC Solutions')
                            </a>
                        </li>
                    @endif
                    <li>
                        <a class="{{ ($last == 'my-investment-properties') ? 'active' : '' }}"
                           href="{{ route('user.propertyMarket', 'my-investment-properties') }}">
                            <i class="fal fa-tower-broadcast"></i>@lang('My Projects & Systems')
                        </a>
                    </li>
                    @if(basicControl()->is_share_investment == 1)
                        <li>
                            <a class="{{ ($last == 'my-shared-properties') ? 'active' : '' }}"
                               href="{{ route('user.propertyMarket', 'my-shared-properties') }}">
                                <i class="fal fa-house-signal"></i>@lang('Rooftop / PPA Management')
                            </a>
                        </li>
                        <li>
                            <a class="{{ ($last == 'my-offered-properties') ? 'active' : '' }}"
                               href="{{ route('user.propertyMarket', 'my-offered-properties') }}">
                                <i class="fal fa-paper-plane"></i>@lang('Survey & RFQ Request')
                            </a>
                        </li>
                        <li>
                            <a class="{{ ($last == 'receive-offered-properties' || request()->routeIs('user.offerList')) ? 'active' : '' }}"
                               href="{{ route('user.propertyMarket', 'receive-offered-properties') }}">
                                <i class="fal fa-file-lines"></i>@lang('Technical Proposals')
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>

        {{-- ── Lịch Sử Đơn Hàng & Dự Án ───────────────────── --}}
        <li>
            <a class="{{menuActive(['user.invest-history'])}}" href="{{route('user.invest-history')}}">
                <i class="fal fa-rectangle-history"></i>@lang('Order & Project History')
            </a>
        </li>

        {{-- ── Thiết Bị Lưu Tâm / Dự Án Mẫu ──────────────── --}}
        <li>
            <a class="{{menuActive(['user.wishListProperty'])}}" href="{{ route('user.wishListProperty') }}">
                <i class="fal fa-bookmark"></i>@lang('Saved Equipment / Cases')
            </a>
        </li>

        {{-- ── Quản Lý Tài Chính & Công Nợ ─────────────────── --}}
        @php
            $inFinance = menuActive(['user.add.fund','user.fund.history','user.fund.history.search','user.payout','user.payout.confirm','user.payout.history','user.money.transfer','user.money.transfer.history','user.transaction','user.transaction.search']) === 'active';
        @endphp
        <li>
            <a class="dropdown-toggle {{ $inFinance ? 'active' : '' }}"
                data-bs-toggle="collapse"
                href="#dropdownFinance"
                role="button"
                aria-expanded="{{ $inFinance ? 'true' : 'false' }}"
                aria-controls="dropdownFinance">
                <i class="fal fa-file-invoice-dollar"></i>@lang('Finance & Debt Management')
            </a>
            <div class="collapse {{ $inFinance ? 'show' : '' }} dropdownCollapsible" id="dropdownFinance">
                <ul class="">
                    <li>
                        <a class="{{menuActive(['user.add.fund'])}}" href="{{route('user.add.fund')}}">
                            <i class="fal fa-hand-holding-dollar"></i>@lang('Payment & Advance')
                        </a>
                    </li>
                    <li>
                        <a class="{{menuActive(['user.fund.history','user.fund.history.search'])}}" href="{{route('user.fund.history')}}">
                            <i class="fal fa-file-invoice"></i>@lang('Payment History & VAT Invoice')
                        </a>
                    </li>
                    <li>
                        <a class="{{menuActive(['user.payout','user.payout.confirm'])}}" href="{{route('user.payout')}}">
                            <i class="fal fa-rotate-left"></i>@lang('Refund / Final Settlement')
                        </a>
                    </li>
                    <li>
                        <a class="{{menuActive(['user.payout.history'])}}" href="{{route('user.payout.history')}}">
                            <i class="fal fa-usd-square"></i>@lang('Settlement History')
                        </a>
                    </li>
                    <li>
                        <a class="{{menuActive(['user.money.transfer'])}}" href="{{route('user.money.transfer')}}">
                            <i class="fal fa-exchange-alt"></i>@lang('Credit Limit & Guarantee')
                        </a>
                    </li>
                    <li>
                        <a class="{{menuActive(['user.money.transfer.history'])}}" href="{{route('user.money.transfer.history')}}">
                            <i class="fal fa-money-bill"></i>@lang('Credit History')
                        </a>
                    </li>
                    <li>
                        <a class="{{menuActive(['user.transaction','user.transaction.search'])}}" href="{{route('user.transaction')}}">
                            <i class="fal fa-money-check-alt"></i>@lang('Ledger & Debt Reconciliation')
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- ── Đối Tác & Đại Lý ────────────────────────────── --}}
        @php
            $inPartner = menuActive(['user.referral','user.referral.bonus','user.referral.bonus.search','user.ranks']) === 'active';
        @endphp
        <li>
            <a class="dropdown-toggle {{ $inPartner ? 'active' : '' }}"
                data-bs-toggle="collapse"
                href="#dropdownPartner"
                role="button"
                aria-expanded="{{ $inPartner ? 'true' : 'false' }}"
                aria-controls="dropdownPartner">
                <i class="fal fa-people-group"></i>@lang('Partner Network')
            </a>
            <div class="collapse {{ $inPartner ? 'show' : '' }} dropdownCollapsible" id="dropdownPartner">
                <ul class="">
                    <li>
                        <a class="{{menuActive(['user.referral'])}}" href="{{route('user.referral')}}">
                            <i class="fal fa-handshake"></i>@lang('Dealer / Contractor Network')
                        </a>
                    </li>
                    <li>
                        <a class="{{menuActive(['user.referral.bonus','user.referral.bonus.search'])}}" href="{{route('user.referral.bonus')}}">
                            <i class="fal fa-percent"></i>@lang('Discount Policy & Commission')
                        </a>
                    </li>
                    <li>
                        <a class="{{menuActive(['user.ranks'])}}" href="{{route('user.ranks')}}">
                            <i class="fal fa-badge-check"></i>@lang('Partner Tier & Sales Ranking')
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- ── Hồ Sơ Tiêu Chuẩn & Chứng Nhận ─────────────── --}}
        <li>
            <a class="{{ request()->is('*compliance*') ? 'active' : '' }}" href="{{ url('/') }}">
                <i class="fal fa-file-certificate"></i>@lang('ISO & Compliance Profile')
            </a>
        </li>

        {{-- ── Trung Tâm Hỗ Trợ & Bảo Hành ───────────────── --}}
        <li>
            <a class="{{menuActive(['user.ticket.list','user.ticket.create','user.ticket.view'])}}"
               href="{{route('user.ticket.list')}}">
                <i class="fal fa-screwdriver-wrench"></i>@lang('RMA & Service Support')
            </a>
        </li>

        {{-- ── 2FA (mobile only) ───────────────────────────── --}}
        <li class="d-lg-none">
            <a href="{{route('user.twostep.security')}}">
                <i class="fal fa-lock"></i> @lang('2FA Security')
            </a>
        </li>

        <li class="d-lg-none">
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fal fa-sign-out-alt"></i> @lang('Logout')
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
