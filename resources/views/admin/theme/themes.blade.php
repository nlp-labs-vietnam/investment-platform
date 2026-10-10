@extends('admin.layouts.app')
@section('page_title', __('Manage Theme'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manage Theme')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Themes')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('All Themes')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach ($theme as $key => $data)
                <div class="col-lg-5 mb-3 mb-lg-5">
                    <div class="card ">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title"> {{$data['title']}}</h4>
                        </div>
                        <form action="{{ route('admin.rank.bonus.update') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <img class="w-100 " src="{{asset($data['path'])}}" alt="@lang('theme-image')">
                            </div>

                            @if(adminAccessRoute(config('permissionList.Theme_Settings.Manage_Menu.permission.edit')))
                                <div class="card-footer">
                                    @if (basicControl()->theme == $key)
                                        <button
                                            type="button"
                                            disabled
                                            class="btn waves-effect waves-light btn-rounded btn-dark btn-block w-100 mt-5">
                                            <span><i class="fas fa-check-circle pr-2"></i> @lang('Selected')</span>
                                        </button>
                                    @else
                                        <button
                                            type="button"
                                            class="btn waves-effect waves-light btn-rounded btn-primary btn-block w-100 activeThemeBtn"
                                            data-bs-toggle="modal" data-bs-target="#activeThemeModal"
                                            data-route="{{route('admin.theme.active', $key)}}">
                                            <span><i class="fas fa-save pr-2"></i> @lang('Select As Active')</span>
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Active Theme Modal -->
    <div class="modal fade" id="activeThemeModal" tabindex="-1" role="dialog"
         aria-labelledby="loginAsUserModalLabel"
         data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="loginAsManagerModalLabel"><i
                            class="fas fa-plus-circle me-1"></i> @lang('Active Confirmation')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" enctype="multipart/form-data" class="activeThemeForm">
                    @csrf
                    <div class="modal-body">

                        <p>@lang('Are you sure to active this theme?')</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 500
            })

            $('.activeThemeBtn').on('click', function () {
                $('.activeThemeForm').attr('action', $(this).data('route'));
            })

        })
    </script>
@endpush
