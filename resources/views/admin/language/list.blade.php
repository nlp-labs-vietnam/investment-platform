@extends('admin.layouts.app')
@section('page_title', __('Language Setting'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Language Settings')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Language Settings')</h1>
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
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h2 class="card-title h4 mt-3">@lang("Languages")</h2>
                            <a href="{{ route('admin.language.create') }}"
                               class="btn btn-primary btn-sm">@lang("Add Language")
                            </a>
                        </div>
                        <div class="table-responsive position-relative">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                id="supported_currency_table">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">@lang('Name')</th>
                                    <th scope="col">@lang('Short Name')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($languages as $language)
                                    <tr>
                                        <td>@lang($language->name)</td>
                                        <td>@lang($language->short_name)</td>
                                        <td>
                                            @if($language->default_status)
                                                <span class="badge bg-soft-primary text-primary">
                                                    <span class="legend-indicator bg-primary"></span>@lang('Default')
                                                </span>
                                            @endif

                                            <span
                                                class="badge bg-soft-{{ $language->getStatusClass() }} text-{{ $language->getStatusClass() }}">
                                                <span
                                                    class="legend-indicator bg-{{ $language->getStatusClass() }}"></span>{{ $language->status ? __('Active') : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a class="btn btn-white btn-sm"
                                                   href="{{ route('admin.language.edit', $language->id) }}">
                                                    <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                                </a>
                                                <div class="btn-group">
                                                    <button type="button"
                                                            class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                            id="productsEditDropdown1" data-bs-toggle="dropdown"
                                                            aria-expanded="false"></button>

                                                    <div class="dropdown-menu dropdown-menu-end mt-1"
                                                         aria-labelledby="productsEditDropdown1">
                                                        <a href="{{ route('admin.language.keywords', $language->short_name) }}"
                                                           class="dropdown-item">
                                                            <i class="fas fa-code dropdown-item-icon"></i> @lang('Keywords')
                                                        </a>
                                                        @if(!$language->default_status)
                                                            <a href=""
                                                               class="dropdown-item status-change"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#statusChangeModal"
                                                               data-route="{{ route('admin.change.language.status', $language->id) }}"
                                                               data-status="@lang('Do you want to') {{ $language->status == 0 ? trans('activate') : trans('deactivate') }} @lang('this') `@lang($language->name)` @lang('language?')">
                                                                <i class="fa-light fa-{{ $language->status == 0 ? 'check' : 'ban' }} dropdown-item-icon"></i>
                                                                {{ $language->status == 0 ? trans('Activate') : trans('Deactivate') }}
                                                            </a>
                                                            @if($language->short_name !== 'en')
                                                                <a href="{{ route('admin.language.delete', $language->id) }}"
                                                                   type="button"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#deleteModal"
                                                                   data-route="{{ route('admin.language.delete', $language->id) }}"
                                                                   data-text="`@lang($language->name)` @lang('language')"
                                                                   class="dropdown-item deleteBtn">
                                                                    <i class="bi-trash dropdown-item-icon"></i> @lang('Delete')
                                                                </a>
                                                            @endif
                                                        @endif
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
    <!-- End Content -->

    <!-- Status Change Modal -->
    <div class="modal fade" id="statusChangeModal" tabindex="-1" role="dialog" aria-labelledby="statusChangeModalLabel" data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="accountAddCardModalLabel"><i class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <span class="status-change-text"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang("Confirm")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Status Change Modal -->

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="accountAddCardModalLabel"><i class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        @lang('Do you want to delete this') <span class="delete-text"></span> ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang("Close")</button>
                        <button type="submit" class="btn btn-primary">@lang("Confirm")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Delete Modal -->
@endsection

@push('script')
    <script>
        'use strict'
        $(document).on('click', '.deleteBtn', function () {
            let url = $(this).data('route');
            let deleteText = $(this).data('text');
            $('.delete-text').text(deleteText);
            $('.setRoute').attr('action', url);
        })

        $(document).on('click', '.status-change', function () {
            let url = $(this).data('route');
            let statusText = $(this).data('status');
            $('.status-change-text').text(statusText);
            $('.setRoute').attr('action', url);
        })

    </script>
@endpush



