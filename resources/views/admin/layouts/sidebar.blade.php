<!-- Navbar Vertical -->
<aside
    class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-vertical-aside-initialized
    {{in_array(session()->get('themeMode'), [null, 'auto'] )?  'navbar-dark bg-dark ' : 'navbar-light bg-white'}}">
    <div class="navbar-vertical-container">
        <div class="navbar-vertical-footer-offset">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}" aria-label="{{ $basicControl->site_title }}">
                <img class="navbar-brand-logo navbar-brand-logo-auto"
                     src="{{ getFile(session()->get('themeMode') == 'auto'?$basicControl->admin_dark_mode_logo_driver : $basicControl->admin_logo_driver, session()->get('themeMode') == 'auto'?$basicControl->admin_dark_mode_logo:$basicControl->admin_logo, true) }}"
                     alt="{{ $basicControl->site_title }} Logo"
                     data-hs-theme-appearance="default">

                <img class="navbar-brand-logo"
                     src="{{ getFile($basicControl->admin_dark_mode_logo_driver, $basicControl->admin_dark_mode_logo, true) }}"
                     alt="{{ $basicControl->site_title }} Logo"
                     data-hs-theme-appearance="dark">

                <img class="navbar-brand-logo-mini"
                     src="{{ getFile($basicControl->favicon_driver, $basicControl->favicon, true) }}"
                     alt="{{ $basicControl->site_title }} Logo"
                     data-hs-theme-appearance="default">
                <img class="navbar-brand-logo-mini"
                     src="{{ getFile($basicControl->favicon_driver, $basicControl->favicon, true) }}"
                     alt="Logo"
                     data-hs-theme-appearance="dark">
            </a>
            <!-- End Logo -->

            <!-- Navbar Vertical Toggle -->
            <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                <i class="bi-arrow-bar-left navbar-toggler-short-align"
                   data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                   data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   title="Collapse">
                </i>
                <i
                    class="bi-arrow-bar-right navbar-toggler-full-align"
                    data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                    data-bs-toggle="tooltip"
                    data-bs-placement="right"
                    title="Expand"
                ></i>
            </button>
            <!-- End Navbar Vertical Toggle -->


            <!-- Content -->
            <div class="navbar-vertical-content">
                <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">

                    @if(adminAccessRoute(array_merge(config('permissionList.Dashboard.Dashboard.permission.view'))))
                        <div class="nav-item">
                            <a class="nav-link {{ menuActive(['admin.dashboard']) }}"
                               href="{{ route('admin.dashboard') }}">
                                <i class="bi-house-door nav-icon"></i>
                                <span class="nav-link-title">@lang("Dashboard")</span>
                            </a>
                        </div>
                    @endif

                    @if(adminAccessRoute(array_merge(config('permissionList.Manage_Property.Profit_Schedule.permission.view'), config('permissionList.Manage_Property.Amenities.permission.view'), config('permissionList.Manage_Property.Address.permission.view'), config('permissionList.Manage_Property.Properties.permission.view'), config('permissionList.Manage_Property.Wishlist.permission.view'))))
                        <span class="dropdown-header mt-3">@lang('Manage Property')</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        @if(adminAccessRoute(config('permissionList.Manage_Property.Profit_Schedule.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.profit.schedule']) }}"
                                   href="{{ route('admin.profit.schedule') }}" data-placement="left">
                                    <i class="fal fa-clock nav-icon"></i>
                                    <span class="nav-link-title">@lang("Profit Schedule")</span>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Manage_Property.Amenities.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.amenities']) }}"
                                   href="{{ route('admin.amenities') }}" data-placement="left">
                                    <i class="fal fa-plus nav-icon"></i>
                                    <span class="nav-link-title">@lang("Amenities")</span>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Manage_Property.Address.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.addresses']) }}"
                                   href="{{ route('admin.addresses') }}" data-placement="left">
                                    <i class="fal fa-location-dot nav-icon"></i>
                                    <span class="nav-link-title">@lang("Address")</span>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Manage_Property.Properties.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle {{ menuActive(['admin.properties', 'admin.property.search', 'admin.property.create', 'admin.property.edit', 'admin.property.details'], 3) }}"
                                   href="#navbarVerticalPropertyMenu"
                                   role="button"
                                   data-bs-toggle="collapse"
                                   data-bs-target="#navbarVerticalPropertyMenu"
                                   aria-expanded="false"
                                   aria-controls="navbarVerticalPropertyMenu">
                                    <i class="fa-regular fa-building nav-icon"></i>
                                    <span class="nav-link-title">@lang("Properties")</span>
                                </a>
                                <div id="navbarVerticalPropertyMenu"
                                     class="nav-collapse collapse {{ menuActive(['admin.properties', 'admin.property.search', 'admin.property.details', 'admin.property.create', 'admin.property.edit'], 2) }}"
                                     data-bs-parent="#navbarVerticalPropertyMenu">
                                    <a class="nav-link {{ request()->is('admin/properties/all') ? 'active' : '' }}"
                                       href="{{ route('admin.properties', 'all') }}">
                                        <span class="nav-link-title">@lang('All Properties')</span>
                                        <small class="d-none">@lang("Properties > All Properties")</small>
                                    </a>
                                    <a class="nav-link {{ request()->is('admin/properties/running') ? 'active' : '' }}"
                                       href="{{ route('admin.properties', 'running') }}">
                                        <span class="nav-link-title">@lang('Running Properties')</span>
                                        <small class="d-none">@lang("Properties > Running Properties")</small>
                                    </a>
                                    <a class="nav-link {{ request()->is('admin/properties/upcoming') ? 'active' : '' }}"
                                       href="{{ route('admin.properties', 'upcoming') }}">
                                        <span class="nav-link-title">@lang('Upcoming Properties')</span>
                                        <small class="d-none">@lang("Properties > Upcoming Properties")</small>
                                    </a>
                                    <a class="nav-link {{ request()->is('admin/properties/expired') ? 'active' : '' }}"
                                       href="{{ route('admin.properties', 'expired') }}">
                                        <span class="nav-link-title">@lang('Expired Properties')</span>
                                        <small class="d-none">@lang("Properties > Expired Properties")</small>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Manage_Property.Wishlist.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.property.wishlist']) }}"
                                   href="{{ route('admin.property.wishlist') }}" data-placement="left">
                                    <i class="fal fa-heart nav-icon" aria-hidden="true"></i>
                                    <span class="nav-link-title">@lang("WishList")</span>
                                </a>
                            </div>
                        @endif
                    @endif


                    @if(adminAccessRoute(array_merge(config('permissionList.Manage_Investment.All_Investment.permission.view'), config('permissionList.Manage_Investment.Running_Investment.permission.view'), config('permissionList.Manage_Investment.Due_Investment.permission.view'), config('permissionList.Manage_Investment.Expired_Investment.permission.view'), config('permissionList.Manage_Investment.Completed_Investment.permission.view'))))
                        <span class="dropdown-header mt-3">@lang('Manage Investment')</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>

                        @if(adminAccessRoute(config('permissionList.Manage_Investment.All_Investment.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.investments','admin.investment.details']) }}"
                                   href="{{ route('admin.investments') }}" data-placement="left">
                                    <i class="fal fa-sack-dollar nav-icon"></i>
                                    <span class="nav-link-title">@lang("All Investments")</span>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Manage_Investment.Running_Investment.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.running.investments','admin.running.investment.details']) }}"
                                   href="{{ route('admin.running.investments') }}" data-placement="left">
                                    <i class="fal fa-sack-dollar nav-icon"></i>
                                    <span class="nav-link-title">@lang("Running Investments")</span>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Manage_Investment.Due_Investment.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.due.investments','admin.due.investment.details']) }}"
                                   href="{{ route('admin.due.investments') }}" data-placement="left">
                                    <i class="fal fa-sack-dollar nav-icon"></i>
                                    <span class="nav-link-title">@lang("Due Investments")</span>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Manage_Investment.Expired_Investment.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.expired.investments','admin.expired.investment.details']) }}"
                                   href="{{ route('admin.expired.investments') }}" data-placement="left">
                                    <i class="fal fa-sack-dollar nav-icon"></i>
                                    <span class="nav-link-title">@lang("Expired Investments")</span>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Manage_Investment.Completed_Investment.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.completed.investments','admin.completed.investment.details']) }}"
                                   href="{{ route('admin.completed.investments') }}" data-placement="left">
                                    <i class="fal fa-sack-dollar nav-icon"></i>
                                    <span class="nav-link-title">@lang("Completed Investments")</span>
                                </a>
                            </div>
                        @endif
                    @endif


                    @if(adminAccessRoute(array_merge(config('permissionList.Manage_Rank.All_Ranks.permission.view'), config('permissionList.Manage_Rank.Rank_Bonus.permission.view'))))
                        <span class="dropdown-header mt-3">@lang('Manage Rank')</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>

                        @if(adminAccessRoute(config('permissionList.Manage_Rank.All_Ranks.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.ranks', 'admin.rank.create', 'admin.rank.edit']) }}"
                                   href="{{ route('admin.ranks') }}" data-placement="left">
                                    <i class="fal fa-certificate nav-icon"></i>
                                    <span class="nav-link-title">@lang("All Ranks")</span>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Manage_Rank.Rank_Bonus.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.rank.bonus']) }}"
                                   href="{{ route('admin.rank.bonus') }}" data-placement="left">
                                    <i class="fal fa-gift nav-icon"></i>
                                    <span class="nav-link-title">@lang("Rank Bonus")</span>
                                </a>
                            </div>
                        @endif
                    @endif


                    @if(adminAccessRoute(config('permissionList.Manage_Commission.Referral.permission.view')))
                        <span class="dropdown-header mt-3">@lang('Manage Commission')</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        <div class="nav-item">
                            <a class="nav-link {{ menuActive(['admin.referral.commission']) }}"
                               href="{{ route('admin.referral.commission') }}" data-placement="left">
                                <i class="fas fa-cogs nav-icon"></i>
                                <span class="nav-link-title">@lang("Referral")</span>
                            </a>
                        </div>
                    @endif

                    @if(adminAccessRoute(config('permissionList.User_Panel.User_Management.permission.view')))
                        <span class="dropdown-header mt-3"> @lang("User Panel")</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        <div class="nav-item">
                            <a class="nav-link dropdown-toggle {{ menuActive(['admin.users'], 3) }}"
                               href="#navbarVerticalUserPanelMenu"
                               role="button"
                               data-bs-toggle="collapse"
                               data-bs-target="#navbarVerticalUserPanelMenu"
                               aria-expanded="false"
                               aria-controls="navbarVerticalUserPanelMenu">
                                <i class="bi-people nav-icon"></i>
                                <span class="nav-link-title">@lang('User Management')</span>
                            </a>
                            <div id="navbarVerticalUserPanelMenu"
                                 class="nav-collapse collapse {{ menuActive(['admin.mail.all.user','admin.users','admin.users.add','admin.user.edit',
                                                                        'admin.user.view.profile','admin.user.transaction','admin.user.payment',
                                                                        'admin.user.payout','admin.user.kyc.list','admin.send.email'], 2) }}"
                                 data-bs-parent="#navbarVerticalUserPanelMenu">

                                <a class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                    <span class="nav-link-title">@lang('All User')</span>
                                    <small class="d-none">@lang("User Management > All User")</small>
                                </a>

                                <a href="{{ route('admin.users','active-users') }}"
                                    class="nav-link d-flex justify-content-between {{ request()->is('admin/users/active-users') ? 'active' : '' }}">
                                    <span class="nav-link-title">@lang('Active Users')</span>
                                    <small class="d-none">@lang("User Management > Active Users")</small>
                                    @if($sidebarCounts->active_users > 0)
                                        <span class="badge bg-primary rounded-pill ">{{ $sidebarCounts->active_users }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.users','blocked-users') }}"
                                    class="nav-link d-flex justify-content-between {{ request()->is('admin/users/blocked-users') ? 'active' : '' }}">
                                    <span class="nav-link-title">@lang('Blocked Users')</span>
                                    <small class="d-none">@lang("User Management > Blocked Users")</small>

                                @if($sidebarCounts->blocked_users > 0)
                                        <span class="badge bg-primary rounded-pill ">{{ $sidebarCounts->blocked_users }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.users','email-unverified') }}"
                                    class="nav-link d-flex justify-content-between {{ request()->is('admin/users/email-unverified') ? 'active' : '' }}">
                                    <span class="nav-link-title">@lang('Email Unverified')</span>
                                    <small class="d-none">@lang("User Management > Email Unverified")</small>
                                    @if($sidebarCounts->email_unverified > 0)
                                        <span class="badge bg-primary rounded-pill ">{{ $sidebarCounts->email_unverified }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.users','sms-unverified') }}"
                                    class="nav-link d-flex justify-content-between {{ request()->is('admin/users/sms-unverified') ? 'active' : '' }}">
                                    <span class="nav-link-title">@lang('Sms Unverified')</span>
                                    <small class="d-none">@lang("User Management > Sms Unverified")</small>
                                    @if($sidebarCounts->sms_unverified > 0)
                                        <span class="badge bg-primary rounded-pill ">{{ $sidebarCounts->sms_unverified }}</span>
                                    @endif
                                </a>

                                <a class="nav-link {{ menuActive(['admin.mail.all.user']) }}"
                                   href="{{ route("admin.mail.all.user") }}">@lang('Mail To Users')</a>
                            </div>
                        </div>
                    @endif


                    @if(adminAccessRoute(array_merge(config('permissionList.Transactions.Transaction.permission.view'), config('permissionList.Transactions.Commission.permission.view'))))
                        <span class="dropdown-header mt-3">@lang('Transactions')</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        @if(adminAccessRoute(config('permissionList.Transactions.Transaction.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.transaction']) }}"
                                   href="{{ route('admin.transaction') }}" data-placement="left">
                                    <i class="bi bi-send nav-icon"></i>
                                    <span class="nav-link-title">@lang("Transaction")</span>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Transactions.Commission.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.commission']) }}"
                                   href="{{ route('admin.commission') }}" data-placement="left">
                                    <i class="bi bi-send nav-icon"></i>
                                    <span class="nav-link-title">@lang("Commission")</span>
                                </a>
                            </div>
                        @endif
                    @endif

                    @if(adminAccessRoute(array_merge(config('permissionList.Payment_Transactions.Payment_Log.permission.view'), config('permissionList.Payment_Transactions.Payment_Request.permission.view'))))
                        <span class="dropdown-header mt-3">@lang('Payment Transactions')</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        @if(adminAccessRoute(config('permissionList.Payment_Transactions.Payment_Log.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.payment.log']) }}"
                                   href="{{ route('admin.payment.log') }}" data-placement="left">
                                    <i class="bi bi-credit-card-2-front nav-icon"></i>
                                    <span class="nav-link-title">@lang("Payment Log")</span>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Payment_Transactions.Payment_Request.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link  {{ menuActive(['admin.payment.pending']) }}"
                                   href="{{ route('admin.payment.pending') }}" data-placement="left">
                                    <i class="bi bi-cash nav-icon"></i>

                                    <div class="d-flex justify-content-between gap-3">
                                        @lang("Payment Request")
                                        @if($sidebarCounts->deposit_pending > 0)
                                            <span class="badge bg-primary rounded-pill ">{{ $sidebarCounts->deposit_pending }}</span>
                                        @endif
                                    </div>

                                </a>
                            </div>
                        @endif
                    @endif


                    @if(adminAccessRoute(array_merge(config('permissionList.Withdraw_Transactions.Withdraw_Log.permission.view'), config('permissionList.Withdraw_Transactions.Withdraw_Request.permission.view'))))
                        <span class="dropdown-header mt-3">@lang('Withdraw Transactions')</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        <div class="nav-item">
                            @if(adminAccessRoute(config('permissionList.Withdraw_Transactions.Withdraw_Log.permission.view')))
                                <a class="nav-link {{ menuActive(['admin.payout.log']) }}"
                                   href="{{ route('admin.payout.log') }}" data-placement="left">
                                    <i class="bi bi-wallet2 nav-icon "></i>
                                    <span class="nav-link-title">@lang("Withdraw Log")</span>
                                </a>
                            @endif

                            @if(adminAccessRoute(config('permissionList.Withdraw_Transactions.Withdraw_Request.permission.view')))
                                <a class="nav-link {{ menuActive(['admin.payout.pending']) }}"
                                   href="{{ route('admin.payout.pending') }}" >
                                    <i class="bi bi-wallet2 nav-icon "></i>
                                    <div class="d-flex justify-content-between gap-3">
                                        @lang("Withdraw Request")
                                        @if($sidebarCounts->payout_pending > 0)
                                            <span class="badge bg-primary rounded-pill ">{{ $sidebarCounts->payout_pending }}</span>
                                        @endif
                                    </div>

                                </a>
                            @endif
                        </div>
                    @endif


                    @if(adminAccessRoute(array_merge(config('permissionList.Kyc_Management.Kyc_Settings.permission.view'), config('permissionList.Kyc_Management.Kyc_Request.permission.view'))))
                        <span class="dropdown-header mt-3"> @lang('Kyc Management')</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        @if(adminAccessRoute(config('permissionList.Kyc_Management.Kyc_Settings.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(['admin.kyc.form.list','admin.kyc.edit','admin.kyc.create']) }}"
                                   href="{{ route('admin.kyc.form.list') }}" data-placement="left">
                                    <i class="bi-stickies nav-icon"></i>
                                    <span class="nav-link-title">@lang('KYC Setting')</span>
                                    <small class="d-none">@lang("Kyc Management > KYC Setting")</small>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Kyc_Management.Kyc_Request.permission.view')))
                            <div class="nav-item" {{ menuActive(['admin.kyc.list*','admin.kyc.view'], 3) }}>
                                <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalKycRequestMenu"
                                   role="button"
                                   data-bs-toggle="collapse" data-bs-target="#navbarVerticalKycRequestMenu"
                                   aria-expanded="false"
                                   aria-controls="navbarVerticalKycRequestMenu">
                                    <i class="bi bi-person-lines-fill nav-icon"></i>
                                    <span class="nav-link-title">@lang("KYC Request")</span>
                                </a>
                                <div id="navbarVerticalKycRequestMenu"
                                     class="nav-collapse collapse {{ menuActive(['admin.kyc.list*','admin.kyc.view'], 2) }}"
                                     data-bs-parent="#navbarVerticalKycRequestMenu">

                                    <a class="nav-link d-flex justify-content-between {{ Request::is('admin/kyc/pending') ? 'active' : '' }}"
                                       href="{{ route('admin.kyc.list', 'pending') }}">
                                        <span class="nav-link-title">@lang('Pending KYC')</span>
                                        <small class="d-none">@lang("KYC Request > Pending KYC")</small>
                                        @if($sidebarCounts->kyc_pending > 0)
                                            <span class="badge bg-primary rounded-pill ">{{ $sidebarCounts->kyc_pending }}</span>
                                        @endif
                                    </a>
                                    <a class="nav-link d-flex justify-content-between {{ Request::is('admin/kyc/approve') ? 'active' : '' }}"
                                       href="{{ route('admin.kyc.list', 'approve') }}">
                                        <span class="nav-link-title">@lang('Approved KYC')</span>
                                        <small class="d-none">@lang("KYC Request > Approved KYC")</small>
                                        @if($sidebarCounts->kyc_verified > 0)
                                            <span class="badge bg-primary rounded-pill ">{{ $sidebarCounts->kyc_verified }}</span>
                                        @endif
                                    </a>
                                    <a class="nav-link d-flex justify-content-between {{ Request::is('admin/kyc/rejected') ? 'active' : '' }}"
                                       href="{{ route('admin.kyc.list', 'rejected') }}">
                                        <span class="nav-link-title">@lang('Rejected KYC')</span>
                                        <small class="d-none">@lang("KYC Request > Rejected KYC")</small>
                                        @if($sidebarCounts->kyc_rejected > 0)
                                            <span class="badge bg-primary rounded-pill ">{{ $sidebarCounts->kyc_rejected }}</span>
                                        @endif
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if(adminAccessRoute(config('permissionList.Ticket_Panel.Support_Tickets.permission.view')))
                        <span class="dropdown-header mt-3"> @lang("Ticket Panel")</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        <div class="nav-item">
                            <a class="nav-link dropdown-toggle {{ menuActive(['admin.ticket', 'admin.ticket.search', 'admin.ticket.view'], 3) }}"
                               href="#navbarVerticalTicketMenu"
                               role="button"
                               data-bs-toggle="collapse"
                               data-bs-target="#navbarVerticalTicketMenu"
                               aria-expanded="false"
                               aria-controls="navbarVerticalTicketMenu">
                                <i class="fa-light fa-headset nav-icon"></i>
                                <span class="nav-link-title">@lang("Support Ticket")</span>
                            </a>
                            <div id="navbarVerticalTicketMenu"
                                 class="nav-collapse collapse {{ menuActive(['admin.ticket','admin.ticket.search', 'admin.ticket.view'], 2) }}"
                                 data-bs-parent="#navbarVerticalTicketMenu">
                                <a class="nav-link {{ request()->is('admin/tickets/all') ? 'active' : '' }}"
                                   href="{{ route('admin.ticket', 'all') }}">
                                    <span class="nav-link-title">@lang('All Tickets')</span>
                                    <small class="d-none">@lang("Support Ticket > All Tickets")</small>
                                </a>
                                <a class="nav-link {{ request()->is('admin/tickets/answered') ? 'active' : '' }}"
                                   href="{{ route('admin.ticket', 'answered') }}">
                                    <span class="nav-link-title">@lang('Answered Tickets')</span>
                                    <small class="d-none">@lang("Support Ticket > Answered Tickets")</small>
                                </a>
                                <a class="nav-link {{ request()->is('admin/tickets/replied') ? 'active' : '' }}"
                                   href="{{ route('admin.ticket', 'replied') }}">
                                    <span class="nav-link-title">@lang('Replied Tickets')</span>
                                    <small class="d-none">@lang("Support Ticket > Replied Tickets")</small>
                                </a>
                                <a class="nav-link {{ request()->is('admin/tickets/closed') ? 'active' : '' }}"
                                   href="{{ route('admin.ticket', 'closed') }}">
                                    <span class="nav-link-title">@lang('Closed Tickets')</span>
                                    <small class="d-none">@lang("Support Ticket > Closed Tickets")</small>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if(adminAccessRoute(config('permissionList.Subscribers.Subscriber_List.permission.view')))
                        <span class="dropdown-header mt-3">@lang('Subscribers')</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        <div class="nav-item">
                            <a class="nav-link {{ menuActive(['admin.subscribers']) }}"
                               href="{{ route('admin.subscribers') }}" data-placement="left">
                                <i class="bi bi-wallet2 nav-icon "></i>
                                <span class="nav-link-title">@lang("Subscriber List")</span>
                            </a>
                        </div>
                    @endif


                    @if(adminAccessRoute(array_merge(config('permissionList.Roles_And_Permission.Available_Roles.permission.view'), config('permissionList.Roles_And_Permission.Manage_Staff.permission.view'))))
                        <span class="dropdown-header mt-3"> @lang('Role & Permission')</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        <div class="nav-item">
                            <a class="nav-link dropdown-toggle {{ menuActive(['admin.users'], 3) }}"
                               href="#navbarRolePermissionMenu"
                               role="button"
                               data-bs-toggle="collapse"
                               data-bs-target="#navbarRolePermissionMenu"
                               aria-expanded="false"
                               aria-controls="navbarRolePermissionMenu">
                                <i class="bi-people nav-icon"></i>
                                <span class="nav-link-title">@lang('Roles And Permission')</span>
                            </a>
                            <div id="navbarRolePermissionMenu"
                                 class="nav-collapse collapse {{ menuActive(['admin.role','admin.role.staff', 'admin.createRole', 'admin.editRole', 'admin.role.staffCreate', 'admin.role.staffEdit'], 2) }}"
                                 data-bs-parent="#navbarRolePermissionMenu">
                                @if(adminAccessRoute(config('permissionList.Roles_And_Permission.Available_Roles.permission.view')))
                                    <a class="nav-link {{ menuActive(['admin.role', 'admin.createRole', 'admin.editRole']) }}"
                                       href="{{ route('admin.role') }}">
                                        <span class="nav-link-title">@lang('Available Roles')</span>
                                        <small class="d-none">@lang("Roles And Permission > Available Roles")</small>
                                    </a>
                                @endif

                                @if(adminAccessRoute(config('permissionList.Roles_And_Permission.Manage_Staff.permission.view')))
                                    <a class="nav-link {{ menuActive(['admin.role.staff', 'admin.role.staffCreate', 'admin.role.staffEdit']) }}"
                                       href="{{ route('admin.role.staff') }}">
                                        <span class="nav-link-title">@lang('Manage Staff')</span>
                                        <small class="d-none">@lang("Roles And Permission > Manage Staff")</small>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(adminAccessRoute(array_merge(config('permissionList.Settings_Panel.Control_Panel.permission.view'), config('permissionList.Settings_Panel.Payment_Setting.permission.view'), config('permissionList.Settings_Panel.Withdraw_Setting.permission.view'))))
                        <span class="dropdown-header mt-3"> @lang('SETTINGS PANEL')</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        @if(adminAccessRoute(config('permissionList.Settings_Panel.Control_Panel.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link {{ menuActive(controlPanelRoutes()) }}"
                                   href="{{ route('admin.settings') }}" data-placement="left">
                                    <i class="bi bi-gear nav-icon"></i>
                                    <span class="nav-link-title">@lang('Control Panel')</span>
                                </a>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Settings_Panel.Payment_Setting.permission.view')))
                            <div
                                class="nav-item {{ menuActive(['admin.payment.methods', 'admin.edit.payment.methods', 'admin.deposit.manual.index', 'admin.deposit.manual.create', 'admin.deposit.manual.edit'], 3) }}">
                                <a class="nav-link dropdown-toggle"
                                   href="#navbarVerticalGatewayMenu"
                                   role="button"
                                   data-bs-toggle="collapse"
                                   data-bs-target="#navbarVerticalGatewayMenu"
                                   aria-expanded="false"
                                   aria-controls="navbarVerticalGatewayMenu">
                                    <i class="bi-briefcase nav-icon"></i>
                                    <span class="nav-link-title">@lang('Payment Setting')</span>
                                </a>
                                <div id="navbarVerticalGatewayMenu"
                                     class="nav-collapse collapse {{ menuActive(['admin.payment.methods', 'admin.edit.payment.methods', 'admin.deposit.manual.index', 'admin.deposit.manual.create', 'admin.deposit.manual.edit'], 2) }}"
                                     data-bs-parent="#navbarVerticalGatewayMenu">
                                    <a class="nav-link {{ menuActive(['admin.payment.methods', 'admin.edit.payment.methods',]) }}"
                                       href="{{ route('admin.payment.methods') }}">
                                        <span class="nav-link-title">@lang('Payment Gateway')</span>
                                        <small class="d-none">@lang("Payment Setting > Payment Gateway")</small>
                                    </a>
                                    <a class="nav-link {{ menuActive([ 'admin.deposit.manual.index', 'admin.deposit.manual.create', 'admin.deposit.manual.edit']) }}"
                                       href="{{ route('admin.deposit.manual.index') }}">
                                        <span class="nav-link-title">@lang('Manual Gateway')</span>
                                        <small class="d-none">@lang("Payment Setting > Manual Gateway")</small>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if(adminAccessRoute(config('permissionList.Settings_Panel.Withdraw_Setting.permission.view')))
                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle {{ menuActive(['admin.payout.method.list','admin.payout.method.create','admin.manual.method.edit','admin.payout.method.edit','admin.payout.withdraw.days'], 3) }}"
                                   href="#navbarVerticalWithdrawMenu"
                                   role="button"
                                   data-bs-toggle="collapse"
                                   data-bs-target="#navbarVerticalWithdrawMenu"
                                   aria-expanded="false"
                                   aria-controls="navbarVerticalWithdrawMenu">
                                    <i class="bi bi-wallet2 nav-icon"></i>
                                    <span class="nav-link-title">@lang('Withdraw Setting')</span>
                                </a>
                                <div id="navbarVerticalWithdrawMenu"
                                     class="nav-collapse collapse {{ menuActive(['admin.payout.method.list','admin.payout.method.create','admin.manual.method.edit','admin.payout.method.edit','admin.payout.withdraw.days'], 2) }}"
                                     data-bs-parent="#navbarVerticalWithdrawMenu">
                                    <a class="nav-link {{ menuActive(['admin.payout.method.list','admin.payout.method.create','admin.manual.method.edit','admin.payout.method.edit']) }}"
                                       href="{{ route('admin.payout.method.list') }}">
                                        <span class="nav-link-title">@lang('Withdraw Method')</span>
                                        <small class="d-none">@lang("Withdraw Setting > Withdraw Method")</small>
                                    </a>
                                    <a class="nav-link  {{ menuActive(['admin.payout.withdraw.days']) }}"
                                       href="{{ route("admin.payout.withdraw.days") }}">
                                        <span class="nav-link-title">@lang('Withdrawal Days Setup')</span>
                                        <small class="d-none">@lang("Withdraw Setting > Withdrawal Days Setup")</small>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif


                    @if(adminAccessRoute(array_merge(config('permissionList.Theme_Settings.Pages.permission.view'), config('permissionList.Theme_Settings.Manage_Menu.permission.view'), config('permissionList.Theme_Settings.Manage_Theme.permission.view'), config('permissionList.Theme_Settings.Manage_Content.permission.view'))))
                        <span class="dropdown-header mt-3">@lang("Themes Settings")</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        <div id="navbarVerticalThemeMenu">

                            @if(adminAccessRoute(config('permissionList.Theme_Settings.Manage_Theme.permission.view')))
                                <div class="nav-item">
                                    <a class="nav-link {{ menuActive(['admin.manage.theme']) }}"
                                       href="{{ route('admin.manage.theme') }}" data-placement="left">
                                        <i class="fal fa-image nav-icon"></i>
                                        <span class="nav-link-title">@lang('Manage Theme')</span>
                                    </a>
                                </div>
                            @endif

                            @if(adminAccessRoute(config('permissionList.Theme_Settings.Pages.permission.view')))
                                <div class="nav-item">
                                    <a class="nav-link {{ menuActive(['admin.page.index','admin.create.page','admin.edit.page']) }}"
                                       href="{{ route('admin.page.index', basicControl()->theme) }}"
                                       data-placement="left">
                                        <i class="fa-light fa-list nav-icon"></i>
                                        <span class="nav-link-title">@lang('Pages')</span>
                                    </a>
                                </div>
                            @endif

                            @if(adminAccessRoute(config('permissionList.Theme_Settings.Manage_Menu.permission.view')))
                                <div class="nav-item">
                                    <a class="nav-link {{ menuActive(['admin.manage.menu']) }}"
                                       href="{{ route('admin.manage.menu') }}" data-placement="left">
                                        <i class="bi-folder2-open nav-icon"></i>
                                        <span class="nav-link-title">@lang('Manage Menu')</span>
                                    </a>
                                </div>
                            @endif

                        </div>


                        @if(adminAccessRoute(config('permissionList.Theme_Settings.Manage_Content.permission.view')))
                            @php
                                $segments = request()->segments();
                                $last  = end($segments);
                            @endphp
                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle {{ menuActive(['admin.manage.content', 'admin.manage.content.multiple', 'admin.content.item.edit*'], 3) }}"
                                   href="#navbarVerticalContentsMenu"
                                   role="button" data-bs-toggle="collapse"
                                   data-bs-target="#navbarVerticalContentsMenu" aria-expanded="false"
                                   aria-controls="navbarVerticalContentsMenu">
                                    <i class="fa-light fa-pen nav-icon"></i>
                                    <span class="nav-link-title">@lang('Manage Content')</span>
                                </a>
                                <div id="navbarVerticalContentsMenu"
                                     class="nav-collapse collapse {{ menuActive(['admin.manage.content', 'admin.manage.content.multiple', 'admin.content.item.edit*'], 2) }}"
                                     data-bs-parent="#navbarVerticalContentsMenu">

                                    @foreach(array_diff(array_keys(config('contents')), ['message', 'content_media']) as $theme)

                                        @if($theme == basicControl()->theme)
                                            @foreach(config('contents')[$theme] as $name => $content)
                                        <div class="talk-nav-link">
                                            <a class="nav-link contentTitle {{ ($last == $name) ? 'active' : '' }}"
                                               href="{{ route('admin.manage.content', $name) }}">
                                                <span>@lang(stringToTitle($name))</span>
                                                <small class="d-none">@lang("Manage Content > ".stringToTitle($name))</small>
                                            </a>
                                            @php
                                                $previewImg = @asset(config('contents.' .$theme. '.' . $name . '.preview'));
                                            @endphp
                                            @if($previewImg)
                                                <div class="talk-nav-link-icon">
                                                    <a href="{{$previewImg}}" data-fancybox="gallery"><i class="fa-light fa-eye"></i></a>
                                                </div>
                                            @endif
                                        </div>
                                            @endforeach
                                        @endif

                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif


                    @if(adminAccessRoute(array_merge(config('permissionList.Manage_Blog.Category.permission.view'), config('permissionList.Manage_Blog.Blog.permission.view'))))
                        <span class="dropdown-header mt-3">@lang("Manage Blog")</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        <div id="navbarVerticalThemeMenu">
                            @if(adminAccessRoute(config('permissionList.Manage_Blog.Category.permission.view')))
                                <div class="nav-item">
                                    <a class="nav-link {{ menuActive(['admin.blog-category.index', 'admin.blog-category.create', 'admin.blog-category.edit']) }}"
                                       href="{{ route('admin.blog-category.index') }}"
                                       data-placement="left">
                                        <i class="fa-light fa-list nav-icon"></i>
                                        <span class="nav-link-title">@lang('Category')</span>
                                    </a>
                                </div>
                            @endif

                            @if(adminAccessRoute(config('permissionList.Manage_Blog.Blog.permission.view')))
                                <div class="nav-item">
                                    <a class="nav-link {{ menuActive(['admin.blogs.index', 'admin.blogs.create','admin.blogs.edit*']) }}"
                                       href="{{ route('admin.blogs.index') }}"
                                       data-placement="left">
                                        <i class="fal fa-blog nav-icon"></i>
                                        <span class="nav-link-title">@lang('Blog')</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                        <div class="nav-item">
                            <a class="nav-link"
                               href="{{ route('clear') }}" data-placement="left">
                                <i class="bi bi-radioactive nav-icon"></i>
                                <span class="nav-link-title">@lang('Clear Cache')</span>
                            </a>
                        </div>

                    @foreach(collect(config('generalsettings.settings')) as $key => $setting)
                        <div class="nav-item d-none">
                            <a class="nav-link  {{ isMenuActive($setting['route']) }}"
                               href="{{ getRoute($setting['route'], $setting['route_segment'] ?? null) }}">
                                <i class="{{$setting['icon']}} nav-icon"></i>
                                <span class="nav-link-title">{{ __(getTitle($key.' '.'Settings')) }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="navbar-vertical-footer">
                    <ul class="navbar-vertical-footer-list">
                        <li class="navbar-vertical-footer-list-item">
                            <span class="dropdown-header">@lang('Version 2.0')</span>
                        </li>
                        <li class="navbar-vertical-footer-list-item">
                            <div class="dropdown dropup">
                                <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle"
                                        id="selectThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                        data-bs-dropdown-animation></button>
                                <div class="dropdown-menu navbar-dropdown-menu navbar-dropdown-menu-borderless"
                                     aria-labelledby="selectThemeDropdown">
                                    <a class="dropdown-item" href="javascript:void(0)" data-icon="bi-moon-stars"
                                       data-value="auto">
                                        <i class="bi-moon-stars me-2"></i>
                                        <span class="text-truncate"
                                              title="Auto (system default)">@lang("Default")</span>
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)" data-icon="bi-brightness-high"
                                       data-value="default">
                                        <i class="bi-brightness-high me-2"></i>
                                        <span class="text-truncate"
                                              title="Default (light mode)">@lang("Light Mode")</span>
                                    </a>
                                    <a class="dropdown-item active" href="javascript:void(0)" data-icon="bi-moon"
                                       data-value="dark">
                                        <i class="bi-moon me-2"></i>
                                        <span class="text-truncate" title="Dark">@lang("Dark Mode")</span>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</aside>


@push('js-lib')
    <script src="{{asset('assets/global/js/fancybox.js')}}"></script>
@endpush


