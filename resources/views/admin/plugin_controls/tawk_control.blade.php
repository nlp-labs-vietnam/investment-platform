@extends('admin.layouts.app')
@section('page_title', __('Tawk Configuration'))
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
                                    @lang('Plugin Controls')
                                </a>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Tawk Configuration')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Tawk Configuration')</h1>
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
                            <h4 class="card-header-title">@lang('Tawk Configuration')</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.tawk.configuration.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <label for="tawk_id"
                                           class="col-sm-3 col-form-label form-label">@lang("Tawk ID")<i
                                            class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="@lang("Please put your Tawk ID.")"></i></label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               id="tawk_id"
                                               class="form-control @error('tawk_id') is-invalid @enderror"
                                               name="tawk_id" placeholder="@lang("Takw ID")"
                                               value="{{ old('tawk_id',$basicControl->tawk_id) }}" autocomplete="off">
                                        @error('tawk_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Form Switch -->
                                <label class="row form-check form-switch mb-4" for="tawkStatus">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="d-block text-dark">@lang("Tawk Status")</span>
                                          <span
                                              class="d-block fs-5">@lang("Enable your tawk chat system in your application.")</span>
                                        </span>
                                    <span class="col-4 col-sm-3 text-end">
                                             <input type='hidden' value='0' name='status'>
                                            <input
                                                class="form-check-input @error('status') is-invalid @enderror"
                                                type="checkbox" name="status"
                                                id="tawkStatus"
                                                value="1" {{ $basicControl->tawk_status == 1 ? 'checked' : ''}}>
                                        </span>
                                    @error('tawk_status')
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





