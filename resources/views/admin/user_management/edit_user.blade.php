@extends('admin.layouts.app')
@section('page_title',__('Edit User'))
@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('User Management')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit User')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit @'. $user->username . ' Profile')</h1>
                </div>
                <div class="col-sm-auto">
                    <a class="btn btn-primary" href="{{ route('admin.user.view.profile', $user->id) }}">
                        <i class="bi-eye-fill me-1"></i> @lang('View Profile')
                    </a>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-3">
                <div class="navbar-expand-lg navbar-vertical mb-3 mb-lg-5">
                    <div class="d-grid">
                        <button type="button" class="navbar-toggler btn btn-white mb-3" data-bs-toggle="collapse"
                                data-bs-target="#navbarVerticalNavMenu" aria-label="Toggle navigation"
                                aria-expanded="false" aria-controls="navbarVerticalNavMenu">
                                <span class="d-flex justify-content-between align-items-center">
                                  <span class="text-dark">@lang('Menu')</span>
                                  <span class="navbar-toggler-default">
                                    <i class="bi-list"></i>
                                  </span>
                                  <span class="navbar-toggler-toggled">
                                    <i class="bi-x"></i>
                                </span>
                            </span>
                        </button>
                    </div>

                    <div id="navbarVerticalNavMenu" class="collapse navbar-collapse">
                        <ul id="navbarSettings"
                            class="js-sticky-block js-scrollspy card card-navbar-nav nav nav-tabs nav-lg nav-vertical"
                            data-hs-sticky-block-options='{
                             "parentSelector": "#navbarVerticalNavMenu",
                             "targetSelector": "#header",
                             "breakpoint": "lg",
                             "startPoint": "#navbarVerticalNavMenu",
                             "endPoint": "#stickyBlockEndPoint",
                             "stickyOffsetTop": 20
                           }'>
                            <li class="nav-item">
                                <a class="nav-link active" href="#content">
                                    <i class="bi-person nav-icon"></i> @lang('Basic information')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#emailSection">
                                    <i class="bi-at nav-icon"></i> @lang('Email')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#usernameSection">
                                    <i class="bi bi-person nav-icon"></i>@lang('Username')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#passwordSection">
                                    <i class="bi-key nav-icon"></i> @lang('Password')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#preferencesSection">
                                    <i class="bi-gear nav-icon"></i> @lang('Preferences')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#twoStepVerificationSection">
                                    <i class="bi-shield-lock nav-icon"></i> @lang('Two-step verification')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#recentDevicesSection">
                                    <i class="bi-phone nav-icon"></i> @lang('Recent devices')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#deleteAccountSection">
                                    <i class="bi-trash nav-icon"></i> @lang('Delete account')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="col-lg-9">
                <div class="d-grid gap-3 gap-lg-5">
                    <form action="{{ route('admin.user.update', $user->id) }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="profile-cover">
                            <div class="profile-cover-img-wrapper">
                                <img id="profileCoverImg" class="profile-cover-img"
                                     src="{{ asset('assets/admin/img/img1.jpg') }}"
                                     alt="Image Description">
                            </div>
                        </div>

                        <label class="avatar avatar-xxl avatar-circle avatar-uploader profile-cover-avatar"
                               for="editAvatarUploaderModal">
                            <img id="editAvatarImgModal" class="avatar-img"
                                 src="{{ getFile($user->image_driver, $user->image) }}"
                                 alt="Image Description">
                            <input type="file" class="js-file-attach avatar-uploader-input" id="editAvatarUploaderModal"
                                   name="image"
                                   data-hs-file-attach-options='{
                                    "textTarget": "#editAvatarImgModal",
                                    "mode": "image",
                                    "targetAttr": "src",
                                    "allowTypes": [".png", ".jpeg", ".jpg"]
                                 }'>
                            <span class="avatar-uploader-trigger">
                          <i class="bi-pencil-fill avatar-uploader-icon shadow-sm"></i>
                        </span>
                        </label>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang('Basic information')</h2>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <label for="firstNameLabel"
                                           class="col-sm-3 col-form-label form-label">@lang('Full name')</label>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-sm-vertical">
                                            <input type="text" class="form-control" name="firstName" id="firstNameLabel"
                                                   placeholder="First name" aria-label="First name"
                                                   value="{{ old('firstName', $user->firstname) }}" autocomplete="off">
                                            <input type="text" class="form-control" name="lastName" id="lastNameLabel"
                                                   placeholder="Last name" aria-label="Last name"
                                                   value="{{ old('lastName', $user->lastname) }}" autocomplete="off">
                                        </div>
                                        @error('firstName')
                                        <span class="invalid-feedback d-inline">{{ $message }}</span>
                                        @enderror
                                        @error('lastName')
                                        <span class="invalid-feedback d-inline">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-4">
                                    <label for="phoneLabel"
                                           class="col-sm-3 col-form-label form-label">@lang('Phone')</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="js-input-mask form-control" name="phone"
                                               id="phoneLabel" placeholder="Phone"
                                               aria-label="Phone" value="{{ old('phone', $user->phone) }}"
                                               autocomplete="off">
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
                                            <select class="js-select form-select" id="locationLabel" name="country">
                                                @forelse($allCountry as $country)
                                                    <option value="{{ $country['name'] }}"
                                                            {{ $country['name'] == $user->country ? 'selected' : '' }}
                                                            data-option-template='<span class="d-flex align-items-center"><img class="avatar avatar-xss avatar-circle me-2" src="{{ asset($country['flag']) }}" alt="Afghanistan Flag" /><span class="text-truncate">{{ $country['name'] }}</span></span>'>
                                                        @lang($country['name'])
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('country')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control" name="city" id="cityLabel"
                                                       placeholder="City" aria-label="City"
                                                       value="{{ old('city', $user->city) }}" autocomplete="off">
                                                <input type="text" class="form-control" name="state" id="stateLabel"
                                                       placeholder="State" aria-label="State"
                                                       value="{{ old('state', $user->state) }}">

                                                <input type="text" class="js-input-mask form-control" name="zipCode"
                                                       id="zipCodeLabel" placeholder="Zip code" aria-label="Zip code"
                                                       value="{{ old('zipCode', $user->zip_code) }}" autocomplete="off">
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
                                               id="addressLine1Label" placeholder="Address One"
                                               aria-label="Your address"
                                               value="{{ old('addressOne', $user->address_one) }}" autocomplete="off">
                                        @error('addressOne')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>


                                <div class="row mb-4">
                                    <label for="addressLine2Label" class="col-sm-3 col-form-label form-label">
                                        @lang('Address line 2')
                                        <span class="form-label-secondary">(@lang("Optional"))</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="addressTwo"
                                               id="addressLine2Label" placeholder="Address Two"
                                               aria-label="Address Two"
                                               value="{{ old('addressTwo', $user->address_two) }}" autocomplete="off">
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
                                             id="userStatusSwitch" value="1" {{ $user->status == 1 ? 'checked' : '' }}>
                                    </span>
                                </label>
                                @error('status')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror

                                <div class="d-flex justify-content-start">
                                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                </div>
                            </div>
                        </div>
                        <!-- End Card -->
                    </form>


                    @include('admin.user_management.components.email_section')
                    @include('admin.user_management.components.username_section')
                    @include('admin.user_management.components.password_section')

                    @include('admin.user_management.components.preferences_section')

                    @include('admin.user_management.components.two_step_verify_section')

                    @include('admin.user_management.components.recent_devices_section')

                    @include('admin.user_management.components.delete_account_section')

                </div>
                <div id="stickyBlockEndPoint"></div>
            </div>
        </div>
    </div>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-sticky-block.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-scrollspy.min.js') }}"></script>
@endpush


@push('script')
    <script>
        $(document).ready(function () {
            $('.delete-btn').prop('disabled', true);
            $('#deleteAccountCheckbox').on('change', function() {
                let checkboxValue = $(this).prop('checked');
                $('.delete-btn').prop('disabled', !checkboxValue);
            });

            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250
            })

            new HSFileAttach('.js-file-attach')
            new HSStickyBlock('.js-sticky-block', {
                targetSelector: document.getElementById('header').classList.contains('navbar-fixed') ? '#header' : null
            })
            new bootstrap.ScrollSpy(document.body, {
                target: '#navbarSettings',
                offset: 100
            })
            new HSScrollspy('#navbarVerticalNavMenu', {
                breakpoint: 'lg',
                scrollOffset: -20
            })
        })

    </script>
@endpush





