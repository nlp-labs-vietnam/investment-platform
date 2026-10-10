@extends(template().'layouts.user')
@section('title',trans('Offer Conversation'))

@section('content')
    <div class="container-fluid" id="messenger" v-cloak>
        <div class="main row" id="content">
            <div class="media mt-0 mb-2 d-flex justify-content-end">
                @if($singlePropertyOffer->offered_from != Auth::id())
                    <a href="{{ route('user.offerList', $singlePropertyOffer->property_share_id) }}"
                       class="btn btn-sm bgPrimary text-white mr-2">
                        <span><i class="fas fa-arrow-left font-12"></i> @lang('Back')</span>
                    </a>
                @else
                    <a href="{{ route('user.propertyMarket', 'my-offered-properties') }}"
                       class="btn btn-sm bgPrimary text-white mr-2">
                        <span><i class="fas fa-arrow-left font-12"></i> @lang('Back')</span>
                    </a>
                @endif
            </div>

            <div class="col-xl-12 col-md-12 col-12">
                <div class="search-bar my-search-bar">
                    <section class="conversation-section  pt-3 pb-3">
                        <div class="container-fluid">
                            <div class="row g-4">
                                <div class="col-lg-7">
                                    <div class="inbox_right_side__profile__info__phone d-flex">
                                        <i class="far fa-question custom--mar"></i>
                                        <p class="ms-2"> @lang($singlePropertyOffer->description) </p>
                                    </div>

                                    <div class="inbox-wrapper shop-section p-0">
                                        <!-- top bar -->
                                        <div class="top-bar d-flex justify-content-between property-box">
                                            <div>
                                                @if($singlePropertyOffer->offered_from != Auth::id())
                                                    <div class="massenger_active">
                                                        <img class="user img-fluid"
                                                             src="{{getFile(optional($singlePropertyOffer->user)->image_driver, optional($singlePropertyOffer->user)->image) }}"/>
                                                        <p class="{{optional($singlePropertyOffer->user)->last_seen == 'true' ? 'active-icon-messenger':'deActive-icon-messenger' }}"></p>
                                                        <span
                                                            class="name text-white">@lang(optional($singlePropertyOffer->user)->firstname) @lang(optional($singlePropertyOffer->user)->lastname)</span>
                                                    </div>
                                                @else
                                                    <div class="massenger_active">
                                                        <img class="user img-fluid"
                                                             src="{{getFile(optional($singlePropertyOffer->owner)->image_driver, optional($singlePropertyOffer->owner)->image) }}"/>
                                                        <p class="{{optional($singlePropertyOffer->owner)->last_seen == 'true' ? 'active-icon-messenger':'deActive-icon-messenger' }}"></p>
                                                        <span
                                                            class="name text-white">@lang(optional($singlePropertyOffer->owner)->firstname) @lang(optional($singlePropertyOffer->owner)->lastname)</span>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($singlePropertyOffer->offered_from != Auth::id())
                                                <div>
                                                    <div class="sidebar-dropdown-items">
                                                        <button
                                                            type="button"
                                                            class="dropdown-toggle"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="fal fa-cog"></i>
                                                        </button>

                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            @if($singlePropertyOffer->lockInfo() && optional($singlePropertyOffer->offerlock)->status == 1 && $singlePropertyOffer->lockInfo()->status == 1)

                                                            @else
                                                                @if($singlePropertyOffer->offerlock && $singlePropertyOffer->lockInfo() != null && $singlePropertyOffer->status != 1)
                                                                    <li>
                                                                        <a class="dropdown-item btn acceptOffer"
                                                                           data-route="{{ route('user.offerAccept', $singlePropertyOffer->id) }}">
                                                                            <i class="fal fa-check-circle"></i> @lang('Accept Offer')
                                                                        </a>
                                                                    </li>
                                                                @endif

                                                                @if($singlePropertyOffer->status != 1)
                                                                    <li>
                                                                        <a class="dropdown-item btn acceptOffer"
                                                                           data-route="{{ route('user.offerAccept', $singlePropertyOffer->id) }}">
                                                                            <i class="fal fa-check-circle"></i> @lang('Accept Offer')
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if($singlePropertyOffer->status != 2)
                                                                    <li>
                                                                        <a class="dropdown-item btn rejectOffer"
                                                                           data-route="{{ route('user.offerReject', $singlePropertyOffer->id) }}">
                                                                            <i class="fal fa-times-circle"></i> @lang('Reject Offer')
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif

                                                            @if($singlePropertyOffer->lockInfo() && optional($singlePropertyOffer->offerlock)->status == 0 && $singlePropertyOffer->lockInfo()->status == 0)
                                                                <li>
                                                                    <a class="dropdown-item btn paymentLockInfo"
                                                                       data-route="{{ route('user.paymentLockUpdate', $singlePropertyOffer->lockInfo()->id) }}"
                                                                       data-lockamount="{{ $singlePropertyOffer->lockInfo()->lock_amount }}"
                                                                       data-duration="{{ $singlePropertyOffer->lockInfo()->duration }}">
                                                                        <i class="fal fa-lock"></i> @lang('Payment Lock Info')
                                                                    </a>
                                                                </li>
                                                            @elseif($singlePropertyOffer->lockInfo() && optional($singlePropertyOffer->offerlock)->status == 1 && $singlePropertyOffer->lockInfo()->status == 1)
                                                                <li>
                                                                    <a class="dropdown-item btn paymentCompletedInfo"
                                                                       data-lockamount="{{ $singlePropertyOffer->lockInfo()->lock_amount }}"
                                                                       data-duration="{{ $singlePropertyOffer->lockInfo()->duration }}">
                                                                        <i class="fal fa-check-double"></i> @lang('Payment Completed')
                                                                    </a>
                                                                </li>

                                                            @elseif($singlePropertyOffer->offerlock && $singlePropertyOffer->lockInfo() == null)
                                                                <li>
                                                                    <button class="dropdown-item btn disabled">
                                                                        <i class="fal fa-lock"></i> @lang('Already Locked')
                                                                    </button>

                                                                </li>
                                                            @else
                                                                <li>
                                                                    <a class="dropdown-item btn paymentLock"
                                                                       data-route="{{ route('user.paymentLock', $singlePropertyOffer->id) }}">
                                                                        <i class="fal fa-lock"></i> @lang('Lock Payment')
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            @else
                                                @if($singlePropertyOffer->receiveMyOffer && optional($singlePropertyOffer->receiveMyOffer)->status == 0)
                                                    <div>
                                                        <div class="sidebar-dropdown-items">
                                                            <button
                                                                type="button"
                                                                class="dropdown-toggle"
                                                                data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fal fa-cog"></i>
                                                            </button>

                                                            <ul class="dropdown-menu dropdown-menu-right">
                                                                <li>
                                                                    <a class="dropdown-item btn paymentInfo"
                                                                       data-route="{{ route('user.paymentLockConfirm', $singlePropertyOffer->lockInfo()->id) }}"
                                                                       data-payableamount="{{ $singlePropertyOffer->lockInfo()->lock_amount }}"
                                                                       data-payableduration="{{ $singlePropertyOffer->lockInfo()->duration }}">
                                                                        <i class="fal fa-money-check-alt"></i> @lang('Payment Information')
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <a class="dropdown-item btn paymentLockCancel"
                                                                       data-route="{{ route('user.paymentLockCancel', $singlePropertyOffer->lockInfo()->id) }}">
                                                                        <i aria-hidden="true"
                                                                           class="fal fa-times-circle"></i> @lang('Cancel Payment')
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @elseif($singlePropertyOffer->receiveMyOffer && optional($singlePropertyOffer->receiveMyOffer)->status == 1)
                                                    <div>
                                                        <div class="sidebar-dropdown-items">
                                                            <button
                                                                type="button"
                                                                class="dropdown-toggle"
                                                                data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fal fa-cog"></i>
                                                            </button>

                                                            <ul class="dropdown-menu dropdown-menu-right">
                                                                <li>
                                                                    <a class="dropdown-item btn buyerPaymentCompletedInfo"
                                                                       data-payableamount="{{ $singlePropertyOffer->lockInfo()->lock_amount }}"
                                                                       data-payableduration="{{ $singlePropertyOffer->lockInfo()->duration }}">
                                                                        <i class="fal fa-check-double"></i> @lang('Payment Completed Info')
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>

                                        <!-- chats -->
                                        <div class="chats">
                                            <div v-for="message in messages">
                                                <div v-if="message.client_id != authUser" class="chat-box this-side">
                                                    <div class="text-wrapper">
                                                        <div class="text">
                                                            <p>@{{ message.reply }}</p>
                                                        </div>
                                                        <div class="fileimg" v-if="message.fileImage">
                                                            <a :href="message.fileImage" data-fancybox="gallery">
                                                                <img :src="message.fileImage" width="50px"
                                                                     height="50px">
                                                            </a>
                                                        </div>
                                                        <span class="time" v-cloak>@{{ message.sent_at }}</span>
                                                    </div>
                                                    <div class="img">
                                                        <img class="img-fluid" :src="message.sender_image"/>
                                                    </div>
                                                </div>

                                                <div v-else class="chat-box opposite-side">
                                                    <div class="img">
                                                        <img class="img-fluid" :src="message.sender_image"/>
                                                    </div>
                                                    <div class="text-wrapper">
                                                        <div class="text">
                                                            <p>@{{ message.reply }}</p>
                                                        </div>
                                                        <div class="fileimg" v-if="message.fileImage">
                                                            <a :href="message.fileImage" data-fancybox="gallery">
                                                                <img :src="message.fileImage" width="50px"
                                                                     height="50px">
                                                            </a>
                                                        </div>
                                                        <span class="time" v-cloak>@{{ message.sent_at }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!------------------------------------- typing area ---------------------------------------------->
                                        <div class="typing-area">
                                            <div class="img-preview" v-if="file.name">
                                                <button class="delete" @click="removeImage">
                                                    <i class="fal fa-times"></i>
                                                </button>
                                                <img id="attachment" :src="photo" class="img-fluid"/>
                                            </div>

                                            <small v-if="typingFriend.firstname" v-cloak>@{{ typingFriend.firstname
                                                }} @lang('is typing...')</small>

                                            <div class="input-group">
                                                <div>
                                                    <button class="upload-img send-file-btn">
                                                        <i class="fal fa-paperclip" aria-hidden="true"></i>
                                                        <input class="form-control" id="upload" accept="image/*"
                                                               type="file" @change="handleFileUpload( $event )"/>
                                                    </button>
                                                    <span class="text-danger file"></span>
                                                </div>

                                                <input type="hidden" name="property_offer_id" value="{{ $id }}"
                                                       class="form-control property_offer_id">

                                                <textarea v-model="message" @keydown.enter.prevent="sendMessage"
                                                          @keydown="onTyping" cols="30" rows="10"
                                                          class="form-control type-message"
                                                          placeholder="@lang('Type your message...')"></textarea>

                                                <button @click.prevent="sendMessage" class="submit-btn">
                                                    <i class="fal fa-paper-plane reply-submit-btn"
                                                       aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-5">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="search-bar my-search-bar">
                                                <form action="" method="get" enctype="multipart/form-data">
                                                    <div class="row g-3">
                                                        <div class="inbox_right_side bg-white rounded">
                                                            <div class="d-flex justify-content-center">
                                                                <h5>@lang('Offer Information')</h5>
                                                            </div>
                                                            <div class="inbox_right_side__profile  p-3">
                                                                <div
                                                                    class="inbox_right_side__profile__header text-center mb-4">
                                                                    <img
                                                                        src="{{ getFile(optional($singlePropertyOffer->property)->driver, optional($singlePropertyOffer->property)->thumbnail) }}"
                                                                        class="productInfoThumbnail">
                                                                </div>

                                                                <div class="inbox_right_side__profile__info">
                                                                    <div
                                                                        class="inbox_right_side__profile__info__phone d-flex justify-content-between">
                                                                        <p>{{ __('Property') }} : </p>
                                                                        <p>@if(optional($singlePropertyOffer->property)->title)
                                                                                <a href="{{ route('property.details',[slug(optional($singlePropertyOffer->property)->title), optional($singlePropertyOffer->property)->id]) }}"
                                                                                   target="_blank">
                                                                                    @lang(Str::limit(optional($singlePropertyOffer->property)->title, 30))
                                                                                </a>
                                                                            @else
                                                                                @lang('N/A')
                                                                            @endif</p>
                                                                    </div>
                                                                    <div
                                                                        class="inbox_right_side__profile__info__phone d-flex justify-content-between">
                                                                        <p>{{ __('Offer Amount') }} : </p>
                                                                        <p>
                                                                            {{ basicControl()->currency_symbol }}{{ $singlePropertyOffer->amount }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            @if($singlePropertyOffer->offered_from != Auth::id())
                                                <div class="search-bar my-search-bar">
                                                    <form action="" method="get" enctype="multipart/form-data">
                                                        <div class="row g-3">
                                                            <div class="d-flex justify-content-center">
                                                                <h5>@lang('Buyer Information')</h5>
                                                            </div>
                                                            <div class="inbox_right_side bg-white rounded m-0">
                                                                <div class="inbox_right_side__profile  p-3">
                                                                    <div class="inbox_right_side__profile__header text-center mb-4">
                                                                        <img src="{{ getFile(optional($singlePropertyOffer->user)->image_driver, optional($singlePropertyOffer->user)->image) }}" class="productClientImage">
                                                                        <h6 class="mt-2 mb-0">
                                                                            <b>@lang(optional($singlePropertyOffer->user)->firstname) @lang(optional($singlePropertyOffer->user)->lastname)</b>
                                                                        </h6>
                                                                    </div>

                                                                    <div class="inbox_right_side__profile__info">
                                                                        <div
                                                                            class="inbox_right_side__profile__info__phone d-flex justify-content-between">
                                                                            <p>{{ __('Phone') }}:</p>
                                                                            <p>{{ (optional($singlePropertyOffer->user)->phone) ? __(optional($singlePropertyOffer->user)->phone) : __('N/A') }}</p>
                                                                        </div>

                                                                        <div
                                                                            class="inbox_right_side__profile__info__phone d-flex justify-content-between">
                                                                            <p>{{ __('Email') }}:</p>
                                                                            <p>{{ (optional($singlePropertyOffer->user)->email) ? __(optional($singlePropertyOffer->user)->email) : __('N/A') }}</p>
                                                                        </div>

                                                                        <div
                                                                            class="inbox_right_side__profile__info__phone d-flex justify-content-between">
                                                                            <p>{{ __('Address') }}: </p>
                                                                            <p>{{ (optional($singlePropertyOffer->user)->address) ? __(optional($singlePropertyOffer->user)->address) : __('N/A') }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="right_side_bottom p-3">
                                                                    <a href="{{ route('investor.profile', [optional($singlePropertyOffer->user)->id, slug(optional($singlePropertyOffer->user)->username)]) }}"
                                                                       target="_blank"
                                                                       class="btn w-100 text-white cmn-btn d-flex justify-content-center">@lang('Visit Profile')</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="search-bar my-search-bar">
                                                    <form action="" method="get" enctype="multipart/form-data"
                                                          class="reply__form">
                                                        <div class="row g-3">
                                                            <div class="d-flex justify-content-center">
                                                                <h5>@lang('Owner Information')</h5>
                                                            </div>
                                                            <div class="inbox_right_side bg-white rounded m-0">
                                                                <div class="inbox_right_side__profile  p-3">
                                                                    <div
                                                                        class="inbox_right_side__profile__header text-center mb-4">
                                                                        <img
                                                                            src="{{ getFile(optional($singlePropertyOffer->owner)->image_driver, optional($singlePropertyOffer->owner)->image) }}"
                                                                            class="productClientImage">
                                                                        <h6 class="mt-2 mb-0">
                                                                            <b>@lang(optional($singlePropertyOffer->owner)->firstname) @lang(optional($singlePropertyOffer->owner)->lastname)</b>
                                                                        </h6>
                                                                    </div>

                                                                    <div class="inbox_right_side__profile__info">
                                                                        <div
                                                                            class="inbox_right_side__profile__info__phone d-flex justify-content-between">
                                                                            <p>{{ __('Phone') }}:</p>
                                                                            <p>{{ (optional($singlePropertyOffer->owner)->phone) ? __(optional($singlePropertyOffer->owner)->phone) : __('N/A') }}</p>
                                                                        </div>

                                                                        <div
                                                                            class="inbox_right_side__profile__info__phone d-flex justify-content-between">
                                                                            <p>{{ __('Email') }}:</p>
                                                                            <p>{{ (optional($singlePropertyOffer->owner)->email) ? __(optional($singlePropertyOffer->owner)->email) : __('N/A') }}</p>
                                                                        </div>

                                                                        <div
                                                                            class="inbox_right_side__profile__info__phone d-flex justify-content-between">
                                                                            <p>{{ __('Address') }}: </p>
                                                                            <p>{{ (optional($singlePropertyOffer->owner)->address) ? __(optional($singlePropertyOffer->owner)->address) : __('N/A') }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="right_side_bottom p-3">
                                                                    <a href="{{ route('investor.profile', [optional($singlePropertyOffer->owner)->id, slug(optional($singlePropertyOffer->owner)->username)]) }}"
                                                                       target="_blank"
                                                                       class="btn w-100 text-white cmn-btn d-flex justify-content-center">@lang('Visit Profile')</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    @push('loadModal')
        {{--  Accept Offer modal --}}
        <div class="modal fade" id="acceptOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">@lang('Accept Confirmation')</h5>
                        <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>@lang('Are you sure to Accept this?')</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="cmn-btn2 btn2 btn-secondary close_invest_modal close__btn"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <form action="" method="get" class="accept_offer_form">
                            @csrf
                            <button type="submit" class="cmn-btn">@lang('Accept')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{--  Reject Offer modal --}}
        <div class="modal fade" id="rejectOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">@lang('Reject Confirmation')</h5>
                        <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>@lang('Are you sure to reject this?')</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="cmn-btn2 btn2 btn-secondary close_invest_modal close__btn"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <form action="" method="get" class="reject_offer_form">
                            @csrf
                            <button type="submit" class="cmn-btn">@lang('Reject')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{--  Payment Lock modal --}}
        <div class="modal fade" id="paymentLockModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <form action="" method="post" class="payment_lock_form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Lock Payment')</h5>
                            <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="input-box col-12">
                                <label for="">@lang('Sell Amount')</label>
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="invest-amount amount form-control @error('amount') is-invalid @enderror"
                                        name="amount"
                                        value=""
                                        onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                        autocomplete="off"
                                        placeholder="@lang('Enter amount')" required>
                                    <button class="show-currency" type="button"></button>
                                </div>
                                @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-box col-12 mt-2">
                                <label for="duration">@lang('Payment Duration')</label>
                                <input type="datetime-local" class="form-control" name="duration"
                                       value="{{ old('duration',request()->duration) }}"
                                       placeholder="@lang('schedule time')" autocomplete="off"/>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="cmn-btn2 btn2 btn-secondary close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>

                            <button type="submit" class="cmn-btn">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Payment Lock Info modal --}}
        <div class="modal fade" id="paymentLockInfoModal" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <form action="" method="post" class="payment_lock_update">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Payment Lock Infromation')</h5>
                            <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="input-box col-12">
                                <label for="">@lang('Sell Amount')</label>
                                <div class="input-group">

                                    <input
                                        type="text"
                                        class="invest-amount lock_amount form-control @error('amount') is-invalid @enderror"
                                        name="amount"
                                        value=""
                                        onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                        autocomplete="off"
                                        placeholder="@lang('Enter amount')" required>
                                    <button class="show-currency" type="button"></button>
                                </div>
                                @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-box col-12 mt-2">
                                <label for="duration">@lang('Payment Duration')</label>
                                <input type="datetime-local" class="form-control lock_duration" name="duration"
                                       placeholder="@lang('schedule time')" autocomplete="off"/>
                            </div>


                            <div class="input-box col-12 mt-2">
                                <label for="duration" class="mb-1 payment_expired"
                                       data-expired="{{ $singlePropertyOffer->lockinfo() ? $singlePropertyOffer->lockinfo()->duration : 0 }}">@lang('Remaining Time')</label>
                                @if($singlePropertyOffer->lockinfo())
                                    <p id="ownerTime"></p>
                                @endif

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="cmn-btn2 btn2 btn-danger close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="cmn-btn">@lang('Update')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Payment Completed Info modal --}}
        <div class="modal fade" id="paymentCompletedInfoModal" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <form action="" method="post" class="payment_lock_form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Payment Infromation')</h5>
                            <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="input-box col-12">
                                <label for="">@lang('Selling Amount')</label>
                                <div class="input-group">

                                    <input
                                        type="text"
                                        class="invest-amount lock_amount form-control @error('amount') is-invalid @enderror"
                                        name="amount"
                                        value=""
                                        onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                        autocomplete="off"
                                        placeholder="@lang('Enter amount')" required>
                                    <button class="show-currency" type="button"></button>
                                </div>
                                @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-box col-12 mt-2">
                                <label for="duration">@lang('Payment Duration')</label>
                                <input type="datetime-local" class="form-control lock_duration" name="duration"
                                       placeholder="@lang('schedule time')" autocomplete="off"/>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="cmn-btn2 btn2 btn-danger close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Payment Info modal --}}
        <div class="modal fade" id="paymentInfoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <form action="" method="post" class="payment_info_form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Payment Infromation')</h5>
                            <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="input-box col-12">
                                <label for="">@lang('Select Wallet')</label>
                                <select class="form-control form-select" id="exampleFormControlSelect1"
                                        name="balance_type">
                                    @auth
                                        <option
                                            value="balance">@lang('Deposit Balance - '.basicControl()->currency_symbol.getAmount(auth()->user()->balance))</option>
                                        <option
                                            value="interest_balance">@lang('Interest Balance -'.basicControl()->currency_symbol.getAmount(auth()->user()->interest_balance))</option>
                                    @endauth
                                </select>
                            </div>
                            <div class="input-box col-12 mt-3">
                                <label for="">@lang('Payable Amount')</label>
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="invest-amount payable_amount form-control @error('amount') is-invalid @enderror"
                                        name="amount"
                                        value=""
                                        onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                        autocomplete="off"
                                        placeholder="@lang('Enter amount')" required readonly>
                                    <button class="show-currency" type="button"></button>
                                </div>
                                @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-box col-12 mt-2">
                                <label for="duration">@lang('Payment Duration')</label>
                                <input type="datetime-local" class="form-control payable_duration" name="duration"
                                       placeholder="@lang('schedule time')" autocomplete="off" readonly/>
                            </div>

                            <div class="input-box col-12 mt-2">
                                <label for="duration" class="mb-1 payment_expired"
                                       data-expired="{{ $singlePropertyOffer->lockinfo() ? $singlePropertyOffer->lockinfo()->duration : 0 }}">@lang('Remaining Time')</label>
                                @if($singlePropertyOffer->lockinfo())
                                    <p id="customerTime"></p>
                                    <input type="hidden" class="expired_time" name="expired_time">
                                @endif
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="cmn-btn2 btn2 btn-danger close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="cmn-btn">@lang('Pay Now')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Buyer Payment Completed Info modal --}}
        <div class="modal fade" id="buyerPaymentCompletedInfoModal" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <form action="" method="post" class="payment_info_form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Payment Infromation')</h5>
                            <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="input-box col-12 mt-3">
                                <label for="">@lang('Payable Amount')</label>
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="invest-amount payable_amount form-control @error('amount') is-invalid @enderror"
                                        name="amount"
                                        value=""
                                        onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                        autocomplete="off"
                                        placeholder="@lang('Enter amount')" required readonly>
                                    <button class="show-currency" type="button"></button>
                                </div>
                                @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-box col-12 mt-2">
                                <label for="duration">@lang('Payment Duration')</label>
                                <input type="datetime-local" class="form-control payable_duration" name="duration"
                                       placeholder="@lang('schedule time')" autocomplete="off"/>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="cmn-btn2 btn2 btn-danger close_invest_modal close__btn"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Payment Lock Cancel modal --}}
        <div class="modal fade" id="paymentLockCancelModal" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">@lang('Confirmation')</h5>
                        <button type="button" class="close-btn close_invest_modal" data-bs-dismiss="modal"
                                aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>@lang('Are you sure to cancel this?')</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="cmn-btn2 btn2 btn-secondary close_invest_modal close__btn"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <form action="" method="get" class="cancel_offer_form">
                            <button type="submit" class="cmn-btn">@lang('Yes')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @endpush
