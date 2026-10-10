@extends(template().'layouts.user')
@section('title',trans('Support Ticket'))
@section('content')

    <div class="container-fluid">
        <div class="main row mt-4 mb-2">
            <div class="col ms-2">
                <div class="header-text-full  d-flex justify-content-between align-items-end">
                    <nav aria-label="breadcrumb ms-1">
                    <h3 class="dashboard_breadcurmb_heading mb-1">@lang('Support Ticket')</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Support Ticket')</li>
                        </ol>
                    </nav>
                    <div class="mb-4">
                        <a href="{{route('user.ticket.create')}}" class="btn btn-custom text-white create__ticket"> <i class="fa fa-plus-circle"></i> @lang('Create Ticket')</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main row">
            <div class="col">
                <div class="card bg-light">
                    <div class="card-body p-0">
                        <div class="table-parent table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">@lang('Subject')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Last Reply')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($tickets as $key => $ticket)
                                    <tr>
                                        <td data-label="@lang('Ticket Id')">
                                        <span class="font-weight-bold">
                                            [{{ trans('Ticket#').$ticket->ticket }}] {{ $ticket->subject }}
                                        </span>
                                        </td>
                                        <td data-label="@lang('status')">
                                            @if($ticket->status == 0)
                                                <span class="badge rounded-pill bg-success">@lang('Open')</span>
                                            @elseif($ticket->status == 1)
                                                <span class="badge rounded-pill bg-primary">@lang('Answered')</span>
                                            @elseif($ticket->status == 2)
                                                <span class="badge rounded-pill bg-warning">@lang('Replied')</span>
                                            @elseif($ticket->status == 3)
                                                <span class="badge rounded-pill bg-danger">@lang('Closed')</span>
                                            @endif
                                        </td>
                                        <td>{{diffForHumans($ticket->last_reply) }}</td>
                                        <td data-label="Action">
                                            <a href="{{ route('user.ticket.view', $ticket->ticket) }}" class="action-btn">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="100%">{{__('No Data Found!')}}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center m-0">
                                    {{ $tickets->appends($_GET)->links(template().'partials.pagination') }}
                                </ul>
                            </nav>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

