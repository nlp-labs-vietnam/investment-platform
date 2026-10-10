@extends('admin.layouts.app')
@section('page_title', __('Manage Theme'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Frontend Management')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manage Theme')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Manage Theme')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($allThemes as $key => $theme)
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header bg-dark p-3 text-white d-flex justify-content-between">
                                            <span>{{ucfirst($theme)}}</span>
                                            <a href="{{route('admin.page.index', $theme)}}"><i class="bi bi-gear text-white"></i></a>
                                        </div>
                                        <div class="card-body m-0 p-0">
                                            <img class="w-100" src="{{ asset("assets/theme.png") }}" alt="@lang('theme-image')" >
                                        </div>
                                        <div class="card-footer">
                                            @if ($basicControl->theme == $theme)
                                                <button
                                                    type="button"
                                                    disabled
                                                    class="btn waves-effect waves-light btn-rounded btn-success btn-block mt-3">
                                                    <span><i class="fas fa-check-circle pr-2"></i> @lang('Selected')</span>
                                                </button>
                                            @else
                                                <button
                                                    type="button"
                                                    class="btn btn-primary mt-3 activateBtn"
                                                    data-bs-toggle="modal" data-bs-target="#activateModal"
                                                    data-route="{{route('admin.activate.themeUpdate', $theme)}}">
                                                    <span><i class="fas fa-save pr-2"></i> @lang('Select As Active')</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="activateModal" tabindex="-1" role="dialog" aria-labelledby="activateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="setAsDefaultModalLabel"><i class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>@lang('Do you want to activate this theme?')</p>
                </div>
                <form action="" method="post" class="actionForm">
                    @csrf
                    @method('put')
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">@lang("Confirm")</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->



@endsection


@push('script')
    <script>
        "use strict";
        $(document).ready(function () {

            $('.activateBtn').on('click', function () {
                $('.actionForm').attr('action', $(this).data('route'));
            })

        });
    </script>
@endpush








