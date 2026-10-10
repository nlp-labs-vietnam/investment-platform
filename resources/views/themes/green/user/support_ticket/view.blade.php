@extends(template().'layouts.user')
@section('title')
    @lang('View Ticket')
@endsection

@section('content')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('View Ticket')</li>
            </ol>
            <p class="breadcrumb-item active"><span class="">@lang('Ticket Id'):</span> #{{ $ticket->ticket }}</p>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Chat section start -->
            <form action="{{ route('user.ticket.reply', $ticket->id)}}" method="post"
                  enctype="multipart/form-data" class="p-0">
                @csrf
                @method('PUT')
                <div class="message-container mt-15">
                    <div class="chat-box m-2">
                        <div class="header-section">
                            <div class="profile-info">
                                <div class="thumbs-area">
                                    <img src="{{getFile(auth()->user()->image_driver, auth()->user()->image)}}">
                                </div>

                                <div class="content-area">
                                    <div class="title">{{auth()->user()->fullname}}</div>
                                    @if($ticket->status == 0)
                                        <span class="badge text-bg-primary">@lang('Open')</span>
                                    @elseif($ticket->status == 1)
                                        <span class=" badge text-bg-success">@lang('Answered')</span>
                                    @elseif($ticket->status == 2)
                                        <span class="badge text-bg-dark">@lang('Customer Reply')</span>
                                    @elseif($ticket->status == 3)
                                        <span class="badge text-bg-danger">@lang('Closed')</span>
                                    @endif
                                </div>
                            </div>
                            <div class="single-btn-box d-flex justify-content-sm-end ">
                                <button type="button" class="delete-btn" data-bs-toggle="modal"
                                        data-bs-target="#closeTicketModal"><i
                                        class="fa-light fa-circle-xmark"></i>{{trans('Close')}}</button>
                            </div>
                        </div>

                        @if(isset($ticket->messages) && count($ticket->messages) > 0)
                            <div class="chat-box-inner">
                                @foreach($ticket->messages as $item)
                                    @if($item->admin_id == null)
                                        <div class="message-bubble">
                                            <div class="message-thumbs">
                                                <img
                                                    src="{{getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image)}}">
                                            </div>
                                            <div class="message-text-box">
                                                <div class="message-text">{{$item->message}}</div>
                                                @if(0 < count($item->attachments))
                                                    @foreach($item->attachments as $k=> $image)
                                                        <a href="{{route('user.ticket.download',encrypt($image->id))}}"
                                                           class="attach-file-box"><i
                                                                class="fa-solid fa-file"></i>
                                                            @lang('File') {{++$k}}
                                                        </a>
                                                    @endforeach
                                                @endif
                                                <p class="time">{{ diffForHumans($item->created_at) }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="message-bubble message-bubble-right">
                                            <div class="message-thumbs">
                                                <img
                                                    src="{{getFile(optional($item->admin)->image_driver, optional($item->admin)->image)}}">
                                            </div>
                                            <div class="message-text-box">
                                                <div class="message-text">{{$item->message}}</div>
                                                @if(0 < count($item->attachments))
                                                    @foreach($item->attachments as $k=> $image)
                                                        <a href="{{route('user.ticket.download',encrypt($image->id))}}"
                                                           class="attach-file-box"><i
                                                                class="fa-solid fa-file"></i>
                                                            @lang('File') {{++$k}}
                                                        </a>
                                                    @endforeach
                                                @endif
                                                <p class="time">{{ diffForHumans($item->created_at) }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        @endif

                        <div class="chat-box-bottom">
                            <div class="cmn-btn-group2 d-flex justify-content-sm-end ">
                                <div class="upload-btn">
                                    <div class="btn btn-primary new-file-upload mr-3 mt-3"
                                         title="{{trans('Image Upload')}}">
                                        <a href="#">
                                            <i class="fa-regular fa-paperclip"></i>
                                        </a>
                                        <input type="file" name="attachments[]" id="upload" class="upload-box"
                                               multiple
                                               placeholder="@lang('Upload File')">
                                    </div>
                                    <p class="text-danger select-files-count"></p>
                                </div>
                            </div>

                            <textarea class="form-control" id="exampleFormControlTextarea1"
                                      rows="3" name="message" placeholder="@lang('Type here')">{{old('message')}}</textarea>
                            <br>
                            @error('message')
                            <div class="error text-danger">@lang($message) </div>
                            @enderror

                            <button type="submit" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="@lang('Reply')" name="replayTicket" value="1" class="message-send-btn"><i
                                    class="fa-thin fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Chat section end -->
        </div>
    </div>


    @push('loadModal')
        <div class="modal fade" id="closeTicketModal" tabindex="-1" aria-labelledby="addListingmodal"
             aria-hidden="true">
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
                            <button type="button" class="cmn-btn2 close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                            <button class="cmn-btn" type="submit" name="replayTicket"
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





