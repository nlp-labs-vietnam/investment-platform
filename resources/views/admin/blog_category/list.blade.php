@extends('admin.layouts.app')
@section('page_title', __('Blog Category'))
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
                    <h1 class="page-header-title">@lang('Blog Category')</h1>
                </div>
            </div>
        </div>
        <div class="card">
            @if(adminAccessRoute(config('permissionList.Manage_Blog.Category.permission.add')))
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">@lang('Add Category')</h4>
                    <a href="{{ route('admin.blog-category.create') }}"
                       class="btn btn-primary">@lang('Add Category')</a>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>@lang('No.')</th>
                        <th>@lang('Name')</th>
                        @if(adminAccessRoute(array_merge(config('permissionList.Manage_Blog.Category.permission.edit'), config('permissionList.Manage_Blog.Category.permission.delete'))))
                            <th>@lang('Action')</th>
                        @endif
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($blogCategory as $key => $category)
                        <tr>
                            <td>{{ $loop->index + 1  }}</td>
                            <td>
                                @lang($category->name)
                            </td>
                            @if(adminAccessRoute(array_merge(config('permissionList.Manage_Blog.Category.permission.edit'), config('permissionList.Manage_Blog.Category.permission.delete'))))
                                <td>
                                    <div class="btn-group" role="group">
                                        @if(adminAccessRoute(config('permissionList.Manage_Blog.Category.permission.add')))
                                            <a class="btn btn-white btn-sm"
                                               href="{{ route('admin.blog-category.edit', $category->id) }}">
                                                <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                            </a>
                                        @endif
                                        @if(adminAccessRoute(config('permissionList.Manage_Blog.Category.permission.add')))
                                            <div class="btn-group">
                                                <button type="button"
                                                        class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                        id="productsEditDropdown1" data-bs-toggle="dropdown"
                                                        aria-expanded="false"></button>

                                                <div class="dropdown-menu dropdown-menu-end mt-1"
                                                     aria-labelledby="productsEditDropdown1">
                                                    <a class="dropdown-item deleteBtn text-danger"
                                                       href="javascript:void(0)"
                                                       data-route="{{ route("admin.blog-category.destroy", $category->id) }}"
                                                       data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="bi-trash dropdown-item-icon text-danger"></i> @lang("Delete")
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
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

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deleteModalLabel"><i
                            class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                    @method("delete")
                    <div class="modal-body">
                        <p>@lang("Do you want to delete this category")</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Delete Modal -->
@endsection

@push('script')
    <script>
        "use script";
        $(document).ready(function () {
            $('.deleteBtn').on('click', function () {
                let route = $(this).data('route');
                $('.setRoute').attr('action', route);
            })
        })
    </script>
@endpush



