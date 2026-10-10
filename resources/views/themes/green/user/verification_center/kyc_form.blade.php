@extends(template().'layouts.user')
@section('title',trans($kyc->name))
@section('content')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang($kyc->name)</li>
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
                        <i class="fal fa-key" aria-hidden="true"></i>
                        @lang('Password Settings')
                    </a>
                </li>

                @foreach($kycs as $key => $k)
                    <li class="nav-item">
                        <a class="nav-link {{ $k->id == $kyc->id ? 'active' : '' }}"
                           href="{{ route('user.verification.kyc.form', $k->id) }}"><i
                                class="fa-regular fa-link"></i> @lang($k->name)</a>
                    </li>
                @endforeach

            </ul>
        </div>
        <!-- Account settings navbar end -->
        <form action="{{ route('user.kyc.verification.submit') }}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="account-settings-profile-section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang($kyc->name)
                        </h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="profile-form-section">
                            <input type="hidden" name="type" value="{{ $kyc->id }}">

                            @if($userKyc && $userKyc->status == 0)
                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                    <div>
                                        @lang('Your kyc is pending')
                                    </div>
                                </div>
                            @elseif($userKyc && $userKyc->status == 1)
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <div>
                                        @lang('Your kyc is approved')
                                    </div>
                                </div>
                            @else
                                @if($userKyc && $userKyc->status == 2)
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <div>
                                            @lang('Your previous kyc is rejected!')
                                        </div>
                                    </div>
                                @endif
                                <div class="row g-3">
                                    @foreach($kyc->input_form as $k => $value)
                                        @if($value->type == "text")
                                            <div class="col-md-6">
                                                <label for="firstname"
                                                       class="form-label">{{ $value->field_label }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="{{ $value->field_name }}"
                                                       placeholder="{{ $value->field_label }}"/>

                                                @if($errors->has($value->field_name))
                                                    <div
                                                        class="error text-danger">@lang($errors->first($value->field_name))</div>
                                                @endif
                                            </div>
                                        @endif

                                        @if($value->type == "number")
                                            <div class="col-md-6">
                                                <label for="firstname"
                                                       class="form-label">{{ $value->field_label }}</label>
                                                <input type="number"
                                                       class="form-control"
                                                       name="{{ $value->field_name }}"
                                                       placeholder="{{ $value->field_label }}"/>

                                                @if($errors->has($value->field_name))
                                                    <div
                                                        class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if($value->type == "date")
                                            <div class="col-md-12">
                                                <label for="firstname"
                                                       class="form-label">{{ $value->field_label }}</label>
                                                <input type="date"
                                                       class="form-control"
                                                       name="{{ $value->field_name }}"
                                                       placeholder="{{ $value->field_label }}"/>

                                                @if($errors->has($value->field_name))
                                                    <div
                                                        class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if($value->type == "textarea")
                                            <div class="col-md-12">
                                                <label for="firstname"
                                                       class="form-label">{{ $value->field_label }}</label>
                                                <textarea class="form-control" id="" cols="30" rows="10"
                                                          name="{{ $value->field_name }}"></textarea>
                                                @if($errors->has($value->field_name))
                                                    <div
                                                        class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if($value->type == "file")
                                            <div class="col-md-12">
                                                <label for="firstname"
                                                       class="form-label">{{ $value->field_label }}</label>
                                                <div class="attach-file">
                                                            <span class="prev"> <i
                                                                    class="fa-duotone fa-link"></i> </span>
                                                    <input class="form-control" accept="image/*"
                                                           name="{{ $value->field_name }}" type="file"/>
                                                    @if($errors->has($value->field_name))
                                                        <div
                                                            class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="btn-area d-flex g-3">
                                    <button type="submit" class="cmn-btn">@lang('Submit')</button>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
