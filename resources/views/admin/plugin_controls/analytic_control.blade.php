@extends('admin.layouts.app')
@section('page_title', __('Google Analytics Configuration'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a class="breadcrumb-link" href="{{ route('admin.settings') }}">@lang('Settings')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a class="breadcrumb-link"
                                   href="{{ route('admin.plugin.config') }}">@lang('Plugin Controls')</a>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang('Google Analytics Configuration')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Google Analytics Configuration')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.plugin'), 'suffix' => ''])
            </div>
            <div class="col-lg-7">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card h-100">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">@lang('Google Analytics Configuration')</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.google.analytics.configuration.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">
                                    <label for="MEASUREMENT_ID"
                                           class="col-sm-3 col-form-label form-label">@lang("MEASUREMENT ID")</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               class="form-control @error('MEASUREMENT_ID') is-invalid @enderror"
                                               name="MEASUREMENT_ID" id="MEASUREMENT_ID"
                                               placeholder="MEASUREMENT ID"
                                               value="{{ old('MEASUREMENT_ID',$basicControl->measurement_id) }}"
                                               autocomplete="off">
                                        @error('MEASUREMENT_ID')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <label class="row form-check form-switch mb-4" for="googleAnalyticStatus">
                                    <span class="col-8 col-sm-9 ms-0">
                                      <span class="d-block text-dark">@lang("Google Analytic Status")</span>
                                      <span class="d-block fs-5">
                                          @lang("Enable google analytics is used to track and analyze website traffic.")
                                      </span>
                                    </span>
                                    <span class="col-4 col-sm-3 text-end">
                                            <input type='hidden' value='0' name='analytic_status'>
                                            <input
                                                class="form-check-input @error('analytic_status') is-invalid @enderror"
                                                type="checkbox" name="analytic_status"
                                                id="googleAnalyticStatus"
                                                value="1" {{ $basicControl->analytic_status == 1 ? 'checked' : ''}}>
                                        </span>
                                    @error('analytic_status')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection







