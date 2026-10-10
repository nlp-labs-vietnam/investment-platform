@extends(template().'layouts.user')
@section('title')
    {{ 'Pay with '.optional($deposit->gateway)->name ?? '' }}
@endsection

@section('content')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Pay With') {{ optional($deposit->gateway)->name }}</li>
            </ol>
        </nav>
    </div>

    <section class="transaction-history p-0">
        <div class="container">
            <div class="row">
                <div class="col ms-2">
                    <div class="header-text-full">
                        <h3 class="dashboard_breadcurmb_heading">{{ 'Pay with '.optional($deposit->gateway)->name ?? '' }}</h3>
                    </div>
                </div>
            </div>


            <div class="card secbg">
                <div class="card-body profile-setting">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="title text-center">{{trans('Please follow the instruction below')}}</h4>
                            <p class="text-center mt-2 ">{{trans('You have requested to deposit')}} <b
                                    class="text--base">{{getAmount($deposit->amount)}}
                                    {{basicControl()->base_currency}}</b> , {{trans('Please pay')}}
                                <b class="text--base">{{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</b> {{trans('for successful payment')}}
                            </p>

                            <p class="mt-2 ">
                                <?php echo optional($deposit->gateway)->note; ?>
                            </p>


                            <form action="{{route('addFund.fromSubmit',$deposit->trx_id)}}" method="post"
                                  enctype="multipart/form-data"
                                  class="form-row  preview-form">
                                @csrf
                                @if(optional($deposit->gateway)->parameters)
                                    @foreach($deposit->gateway->parameters as $k => $v)
                                        @if($v->type == "text")
                                            <div class="col-12 mb-3">
                                                <label class="form-label">{{trans($v->field_label)}}
                                                    @if($v->validation == 'required')
                                                        *
                                                    @endif
                                                </label>
                                                <input type="text" name="{{$k}}" class="form-control"
                                                       @if($v->validation == "required") required @endif>
                                                @if ($errors->has($k))
                                                    <span
                                                        class="text--danger">{{ trans($errors->first($k)) }}</span>
                                                @endif
                                            </div>

                                        @elseif($v->type == "number")
                                            <div class="col-12 mb-3">
                                                <label class="form-label">{{trans($v->field_label)}}
                                                    @if($v->validation == 'required')
                                                        *
                                                    @endif
                                                </label>
                                                <input type="text" name="{{$k}}"
                                                       class="form-control"
                                                       @if($v->validation == "required") required @endif>
                                                @if ($errors->has($k))
                                                    <span
                                                        class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                @endif
                                            </div>

                                        @elseif($v->type == "textarea")
                                            <div class="col-12 mb-3">
                                                <label class="form-label">{{trans($v->field_label)}}
                                                    @if($v->validation == 'required')
                                                        *
                                                    @endif
                                                </label>
                                                <textarea name="{{$k}}" class="form-control" rows="3"
                                                          @if($v->validation == "required") required @endif></textarea>
                                                @if($errors->has($k))
                                                    <span
                                                        class="text--danger">{{ trans($errors->first($k)) }}</span>
                                                @endif
                                            </div>

                                        @elseif($v->type == "file")
                                            <div class="col-12 mb-3">
                                                <label>{{trans($v->field_label)}} @if($v->validation == 'required')
                                                        <span class="text--danger">*</span>
                                                    @endif </label>

                                                <div class="form-group">
                                                    <div class="fileinput fileinput-new "
                                                         data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail withdraw-thumbnail"
                                                             data-trigger="fileinput">
                                                            <img class=""
                                                                 src="{{ getFile(config('filelocation.default')) }}"
                                                                 alt="..." width="200">
                                                        </div>
                                                        <div
                                                            class="fileinput-preview fileinput-exists thumbnail wh-200-150 "></div>

                                                        <div class="img-input-div">
                                                                <span class="btn btn-success btn-file">
                                                                    <span
                                                                        class="fileinput-new "> @lang('Select') {{$v->field_label}}</span>
                                                                    <span
                                                                        class="fileinput-exists"> @lang('Change')</span>
                                                                    <input type="file" name="{{$k}}" accept="image/*"
                                                                           @if($v->validation == "required") required @endif>
                                                                </span>
                                                            <a href="#" class="btn btn-danger fileinput-exists"
                                                               data-dismiss="fileinput"> @lang('Remove')</a>
                                                        </div>

                                                    </div>
                                                    @if ($errors->has($k))
                                                        <br>
                                                        <span
                                                            class="text--danger">{{ __($errors->first($k)) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                                <div class="col-md-12 ">
                                    <div class=" form-group">
                                        <button type="submit" class="cmn-btn w-100 mt-3">
                                            <span>@lang('Confirm Now')</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @push('css-lib')
        <link rel="stylesheet" href="{{asset(template(true).'css/bootstrap-fileinput.css')}}">
    @endpush

    @push('extra-js')
        <script src="{{asset(template(true).'js/bootstrap-fileinput.js')}}"></script>
    @endpush
@endsection
