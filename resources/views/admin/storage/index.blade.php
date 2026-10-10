@extends('admin.layouts.app')
@section('page_title', __('File Storage System'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('File Storage System')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('File Storage System')</h1>
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
                            <h2 class="card-title h4">@lang('File Storage System')</h2>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                <tr>
                                    <th>@lang('Sl.')</th>
                                    <th>@lang('File Storage System')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($fileStorageMethod as $item)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar avatar-sm avatar-circle">
                                                        <img class="avatar-img" src="{{ getFile($item->driver,$item->logo) }}" alt="Image Description">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="text-inherit mb-0">@lang($item->name)</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->status == 0)
                                                <span class="badge bg-soft-danger text-danger">
                                                        <span class="legend-indicator bg-danger"></span>@lang("Inactive")
                                                  </span>
                                            @elseif($item->status == 1)
                                                <span class="badge bg-soft-success text-success">
                                                        <span class="legend-indicator bg-success"></span>@lang("Active")
                                                  </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($item->code != 'local')
                                                    <a class="btn btn-white btn-sm"
                                                       href="{{ route('admin.storage.edit', $item->id) }}">
                                                        <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                                    </a>
                                                @endif
                                                @if($item->status != 1)
                                                    <div class="btn-group">
                                                        <button type="button"
                                                                class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                                id="storageEditDropdown" data-bs-toggle="dropdown"
                                                                aria-expanded="false"></button>

                                                        <div class="dropdown-menu dropdown-menu-end mt-1"
                                                             aria-labelledby="storageEditDropdown">
                                                            <button class="dropdown-item setDefault"
                                                                    data-route="{{route('admin.storage.setDefault',$item->id)}}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#setAsDefaultModal">
                                                                <i class="bi-upload dropdown-item-icon"></i>
                                                                @lang('Set as default')
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="odd">
                                        <td valign="top" colspan="8" class="dataTables_empty">
                                            <div class="text-center p-4">
                                                <img class="mb-3 dataTables-image"
                                                     src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                                     alt="Image Description" data-hs-theme-appearance="default">
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
            </div>
        </div>
    </div>

    <!-- Set As Default Modal -->
    <div class="modal fade" id="setAsDefaultModal" tabindex="-1" role="dialog" aria-labelledby="setAsDefaultModalLabel" data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="setAsDefaultModalLabel"><i class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                <div class="modal-body">
                    <p>@lang('Do you want to set as default this file storage system?')</p>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-white" data-bs-dismiss="modal">@lang("Close")</button>
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
        "use strict";
        $(document).on('click', '.setDefault', function () {
            let url = $(this).data('route');
            $('.setRoute').attr('action', url);
        })
    </script>
@endpush


