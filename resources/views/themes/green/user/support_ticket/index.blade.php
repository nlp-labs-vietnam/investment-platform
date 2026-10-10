@extends(template().'layouts.user')
@section('title',trans('Support Ticket'))
@section('content')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Support Ticket')</li>
            </ol>
        </nav>
    </div>

    <!-- Cmn table section start -->
    <div class="card mt-25">
        <div class="card-header d-flex align-items-center justify-content-between pb-0 border-0">
            <h4 class="mb-0">@lang('Ticket List')</h4>
            <div class="btn-area d-flex align-items-center gap-2">
                <a href="{{route('user.ticket.create')}}" class="cmn-btn3"><i
                        class="fa-regular fa-circle-plus"></i>@lang('create ticket')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="cmn-table">
                <div class="table-responsive overflow-hidden">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th scope="col">@lang('Ticket No.')</th>
                            <th scope="col">@lang('Subject')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Last Reply')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($tickets as $key => $ticket)
                            <tr>
                                <td data-label="Ticket No."><span>#{{ $ticket->ticket }}</span></td>

                                <td data-label="Subject">
                                    <span>@lang($ticket->subject)</span>
                                </td>

                                <td data-label="Status">
                                    @if($ticket->status == 0)
                                        <span class="badge text-bg-success">@lang('Open')</span>
                                    @elseif($ticket->status == 1)
                                        <span class="badge text-bg-primary">@lang('Answered')</span>
                                    @elseif($ticket->status == 2)
                                        <span class="badge text-bg-warning">@lang('Replied')</span>
                                    @elseif($ticket->status == 3)
                                        <span class="badge text-bg-danger">@lang('Closed')</span>
                                    @endif
                                </td>

                                <td data-label="Last Reply">
                                    <span>{{diffForHumans($ticket->last_reply) }}</span>
                                </td>
                                <td data-label="Action">
                                    <a href="{{ route('user.ticket.view', $ticket->ticket) }}" class="cmn-btn2"><i
                                            class="fa-regular fa-eye"></i>@lang('view')</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="dataTables_empty">
                                    <div class="text-center p-4">
                                        <img class="dataTables-image mb-3 empty-state-img"
                                             src="{{ asset(template(true).'img/oc-error.svg') }}"
                                             alt="Image Description" data-hs-theme-appearance="default">
                                        <p class="mb-0">@lang('No data to show')</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Cmn table section end -->
@endsection
