@extends(template().'layouts.user')
@section('title',__($page_title))

@section('content')
    <div class="container-fluid">
        <div class="main row" id="supportTicketView">
            <div class="col-12">
                <div
                    class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">
                        <span class="ticket-span-view">
                            @if($ticket->status == 0)
                                <span class="badge rounded-pill bg-primary">@lang('Open')</span>
                            @elseif($ticket->status == 1)
                                <span class=" badge rounded-pill bg-success">@lang('Answered')</span>
                            @elseif($ticket->status == 2)
                                <span class="badge rounded-pill bg-dark">@lang('Customer Reply')</span>
                            @elseif($ticket->status == 3)
                                <span class="badge rounded-pill bg-danger">@lang('Closed')</span>
                            @endif
                        </span>
                        {{trans('Ticket #'). $ticket->ticket }} [{{ $ticket->subject }}]

                    </h4>
                    <div class="col-sm-2 text-sm-right mt-sm-0 mt-3">
                        <button type="button" class="btn btn-sm btn-danger btn-rounded float-end"
                                data-bs-toggle="modal"
                                data-bs-target="#closeTicketModal"><i
                                class="fas fa-times-circle"></i> {{trans('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12 col-12 ps-4 pe-4">
                <div class="my-search-bar p-0">
                    <form action="{{ route('user.ticket.reply', $ticket->id)}}" method="post"
                          enctype="multipart/form-data" class="p-0">
                        @csrf
                        @method('PUT')

                        <div class="basic-form ticket-reply-basic-form">
                            <div class="p-3">
                                <div class="row g-3">
                                    <div class="input-box col-md-12">
                                        <label>@lang('Message')</label>
                                        <textarea class="form-control ticket-box" name="message" rows="5"
                                                  id="textarea1"
                                                  placeholder="@lang('Type here')">{{old('message')}}</textarea>
                                        @error('message')
                                        <div class="error text-danger">@lang($message) </div>
                                        @enderror
                                    </div>

                                    <div class="input-box col-md-12">
                                        <div
                                            class="justify-content-sm-end justify-content-start mt-sm-0 mt-2 align-items-center d-flex h-100">
                                            <div class="upload-btn">
                                                <div class="btn btn-primary new-file-upload mr-3 mt-3"
                                                     title="{{trans('Image Upload')}}">
                                                    <a href="#">
                                                        <i class="fa fa-image"></i>
                                                    </a>
                                                    <input type="file" name="attachments[]" id="upload" class="upload-box"
                                                           multiple
                                                           placeholder="@lang('Upload File')">
                                                </div>
                                                <p class="text-danger select-files-count"></p>
                                            </div>

                                            <button type="submit"
                                                    class="btn btn-circle btn-lg btn-success float-right text-white"
                                                    title="{{trans('Reply')}}" name="replayTicket"
                                                    value="1"><i class="fas fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            @if(isset($ticket->messages) && count($ticket->messages) > 0)
                                <div class="ticket-reply-section">
                                    @foreach($ticket->messages as $item)
                                        @if($item->admin_id == null)
                                            <div class="bug_fixing_inbox_start_new">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="bug_fixing_inbox_start__msg d-flex flex-column get_message_dropdown">
                                                            <div class="bug_fixing_inbox_start__msg__rec_msg me-auto d-flex user-product-message">
                                                                <div class="bug_fixing_inbox_start__msg__rec_msg__img">
                                                                </div>
                                                                <div class="bug_fixing_inbox_start__msg__rec_msg__text">
                                                                    <div class="bug_fixing_inbox_start__msg__rec_msg__text__one message">
                                                                        <div class="image__title d-flex align-items-center">
                                                                            <img src="{{getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image)}}" class="ticket-user-img" class="me-2" alt="@lang('not found')">
                                                                            <h6 class="m-0">{{optional($ticket->user)->username}}</h6>
                                                                        </div>

                                                                        <p class="m-0 ticket-user-name"> {{$item->message}} </p>
                                                                        @if(0 < count($item->attachments))
                                                                            <div class="d-flex justify-content-end">
                                                                                @foreach($item->attachments as $k=> $image)
                                                                                    <a href="{{route('user.ticket.download',encrypt($image->id))}}"
                                                                                       class="ml-3 nowrap ticket-file-icon"><i
                                                                                            class="fa fa-file"></i> @lang('File') {{++$k}}
                                                                                    </a>
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div
                                                class="bug_fixing_inbox_start__msg__outgoing_msg d-flex flex-row-reverse">
                                                <div class="bug_fixing_inbox_start__msg__outgoing_msg__new border_down customer-product-message">
                                                    <div class="image__title d-flex  align-items-center">
                                                        <img src="{{getFile(optional($item->admin)->image_driver, optional($item->admin)->image)}}" class="me-2 ticket-user-img" >
                                                        <h6 class="m-0">{{optional($item->admin)->name}}</h6>
                                                    </div>

                                                    <p class="m-0 ticket-user-name"> {{$item->message}} </p>
                                                    @if(0 < count($item->attachments))
                                                        <div class="d-flex justify-content-start">
                                                            @foreach($item->attachments as $k=> $image)
                                                                <a href="{{route('user.ticket.download',encrypt($image->id))}}"
                                                                   class="mr-3 nowrap ticket-file-icon2"><i
                                                                        class="fa fa-file"></i> @lang('File') {{++$k}}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('loadModal')
        <div class="modal fade" id="closeTicketModal" tabindex="-1" aria-labelledby="addListingmodal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-custom">
                        <h4 class="modal-title" id="editModalLabel">@lang('Confirmation')</h4>
                        <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                    </div>
                    <form method="post" action="{{ route('user.ticket.reply', $ticket->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <p>@lang('Are you want to close ticket?')</p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-custom close__btn" data-bs-dismiss="modal">@lang('Close')</button>
                            <button class="btn-custom" type="submit" name="replayTicket"
                                    value="2">@lang('Confirm')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endpush
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            'use strict';
            $('.delete-message').on('click', function (e) {
                $('.message_id').val($(this).data('id'));
            })

            $(document).on('change', '#upload', function () {
                var fileCount = $(this)[0].files.length;
                $('.select-files-count').text(fileCount + ' file(s) selected')
            })
        });
    </script>
@endpush





