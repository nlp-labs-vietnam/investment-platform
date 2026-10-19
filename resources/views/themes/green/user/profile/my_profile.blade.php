@extends(template().'layouts.user')
@section('title',trans('Profile Settings'))

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrapicons-iconpicker.css') }}">
@endpush

@section('content')
    <!-- profile setting -->

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Profile')</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Account-settings -->
        <!-- Account settings navbar start -->
        <div class="account-settings-navbar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('user.profile') }}"><i
                            class="fa-regular fa-user"></i>@lang('profile')</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.password.setting') }}">
                        <i class="fa-light fa-key" aria-hidden="true"></i>
                        @lang('Password Settings')
                    </a>
                </li>

                @foreach($kyc as $key => $k)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.verification.kyc.form', $k->id) }}"><i
                                class="fa-regular fa-link"></i> @lang($k->name)</a>
                    </li>
                @endforeach

            </ul>
        </div>
        <!-- Account settings navbar end -->
        <form action="{{ route('user.updateInformation')}}" method="post" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="account-settings-profile-section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Profile Details
                        </h5>
                        <div class="profile-details-section">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="image-area">
                                    <img id="profile-img" src="{{getFile($user->image_driver, $user->image)}}"
                                         alt="NLP Group">
                                </div>
                                <div class="btn-area">
                                    <div class="btn-area-inner d-flex">
                                        <div class="cmn-file-input">
                                            <button for="formFile" class="cmn-btn">@lang('Upload Photo')</button>
                                            <input class="form-control" type="file" name="image" accept="image/*"
                                                   id="formFile"
                                                   onchange="previewImage('profile-img')">
                                        </div>
                                        <button type="button" class="cmn-btn3"
                                                onclick="resetPreviewImage()">reset
                                        </button>
                                    </div>
                                    <small>@lang('Allowed JPG, JPEG or PNG. Max size of 5MB')</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">

                        <div class="profile-form-section">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="firstname" class="form-label">@lang('First Name')</label>
                                    <input type="text"
                                           class="form-control"
                                           name="firstname"
                                           id="firstname"
                                           placeholder="@lang('first name')"
                                           value="{{old('firstname')?: $user->firstname }}"/>
                                    @if($errors->has('firstname'))
                                        <div
                                            class="error text-danger">@lang($errors->first('firstname'))
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="lastname" class="form-label">@lang('Last Name')</label>
                                    <input type="text"
                                           id="lastname"
                                           name="lastname"
                                           placeholder="@lang('last name')"
                                           class="form-control"
                                           value="{{old('lastname')?: $user->lastname }}"/>
                                    @if($errors->has('lastname'))
                                        <div
                                            class="error text-danger">@lang($errors->first('lastname'))
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="e-mail" class="form-label">@lang('Username')</label>
                                    <input type="text"
                                           id="username"
                                           name="username"
                                           placeholder="@lang('username')"
                                           value="{{old('username')?: $user->username }}"
                                           class="form-control"/>
                                    @if($errors->has('username'))
                                        <div
                                            class="error text-danger">@lang($errors->first('username'))
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="organization" class="form-label">@lang('Email Address')</label>
                                    <input type="email"
                                           id="email"
                                           placeholder="@lang('email')"
                                           value="{{ $user->email }}"
                                           readonly
                                           class="form-control"/>
                                    @if($errors->has('email'))
                                        <div
                                            class="error text-danger">@lang($errors->first('email'))
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="phonenumber" class="form-label">@lang('Phone Number')</label>
                                    <input type="text"
                                           name="phone"
                                           id="phone"
                                           placeholder="@lang('phone')"
                                           class="form-control"
                                           value="{{ old('phone', $user->phone) }}"/>
                                    @if($errors->has('phone'))
                                        <div
                                            class="error text-danger">@lang($errors->first('phone'))
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">@lang('Preferred language')</label>
                                    <select class="cmn-select2"
                                            name="language_id"
                                            id="language_id"
                                            aria-label="Default select example">
                                        <option value="" disabled>@lang('Select Language')</option>
                                        @foreach($languages as $la)
                                            <option
                                                value="{{$la->id}}" {{ old('language_id', $user->language_id) == $la->id ? 'selected' : '' }}> @lang($la->name)</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('language_id'))
                                        <div
                                            class="error text-danger">@lang($errors->first('language_id'))
                                        </div>
                                    @endif
                                </div>


                                <div class="col-md-12">
                                    <label for="">@lang('Address')</label>
                                    <input
                                        class="form-control @error('address') is-invalid @enderror"
                                        id="address"
                                        name="address"
                                        type="text"
                                        placeholder="@lang('address')"
                                        value="{{ old('address', $user->address) }}"/>
                                    @if($errors->has('address'))
                                        <div
                                            class="error text-danger">@lang($errors->first('address'))
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label for="">@lang('Bio')</label>
                                    <textarea
                                        class="form-control @error('Bio') is-invalid @enderror"
                                        cols="30"
                                        rows="3"
                                        placeholder="@lang('bio')"
                                        name="bio">@lang($user->bio)</textarea>
                                    @if($errors->has('bio'))
                                        <div
                                            class="error text-danger">@lang($errors->first('bio'))
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label for="">@lang('Social Links')</label>
                                    <div class="form website_social_links">
                                        @php
                                            $oldSocialCounts = max(old('social_icon', $social_links) ? count(old('social_icon', $social_links)) : 1, 1);
                                        @endphp

                                        @if($oldSocialCounts > 0)
                                            @for($i = 0; $i < $oldSocialCounts; $i++)
                                                <div
                                                    class="d-flex justify-content-between append_new_social_form removeSocialLinksInput">
                                                    <div class="input-group mt-1 me-3">
                                                        <input type="text" name="social_icon[]"
                                                               value="{{ old("social_icon.$i", $social_links[$i]->social_icon ?? '') }}"
                                                               class="form-control demo__icon__picker iconpicker1 @error("social_icon.$i") is-invalid @enderror"
                                                               placeholder="Pick a icon"
                                                               aria-label="Pick a icon"
                                                               aria-describedby="basic-addon1" readonly>

                                                        <div class="invalid-feedback">
                                                            @error("social_icon.$i") @lang($message) @enderror
                                                        </div>
                                                    </div>

                                                    <div class="input-group w-100 my-1 me-1">
                                                        <input type="url" name="social_url[]"
                                                               value="{{ old("social_url.$i", $social_links[$i]->social_url ?? '') }}"
                                                               class="form-control @error("social_url.$i") is-invalid @enderror"
                                                               placeholder="@lang('URL')"/>
                                                        @error("social_url.$i")
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror

                                                    </div>
                                                    <div class="my-1 me-1">
                                                        @if($i == 0)
                                                            <button class="cmn-btn add-new" type="button"
                                                                    id="add_social_links">
                                                                <i class="fa-light fa-plus"></i>
                                                            </button>
                                                        @else
                                                            <button
                                                                class="cmn-btn add-new btn-custom-danger remove_social_link_input_field"
                                                                type="button">
                                                                <i class="fa fa-xmark"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif

                                        <div class="new_social_links_form append_new_social_form">

                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="btn-area d-flex g-3">
                                <button type="submit" class="cmn-btn">@lang('save changes')</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection


