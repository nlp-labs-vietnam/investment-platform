@extends('admin.layouts.app')
@section('page_title', __('Dashboard'))
@section('content')
    <div class="content container-fluid dashboard-height">
        <div class="row">
            <div class="col-xl-12">
                <!-- Card -->
                <div class="card user-card card-hover-shadow" href="javascript:void(0)"
                     id="userRecord">
                    <div class="card-body block-statistics dashboard-alternative">
                        <div class="row g-3 mb-4">
                            <div class="col-lg-7 p-5 mt-5">
                                <h2 class="dashboard-alternative-user-name"> Hi {{ auth()->user()->name ?? auth()->user()->username }}, </h2>
                                <h3 class="dashboard-alternative-welcome-heading"><span class="dashboard-alternative-welcome">@lang('Welcome to')</span> <br>
                                    <img class="navbar-brand-logo navbar-brand-logo-auto"
                                         src="{{ getFile(in_array(session()->get('themeMode'),['auto',null])?$basicControl->admin_dark_mode_logo_driver : $basicControl->admin_logo_driver, in_array(session()->get('themeMode'),['auto',null])?$basicControl->admin_dark_mode_logo:$basicControl->admin_logo, true) }}"
                                         alt="{{ basicControl()->site_title }}"
                                         data-hs-theme-appearance="default">
                                </h3>
                                <p class="dashboard-alternative-para">
                                    @lang('Welcome to the Admin Dashboard! Manage with') <br> @lang("confidence, and let's keep")
                                    @lang('everything running') <br> @lang('smoothly together').
                                </p>
                            </div>

                            <div class="col-lg-5">
                                <img src="{{ asset('assets/admin/img/dashboard_img.png') }}" alt="" class="img-fluid dashboard-alternative-img">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push("script")
@endpush
