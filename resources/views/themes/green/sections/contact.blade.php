<!-- contact section -->
@if(isset($contact['single']))
    <!-- Contact section start -->
    <section class="contact-section">
        <div class="container">
            <div class="row g-4 g-xxl-5">
                <div class="col-lg-6">
                    <!-- Map section start -->
                    <div class="map-section">
                        <iframe
                            src="{{ $contact['single']['google_map_embed_url'] }}"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <!-- Map section end -->
                </div>
                <div class="col-lg-6">
                    <div class="contact-message-area">
                        <div class="contact-header">
                            <h3 class="section-title">@lang($contact['single']['heading'])</h3>
                            <p>@lang($contact['single']['sub_heading'])</p>
                        </div>
                        <form action="{{route('contact.send')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="@lang('Full Name')">
                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="@lang('Email Address')">
                                    @error('email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-12">
                                    <input type="text" name="subject" value="{{old('subject')}}" class="form-control" placeholder="@lang('Subject')">
                                    @error('subject')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-12">
                                    <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="5"
                                              placeholder="@lang('Message')">{{old('message')}}</textarea>
                                    @error('message')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="btn-area d-flex justify-content-end">
                                <button type="submit" class="cmn-btn w-100"><span>@lang('Send a message')</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="contact-area mt-50">
                <div class="contact-item-list">
                    <div class="row g-4">
                        <div class="col-lg-4 col-md-6">
                            <div class="item">
                                <div class="icon-area">
                                    <i class="fa-light fa-phone"></i>
                                </div>
                                <div class="content-area">
                                    <h6 class="mb-0">@lang('Phone'):</h6>
                                    <p class="mb-0">@lang($contact['single']['phone'])</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="item">
                                <div class="icon-area">
                                    <i class="fa-light fa-envelope"></i>
                                </div>
                                <div class="content-area">
                                    <h6 class="mb-0">@lang('Email'):</h6>
                                    <p class="mb-0">@lang($contact['single']['email'])</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="item">
                                <div class="icon-area">
                                    <i class="fa-light fa-location-dot"></i>
                                </div>
                                <div class="content-area">
                                    <h6 class="mb-0">@lang('Address'):</h6>
                                    <p class="mb-0">@lang($contact['single']['address'])</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact section end -->
@endif
