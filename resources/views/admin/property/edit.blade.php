@extends('admin.layouts.app')
@section('page_title', __('Edit Property'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item">@lang('Manage Property')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("Edit Property")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Edit Property")</h1>
                </div>
            </div>
        </div>


        {{-- ── Tab Navigation ── --}}
        <ul class="nav nav-tabs mb-4" id="propertyEditTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-content-btn" data-bs-toggle="tab" data-bs-target="#tab-content" type="button" role="tab">
                    <i class="bi-file-text me-1"></i> @lang('Nội Dung')
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-images-btn" data-bs-toggle="tab" data-bs-target="#tab-images" type="button" role="tab">
                    <i class="bi-images me-1"></i> @lang('Hình Ảnh')
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-seo-btn" data-bs-toggle="tab" data-bs-target="#tab-seo" type="button" role="tab">
                    <i class="bi-search me-1"></i> @lang('SEO')
                </button>
            </li>
            @if(adminAccessRoute(config('permissionList.Manage_Property.Property_Revisions.permission.view')))
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-history-btn" data-bs-toggle="tab" data-bs-target="#tab-history" type="button" role="tab">
                    <i class="bi-clock-history me-1"></i> @lang('Lịch Sử')
                </button>
            </li>
            @endif
        </ul>

        <div class="tab-content" id="propertyEditTabsContent">

        {{-- ══════════════════════════════════
             TAB 1 — NỘI DUNG (form gốc)
        ══════════════════════════════════ --}}
        <div class="tab-pane fade show active" id="tab-content" role="tabpanel">
        <form action="{{ route("admin.property.update", $singleProperty->id) }}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="d-grid gap-3 gap-lg-5">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title h4">@lang("Edit Property Details")</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="title" class="form-label">@lang("Title")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control propertyTitle"
                                                           name="title"
                                                           value="{{ old("title", $singleProperty->title) }}"
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
                                                                value="{{ $address->id }}" {{ old('address_id', $singleProperty->address_id) == $address->id ? 'selected' : '' }}>@lang($address->title)</option>
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
                                                            <option value="{{ $amenity->id }}"
                                                                    @selected(in_array($amenity->id, $singleProperty->amenity_id??[]))>
                                                                @lang($amenity->title)
                                                            </option>
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
                                                           name="location"
                                                           value="{{ old("location", $singleProperty->location) }}"
                                                           placeholder="@lang('only embed url accepted')"
                                                           autocomplete="off">
                                                </div>
                                                @error("location")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-md-12">
                                                <label for="video" class="form-label">@lang("Video URL (YouTube embed)")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control change_name_input"
                                                           name="video"
                                                           value="{{ old("video", $singleProperty->video) }}"
                                                           placeholder="@lang('only iframe embed url accepted')"
                                                           autocomplete="off">
                                                </div>
                                                @error("video")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label for="video_file" class="form-label">
                                                    @lang("Video File (Upload lên server)")
                                                    <small class="text-muted ms-1">@lang("mp4, webm, ogg, mov, avi — tối đa 200 MB. Ưu tiên hơn URL YouTube.")</small>
                                                </label>
                                                @if(!empty($singleProperty->video_file))
                                                <div class="mb-2">
                                                    <video controls style="max-width:320px;max-height:180px;border-radius:6px;">
                                                        <source src="{{ getFile($singleProperty->video_driver ?? 'local', $singleProperty->video_file) }}"
                                                                type="video/mp4">
                                                    </video>
                                                    <p class="small text-muted mt-1">@lang("Video hiện tại. Upload file mới để thay thế.")</p>
                                                </div>
                                                @endif
                                                <input type="file" class="form-control"
                                                       name="video_file" id="video_file"
                                                       accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-msvideo">
                                                @error("video_file")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                        </div>

                                        <div class="row mb-3 property-create-textarea">
                                            <div class="col-md-12">
                                                <label for="" class="form-label">@lang("Description")</label>
                                                <textarea class="form-control" name="details" id="summernote"
                                                          rows="30" cols="50"
                                                          style="height: 1000px !important;">{{ old("description", $singleProperty->details) }}</textarea>
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

                                        @if(!empty($singleProperty->faq))
                                            @php
                                                $i = 0;
                                            @endphp
                                            @foreach($singleProperty->faq as $key => $value)
                                                @php
                                                    $i++
                                                @endphp
                                                <div class="row mb-3" id="removeFaqField{{ $i }}">
                                                    <div class="col-12 col-sm-auto col-md-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                   name="faq_title[]"
                                                                   value="{{ @$value->field_name }}"
                                                                   placeholder="@lang('question')">
                                                        </div>
                                                    </div>


                                                    <div class="col-12 col-sm-auto col-md-6">
                                                        <div class="input-group">
                                                            <input type="text" name="faq_details[]"
                                                                   class="form-control"
                                                                   value="{{ @$value->field_value }}"
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
                                            @endforeach
                                        @endif

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
                                                class="col-md-6 mb-4 fixedAmount {{ old('is_invest_type', $singleProperty->is_invest_type) == "1" ? 'd-block' : 'd-none' }}">
                                                <label for="fixed_amount"
                                                       class="form-label">@lang("Fixed Invest Amount")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="fixed_amount"
                                                           value="{{ old("fixed_amount", nullAmount($singleProperty->fixed_amount)) }}"
                                                           id="fixedAmount"
                                                           placeholder="@lang("0.00")" autocomplete="off">
                                                </div>

                                                @error("fixed_amount")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div
                                                class="col-md-6 mb-4 rangeAmount {{ old('is_invest_type', $singleProperty->is_invest_type) == "1" ? 'd-none' : '' }}">
                                                <label for="minimum_amount"
                                                       class="form-label">@lang("Minimum Invest Amount")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="minimum_amount"
                                                           value="{{ old("minimum_amount", nullAmount($singleProperty->minimum_amount)) }}"
                                                           placeholder="@lang("0.00")" autocomplete="off">
                                                </div>
                                                @error("minimum_amount")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div
                                                class="col-md-6 mb-4 rangeAmount {{ old('is_invest_type', $singleProperty->is_invest_type) == "1" ? 'd-none' : '' }}">
                                                <label for="maximum_amount"
                                                       class="form-label">@lang("Maximum Invest Amount")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           name="maximum_amount"
                                                           value="{{ old("maximum_amount", nullAmount($singleProperty->maximum_amount)) }}"
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
                                                           value="{{ old("total_investment_amount", nullAmount($singleProperty->total_investment_amount)) }}"
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
                                                           class="form-control"
                                                           value="{{ old('profit', fractionNumber($singleProperty->profit)) }}"
                                                           name="profit" placeholder="0.00">

                                                    <div class="input-group-append">
                                                        <select name="profit_type" id="profit_type"
                                                                class="form-control">
                                                            <option
                                                                value="1" {{ $singleProperty->profit_type == 1 ? 'selected' : '' }}>
                                                                %
                                                            </option>
                                                            <option
                                                                value="0" {{ $singleProperty->profit_type == 0 ? 'selected' : '' }}>{{ basicControl()->currency_symbol }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @error("profit")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div
                                                class="col-md-6 mb-4 installmentField {{ old('is_invest_type', $singleProperty->is_invest_type) == "1" && old('is_installment', $singleProperty->is_installment) == "1" ? '' : 'd-none' }}">
                                                <label for="total_installments"
                                                       class="form-label">@lang("Total Installments")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="total_installments"
                                                           id="totalInstallments"
                                                           value="{{ old("total_installments", $singleProperty->total_installments) }}"
                                                           placeholder="@lang("min 1")" autocomplete="off">
                                                </div>
                                                @error("total_installments")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div
                                                class="col-md-6 mb-4 installmentField {{ old('is_invest_type', $singleProperty->is_invest_type) == "1" && old('is_installment', $singleProperty->is_installment) == "1" ? '' : 'd-none' }}">
                                                <label for="installment_amount"
                                                       class="form-label">@lang("Installment Amount")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="installment_amount"
                                                           id="installmentAmount"
                                                           value="{{ old("installment_amount", nullAmount($singleProperty->installment_amount)) }}"
                                                           placeholder="@lang("0.00")" autocomplete="off">
                                                </div>
                                                @error("installment_amount")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div
                                                class="col-md-6 mb-4 installmentField {{ old('is_invest_type', $singleProperty->is_invest_type) == "1" && old('is_installment', $singleProperty->is_installment) == "1" ? '' : 'd-none' }}">
                                                <label for="installment_duration"
                                                       class="form-label">@lang("Installment Duration")</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           class="form-control expiry_time" name="installment_duration"
                                                           value="{{ old('installment_duration', $singleProperty->installment_duration) }}"
                                                           placeholder="@lang('min 1')" min="1">

                                                    <div class="input-group-append">
                                                        <select name="installment_duration_type"
                                                                id="installment_duration_type"
                                                                class="form-control installment_duration_type">
                                                            <option
                                                                value="Days" {{ old('installment_duration_type', $singleProperty->installment_duration_type) == 'Days' ? 'selected' : '' }}>@lang('Day(s)')</option>
                                                            <option
                                                                value="Months" {{ old('installment_duration_type', $singleProperty->installment_duration_type) == 'Months' ? 'selected' : '' }}>@lang('Month(s)')</option>
                                                            <option
                                                                value="Years" {{ old('installment_duration_type', $singleProperty->installment_duration_type) == 'Years' ? 'selected' : '' }}>@lang('Year(s)')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @error("installment_duration")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div
                                                class="col-md-6 mb-4 installmentField {{ old('is_invest_type', $singleProperty->is_invest_type) == "1" && old('is_installment', $singleProperty->is_installment) == "1" ? '' : 'd-none' }}">
                                                <label for="installment_late_fee"
                                                       class="form-label">@lang("Installment Late Fee")</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           class="form-control" name="installment_late_fee"
                                                           placeholder="@lang('0.00')"
                                                           value="{{ old('installment_late_fee', nullAmount($singleProperty->installment_late_fee)) }}">

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
                                                class="col-md-6 mb-4 howManyTimes {{ old('is_return_type', $singleProperty->is_return_type) == "0" ? '' : 'd-none' }}">
                                                <label for="how_many_times"
                                                       class="form-label">@lang("how many times get profit?")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="how_many_times"
                                                           id="installmentAmount"
                                                           value="{{ old("how_many_times", $singleProperty->how_many_times) }}"
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
                                                                value="{{ $schedule->id }}" {{ old('how_many_days', $singleProperty->how_many_days) == $schedule->id ? 'selected' : ''}}>@lang($schedule->time) @lang($schedule->time_type)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error("how_many_days")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-md-6 mb-4">
                                                <label for="start_date"
                                                       class="form-label">@lang("Investment Start")</label>

                                                <div class="flatpickr">
                                                    <div class="input-group input-box">
                                                        <input type="date" placeholder="@lang('Start Date')"
                                                               class="form-control start_date"
                                                               name="start_date"
                                                               value="{{ old('start_date', $singleProperty->start_date) }}"
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
                                                               value="{{ old('expire_date', $singleProperty->expire_date) }}"
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
                                    <button type="submit" class="btn btn-primary">@lang("Update")</button>
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
                                                 src="{{ getFile($singleProperty->driver, $singleProperty->thumbnail) }}"
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
                                             data-bs-original-title="@lang("Enable for available also home page")"></i>
                                      </span>
                                    </span>
                                    <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="is_featured" value="0" {{ old('is_featured', $singleProperty->is_featured) == "0" ? 'checked' : '' }}>
                                        <input type="checkbox" class="form-check-input" name="is_featured"
                                               id="is_featured" value="1" {{ old('is_featured', $singleProperty->is_featured) == "1" ? 'checked' : ''}}>
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
                                        <input type="hidden" name="is_available_funding" value="0" {{ old('is_available_funding', $singleProperty->is_available_funding) == "0" ? 'checked' : '' }}>
                                        <input type="checkbox" class="form-check-input" name="is_available_funding"
                                               id="is_available_funding" value="1" {{ old('is_available_funding', $singleProperty->is_available_funding) == "1" ? 'checked' : '' }}>
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
                                                           value="0"
                                                           {{ old('is_invest_type', $singleProperty->is_invest_type) == "0" ? 'checked' : ''}}>
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
                                                           value="1"
                                                           {{ old('is_invest_type', $singleProperty->is_invest_type) == "1" ? 'checked' : ''}}>
                                                    <span class="form-check-label">@lang('Fixed')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->
                                        </div>
                                    </div>


                                </label>

                                <label
                                    class="row form-check form-switch mt-5 acceptInstallments {{ old('is_invest_type', $singleProperty->is_invest_type) == "1" ? '' : 'd-none' }}"
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
                                                           {{ old('is_installment', $singleProperty->is_installment) == "1" ? 'checked' : ''}} value="1">
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
                                                           {{ old('is_installment', $singleProperty->is_installment) == "0" ? 'checked' : ''}} value="0">
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
                                                           {{ old('is_return_type', $singleProperty->is_return_type) == "1" ? 'checked' : '' }} value="1"
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
                                                           {{ old('is_return_type', $singleProperty->is_return_type) == "0" ? 'checked' : '' }} value="0">
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
                                                    <input type="radio" class="form-check-input" name="is_investor"
                                                           id="isInvestorYes" {{ old('is_investor', $singleProperty->is_investor) == "1" ? 'checked' : '' }} value="1">
                                                    <span class="form-check-label">@lang('Yes')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->

                                            <!-- Radio Check -->
                                            <label class="form-control" for="isInvestorNo">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input" name="is_investor"
                                                           id="isInvestorNo" {{ old('is_investor', $singleProperty->is_investor) == "0" ? 'checked' : '' }} value="0">
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
                                                           id="capitalBackYes" {{ old('is_capital_back', $singleProperty->is_capital_back) == "1" ? 'checked' : '' }} value="1">
                                                    <span class="form-check-label">@lang('Yes')</span>
                                                  </span>
                                            </label>
                                            <!-- End Radio Check -->

                                            <!-- Radio Check -->
                                            <label class="form-control" for="capitalBackNo">
                                                  <span class="form-check">
                                                    <input type="radio" class="form-check-input" name="is_capital_back"
                                                           id="capitalBackNo" {{ old('is_capital_back', $singleProperty->is_capital_back) == "0" ? 'checked' : '' }} value="0">
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
                                               id="status" value="1" {{ old('status', $singleProperty->status) == "1" ? 'checked' : '' }}>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>{{-- /tab-pane #tab-content --}}

        {{-- ══════════════════════════════════
             TAB 2 — HÌNH ẢNH
        ══════════════════════════════════ --}}
        <div class="tab-pane fade" id="tab-images" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    {{-- Thumbnail --}}
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2 class="card-title h4 mb-0">@lang('Property Thumbnail')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.property.update', $singleProperty->id) }}" method="post" enctype="multipart/form-data" id="thumbnailForm">
                                @csrf
                                <input type="hidden" name="_thumbnail_only" value="1">
                                <div class="d-flex align-items-start gap-4 flex-wrap">
                                    <img id="thumbPreview"
                                         src="{{ getFile($singleProperty->driver, $singleProperty->thumbnail) }}"
                                         class="rounded border"
                                         style="width:200px;height:130px;object-fit:cover;"
                                         alt="@lang('Thumbnail')">
                                    <div>
                                        <label class="form-label">@lang('Replace Thumbnail')</label>
                                        <input type="file" class="form-control" name="thumbnail" id="thumbInput"
                                               accept=".jpg,.jpeg,.png">
                                        <small class="text-muted d-block mt-1">@lang('JPG/PNG, max 5MB. Auto-converted to WebP.')</small>
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">@lang('Save Thumbnail')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Gallery Images --}}
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2 class="card-title h4 mb-0">@lang('Gallery Images')</h2>
                            @if(adminAccessRoute(config('permissionList.Manage_Property.Property_Images.permission.add')))
                            <label class="btn btn-sm btn-outline-primary mb-0">
                                <i class="bi-plus-circle me-1"></i> @lang('Add Images')
                                <input type="file" id="galleryUploadInput" multiple accept=".jpg,.jpeg,.png" class="d-none">
                            </label>
                            @endif
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">@lang('Drag cards to reorder. Click the pencil to edit alt text / caption. Click the crop icon to crop.')</p>
                            <div id="galleryGrid" class="row g-3">
                                @foreach($singleProperty->image as $img)
                                <div class="col-6 col-md-4 gallery-item" data-id="{{ $img->id }}">
                                    <div class="card h-100 border">
                                        <div class="position-relative">
                                            <img src="{{ asset(getFile($img->driver, $img->image)) }}"
                                                 class="card-img-top"
                                                 style="height:130px;object-fit:cover;"
                                                 alt="{{ $img->alt_text ?? '' }}">
                                            <div class="position-absolute top-0 end-0 p-1 d-flex gap-1">
                                                @if(adminAccessRoute(config('permissionList.Manage_Property.Property_Images.permission.edit')))
                                                <button type="button" class="btn btn-xs btn-light btn-crop-img"
                                                        data-id="{{ $img->id }}"
                                                        data-src="{{ asset(getFile($img->driver, $img->image)) }}"
                                                        title="@lang('Crop')">
                                                    <i class="bi-crop"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-light btn-edit-meta"
                                                        data-id="{{ $img->id }}"
                                                        data-alt="{{ $img->alt_text }}"
                                                        data-caption="{{ $img->caption }}"
                                                        title="@lang('Edit meta')">
                                                    <i class="bi-pencil"></i>
                                                </button>
                                                @endif
                                                @if(adminAccessRoute(config('permissionList.Manage_Property.Property_Images.permission.delete')))
                                                <button type="button" class="btn btn-xs btn-danger btn-delete-img"
                                                        data-id="{{ $img->id }}"
                                                        title="@lang('Delete')">
                                                    <i class="bi-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body p-2">
                                            <p class="small text-muted mb-0 text-truncate" title="{{ $img->alt_text }}">
                                                {{ $img->alt_text ?: '—' }}
                                            </p>
                                            <p class="small text-muted mb-0 text-truncate" title="{{ $img->caption }}">
                                                {{ $img->caption ?: '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal: Edit Alt / Caption --}}
            <div class="modal fade" id="metaModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang('Edit Image Meta')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="metaImageId">
                            <div class="mb-3">
                                <label class="form-label">@lang('Alt Text')</label>
                                <input type="text" class="form-control" id="metaAltText" maxlength="191">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">@lang('Caption')</label>
                                <input type="text" class="form-control" id="metaCaption" maxlength="255">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                            <button type="button" class="btn btn-primary" id="saveMetaBtn">@lang('Save')</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal: Crop --}}
            <div class="modal fade" id="cropModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang('Crop Image')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <input type="hidden" id="cropImageId">
                            <img id="cropImageEl" src="" style="max-width:100%;">
                        </div>
                        <div class="modal-footer justify-content-between">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="cropRatio169">16:9</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="cropRatio43">4:3</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="cropRatio11">1:1</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="cropRatioFree">@lang('Free')</button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                                <button type="button" class="btn btn-primary" id="saveCropBtn">@lang('Save Cropped')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Inline Upload Form (hidden) - dùng AJAX --}}
            <form id="galleryUploadForm" action="{{ route('admin.property.update', $singleProperty->id) }}"
                  method="post" enctype="multipart/form-data" class="d-none">
                @csrf
                <input type="hidden" name="_gallery_only" value="1">
                <input type="file" name="property_image[]" id="galleryUploadFiles" multiple>
            </form>
        </div>{{-- /tab-pane #tab-images --}}

        {{-- ══════════════════════════════════
             TAB 3 — SEO
        ══════════════════════════════════ --}}
        <div class="tab-pane fade" id="tab-seo" role="tabpanel">
            @php $seo = $singleProperty->seo; @endphp
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4 mb-0">@lang('SEO Meta')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.property.seo.store', $singleProperty->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">@lang('Meta Title') <small class="text-muted">(@lang('max 60 chars'))</small></label>
                                    <input type="text" class="form-control" name="meta_title" maxlength="191"
                                           value="{{ old('meta_title', $seo->meta_title ?? $singleProperty->title) }}"
                                           id="seoMetaTitle">
                                    <div class="d-flex justify-content-end">
                                        <small class="text-muted"><span id="metaTitleCount">0</span>/60</small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">@lang('Meta Description') <small class="text-muted">(@lang('max 160 chars'))</small></label>
                                    <textarea class="form-control" name="meta_description" rows="3" maxlength="500"
                                              id="seoMetaDesc">{{ old('meta_description', $seo->meta_description ?? '') }}</textarea>
                                    <div class="d-flex justify-content-end">
                                        <small class="text-muted"><span id="metaDescCount">0</span>/160</small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">@lang('Custom Slug') <small class="text-muted">(@lang('optional'))</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text text-muted">/property/</span>
                                        <input type="text" class="form-control" name="slug"
                                               value="{{ old('slug', $seo->slug ?? \Illuminate\Support\Str::slug($singleProperty->title)) }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">@lang('OG Image') <small class="text-muted">(@lang('choose from gallery'))</small></label>
                                    <div class="d-flex flex-wrap gap-2" id="ogImagePicker">
                                        @foreach($singleProperty->image as $img)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="og_image_id" value="{{ $img->id }}"
                                                   class="d-none og-image-radio"
                                                {{ ($seo->og_image_id ?? null) == $img->id ? 'checked' : '' }}>
                                            <img src="{{ asset(getFile($img->driver, $img->image)) }}"
                                                 class="rounded border og-image-thumb {{ ($seo->og_image_id ?? null) == $img->id ? 'border-primary border-3' : '' }}"
                                                 style="width:80px;height:56px;object-fit:cover;"
                                                 alt="">
                                        </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Google SERP Preview --}}
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body py-2 px-3">
                                        <p class="small text-muted mb-1">@lang('Google preview')</p>
                                        <div id="serpTitle" class="text-primary fw-semibold" style="font-size:18px;"></div>
                                        <div class="text-success small">{{ url('/property') }}/<span id="serpSlug"></span></div>
                                        <div id="serpDesc" class="small text-muted"></div>
                                    </div>
                                </div>

                                @if(adminAccessRoute(config('permissionList.Manage_Property.Property_Seo.permission.edit')))
                                <button type="submit" class="btn btn-primary">@lang('Save SEO')</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>{{-- /tab-pane #tab-seo --}}

        {{-- ══════════════════════════════════
             TAB 4 — LỊCH SỬ
        ══════════════════════════════════ --}}
        @if(adminAccessRoute(config('permissionList.Manage_Property.Property_Revisions.permission.view')))
        <div class="tab-pane fade" id="tab-history" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2 class="card-title h4 mb-0">@lang('Revision History')</h2>
                            <small class="text-muted">@lang('Last 20 changes')</small>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('Editor')</th>
                                            <th>@lang('Summary')</th>
                                            <th>@lang('Date')</th>
                                            @if(adminAccessRoute(config('permissionList.Manage_Property.Property_Revisions.permission.edit')))
                                            <th>@lang('Action')</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody id="revisionsTbody">
                                        <tr><td colspan="5" class="text-center text-muted py-4">
                                            <span class="spinner-border spinner-border-sm me-2"></span> @lang('Loading...')
                                        </td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{{-- /tab-pane #tab-history --}}
        @endif

        </div>{{-- /tab-content --}}
    </div>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.css') }}"/>
    <link href="{{ asset('assets/global/css/flatpickr.min.css') }}" rel="stylesheet">
    {{-- Cropper.js --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
    <style>
        .btn-xs { padding: 2px 6px; font-size: 11px; line-height: 1.4; }
        .gallery-item { cursor: grab; }
        .gallery-item.sortable-ghost { opacity: .4; }
        .og-image-thumb.border-primary { box-shadow: 0 0 0 3px #0d6efd; }
        .cursor-pointer { cursor: pointer; }
    </style>
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
    <script src="{{ asset('assets/global/js/image-uploader.js') }}"></script>
    <script src="{{ asset('assets/global/js/flatpickr.js') }}"></script>
    {{-- SortableJS + Cropper.js (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
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

            var property_images = [
                    @foreach($singleProperty->image as $image)
                {
                    id: {{ $image->id }},
                    image_name: "{{ $image->image }}",
                    src: "{{ asset(getFile($image->driver, $image->image)) }}"
                },
                @endforeach
            ];

            let preloaded = [];
            property_images.forEach(function (value, index) {
                preloaded.push({
                    id: value.id,
                    image_name: value.image_name,
                    src: value.src
                });
            });

            let propertyImageOptions = {
                preloaded: preloaded,
                imagesInputName: 'property_image',
                preloadedInputName: 'old_property_image',
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

        // ── Thumbnail live preview ──────────────────────────────────────
        $('#thumbInput').on('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => $('#thumbPreview').attr('src', e.target.result);
                reader.readAsDataURL(file);
            }
        });

        // ── Gallery: SortableJS drag-and-drop ──────────────────────────
        const galleryGrid = document.getElementById('galleryGrid');
        if (galleryGrid) {
            Sortable.create(galleryGrid, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                handle: '.gallery-item',
                onEnd: function () {
                    const ids = [...galleryGrid.querySelectorAll('.gallery-item')].map(el => el.dataset.id);
                    $.ajax({
                        url: '{{ route("admin.property.image.reorder") }}',
                        method: 'PATCH',
                        data: { ids, _token: '{{ csrf_token() }}' },
                        success: () => toastr.success('{{ __("Image order saved.") }}'),
                        error: () => toastr.error('{{ __("Could not save order.") }}')
                    });
                }
            });
        }

        // ── Gallery: Delete image ──────────────────────────────────────
        $(document).on('click', '.btn-delete-img', function () {
            if (!confirm('{{ __("Delete this image?") }}')) return;
            const btn = $(this);
            const id = btn.data('id');
            $.ajax({
                url: `/admin/property/image/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function () {
                    btn.closest('.gallery-item').remove();
                    toastr.success('{{ __("Image deleted.") }}');
                },
                error: () => toastr.error('{{ __("Delete failed.") }}')
            });
        });

        // ── Gallery: Edit Meta (alt/caption) ──────────────────────────
        $(document).on('click', '.btn-edit-meta', function () {
            $('#metaImageId').val($(this).data('id'));
            $('#metaAltText').val($(this).data('alt') || '');
            $('#metaCaption').val($(this).data('caption') || '');
            new bootstrap.Modal('#metaModal').show();
        });

        $('#saveMetaBtn').on('click', function () {
            const id = $('#metaImageId').val();
            $.ajax({
                url: `/admin/property/image/${id}/meta`,
                method: 'PATCH',
                data: {
                    alt_text: $('#metaAltText').val(),
                    caption:  $('#metaCaption').val(),
                    _token:   '{{ csrf_token() }}'
                },
                success: function () {
                    bootstrap.Modal.getInstance(document.getElementById('metaModal')).hide();
                    toastr.success('{{ __("Metadata saved.") }}');
                },
                error: () => toastr.error('{{ __("Save failed.") }}')
            });
        });

        // ── Gallery: Crop ──────────────────────────────────────────────
        let cropperInstance = null;
        $(document).on('click', '.btn-crop-img', function () {
            const id  = $(this).data('id');
            const src = $(this).data('src');
            $('#cropImageId').val(id);
            const el = document.getElementById('cropImageEl');
            el.src = src;
            if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
            new bootstrap.Modal('#cropModal').show();
            document.getElementById('cropModal').addEventListener('shown.bs.modal', () => {
                cropperInstance = new Cropper(el, { aspectRatio: 16/9, viewMode: 1 });
            }, { once: true });
        });

        ['169', '43', '11'].forEach(key => {
            const ratios = { '169': 16/9, '43': 4/3, '11': 1 };
            $(`#cropRatio${key}`).on('click', () => cropperInstance && cropperInstance.setAspectRatio(ratios[key]));
        });
        $('#cropRatioFree').on('click', () => cropperInstance && cropperInstance.setAspectRatio(NaN));

        $('#saveCropBtn').on('click', function () {
            if (!cropperInstance) return;
            const id      = $('#cropImageId').val();
            const canvas  = cropperInstance.getCroppedCanvas({ maxWidth: 1920 });
            const imgData = canvas.toDataURL('image/webp', 0.9);
            $.ajax({
                url: `/admin/property/image/${id}/crop`,
                method: 'POST',
                data: { image_data: imgData, _token: '{{ csrf_token() }}' },
                success: function (res) {
                    // Cập nhật src ảnh trong gallery
                    $(`.gallery-item[data-id="${id}"] img`).attr('src', res.src + '?t=' + Date.now());
                    $(`.btn-crop-img[data-id="${id}"]`).data('src', res.src);
                    bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
                    toastr.success('{{ __("Image cropped.") }}');
                },
                error: () => toastr.error('{{ __("Crop failed.") }}')
            });
        });

        // ── Gallery: Add new images (upload) ──────────────────────────
        $('#galleryUploadInput').on('change', function () {
            const dt = new DataTransfer();
            [...this.files].forEach(f => dt.items.add(f));
            document.getElementById('galleryUploadFiles').files = dt.files;
            const form = document.getElementById('galleryUploadForm');
            const fd = new FormData(form);
            $.ajax({
                url: form.action,
                method: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: () => { toastr.success('{{ __("Images uploaded.") }}'); location.reload(); },
                error: () => toastr.error('{{ __("Upload failed.") }}')
            });
        });

        // ── SEO tab: character counters & SERP preview ────────────────
        function updateSerp() {
            const title = $('#seoMetaTitle').val() || '';
            const desc  = $('#seoMetaDesc').val()  || '';
            const slug  = $('input[name="slug"]').val() || '';
            $('#serpTitle').text(title.substring(0, 60));
            $('#serpDesc').text(desc.substring(0, 160));
            $('#serpSlug').text(slug);
            $('#metaTitleCount').text(title.length);
            $('#metaDescCount').text(desc.length);
        }
        $('#seoMetaTitle, #seoMetaDesc, input[name="slug"]').on('input', updateSerp);
        updateSerp();

        // OG image highlight on select
        $(document).on('change', '.og-image-radio', function () {
            $('.og-image-thumb').removeClass('border-primary border-3');
            $(this).siblings('img').addClass('border-primary border-3');
        });

        // ── Revision History lazy load ─────────────────────────────────
        $('#tab-history-btn').one('click', function () {
            $.get('{{ route("admin.property.revisions", $singleProperty->id) }}', function (data) {
                let html = '';
                if (!data.length) {
                    html = '<tr><td colspan="5" class="text-center text-muted py-3">{{ __("No revisions yet.") }}</td></tr>';
                } else {
                    data.forEach((r, i) => {
                        const canRollback = {{ adminAccessRoute(config('permissionList.Manage_Property.Property_Revisions.permission.edit')) ? 'true' : 'false' }};
                        html += `<tr>
                            <td>${i+1}</td>
                            <td>${r.editor_name || '—'}</td>
                            <td>${r.change_summary || '—'}</td>
                            <td>${r.created_at}</td>
                            ${canRollback ? `<td><button class="btn btn-xs btn-outline-warning btn-rollback" data-id="${r.id}">Rollback</button></td>` : ''}
                        </tr>`;
                    });
                }
                $('#revisionsTbody').html(html);
            });
        });

        $(document).on('click', '.btn-rollback', function () {
            if (!confirm('{{ __("Restore this version? Current data will be overwritten.") }}')) return;
            const id = $(this).data('id');
            $.post(`/admin/property/rollback/${id}`, { _token: '{{ csrf_token() }}' })
                .done(() => { toastr.success('{{ __("Rollback successful.") }}'); setTimeout(() => location.reload(), 1200); })
                .fail(() => toastr.error('{{ __("Rollback failed.") }}'));
        });
    </script>
@endpush
