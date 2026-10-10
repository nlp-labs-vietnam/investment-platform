@extends('admin.layouts.app')
@section('page_title',__('Create New Role'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Roles And Permissions')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="{{ route('admin.role') }}">@lang('Available Roles')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Create New Role')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@yield('page_title')</h1>
                </div>
            </div>
        </div>

        <div class="content container-fluid">
            <div class="row justify-content-lg-center">
                <div class="col-lg-12">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">

                            <div class="card-header d-flex justify-content-between">
                                <h4 class="card-title mt-2">@lang("Create New Role")</h4>
                            </div>

                            <div class="card-body mt-2">
                                <form action="{{ route('admin.roleStore') }}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="name">@lang('Role Name')</label>
                                                <input type="text" name="name"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       value="{{ old('name') }}">
                                                @error('name')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="row form-check form-switch my-4"
                                                       for="status">
                                            <span class="col-8 col-sm-9 ms-0">
                                              <span class="d-block text-dark">@lang("Status")</span>
                                              <span
                                                  class="d-block fs-5">@lang("Display the status of the Role (active or Inactive)")</span>
                                            </span>
                                                    <span class="col-4 col-sm-3 text-end">
                                                    <input type="hidden" value="0" name="status"/>
                                                    <input
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        type="checkbox" name="status"
                                                        id="status" value="1"
                                                        {{old('status') == '1' ? 'checked':''}}>
                                                </span>
                                                    @error('status')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card mb-4 card-primary ">
                                                <div class="card-header d-flex align-items-center justify-content-between">
                                                    <div class="title">
                                                        <h5>@lang('Accessibility')</h5>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <table width="100%" class="select-all-access role-parent-table">
                                                        <thead>
                                                        <tr>
                                                            <th class="p-2">@lang('Permissions Group')</th>
                                                            <th class="p-2"><input type="checkbox" class="selectAll"
                                                                                   name="accessAll" id="allowAll"> <label
                                                                    class="mb-0"
                                                                    for="allowAll">@lang('Allow All Permissions')</label>
                                                            </th>

                                                            <th class="p-2">@lang('Permission')</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(config('permissionList'))
                                                            @php
                                                                $i = 0;
                                                                $j = 500;
                                                            @endphp
                                                            @foreach(collect(config('permissionList')) as $key1 => $permission)
                                                                @php
                                                                    $i++;
                                                                @endphp
                                                                <tr class="partiallyCheckAll{{ $i }}">
                                                                    <td class="ps-2">
                                                                        <strong>
                                                                            <input
                                                                                type="checkbox"
                                                                                class="partiallySelectAll{{$i}}"
                                                                                name="partiallyAccessAll"
                                                                                id="partiallySelectAll{{$i}}"
                                                                                onclick="partiallySelectAll({{$i}})"> <label
                                                                                for="partiallySelectAll{{$i}}">@lang(str_replace('_', ' ', $key1))</label>
                                                                        </strong>
                                                                    </td>
                                                                    @if(1 == count($permission))
                                                                        <td class="border-start ps-2">
                                                                            <input type="checkbox"
                                                                                   class="cursor-pointer singlePermissionSelectAll{{$i}}"
                                                                                   id="singlePermissionSelect{{$i}}"
                                                                                   value="{{join(",",collect($permission)->collapse()['permission']['view'])}}"
                                                                                   onclick="singlePermissionSelectAll({{$i}})"
                                                                                   name="permissions[]"/>
                                                                            <label
                                                                                for="singlePermissionSelect{{$i}}">{{ str_replace('_', ' ', collect($permission)->keys()[0]) }}</label>
                                                                        </td>
                                                                        <td class="ps-2 border-start" style="width: 178px;">
                                                                            <ul class="list-unstyled">
                                                                                @if(!empty(collect($permission)->collapse()['permission']['view']))
                                                                                    <li>
                                                                                        @if(!empty(collect($permission)->collapse()['permission']['view']))
                                                                                            <input
                                                                                                type="checkbox"
                                                                                                value="{{join(",",collect($permission)->collapse()['permission']['view'])}}"
                                                                                                class="cursor-pointer"
                                                                                                name="permissions[]"/> @lang('View')
                                                                                        @endif
                                                                                    </li>
                                                                                @endif

                                                                                @if(!empty(collect($permission)->collapse()['permission']['add']))
                                                                                    <li>
                                                                                        <input type="checkbox"
                                                                                               value="{{join(",",collect($permission)->collapse()['permission']['add'])}}"
                                                                                               class="cursor-pointer"
                                                                                               name="permissions[]"/> @lang('Add')
                                                                                    </li>
                                                                                @endif

                                                                                @if(!empty(collect($permission)->collapse()['permission']['edit']))
                                                                                    <li>
                                                                                        <input type="checkbox"
                                                                                               value="{{join(",",collect($permission)->collapse()['permission']['edit'])}}"
                                                                                               class="cursor-pointer"
                                                                                               name="permissions[]"/>
                                                                                        @lang('Edit')
                                                                                    </li>
                                                                                @endif

                                                                                @if(!empty(collect($permission)->collapse()['permission']['delete']))
                                                                                    <li>
                                                                                        <input type="checkbox"
                                                                                               value="{{join(",",collect($permission)->collapse()['permission']['delete'])}}"
                                                                                               class="cursor-pointer"
                                                                                               name="permissions[]"/>
                                                                                        @lang('Delete')
                                                                                    </li>
                                                                                @endif

                                                                                @if(collect($permission)->keys()[0] == 'Shipment_List')
                                                                                    @if(!empty(collect($permission)->collapse()['permission']['dispatch']))
                                                                                        <li>
                                                                                            <input
                                                                                                type="checkbox"
                                                                                                value="{{join(",",collect($permission)->collapse()['permission']['dispatch'])}}"
                                                                                                class="cursor-pointer"
                                                                                                name="permissions[]"/>
                                                                                            @lang('Dispatch')
                                                                                        </li>
                                                                                    @endif
                                                                                @endif

                                                                                @if(collect($permission)->keys()[0] == 'Customer_List')
                                                                                    @if(!empty(collect($permission)->collapse()['permission']['show_profile']))
                                                                                        <li>
                                                                                            <input
                                                                                                type="checkbox"
                                                                                                value="{{join(",",collect($permission)->collapse()['permission']['show_profile'])}}"
                                                                                                class="cursor-pointer"
                                                                                                name="permissions[]"/>
                                                                                            @lang('Profile')
                                                                                        </li>
                                                                                    @endif

                                                                                    @if(!empty(collect($permission)->collapse()['permission']['login_as']))
                                                                                        <li>
                                                                                            <input
                                                                                                type="checkbox"
                                                                                                value="{{join(",",collect($permission)->collapse()['permission']['login_as'])}}"
                                                                                                class="cursor-pointer"
                                                                                                name="permissions[]"/>
                                                                                            @lang('Login As')
                                                                                        </li>
                                                                                    @endif
                                                                                @endif

                                                                                @if($key1 == 'User_Panel')
                                                                                    @if(!empty(collect($permission)->collapse()['permission']['send_mail']))
                                                                                        <li>
                                                                                            <input
                                                                                                type="checkbox"
                                                                                                value="{{join(",",collect($permission)->collapse()['permission']['send_mail'])}}"
                                                                                                class="cursor-pointer"
                                                                                                name="permissions[]"/>
                                                                                            @lang('Send Mail')
                                                                                        </li>
                                                                                    @endif

                                                                                    @if(!empty(collect($permission)->collapse()['permission']['login_as']))
                                                                                        <li>
                                                                                            <input
                                                                                                type="checkbox"
                                                                                                value="{{join(",",collect($permission)->collapse()['permission']['login_as'])}}"
                                                                                                class="cursor-pointer"
                                                                                                name="permissions[]"/>
                                                                                            @lang('Login As')
                                                                                        </li>
                                                                                    @endif
                                                                                @endif
                                                                            </ul>
                                                                        </td>
                                                                    @else
                                                                        <td colspan="2">
                                                                            <!-- Nested table for the second column -->
                                                                            <table class="child-table">
                                                                                @foreach($permission as $key2 => $subMenu)
                                                                                    @php
                                                                                        $j++;
                                                                                    @endphp

                                                                                    <tr class="partiallyCheckAll{{ $j }}">
                                                                                        <td class="p-2">
                                                                                            <input type="checkbox"
                                                                                                   class="cursor-pointer multiplePermissionSelectAll{{$j}}"
                                                                                                   id="multiplePermissionSelectAll{{$j}}"
                                                                                                   value="{{join(",",$subMenu['permission']['view'])}}"
                                                                                                   onclick="multiplePermissionSelectAll({{$j}})"
                                                                                                   name="permissions[]"/>
                                                                                            <label class="mb-0"
                                                                                                   for="multiplePermissionSelectAll{{$j}}">@lang(str_replace('_', ' ', $key2))</label>
                                                                                        </td>

                                                                                        <td class="ps-2 border-start  multiplePermissionCheck{{$j}}"
                                                                                            style="width: 178px;">
                                                                                            <ul class="list-unstyled py-2 mb-0">
                                                                                                @if(!empty($subMenu['permission']['view']))
                                                                                                    <li>
                                                                                                        <input
                                                                                                            type="checkbox"
                                                                                                            value="{{join(",",$subMenu['permission']['view'])}}"
                                                                                                            class="cursor-pointer"
                                                                                                            name="permissions[]"/> @lang('View')
                                                                                                    </li>
                                                                                                @endif

                                                                                                @if(!empty($subMenu['permission']['add']))
                                                                                                    <li>
                                                                                                        <input
                                                                                                            type="checkbox"
                                                                                                            value="{{join(",",$subMenu['permission']['add'])}}"
                                                                                                            class="cursor-pointer"
                                                                                                            name="permissions[]"/> @lang('Add')
                                                                                                    </li>
                                                                                                @endif

                                                                                                @if(!empty($subMenu['permission']['edit']))
                                                                                                    <li>
                                                                                                        <input
                                                                                                            type="checkbox"
                                                                                                            value="{{join(",",$subMenu['permission']['edit'])}}"
                                                                                                            class="cursor-pointer"
                                                                                                            name="permissions[]"/> @lang('Edit')
                                                                                                    </li>
                                                                                                @endif

                                                                                                @if(!empty($subMenu['permission']['delete']))
                                                                                                    <li>
                                                                                                        <input
                                                                                                            type="checkbox"
                                                                                                            value="{{join(",",$subMenu['permission']['delete'])}}"
                                                                                                            class="cursor-pointer"
                                                                                                            name="permissions[]"/> @lang('Delete')
                                                                                                    </li>
                                                                                                @endif

                                                                                                @if($key1 == 'Shipping_Rates')
                                                                                                    @if(!empty($subMenu['permission']['show']))
                                                                                                        <li>
                                                                                                            <input
                                                                                                                type="checkbox"
                                                                                                                value="{{join(",",$subMenu['permission']['show'])}}"
                                                                                                                class="cursor-pointer"
                                                                                                                name="permissions[]"/>
                                                                                                            @lang('Show')
                                                                                                        </li>
                                                                                                    @endif
                                                                                                @endif

                                                                                                @if($key1 == 'Logistic_Hub')
                                                                                                    @if(!empty($subMenu['permission']['show_profile']))
                                                                                                        <li>
                                                                                                            <input
                                                                                                                type="checkbox"
                                                                                                                value="{{join(",",$subMenu['permission']['show_profile'])}}"
                                                                                                                class="cursor-pointer"
                                                                                                                name="permissions[]"/>
                                                                                                            @lang('Profile')
                                                                                                        </li>
                                                                                                    @endif

                                                                                                    @if(!empty($subMenu['permission']['show_staff_list']))
                                                                                                        <li>
                                                                                                            <input
                                                                                                                type="checkbox"
                                                                                                                value="{{join(",",$subMenu['permission']['show_staff_list'])}}"
                                                                                                                class="cursor-pointer"
                                                                                                                name="permissions[]"/>
                                                                                                            @lang('Staff List')
                                                                                                        </li>
                                                                                                    @endif

                                                                                                    @if(!empty($subMenu['permission']['login_as']))
                                                                                                        <li>
                                                                                                            <input
                                                                                                                type="checkbox"
                                                                                                                value="{{join(",",$subMenu['permission']['login_as'])}}"
                                                                                                                class="cursor-pointer"
                                                                                                                name="permissions[]"/>
                                                                                                            @lang('Login As')
                                                                                                        </li>
                                                                                                    @endif
                                                                                                @endif

                                                                                                @if($key1 == 'Role_And_Permissions')
                                                                                                    @if(!empty($subMenu['permission']['login_as']))
                                                                                                        <li>
                                                                                                            <input
                                                                                                                type="checkbox"
                                                                                                                value="{{join(",",$subMenu['permission']['login_as'])}}"
                                                                                                                class="cursor-pointer"
                                                                                                                name="permissions[]"/>
                                                                                                            @lang('Login As')
                                                                                                        </li>
                                                                                                    @endif
                                                                                                @endif

                                                                                            </ul>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </table>
                                                                        </td>

                                                                    @endif
                                                                </tr>

                                                            @endforeach
                                                        @endif

                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="invalid-feedback d-block">
                                                    @error('permissions') @lang($message) @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-start">
                                        <button type="submit" class="btn btn-primary">@lang('Create')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('css-lib')
@endpush

@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')
    <script type="text/javascript">
        'use strict';

        function partiallySelectAll($key1) {
            if ($(`.partiallySelectAll${$key1}`).prop('checked') == true) {
                $(`.partiallyCheckAll${$key1}`).find('input[type="checkbox"]').prop('checked', true);
            } else {
                $(`.partiallyCheckAll${$key1}`).find('input[type="checkbox"]').prop('checked', false);
            }
        }

        function singlePermissionSelectAll($key) {
            if ($(`.singlePermissionSelectAll${$key}`).prop('checked') == true) {
                $(`.partiallyCheckAll${$key}`).find('input[type="checkbox"]').prop('checked', true);
            } else {
                $(`.partiallyCheckAll${$key}`).find('input[type="checkbox"]').prop('checked', false);
            }
        }

        function multiplePermissionSelectAll($key) {
            if ($(`.multiplePermissionSelectAll${$key}`).prop('checked') == true) {
                $(`.multiplePermissionCheck${$key}`).find('input[type="checkbox"]').prop('checked', true);
            } else {
                $(`.multiplePermissionCheck${$key}`).find('input[type="checkbox"]').prop('checked', false);
            }
        }

        $(document).ready(function () {
            $(document).on('click', '.selectAll', function (){
                if ($(this).is(':checked')) {
                    $(this).parents('.select-all-access').find('input[type="checkbox"]').prop('checked', true);
                } else {
                    $(this).parents('.select-all-access').find('input[type="checkbox"]').prop('checked', false);
                    $('.allAccordianShowHide').removeClass('show');
                }
            });

            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 500
            });
            new HSFileAttach('.js-file-attach');
        })
    </script>
@endpush










