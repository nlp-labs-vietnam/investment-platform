@extends(template().'layouts.user')
@section('title',__('Create Ticket'))

@section('content')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('Create Ticket')</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h4 class="title">@lang('Create a new ticket')</h4>
                </div>

                <div class="card-body">
                    <form action="{{route('user.ticket.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="subject" class="form-label">@lang('Subject')</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="subject"
                                    name="subject" value="{{old('subject')}}" placeholder="@lang('Enter Subject')"
                                />
                                @error('subject')
                                    <div class="error text-danger">@lang($message) </div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label">@lang('Message')</label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="@lang('Enter Message')">{{old('message')}}</textarea>
                                @error('message')
                                <div class="error text-danger">@lang($message) </div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="Upload-File" class="form-label">@lang('Upload File')</label>
                                <div class="attach-file">
                                    <div class="prev">@lang('Upload File')</div>
                                    <input class="form-control" type="file" id="Upload-File" name="attachments[]" multiple>
                                </div>
                                @error('attachments')
                                <span class="text-danger">{{trans($message)}}</span>
                                @enderror
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="cmn-btn mt-10"><i
                                        class="fa-regular fa-circle-plus"></i>@lang('Create')</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
