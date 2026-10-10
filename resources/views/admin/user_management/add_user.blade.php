@extends('admin.layouts.app')
@section('page_title',__('Add User'))
@section('content')
    <div class="content container-fluid">
        <form class="js-step-form py-md-5" data-hs-step-form-options='{
              "progressSelector": "#addUserStepFormProgress",
              "stepsSelector": "#addUserStepFormContent",
              "endSelector": "#addUserFinishBtn",
              "isValidate": false
            }' action="{{ route('admin.user.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-lg-center">
                <div class="col-lg-8">
                    <ul id="addUserStepFormProgress"
                        class="js-step-progress step step-sm step-icon-sm step step-inline step-item-between mb-3 mb-md-5">
                        <li class="step-item">
                            <a class="step-content-wrapper " href="javascript:void(0)" data-hs-step-form-next-options='{
                                    "targetSelector": "#addUserStepProfile"
                                  }'>
                                <span class="step-icon step-icon-soft-dark">1</span>
                                <div class="step-content">
                                    <span class="step-title">@lang('Profile')</span>
                                </div>
                            </a>
                        </li>
                        <li class="step-item">
                            <a class="step-content-wrapper" href="javascript:void(0);" data-hs-step-form-next-options='{
                                    "targetSelector": "#addUserStepConfirmation"
                                  }'>
                                <span class="step-icon step-icon-soft-dark">2</span>
                                <div class="step-content">
                                    <span class="step-title">@lang('Confirmation')</span>
                                </div>
                            </a>
                        </li>
                    </ul>

                    <div id="addUserStepFormContent">
                        <div id="addUserStepProfile" class="card card-lg active">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <label class="col-sm-3 col-form-label form-label">@lang('Profile Image')</label>
                                    <div class="col-sm-9">
                                        <div class="d-flex align-items-center">
                                            <label class="avatar avatar-xl avatar-circle avatar-uploader me-5"
                                                   for="avatarUploader">
                                                <img id="avatarImg" class="avatar-img"
                                                     src="{{ asset('assets/admin/img/img-profile-avatar.jpg') }}"
                                                     alt="Image Description">
                                                <input type="file" class="js-file-attach avatar-uploader-input"
                                                       name="image"
                                                       id="avatarUploader" data-hs-file-attach-options='{
                                                        "textTarget": "#avatarImg",
                                                        "mode": "image",
                                                        "targetAttr": "src",
                                                        "resetTarget": ".js-file-attach-reset-img",
                                                        "resetImg": "{{ asset('assets/admin/img/img-profile-avatar.jpg') }}",
                                                        "allowTypes": [".png", ".jpeg", ".jpg"]
                                                     }'>
                                                <span class="avatar-uploader-trigger">
                                                    <i class="bi-pencil avatar-uploader-icon shadow-sm"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Form -->
                                <div class="row mb-4">
                                    <label for="firstNameLabel"
                                           class="col-sm-3 col-form-label form-label">@lang('First name')</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-field" name="firstName"
                                               id="firstNameLabel"
                                               placeholder="First name" aria-label="First name"
                                               data-target=".full_name"
                                               value="{{ old('firstName') }}" autocomplete="off">
                                        @error('firstName')
                                        <span class="invalid-feedback d-inline">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="firstNameLabel"
                                           class="col-sm-3 col-form-label form-label">@lang('Last Name')</label>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-sm-vertical">
                                            <input type="text" class="form-control input-field" name="lastName"
                                                   id="lastNameLabel"
                                                   placeholder="Last name" aria-label="Last name"
                                                   data-target=".full_name"
                                                   value="{{ old('lastName') }}" autocomplete="off">
                                        </div>
                                        @error('lastName')
                                        <span class="invalid-feedback d-inline">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="userNameLabel"
                                           class="col-sm-3 col-form-label form-label">@lang('Username')</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="username" id="userNameLabel"
                                               value="{{ old("username") }}"
                                               placeholder="@lang("Username")" aria-label="@lang("Username")"
                                               autocomplete="off">
                                        @error('username')
                                        <span class="invalid-feedback d-inline">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="emailLabel"
                                           class="col-sm-3 col-form-label form-label">@lang('Email')</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="email" id="emailLabel"
                                               placeholder="@lang("Email")"
                                               aria-label="@lang("Email")"
                                               autocomplete="off" value="{{ old("email") }}" required>
                                        @error('email')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="phoneLabel"
                                           class="col-sm-3 col-form-label form-label">@lang('Phone')</label>
                                    <div class="col-sm-9">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="spanPhoneCode"></span>

                                            <input type="text" class="js-input-mask form-control" name="phone"
                                                   id="phoneLabel" placeholder="Phone"
                                                   value="{{ old('phone') }}"
                                                   autocomplete="off">
                                        </div>
                                        @error('phone')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="locationLabel"
                                           class="col-sm-3 col-form-label form-label">@lang('Country')</label>
                                    <div class="col-sm-9">
                                        <div class="tom-select-custom mb-4">
                                            <select class="js-select form-select countryChange" id="locationLabel" name="country">
                                                @forelse($allCountry as $country)
                                                    <option value="{{ $country['name'] }}"
                                                            data-country-code="{{ $country['code'] }}"
                                                            data-phone-code="{{ $country['phone_code'] }}"
                                                            data-option-template='<span class="d-flex align-items-center"><img class="avatar avatar-xss avatar-circle me-2" src="{{ asset($country['flag']) }}" alt="Afghanistan Flag" /><span class="text-truncate">{{ $country['name'] }}</span></span>'>
                                                        @lang($country['name'])
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                            <input type="hidden" name="phone_code" id="phoneCode" value="{{ old('phone_code') }}"/>
                                            <input type="hidden" name="country" id="countryName" value="{{ old('country') }}"/>
                                            <input type="hidden" name="country_code" id="countryCode" value="{{ old('country_code') }}"/>
                                            @error('country')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control" name="city" id="cityLabel"
                                                       placeholder="City" aria-label="City"
                                                       value="{{ old('city') }}" autocomplete="off">
                                                <input type="text" class="form-control" name="state" id="stateLabel"
                                                       placeholder="@lang("State")" aria-label="@lang("State")"
                                                       value="{{ old('state') }}" autocomplete="off">
                                                <input type="text" class="js-input-mask form-control" name="zipCode"
                                                       id="zipCodeLabel" placeholder="Zip code" aria-label="Zip code"
                                                       value="{{ old('zipCode') }}" autocomplete="off">
                                            </div>
                                            @error('city')
                                            <span class="invalid-feedback d-inline">{{ $message }}</span>
                                            @enderror
                                            @error('state')
                                            <span class="invalid-feedback d-inline">{{ $message }}</span>
                                            @enderror
                                            @error('zipCode')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="addressLine1Label" class="col-sm-3 col-form-label form-label">
                                        @lang('Address line 1')</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="addressOne"
                                               id="addressLine1Label" placeholder="@lang('Address line 1')"
                                               aria-label="@lang('Address line 1')"
                                               value="{{ old('addressOne') }}" autocomplete="off">
                                        @error('addressOne')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="addressLine2Label" class="col-sm-3 col-form-label form-label">
                                        @lang('Address line 2')
                                        <span class="form-label-secondary"> (@lang('Optional'))</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="addressTwo"
                                               id="addressLine2Label" placeholder="@lang('Address line 2 (optional)')"
                                               aria-label="Address Two"
                                               value="{{ old('addressTwo') }}" autocomplete="off">
                                        @error('addressTwo')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <label class="row form-check form-switch mb-4" for="userStatusSwitch">
                                    <span class="col-8 col-sm-3 ms-0">
                                      <span class="d-block text-dark">@lang('Status')</span>
                                    </span>
                                    <span class="col-4 col-sm-3">
                                         <input type="hidden" name="status" value="0">
                                      <input type="checkbox" class="form-check-input" name="status"
                                             id="userStatusSwitch" value="1" checked>
                                    </span>
                                </label>
                                @error('status')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="card-footer d-flex justify-content-end align-items-center">
                                <button type="button" class="btn btn-primary nextButton" data-hs-step-form-next-options='{
                                    "targetSelector": "#addUserStepConfirmation"
                                  }'>
                                    @lang('Next') <i class="bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        <div id="addUserStepConfirmation" class="card card-lg">
                            <div class="profile-cover">
                                <div class="profile-cover-img-wrapper">
                                    <img class="profile-cover-img" src="{{ asset('assets/admin/img/img1.jpg') }}"
                                         alt="Image Description">
                                </div>
                            </div>

                            <div class="avatar avatar-xxl avatar-circle avatar-border-lg profile-cover-avatar">
                                <img class="avatar-img" src="{{ asset('assets/admin/img/img-profile-avatar.jpg') }}"
                                     alt="Image Description">
                            </div>

                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-6 text-sm-end mb-2">@lang('Full name:')</dt>
                                    <dd class="col-sm-6 full_name">-</dd>

                                    <dt class="col-sm-6 text-sm-end mb-2">@lang('Username:')</dt>
                                    <dd class="col-sm-6 username">-</dd>

                                    <dt class="col-sm-6 text-sm-end mb-2">@lang('Email:')</dt>
                                    <dd class="col-sm-6 email">-</dd>

                                    <dt class="col-sm-6 text-sm-end mb-2">@lang('Phone:')</dt>
                                    <dd class="col-sm-6 phone">-</dd>

                                    <dt class="col-sm-6 text-sm-end mb-2">@lang('Country:')</dt>
                                    <dd class="col-sm-6 country">-</dd>

                                    <dt class="col-sm-6 text-sm-end mb-2">@lang('City:')</dt>
                                    <dd class="col-sm-6 city">-</dd>

                                    <dt class="col-sm-6 text-sm-end mb-2">@lang('State:')</dt>
                                    <dd class="col-sm-6 state">-</dd>

                                    <dt class="col-sm-6 text-sm-end mb-2">@lang('Address line 1:')</dt>
                                    <dd class="col-sm-6 address_line1">-</dd>

                                    <dt class="col-sm-6 text-sm-end mb-2">@lang('Address line 2:')</dt>
                                    <dd class="col-sm-6 address_line2">-</dd>

                                    <dt class="col-sm-6 text-sm-end mb-2">@lang('Zip code:')</dt>
                                    <dd class="col-sm-6 zip_code">-</dd>
                                </dl>
                            </div>

                            <div class="card-footer d-sm-flex align-items-sm-center">
                                <button type="button" class="btn btn-ghost-secondary mb-2 mb-sm-0"
                                        data-hs-step-form-prev-options='{
                                           "targetSelector": "#addUserStepProfile"
                                         }'>
                                    <i class="bi-chevron-left"></i> @lang('Previous step')
                                </button>
                                <div class="ms-auto">
                                    <button id="addUserFinishBtn" type="submit"
                                            class="btn btn-primary addUserBtn">@lang('Add user')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection


