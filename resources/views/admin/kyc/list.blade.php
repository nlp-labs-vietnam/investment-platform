@extends('admin.layouts.app')
@section('page_title', __('KYC Setting'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('KYC Setting')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('KYC Setting')</h1>
                </div>
            </div>
        </div>

        <div class="card">
            @if(adminAccessRoute(config('permissionList.Kyc_Management.Kyc_Settings.permission.add')))
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">@lang('KYC Form')</h4>
                    <a href="{{ route('admin.kyc.create') }}" class="btn btn-primary">@lang('Add Form')</a>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>@lang('No.')</th>
                        <th>@lang('Form Type')</th>
                        <th>@lang('Status')</th>
                        @if(adminAccessRoute(config('permissionList.Kyc_Management.Kyc_Settings.permission.edit')))
                            <th>@lang('Action')</th>
                        @endif
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($kycList as $key => $kyc)
                        <tr>
                            <td>{{ $loop->index + 1  }}</td>
                            <td>
                                @lang($kyc->name)
                            </td>
                            <td>
                                @if($kyc->status ==  0)
                                    <span class="badge bg-soft-danger text-danger">
                                                <span class="legend-indicator bg-danger"></span>@lang('Inactive')
                                            </span>
                                @elseif($kyc->status ==  1)
                                    <span class="badge bg-soft-success text-success">
                                                <span class="legend-indicator bg-success"></span>@lang('Active')
                                                </span>
                                @endif
                            </td>
                            @if(adminAccessRoute(config('permissionList.Kyc_Management.Kyc_Settings.permission.edit')))
                            <td>
                                <a class="btn btn-white btn-sm" href="{{ route('admin.kyc.edit', $kyc->id) }}">
                                    <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                </a>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr class="odd">
                            <td valign="top" colspan="8" class="dataTables_empty">
                                <div class="text-center p-4">
                                    <img class="mb-3 dataTables-image"
                                         src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description"
                                         data-hs-theme-appearance="default">
                                    <img class="mb-3 dataTables-image"
                                         src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                         alt="Image Description" data-hs-theme-appearance="dark">
                                    <p class="mb-0">@lang("No data to show")</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/flatpickr/dist/flatpickr.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/vendor/flatpickr/dist/flatpickr.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function () {
            HSCore.components.HSFlatpickr.init('.js-flatpickr')
        })();
    </script>
@endpush



