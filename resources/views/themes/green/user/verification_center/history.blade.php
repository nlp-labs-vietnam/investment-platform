@extends(template().'layouts.user')
@section('title',trans('KYC Verification History'))
@section('content')
    <div class="main row px-3">
        <div class="col-12">
            <div class="row pageHeadingAll">
                <div class="AddProduct">
                    <h2>@lang('Verification History')</h2>
                </div>
            </div>
            <div class="card">
                <div class="table-parent table-responsive mt-4">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Serial')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Submitted Date')</th>
                                <th scope="col">@lang('Approved Date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($userKyc as $item)
                                <tr>
                                    <td data-label="@lang('Serial No.')">
                                        {{$loop->iteration}}
                                    </td>
                                    <td data-label="@lang('Kyc Type')">
                                                                <span class="font-weight-bold">
                                                                    {{$item->kyc_type}}
                                                                </span>
                                    </td>
                                    <td data-label="@lang('Kyc Status')">
                                        @if($item->status == 0)
                                            <span class="badge bg-warning text-warning">@lang('Pending')</span>
                                        @elseif($item->status == 1)
                                            <span class="badge bg-success text-success">@lang('Accepted')</span>
                                        @elseif($item->status == 2)
                                            <span class="badge bg-danger text-danger">@lang('Rejected')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Submitted Date')">
                                        {{dateTime($item->created_at) }}
                                    </td>
                                    <td data-label="@lang('Approved Date')">
                                        {{ dateTime($item->approved_at, 'd M Y') }}
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        {{ $userKyc->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

