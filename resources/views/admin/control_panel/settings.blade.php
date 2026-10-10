@extends('admin.layouts.app')
@section('page_title', __(getTitle($settings)))
@section('content')
    <div class="content container-fluid" id="setting-section">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __(getTitle($settings)) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if(adminAccessRoute(config('permissionList.Settings_Panel.Control_Panel.permission.view')))
            <div class="row">
                @forelse($settingsDetails as $key => $detail)
                    @if(isset($detail['route']))
                        <div class="col-lg-4 col-sm-6 mb-4">
                            <div class="card h-100">
                                <div class="d-flex flex-row p-4 gap-4 justify-items-center">
                                <span class="card-icon ">
                                    <i class="text-primary {{ $detail['icon'] ?? '' }}"></i>
                                </span>
                                    <div class="d-flex flex-column">
                                        <h5>{{ __(getTitle($key)) }}</h5>
                                        <span class="">{{ __($detail['short_description'] ?? '') }}</span>
                                        <span class="mt-1 link-text">
                                    <a href="{{ getRoute($detail['route'], $detail['route_segment'] ?? null) }}">@lang('Change Setting')
                                        <i class="fa-sharp fa-light fa-chevron-right"></i>
                                    </a>
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                @endforelse
            </div>
        @endif

    </div>
@endsection


