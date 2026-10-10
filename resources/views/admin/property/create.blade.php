@extends('admin.layouts.app')
@section('page_title', __('Create New Property'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item">@lang('Manage Property')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("Create Property")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Create New Property")</h1>
                </div>
            </div>
        </div>

        <form action="{{ route("admin.property.store") }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="d-grid gap-3 gap-lg-5">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title h4">@lang("Add Property Details")</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="title" class="form-label">@lang("Title")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control propertyTitle"
                                                           name="title" value="{{ old("title") }}"
                                                           placeholder="@lang("title")" autocomplete="off">
                                                </div>
                                                @error("title")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-md-6 mb-3">
                                                <label for="NameLabel" class="form-label">@lang("Address")</label>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select" autocomplete="off"
                                                            name="address_id"
                                                            data-hs-tom-select-options='{
                                                      "placeholder": "Select Address",
                                                      "hideSearch": true
                                                    }'>
                                                        <option value=""></option>
                                                        @foreach($addresses as $address)
                                                            <option
                                                                value="{{ $address->id }}" {{ old('address_id') == $address->id ? 'selected' : '' }}>@lang($address->title)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('address_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="amenity_id" class="form-label">@lang("Amenities")</label>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select" autocomplete="off"
                                                            name="amenity_id[]"
                                                            multiple
                                                            data-hs-tom-select-options='{
                                                      "placeholder": "Select Amenity",
                                                      "hideSearch": true
                                                    }'>
                                                        <option value=""></option>
                                                        @foreach($amenities as $amenity)
                                                            <option
                                                                value="{{ $amenity->id }}" {{ in_array($amenity->id, (array) old('amenity_id')) ? 'selected' : '' }}>@lang($amenity->title)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('amenity_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="location" class="form-label">@lang("Location")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           name="location" value="{{ old("location") }}"
                                                           placeholder="@lang('only embed url accepted')"
                                                           autocomplete="off">
                                                </div>
                                                @error("location")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-md-12">
                                                <label for="video" class="form-label">@lang("Video Url")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control change_name_input"
                                                           name="video" value="{{ old("video") }}"
                                                           placeholder="@lang('only iframe embed url accepted')"
                                                           autocomplete="off">
                                                </div>
                                                @error("video")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                        </div>

                                        <div class="row mb-3 property-create-textarea">
                                            <div class="col-md-12">
                                                <label for="" class="form-label">@lang("Description")</label>
                                                <textarea class="form-control" name="details" id="summernote"
                                                          rows="30" cols="50"
                                                          style="height: 1000px !important;">{{ old("details") }}</textarea>
                                                @error("details")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3 property-create-textarea">
                                            <div class="col-md-12">
                                                <a href="javascript:;" class="js-create-field form-link"
                                                   id="faqGenerate">
                                                    <i class="bi-plus"></i> @lang('Add FAQ')
                                                </a>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="addedFaqField">
                                                    @php
                                                        $oldFaqCounts = old('faq_title') ? count(old('faq_title')) : 0;
                                                    @endphp

                                                    @if($oldFaqCounts > 1)
                                                        @for($i = 1; $i < $oldFaqCounts; $i++)
                                                            <div class="row mb-3" id="removeFaqField{{ $i }}">
                                                                <div class="col-12 col-sm-auto col-md-6">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                               name="faq_title[]"
                                                                               value="{{ old('faq_title.'.$i) }}"
                                                                               placeholder="@lang('question')">
                                                                    </div>
                                                                </div>


                                                                <div class="col-12 col-sm-auto col-md-6">
                                                                    <div class="input-group">
                                                                        <input type="text" name="faq_details[]"
                                                                               class="form-control"
                                                                               value="{{ old('faq_details.'.$i) }}"
                                                                               placeholder="@lang('Answer')">


                                                                        <span class="input-group-btn">
                                                                    <button class="custom-delete-add-field-btn"
                                                                            type="button"
                                                                            onclick="deleteFaqField({{ $i }})">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    @endif

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12">
                            <div class="d-grid gap-3 gap-lg-5">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title h4">@lang("Add Investment Details")</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div
                                                class="col-md-6 mb-4 fixedAmount d-none {{ old('is_invest_type') == "1" ? 'd-block' : 'd-none' }}">
                                                <label for="fixed_amount"
                                                       class="form-label">@lang("Fixed Invest Amount")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="fixed_amount" value="{{ old("fixed_amount") }}"
                                                           id="fixedAmount"
                                                           placeholder="@lang("0.00")" autocomplete="off">
                                                </div>

                                                @error("fixed_amount")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div
                                                class="col-md-6 mb-4 rangeAmount {{ old('is_invest_type') == "1" ? 'd-none' : '' }}">
                                                <label for="minimum_amount"
                                                       class="form-label">@lang("Minimum Invest Amount")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="minimum_amount" value="{{ old("minimum_amount") }}"
                                                           placeholder="@lang("0.00")" autocomplete="off">
                                                </div>
                                                @error("minimum_amount")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div
                                                class="col-md-6 mb-4 rangeAmount {{ old('is_invest_type') == "1" ? 'd-none' : '' }}">
                                                <label for="maximum_amount"
                                                       class="form-label">@lang("Maximum Invest Amount")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           name="maximum_amount" value="{{ old("maximum_amount") }}"
                                                           placeholder="@lang("0.00")" autocomplete="off">
                                                </div>
                                                @error("maximum_amount")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <label for="total_investment_amount"
                                                       class="form-label">@lang("Total Invest Amount")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           name="total_investment_amount"
                                                           value="{{ old("total_investment_amount") }}"
                                                           placeholder="@lang("0.00")"
                                                           autocomplete="off">
                                                </div>
                                                @error("total_investment_amount")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <label for="profit"
                                                       class="form-label">@lang("Profit")</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           class="form-control" value="{{ old('profit') }}"
                                                           name="profit" placeholder="0.00">

                                                    <div class="input-group-append">
                                                        <select name="profit_type" id="profit_type"
                                                                class="form-control">
                                                            <option value="1">%</option>
                                                            <option
                                                                value="0">{{ basicControl()->currency_symbol }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @error("profit")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div
                                                class="col-md-6 mb-4 installmentField {{ old('is_invest_type') == "1" && old('is_installment') == "1" ? '' : 'd-none' }}">
                                                <label for="total_installments"
                                                       class="form-label">@lang("Total Installments")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="total_installments"
                                                           id="totalInstallments"
                                                           value="{{ old("total_installments") }}"
                                                           placeholder="@lang("min 1")" autocomplete="off">
                                                </div>
                                                @error("total_installments")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div
                                                class="col-md-6 mb-4 installmentField {{ old('is_invest_type') == "1" && old('is_installment') == "1" ? '' : 'd-none' }}">
                                                <label for="installment_amount"
                                                       class="form-label">@lang("Installment Amount")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="installment_amount"
                                                           id="installmentAmount"
                                                           value="{{ old("installment_amount") }}"
                                                           placeholder="@lang("0.00")" autocomplete="off">
                                                </div>
                                                @error("installment_amount")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div
                                                class="col-md-6 mb-4 installmentField {{ old('is_invest_type') == "1" && old('is_installment') == "1" ? '' : 'd-none' }}">
                                                <label for="installment_duration"
                                                       class="form-label">@lang("Installment Duration")</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           class="form-control expiry_time" name="installment_duration"
                                                           value="{{ old('installment_duration') }}"
                                                           placeholder="@lang('min 1')" min="1">

                                                    <div class="input-group-append">
                                                        <select name="installment_duration_type"
                                                                id="installment_duration_type"
                                                                class="form-control installment_duration_type">
                                                            <option
                                                                value="Days" {{ old('installment_duration_type') == 'Days' ? 'selected' : '' }}>@lang('Day(s)')</option>
                                                            <option
                                                                value="Months" {{ old('installment_duration_type') == 'Months' ? 'selected' : '' }}>@lang('Month(s)')</option>
                                                            <option
                                                                value="Years" {{ old('installment_duration_type') == 'Years' ? 'selected' : '' }}>@lang('Year(s)')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @error("installment_duration")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div
                                                class="col-md-6 mb-4 installmentField {{ old('is_invest_type') == "1" && old('is_installment') == "1" ? '' : 'd-none' }}">
                                                <label for="installment_late_fee"
                                                       class="form-label">@lang("Installment Late Fee")</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           class="form-control" name="installment_late_fee"
                                                           placeholder="@lang('0.00')"
                                                           value="{{ old('installment_late_fee') }}">

                                                    <div class="input-group-append">
                                                        <span
                                                            class="input-group-text">{{ basicControl()->currency_symbol }}</span>
                                                    </div>
                                                </div>
                                                @error("installment_late_fee")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div
                                                class="col-md-6 mb-4 howManyTimes {{ old('is_return_type') == "0" ? '' : 'd-none' }}">
                                                <label for="how_many_times"
                                                       class="form-label">@lang("how many times get profit?")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="how_many_times"
                                                           id="installmentAmount"
                                                           value="{{ old("how_many_times") }}"
                                                           placeholder="@lang("min 1")" autocomplete="off">
                                                </div>
                                                @error("how_many_times")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <label for="how_many_days"
                                                       class="form-label">@lang("Profit Return Schedule") (<span
                                                        class="text-primary font-weight-normal font-14">@lang('after how many days')</span>)</label>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select" autocomplete="off"
                                                            id="how_many_days"
                                                            name="how_many_days"
                                                            data-hs-tom-select-options='{
                                                      "placeholder": "Select a Period",
                                                      "hideSearch": true
                                                    }'>
                                                        <option value=""></option>
                                                        @foreach($schedules as $schedule)
                                                            <option
                                                                value="{{ $schedule->id }}" {{ old('how_many_days') == $schedule->id ? 'selected' : ''}}>@lang($schedule->time) @lang($schedule->time_type)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error("how_many_days")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-md-6 mb-4">
                                                <label for="start_date" class="form-label">@lang("Investment Start")</label>

                                                <div class="flatpickr">
                                                    <div class="input-group input-box">
                                                        <input type="date" placeholder="@lang('Start Date')"
                                                               class="form-control start_date"
                                                               name="start_date"
                                                               value="{{ old('start_date',request()->start_date) }}"
                                                               data-input>
                                                        <div class="input-group-append" readonly="">
                                                            <div class="form-control">
                                                                <a class="input-button cursor-pointer" title="clear"
                                                                   data-clear>
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        @error("start_date")
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="expire_date"
                                                       class="form-label">@lang("Investment End")</label>
                                                <div class="flatpickr">
                                                    <div class="input-group input-box">
                                                        <input type="date" placeholder="@lang('End Date')"
                                                               class="form-control expire_date"
                                                               name="expire_date"
                                                               value="{{ old('expire_date',request()->expire_date) }}"
                                                               data-input>
                                                        <div class="input-group-append" readonly="">
                                                            <div class="form-control">
                                                                <a class="input-button cursor-pointer" title="clear"
                                                                   data-clear>
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        @error("expire_date")
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4">
                    <div class="d-grid gap-3 gap-lg-5 res-order">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Publish")</h2>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-start gap-2">
                                    <button type="submit" class="btn btn-primary">@lang("Save")</button>
                                    <a href="{{ route('admin.properties', 'all') }}"
                                       class="btn btn-info">@lang("Cancel")</a>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Property Thumbnail")</h2>
                            </div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-12 mb-3 mb-md-0">
                                        <label class="form-check form-check-dashed" for="ImageUploader">
                                            <img id="propertyThumbnail"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset("assets/admin/img/oc-browse-file.svg") }}"
                                                 alt="@lang("Property Thumbnail")"
                                                 data-hs-theme-appearance="default">

                                            <img id="propertyThumbnail"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset("assets/admin/img/oc-browse-file-light.svg") }}"
                                                 alt="@lang("Property Thumbnail")"
                                                 data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="thumbnail" id="ImageUploader"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#propertyThumbnail",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                           }'>
                                        </label>
                                        @error("thumbnail")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Property Galary Images")</h2>
                            </div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-12 mb-3 mb-md-0">
                                        <div class="property-image"></div>

                                        @error('property_image.*')
                                        <span class="text-danger">@lang($message)</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Status")</h2>
                            </div>
                            <div class="card-body">
                                <label class="row form-check form-switch mb-4" for="breadcrumbSwitch">
                                    <span class="col-8 col-sm-9 ms-0">
                                      <span class="text-dark">@lang("Is Featured Property")
                                          <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                             data-bs-placement="top"
                                             aria-label="@lang("Enable for available also home page")"
                                             data-bs-original-title="@lang("Enable for available also home page")"></i></span>
                                    </span>
                                    <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input type="checkbox" class="form-check-input" name="is_featured"
                                               id="is_featured" value="1" {{ old('is_featured') == "1" ? 'checked' : ''}}>
                                    </span>
                                </label>

                                <label class="row form-check form-switch" for="breadcrumbSwitch">
                                    <span class="col-8 col-sm-9 ms-0">
                                      <span class="text-dark">@lang("Is Available Fund")
                                          <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                             data-bs-placement="top"
                                             aria-label="@lang("Can investors see property available funds for investment")"
                                             data-bs-original-title="@lang("Can investors see property available funds for investment")"></i>
                                      </span>
                                    </span>
                                    <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="is_available_funding" value="0">
                                        <input type="checkbox" class="form-check-input" name="is_available_funding"
                                               id="is_available_funding" value="1" {{ old('is_available_funding') == "1" ? 'checked' : ''}}>
                                    </span>
                                </label>

                                <label class="row form-check form-switch mt-5" for="breadcrumbSwitch">
                                    <label class="col-sm-4 col-form-label form-label">@lang('Invest Type') <i
                                            class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="@lang("Money can be invested either fixed or within a range")"
                                            data-bs-original-title="@lang("Money can be invested either fixed or within a range")"></i>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="input-group input-group-sm-vertical">
                                            <!-- Radio Check -->
                                            <label class="form-control" for="investTypeRange">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input isInvestType"
                                                           name="is_invest_type"
                                                           id="investTypeRange"
                                                           {{ old('is_invest_type') == "0" ? 'checked' : ''}} value="{{ old('is_invest_type', 0) }}"
                                                           checked>
                                                    <span class="form-check-label">@lang('Range')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->

                                            <!-- Radio Check -->
                                            <label class="form-control" for="investTypeFixed">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input isInvestType"
                                                           name="is_invest_type"
                                                           id="investTypeFixed"
                                                           {{ old('is_invest_type') == "1" ? 'checked' : ''}} value="{{ old('is_invest_type', 1) }}">
                                                    <span class="form-check-label">@lang('Fixed')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->
                                        </div>
                                    </div>


                                </label>

                                <label
                                    class="row form-check form-switch mt-5 acceptInstallments {{ old('is_invest_type') == "1" ? '' : 'd-none' }}"
                                    for="breadcrumbSwitch">
                                    <label class="col-sm-4 col-form-label form-label">
                                        @lang('Installments')
                                        <i
                                            class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="@lang("Is there an opportunity to repay the money invested in a particular property in stages or not")"
                                            data-bs-original-title="@lang("Is there an opportunity to repay the money invested in a particular property in stages or not?")"></i>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="input-group input-group-sm-vertical">
                                            <!-- Radio Check -->
                                            <label class="form-control" for="isInstallmentYes">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input isInstallment"
                                                           name="is_installment"
                                                           id="isInstallmentYes"
                                                           {{ old('is_installment') == "1" ? 'checked' : ''}} value="1">
                                                    <span class="form-check-label">@lang('Yes')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->

                                            <!-- Radio Check -->
                                            <label class="form-control" for="isInstallmentNo">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input isInstallment"
                                                           name="is_installment"
                                                           id="isInstallmentNo"
                                                           {{ old('is_installment') == "0" ? 'checked' : ''}} value="0">
                                                    <span class="form-check-label">@lang('No')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->
                                        </div>
                                    </div>
                                </label>


                                <label class="row form-check form-switch mt-5" for="breadcrumbSwitch">
                                    <label class="col-sm-4 col-form-label form-label">
                                        @lang('Return Type')
                                        <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           aria-label="@lang("Will you get the profit on the investment for a certain period of time or will you continue to get it for life")"
                                           data-bs-original-title="@lang("Will you get the profit on the investment for a certain period of time or will you continue to get it for life")"></i>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="input-group input-group-sm-vertical">
                                            <!-- Radio Check -->
                                            <label class="form-control" for="returnTypeLifeTime">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input isReturnType"
                                                           name="is_return_type"
                                                           id="returnTypeLifeTime"
                                                           {{ old('is_return_type') == "1" ? 'checked' : '' }} value="1"
                                                           checked>
                                                    <span class="form-check-label">@lang('Lifetime')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->

                                            <!-- Radio Check -->
                                            <label class="form-control" for="returnTypePeriod">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input isReturnType"
                                                           name="is_return_type"
                                                           id="returnTypePeriod"
                                                           {{ old('is_return_type') == "0" ? 'checked' : '' }} value="0">
                                                    <span class="form-check-label"> <br>@lang('Period')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->
                                        </div>
                                    </div>
                                </label>

                                <label class="row form-check form-switch mt-5" for="breadcrumbSwitch">
                                    <label class="col-sm-4 col-form-label form-label">
                                        @lang('Is Sell Investment')
                                        <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           aria-label="@lang("Can the investor sell his shares to another investor or not?")"
                                           data-bs-original-title="@lang("Can the investor sell his shares to another investor or not?")"></i>
                                    </label>

                                    <div class="col-sm-8">
                                        <div class="input-group input-group-sm-vertical">
                                            <!-- Radio Check -->
                                            <label class="form-control" for="isInvestorYes">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input isInvestor" name="is_investor"
                                                           id="isInvestorYes" {{ old('is_investor') == "1" ? 'checked' : '' }} value="1">
                                                    <span class="form-check-label">@lang('Yes')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->

                                            <!-- Radio Check -->
                                            <label class="form-control" for="isInvestorNo">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input" name="is_investor"
                                                           id="isInvestorNo" {{ old('is_investor') == "0" ? 'checked' : '' }} value="0">
                                                    <span class="form-check-label"> @lang('No')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->
                                        </div>
                                    </div>
                                </label>

                                <label class="row form-check form-switch mt-5" for="breadcrumbSwitch">
                                    <label class="col-sm-4 col-form-label form-label">
                                        @lang('Capital Back')
                                        <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           aria-label="@lang("Can the investor sell his shares to another investor or not?")"
                                           data-bs-original-title="@lang("Can the investor sell his shares to another investor or not?")"></i>
                                    </label>

                                    <div class="col-sm-8">
                                        <div class="input-group input-group-sm-vertical">
                                            <!-- Radio Check -->
                                            <label class="form-control" for="capitalBackYes">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input" name="is_capital_back"
                                                           id="capitalBackYes" {{ old('is_capital_back') == "1" ? 'checked' : '' }} value="1">
                                                    <span class="form-check-label">@lang('Yes')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->

                                            <!-- Radio Check -->
                                            <label class="form-control" for="capitalBackNo">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input" name="is_capital_back"
                                                           id="capitalBackNo" {{ old('is_capital_back') == "0" ? 'checked' : '' }} value="0">
                                                    <span class="form-check-label"> @lang('No')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->
                                        </div>
                                    </div>

                                </label>

                                <label class="row form-check form-switch mt-5" for="breadcrumbSwitch">
                                    <span class="col-8 col-sm-9 ms-0">
                                      <span class="text-dark">@lang("Status")
                                          <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                             data-bs-placement="top"
                                             aria-label="@lang("Enable or Disable Property Status")"
                                             data-bs-original-title="@lang("Enable or Disable Property Status")"></i></span>
                                    </span>
                                    <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" class="form-check-input" name="status"
                                               id="status" value="1"
                                               {{ old('status') == "1" ? 'checked' : '' }} checked>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.css') }}"/>
    <link href="{{ asset('assets/global/css/flatpickr.min.css') }}" rel="stylesheet">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
    <script src="{{ asset('assets/global/js/image-uploader.js') }}"></script>
    <script src="{{ asset('assets/global/js/flatpickr.js') }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function () {

            $(".flatpickr").flatpickr({
                wrap: true,
                enableTime: true,
                altInput: true,
                dateFormat: "Y-m-d H:i",
            });


            HSCore.components.HSTomSelect.init('.js-select');
            new HSFileAttach('.js-file-attach')
            $(document).on('input', ".change_name_input", function (e) {
                let inputValue = $(this).val();
                let final_value = inputValue.toLowerCase().replace(/\s+/g, '-');
                $('.set-slug').val(final_value);
            });

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

            let propertyImageOptions = {
                imagesInputName: 'property_image',
                label: 'Drag & Drop files here or click to browse images',
                extensions: ['.jpg', '.jpeg', '.png'],
                mimes: ['image/jpeg', 'image/png'],
                maxSize: 5242880
            };

            $('.property-image').imageUploader(propertyImageOptions);


            $(document).on('input', '#totalInstallments', function () {
                let total_installments = $('#totalInstallments').val();
                let fixed_amount = $('#fixedAmount').val();
                let installment_amount = parseInt(fixed_amount) / parseInt(total_installments);
                let final_installment_amount = installment_amount.toFixed(2);
                $('#installmentAmount').val(final_installment_amount);
            });


            $(document).on('change', '.isInvestType', function () {
                let isCheck = $(this).val();
                if (isCheck == 0) {
                    $('.rangeAmount').addClass('d-block');
                    $('.rangeAmount').removeClass('d-none');
                    $('.fixedAmount').removeClass('d-block');
                    $('.fixedAmount').addClass('d-none');
                    $('.acceptInstallments').addClass('d-none');
                    $('.installmentField').addClass('d-none');
                    $('#isInstallmentNo').prop('checked', false);
                    $('#isInstallmentYes').prop('checked', false);
                } else {
                    $('.rangeAmount').addClass('d-none');
                    $('.rangeAmount').removeClass('d-block');
                    $('.fixedAmount').removeClass('d-none');
                    $('.acceptInstallments').removeClass('d-none');
                    $('#isInstallmentNo').prop('checked', true);
                    $('#isInstallmentYes').prop('checked', false);
                }
            });

            $(document).on('change', '.isReturnType', function () {
                var isCheck = $(this).val();
                if (isCheck == 1) {
                    $('.howManyTimes').removeClass('d-block');
                    $('.howManyTimes').addClass('d-none');
                } else {
                    $('.howManyTimes').removeClass('d-none');
                    $('.howManyTimes').addClass('d-block');
                }
            });

            $(document).on('change', '.isInstallment', function () {
                let isCheck = $(this).val();
                if (isCheck == 1) {
                    $('.installmentField').removeClass('d-none');
                } else {
                    $('.installmentField').addClass('d-none');
                }
            });


            $("#faqGenerate").on('click', function () {
                const id = Date.now();
                var form = `<div class="row mb-3" id="removeFaqField${id}">
                                <div class="col-12 col-sm-auto col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               name="faq_title[]"
                                               placeholder="@lang('question')">
                                    </div>
                                </div>


                                <div class="col-12 col-sm-auto col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="faq_details[]"
                                               class="form-control"
                                               placeholder="@lang('Answer')">


                                        <span class="input-group-btn">
                                            <button class="custom-delete-add-field-btn"
                                                    type="button"
                                                    onclick="deleteFaqField(${id})">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>`;

                $('.addedFaqField').append(form)
            });

        });

        function deleteFaqField(id) {
            $(`#removeFaqField${id}`).remove();
        }
    </script>
@endpush








