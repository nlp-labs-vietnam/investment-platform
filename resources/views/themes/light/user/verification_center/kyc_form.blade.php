@extends(template().'layouts.user')
@section('title',trans('Create Ticket'))
@section('content')
    <div class="container-fluid" id="create_ticket">
        <div class="main row d-flex align-items-center justify-content-center">
            <div class="col-lg-8 col-sm-10">
                <div class="dashboard-heading">
                    <h4 class="mb-0">{{ $kyc->name }}</h4>
                </div>
                @if($userKyc && $userKyc->status == 0)
                    <div class="px-3 kycStatusAll">
                        <p class="kycStatus">@lang("Your kyc is pending $userKyc->kyc_type" )
                        </p>
                    </div>
                @elseif($userKyc && $userKyc->status == 1)
                    <div class="px-3 kycStatusAll">
                        <p class="kycStatus">@lang("Your kyc is approved $userKyc->kyc_type" )
                        </p>
                    </div>
                @else
                    <div class="search-bar">
                        <div class="card ">
                            <div class="card-body">
                                <form action="{{ route('user.kyc.verification.submit') }}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-4">
                                        <input type="hidden" name="type" value="{{ $kyc->id }}">
                                        @foreach($kyc->input_form as $k => $value)
                                            @if($value->type == "text")
                                                <div class="input-box col-md-12">
                                                    <label for="">{{ $value->field_label }}</label>
                                                    <input type="text" class="form-control"
                                                           name="{{ $value->field_name }}"
                                                           placeholder="{{ $value->field_label }}"
                                                           autocomplete="off"/>
                                                    @if($errors->has($value->field_name))
                                                        <div class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if($value->type == "number")
                                                <div class="input-box col-md-12">
                                                    <label for="">{{ $value->field_label }}</label>
                                                    <input type="text" class="form-control"
                                                           name="{{ $value->field_name }}"
                                                           placeholder="{{ $value->field_label }}"
                                                           autocomplete="off"/>
                                                    @if($errors->has($value->field_name))
                                                        <div
                                                            class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if($value->type == "date")
                                                <div class="input-box col-md-12">
                                                    <label for="">{{ $value->field_label }}</label>
                                                    <input type="date" class="form-control"
                                                           name="{{ $value->field_name }}"
                                                           placeholder="{{ $value->field_label }}"/>
                                                    @if($errors->has($value->field_name))
                                                        <div
                                                            class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if($value->type == "textarea")
                                                <div class="input-box col-md-12">
                                                    <label for="">{{ $value->field_label }}</label>
                                                    <textarea class="form-control" id="" cols="30" rows="10"
                                                              name="{{ $value->field_name }}"></textarea>
                                                    @if($errors->has($value->field_name))
                                                        <div
                                                            class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if($value->type == "file")
                                                <div class="input-box col-12">
                                                    <label for="">{{ $value->field_label }}</label>
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
                                        <div class="input-box col-12">
                                            <button type="submit" class="btn-custom">submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
