@extends('admin.layouts.app')
@section('page_title', __('Create Address'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="{{ route('admin.dashboard') }}">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="{{ route('admin.addresses') }}">@lang('Addresses')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('page_title')</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row payment_method">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h2 class="card-title h4">@lang('Add New Address')</h2>
                            <a href="{{route('admin.addresses')}}" class="btn btn-sm btn-info">
                                <i class="bi-arrow-left"></i> @lang('Back')
                            </a>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.address.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="title" class="form-label">@lang('Address')</label>
                                        <input type="text" class="form-control  @error('title') is-invalid @enderror"
                                               name="title" id="title" placeholder="@lang('address')"
                                               aria-label="title"
                                               autocomplete="off" value="{{ old('title') }}">
                                        @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <span class="d-block text-dark">@lang("Status")</span>
                                        <label class="row form-check form-switch mt-3" for="country_status">
                                            <span class="col-2 col-sm-3">
                                                <input type='hidden' value='0' name='status'>
                                                <input class="form-check-input @error('status') is-invalid @enderror"
                                                       type="checkbox" name="status" id="status" value="1"
                                                       checked>
                                                <label class="form-check-label text-center" for="status"></label>
                                            </span>
                                            @error('status')<span
                                                class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                        </label>
                                    </div>
                                </div>



                                <div class="d-flex justify-content-start mt-4">
                                    <button type="submit" class="btn btn-primary">@lang('Save')</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('js-lib')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSTomSelect.init('.js-select')
        });
    </script>
@endpush



