@extends('admin.layouts.app')
@section('page_title', __('Edit Withdraw Method'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Withdraw Method')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang('Edit ' . $payoutMethod->name)</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit ' . $payoutMethod->name)</h1>
                </div>
            </div>
        </div>

        <div class="row payout_method">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title mt-2">@lang('Edit ' . $payoutMethod->name)</h3>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.payout.method.update', $payoutMethod->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')

                                <div class="row mb-4">
                                    <div class="col-sm-{{ $payoutMethod->is_automatic == 0 ? "12" : "6" }} ">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel"
                                               placeholder="@lang("Name")" aria-label="@lang("Name")" autocomplete="off"
                                               value="{{ old('name', $payoutMethod->name ?? '') }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    @if($payoutMethod->is_automatic == 1)
                                        <div class="col-sm-6">
                                            <label for="currencyLabel"
                                                   class="form-label">@lang('Supported Currency')</label>
                                            <div class="tom-select-custom tom-select-custom-with-tags">
                                                <select class="js-select form-select supported_currency"
                                                        autocomplete="off" multiple
                                                        name="payout_currencies[][name]" data-hs-tom-select-options='{
                                                        "placeholder": "Select Currency"
                                                      }'>
                                                    @php
                                                        $payoutMethodsCurrency = session()->has('selectedCurrencyList')
                                                            ? session('selectedCurrencyList')
                                                            : (isset($payoutMethod->supported_currency) ? $payoutMethod->supported_currency : []);
                                                    @endphp
                                                    @foreach($payoutMethod->currency_lists as $key => $currency)
                                                        @php
                                                            $isSelected = in_array($key, (array) $payoutMethodsCurrency);
                                                        @endphp
                                                        <option
                                                            value="{{$key}}"{{ $isSelected ? 'selected' : '' }}>{{$currency}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('payout_currencies')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif

                                    @if($payoutMethod->bank_name && $payoutMethod->is_automatic == 1)
                                        <div class="col-sm-12 mt-3">
                                            <label for="BankLabel"
                                                   class="form-label">@lang('Bank')</label>
                                            <div class="tom-select-custom tom-select-custom-with-tags">
                                                <select class="js-select-bank form-select bank" required
                                                        name="banks[]" autocomplete="off"
                                                        multiple
                                                        data-hs-tom-select-options='{
                                                        "placeholder": "Select Bank"
                                                      }'>
                                                    @foreach($payoutMethod->bank_name as $key => $bank)
                                                        @foreach($bank as $curKey => $singleBank)
                                                            <option value="{{ $curKey }}"
                                                                    {{ in_array($curKey,$banks) == true ? 'selected' : '' }} data-fiat="{{ $key }}"
                                                            >{{ trans($curKey) }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('currency')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                </div>


                                @if($payoutMethod->is_automatic == 1)
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-header-title mb-2 mb-sm-0">@lang("Parameters")</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @if($payoutMethod->parameters)
                                                    @foreach ($payoutMethod->parameters as $key => $parameter)
                                                        <div class="col-sm-6 mt-3">
                                                            <div class="form-group">
                                                                <label for="{{ $key }}"
                                                                       class="form-label">{{ __(snake2Title($key)) }}</label>
                                                                <input type="text" name="{{ $key }}"
                                                                       value="{{ old($key, $parameter) }}"
                                                                       id="{{ $key }}"
                                                                       class="form-control @error($key) is-invalid @enderror">
                                                                <div class="invalid-feedback">
                                                                    @error($key) @lang($message) @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                @if($payoutMethod->extra_parameters)
                                                    @foreach($payoutMethod->extra_parameters as $key => $param)
                                                        <div class="col-sm-6 mt-3">
                                                            <label for="{{ $key }}"
                                                                   class="form-label">{{ __(snake2Title($key)) }}</label>
                                                            <div
                                                                class="input-group input-group-merge table-input-group">
                                                                <input id="apiKeyCode1" type="text" name="{{ $key }}"
                                                                       class="form-control @error($key) is-invalid @enderror"
                                                                       readonly
                                                                       value="{{ old($key, route($param, $payoutMethod->code )) }}">
                                                                <a class="js-clipboard input-group-append input-group-text"
                                                                   href="javascript:void(0)" data-bs-toggle="tooltip"
                                                                   title="Copy to clipboard" data-hs-clipboard-options='{
                                                                    "type": "tooltip",
                                                                    "successText": "Copied!",
                                                                    "contentTarget": "#apiKeyCode1",
                                                                    "classChangeTarget": "#apiKeyCodeIcon1",
                                                                    "defaultClass": "bi-clipboard",
                                                                    "successClass": "bi-check"
                                                                   }'>
                                                                    <i id="apiKeyCodeIcon1" class="bi-clipboard"></i>
                                                                </a>
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                @error($key) @lang($message) @enderror
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row my-5">
                                    <div class="col-md-3">
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($payoutMethod->driver, $payoutMethod->logo, true) }}"
                                                 alt="Image Description" data-hs-theme-appearance="default">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($payoutMethod->driver, $payoutMethod->logo, true) }}"
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

                                        @error('image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-9">
                                        <label for="descriptionLabel" class="form-label">@lang('Description')</label>
                                        <textarea name="description" class="form-control custom_textarea" rows="3" id="descriptionLabel"
                                                  placeholder="@lang("Description")">{{ $payoutMethod->description }}</textarea>

                                        <label class="row form-check form-switch mt-3" for="manual_status">
                                        <span class="col-4 col-sm-9 ms-0 ">
                                          <span class="d-block text-dark">@lang("Withdraw Status")</span>
                                          <span
                                              class="d-block fs-5">@lang("Enable withdraw status as active for the transaction.")</span>
                                        </span>
                                            <span class="col-2 col-sm-3 text-end">
                                         <input type='hidden' value='0' name='status'>
                                            <input class="form-check-input @error('status') is-invalid @enderror"
                                                   type="checkbox" name="status" id="withdrawStatusSwitch"
                                                   value="1" {{ $payoutMethod->is_active == 1 ? "checked" : "" }}>
                                            <label class="form-check-label text-center"
                                                   for="withdrawStatusSwitch"></label>
                                        </span>
                                            @error('manual_status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>

                                        <label class="row form-check form-switch mt-3" for="auto_update_currency">
                                        <span class="col-4 col-sm-9 ms-0 ">
                                          <span class="d-block text-dark">@lang("Auto Update Currency")</span>
                                          <span
                                              class="d-block fs-5">@lang("Enable automatic currency rate updates to ensure accurate currency conversion.")</span>
                                        </span>
                                            <span class="col-2 col-sm-3 text-end">
                                         <input type='hidden' value='0' name='auto_update_currency'>
                                            <input
                                                class="form-check-input @error('auto_update_currency') is-invalid @enderror"
                                                type="checkbox" name="auto_update_currency" id="auto_update_currency"
                                                value="1" {{ $payoutMethod->is_auto_update == 1 ? "checked" : "" }}>
                                            <label class="form-check-label text-center"
                                                   for="auto_update_currency"></label>
                                        </span>
                                            @error('manual_status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>

                                </div>

                                @if($payoutMethod->is_automatic == 0)
                                    <div class="card mb-3 mb-lg-5">
                                        <div class="card-header card-header-content-sm-between">
                                            <h4 class="card-header-title mb-2 mb-sm-0">@lang("Add Field")</h4>
                                            <div class="d-sm-flex align-items-center gap-2">
                                                <a class="btn btn-outline-info btn-sm add_field_btn"
                                                   href="javascript:void(0)">
                                                    <i class="bi-plus"></i> @lang("Add Field")
                                                </a>
                                            </div>
                                        </div>
                                        <div class="table-responsive position-relative">
                                            <table id="datatable"
                                                   class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th>@lang("Field Name")</th>
                                                    <th>@lang("Input Type")</th>
                                                    <th>@lang("Validation Type")</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                @php
                                                    $oldPayoutInputFormCount = old('field_name', $payoutMethod->inputForm) ? count( old('field_name', (array) $payoutMethod->inputForm)) : 0;
                                                @endphp
                                                <tbody id="addFieldContainer">
                                                @if( 0 < $oldPayoutInputFormCount)
                                                    @php
                                                        $oldPayoutInputForm = collect(old('field_name', (array)$payoutMethod->inputForm))->values();
                                                    @endphp
                                                    @for($i = 0; $i < $oldPayoutInputFormCount; $i++)
                                                        <tr>
                                                            <td>
                                                                <input type="text" name="field_name[]"
                                                                       class="form-control"
                                                                       value="{{ old("field_name.$i", $oldPayoutInputForm[$i]->field_label ?? '') }}"
                                                                       placeholder="@lang("Field Name")"
                                                                       autocomplete="off">
                                                                @error("field_name.$i")
                                                                <span
                                                                    class="invalid-feedback d-block">{{ $message }}</span>
                                                                @enderror
                                                            </td>

                                                            <td>
                                                                <div class="tom-select-custom">
                                                                    <select
                                                                        class="js-select-input-init{{$i}} form-select"
                                                                        name="input_type[]"
                                                                        data-hs-tom-select-options='{
                                                                        "searchInDropdown": false,
                                                                        "hideSearch": true
                                                                      }'>
                                                                        <option
                                                                            value="text" {{ old("input_type.$i", $oldPayoutInputForm[$i]->type ?? '') == 'text' ? 'selected' : '' }}>@lang('Text')</option>
                                                                        <option
                                                                            value="textarea" {{ old("input_type.$i", $oldPayoutInputForm[$i]->type ?? '') == 'textarea' ? 'selected' : '' }}>@lang('Textarea')</option>
                                                                        <option
                                                                            value="file" {{ old("input_type.$i", $oldPayoutInputForm[$i]->type ?? '') == 'file' ? 'selected' : '' }}>@lang('File')</option>
                                                                        <option
                                                                            value="number" {{ old("input_type.$i", $oldPayoutInputForm[$i]->type ?? '') == 'number' ? 'selected' : '' }}>@lang('Number')</option>
                                                                        <option
                                                                            value="date" {{ old("input_type.$i", $oldPayoutInputForm[$i]->type ?? '') == 'date' ? 'selected' : '' }}>@lang('Date')</option>
                                                                    </select>
                                                                    @error("input_type.$i")
                                                                    <span
                                                                        class="invalid-feedback d-block">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="tom-select-custom">
                                                                    <select
                                                                        class="js-select-required-init{{$i}} form-select"
                                                                        name="is_required[]"
                                                                        data-hs-tom-select-options='{
                                                                        "searchInDropdown": false,
                                                                        "hideSearch": true
                                                                      }'>
                                                                        <option
                                                                            value="required" {{ old("is_required.$i", $oldPayoutInputForm[$i]->validation ?? '') == 'required' ? 'selected' : '' }}>@lang('Required')</option>
                                                                        <option
                                                                            value="optional" {{ old("is_required.$i", $oldPayoutInputForm[$i]->validation ?? '') == 'optional' ? 'selected' : '' }}>@lang('Optional')</option>
                                                                    </select>
                                                                    @error("is_required.$i")
                                                                    <span
                                                                        class="invalid-feedback d-block">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </td>
                                                            <td>
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
                                @endif

                                <div class="card supported_currency_card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-header-title">@lang('Supported Currencies Configuration')</h4>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary auto_update_btn"
                                                    data-meesage="{{ $payoutMethod->name }}"
                                                    data-bs-toggle="modal" data-bs-target="#autoUpdateCurrencyModal">
                                                @lang('Auto Update Currency')
                                            </button>
                                            @if($payoutMethod->is_automatic == 0)
                                                <a href="javascript:void(0)"
                                                   class="add-field-btn btn btn-outline-info btn-sm">
                                                    <i class="bi-plus"></i> @lang('Add Currency')
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="table-responsive position-relative">
                                        <table
                                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                            id="supported_currency_table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th scope="col">{{ $payoutMethod->is_automatic == 0 ? 'Currency' : 'Currency Symbol' }} </th>
                                                <th scope="col">@lang('Conversion Rate')</th>
                                                <th scope="col">@lang('Min Limit')</th>
                                                <th scope="col">@lang('Max Limit')</th>
                                                <th scope="col">@lang('Percentage Charge')</th>
                                                <th scope="col">@lang('Fixed Charge')</th>
                                                @if($payoutMethod->is_automatic == 0)
                                                    <th scope="col"></th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody class="add_table_row">
                                            @php
                                                $oldPayoutMethodCur = old('payout_currencies', $payoutMethod->payout_currencies) ? count(old('payout_currencies', $payoutMethod->payout_currencies)) : 0;
                                                $oldSelectedCurrency = session()->get('selectedCurrencyList');
                                            @endphp

                                            @if($oldPayoutMethodCur > 0)
                                                @for($i = 0; $i < $oldPayoutMethodCur; $i++)
                                                    <tr class="{{ $payoutMethod->payout_currencies[$i]->currency_symbol ?? $oldSelectedCurrency[$i] }}-row">
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text" class="form-control"
                                                                       name="payout_currencies[{{ $i }}][currency_symbol]"
                                                                       placeholder="Symbol" aria-label="CurrencySymbol"
                                                                       value="{{ old("payout_currencies.$i.currency_symbol", $payoutMethod->payout_currencies[$i]->currency_symbol ?? '') }}"
                                                                       aria-describedby="basic-addon1"
                                                                       autocomplete="off">
                                                                @error("payout_currencies.$i.currency_symbol")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <span class="input-group-text"> 1 {{ $basicControl->base_currency ? : 'USD' }} = </span>
                                                                <input type="text"
                                                                       class="form-control @error('conversion_rate') is-invalid @enderror"
                                                                       name="payout_currencies[{{ $i }}][conversion_rate]"
                                                                       value="{{ old("payout_currencies.$i.conversion_rate", $payoutMethod->payout_currencies[$i]->conversion_rate ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("payout_currencies.$i.currency_symbol", $payoutMethod->payout_currencies[$i]->currency_symbol ?? 'USD') }}</span>
                                                                @error("payout_currencies.$i.conversion_rate")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('min_limit') is-invalid @enderror"
                                                                       name="payout_currencies[{{$i}}][min_limit]"
                                                                       value="{{ old("payout_currencies.$i.min_limit", $payoutMethod->payout_currencies[$i]->min_limit ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("payout_currencies.$i.currency_symbol", $payoutMethod->payout_currencies[$i]->currency_symbol ?? 'USD') }}</span>
                                                                @error("payout_currencies.$i.min_limit")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('max_limit') is-invalid @enderror"
                                                                       name="payout_currencies[{{$i}}][max_limit]"
                                                                       value="{{ old("payout_currencies.$i.min_limit", $payoutMethod->payout_currencies[$i]->max_limit ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("payout_currencies.$i.currency_symbol", $payoutMethod->payout_currencies[$i]->currency_symbol ?? 'USD') }}</span>
                                                                @error("payout_currencies.$i.max_limit")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('percentage_charge') is-invalid @enderror"
                                                                       name="payout_currencies[{{$i}}][percentage_charge]"
                                                                       value="{{ old("payout_currencies.$i.min_limit", $payoutMethod->payout_currencies[$i]->percentage_charge ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">%</span>
                                                                @error("payout_currencies.$i.percentage_charge")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('fixed_charge') is-invalid @enderror"
                                                                       name="payout_currencies[{{$i}}][fixed_charge]"
                                                                       value="{{ old("payout_currencies.$i.fixed_charge", $payoutMethod->payout_currencies[$i]->fixed_charge ?? 0) }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("payout_currencies.$i.currency_symbol", $payoutMethod->payout_currencies[$i]->currency_symbol ?? 'USD') }}</span>
                                                                @error("payout_currencies.$i.fixed_charge")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        @if($payoutMethod->is_automatic == 0)
                                                            <td>
                                                                <button type="button" class="btn btn-white remove-row">
                                                                    <i class="bi-trash"></i>
                                                                </button>
                                                            </td>
                                                        @endif
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

    <!-- Modal -->
    <div class="modal fade" id="autoUpdateCurrencyModal" tabindex="-1" role="dialog"
         aria-labelledby="autoUpdateCurrencyModalLabel" data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="autoUpdateCurrencyModalLabel">@lang('Confirmation')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payout.method.auto.update', $payoutMethod->id) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <span>@lang("Do you want to") <span>{{ __($payoutMethod->name) }}</span> @lang("currency rate auto update?")</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>

@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSClipboard.init('.js-clipboard')

            var eventHandler = function (name) {
                return function () {
                    if (name == 'onItemAdd') {
                        itemAppend(arguments[0]);
                    } else if (name == 'onItemRemove') {
                        itemRemove(arguments[0][0]);
                    }
                };
            };

            var autoMatic = "{{ $payoutMethod->is_automatic }}";
            if (autoMatic == 1) {
                new TomSelect('.js-select', {
                    plugins: {
                        remove_button: {
                            title: '',
                        },
                    },
                    create: true,
                    onItemAdd: eventHandler('onItemAdd'),
                    onDelete: eventHandler('onItemRemove'),
                });
                new TomSelect('.js-select-bank', {
                    plugins: {
                        remove_button: {
                            title: '',
                        },
                    },
                    create: true,
                });
            }

            let rowCountSelect = $('#addFieldContainer tr').length;
            for (let i = 0; i < rowCountSelect; i++) {
                new TomSelect(`.js-select-input-init${i}`);
                new TomSelect(`.js-select-required-init${i}`);
            }

            $(document).on('click', '.add-field-btn', function () {
                itemAppend('USD');
            });

            $(document).on('change input', ".change_currency", function (e) {
                let currency = $(this).val();
                $(this).closest('tr').find('.set-currency').text(currency);
            });

            function itemAppend(currency) {

                let rowCount = $('#supported_currency_table tr').length;

                let removeButtonMarkup = '';
                if (autoMatic == 1) {
                    removeButtonMarkup = ``;
                } else {
                    removeButtonMarkup = `
                    <td class="table-column-ps-0">
                        <button type="button" class="btn btn-white remove-row">
                            <i class="bi-trash"></i>
                        </button>
                    </td>`;
                }

                let markup = "";
                markup += `
                        <tr class="${currency}-row">
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control change_currency" name="payout_currencies[${rowCount - 1}][currency_symbol]"
                                           placeholder="@lang("Currency")" aria-label="@lang("Currency")"
                                           autocomplete="off">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">1 {{ $basicControl->base_currency }} = </span>
                                        <input type="text"
                                            class="form-control"
                                            name="payout_currencies[${rowCount - 1}][conversion_rate]"
                                            autocomplete="off">
                                            <span class="input-group-text set-currency">${currency}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                          name="payout_currencies[${rowCount - 1}][min_limit]"
                                          autocomplete="off">
                                          <span class="input-group-text set-currency">${currency}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            name="payout_currencies[${rowCount - 1}][max_limit]"
                                            autocomplete="off">
                                            <span class="input-group-text set-currency">${currency}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            name="payout_currencies[${rowCount - 1}][percentage_charge]"
                                            autocomplete="off">
                                            <span class="input-group-text set-currency">${currency}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                             name="payout_currencies[${rowCount - 1}][fixed_charge]"
                                             autocomplete="off">
                                             <span class="input-group-text set-currency">${currency}</span>
                                    </div>
                                </td>
                                ${removeButtonMarkup}
                            </tr>`;

                $('.add_table_row').append(markup);
            }

            function itemRemove(currency) {
                $(`.${currency}-row`).remove();
                alignArrayIndexForSupportCurrency();
            }

            function alignArrayIndexForSupportCurrency() {
                $('.add_table_row tr').each(function (index) {
                    $(this).find('input[name^="payout_currencies"]').each(function () {
                        var newName = $(this).attr('name').replace(/\[(\d+)\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    })
                })
            }

            $(document).on('click', '.remove-row', function (e) {
                e.preventDefault();
                $(this).closest('tr').remove();
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
                                <td>
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
        });
    </script>
@endpush


