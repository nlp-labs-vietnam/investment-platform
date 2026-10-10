@extends(template().'layouts.user')
@section('title',trans($title))
@section('content')

    <!-- My Referral -->

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('My Referral')</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="input-group">
                <input id="referralURL" type="text" class="form-control"
                       value="{{route('register.sponsor',[Auth::user()->username])}}"
                       aria-label="Recipient's username" aria-describedby="basic-addon2" readonly>
                <div class="input-group-text" id="copyBtn"><i class="fa-regular fa-copy"></i>@lang('copy')
                </div>
            </div>
        </div>
    </div>

    <section class="transaction-history p-0">
        <div class="container-fluid">
            <!-- refferal-information -->
            @if(isset($referrals) && 0 < count($referrals))
                <div class="row mt-5">
                    <div class="col-md-12 col-lg-12">
                        <div class="row" id="ref-label">
                            <div class="col-lg-2">
                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                     aria-orientation="vertical">
                                    @foreach($referrals as $key => $referral)
                                        <a class="btn-custom nav-link @if($key == '1')   active  @endif "
                                           id="v-pills-{{$key}}-tab" href="javascript:void(0)" data-bs-toggle="pill"
                                           data-bs-target="#v-pills-{{$key}}" role="tab"
                                           aria-controls="v-pills-{{$key}}"
                                           aria-selected="true">@lang('Level') {{$key}}</a>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-lg-10">
                                <div class="tab-content" id="v-pills-tabContent">
                                    @foreach($referrals as $key => $referral)
                                        <div class="tab-pane fade @if($key == '1') show active  @endif "
                                             id="v-pills-{{$key}}" role="tabpanel"
                                             aria-labelledby="v-pills-{{$key}}-tab">
                                            @if( 0 < count($referral))
                                                <div class="card-body">
                                                    <div class="cmn-table">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped align-middle">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col">@lang('Username')</th>
                                                                    <th scope="col">@lang('Email')</th>
                                                                    <th scope="col">@lang('Phone Number')</th>
                                                                    <th scope="col">@lang('Upline')</th>
                                                                    <th scope="col">@lang('Joined At')</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                @foreach($referral as $user)
                                                                    <tr>

                                                                        <td data-label="@lang('Username')">
                                                                            @lang($user->username)
                                                                        </td>
                                                                        <td data-label="@lang('Email')"
                                                                            class="">{{$user->email}}</td>
                                                                        <td data-label="@lang('Phone Number')">
                                                                            {{$user->phone}}
                                                                        </td>
                                                                        <td data-label="@lang('Upline')">
                                                                            {{ $user->uplineRefer($user->referral_id)->username }}
                                                                        </td>
                                                                        <td data-label="@lang('Joined At')">
                                                                            {{dateTime($user->created_at)}}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

