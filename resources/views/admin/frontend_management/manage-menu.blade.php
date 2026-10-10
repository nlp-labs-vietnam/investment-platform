@extends('admin.layouts.app')
@section('page_title', __('Manage Menu'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Frontend')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manage Menu')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Manage Menu')</h1>
                </div>
            </div>
        </div>

        <div class="shadow p-3 mb-5 alert-soft-blue mb-4 mb-lg-7" role="alert">
            <div class="alert-box d-flex flex-wrap align-items-center">
                <div class="flex-shrink-0">
                    <img class="avatar avatar-xl"
                         src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="default">
                    <img class="avatar avatar-xl"
                         src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="dark">
                </div>

                <div class="flex-grow-1 ms-3">
                    <h3 class="alert-heading mb-1 text-info">@lang("Attention!")</h3>
                    <div class="d-flex align-items-center">
                        <p class="mb-0 text-info"> @lang('To display menu items on the website, drag and drop them from the left to your desired position on the right, then click "Save Changes"')</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <h3 class="ms-1 ">@lang("Manage Menu For Header")</h3>
            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal"
                    data-bs-target="#addCustomLinkModal">
                @lang('Add Custom Links')
            </button>
        </div>
        <div class="row" id="manage-menu">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title h4">@lang('Pages')</h2>
                    </div>
                    <div class="card-body bg-soft-secondary">
                        <div class="row">
                            @foreach($pages as $key => $page)
                                <div
                                    class="js-sortable sortablejs-custom list-group drop-pages listed_arr col-md-6"
                                    data-hs-sortable-options='{
                                       "animation": 150,
                                       "group": {
                                         "name": "MenuSorting",
                                         "pull": "clone",
                                         "put": false
                                       }
                                     }'>
                                    <div class="list-group-item mb-2">
                                        <div class="d-flex justify-content-between align-items-center drop-content">
                                            <span> @lang(ucwords(optional($page->details)->name ?? $page->name)) </span>
                                            <i class="fa-light"></i>
                                            @if($page->type == 3)
                                                <div class="dropdown">
                                                    <button type="button"
                                                            class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle"
                                                            id="contentActivityStreamDropdown" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                        <i class="bi-three-dots-vertical"></i>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-end mt-1"
                                                         aria-labelledby="contentActivityStreamDropdown" style="">
                                                        <a class="dropdown-item edit_custom_btn" data-bs-toggle="modal"
                                                           data-bs-target="#editCustomLinkModal"
                                                           data-route="{{ route("admin.update.custom.link", $page->id) }}"
                                                           data-name="{{ optional($page->details)->name }}"
                                                           data-link="{{ $page->custom_link }}"
                                                           data-pageid="{{$page->id}}">
                                                            <i class="bi bi-pencil-square dropdown-item-icon"></i>
                                                            Edit
                                                        </a>
                                                        <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                                                           data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                           data-route="{{ route('admin.delete.custom.link', $page->id) }}">
                                                            <i class="bi bi-trash dropdown-item-icon"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <input type="hidden" name="menu_item[]"
                                               value="{{ $page->name }}">
                                        <div class="js-sortable list-group nested-list d-none"
                                             data-hs-sortable-options='{
                                        "animation": 150,
                                        "group": "MenuSorting"
                                     }'>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title h4">@lang('Manage Menu')</h2>
                    </div>
                    <div class="card-body bg-soft-primary">
                        <form action="{{ route('admin.header.menu.item.store') }}" method="post" id="header-form">
                            @csrf
                            <div class="js-sortable list-group manage-menu-drop"
                                 data-hs-sortable-options='{
                                  "animation": 150,
                                  "group": "MenuSorting",
                                  "fallbackOnBody": true
                                }'>
                                @if($headerMenus->menu_items)
                                    @foreach ($headerMenus->menu_items as $key => $value)
                                        <div class="list-group-item mb-2" draggable="false" style="">
                                            <div class="d-flex justify-content-between drop-content">
                                                <span>{{ getPageName(is_numeric($key) ? $value : $key) }}</span>
                                                <i class="fa-light fa-xmark text-danger remove-icon"></i>
                                            </div>
                                            <input type="hidden" value="{{ is_numeric($key) ? $value : $key }}"
                                                   name="menu_item[{{ is_numeric($key) ? $value : $key }}]">
                                            <div class="js-sortable list-group nested-list" data-hs-sortable-options='{
                                                 "animation": 150,
                                                 "group": "MenuSorting"
                                            }'>
                                                @if (is_array($value))
                                                    @include('admin.frontend_management.components.header_menu', ['menuArray' => $value])
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button"
                                    class="btn btn-primary header-form-btn w-100">@lang('Save Changes')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-lg-5" id="manage-menu">
            <h3 class="ms-1">@lang("Manage Menu For Footer")</h3>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title h4">@lang('Pages')</h2>
                    </div>
                    <div class="card-body bg-soft-secondary">
                        <div class="row">
                            @foreach($pages as $key => $page)
                                <div class="col-md-6 js-sortable sortablejs-custom list-group manage-menu-drop-footer"
                                     data-hs-sortable-options='{
                                           "animation": 150,
                                           "group": "listGroup"
                                         }'>
                                    <div
                                        class="list-group-item mb-2 d-flex justify-content-between align-items-center">
                                        <span>@lang(ucwords(optional($page->details)->name ?? $page->name))</span>
                                        <i class="fa-light"></i>
                                        @if($page->type == 3)
                                            <div class="dropdown">
                                                <button type="button"
                                                        class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle"
                                                        id="contentActivityStreamDropdown" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                    <i class="bi-three-dots-vertical"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-end mt-1"
                                                     aria-labelledby="contentActivityStreamDropdown" style="">
                                                    <a class="dropdown-item edit_custom_btn" data-bs-toggle="modal"
                                                       data-bs-target="#editCustomLinkModal"
                                                       data-route="{{ route("admin.update.custom.link", $page->id) }}"
                                                       data-name="{{ optional($page->details)->name }}"
                                                       data-link="{{ $page->custom_link }}" data-pageid="{{$page->id}}">
                                                        <i class="bi bi-pencil-square dropdown-item-icon"></i>
                                                        Edit
                                                    </a>
                                                    <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                                                       data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                       data-route="{{ route('admin.delete.custom.link', $page->id) }}">
                                                        <i class="bi bi-trash dropdown-item-icon"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <input type="hidden" name="menu_item[]"
                                               value="{{ strtolower($page->name) }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <form action="{{ route('admin.footer.menu.item.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-3" id="usefulLInk">
                                <div class="card-header">
                                    <h2 class="card-title h4">
                                        @lang('Useful Links')
                                    </h2>
                                </div>
                                <div class="card-body bg-soft-primary mb-3">
                                    <div class="col-md-12">
                                        <div class="js-sortable sortablejs-custom list-group useful-link"
                                             data-hs-sortable-options='{
                                                       "animation": 150,
                                                       "group": "listGroup"
                                                     }'>
                                            @if($footerMenus->menu_items)
                                                @foreach($footerMenus->menu_items as $key => $footerMenu)
                                                    @if($key == "useful_link")
                                                        @foreach($footerMenu as  $menu)
                                                            <div
                                                                class="list-group-item mb-2 d-flex justify-content-between">
                                                                <span>@lang(getPageName($menu))</span>
                                                                <i class="fa-light fa-xmark text-danger remove-icon-footer"></i>
                                                                <input type="hidden" name="menu_item[useful_link][]"
                                                                       value="{{ $menu }}">
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card mb-3" id="supportLInk">
                                <div class="card-header">
                                    <h2 class="card-title h4">
                                        @lang('Support Link')
                                    </h2>
                                </div>
                                <div class="card-body bg-soft-primary ">
                                    <div class="js-sortable sortablejs-custom list-group support-link"
                                         data-hs-sortable-options='{
                                           "animation": 150,
                                           "group": "listGroup"
                                         }'>
                                        @if($footerMenus->menu_items)
                                            @foreach($footerMenus->menu_items as $key => $footerMenu)
                                                @if($key == "support_link")
                                                    @foreach($footerMenu as  $menu)
                                                        <div
                                                            class="list-group-item mb-2 d-flex justify-content-between">
                                                            <span>@lang(getPageName($menu))</span>
                                                            <i class="fa-light fa-xmark text-danger remove-icon-footer"></i>
                                                            <input type="hidden" name="menu_item[support_link][]"
                                                                   value="{{ $menu }}">
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary w-100">@lang('Save Changes')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create a Custom link  Modal -->
    <div class="modal fade" id="addCustomLinkModal" data-bs-backdrop="static" tabindex="-1"
         aria-labelledby="addCustomLinkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addCustomLinkModalLabel"><i class="bi bi-box-arrow-up-right"></i> Custom
                        Links</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route("admin.add.custom.link") }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <input type="text" class="form-control" id="linkText" name="link_text"
                                   placeholder="Button Name" autocomplete="off"
                                   aria-label="Button Name">
                            @error("link_text")
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control" id="link" name="link" placeholder="https://"
                                   autocomplete="off"
                                   aria-label="https://">
                            @error("link")
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Links</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Create a Custom link Modal -->


    <!-- Edit a Custom Link Modal -->
    <div class="modal fade" id="editCustomLinkModal" data-bs-backdrop="static" tabindex="-1"
         aria-labelledby="editCustomLinkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editCustomLinkModalLabel"><i class="bi bi-box-arrow-up-right"></i>
                        Custom
                        Links</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">
                    <div class="edit-section-tab">
                        <ul class="nav nav-segment mb-2" role="tablist">
                            @foreach($languages as $key => $language)
                                <li class="nav-item edit-nav-item" data-language="{{$language->id}}">
                                    <a class="nav-link @error('errActive') @if($language->id == $message) active @endif @else @if($loop->first) active @endif  @enderror"
                                       id="nav-one-eg1-tab"
                                       href="#nav-one-{{ $key }}"
                                       data-bs-toggle="pill"
                                       data-bs-target="#nav-one-{{ $key }}"
                                       role="tab" aria-controls="nav-one-{{ $key }}"
                                       aria-selected="@error('errActive') @if($language->id == $message) true @else false @endif @else @if($loop->first) true @else false @endif  @enderror">
                                        @lang($language->name)
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-content">
                        @foreach($languages as $key => $language)
                            <div
                                class="tab-pane fade @error('errActive') @if($language->id == $message) show active @endif @else @if($loop->first) show active @endif  @enderror"
                                id="nav-one-{{ $key }}"
                                role="tabpanel" aria-labelledby="nav-one-{{ $key }}-tab">
                                <div class="row justify-content-lg-center">
                                    <form action="" method="post" class="editSetRoute"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="language_id" value="{{ $language->id }}">
                                        <div class="col-lg-12">
                                            <div class="mb-4">
                                                <input type="text" class="form-control editLinkText"
                                                       name="link_text[{{ $language->id }}]"
                                                       placeholder="Link Text"
                                                       value="{{ old('link_text'.'.'.$language->id) }}"
                                                       aria-label="Link Text">
                                                @error('link_text'.'.'.$language->id)
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" class="form-control editLink"
                                                       name="link[{{ $language->id }}]"
                                                       placeholder="https://"
                                                       value="{{ old('link'.'.'.$language->id) }}"
                                                       aria-label="https://">
                                                @error('link'.'.'.$language->id)
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="d-flex justify-content-end align-items-center mt-3">
                                                <button type="button" class="btn btn-white me-2"
                                                        data-bs-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">@lang('Edit Link')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Custom Link Modal -->

    <!-- Delete Custom link Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         data-bs-backdrop="static"
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
                    @method('delete')
                    <div class="modal-body">
                        @lang('Do you want to delete this custom link data?')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang("Close")</button>
                        <button type="submit" class="btn btn-primary">@lang("Confirm")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Delete Custom link Modal -->

