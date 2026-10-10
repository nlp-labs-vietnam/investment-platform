@extends('admin.layouts.app')
@section('page_title', __('Manages Page'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Frontend')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Pages')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Pages')</h1>
                </div>
            </div>
        </div>


        <div class="alert alert-soft-dark" role="alert">
            <div class="d-sm-flex">
                <div class="flex-shrink-0">
                    <img class="avatar avatar-xl mb-2 mb-sm-0" src="{{ asset('assets/admin/img/oc-megaphone.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="avatar avatar-xl mb-2 mb-sm-0" src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                </div>

                <div class="flex-grow-1 ms-sm-4" id="control-panel-notice">
                    <h3>You're using `<span>{{stringToTitle(basicControl()->theme)}}</span>` Theme</h3>
                    In the <strong>Pages</strong> section, you can add, edit, or delete pages as needed. Additionally, you can insert customizable section blocks within pages, allowing flexible content arrangement to suit your design needs.
                    <br>
                    If you wish to change the <a href="{{route('admin.manage.theme')}}">theme</a> , please navigate to the Themes section to select or customize a new look for your site.
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-3">

                    <div class="text-start d-none">
                        <ul class="nav nav-segment " role="tablist">
                            @foreach($allTemplate as $key => $template)
                                <li class="nav-item">
                                    <a class="nav-link {{ $template == "light" ? 'active' : '' }}" id="nav-one-eg1-tab"
                                       href="#nav-{{$key}}-eg1"
                                       data-bs-toggle="pill" data-bs-target="#nav-one-eg1" role="tab"
                                       aria-controls="nav-one-eg1" aria-selected="true">
                                        @lang(ucwords($template))
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="card">
                        @if(adminAccessRoute(config('permissionList.Theme_Settings.Pages.permission.add')))
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">@lang("Pages")</h4>
                                <a href="{{ route("admin.create.page", $theme) }}"
                                   class="btn btn-primary">@lang("Create Page")</a>
                            </div>
                        @endif

                        @if(count($allPages) < 1)
                            <div class="card-body">
                                <div class="text-center p-4">
                                    <img class="dataTables-image mb-3"
                                         src="{{ asset("assets/admin/img/oc-error.svg") }}"
                                         alt="Image Description"
                                         data-hs-theme-appearance="default">
                                    <img class="dataTables-image mb-3"
                                         src="{{ asset("assets/admin/img/oc-error-light.svg") }}"
                                         alt="Image Description" data-hs-theme-appearance="dark">
                                    <p class="mb-0">@lang("No data to show")</p>
                                </div>
                            </div>
                        @endif

                        @if(count($allPages) > 0)
                            <div class="table-responsive">
                                <table class="table table-thead-bordered table-nowrap table-align-middle card-table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>@lang("Sl.")</th>
                                        <th>@lang("Name")</th>
                                        <th>@lang("Slug")</th>
                                        <th>@lang("Template")</th>
                                        <th>@lang("Created At")</th>
                                        <th>@lang("Status")</th>
                                        <th class="text-center">
                                            @foreach($allLanguage as $language)
                                                <img class="avatar avatar-xss avatar-square me-2"
                                                     src="{{ getFile($language->flag_driver, $language->flag) }}"
                                                     alt="{{ $language->name }} Flag">
                                            @endforeach
                                        </th>
                                        @if(adminAccessRoute(array_merge(config('permissionList.Theme_Settings.Pages.permission.edit'), config('permissionList.Theme_Settings.Pages.permission.delete'))))
                                            <th>@lang("Action")</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($allPages as $page)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td> @lang(ucwords(optional($page->details)->name ?? $page->name))
                                             <a href="{{route('page',$page->slug)}}" target="_blank"><i class="bi bi-link-45deg"></i></a>
                                            </td>
                                            <td><span class="badge bg-soft-secondary text-dark">{{$page->slug!='/'?'/'.$page->slug:$page->slug}}</span> </td>
                                            <td>
                                                @lang(ucfirst($page->template_name))
                                            </td>
                                            <td>@lang(dateTime($page->created_at))</td>

                                            <td>
                                                @if($page->status == 0)
                                                    <span class="badge bg-soft-warning text-warning">
                                                    <span class="legend-indicator bg-warning"></span>@lang("Unpublish")
                                                  </span>
                                                @elseif($page->status == 1)
                                                    <span class="badge bg-soft-success text-success">
                                                    <span class="legend-indicator bg-success"></span>@lang("Publish")
                                                  </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($page->type == 0)
                                                    @foreach($allLanguage as $language)
                                                        <a href="{{ route('admin.edit.page', [$page->id, $theme, $language->id]) }}"
                                                           class="btn btn-white btn-icon btn-sm flag-btn"
                                                           target="_blank">
                                                            <i class="bi {{ $page->getLanguageEditClass($language->id) }}"></i>
                                                        </a>
                                                    @endforeach
                                                @else
                                                    @foreach($allLanguage as $language)
                                                        <a href="{{ route('admin.edit.static.page', [$page->id, $theme, $language->id]) }}"
                                                           class="btn btn-white btn-icon btn-sm flag-btn"
                                                           target="_blank">
                                                            <i class="bi {{ $page->getLanguageEditClass($language->id) }}"></i>
                                                        </a>
                                                    @endforeach
                                                @endif
                                            </td>
                                            @if(adminAccessRoute(array_merge(config('permissionList.Theme_Settings.Pages.permission.edit'), config('permissionList.Theme_Settings.Pages.permission.delete'))))
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if(adminAccessRoute(config('permissionList.Theme_Settings.Pages.permission.edit')))
                                                            @if($page->type == 0)
                                                                <a class="btn btn-white btn-sm"
                                                                   href="{{ route('admin.edit.page', [$page->id, $theme, $defaultLanguage->id]) }}">
                                                                    <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                                                </a>
                                                            @else
                                                                <a class="btn btn-white btn-sm"
                                                                   href="{{ route('admin.edit.static.page', [$page->id, $theme, $defaultLanguage->id]) }}">
                                                                    <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                                                </a>
                                                            @endif
                                                        @endif

                                                        @if(adminAccessRoute(config('permissionList.Theme_Settings.Pages.permission.delete')))
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                        class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                                        id="pageEditDropdown" data-bs-toggle="dropdown"
                                                                        aria-expanded="false"></button>
                                                                <div class="dropdown-menu dropdown-menu-end mt-1"
                                                                     aria-labelledby="pageEditDropdown">
                                                                    <a class="dropdown-item"
                                                                       href="{{ route("admin.page.seo", $page->id) }}">
                                                                        <i class="fa-light fa-magnifying-glass dropdown-item-icon"></i>
                                                                        @lang("SEO")
                                                                    </a>
                                                                    @if($page->type == 0)
                                                                        <a class="dropdown-item deleteBtn text-danger"
                                                                           href="javascript:void(0)"
                                                                           data-pagename="{{ optional($page->details)->name }}"
                                                                           data-route="{{ route("admin.page.delete", $page->id) }}"
                                                                           data-bs-toggle="modal"
                                                                           data-bs-target="#deleteModal">
                                                                            <i class="bi-trash dropdown-item-icon text-danger"></i> @lang("Delete")
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <div class="text-center p-4">
                                                <img class="dataTables-image mb-3"
                                                     src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                                     alt="Image Description" data-hs-theme-appearance="default">
                                                <img class="dataTables-image mb-3"
                                                     src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                                     alt="Image Description" data-hs-theme-appearance="dark">
                                                <p class="mb-0">@lang("No data to show")</p>
                                            </div>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
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
                        <p>@lang("Do you want to delete") `<span class="page-name"></span>` @lang("Page?") </p>
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
                let page_name = $(this).data('pagename');
                $(".page-name").text(page_name);
                let route = $(this).data('route');
                $('.setRoute').attr('action', route);
            })
        })
    </script>
@endpush





