@extends(template().'layouts.user')
@section('title',trans('KYC Verification Center'))
@section('content')
    <div class="main row px-3">
        <div class="col-12">
            <div class="row pageHeadingAll">
                <div class="AddProduct">
                    <h4>@lang('Verification Center')</h4>
                </div>
            </div>
            <div class="card">
                <div class="table-parent table-responsive mt-4">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="text-center w-10" scope="col">@lang('SL')</th>
                                <th class="text-center w-50" scope="col">@lang('Type')</th>
                                <th class="text-center w-40" scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($kyc as $key => $item)
                                <tr>
                                    <td class="text-center" data-label="@lang('SL')">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="text-center" data-label="@lang('Kyc Type')">
                                <span class="font-weight-bold">
                                    {{ $item->name }}
                                </span>
                                    </td>
                                    <td class="text-center" data-label="@lang('Action')">
                                        <a href="{{ route('user.verification.kyc.form', $item->id) }}">
                                            <i class="fa-light fa-pen-field"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset(template(true) . 'css/flatpickr.min.css') }}"/>
@endpush

