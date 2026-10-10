<aside id="sidebar" class="sidebar">
    <div class="logo-container">
        <a href="{{url('/')}}" class="logo d-flex align-items-center">
            <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="NLP Group">
        </a>
    </div>

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- ── Bảng Điều Khiển ─────────────────────────────── --}}
        <li class="nav-item">
            <a class="nav-link {{menuActive(['user.dashboard'])}}" href="{{ route('user.dashboard') }}">
                <i class="fa-regular fa-grid"></i>
                <span>@lang('Dashboard')</span>
            </a>
        </li>

        @php
            $segments = request()->segments();
            $last  = end($segments);
            $propertyMarketSegments = ['investment-properties', 'property-share-market', 'my-investment-properties', 'my-shared-properties', 'my-offered-properties', 'receive-offered-properties', 'offer-conversation'];
            $inMarket = in_array($last, $propertyMarketSegments) || (isset($segments[1]) && in_array($segments[1], $propertyMarketSegments));
        @endphp

        {{-- ── Phân Phối & Tổng Thầu EPC ──────────────────── --}}
        <li class="nav-item">
            <a class="nav-link {{ $inMarket ? 'active' : '' }}"
               data-bs-target="#menu-epc" data-bs-toggle="collapse" href="javascript:void(0)"
               aria-expanded="{{ $inMarket ? 'true' : 'false' }}">
                <i class="fa-regular fa-solar-panel"></i>
                <span>@lang('Solar Distribution & EPC')</span>
                <i class="fa-regular fa-angle-down ms-auto bi-chevron-down"></i>
            </a>
            <ul id="menu-epc"
                class="nav-content collapse {{ $inMarket ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ ($last == 'investment-properties') ? 'active' : '' }}"
                       href="{{ route('user.propertyMarket', 'investment-properties') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('Distribution Center')</span>
                    </a>
                </li>
                @if(basicControl()->is_share_investment == 1)
                    <li>
                        <a class="{{ ($last == 'property-share-market') ? 'active' : '' }}"
                           href="{{ route('user.propertyMarket', 'property-share-market') }}">
                            <i class="fa-regular fa-circle"></i>
                            <span>@lang('EPC Solutions')</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a class="{{ ($last == 'my-investment-properties') ? 'active' : '' }}"
                       href="{{ route('user.propertyMarket', 'my-investment-properties') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('My Projects & Systems')</span>
                    </a>
                </li>
                @if(basicControl()->is_share_investment == 1)
                    <li>
                        <a class="{{ ($last == 'my-shared-properties') ? 'active' : '' }}"
                           href="{{ route('user.propertyMarket', 'my-shared-properties') }}">
                            <i class="fa-regular fa-circle"></i>
                            <span>@lang('Rooftop / PPA Management')</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ ($last == 'my-offered-properties') ? 'active' : '' }}"
                           href="{{ route('user.propertyMarket', 'my-offered-properties') }}">
                            <i class="fa-regular fa-circle"></i>
                            <span>@lang('Survey & RFQ Request')</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ ($last == 'receive-offered-properties' || request()->routeIs('user.offerList')) ? 'active' : '' }}"
                           href="{{ route('user.propertyMarket', 'receive-offered-properties') }}">
                            <i class="fa-regular fa-circle"></i>
                            <span>@lang('Technical Proposals')</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        {{-- ── Lịch Sử Đơn Hàng & Dự Án ───────────────────── --}}
        <li class="nav-item">
            <a class="nav-link {{menuActive(['user.invest-history'])}}" href="{{ route('user.invest-history') }}">
                <i class="fa-regular fa-rectangle-history"></i>
                <span>@lang('Order & Project History')</span>
            </a>
        </li>

        {{-- ── Thiết Bị Lưu Tâm / Dự Án Mẫu ──────────────── --}}
        <li class="nav-item">
            <a class="nav-link {{menuActive(['user.wishListProperty'])}}" href="{{ route('user.wishListProperty') }}">
                <i class="fa-regular fa-bookmark"></i>
                <span>@lang('Saved Equipment / Cases')</span>
            </a>
        </li>

        {{-- ── Quản Lý Tài Chính & Công Nợ ─────────────────── --}}
        @php
            $financeSegments = ['add-fund', 'fund-history', 'payout', 'payout-history', 'money-transfer', 'transfer-history', 'transactions'];
            $inFinance = in_array($last, $financeSegments) ||
                         menuActive(['user.add.fund','user.fund.history','user.payout','user.payout.history','user.money.transfer','user.money.transfer.history','user.transaction']) === 'active';
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $inFinance ? 'active' : '' }}"
               data-bs-target="#menu-finance" data-bs-toggle="collapse" href="javascript:void(0)"
               aria-expanded="{{ $inFinance ? 'true' : 'false' }}">
                <i class="fa-regular fa-file-invoice-dollar"></i>
                <span>@lang('Finance & Debt Management')</span>
                <i class="fa-regular fa-angle-down ms-auto bi-chevron-down"></i>
            </a>
            <ul id="menu-finance"
                class="nav-content collapse {{ $inFinance ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ menuActive(['user.add.fund']) }}" href="{{ route('user.add.fund') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('Payment & Advance')</span>
                    </a>
                </li>
                <li>
                    <a class="{{ menuActive(['user.fund.history','user.fund.history.search']) }}" href="{{ route('user.fund.history') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('Payment History & VAT Invoice')</span>
                    </a>
                </li>
                <li>
                    <a class="{{ menuActive(['user.payout','user.payout.confirm']) }}" href="{{ route('user.payout') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('Refund / Final Settlement')</span>
                    </a>
                </li>
                <li>
                    <a class="{{ menuActive(['user.payout.history']) }}" href="{{ route('user.payout.history') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('Settlement History')</span>
                    </a>
                </li>
                <li>
                    <a class="{{ menuActive(['user.money.transfer']) }}" href="{{ route('user.money.transfer') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('Credit Limit & Guarantee')</span>
                    </a>
                </li>
                <li>
                    <a class="{{ menuActive(['user.money.transfer.history']) }}" href="{{ route('user.money.transfer.history') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('Credit History')</span>
                    </a>
                </li>
                <li>
                    <a class="{{ menuActive(['user.transaction','user.transaction.search']) }}" href="{{ route('user.transaction') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('Ledger & Debt Reconciliation')</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- ── Đối Tác & Đại Lý ────────────────────────────── --}}
        @php
            $partnerSegments = [];
            $inPartner = menuActive(['user.referral','user.referral.bonus','user.referral.bonus.search']) === 'active';
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $inPartner ? 'active' : '' }}"
               data-bs-target="#menu-partner" data-bs-toggle="collapse" href="javascript:void(0)"
               aria-expanded="{{ $inPartner ? 'true' : 'false' }}">
                <i class="fa-regular fa-people-group"></i>
                <span>@lang('Partner Network')</span>
                <i class="fa-regular fa-angle-down ms-auto bi-chevron-down"></i>
            </a>
            <ul id="menu-partner"
                class="nav-content collapse {{ $inPartner ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ menuActive(['user.referral']) }}" href="{{ route('user.referral') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('Dealer / Contractor Network')</span>
                    </a>
                </li>
                <li>
                    <a class="{{ menuActive(['user.referral.bonus','user.referral.bonus.search']) }}" href="{{ route('user.referral.bonus') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('Discount Policy & Commission')</span>
                    </a>
                </li>
                <li>
                    <a class="{{ menuActive(['user.ranks']) }}" href="{{ route('user.ranks') }}">
                        <i class="fa-regular fa-circle"></i>
                        <span>@lang('Partner Tier & Sales Ranking')</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- ── Hồ Sơ Tiêu Chuẩn & Chứng Nhận ─────────────── --}}
        {{-- Static link — hiển thị trang hồ sơ năng lực --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->is('*compliance*') ? 'active' : '' }}"
               href="{{ url('/') }}">
                <i class="fa-regular fa-file-certificate"></i>
                <span>@lang('ISO & Compliance Profile')</span>
            </a>
        </li>

        {{-- ── Trung Tâm Hỗ Trợ & Bảo Hành ───────────────── --}}
        <li class="nav-item">
            <a class="nav-link {{menuActive(['user.ticket.list','user.ticket.create','user.ticket.view'])}}"
               href="{{ route('user.ticket.list') }}">
                <i class="fa-regular fa-screwdriver-wrench"></i>
                <span>@lang('RMA & Service Support')</span>
            </a>
        </li>

        {{-- ── 2FA (mobile only) ───────────────────────────── --}}
        <li class="nav-item d-lg-none">
            <a class="nav-link {{menuActive(['user.twostep.security'])}}"
               href="{{ route('user.twostep.security') }}">
                <i class="fa-regular fa-shield-check"></i>
                <span>@lang('2FA Security')</span>
            </a>
        </li>

    </ul>
</aside>
