@extends(template().'layouts.user')
@section('title',trans('KYC Verification'))
@section('content')
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <div class="dashboard-heading">
                    <h4 class="mb-0">KYC Verification</h4>
                </div>

                <div class="table-parent table-responsive mt-4">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">@lang('SL')</th>
                            <th scope="col">@lang('Type')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Submitted At')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($userKycs as $key => $item)
                            <tr>
                                <td data-label="@lang('SL')"><span>{{++$key}}</span></td>
                                <td data-label="@lang('Type')">
                                    <span>{{$item->kyc_type}}</span>
                                </td>
                                <td data-label="@lang('Status')">
                                    {!! $item->getStatus() !!}
                                </td>
                                <td data-label="@lang('Submitted At')">
                                    <span>{{dateTime($item->created_at,basicControl()->date_time_format)}}</span>
                                </td>
                                <td data-label="@lang('Action')">
                                    <div class="dropdown">
                                        <button class="action-btn2" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fa-regular fa-ellipsis-stroke-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item showDetails" data-bs-target="#modalShow"
                                                   data-bs-toggle="modal"
                                                   data-res="{{json_encode($item->kycInfoShow())}}"
                                                   data-type="{{$item->kyc_type}}"
                                                   href="javascript:void(0)">@lang('View')</a>
                                            </li>
                                            @if($item->status == 2)
                                                <li><a class="dropdown-item showReason"
                                                       data-bs-target="#modalReject"
                                                       data-bs-toggle="modal" data-reason="{{$item->reason}}"
                                                       href="javascript:void(0)">@lang('Reason')</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end mt-4">
                            {{ $tickets->appends($_GET)->links(template().'partials.pagination') }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
