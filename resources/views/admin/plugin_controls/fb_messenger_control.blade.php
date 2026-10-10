@extends('admin.layouts.app')
@section('page_title', __('FB Messenger Configuration'))
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
                                <a class="breadcrumb-link" href="{{ route('admin.plugin.config') }}">
                                    @lang('Plugin Controls')</a>
                            <li class="breadcrumb-item active" aria-current="page">@lang('FB Messenger Configuration')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('FB Messenger Configuration')</h1>
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
                            <h4 class="card-header-title">@lang('FB Messenger Configuration')</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.fb.messenger.configuration.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">
                                    <label for="fb_app_id" class="col-sm-3 col-form-label form-label">@lang('App ID') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang("Please put your Facebook APP ID.")"></i></label>

                                    <div class="col-sm-9">
                                        <input type="text"
                                               class="form-control @error('fb_app_id') is-invalid @enderror"
                                               name="fb_app_id" id="fb_app_id"
                                               placeholder="@lang("App Id")"
                                               value="{{ old('fb_app_id',$basicControl->fb_app_id) }}"
                                               autocomplete="off">
                                        @error('fb_app_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="urlLabel" class="col-sm-3 col-form-label form-label">@lang('Page ID') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang("Please put your Facebook Page ID.")"></i></label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               class="form-control @error('fb_page_id') is-invalid @enderror"
                                               name="fb_page_id" id="fb_page_id"
                                               placeholder="@lang("Page ID")"
                                               value="{{ old('fb_page_id',$basicControl->fb_page_id) }}"
                                               autocomplete="off">
                                        @error('fb_page_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>


                                <!-- Form Switch -->
                                <label class="row form-check form-switch mb-4" for="fb_messenger_status">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="d-block text-dark">@lang("FB Messenger Status")</span>
                                          <span class="d-block fs-5">@lang("Enable status fb messenger chat in your system.")</span>
                                        </span>
                                    <span class="col-4 col-sm-3 text-end">

                                        <input type='hidden' value='0' name='fb_messenger_status'>
                                          <input  type="checkbox" name="fb_messenger_status"  id="fb_messenger_status"
                                                  value="1" {{ $basicControl->fb_messenger_status == 1 ? 'checked' : ''}} class="form-check-input">
                                        </span>


                                    @error('fb_messenger_status')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="d-flex justify-content-end mt-4">
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






