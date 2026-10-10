@extends(template().'layouts.user')
@section('title',trans('Profile Settings'))

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrapicons-iconpicker.css') }}">
@endpush

@section('content')
    <!-- profile setting -->
    <div class="container-fluid">
        <div class="main row">
            <div class="row mt-2">
                <div class="col">
                    <div class="header-text-full">
                        <h3 class="dashboard_breadcurmb_heading mb-1">@lang('Profile Settings')</h3>
                        <nav aria-label="breadcrumb" class="ms-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">@lang('Profile')</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="col">
                <!-- profile setting -->
                <section class="profile-setting">
                    <div class="row g-4 g-lg-5">
                        <div class="col-lg-4">
                            <div class="sidebar-wrapper">
                                <div class="profile">
                                    <div class="img">
                                        <img id="profile" src="{{getFile($user->image_driver, $user->image)}}"
                                             alt="@lang('not found')" class="img-fluid profile-image-preview"/>
                                        <button class="upload-img">
                                            <i class="fal fa-camera"></i>
                                            <input
                                                class="form-control" id="userPorfileImage" name="image"
                                                accept="image/*" type="file"/>
                                        </button>
                                    </div>
                                    <div class="text">
                                        <h5 class="name">@lang($user->fullname)</h5>
                                        <span>@lang($user->email)</span>
                                    </div>
                                </div>
                                <div class="profile-navigator">
                                    <button tab-id="tab1"
                                            class="tab {{ $errors->has('profile') ? 'active' : (($errors->has('password') || $errors->has('identity') || $errors->has('addressVerification')) ? '' : ' active') }}">
                                        <i class="fal fa-user"></i> @lang('Profile information')
                                    </button>
                                    <button tab-id="tab2" class="tab {{ $errors->has('password') ? 'active' : '' }}">
                                        <i class="fal fa-key"></i> @lang('Password setting')
                                    </button>
                                    @php
                                        $i = 2;
                                    @endphp

                                    @foreach($kyc as $key => $k)
                                        @php
                                            $i++;
                                        @endphp
                                        <button tab-id="tab{{ $i }}"
                                                class="tab {{ request()->routeIs('user.verify') && $item->id == $k->id ? 'active' : '' }}">
                                            @if($key == 0)
                                                <i class="fal fa-id-card"></i>
                                            @else
                                                <i class="fal fa-location"></i>
                                            @endif

                                            @lang($k->name)
                                        </button>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div id="tab1"
                                 class="content {{ $errors->has('profile') ? ' active' : (($errors->has('password') || $errors->has('identity') || $errors->has('addressVerification')) ? '' :  ' active') }}">
                                <form action="{{ route('user.updateInformation')}}" method="post">
                                    @method('put')
                                    @csrf
                                    <div class="row g-4">
                                        <div class="input-box col-md-6">
                                            <label for="firstname">@lang('First Name')</label>
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
                                        <div class="input-box col-md-6">
                                            <label for="lastname">@lang('Last Name')</label>
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
                                        <div class="input-box col-md-6">
                                            <label for="username">@lang('Username')</label>
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
                                        <div class="input-box col-md-6">
                                            <label for="email">@lang('Email Address')</label>
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
                                        <div class="input-box col-md-6">
                                            <label for="phone">@lang('Phone Number')</label>
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

                                        <div class="input-box col-md-6">
                                            <label for="language_id">@lang('Preferred language')</label>
                                            <select class="form-select"
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

                                        <div class="input-box col-md-12">
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

                                        <div class="input-box col-12">
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

                                        <div class="input-box col-12">
                                            <label for="">@lang('Social Links')</label>
                                            <div class="form website_social_links">
                                                @php
                                                    $oldSocialCounts = max(old('social_icon', $social_links) ? count(old('social_icon', $social_links)) : 1, 1);
                                                @endphp

                                                @if($oldSocialCounts > 0)
                                                    @for($i = 0; $i < $oldSocialCounts; $i++)
                                                        <div
                                                            class="d-flex justify-content-between append_new_social_form removeSocialLinksInput">
                                                            <div class="input-group mt-1">
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

                                                            <div class="input-box w-100 my-1 me-1">
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
                                                                    <button class="btn-custom add-new" type="button"
                                                                            id="add_social_links">
                                                                        <i class="fal fa-plus"></i>
                                                                    </button>
                                                                @else
                                                                    <button
                                                                        class="btn-custom add-new btn-custom-danger remove_social_link_input_field"
                                                                        type="button">
                                                                        <i class="fa fa-times"></i>
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

                                        <div class="input-box col-12">
                                            <button class="btn-custom" type="submit">@lang('Update User')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div id="tab2" class="content {{ $errors->has('password') ? 'active' : '' }}">
                                <form method="post" action="{{ route('user.updatePassword') }}">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="input-box col-md-6">
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
                                        <div class="input-box col-md-6">
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

                                        <div class="input-box col-md-6">
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

                                        <div class="input-box col-12">
                                            <button class="btn-custom" type="submit">@lang('Update Password')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            @php
                                $i = 2;
                            @endphp
                            @foreach($kyc as $key => $k)
                                @php
                                    $i++;
                                @endphp
                                <div id="tab{{ $i }}"
                                     class="content {{ request()->routeIs('user.verify') && $item->id == $k->id ? 'active' : '' }}">


                                    @if(count($k->userKyc) > 0 && $k->userKyc[0]['status'] == 0)
                                        <div class="row p-3">
                                            <div class="input-box col-md-12">
                                                <div class="alert mb-0">
                                                    <i class="fal fa-times-circle"></i>
                                                    <span>@lang('Your ' . $k->userKyc[0]['kyc_type'] . ' is pending..')</span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif(count($k->userKyc) > 0 && $k->userKyc[0]['status'] == 1)
                                        <div class="row p-3">
                                            <div class="input-box col-md-12">
                                                <div class="alert mb-0">
                                                    <i class="fal fa-check-circle"></i>
                                                    <span>@lang('Your ' . $k->userKyc[0]['kyc_type'] . ' is already verified..')</span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif(count($k->userKyc) > 0 && $k->userKyc[0]['status'] == 2)
                                        <div class="row p-3">
                                            <div class="input-box col-md-12">
                                                <div class="alert mb-0">
                                                    <i class="fal fa-check-circle"></i>
                                                    <span>@lang('Your ' . $k->userKyc[0]['kyc_type'] . ' is rejected..')</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif



                                    @if(count($k->userKyc) <= 0 || $k->userKyc[0]['status'] == 2)
                                        <form method="post" action="{{ route('user.kyc.verification.submit') }}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="type" value="{{ $k->id }}">
                                            <div class="row g-4">
                                                @foreach($k->input_form as $k => $value)
                                                    @if($value->type == "text")
                                                        <div class="input-box col-md-12">
                                                            <label for="">@lang($value->field_label)</label>
                                                            <input type="text" class="form-control"
                                                                   name="{{ $value->field_name }}"
                                                                   value="{{ old($value->field_name) }}"
                                                                   placeholder="{{ $value->field_label }}"/>
                                                            @if($errors->has($value->field_name))
                                                                <div
                                                                    class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if($value->type == "number")
                                                        <div class="input-box col-md-12">
                                                            <label for="">@lang($value->field_label)</label>
                                                            <input type="text" class="form-control"
                                                                   name="{{ $value->field_name }}"
                                                                   value="{{ old($value->field_name) }}"
                                                                   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                                   placeholder="{{ $value->field_label }}"/>
                                                            @if($errors->has($value->field_name))
                                                                <div
                                                                    class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if($value->type == "date")
                                                        <div class="input-box col-md-12 flatpickr">
                                                            <label for="">@lang($value->field_label)</label>
                                                            <div class="input-group">
                                                                <input type="date" class="form-control"
                                                                       name="{{ $value->field_name }}"
                                                                       value="{{ old($value->field_name) }}"
                                                                       placeholder="{{ $value->field_label }}"
                                                                       data-input/>
                                                                <div class="input-group-append" readonly="">
                                                                    <div class="form-control">
                                                                        <a class="input-button cursor-pointer"
                                                                           title="clear" data-clear>
                                                                            <i class="fas fa-times"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                @if($errors->has($value->field_name))
                                                                    <div
                                                                        class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($value->type == "textarea")
                                                        <div class="input-box col-12">
                                                            <label for="">{{ $value->field_label }}</label>
                                                            <textarea
                                                                class="form-control"
                                                                cols="30" rows="5"
                                                                name="{{ $value->field_name }}"
                                                                placeholder="{{ $value->field_name }}"></textarea>
                                                            @if($errors->has($value->field_name))
                                                                <div
                                                                    class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if($value->type == "file")
                                                        <div class="col-md-12 mb-2">
                                                            <div class="form-group">
                                                                <label class="golden-text">
                                                                    {{ @$value->field_label }}
                                                                </label>
                                                                @if(@$value->validation == 'required')
                                                                    <span class="text-danger">*</span>
                                                                @endif
                                                                <br>
                                                                <div class="fileinput fileinput-new "
                                                                     data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail "
                                                                         data-trigger="fileinput">
                                                                        <img class="custom-verification-img"
                                                                             src="{{ getFile(config('filelocation.default')) }}"
                                                                             alt="@lang('not found')">
                                                                    </div>
                                                                    <div
                                                                        class="fileinput-preview fileinput-exists thumbnail wh-200-150 ">
                                                                    </div>

                                                                    <div class="img-input-div">
                                                                    <span class="btn btn-success btn-file">
                                                                        <span
                                                                            class="fileinput-new"> @lang('Select') {{@$value->field_labe}}</span>
                                                                        <span
                                                                            class="fileinput-exists"> @lang('Change')</span>
                                                                        <input type="file"
                                                                               name="{{ @$value->field_name }}"
                                                                               value="{{ old(@$value->field_name) }}"
                                                                               accept="image/*" @if(@$value->validation == "required")@endif>
                                                                    </span>
                                                                        <a href="#"
                                                                           class="btn btn-danger fileinput-exists"
                                                                           data-dismiss="fileinput"> @lang('Remove')</a>
                                                                    </div>
                                                                </div>

                                                                @error(@$value->field_name)
                                                                <div class="error text-danger">
                                                                    {{trans($message)}}
                                                                </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif

                                                @endforeach

                                                <div class="input-box col-12">
                                                    <button class="btn-custom" type="submit">@lang('Submit')</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif

                                </div>
                            @endforeach


                            {{--
                            <div id="tab3" class="content {{ $errors->has('identity') ? 'active' : '' }}">
                                @if(in_array($user->identity_verify,[0,3])  )
                                    @if($user->identity_verify == 3)
                                        <div class="alert mb-0">
                                            <i class="fal fa-times-circle"></i>
                                            <span>@lang('You previous request has been rejected')</span>
                                        </div>
                                    @endif
                                    <form method="post" action="{{route('user.verificationSubmit')}}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-12 mb-3">
                                            <div class="input-box col-md-12">
                                                <label for="identity_type">@lang('Identity Type')</label>
                                                <select class="form-select"
                                                        name="identity_type" id="identity_type"
                                                        aria-label="Default select example">
                                                    <option value="" disabled>@lang('Select Language')</option>
                                                    @foreach($identityFormList as $sForm)
                                                        <option
                                                            value="{{$sForm->slug}}" {{ old('identity_type', @$identity_type) == $sForm->slug ? 'selected' : '' }}> @lang($sForm->name) </option>
                                                    @endforeach
                                                </select>
                                                @error('identity_type')
                                                <div class="error text-danger">@lang($message) </div>
                                                @enderror
                                            </div>
                                        </div>

                                        @if(isset($identityForm))
                                            @foreach($identityForm->services_form as $k => $v)
                                                @if($v->type == "text")
                                                    <div class="input-box col-md-12">
                                                        <label for="{{$k}}">
                                                            {{trans($v->field_level)}}
                                                            @if($v->validation == 'required')
                                                                <span class="text-danger">*</span>
                                                            @endif
                                                        </label>
                                                        <input type="text" name="{{$k}}"
                                                               class="form-control "
                                                               value="{{old($k)}}" id="{{$k}}"
                                                               @if($v->validation == 'required') required @endif/>
                                                        @if($errors->has($k))
                                                            <div
                                                                class="error text-danger">@lang($errors->first($k))</div>
                                                        @endif
                                                    </div>

                                                @elseif($v->type == "textarea")
                                                    <div class="input-box col-12 mt-2">
                                                        <label for="{{$k}}">
                                                            {{trans($v->field_level)}}
                                                            @if($v->validation == 'required')
                                                                <span class="text-danger">*</span>
                                                            @endif
                                                        </label>
                                                        <textarea
                                                            name="{{$k}}"
                                                            id="{{$k}}"
                                                            class="form-control"
                                                            cols="30"
                                                            rows="3"
                                                            placeholder="{{trans('Type Here')}}"
                                                            @if($v->validation == 'required')@endif>{{old($k)}}</textarea>
                                                        @error($k)
                                                        <div class="error text-danger">
                                                            {{trans($message)}}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                @elseif($v->type == "file")
                                                    <div class="col-md-12 mb-2">
                                                        <div class="form-group">
                                                            <label class="golden-text">
                                                                {{trans($v->field_level)}}
                                                                @if($v->validation == 'required')
                                                                    <span class="text-danger">*</span>
                                                                @endif
                                                            </label>

                                                            <br>
                                                            <div class="fileinput fileinput-new "
                                                                 data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail "
                                                                     data-trigger="fileinput">
                                                                    <img class="w-150px custom-verification-img"
                                                                         src="{{ getFile(config('location.default')) }}"
                                                                         alt="@lang('not found')">
                                                                </div>
                                                                <div
                                                                    class="fileinput-preview fileinput-exists thumbnail wh-200-150 ">
                                                                </div>

                                                                <div class="img-input-div">
                                                                    <span class="btn btn-success btn-file">
                                                                        <span
                                                                            class="fileinput-new"> @lang('Select') {{$v->field_level}}</span>
                                                                        <span
                                                                            class="fileinput-exists"> @lang('Change')</span>
                                                                        <input type="file" name="{{$k}}"
                                                                               value="{{ old($k) }}"
                                                                               accept="image/*" @if($v->validation == "required")@endif>
                                                                    </span>
                                                                    <a href="#" class="btn btn-danger fileinput-exists"
                                                                       data-dismiss="fileinput"> @lang('Remove')</a>
                                                                </div>
                                                            </div>

                                                            @error($k)
                                                            <div class="error text-danger">
                                                                {{trans($message)}}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                            <button type="submit" class="gold-btn mt-2 btn-custom">
                                                @lang('Submit')
                                            </button>
                                        @endif
                                    </form>
                                @elseif($user->identity_verify == 1)
                                    <div class="alert mb-0">
                                        <i class="fal fa-times-circle"></i>
                                        <span> @lang('Your KYC submission has been pending')</span>
                                    </div>
                                @elseif($user->identity_verify == 2)
                                    <div class="alert mb-0">
                                        <i class="fal fa-check-circle"></i>
                                        <span> @lang('Your KYC already verified')</span>
                                    </div>
                                @endif
                            </div>

                            <div id="tab4" class="content {{ $errors->has('addressVerification') ? 'active' : '' }}">
                                @if(in_array($user->address_verify,[0,3])  )
                                    @if($user->address_verify == 3)
                                        <div class="alert mb-0">
                                            <i class="fal fa-times-circle"></i>
                                            <span> @lang('You previous request has been rejected')</span>
                                        </div>
                                    @endif


                                    <form method="post" action="{{route('user.addressVerification')}}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-12 mb-2">
                                            <div class="form-group">
                                                <label class="form-label golden-text">{{trans('Address Proof')}} <span
                                                        class="text-danger">*</span> </label><br>

                                                <div class="fileinput fileinput-new "
                                                     data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail "
                                                         data-trigger="fileinput">
                                                        <img class="custom-verification-img"
                                                             src="{{ getFile(config('location.default')) }}"
                                                             alt="...">
                                                    </div>
                                                    <div
                                                        class="fileinput-preview fileinput-exists thumbnail wh-200-150 "></div>

                                                    <div class="img-input-div">
                                                        <span class="btn btn-success btn-file">
                                                            <span
                                                                class="fileinput-new "> @lang('Select Image') </span>
                                                            <span
                                                                class="fileinput-exists"> @lang('Change')</span>
                                                            <input type="file" name="addressProof"
                                                                   value="{{ old('addressProof')}}"
                                                                   accept="image/*">
                                                        </span>
                                                        <a href="#" class="btn btn-danger fileinput-exists"
                                                           data-dismiss="fileinput"> @lang('Remove')</a>
                                                    </div>

                                                </div>

                                                @error('addressProof')
                                                <div class="error text-danger">
                                                    {{trans($message)}}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <button type="submit" class="gold-btn btn-custom">
                                            @lang('Submit')
                                        </button>

                                    </form>

                                @elseif($user->address_verify == 1)
                                    <div class="alert mb-0">
                                        <i class="fal fa-times-circle"></i>
                                        <span> @lang('Your KYC submission has been pending')</span>
                                    </div>
                                @elseif($user->address_verify == 2)
                                    <div class="alert mb-0">
                                        <i class="fal fa-check-circle"></i>
                                        <span> @lang('Your KYC already verified')</span>
                                    </div>
                                @endif
                            </div>
                            --}}
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @push('loadModal')
        <!-- Modal -->
        <div class="modal fade" id="profileImage" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="planModalLabel">@lang('Confirmation')</h4>
                        <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <h6 id="imageChangeText"></h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="button" class="btn-custom profile-image-save">@lang('Save changes')</button>
                    </div>
                </div>
            </div>
        </div>
    @endpush
