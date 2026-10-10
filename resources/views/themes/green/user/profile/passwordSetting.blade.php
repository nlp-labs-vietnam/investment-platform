@extends(template().'layouts.user')
@section('title',trans('Password Settings'))


@section('content')
    <!-- profile setting -->

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Password Setting')</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Account-settings -->
        <!-- Account settings navbar start -->
        <div class="account-settings-navbar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{ route('user.profile') }}"><i
                            class="fa-regular fa-user"></i>@lang('profile')</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('user.password.setting') }}">
                        <i class="fal fa-key" aria-hidden="true"></i>
                        @lang('Password Settings')
                    </a>
                </li>

                @foreach($kycs as $key => $k)
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('user.verification.kyc.form', $k->id) }}"><i
                                class="fa-regular fa-link"></i> @lang($k->name)</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Account settings navbar end -->
        <form method="post" action="{{ route('user.updatePassword') }}">
            @csrf
            <div class="account-settings-profile-section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Update Password')</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="profile-form-section">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="">@lang('Current Password')</label>
                                    <input type="password"
                                           id="current_password"
                                           name="current_password"
                                           autocomplete="off"
                                           class="form-control"
                                           placeholder="@lang('Enter Current Password')"/>
                                    @if($errors->has('current_password'))
                                        <div
                                            class="error text-danger">@lang($errors->first('current_password'))</div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="">@lang('New Password')</label>
                                    <input type="password"
                                           id="password"
                                           name="password"
                                           autocomplete="off"
                                           class="form-control"
                                           placeholder="@lang('Enter New Password')"/>
                                    @if($errors->has('password'))
                                        <div class="error text-danger">@lang($errors->first('password'))</div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation">@lang('Confirm Password')</label>
                                    <input type="password"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           autocomplete="off"
                                           class="form-control"
                                           placeholder="@lang('Confirm Password')"/>
                                    @if($errors->has('password_confirmation'))
                                        <div
                                            class="error text-danger">@lang($errors->first('password_confirmation'))</div>
                                    @endif
                                </div>



                            </div>
                            <div class="btn-area d-flex g-3">
                                <button type="submit" class="cmn-btn">@lang('Update Password')</button>
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
                                        <i class="fa fa-times"></i>
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