@endsection
@push('js-lib')
    <script src="{{ asset('assets/admin/js/sortable.min.js') }}"></script>
@endpush
@push('script')
    <script>
        'use strict';

        @error('errorMessage')
        let addCustomLinkModal = new bootstrap.Modal(document.getElementById("addCustomLinkModal"), {});
        document.onreadystatechange = function () {
            addCustomLinkModal.show();
        };
        @enderror

        @error('errActive')
        let editCustomLinkModal = new bootstrap.Modal(document.getElementById("editCustomLinkModal"), {});
        document.onreadystatechange = function () {
            editCustomLinkModal.show();
        };
        let pageId = sessionStorage.getItem("pageid");
        $('.edit-nav-item').attr('data-pageid', pageId);
        @enderror

        $(document).on('click', '.edit_custom_btn', function () {
            let url = $(this).data('route');
            $('.editSetRoute').attr('action', url);
        })

        $(document).on('click', '.edit_custom_btn', function () {
            let pageId = $(this).data('pageid');
            sessionStorage.setItem("pageid", pageId);
            $('.edit-nav-item').attr('data-pageid', pageId);
            $('.editLinkText').val($(this).data('name'));
            $('.editLink').val($(this).data('link'));
        })

        $(document).on('click', '.edit-nav-item', function () {
            const languageId = $(this).data('language');
            const pageId = $(this).data('pageid');

            $.ajax({
                url: '{{ route('admin.get.custom.link') }}',
                type: 'GET',
                data: {
                    pageId: pageId,
                    languageId: languageId
                },
                success: function (response) {
                    $('.editLinkText').val(response.name);
                    $('.editLink').val(response.custom_link);
                },
                error: function (xhr, status, error) {
                    // Handle errors
                }
            });
        })

        $(document).on('click', '.deleteBtn', function () {
            let url = $(this).data('route');
            $('.setRoute').attr('action', url);
        })

        $(document).on('change', '.drop-pages, .manage-menu-drop', function (event, ui) {
            $('.manage-menu-drop').find('.fa-light').addClass('fa-xmark text-danger remove-icon');
            $('.nested-list').removeClass('d-none');
            $('.manage-menu-drop').find('.dropdown').addClass('d-none');
        });

        $(document).on('click', '.header-form-btn', function (e) {
            var $manageMenuDrop = $('.manage-menu-drop');
            updateInputNames($manageMenuDrop);
            $('#header-form').submit();
        });

        function updateInputNames($list) {
            $list.find('.list-group-item').each(function () {
                var $input = $(this).find('input');
                var itemName = $input.val();
                var parentNames = getParentNames($(this));

                var $nestedList = $(this).find('.nested-list').find('.list-group-item');
                if ($nestedList.length <= 3) {
                    if ($nestedList.length <= 0) {
                        var name = 'menu_item' + (parentNames.length > 0 ? '[' + parentNames.join('][') + '][]' : '[]');
                        $input.attr('name', name);
                    } else {
                        $input.removeAttr('name');
                    }
                }
                if ($nestedList.length)
                    updateInputNames($nestedList);

            });
        }

        function getParentNames($item) {
            var parentNames = [];
            $item.parentsUntil('.manage-menu-drop', '.list-group-item').each(function () {
                parentNames.unshift($(this).find('input').val());
            });
            return parentNames;
        }


        $(document).on('change', '.manage-menu-drop-footer', function () {
            $('.manage-menu-drop-footer').find('button').addClass('d-none');
            let $nestedUsefulLink = $('.useful-link')
            $nestedUsefulLink.find('.fa-light').addClass('fa-xmark text-danger remove-icon');
            let $nestedElementsUseFul = $nestedUsefulLink.find('.list-group-item input');
            $nestedElementsUseFul.each(function () {
                let newName = "menu_item[useful_link][]";
                $(this).attr('name', newName);
            });

            let $nestedListSupport = $('.support-link')
            $nestedListSupport.find('.fa-light').addClass('fa-xmark text-danger remove-icon');
            $nestedListSupport.find('.fa-light').addClass('fa-xmark text-danger remove-icon');
            let $nestedElementsSupport = $nestedListSupport.find('.list-group-item input');
            $nestedElementsSupport.each(function () {
                let newName = "menu_item[support_link][]";
                $(this).attr('name', newName);
            });

            let $nestedListFooterBottomLink = $('.footer-bottom-link')
            $nestedListFooterBottomLink.find('.fa-light').addClass('fa-xmark text-danger remove-icon');
            $nestedListFooterBottomLink.find('.fa-light').addClass('fa-xmark text-danger remove-icon');
            let $nestedElementsBootomLink = $nestedListFooterBottomLink.find('.list-group-item input');
            $nestedElementsBootomLink.each(function () {
                let newName = "menu_item[footer_bottom_link][]";
                $(this).attr('name', newName);
            });
        });

        $(document).on('click', ".remove-icon", function () {
            $(this).parent('.drop-content').parent('.list-group-item').remove();
        });

        $(document).on('click', ".remove-icon-footer", function () {
            $(this).parent('.list-group-item').remove();
        });

        (function () {
            HSCore.components.HSSortable.init('.js-sortable')
        })();

    </script>
@endpush





