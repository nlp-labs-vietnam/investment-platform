@extends('admin.layouts.app')
@section('page_title', __('Dashboard'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0);">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Profile Setting')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Profile Setting')</h1>
                </div>
            </div>
        </div>


        <div class="row d-flex justify-content-center">
            <div class="col-lg-9">
                <div class="d-grid gap-3 gap-lg-5">
                    <form action="{{ route("admin.profile.update") }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method("put")
                        <div>
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
                                     src="{{ getFile($admin->image_driver, $admin->image) }}"
                                     alt="Image Description">
                                <input type="file" class="js-file-attach avatar-uploader-input"
                                       id="editAvatarUploaderModal"
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
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Admin Information")</h2>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <label for="nameLabel"
                                           class="col-sm-3 col-form-label form-label">@lang("Name")</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" id="nameLabel"
                                               placeholder="@lang("Name")" aria-label="@lang("Name")"
                                               value="{{ old("name", $admin->name) }}"
                                               autocomplete="off">
                                        @error('name')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="userNameLabel"
                                           class="col-sm-3 col-form-label form-label">@lang("Username")</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="username" id="userNameLabel"
                                               placeholder="@lang("Username")" aria-label="@lang("Username")"
                                               value="{{ old("username", $admin->username) }}" autocomplete="off">
                                        @error('username')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="emailLabel"
                                           class="col-sm-3 col-form-label form-label">@lang("Email")</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" name="email" id="emailLabel"
                                               placeholder="@lang("Email")" aria-label="@lang("Email")"
                                               value="{{ old("email", $admin->email) }}">
                                        @error('email')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="phoneLabel"
                                           class="col-sm-3 col-form-label form-label">@lang("Phone")</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="phone" id="phoneLabel"
                                               placeholder="@lang("Phone")" aria-label="@lang("Phone")"
                                               value="{{ old("phone", $admin->phone) }}" autocomplete="off">
                                        @error('phone')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="addressLineLabel"
                                           class="col-sm-3 col-form-label form-label">@lang("Address line")</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="addressLine"
                                               id="addressLineLabel" placeholder="@lang("Your address")"
                                               aria-label="@lang("Your address")"
                                               value="{{ old("addressLine", $admin->address) }}" autocomplete="off">
                                        @error('addressLine')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">@lang("Save changes")</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="passwordSection" class="card">
                        <div class="card-header">
                            <h4 class="card-title">@lang("Change your password")</h4>
                        </div>
                        <div class="card-body">
                            <form id="changePasswordForm" action="{{ route("admin.password.update") }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-4">
                                    <label for="currentPasswordLabel" class="col-sm-3 col-form-label form-label">
                                        @lang("Current password")</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="current_password"
                                               id="currentPasswordLabel" placeholder="@lang("Enter current password")"
                                               aria-label="@lang("Enter current password")">
                                        @error('current_password')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="newPassword" class="col-sm-3 col-form-label form-label">
                                        @lang("New password")</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="password"
                                               id="newPassword" placeholder="@lang("Enter new password")"
                                               aria-label="@lang("Enter new password")">
                                        @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="confirmNewPassword" class="col-sm-3 col-form-label form-label">
                                        @lang("Confirm new password")</label>

                                    <div class="col-sm-9">
                                        <div class="mb-3">
                                            <input type="password" class="form-control" name="password_confirmation"
                                                   id="confirmNewPassword"
                                                   placeholder="@lang("Confirm your new password")"
                                                   aria-label="@lang("Confirm your new password")">
                                        </div>

                                        @if($basicControl->strong_password)
                                            <h5>@lang("Password requirements:")</h5>

                                            <p class="fs-6 mb-2">@lang("Ensure that these requirements are met:")</p>

                                            <ul class="fs-6">
                                                <li>@lang("Minimum 8 characters long - the more, the better")</li>
                                                <li>@lang("At least one lowercase character")</li>
                                                <li>@lang("At least one uppercase character")</li>
                                                <li>@lang("At least one number, symbol, or whitespace character")</li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">@lang("Save Changes")</button>
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- Card -->
                    <div id="notificationsSection" class="card">
                        <div class="card-header">
                            <h4 class="card-title">@lang("Notifications")</h4>
                        </div>

                        <div class="card-body-height">
                            <form action="{{ route("admin.notification.permission") }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="alert alert-soft-dark card-alert text-center" role="alert">
                                    @lang("We need permission from your browser to show notifications.")
                                    <a class="alert-link" href="javascript:void(0)">@lang("Request permission")</a>
                                </div>
                                <div class="table-responsive datatable-custom">
                                    <table
                                        class="table table-thead-bordered table-nowrap table-align-middle table-first-col-px-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>@lang("Type")</th>
                                            <th class="text-center">
                                                <div class="mb-1">
                                                    <img class="avatar avatar-xs"
                                                         src="{{ asset("assets/admin/img/oc-email-at.svg") }}"
                                                         alt="Image Description" data-hs-theme-appearance="default">
                                                    <img class="avatar avatar-xs"
                                                         src="{{ asset("assets/admin/img/oc-email-at-light.svg") }}"
                                                         alt="Image Description" data-hs-theme-appearance="dark">
                                                </div>
                                                @lang("Email")
                                            </th>

                                            <th class="text-center">
                                                <div class="mb-1">
                                                    <img class="avatar avatar-xs"
                                                         src="{{ asset("assets/admin/img/oc-message.svg") }}"
                                                         alt="Image Description" data-hs-theme-appearance="default">
                                                    <img class="avatar avatar-xs"
                                                         src="{{ asset("assets/admin/img/oc-message.svg") }}"
                                                         alt="Image Description" data-hs-theme-appearance="dark">
                                                </div>
                                                @lang("SMS")
                                            </th>

                                            <th class="text-center">
                                                <div class="mb-1">
                                                    <img class="avatar avatar-xs"
                                                         src="{{ asset("assets/admin/img/oc-phone.svg") }}"
                                                         alt="Image Description" data-hs-theme-appearance="default">
                                                    <img class="avatar avatar-xs"
                                                         src="{{ asset("assets/admin/img/oc-phone-light.svg") }}"
                                                         alt="Image Description" data-hs-theme-appearance="dark">
                                                </div>
                                                @lang("In-App")
                                            </th>

                                            <th class="text-center">
                                                <div class="mb-1">
                                                    <img class="avatar avatar-xs"
                                                         src="{{ asset("assets/admin/img/oc-globe.svg") }}"
                                                         alt="Image Description" data-hs-theme-appearance="default">
                                                    <img class="avatar avatar-xs"
                                                         src="{{ asset("assets/admin/img/oc-globe-light.svg") }}"
                                                         alt="Image Description" data-hs-theme-appearance="dark">
                                                </div>
                                                @lang("Push")
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($templates as $template)
                                            <tr>
                                                <td>@lang($template->name)</td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input type="hidden" name="templates[{{ $template->id }}][mail]"
                                                               value="0">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="templates[{{ $template->id }}][mail]"
                                                               value="1"
                                                               id="emailAlertCheckbox{{ $template->id }}" {{ $template->status['mail'] ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="emailAlertCheckbox{{ $template->id }}"></label>
                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input type="hidden" name="templates[{{ $template->id }}][sms]"
                                                               value="0">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="templates[{{ $template->id }}][sms]" value="1"
                                                               id="smsAlertCheckbox{{ $template->id }}" {{ $template->status['sms'] ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="smsAlertCheckbox{{ $template->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input type="hidden" name="templates[{{ $template->id }}][in_app]"
                                                               value="0">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="templates[{{ $template->id }}][in_app]"
                                                               value="1"
                                                               id="inAppAlertCheckbox{{ $template->id }}" {{ $template->status['in_app'] ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="inAppAlertCheckbox{{ $template->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input type="hidden" name="templates[{{ $template->id }}][push]"
                                                               value="0">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="templates[{{ $template->id }}][push]"
                                                               value="1"
                                                               id="pushAlertCheckbox{{ $template->id }}" {{ $template->status['push'] ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="pushAlertCheckbox{{ $template->id }}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <div class="text-center p-4">
                                                <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                                                <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                                                <p class="mb-0">@lang("No data to show")</p>
                                            </div>
                                        </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">@lang("Save changes")</button>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>



                </div>
            </div>
        </div>
    </div>
@endsection

@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push("script")
    <script>
        "use strict";
        $(document).ready(function () {
            new HSFileAttach('.js-file-attach')
        })
    </script>
@endpush











