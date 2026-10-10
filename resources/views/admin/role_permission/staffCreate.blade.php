@extends('admin.layouts.app')
@section('page_title',__('Create New Staff'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Role & Permissions')</a>
                            </li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="{{ route('admin.role.staff') }}">@lang('Staff List')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Create New Staff')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@yield('page_title')</h1>
                </div>
            </div>
        </div>

        <div class="content container-fluid">
            <div class="row justify-content-lg-center">
                <div class="col-lg-12">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="card-title mt-2">@lang("Create Staff")</h4>
                            </div>

                            <div class="card-body mt-2">
                                <form action="{{ route('admin.role.staffStore') }}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label"
                                                       for="role_id">@lang('Select Role')</label>
                                                <select name="role_id"
                                                        class="form-control @error('role_id') is-invalid @enderror select2">
                                                    <option value="" disabled selected>@lang('Select Role')</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}">@lang($role->name)</option>
                                                    @endforeach
                                                </select>
                                                @error('role_id')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label"
                                                       for="name">@lang('Name') </label>
                                                <input type="text" name="name"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       value="{{ old('name') }}">
                                                @error('name')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label"
                                                       for="email">@lang('Email') </label>
                                                <input type="email" name="email"
                                                       class="form-control @error('email') is-invalid @enderror"
                                                       value="{{ old('email') }}">
                                                @error('email')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label"
                                                       for="phone">@lang('Phone') </label>
                                                <input type="text" name="phone"
                                                       class="form-control @error('phone') is-invalid @enderror"
                                                       value="{{ old('phone') }}">
                                                @error('phone')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label"
                                                       for="username">@lang('Username') </label>
                                                <input type="text" name="username"
                                                       class="form-control @error('username') is-invalid @enderror"
                                                       value="{{ old('username') }}">
                                                @error('username')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label"
                                                       for=password"">@lang('Password') </label>
                                                <input type="password" name="password"
                                                       class="form-control @error('password') is-invalid @enderror"
                                                       value="{{ old('password') }}">
                                                @error('password')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="row form-check form-switch my-4"
                                                       for="status">
                                            <span class="col-8 col-sm-9 ms-0">
                                              <span class="d-block text-dark">@lang("Status")</span>
                                              <span
                                                  class="d-block fs-5">@lang("Display the status of the Staff (active or Inactive)")</span>
                                            </span>
                                                    <span class="col-4 col-sm-3 text-end">
                                                    <input type="hidden" value="0" name="status"/>
                                                    <input
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        type="checkbox" name="status"
                                                        id="status" value="1"
                                                        {{old('status') == '1' ? 'checked':''}} checked>
                                                </span>
                                                    @error('status')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="d-flex justify-content-start">
                                        <button type="submit" class="btn btn-primary">@lang('Create')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('css-lib')
@endpush

@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 500
            });
            new HSFileAttach('.js-file-attach');
        })
    </script>
@endpush










