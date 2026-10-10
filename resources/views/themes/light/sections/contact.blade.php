<!-- contact section -->
@if(isset($contact['single']))
    <div class="contact-section">
        <div class="container">
            <div class="info-wrapper">
                <div class="row g-lg-5 g-4">
                    <div class="col-lg-4">
                        <div class="info-box">
                            <div class="icon"><img src="{{ template(true).'img/location.png' }}" alt=""/></div>
                            <div class="text">
                                <h4>@lang('Location')</h4>
                                <p>@lang($contact['single']['address'])</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="info-box">
                            <div class="icon"><img src="{{ template(true).'img/email.png' }}" alt=""/></div>
                            <div class="text">
                                <h4>@lang('Email')</h4>
                                <p>@lang($contact['single']['email'])</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="info-box">
                            <div class="icon"><img src="{{ template(true).'img/phone-call.png' }}" alt=""/></div>
                            <div class="text">
                                <h4>@lang('Phone')</h4>
                                <p>@lang($contact['single']['phone'])</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-5">
                    <img src="{{ template(true).'img/contact.png' }}" alt="" class="img-fluid"/>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-6">
                    <h4>@lang($contact['single']['heading'])</h4>
                    <p class="mb-4">
                        @lang($contact['single']['sub_heading'])
                    </p>
                    <form action="{{route('contact.send')}}" method="post">
                        @csrf
                        <div class="row g-3">
                            <div class="input-box col-md-6">
                                <input type="text"
                                       name="name"
                                       value="{{old('name')}}"
                                       class="form-control"
                                       placeholder="@lang('Full Name')"/>
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="input-box col-md-6">
                                <input type="email"
                                       name="email"
                                       value="{{old('email')}}"
                                       class="form-control"
                                       placeholder="@lang('Email Address')"/>
                                @error('email')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="input-box col-12">
                                <input
                                    type="text"
                                    name="subject"
                                    value="{{old('subject')}}"
                                    class="form-control"
                                    placeholder="@lang('Subject')"
                                />
                                @error('subject')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="input-box col-12">
                                <textarea
                                    class="form-control"
                                    name="message"
                                    cols="30"
                                    rows="10"
                                    placeholder="@lang('Message')"
                                >{{old('message')}}</textarea>
                                @error('message')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="input-box col-12">
                                <button class="btn-custom">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
