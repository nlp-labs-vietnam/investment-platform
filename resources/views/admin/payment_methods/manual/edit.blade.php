@extends('admin.layouts.app')
@section('page_title', __('Edit Payment Method'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Dashboard")</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Manual Gateway")</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Edit " . $method->name)</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Edit " . $method->name)</h1>
                </div>
            </div>
        </div>

        <div class="row payment_method">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title mt-2">@lang("Edit " . $method->name)</h3>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{route('admin.deposit.manual.update',$method)}}" method="post"
                                  enctype="multipart/form-data" id="myForm">
                                @csrf
                                @method('put')
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-sm-6">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel"
                                               placeholder="Name" aria-label="Name" autocomplete="off"
                                               value="{{ old('name', $method->name ?? '') }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="row form-check form-switch mt-3" for="manual_gateway_status">
                                        <span class="col-4 col-sm-9 ms-0 ">
                                          <span class="d-block text-dark">@lang("Manual Gateway Status")</span>
                                          <span
                                              class="d-block fs-5">@lang("Enable manual gateway status for your user to payment requiredment.")</span>
                                        </span>
                                            <span class="col-2 col-sm-3 text-end">
                                         <input type='hidden' value='0' name='manual_gateway_status'>
                                            <input
                                                class="form-check-input @error('manual_gateway_status') is-invalid @enderror"
                                                type="checkbox" name="manual_gateway_status" id="manualGatewayStatus"
                                                value="1" {{ $method->status == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label text-center"
                                                   for="manualGatewayStatus"></label>
                                        </span>
                                            @error('manual_gateway_status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-5">
                                        <label class="form-label"
                                               for="descriptionArea">@lang("Gateway Description")</label>
                                        <textarea id="descriptionArea" class="form-control" name="description" placeholder="Description">{{ old('description', $method->description) }}</textarea>
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
                                                 src="{{ getFile($method->driver, $method->image, true) }}"
                                                 alt="Image Description" data-hs-theme-appearance="default">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($method->driver, $method->image, true) }}"
                                                 alt="Image Description" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input" name="image"
                                                   id="logoUploader" data-hs-file-attach-options='{
                                                      "textTarget": "#logoImg",
                                                      "mode": "image",
                                                      "targetAttr": "src",
                                                      "allowTypes": [".png", ".jpeg", ".jpg"]
                                                   }'>
                                            @error("image")
                                            <span
                                                class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>

                                    <div class="col-sm-9">
                                        <label for="nameLabel" class="form-label">@lang('Payment Description')</label>
                                        <textarea id="summernote" name="note" autocomplete="off">{{ old('note', $method->note) }}</textarea>
                                        <span class="invalid-feedback d-block">
                                            @error('note') @lang($message) @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="card mb-3 mb-lg-5">
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

                                            @php
                                                $oldKycInputFormCount = old('field_name', $method->parameters) ? count( old('field_name', (array) $method->parameters)) : 0;
                                            @endphp
                                            <tbody id="addFieldContainer">
                                            @if( 0 < $oldKycInputFormCount)
                                                @php
                                                    $oldKycInputForm = collect(old('field_name', (array)$method->parameters))->values();
                                                @endphp

                                                @for($i = 0; $i < $oldKycInputFormCount; $i++)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="field_name[]" class="form-control"
                                                                   value="{{ old("field_name.$i", $oldKycInputForm[$i]->field_label ?? '') }}"
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
                                                                        value="text" {{ old("input_type.$i", $oldKycInputForm[$i]->type ?? '') == 'text' ? 'selected' : '' }}>@lang('Text')</option>
                                                                    <option
                                                                        value="textarea" {{ old("input_type.$i", $oldKycInputForm[$i]->type ?? '') == 'textarea' ? 'selected' : '' }}>@lang('Textarea')</option>
                                                                    <option
                                                                        value="file" {{ old("input_type.$i", $oldKycInputForm[$i]->type ?? '') == 'file' ? 'selected' : '' }}>@lang('File')</option>
                                                                    <option
                                                                        value="number" {{ old("input_type.$i", $oldKycInputForm[$i]->type ?? '') == 'number' ? 'selected' : '' }}>@lang('Number')</option>
                                                                    <option
                                                                        value="date" {{ old("input_type.$i", $oldKycInputForm[$i]->type ?? '') == 'date' ? 'selected' : '' }}>@lang('Date')</option>
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
                                                                        value="required" {{ old("is_required.$i", $oldKycInputForm[$i]->validation ?? '') == 'required' ? 'selected' : '' }}>@lang('Required')</option>
                                                                    <option
                                                                        value="optional" {{ old("is_required.$i", $oldKycInputForm[$i]->validation ?? '') == 'optional' ? 'selected' : '' }}>@lang('Optional')</option>
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
                                        <div class="d-sm-flex align-items-center gap-2">
                                            <a class="js-create-field btn btn-outline-info btn-sm add_currency_btn"
                                               href="javascript:void(0);">
                                                <i class="bi-plus"></i> @lang("Add Currency")
                                            </a>
                                        </div>
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
                                                $oldReceivableCurrency = old('receivable_currencies', $method->receivable_currencies) ? count(old('receivable_currencies', $method->receivable_currencies)) : 0;
                                            @endphp

                                            @if($oldReceivableCurrency > 0)
                                                @for($i = 0; $i < $oldReceivableCurrency; $i++)
                                                    <tr>
                                                        <td>
                                                            <div class="mb-1">
                                                                <input type="text" class="form-control"
                                                                       name="receivable_currencies[{{ $i }}][currency]"
                                                                       placeholder="@lang("Currency")"
                                                                       aria-label="@lang("Currency")"
                                                                       value="{{ old("receivable_currencies.$i.currency", $method->receivable_currencies[$i]->currency ?? '') }}"
                                                                       autocomplete="off">
                                                                @error("receivable_currencies.$i.currency")
                                                                <span
                                                                    class="invalid-feedback d-block">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <span class="input-group-text"> 1 {{ $basicControl->base_currency ? : 'USD' }} = </span>
                                                                <input type="text"
                                                                       class="form-control @error('conversion_rate') is-invalid @enderror"
                                                                       name="receivable_currencies[{{ $i }}][conversion_rate]"
                                                                       value="{{ old("receivable_currencies.$i.conversion_rate", $method->receivable_currencies[$i]->conversion_rate ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.currency", $method->receivable_currencies[$i]->currency ?? '') }}</span>
                                                                @error("receivable_currencies.$i.conversion_rate")
                                                                <span
                                                                    class="invalid-feedback d-block">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('min_limit') is-invalid @enderror"
                                                                       name="receivable_currencies[{{ $i }}][min_limit]"
                                                                       aria-label="Amount (to the nearest dollar)"
                                                                       value="{{ old("receivable_currencies.$i.min_limit", $method->receivable_currencies[$i]->min_limit ?? '')  }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.currency", $method->receivable_currencies[$i]->currency ?? '') }}</span>
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
                                                                       aria-label="Amount (to the nearest dollar)"
                                                                       value="{{ old("receivable_currencies.$i.max_limit", $method->receivable_currencies[$i]->max_limit ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.currency", $method->receivable_currencies[$i]->currency ?? '') }}</span>
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
                                                                       name="receivable_currencies[{{ $i }}][percentage_charge]"
                                                                       aria-label="Amount (to the nearest dollar)"
                                                                       value="{{ old("receivable_currencies.$i.percentage_charge", $method->receivable_currencies[$i]->percentage_charge ?? '') }}"
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
                                                                       name="receivable_currencies[{{ $i }}][fixed_charge]"
                                                                       aria-label="Amount (to the nearest dollar)"
                                                                       value="{{ old("receivable_currencies.$i.fixed_charge", $method->receivable_currencies[$i]->fixed_charge ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.currency", $method->receivable_currencies[$i]->currency ?? '') }}</span>
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

            $('#summernote').summernote({
                height: 160,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                },
            });

            $(document).on('change input', ".change_currency", function (e) {
                let currency = $(this).val();
                $(this).closest('tr').find('.set-currency').text(currency);
            });

            $(document).on('click', '.add_currency_btn', function () {
                let rowCount = $('#supported_currency_table tr').length;
                let baseCurrency = "{{ $basicControl->base_currency }}";
                let markup = `
                           <tr>
                                   <td>
                                        <input type="text" class="form-control change_currency"
                                               name="receivable_currencies[${rowCount - 1}][currency]"
                                               placeholder="Symbol" aria-label="Symbol"
                                               aria-describedby="basic-addon1"
                                               autocomplete="off">
                                   </td>
                                   <td>
                                        <div class="input-group mb-1">
                                            <span class="input-group-text"> 1 ${baseCurrency} = </span>
                                            <input type="text"
                                                   class="form-control"
                                                   name="receivable_currencies[${rowCount - 1}][conversion_rate]"
                                                   autocomplete="off">
                                            <span class="input-group-text set-currency"></span>
                                        </div>
                                   </td>
                                    <td>
                                        <div class="input-group mb-1">
                                            <input type="text" class="form-control"
                                                   name="receivable_currencies[${rowCount - 1}][min_limit]"
                                                   autocomplete="off">
                                                    <span class="input-group-text set-currency"></span>
                                            </div>
                                        </td>
                                    <td>
                                        <div class="input-group mb-1">
                                            <input type="text"
                                                   class="form-control"
                                                   name="receivable_currencies[${rowCount - 1}][max_limit]"
                                                   autocomplete="off">
                                            <span class="input-group-text set-currency"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group mb-1">
                                            <input type="text"
                                                   class="form-control"
                                                   name="receivable_currencies[${rowCount - 1}][percentage_charge]"
                                                   autocomplete="off">
                                                <span class="input-group-text">%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group mb-1">
                                            <input type="text"
                                                   class="form-control"
                                                   name="receivable_currencies[${rowCount - 1}][fixed_charge]"
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