@endsection

@push('script')
    <script src="{{asset('assets/global/js/laravel-echo.common.min.js')}}"></script>

    <script>
        'use strict'
        // Set the date we're counting down to
        var expired = $('.payment_expired').data('expired');
        var countDownDate = new Date(expired).getTime();

        var offeredForm = {{ $singlePropertyOffer->offered_from }};
        var authId = {{ Auth::id() }};

        if (offeredForm != authId) {
            // Update the count down every 1 second
            var x = setInterval(function () {

                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="ownerTime"
                document.getElementById("ownerTime").innerHTML = days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s ";

                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("ownerTime").innerHTML = "EXPIRED";
                }
            }, 1000);
        } else {
            // Update the count down every 1 second
            var x = setInterval(function () {

                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="customerTime"
                $('.expired_time').val('available');
                document.getElementById("customerTime").innerHTML = days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s ";


                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    $('.expired_time').val('expired');
                    document.getElementById("customerTime").innerHTML = "EXPIRED";


                }
            }, 1000);
        }

    </script>


    <script>
        "use strict";
        let messenger = new Vue({
            el: "#messenger",
            data: {
                item: {},
                authUser: '',
                id: '',
                selectedContactId: 0,
                selectedContact: null,
                messages: [],
                message: '',
                file: '',
                photo: '',
                myProfile: [],  //<!-- typing show -->
                typingFriend: {},   //<!-- typing show -->
                typingClock: null,  //<!-- typing show -->
                errors: {},
            },
            mounted() {
                this.authUser = "{{auth()->user()->id}}";
                this.allMessages();
                this.wsConnection();
                this.listenUser();
            },
            watch: {
                messages(messages) {
                    this.scrollToBottom();
                }
            },
            methods: {
                handleFileUpload(event) {
                    if (event.target.files[0].size > 10485760) {  //made condition: file will less than 3MB(3*1024*1024=1048576 byte)
                        Notiflix.Notify.failure("@lang('Image should be less than 3MB!')");
                    } else {
                        this.file = event.target.files[0];
                        this.photo = URL.createObjectURL(event.target.files[0]);
                    }
                },
                removeImage() {
                    this.file = '';
                    this.photo = '';
                },
                scrollToBottom() {
                    setTimeout(() => {
                        let messagesContainer = this.$el.querySelector(".chats");
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }, 50);
                },
                allMessages() {
                    let item = this.item;
                    var property_offer_id = $('.property_offer_id').val();
                    var client_id = {{optional($singlePropertyOffer->user)->id}};
                    item.offerId = property_offer_id;
                    this.selectedContactId = client_id;
                    axios.post("{{ route('user.offerReplyMessageRender') }}", this.item)
                        .then(response => {
                            this.myProfile = response.data[response.data.length - 1];   //<!-- typing show -->
                            this.messages = response.data.filter(ownProfile => ownProfile.id !== this.myProfile.id); //<!-- typing show -->
                        });
                },
                sendMessage() {
                    var _this = this;
                    if (this.message === '' && this.file === '') {
                        Notiflix.Notify.failure("@lang('Can\'t send empty message')");
                        return;
                    }
                    let formData = new FormData();
                    formData.append('file', this.file);
                    formData.append('reply', this.message);
                    formData.append('property_offer_id', $('.property_offer_id').val());
                    var check = "{{ $singlePropertyOffer->offered_to }}";
                    if (this.authUser != check) {
                        var client_id = {{ $singlePropertyOffer->offered_to }};
                    } else {
                        var client_id = {{ $singlePropertyOffer->offered_from }};
                    }

                    formData.append('client_id', client_id);

                    const headers = {'Content-Type': 'multipart/form-data'};
                    axios.post("{{route('user.offerReplyMessage')}}", formData, {headers})
                        .then(function (res) {
                            _this.message = '';
                            _this.file = '';
                            _this.messages.push(res.data);
                        })
                        .catch(error => this.errors = error.response.data.errors);
                },
                wsConnection() {
                    window.Echo = new Echo({
                        broadcaster: 'pusher',
                        key: '{{ config("broadcasting.connections.pusher.key") }}',
                        cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
                        forceTLS: true,
                        authEndpoint: '{{ url('/') }}/broadcasting/auth'
                    });
                },
                listenUser() {
                    let _this = this;
                    window.Echo.private('user.chat.{{ auth()->id() }}')
                        .listen('ChatEvent', (e) => {
                            _this.messages.push(e.message);
                        })
                        .listenForWhisper('typing', (e) => {            //<!-- typing show -->
                            _this.typingFriend = e.user;
                        });
                },
                onTyping() {        //<!-- typing show -->
                    var check = "{{ $singlePropertyOffer->offered_to }}";
                    if (this.authUser != check) {
                        var client_id = {{ $singlePropertyOffer->offered_to }};
                    } else {
                        var client_id = {{ $singlePropertyOffer->offered_from }};
                    }
                    Echo.private('user.chat.' + client_id).whisper('typing', {
                        user: this.myProfile
                    });
                },
            }
        });

        $(document).on('click', '.acceptOffer', function () {
            var acceptOfferModal = new bootstrap.Modal(document.getElementById('acceptOfferModal'))
            acceptOfferModal.show();
            let dataRoute = $(this).data('route');
            $('.accept_offer_form').attr('action', dataRoute);
        });
        $(document).on('click', '.rejectOffer', function () {
            var rejectOfferModal = new bootstrap.Modal(document.getElementById('rejectOfferModal'))
            rejectOfferModal.show();
            let dataRoute = $(this).data('route');
            $('.reject_offer_form').attr('action', dataRoute);
        });

        $(document).on('click', '.paymentLock', function () {
            var paymentLockModal = new bootstrap.Modal(document.getElementById('paymentLockModal'))
            paymentLockModal.show();

            let dataRoute = $(this).data('route');

            $('.payment_lock_form').attr('action', dataRoute);
            $('.show-currency').text("{{basicControl()->base_currency}}");
        });

        $(document).on('click', '.paymentLockInfo', function () {
            var paymentLockInfoModal = new bootstrap.Modal(document.getElementById('paymentLockInfoModal'))
            paymentLockInfoModal.show();

            let daraRoute = $(this).data('route');
            let lockAmount = $(this).data('lockamount');
            let duration = $(this).data('duration');
            let durationType = $(this).data('durationtype');

            $('.payment_lock_update').attr('action', daraRoute);
            $('.lock_amount').val(lockAmount);
            $('.lock_duration').val(duration);
            $('.lock_duration_type').val(durationType);
            $('.show-currency').text("{{basicControl()->base_currency}}");
        });

        $(document).on('click', '.paymentCompletedInfo', function () {
            var paymentCompletedInfoModal = new bootstrap.Modal(document.getElementById('paymentCompletedInfoModal'))
            paymentCompletedInfoModal.show();

            let lockAmount = $(this).data('lockamount');
            let duration = $(this).data('duration');

            $('.lock_amount').val(lockAmount);
            $('.lock_duration').val(duration);
            $('.show-currency').text("{{basicControl()->base_currency}}");
        });

        $(document).on('click', '.paymentInfo', function () {
            var paymentInfoModal = new bootstrap.Modal(document.getElementById('paymentInfoModal'))
            paymentInfoModal.show();

            let dataRoute = $(this).data('route');
            let payableAmount = $(this).data('payableamount');
            let payableDuration = $(this).data('payableduration');

            $('.payment_info_form').attr('action', dataRoute);
            $('.payable_amount').val(payableAmount);
            $('.payable_duration').val(payableDuration);
            $('.show-currency').text("{{basicControl()->base_currency}}");
        });

        $(document).on('click', '.buyerPaymentCompletedInfo', function () {
            var buyerPaymentCompletedInfoModal = new bootstrap.Modal(document.getElementById('buyerPaymentCompletedInfoModal'))
            buyerPaymentCompletedInfoModal.show();

            let payableAmount = $(this).data('payableamount');
            let payableDuration = $(this).data('payableduration');

            $('.payable_amount').val(payableAmount);
            $('.payable_duration').val(payableDuration);
            $('.show-currency').text("{{basicControl()->base_currency}}");
        });

        $(document).on('click', '.paymentLockCancel', function () {
            var paymentLockCancelModal = new bootstrap.Modal(document.getElementById('paymentLockCancelModal'))
            paymentLockCancelModal.show();
            let dataRoute = $(this).data('route');
            $('.cancel_offer_form').attr('action', dataRoute);
        });


        $('.notiflix-confirm').on('click', function () {
            var route = $(this).data('route');
            $('.deleteRoute').attr('action', route)
        })
    </script>
@endpush