@push('extra-js')
    <script src="{{ asset('assets/global/js/bootstrapicon-iconpicker.js') }}"></script>
@endpush

@push('script')

    <script>
        // input file preview & reset start
        const originalImageSrc = document.getElementById('profile-img').src;
        const previewImage = (id) => {
            document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
        };
        const resetPreviewImage = () => {
            document.getElementById('profile-img').src = originalImageSrc;
        }

        // input file preview & reset end
    </script>

    <script>
        'use strict'
        $(document).ready(function () {
            let curIconFirst = $($(`#iconpicker1`)).data('icon');
            setIconpicker('.iconpicker1');

            function setIconpicker(selector = '.iconpicker1') {
                $(selector).iconpicker({
                    title: 'Search Social Icons',
                    selected: false,
                    defaultValue: false,
                    placement: "top",
                    collision: "none",
                    animation: true,
                    hideOnSelect: true,
                    showFooter: false,
                    searchInFooter: false,
                    mustAccept: false,
                    icons: [{
                        title: "bi bi-facebook",
                        searchTerms: ["facebook", "text"]
                    }, {
                        title: "bi bi-twitter",
                        searchTerms: ["twitter", "text"]
                    }, {
                        title: "bi bi-linkedin",
                        searchTerms: ["linkedin", "text"]
                    }, {
                        title: "bi bi-youtube",
                        searchTerms: ["youtube", "text"]
                    }, {
                        title: "bi bi-instagram",
                        searchTerms: ["instagram", "text"]
                    }, {
                        title: "bi bi-whatsapp",
                        searchTerms: ["whatsapp", "text"]
                    }, {
                        title: "bi bi-discord",
                        searchTerms: ["discord", "text"]
                    }, {
                        title: "bi bi-globe",
                        searchTerms: ["website", "text"]
                    }, {
                        title: "bi bi-google",
                        searchTerms: ["google", "text"]
                    }, {
                        title: "bi bi-camera-video",
                        searchTerms: ["vimeo", "text"]
                    }, {
                        title: "bi bi-skype",
                        searchTerms: ["skype", "text"]
                    }, {
                        title: "bi bi-camera-video-fill",
                        searchTerms: ["tiktalk", "text"]
                    }, {
                        title: "bi bi-badge-tm-fill",
                        searchTerms: ["tumbler", "text"]
                    }, {
                        title: "bi bi-blockquote-left",
                        searchTerms: ["blogger", "text"]
                    }, {
                        title: "bi bi-file-word-fill",
                        searchTerms: ["wordpress", "text"]
                    }, {
                        title: "bi bi-badge-wc",
                        searchTerms: ["weixin", "text"]
                    }, {
                        title: "bi bi-telegram",
                        searchTerms: ["telegram", "text"]
                    }, {
                        title: "bi bi-bell-fill",
                        searchTerms: ["snapchat", "text"]
                    }, {
                        title: "bi bi-three-dots",
                        searchTerms: ["flickr", "text"]
                    }, {
                        title: "bi bi-file-ppt",
                        searchTerms: ["pinterest", "text"]
                    }],
                    selectedCustomClass: "bg-primary",
                    fullClassFormatter: function (e) {
                        return e;
                    },
                    input: "input,.iconpicker-input",
                    inputSearch: false,
                    container: false,
                    component: ".input-group-addon,.iconpicker-component",
                })
            }

            let newSocialForm = $('.append_new_social_form').length + 1;
            for (let i = 2; i <= newSocialForm; i++) {
                setIconpicker(`#iconpicker${i}`);
            }

            $("#add_social_links").on('click', function () {
                let newSocialForm = $('.append_new_social_form').length + 2;
                var form = `<div class="d-flex justify-content-between append_new_social_form removeSocialLinksInput">
                                <div class="input-group mt-1 me-3">
                                    <input type="text" name="social_icon[]" class="form-control demo__icon__picker iconpicker${newSocialForm}" placeholder="Pick a icon" aria-label="Pick a icon"
                                   aria-describedby="basic-addon1" readonly>
                                </div>

                                <div class="input-box w-100 my-1 me-1">
                                    <input type="url" name="social_url[]" class="form-control" placeholder="@lang('URL')"/>
                                </div>
                                <div class="my-1 me-1">
                                    <button class="cmn-btn add-new btn-custom-danger remove_social_link_input_field" type="button">
                                        <i class="fa fa-xmark"></i>
                                    </button>
                                </div>
                            </div>`;

                $('.new_social_links_form').append(form)
                setIconpicker(`.iconpicker${newSocialForm}`);
            });

            $(document).on('click', '.remove_social_link_input_field', function () {
                $(this).parents('.removeSocialLinksInput').remove();
            });

        });
    </script>

@endpush
