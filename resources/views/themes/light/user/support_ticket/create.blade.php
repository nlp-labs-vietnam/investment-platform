@extends(template().'layouts.user')
@section('title',__('Create Ticket'))

@section('content')
    <section class="transaction-history profile-setting">
        <div class="container-fluid">
            <div class="row mt-4 mb-2">
                <div class="col">
                    <div class="header-text-full ms-2">
                        <h3 class="dashboard_breadcurmb_heading mb-1">@lang('Create New Ticket')</h3>
                        <nav aria-label="breadcrumb" class="ms-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{trans('Create Ticket')}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="col-lg-12">
                <div class="edit-area">
                    <form action="{{route('user.ticket.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-4 input-box">
                                <label for="subject" class="golden-text">@lang('Subject')</label>
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

                            <div class="col-12 mb-4 input-box">
                                <label for="message" class="golden-text">@lang('Message')</label>
                                <textarea
                                    class="form-control ticket-box"
                                    id="message"
                                    name="message"
                                    rows="5"
                                    placeholder="@lang('Enter Message')"
                                >{{old('message')}}</textarea>
                                @error('message')
                                <div class="error text-danger">@lang($message) </div>
                                @enderror
                            </div>

                            <div class="col-12 mb-4 input-box">
                                <label for="" class="golden-text">@lang('Upload File')</label
                                >
                                <div class="attach-file">
                           <span class="prev">
                              @lang('Upload File')
                           </span>
                                    <input
                                        type="file"
                                        name="attachments[]"
                                        multiple
                                        class="form-control"
                                    />
                                </div>
                                @error('attachments')
                                <span class="text-danger">{{trans($message)}}</span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn-custom">@lang('Create')</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
