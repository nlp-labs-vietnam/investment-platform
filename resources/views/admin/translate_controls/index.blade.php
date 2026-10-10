@extends('admin.layouts.app')
@section('page_title', __('Translate Controls'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="javascript:void(0);">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang('Translate API Configuration')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Translate API Configuration')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>
            <div class="col-lg-9">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Translate API Configuration')</h2>
                        </div>
                        <div class="table-responsive position-relative">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                id="supported_currency_table">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">@lang('Sl')</th>
                                    <th scope="col">@lang('Translate Method')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($translateMethod as $key => $method)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>@lang(snake2Title($key))</td>
                                        <td>
                                            @if($activeMethod == $key)
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
                                                <a class="btn btn-white btn-sm"
                                                   href="{{ route('admin.translate.api.config.edit', $key) }}">
                                                    <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                                </a>
                                                <div class="btn-group">
                                                    <button type="button"
                                                            class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                            id="productsEditDropdown1" data-bs-toggle="dropdown"
                                                            aria-expanded="false"></button>
                                                    <div class="dropdown-menu dropdown-menu-end mt-1"
                                                         aria-labelledby="productsEditDropdown1">
                                                        <a class="dropdown-item set" href="#"
                                                           data-route="{{ route('admin.translate.set.default', $key) }}"
                                                           data-bs-toggle="modal" data-bs-target="#confirmModal">
                                                            <i class="fa-light fa-check dropdown-item-icon text-success"></i> @lang('Set As Default')
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <div class="text-center p-4">
                                        <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                                        <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                                        <p class="mb-0">@lang("No data to show")</p>
                                    </div>
                                </tr>
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
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="accountAddCardModalLabel"><i
                            class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                    <div class="modal-body">
                        @lang('Do you want to set as default translate method ?')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-white"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-sm btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection

@push('script')
    <script>
        'use strict'
        $(document).on('click', '.set', function () {
            let url = $(this).data('route');
            let value = $(this).data('value');
            $('.method_value').val(value);
            $('.setRoute').attr('action', url);
        })
    </script>
@endpush



