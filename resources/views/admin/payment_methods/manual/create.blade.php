@extends('admin.layouts.app')
@section('page_title', __('Add Manual Gateway'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manual Gateway')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Add Manual Gateway')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Add Manual Gateway')</h1>
                </div>
            </div>
        </div>

        <div class="row payment_method">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h2 class="card-title h4">@lang('Add Manual Gateway')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.deposit.manual.store')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel" placeholder="@lang("Name")"
                                               aria-label="@lang("Name")"
                                               autocomplete="off"
                                               value="{{ old('name') }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="row form-check form-switch mt-3" for="manual_status">
                                        <span class="col-4 col-sm-9 ms-0 ">
                                          <span class="d-block text-dark">@lang("Manual Gateway Status")</span>
                                          <span
                                              class="d-block fs-5">@lang("Enable manual gateway status as active for the transaction.")</span>
                                        </span>
                                            <span class="col-2 col-sm-3 text-end">
                                         <input type='hidden' value='0' name='status'>
                                            <input class="form-check-input @error('status') is-invalid @enderror"
                                                   type="checkbox" name="status" id="ManualStatusSwitch"
                                                   value="1">
                                            <label class="form-check-label text-center"
                                                   for="ManualStatusSwitch"></label>
                                        </span>
                                            @error('manual_status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-5">
                                        <label class="form-label" for="descriptionArea">@lang("Description")</label>
                                        <textarea id="descriptionArea" class="form-control" name="description" placeholder="Description">{{ old('description') }}</textarea>
                                        <span class="invalid-feedback d-block">
                                            @error('description') @lang($message) @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-md-3">
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset('assets/admin/img/oc-browse-file.svg') }}"
                                                 alt="Image Description" data-hs-theme-appearance="default">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset('assets/admin/img/oc-browse-file-light.svg') }}"
                                                 alt="Image Description" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input" name="image"
                                                   id="logoUploader" data-hs-file-attach-options='{
                                                      "textTarget": "#logoImg",
                                                      "mode": "image",
                                                      "targetAttr": "src",
                                                      "allowTypes": [".png", ".jpeg", ".jpg"]
                                                   }'>
                                        </label>
                                        @error("image")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-9">
                                        <label for="noteLabel" class="form-label">@lang('Payment Description')</label>
                                        <textarea id="summernote" id="noteLabel" name="note">{{ old("note") }}</textarea>
                                        @error('note')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="js-add-field card mb-3 mb-lg-5">
                                    <div class="card-header card-header-content-sm-between">
                                        <h4 class="card-header-title mb-2 mb-sm-0">@lang("Payment Information")</h4>
                                        <div class="d-sm-flex align-items-center gap-2">
                                            <a class="js-create-field btn btn-outline-info btn-sm add_field_btn"
                                               href="javascript:void(0);">
                                                <i class="bi-plus"></i> @lang("Add Field")
                                            </a>
                                        </div>
                                    </div>

                                    <div class="table-responsive datatable-custom dynamic-feild-table">
                                        <table id="datatable"
                                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table overflow-visible">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>@lang("Field Name")</th>
                                                <th>@lang("Input Type")</th>
                                                <th>@lang("Validation Type")</th>
                                                <th></th>
                                            </tr>
                                            </thead>

                                            <tbody id="addFieldContainer">

                                            <tr>
                                                <td>
                                                    <input type="text" name="field_name[]" class="form-control"
                                                           value="{{ old('field_name.0') }}"
                                                           placeholder="@lang("Field Name")" autocomplete="off">
                                                    @error("field_name.0")
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </td>

                                                <td>
                                                    <div class="tom-select-custom">
                                                        <select class="js-select form-select"
                                                                name="input_type[]"
                                                                data-hs-tom-select-options='{
                                                                "searchInDropdown": false,
                                                                "hideSearch": true
                                                              }'>
                                                            <option
                                                                value="text" {{ old('input_type.0') == 'text' ? 'selected' : '' }}>@lang('Text')</option>
                                                            <option
                                                                value="textarea" {{ old('input_type.0') == 'textarea' ? 'selected' : '' }}>@lang('Textarea')</option>
                                                            <option
                                                                value="file" {{ old('input_type.0') == 'file' ? 'selected' : '' }}>@lang('File')</option>
                                                            <option
                                                                value="number" {{ old('input_type.0') == 'number' ? 'selected' : '' }}>@lang('Number')</option>
                                                            <option
                                                                value="date" {{ old('input_type.0') == 'date' ? 'selected' : '' }}>@lang('Date')</option>
                                                        </select>
                                                        @error("input_type.0")
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="tom-select-custom">
                                                        <select class="js-select form-select"
                                                                name="is_required[]"
                                                                data-hs-tom-select-options='{
                                                                        "searchInDropdown": false,
                                                                        "hideSearch": true
                                                                      }'>
                                                            <option
                                                                value="required" {{ old('is_required.0') == 'required' ? 'selected' : '' }}>@lang('Required')</option>
                                                            <option
                                                                value="optional" {{ old('is_required.0') == 'optional' ? 'selected' : '' }}>@lang('Optional')</option>
                                                        </select>
                                                        @error("is_required.0")
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </td>
                                                <td class="table-column-ps-0">
                                                    <button type="button" class="btn btn-white remove-row">
                                                        <i class="bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            @php
                                                $oldValueCounts =  max(old('field_name') ? count(old('field_name')) : 0, 0 )
                                            @endphp

                                            @if($oldValueCounts)
                                                @for($i = 1; $i < $oldValueCounts; $i++)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="field_name[]" class="form-control"
                                                                   value="{{ old("field_name.$i") }}"
                                                                   placeholder="@lang("Field Name")" autocomplete="off">
                                                            @error("field_name.$i")
                                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                                            @enderror
                                                        </td>

                                                        <td>
                                                            <div class="tom-select-custom">
                                                                <select class="js-select form-select"
                                                                        name="input_type[]"
                                                                        data-hs-tom-select-options='{
                                                                    "searchInDropdown": false,
                                                                    "hideSearch": true
                                                                  }'>
                                                                    <option
                                                                        value="text" {{ old("input_type.$i") == 'text' ? 'selected' : '' }}>@lang('Text')</option>
                                                                    <option
                                                                        value="textarea" {{ old("input_type.$i") == 'textarea' ? 'selected' : '' }}>@lang('Textarea')</option>
                                                                    <option
                                                                        value="file" {{ old("input_type.$i") == 'file' ? 'selected' : '' }}>@lang('File')</option>
                                                                    <option
                                                                        value="number" {{ old("input_type.$i") == 'number' ? 'selected' : '' }}>@lang('Number')</option>
                                                                    <option
                                                                        value="date" {{ old("input_type.$i") == 'date' ? 'selected' : '' }}>@lang('Date')</option>
                                                                </select>
                                                                @error("input_type.$i")
                                                                <span
                                                                    class="invalid-feedback d-block">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="tom-select-custom">
                                                                <select class="js-select form-select"
                                                                        name="is_required[]"
                                                                        data-hs-tom-select-options='{
                                                                        "searchInDropdown": false,
                                                                        "hideSearch": true
                                                                      }'>
                                                                    <option
                                                                        value="required" {{ old("is_required.$i") == 'required' ? 'selected' : '' }}>@lang('Required')</option>
                                                                    <option
                                                                        value="optional" {{ old("is_required.$i") == 'optional' ? 'selected' : '' }}>@lang('Optional')</option>
                                                                </select>
                                                                @error("is_required.$i")
                                                                <span
                                                                    class="invalid-feedback d-block">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td class="table-column-ps-0">
                                                            <button type="button" class="btn btn-white remove-row">
                                                                <i class="bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endfor
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header d-flex  justify-content-between align-items-center">
                                        <h4 class="card-header-title">@lang('Supported Currencies Configuration')</h4>
                                        <a href="javascript:void(0)" class="add-field-btn btn btn-outline-info btn-sm">
                                            <i class="bi-plus"></i> @lang("Add Currency")
                                        </a>

                                    </div>
                                    <div class="table-responsive position-relative">
                                        <table
                                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                            id="supported_currency_table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th scope="col">@lang('Currency')</th>
                                                <th scope="col">@lang('Conversion Rate')</th>
                                                <th scope="col">@lang('Min Deposit Limit')</th>
                                                <th scope="col">@lang('Max Deposit Limit')</th>
                                                <th scope="col">@lang('Percentage Charge')</th>
                                                <th scope="col">@lang('Fixed Charge')</th>
                                                <th scope="col"></th>
                                            </tr>
                                            </thead>
                                            <tbody class="add_table_row">

                                            @php
                                                $oldCurrencyCounts = old('receivable_currencies') ? count(old('receivable_currencies')) : 0;
                                            @endphp

                                            @if($oldCurrencyCounts > 0)
                                                @for($i = 0; $i < $oldCurrencyCounts; $i++)
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control change_currency"
                                                                   name="receivable_currencies[{{$i}}][currency]"
                                                                   placeholder="@lang("Currency")"
                                                                   aria-label="@lang("Currency")"
                                                                   value="{{ old("receivable_currencies.$i.currency") }}"
                                                                   autocomplete="off">
                                                            @error('receivable_currencies.' . $i . '.currency')
                                                            <span
                                                                class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <span class="input-group-text"> 1 {{ $basicControl->base_currency ? : 'USD' }} = </span>
                                                                <input type="text"
                                                                       class="form-control @error('conversion_rate') is-invalid @enderror"
                                                                       name="receivable_currencies[{{$i}}][conversion_rate]"
                                                                       value="{{ old("receivable_currencies.$i.conversion_rate") }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text set-currency">{{ old("receivable_currencies.$i.currency") }}</span>
                                                                @error("receivable_currencies.$i.conversion_rate")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('min_limit') is-invalid @enderror"
                                                                       name="receivable_currencies[{{$i}}][min_limit]"
                                                                       value="{{ old("receivable_currencies.$i.min_limit") }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text set-currency">{{ old("receivable_currencies.$i.currency") }}</span>
                                                                @error("receivable_currencies.$i.min_limit")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('max_limit') is-invalid @enderror"
                                                                       name="receivable_currencies[{{$i}}][max_limit]"
                                                                       value="{{ old("receivable_currencies.$i.max_limit") }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text set-currency">{{ old("receivable_currencies.$i.currency") }}</span>
                                                                @error("receivable_currencies.$i.max_limit")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('percentage_charge') is-invalid @enderror"
                                                                       name="receivable_currencies[{{$i}}][percentage_charge]"
                                                                       value="{{ old("receivable_currencies.$i.percentage_charge") }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">%</span>
                                                                @error("receivable_currencies.$i.percentage_charge")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('fixed_charge') is-invalid @enderror"
                                                                       name="receivable_currencies[{{$i}}][fixed_charge]"
                                                                       autocomplete="off"
                                                                       value="{{ old("receivable_currencies.$i.fixed_charge") }}">
                                                                <span
                                                                    class="input-group-text set-currency">{{ old("receivable_currencies.$i.currency") }}</span>
                                                                @error("receivable_currencies.$i.fixed_charge")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td class="table-column-ps-0">
                                                            <button type="button" class="btn btn-white remove-row">
                                                                <i class="bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endfor
                                            @else
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control change_currency"
                                                               name="receivable_currencies[0][currency]"
                                                               placeholder="@lang("Currency")"
                                                               aria-label="@lang("Currency")"
                                                               value="{{ old("receivable_currencies.0.currency") }}"
                                                               autocomplete="off">
                                                        @error('receivable_currencies.0.currency')
                                                        <span
                                                            class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"> 1 {{ $basicControl->base_currency ? : 'USD' }} = </span>
                                                            <input type="text"
                                                                   class="form-control @error('conversion_rate') is-invalid @enderror"
                                                                   name="receivable_currencies[0][conversion_rate]"
                                                                   value="{{ old("receivable_currencies.0.conversion_rate") }}"
                                                                   autocomplete="off">
                                                            <span
                                                                class="input-group-text set-currency">{{ old("receivable_currencies.0.currency") }}</span>
                                                            @error('receivable_currencies.0.conversion_rate')
                                                            <span
                                                                class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <input type="text"
                                                                   class="form-control @error('min_limit') is-invalid @enderror"
                                                                   name="receivable_currencies[0][min_limit]"
                                                                   value="{{ old("receivable_currencies.0.min_limit") }}"
                                                                   autocomplete="off">
                                                            <span
                                                                class="input-group-text set-currency">{{ old("receivable_currencies.0.currency") }}</span>
                                                            @error('receivable_currencies.0.min_limit')
                                                            <span
                                                                class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <input type="text"
                                                                   class="form-control @error('max_limit') is-invalid @enderror"
                                                                   name="receivable_currencies[0][max_limit]"
                                                                   value="{{ old("receivable_currencies.0.max_limit") }}"
                                                                   autocomplete="off">
                                                            <span
                                                                class="input-group-text set-currency">{{ old("receivable_currencies.0.currency") }}</span>
                                                            @error('receivable_currencies.0.max_limit')
                                                            <span
                                                                class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <input type="text"
                                                                   class="form-control @error('percentage_charge') is-invalid @enderror"
                                                                   name="receivable_currencies[0][percentage_charge]"
                                                                   value="{{ old("receivable_currencies.0..percentage_charge]") }}"
                                                                   autocomplete="off">
                                                            <span
                                                                class="input-group-text">%</span>
                                                            @error('receivable_currencies.0.percentage_charge')
                                                            <span
                                                                class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <input type="text"
                                                                   class="form-control @error('fixed_charge') is-invalid @enderror"
                                                                   name="receivable_currencies[0][fixed_charge]"
                                                                   value="{{ old("receivable_currencies.0.fixed_charge") }}"
                                                                   autocomplete="off">
                                                            <span
                                                                class="input-group-text set-currency">{{ old("receivable_currencies.0.currency") }}</span>
                                                            @error('receivable_currencies.0.fixed_charge')
                                                            <span
                                                                class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </td>
                                                    <td class="table-column-ps-0">
                                                        <button type="button" class="btn btn-white remove-row">
                                                            <i class="bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mt-4">
                                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            $('#summernote').summernote({
                placeholder: 'Describe how to make a manual payment.',
                height: 160,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                },
            });

            $(document).on('click', '.add_field_btn', function () {
                let rowCount = $('#addFieldContainer tr').length;
                let markUp = `
                            <tr id="addVariantsTemplate">
                                <td>
                                    <input type="text" class="form-control" name="field_name[]" placeholder="@lang("Field Name")" autocomplete="off">
                                </td>
                                <td>
                                    <div class="tom-select-custom">
                                        <select class="js-select-dynamic-input-type${rowCount} form-select" name="input_type[]"
                                                data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                                            <option value="text">@lang('Text')</option>
                                            <option value="textarea">@lang('Textarea')</option>
                                            <option value="file">@lang('File')</option>
                                            <option value="number">@lang('Number')</option>
                                            <option value="date">@lang('Date')</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="tom-select-custom">
                                        <select class="js-select-dynamic-validation-type${rowCount} form-select" name="is_required[]"
                                                data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                                            <option value="required">@lang('Required')</option>
                                            <option value="optional">@lang('Optional')</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="table-column-ps-0">
                                    <button type="button" class="btn btn-white remove-row">
                                                            <i class="bi-trash"></i>
                                                        </button>
                                </td>
                            </tr>`;

                $("#addFieldContainer").append(markUp);
                const selectClass = `.js-select-dynamic-input-type${rowCount}, .js-select-dynamic-validation-type${rowCount}`;
                $("#addFieldContainer").find(selectClass).each(function () {
                    HSCore.components.HSTomSelect.init($(this));
                });

            });

            $(document).on('click', '.remove-row', function (e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });


            $(document).on('change input', ".change_currency", function (e) {
                let currency = $(this).val();
                $(this).closest('tr').find('.set-currency').text(currency);
            });

            $(document).on('click', '.add-field-btn', function () {

                let baseCurrency = "{{ $basicControl->base_currency }}";
                let rowCount = parseInt($('#supported_currency_table tr').length) - 1;

                let markup = `
                            <tr>
                                   <td>
                                        <input type="text" class="form-control change_currency"
                                            name="receivable_currencies[${rowCount}][currency]"
                                               placeholder="Currency" aria-label="Currency"
                                               autocomplete="off">
                                   </td>
                                   <td>
                                        <div class="input-group mb-1">
                                            <span class="input-group-text"> 1 ${baseCurrency} = </span>
                                            <input type="text"
                                                   class="form-control"
                                                   name="receivable_currencies[${rowCount}][conversion_rate]"
                                                   autocomplete="off">
                                            <span class="input-group-text set-currency"></span>
                                        </div>
                                    </td>
                                     <td>
                                         <div class="input-group mb-1">
                                             <input type="text"
                                                   class="form-control"
                                                   name="receivable_currencies[${rowCount}][min_limit]"
                                                   autocomplete="off">
                                                   <span class="input-group-text set-currency"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group mb-1">
                                            <input type="text"
                                                   class="form-control"
                                                   name="receivable_currencies[${rowCount}][max_limit]"
                                                   autocomplete="off">
                                            <span class="input-group-text set-currency"></span>
                                        </div>
                                        </td>
                                        <td>
                                            <div class="input-group mb-1">
                                                <input type="text"
                                                   class="form-control"
                                                   name="receivable_currencies[${rowCount}][percentage_charge]"
                                                   autocomplete="off">
                                                   <span class="input-group-text">%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group mb-1">
                                                <input type="text"
                                                       class="form-control"
                                                   name="receivable_currencies[${rowCount}][fixed_charge]"
                                                   autocomplete="off">
                                                <span class="input-group-text set-currency"></span>
                                            </div>
                                        </td>
                                            <td class="table-column-ps-0">
                                            <button type="button" class="btn btn-white remove-row">
                                                <i class="bi-trash"></i>
                                            </button>
                                        </td>
                                   </tr>`;

                $('.add_table_row').append(markup);
            });

            new HSFileAttach('.js-file-attach')
            HSCore.components.HSTomSelect.init('.js-select')

        });


    </script>
@endpush