@endsection


@push('css-lib')
    <link rel="stylesheet" href="{{asset(template(true).'css/bootstrap-fileinput.css')}}">
@endpush

@push('extra-js')
    <script src="{{asset(template(true).'js/bootstrap-fileinput.js')}}"></script>
    <script src="{{ asset('assets/global/js/bootstrapicon-iconpicker.js') }}"></script>
@endpush

@push('script')

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
                                <div class="input-group mt-1">
                                    <input type="text" name="social_icon[]" class="form-control demo__icon__picker iconpicker${newSocialForm}" placeholder="Pick a icon" aria-label="Pick a icon"
                                   aria-describedby="basic-addon1" readonly>
                                </div>

                                <div class="input-box w-100 my-1 me-1">
                                    <input type="url" name="social_url[]" class="form-control" placeholder="@lang('URL')"/>
                                </div>
                                <div class="my-1 me-1">
                                    <button class="btn-custom add-new btn-custom-danger remove_social_link_input_field" type="button">
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

            // User profile image change
            $('#userPorfileImage').on('change', function () {
                $('#imageChangeText').text(`@lang('Do you want to change your profile image?')`)
                $('#profileImage').modal('show');
            });

            $(document).on('click', '.profile-image-save', function () {
                $('#profileImage').modal('hide');
                let formData = new FormData();
                console.log(formData);
                formData.append('profile_image', document.getElementById('userPorfileImage').files[0]);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('user.profileImageUpdate') }}",
                    type: "post",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.src) {
                            $('.profile-image-preview').attr('src', data.src);
                        }
                    }
                });
            })

            $(document).on('click', '#image-label', function () {
                $('#image').trigger('click');
            });
            $(document).on('change', '#image', function () {
                var _this = $(this);
                var newimage = new FileReader();
                newimage.readAsDataURL(this.files[0]);
                newimage.onload = function (e) {
                    $('#image_preview_container').attr('src', e.target.result);
                }
            });
            $(document).on('change', "#identity_type", function () {
                let value = $(this).find('option:selected').val();
                window.location.href = "{{route('user.profile')}}/?identity_type=" + value
            });

        });
    </script>

@endpush
