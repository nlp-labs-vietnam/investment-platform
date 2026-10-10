@extends('admin.layouts.app')
@section('page_title', __('Edit email template'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang("Dashboard")
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a class="breadcrumb-link" href="{{ route('admin.settings') }}">@lang('Settings')</a>
                            </li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("Edit email template")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Edit email template")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">@lang("Edit Email Template")</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">@lang('Short keys for use in ') {{ __($template->name) }}</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                <tr>
                                    <th width="50%"> @lang('SHORTCODE') </th>
                                    <th width="50%"> @lang('DESCRIPTION') </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($template->short_keys as $key=> $value)
                                    <tr>
                                        <td>
                                            <pre>[[@lang($key)]]</pre>
                                        </td>
                                        <td>@lang($value)</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body pt-0">
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                @foreach($languages as $key => $value)
                                    <li class="nav-item">
                                        <a id="nav-{{$value->id}}-eg1-tab" href="#nav-{{$value->id}}-eg1"
                                           data-bs-toggle="pill" data-bs-target="#nav-{{$value->id}}-eg1" role="tab"
                                           aria-controls="nav-{{$value->id}}-eg1"
                                           aria-selected="{{ old('language_id') == $value->id ? 'true' : (!old('language_id') && $key == 0 ? 'true' : 'false') }}"
                                           class="nav-link {{ old('language_id') == $value->id ? 'active' : (!old('language_id') && $key == 0 ? 'active' : '') }}">
                                            <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">{{ __($value->name) }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @foreach($languages as $key => $value)
                                    <div
                                        class="tab-pane {{ old('language_id') == $value->id ? 'show active' : (!old('language_id') && $key == 0 ? 'show active' : '') }}"
                                        id="nav-{{$value->id}}-eg1" role="tabpanel"
                                        aria-labelledby="nav-{{$value->id}}-eg1-tab">
                                        <form method="post"
                                              action="{{ route('admin.email.template.update', [$template->id, $value->id]) }}"
                                              enctype="multipart/form-data"
                                              class="base-form">
                                            @csrf
                                            @method('put')
                                            <div class="my-4 section-title">{{trans('Mail in')}}  {{ __($value->name) }}
                                                : {{ __(isset($templates[$key]) ? $templates[$key]->name : '') }}</div>
                                            <div class="row mb-4 d-flex align-items-center">
                                                <div class="col-md-6">
                                                    <label class="form-label">@lang('Subject')</label>
                                                    <input
                                                        class="form-control @error('subject.'.$value->id) is-invalid @enderror"
                                                        type="text" name="subject[{{ $value->id }}]"
                                                        value="{{ old('subject.'.$value->id, isset($templates[$key]) ? $templates[$key]->subject : '') }}"
                                                        autocomplete="off">
                                                    @error('subject.'.$value->id)
                                                    <span class="invalid-feedback">
                                                        {{ __($message) }}
                                                    </span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">@lang('Mail From')</label>
                                                    <input
                                                        class="form-control @error('email_from.'.$value->id) is-invalid @enderror"
                                                        type="text" name="email_from[{{ $value->id }}]"
                                                        value="{{ old('email_from.'.$value->id, isset($templates[$key]) ? $templates[$key]->email_from : '') }}"
                                                        autocomplete="off">
                                                    @error('email_from.'.$value->id)
                                                    <span class="invalid-feedback">
                                                        {{ __($message) }}
                                                    </span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mt-2">
                                                    <label class="form-label">@lang('Name')</label>
                                                    <input
                                                        class="form-control @error('name.'.$value->id) is-invalid @enderror"
                                                        type="text" name="name[{{ $value->id }}]"
                                                        value="{{ old('name.'.$value->id, isset($templates[$key]) ? $templates[$key]->name : '') }}"
                                                        autocomplete="off">
                                                    @error('name.'.$value->id)
                                                    <span class="invalid-feedback">
                                                        {{ __($message) }}
                                                    </span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mt-2">
                                                    <label class="row form-check form-switch"
                                                           for="status[{{ $value->id }}]">
                                                                <span class="col-8 col-sm-9 ms-0 mt-4">
                                                                  <span class="d-block text-dark">@lang("Status")</span>
                                                                  <span
                                                                      class="d-block fs-5">@lang("Enable status for email notification.")</span>
                                                                </span>
                                                        <span class="col-4 col-sm-2 text-end">
                                                                <input type='hidden' value='0' name='mail_status'>
                                                                <input
                                                                    class="form-check-input @error('mail_status') is-invalid @enderror"
                                                                    type="checkbox" name="mail_status"
                                                                    id="mail_status[{{ $value->id }}]"
                                                                    value="1" {{ old('mail_status.'.$value->id, $templates[$key]->status['mail'] ?? 0 ) == 1 ? 'checked' : ''}}>

                                                            </span>
                                                        @error('mail_status.'.$value->id)
                                                        <span class="text-danger">
                                                        {{ __($message) }}
                                                    </span>
                                                        @enderror
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label>@lang('Message')</label>
                                                    <textarea class="form-control summernote @error('email_template.'.$value->id) is-invalid @enderror"
                                                        name="email_template[{{ $value->id }}]">{{ old('email_template.'.$value->id, $templates[$key]->email ?? '') }}</textarea>
                                                    @error('email_template.'.$value->id)
                                                    <span class="invalid-feedback">
                                                        {{ __($message) }}
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <button type="submit"
                                                    class="btn btn-primary mt-3">@lang('Save Changes')</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
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
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            $('.summernote').summernote({
                height: 200,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable')
                            .val();
                        $(this).val(codeviewHtml);
                    }
                }
            });
        });
    </script>
@endpush




