@extends('admin.layouts.app')
@section('page_title', __('KYC Setting'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('KYC Setting')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit KYC Form')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit ' . $kyc->name. ' Form')</h1>
                </div>
            </div>
        </div>

        <div class="row" id="add_kyc_form_table">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title mt-2">@lang('Edit ' . $kyc->name. ' Form')</h3>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.kyc.update', $kyc->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel" placeholder="Name" aria-label="Name"
                                               autocomplete="off"
                                               value="{{ old('name', $kyc->name) }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="row form-check form-switch mt-3" for="push_notification">
                                        <span class="col-4 col-sm-9 ms-0 ">
                                          <span class="d-block text-dark">@lang("KYC Status")</span>
                                          <span
                                              class="d-block fs-5">@lang("Enable KYC status for your user to obtain their information.")</span>
                                        </span>
                                            <span class="col-2 col-sm-3 text-end">
                                         <input type='hidden' value='0' name='status'>
                                            <input class="form-check-input @error('status') is-invalid @enderror"
                                                   type="checkbox" name="status" id="kycStatusSwitch"
                                                   value="1" {{ old('status', $kyc->status) == 1 ? 'checked' : ''}}>
                                            <label class="form-check-label text-center" for="kycStatusSwitch"></label>
                                        </span>
                                            @error('push_notification')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>

                                <div class="js-add-field card mb-3 mb-lg-5">
                                    <div class="card-header card-header-content-sm-between">
                                        <h4 class="card-header-title mb-2 mb-sm-0">@lang("Add Field")</h4>
                                        <div class="d-sm-flex align-items-center gap-2">
                                            <a class="js-create-field btn btn-ghost-secondary btn-sm add_field_btn" href="javascript:void(0);">
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
                                                $oldKycInputFormCount = old('field_name', $kyc->input_form) ? count( old('field_name', (array) $kyc->input_form)) : 0;
                                            @endphp
                                            <tbody id="addFieldContainer">
                                            @if( 0 < $oldKycInputFormCount)
                                                @php
                                                    $oldKycInputForm = collect(old('field_name', (array)$kyc->input_form))->values();
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

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit"
                                            class="btn btn-primary submit_btn">@lang('Save changes')</button>
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
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {

            HSCore.components.HSTomSelect.init('.js-select')

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

            $(document).on('click', '.remove-row', function (e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });

        });

    </script>
@endpush



