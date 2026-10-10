@extends('admin.layouts.app')
@section('page_title', __('Socialite Configuration'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Socialite Configuration')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Socialite Configuration')</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>
            <div class="col-lg-7">
                <div class="d-grid gap-3 gap-lg-5">
                    <div id="socialAccountsSection" class="card">
                        <div class="card-header">
                            <h4 class="card-title">@lang("Socialite Configuration")</h4>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-lg list-group-flush list-group-no-gutters">
                                <div class="list-group-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img class="avatar avatar-xs avatar-4x3 list-group-icon"
                                                 src="{{ asset('assets/admin/google.png') }}"
                                                 alt="Plugin Image">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="row align-items-center">
                                                <div class="col-sm mb-2 mb-sm-0">
                                                    <h4 class="mb-0">@lang('Google')</h4>
                                                    <p class="fs-5 text-body mb-0">@lang("Socialite login your customers,they\'ll love you for it")</p>
                                                </div>
                                                <div class="col-sm-auto">
                                                    <a class="btn btn-white btn-sm"
                                                       href="{{ route('admin.google.control') }}">
                                                        <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group list-group-lg list-group-flush list-group-no-gutters">
                                <div class="list-group-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img class="avatar avatar-xs avatar-4x3 list-group-icon"
                                                 src="{{ asset('assets/admin/facebook.png') }}"
                                                 alt="Plugin Image">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="row align-items-center">
                                                <div class="col-sm mb-2 mb-sm-0">
                                                    <h4 class="mb-0">@lang('Facebook')</h4>
                                                    <p class="fs-5 text-body mb-0">@lang("Socialite login your customers,they\'ll love you for it")</p>
                                                </div>
                                                <div class="col-sm-auto">
                                                    <a class="btn btn-white btn-sm"
                                                       href="{{ route('admin.facebook.control') }}">
                                                        <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group list-group-lg list-group-flush list-group-no-gutters">
                                <div class="list-group-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img class="avatar avatar-xs avatar-4x3 list-group-icon"
                                                 src="{{ asset('assets/admin/github.png') }}"
                                                 alt="Plugin Image">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="row align-items-center">
                                                <div class="col-sm mb-2 mb-sm-0">
                                                    <h4 class="mb-0">@lang('Github')</h4>
                                                    <p class="fs-5 text-body mb-0">@lang("Socialite login your customers,they\'ll love you for it")</p>
                                                </div>
                                                <div class="col-sm-auto">
                                                    <a class="btn btn-white btn-sm"
                                                       href="{{ route('admin.github.control') }}">
                                                        <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