@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-step-form.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-add-field.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush


@push('script')
    <script>
        "use strict";
        $(document).on('change', '#avatarUploader', function () {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#avatarImg').attr('src', e.target.result);
                    $('.profile-cover-avatar .avatar-img').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        function updateFullName() {
            let firstName = $('#firstNameLabel').val();
            let lastName = $('#lastNameLabel').val();
            let fullName = firstName + ' ' + lastName;
            $('.full_name').text(fullName);

            let add = $('.addUserBtn');
            if (firstName.trim() === '' || lastName.trim() === '') {
                add.prop('disabled', true);
            } else {
                add.prop('disabled', false);
            }
        }

        $(document).on("input", "#firstNameLabel, #lastNameLabel", updateFullName);
        updateFullName();

        function updateEmailText() {
            let emailValue = $("#emailLabel").val();
            $('.email').text(emailValue);
            let add = $('.addUserBtn');
            if (emailValue.trim() === '') {
                add.prop('disabled', true);
            } else {
                add.prop('disabled', false);
            }
        }

        $(document).on("input", "#emailLabel", updateEmailText);
        updateEmailText();

        function updateUsernameText() {
            let userNameValue = $("#userNameLabel").val();
            let add = $('.addUserBtn');
            if (userNameValue.trim() === '') {
                add.prop('disabled', true);
            } else {
                add.prop('disabled', false);
            }
            $('.username').text(userNameValue);
        }

        $(document).on("input", "#userNameLabel", updateUsernameText);
        updateUsernameText();

        function updatePhoneText() {
            let phoneValue = $("#phoneLabel").val();
            let add = $('.addUserBtn');
            if (phoneValue.trim() === '') {
                add.prop('disabled', true);
            } else {
                add.prop('disabled', false);
            }
            $('.phone').text(phoneValue);
        }

        $(document).on("input", "#phoneLabel", updatePhoneText);
        updatePhoneText();

        $(document).on("change", "#locationLabel", function () {
            let countryValue = $("#locationLabel").val();
            let add = $('.addUserBtn');
            if (countryValue.trim() === '') {
                add.prop('disabled', true);
            } else {
                add.prop('disabled', false);
            }
            $('.country').text(countryValue);
        });

        function updateCityText() {
            let cityValue = $("#cityLabel").val();
            let countryValue = $("#locationLabel").val();
            let add = $('.addUserBtn');
            if (cityValue.trim() === '' && countryValue.trim() === '') {
                add.prop('disabled', true);
            } else {
                add.prop('disabled', false);
            }
            $('.city').text(cityValue);
        }

        $(document).on("input", "#cityLabel", updateCityText);
        updateCityText();

        function updateStateText() {
            let stateValue = $("#stateLabel").val();
            let add = $('.addUserBtn');
            if (stateValue.trim() === '') {
                add.prop('disabled', true);
            } else {
                add.prop('disabled', false);
            }
            $('.state').text(stateValue);
        }

        $(document).on("input", "#stateLabel", updateStateText);
        updateStateText();


        function updateZipCodeText() {
            let zipCodeValue = $("#zipCodeLabel").val();
            let add = $('.addUserBtn');
            if (zipCodeValue.trim() === '') {
                add.prop('disabled', true);
            } else {
                add.prop('disabled', false);
            }
            $('.zip_code').text(zipCodeValue);
        }

        $(document).on("input", "#zipCodeLabel", updateZipCodeText);
        updateZipCodeText();


        function updateAddressLine1() {
            let addressOneValue = $("#addressLine1Label").val();
            let add = $('.addUserBtn');
            if (addressOneValue.trim() === '') {
                add.prop('disabled', true);
            } else {
                add.prop('disabled', false);
            }
            $('.address_line1').text(addressOneValue);
        }

        $(document).on("input", "#addressLine1Label", updateAddressLine1);
        updateAddressLine1();

        function updateAddressLine2() {
            let addressTwoValue = $("#addressLine2Label").val();
            let add = $('.addUserBtn');
            if (addressTwoValue.trim() === '') {
                add.prop('disabled', true);
            } else {
                add.prop('disabled', false);
            }
            $('.address_line2').text(addressTwoValue);
        }

        $(document).on("input", "#addressLine2Label", updateAddressLine2);
        updateAddressLine2();

        $(document).ready(function () {
            new HSStepForm('.js-step-form', {
                finish: () => {
                    document.getElementById("addUserStepFormProgress").style.display = 'none'
                    document.getElementById("addUserStepProfile").style.display = 'none'
                    document.getElementById("addUserStepConfirmation").style.display = 'none'
                    scrollToTop('#header');
                    const formContainer = document.getElementById('formContainer')
                },
                onNextStep: function () {
                    scrollToTop()
                },
                onPrevStep: function () {
                    scrollToTop()
                }
            })

            function scrollToTop(el = '.js-step-form') {
                el = document.querySelector(el)
                window.scrollTo({
                    top: (el.getBoundingClientRect().top + window.scrollY) - 30,
                    left: 0,
                    behavior: 'smooth'
                })
            }

            new HSAddField('.js-add-field', {
                addedField: field => {
                    HSCore.components.HSTomSelect.init(field.querySelector('.js-select-dynamic'))
                    HSCore.components.HSMask.init(field.querySelector('.js-input-mask'))
                }
            })

            HSCore.components.HSTomSelect.init('.js-select', {
                render: {
                    'option': function (data, escape) {
                        return data.optionTemplate || `<div>${data.text}</div>>`
                    },
                    'item': function (data, escape) {
                        return data.optionTemplate || `<div>${data.text}</div>>`
                    }
                },
                maxOptions: 250
            })

            function updateCountryInfo() {
                let selectedOption = $('.countryChange option:selected');
                $('#phoneCode').val(selectedOption.data('phone-code'));
                $('#countryName').val(selectedOption.val());
                $('#countryCode').val(selectedOption.data('country-code'));
                $('#spanPhoneCode').text(selectedOption.data('phone-code'));
            }
            $('.countryChange').change(updateCountryInfo);
            updateCountryInfo();

        });

    </script>

@endpush




