@extends('admin.layouts.app')
@section('page_title', __('SMS Setting'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang("Dashboard")</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Settings")</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("SMS Setting")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("SMS Setting")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.sms'), 'suffix' => ''])
            </div>
            <div class="col-lg-9">
                <div class="d-grid gap-3 gap-lg-5">

                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang("SMS Configuration")</h2>
                        </div>
                        <div class="table-responsive position-relative">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                id="supported_currency_table">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">@lang('Sl')</th>
                                    <th scope="col">@lang('Sms Method')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($smsControlMethod as $key => $smsMethod)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>@lang(ucfirst($key))</td>
                                        <td>
                                            @if($key == $smsMethodDefault)
                                                <span class="badge bg-soft-success text-success">
                                                        <span class="legend-indicator bg-success"></span>@lang('Active')
                                                    </span>
                                            @else
                                                <span class="badge bg-soft-danger text-danger">
                                                        <span class="legend-indicator bg-danger"></span>@lang('Inactive')
                                                    </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">

                                                @if($key !== "manual")
                                                    <a class="btn btn-white btn-sm"
                                                       href="{{ route('admin.sms.config.edit', $key) }}">
                                                        <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                                    </a>
                                                @else
                                                    <a class="btn btn-white btn-sm"
                                                       href="{{ route('admin.sms.config.edit', $key) }}">
                                                        <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                                    </a>
                                                @endif
                                                <div class="btn-group">
                                                    <button type="button"
                                                            class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                            id="SMSConfigurationDropdown" data-bs-toggle="dropdown"
                                                            aria-expanded="false"></button>
                                                    <div class="dropdown-menu dropdown-menu-end mt-1"
                                                         aria-labelledby="SMSConfigurationDropdown">
                                                        <a class="dropdown-item set" href="javascript:void(0);"
                                                           data-route="{{ route('admin.sms.set.default', $key) }}"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#setAsDefaultModal">
                                                            <i class="fa-light fa-check dropdown-item-icon text-success"></i> @lang('Set As Default')
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="setAsDefaultModal" tabindex="-1" role="dialog" aria-labelledby="setAsDefaultModalLabel"  data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="accountAddCardModalLabel"><i
                            class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>@lang("Do you want to set as default this sms method?")</p>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-white"
                                data-bs-dismiss="modal">@lang("Close")</button>
                        <button type="submit" class="btn btn-sm btn-primary">@lang("Confirm")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection

@push('script')
    <script>
        'use strict';
        $(document).on('click', '.set', function () {
            let url = $(this).data('route');
            let value = $(this).data('value');
            $('.method_value').val(value);
            $('.setRoute').attr('action', url);
        })
    </script>
@endpush



