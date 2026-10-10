@extends('admin.layouts.app')
@section('page_title', __('Default Template'))
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
                                aria-current="page">@lang("Default Template")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Default Template")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.'.(url()->previous() == route('admin.settings', 'sms') || url()->previous() == route('admin.sms.controls') ? 'sms' : 'email')), 'suffix' => ''])
            </div>
            <div class="col-lg-9">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">@lang("Default Template")</h4>
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
                                <tr>
                                    <td>
                                        <pre>@lang('[[name]]')</pre>
                                    </td>
                                    <td> @lang("User's Name will replace here.") </td>
                                </tr>
                                <tr>
                                    <td>
                                        <pre>@lang('[[message]]')</pre>
                                    </td>
                                    <td>@lang("Application notification message will replace here.")</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.email.template.default') }}" method="POST">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('From Email')</label>
                                        <input type="text" name="sender_email" class="form-control"
                                               placeholder="@lang('Enter default form email address')"
                                               value="{{ $basicControl->sender_email }}">
                                        @error('sender_email')<span class="text-danger">@lang($message)</span>@enderror

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-label">@lang('From Email Name')</label>
                                            <input type="text" name="sender_email_name" class="form-control"
                                                   placeholder="@lang('Enter default form email name')"
                                                   value="{{ $basicControl->sender_email_name }}">
                                            @error('sender_email_name')<span
                                                class="text-danger">@lang($message)</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                <label class="form-label">@lang('Email Description')</label>
                                <textarea class="form-control" name="email_description" id="summernote"
                                          placeholder="@lang('Enter default form email template')" rows="20">{{$basicControl->email_description}}</textarea>


                                <button type="submit"
                                        class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save Changes')</button>
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
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            $('#summernote').summernote({
                height: 250,
                dialogsInBody: true,
                callbacks: {
                    onBlurCodeview: function() {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                }
            });
        });
    </script>
@endpush




