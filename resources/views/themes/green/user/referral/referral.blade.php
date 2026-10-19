@extends(template().'layouts.user')
@section('title',trans('Referral'))
@section('content')
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">

                <div class="row mt-4 mb-3">
                    <div class="col ms-2">
                        <div class="header-text-full d-flex justify-content-between align-items-end">
                            <nav aria-label="breadcrumb" class="">
                                <h3 class="dashboard_breadcurmb_heading mb-1">@lang('Referral History')</h3>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">@lang('Invest History')</li>
                                </ol>
                            </nav>
                            <div class="d-flex justify-content-end">
                                <a href="{{route('user.referral.bonus')}}" class="cmn-btn"><i class="la la-money-bill"></i> @lang('Referral Bonus')</a>
                            </div>
                        </div>
                    </div>
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

                <div class="card mt-3">
                    <div class="card-body">
                        @if(0 < count($directReferralUsers))
                            <div class="user-wrapper">
                                <div class="user-table">
                                    <div class="">
                                        <div class=" px-0">
                                            <div class="cmn-table skltbs-panel">
                                                <div class="table-responsive">
                                                    <table class="table table-striped align-middle">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">@lang('Username')</th>
                                                            <th scope="col">@lang('Level')</th>
                                                            <th scope="col">@lang('Email')</th>
                                                            <th scope="col">@lang('Phone Number')</th>
                                                            <th scope="col">@lang('Joined At')</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="block-statistics">
                                                            @forelse($directReferralUsers as $user)
                                                                <tr id="user-{{ $user->id }}" data-level="0" data-loaded="false">
                                                                    <td data-label="@lang('Username')">
                                                                        <a href="javascript:void(0)"
                                                                           class="{{ count(getDirectReferralUsers($user->id)) > 0 ? 'nextDirectReferral' : '' }}"
                                                                           data-id="{{ $user->id }}"
                                                                        >
                                                                            @if(count(getDirectReferralUsers($user->id)) > 0)
                                                                                <i class="fa-regular fa-circle-down color-primary"></i>
                                                                            @endif
                                                                            @lang($user->username)
                                                                        </a>
                                                                    <td data-label="@lang('Level')">
                                                                        @lang('Level 1')
                                                                    </td>
                                                                    <td data-label="@lang('Email')" class="">{{$user->email}}</td>
                                                                    <td data-label="@lang('Phone Number')">
                                                                        {{$user->phone}}
                                                                    </td>
                                                                    <td data-label="@lang('Joined At')">
                                                                        {{dateTime($user->created_at)}}
                                                                    </td>

                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <th colspan="100%" class="text-center text-dark">
                                                                        <div class="no_data_iamge text-center">
                                                                            <img class="no_image_size" src="{{ asset('assets/global/img/oc-error-light.svg') }}">
                                                                        </div>
                                                                        <p class="text-center emptyMessage">@lang('Referral history is empty here!.')</p>
                                                                    </th>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .notiflix-block {
            background-color: rgba(0, 0, 0, 0) !important;
        }

        .notiflix-block .notiflix-spinner {
            border-color: var(--primary);
            border-top-color: var(--primary) !important;
        }
    </style>
@endpush

@push('script')
    <script>
        'use strict'
        $(document).on('click', '.nextDirectReferral', function () {
            let _this = $(this);
            let parentRow = _this.closest('tr');

            if (parentRow.data('loaded')) {
                return;
            }

            getDirectReferralUser(_this);
        });

        function getDirectReferralUser(_this) {

            Notiflix.Block.standard('.block-statistics');

            let userId = _this.data('id');
            let parentRow = _this.closest('tr');
            let currentLevel = parseInt(parentRow.data('level')) + 1;
            let downLabel = currentLevel + 1;

            setTimeout(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('user.myGetDirectReferralUser') }}",
                    method: 'POST',
                    data: {
                        userId: userId,
                    },
                    success: function (response) {

                        Notiflix.Block.remove('.block-statistics');
                        let directReferralUsers = response.data;

                        let referralData = '';

                        directReferralUsers.forEach(function (directReferralUser) {
                            referralData += `
                            <tr id="user-${directReferralUser.id}" data-level="${currentLevel}">
                                <td data-label="@lang('Username')" style="padding-left: ${currentLevel * 35}px;">
                                    <a class="${directReferralUser.count_direct_referral > 0 ? 'nextDirectReferral' : ''}" href="javascript:void(0)" style="border-bottom: none !important;" data-id="${directReferralUser.id}">
                                        ${directReferralUser.count_direct_referral > 0 ? ' <i class="fa-regular fa-circle-down color-primary"></i>' : ''}
                                        ${directReferralUser.username}
                                    </a>
                                </td>

                                <td data-label="@lang('Level')">
                                     <span class="text-info">Level ${downLabel}</span>
                                </td>

                                <td data-label="@lang('Email')">
                                    ${directReferralUser.email ? directReferralUser.email : '-'}
                                </td>
                                <td data-label="@lang('Phone Number')">
                                     ${directReferralUser.phone??'-'}
                                </td>

                                <td data-label="Joined At">
                                    ${directReferralUser.joined_at}
                                </td>
                                </tr>`;
                            });

                        parentRow.data('loaded', true);

                        $(`#user-${userId}`).after(referralData);
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    }
                });
            }, 100);
        }

        function copyFunction() {
            var copyText = document.getElementById("sponsorURL");
            navigator.clipboard.writeText(copyText.value).then(function () {
                Notiflix.Notify.success(`Copied: ${copyText.value}`);
            });
        }

    </script>
@endpush
