@extends('admin.layouts.app')
@section('page_title', __('Manage Content'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Manage Content')</a></li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang(stringToTitle($content))</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang(stringToTitle($content))</h1>
                </div>
            </div>
        </div>


        @if($singleContent)
            <div>
                <ul class="nav nav-segment mb-2" role="tablist">
                    @foreach($languages as $key => $language)
                        <li class="nav-item">
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
                            <form action="{{ route('admin.content.store', [$content, $language->id]) }}" method="post"
                                  id="form_description"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="col-lg-12">
                                    <div class="card card-lg mb-3 mb-lg-4">
                                        @if ($preview)
                                            <div class="card-header d-flex justify-content-end">
                                                <a class="btn btn-white btn-sm sectionPreviewImage "
                                                   href="{{ $preview ? asset($preview):'javascript:void(0)'}}">
                                                    <i class="fa-light fa-eye"></i> @lang('Preview')
                                                </a>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            @foreach($singleContent['field_name'] as $name => $type)

                                                <div class="row justify-content-md-start">
                                                    @if($type == "text")
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label"
                                                                   for="@lang($name)">@lang(stringToTitle($name))
                                                            </label>
                                                            <input type="@lang($type)" id="@lang($name)"
                                                                   name="{{ $name }}[{{ $language->id }}]"
                                                                   class="form-control @error($name.'.'.$language->id) is-invalid @enderror"
                                                                   value="{{ old(@$name.'.'.$language->id, isset($singleContentData[$language->id]) ? @$singleContentData[$language->id][0]->description->{$name} : '') }}"
                                                                   autocomplete="off"
                                                                   placeholder="@lang(stringToTitle($name))">
                                                            @error($name.'.'.$language->id)
                                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                    @if($type == "date" && $language->default_status == 1)
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label"
                                                                   for="@lang($name)">@lang(stringToTitle($name))
                                                            </label>
                                                            <input type="text"
                                                                   class="js-flatpickr form-control flatpickr-custom @error($name.'.'.$language->id) is-invalid @enderror"
                                                                   name="{{ $name }}[{{ $language->id }}]"
                                                                   value="{{ old($name.'.'.$language->id, isset($singleContentData[$language->id]) ? $singleContentData[$language->id][0]->content->media->{$name} : '') }}"
                                                                   autocomplete="off"
                                                                   placeholder="Select dates"
                                                                   data-hs-flatpickr-options='{
                                                                     "dateFormat": "d/m/Y",
                                                                     "enableTime": false
                                                                   }'>
                                                            @error($name.'.'.$language->id)
                                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    @endif

                                                    @if($type == "textarea")
                                                        <div class="col-md-12 mb-4">
                                                            <label
                                                                class="form-label">@lang(stringToTitle2($name))</label>
                                                            <textarea
                                                                class="summernote @error($name.'.'.$language->id) is-invalid @enderror"
                                                                name="{{ $name }}[{{ $language->id }}]">{{ old($name.'.'.$language->id, isset($singleContentData[$language->id]) ? @$singleContentData[$language->id][0]->description->{$name} : '') }}</textarea>
                                                            @error($name.'.'.$language->id)
                                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    @endif

                                                    @if($type == "number" && $language->default_status == 1)
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label"
                                                                   for="@lang($name)">@lang(stringToTitle($name))</label>
                                                            <input type="@lang($type)" id="@lang($name)"
                                                                   name="{{ $name }}[{{ $language->id }}]"
                                                                   class="form-control @error($name.'.'.$language->id) is-invalid @enderror"
                                                                   value="{{ old(@$name.'.'.$language->id, isset($singleContentData[$language->id]) ? @$singleContentData[$language->id][0]->content->media->{$name} : '') }}"
                                                                   placeholder="@lang(stringToTitle($name))">
                                                            @error($name.'.'.$language->id)
                                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    @endif

                                                    @if($type == "url" && $language->default_status == 1)
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label"
                                                                   for="@lang($name)">@lang(stringToTitle($name))</label>
                                                            <input type="@lang($type)" id="@lang($name)"
                                                                   name="{{ $name }}[{{ $language->id }}]"
                                                                   value="{{ old($name.'.'.$language->id, isset($singleContentData[$language->id]) ? @$singleContentData[$language->id][0]->content->media->{$name} : '') }}"
                                                                   class="form-control @error($name.'.'.$language->id) is-invalid @enderror"
                                                                   placeholder="@lang(stringToTitle($name))">
                                                            @error($name.'.'.$language->id)
                                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    @endif

                                                    @if($type == "file" && $language->default_status == 1)
                                                        @if($name == 'image')
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label"
                                                                       for="@lang($name)">@lang(stringToTitle($name))</label>
                                                                <label class="form-check form-check-dashed"
                                                                       for="logoUploader{{$name}}" id="content_img">
                                                                    <img id="contentImg{{$name}}"
                                                                         class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                                         src="{{ getFile((!$singleContentData->isEmpty())?  @$singleContentData[$language->id][0]->content->media->{$name}->driver : '', (!$singleContentData->isEmpty())? @$singleContentData[$language->id][0]->content->media->{$name}->path:'', true) }}"
                                                                         alt="Image Description"
                                                                         data-hs-theme-appearance="default">
                                                                    <img id="contentImg{{$name}}"
                                                                         class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                                         src="{{ getFile(!$singleContentData->isEmpty()  ? @$singleContentData[$language->id][0]->content->media->{$name}->driver: '', (!$singleContentData->isEmpty())? @$singleContentData[$language->id][0]->content->media->{$name}->path:'', true) }}"
                                                                         alt="Image Description"
                                                                         data-hs-theme-appearance="dark">
                                                                    <span
                                                                        class="d-block">@lang("Browse your file here")</span>
                                                                    <input type="file" name="{{ $name }}"
                                                                           class="js-file-attach form-check-input"
                                                                           id="logoUploader{{$name}}"
                                                                           data-hs-file-attach-options='{
                                                                      "textTarget": "#contentImg{{$name}}",
                                                                      "mode": "image",
                                                                      "targetAttr": "src",
                                                                      "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                                                   }'>
                                                                </label>
                                                                @error($name.'.'.$language->id)
                                                                <span
                                                                    class="invalid-feedback d-block">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        @endif

                                                        @if($name == 'image2')
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label"
                                                                       for="@lang($name)">@lang(stringToTitle($name))</label>
                                                                <label class="form-check form-check-dashed"
                                                                       for="logoUploader{{$name}}" id="content_img">
                                                                    <img id="contentImg{{$name}}"
                                                                         class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                                         src="{{ getFile((!$singleContentData->isEmpty())?  @$singleContentData[$language->id][0]->content->media->{$name}->driver : '', (!$singleContentData->isEmpty())? @$singleContentData[$language->id][0]->content->media->{$name}->path:'', true) }}"
                                                                         alt="Image Description"
                                                                         data-hs-theme-appearance="default">
                                                                    <img id="contentImg{{$name}}"
                                                                         class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                                         src="{{ getFile(!$singleContentData->isEmpty()  ? @$singleContentData[$language->id][0]->content->media->{$name}->driver: '', (!$singleContentData->isEmpty())? @$singleContentData[$language->id][0]->content->media->{$name}->path:'', true) }}"
                                                                         alt="Image Description"
                                                                         data-hs-theme-appearance="dark">
                                                                    <span
                                                                        class="d-block">@lang("Browse your file here")</span>
                                                                    <input type="file" name="{{ $name }}"
                                                                           class="js-file-attach form-check-input"
                                                                           id="logoUploader{{$name}}"
                                                                           data-hs-file-attach-options='{
                                                                      "textTarget": "#contentImg{{$name}}",
                                                                      "mode": "image",
                                                                      "targetAttr": "src",
                                                                      "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                                                   }'>
                                                                </label>
                                                                @error($name.'.'.$language->id)
                                                                <span
                                                                    class="invalid-feedback d-block">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        @endif

                                                        @if($name == 'image3')
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label"
                                                                       for="@lang($name)">@lang(stringToTitle($name))</label>
                                                                <label class="form-check form-check-dashed"
                                                                       for="logoUploader{{$name}}" id="content_img">
                                                                    <img id="contentImg{{$name}}"
                                                                         class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                                         src="{{ getFile((!$singleContentData->isEmpty())?  @$singleContentData[$language->id][0]->content->media->{$name}->driver : '', (!$singleContentData->isEmpty())? @$singleContentData[$language->id][0]->content->media->{$name}->path:'', true) }}"
                                                                         alt="Image Description"
                                                                         data-hs-theme-appearance="default">
                                                                    <img id="contentImg{{$name}}"
                                                                         class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                                         src="{{ getFile(!$singleContentData->isEmpty()  ? @$singleContentData[$language->id][0]->content->media->{$name}->driver: '', (!$singleContentData->isEmpty())? @$singleContentData[$language->id][0]->content->media->{$name}->path:'', true) }}"
                                                                         alt="Image Description"
                                                                         data-hs-theme-appearance="dark">
                                                                    <span
                                                                        class="d-block">@lang("Browse your file here")</span>
                                                                    <input type="file" name="{{ $name }}"
                                                                           class="js-file-attach form-check-input"
                                                                           id="logoUploader{{$name}}"
                                                                           data-hs-file-attach-options='{
                                                                      "textTarget": "#contentImg{{$name}}",
                                                                      "mode": "image",
                                                                      "targetAttr": "src",
                                                                      "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                                                   }'>
                                                                </label>
                                                                @error($name.'.'.$language->id)
                                                                <span
                                                                    class="invalid-feedback d-block">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        @endif

                                                        @if($name == 'thumb_image')
                                                            <div class="col-md-4">
                                                                <label class="form-label"
                                                                       for="@lang($name)">@lang(stringToTitle($name))</label>
                                                                <label class="form-check form-check-dashed"
                                                                       for="logoUploader" id="content_img">
                                                                    <img id="contentImg"
                                                                         class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                                         src="{{ getFile((!$singleContentData->isEmpty())?  @$singleContentData[$language->id][0]->content->media->{$name}->driver : '', (!$singleContentData->isEmpty())? @$singleContentData[$language->id][0]->content->media->{$name}->path:'', true) }}"
                                                                         alt="Image Description"
                                                                         data-hs-theme-appearance="default">
                                                                    <img id="contentImg"
                                                                         class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                                         src="{{ getFile(!$singleContentData->isEmpty()  ? @$singleContentData[$language->id][0]->content->media->{$name}->driver: '', (!$singleContentData->isEmpty())? @$singleContentData[$language->id][0]->content->media->{$name}->path:'', true) }}"
                                                                         alt="Image Description"
                                                                         data-hs-theme-appearance="dark">
                                                                    <span
                                                                        class="d-block">@lang("Browse your file here")</span>
                                                                    <input type="file" name="{{ $name }}"
                                                                           class="js-file-attach form-check-input"
                                                                           id="logoUploader"
                                                                           data-hs-file-attach-options='{
                                                                      "textTarget": "#contentImg",
                                                                      "mode": "image",
                                                                      "targetAttr": "src",
                                                                      "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                                                   }'>
                                                                </label>
                                                                @error($name.'.'.$language->id)
                                                                <span
                                                                    class="invalid-feedback d-block">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        @endif

                                                    @endif
                                                </div>
                                            @endforeach
                                            <div class="d-flex justify-content-start align-items-center mt-3">
                                                <button type="submit"
                                                        class="btn btn-primary">@lang('Save changes')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(($multipleContents && !$singleContent) || ($multipleContents && $singleContent))
            <div class="card">
                <div class="card-header card-header-content-md-between">
                    <div class="mb-2 mb-md-0">
                        <input id="datatableSearch" type="search" class="form-control"
                               placeholder="@lang("Search here")" aria-label="@lang("Search here")">
                    </div>
                    <div class="d-grid d-sm-flex justify-content-sm-end align-items-sm-center gap-2">
                        <a href="{{ route('admin.manage.content.multiple', $content) }}"
                           class="btn btn-primary btn-sm mb-2 mb-sm-0 me-2" href="javascript:void(0)">
                            {{ __('Add '. stringToTitle($content)) }}
                        </a>

                        @if ($preview && !$singleContent)
                            <a class="btn btn-white btn-sm sectionPreviewImage "
                               href="{{$preview ? asset($preview):'javascript:void(0)'}}">
                                <i class="fa-light fa-eye"></i> @lang('Preview')
                            </a>
                        @endif

                    </div>
                </div>

                <div class="table-responsive datatable-custom">
                    <table id="datatable"
                           class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                           data-hs-datatables-options='{
                           "columnDefs": [{
                              "targets": [],
                              "orderable": false
                            }],
                           "order": [],
                           "ordering":false,
                           "info": {
                             "totalQty": "#datatableWithPaginationInfoTotalQty"
                           },
                           "search": "#datatableSearch",
                           "entries": "#datatableEntries",
                           "pageLength": 10,
                           "isResponsive": false,
                           "isShowPaging": false,
                           "pagination": "datatablePagination"
                         }'>
                        <thead class="thead-light">
                        <tr>
                            <th>@lang('Sl')</th>
                            <th> {{ array_key_first($multipleContents['field_name'] ?? []) }} </th>
                            <th>@lang('Actions')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($multipleContentData as $key => $data)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td> {{ collect($data->description)->first() }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-white btn-sm"
                                           href="{{ route('admin.content.item.edit', [$content, $data->content_id]) }}">
                                            <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                        </a>
                                        <div class="btn-group">
                                            <button type="button"
                                                    class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                    id="contentEditDropdown" data-bs-toggle="dropdown"
                                                    aria-expanded="false"></button>
                                            <div class="dropdown-menu dropdown-menu-end mt-1"
                                                 aria-labelledby="contentEditDropdown">
                                                <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                                                   data-route="{{ route('admin.content.item.delete', $data->content_id) }}"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#contentDeleteModal">
                                                    <i class="bi-trash dropdown-item-icon"></i> @lang('Delete')
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>


                @if(count($multipleContentData) > 10)
                    <div class="card-footer">
                        <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                            <div class="col-sm mb-2 mb-sm-0">
                                <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                                    <span class="me-2">@lang('Showing'):</span>
                                    <div class="tom-select-custom">
                                        <select id="datatableEntries"
                                                class="js-select form-select form-select-borderless w-auto"
                                                autocomplete="off" data-hs-tom-select-options='{
                                            "searchInDropdown": false,
                                            "hideSearch": true
                                          }'>
                                            <option value="5" selected>5</option>
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="20">20</option>
                                        </select>
                                    </div>
                                    <span class="text-secondary me-2">@lang('of')</span>
                                    <span id="datatableWithPaginationInfoTotalQty"></span>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="d-flex justify-content-center justify-content-sm-end">
                                    <nav id="datatablePagination" aria-label="Activity pagination"></nav>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="modal fade" id="contentDeleteModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
         aria-labelledby="contentDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="contentDeleteModalLabel"><i
                            class="fa-sharp fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        @lang('Do you want to delete this content item?')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{asset('assets/global/css/magnific-popup.css')}}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}" defer></script>
    <script src="{{asset('assets/global/js/jquery.magnific-popup.js')}}"></script>
@endpush

@push('script')
    <script defer>
        'use strict';
        $(document).ready(function () {
            $('.sectionPreviewImage').magnificPopup({type: 'image'});
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSFlatpickr.init('.js-flatpickr')

            HSCore.components.HSDatatables.init($('#datatable'), {
                language: {
                    zeroRecords: `
                        <div class="text-center p-4">
                          <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                          <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                            <p class="mb-0">No data to show</p>
                        </div>`
                }
            });

            $('.deleteBtn').on('click', function () {
                let route = $(this).data('route')
                $('.setRoute').attr('action', route);
            });

            $('.summernote').summernote({
                height: 200,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                }
            });
        });
    </script>
@endpush






