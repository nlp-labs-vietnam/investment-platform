@extends('admin.layouts.app')
@section('page_title', __('Maintenance Mode'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Maintenance Mode')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Maintenance Mode')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card h-100">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">@lang('Maintenance Mode')</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.maintenance.mode.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <label for="heading" class="form-label">@lang('Heading')</label>
                                    <div class="col-sm-12">
                                        <input type="text"
                                               class="form-control @error('heading') is-invalid @enderror"
                                               name="heading" id="heading"
                                               placeholder="@lang("Heading")"
                                               value="{{ old('heading', $maintenanceMode->heading) }}"
                                               autocomplete="off">
                                        @error('heading')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="summernote" class="form-label mb-0">@lang("Description")</label>
                                        </div>
                                        <textarea class="form-control" name="description" id="summernote">{{ old("description", $maintenanceMode->description) }}</textarea>
                                        @error("description")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($maintenanceMode->image_driver, $maintenanceMode->image) }}"
                                                 alt="@lang("Breadcrumb Image")"
                                                 data-hs-theme-appearance="default">

                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($maintenanceMode->image_driver, $maintenanceMode->image) }}"
                                                 alt="@lang("Breadcrumb Image")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input" name="image" id="logoUploader"
                                                   data-hs-file-attach-options='{
                                              "textTarget": "#logoImg",
                                              "mode": "image",
                                              "targetAttr": "src",
                                              "allowTypes": [".png", ".jpeg", ".jpg"]
                                           }'>
                                        </label>
                                        @error("image")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                   <div class="col-md-8">
                                       <label class="row form-check form-switch mb-4" for="maintenanceModeStatus">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="d-block text-dark">@lang("Maintenance Mode Status")</span>
                                          <span class="d-block fs-5">@lang("Maintenance mode is a feature that allows you to temporarily disable access to your online store's frontend.")</span>
                                        </span><span class="col-4 col-sm-3 text-end">
                                        <input type='hidden' value='0' name='is_maintenance_mode'>
                                          <input  type="checkbox" name="is_maintenance_mode" id="maintenanceModeStatus" class="form-check-input" value="1"
                                          {{ $basicControl->is_maintenance_mode == 1 ? 'checked' : '' }}>
                                        </span>
                                       @error('is_maintenance_mode')
                                       <span class="invalid-feedback d-block">{{ $message }}</span>
                                       @enderror
                                       </label>
                                   </div>
                                </div>

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

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')
    <script>;
        $(document).ready(function () {
            new HSFileAttach('.js-file-attach')
            $("#summernote").summernote({
                height: 300,
            });
        })
    </script>
@endpush






