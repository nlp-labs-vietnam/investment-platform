@extends(template().'layouts.user')
@section('title',trans('Dashboard'))
@section('content')
    <div class="dashboard-top-box">
        <div class="row g-4">
            <div class="col-lg-3 col-sm-4 col-6">
                <p class="sub-title">@lang('Main Balance')</p>
                <h3 class="mb-1">{{trans(basicControl()->currency_symbol)}}{{fractionNumber(getAmount($walletBalance))}}</h3>
                <a href="{{ route('user.add.fund') }}" class="cmn-btn3"><i
                        class="fa-regular fa-plus-circle"></i>@lang('Cash In')</a>

            </div>
            <div class="col-lg-3 col-sm-4 col-6">
                <p class="sub-title">@lang('Interest Balance')</p>
                <h3 class="mb-1">{{trans(basicControl()->currency_symbol)}}{{fractionNumber(getAmount($interestBalance))}}</h3>
                <a href="{{ route('user.payout') }}" class="cmn-btn3"><i
                        class="fa-regular fa-circle-dollar"></i>@lang('Withdraw')</a>
            </div>
            <div class="col-lg-6 col-sm-4 justify-content-sm-end align-items-end d-none d-sm-flex">
                <a href="{{ route('user.transaction') }}" class="cmn-btn3"><i
                        class="fa-regular fa-filter"></i>@lang('Transactions')</a>
            </div>
        </div>
    </div>

    <div class="mt-30">
        <!-- Dashboard card start -->
        <div class="row g-4">
            <div class="col-xxl-3 col-sm-6 card-item">
                <div class="box-card">
                    <div class="icon-box">
                        <i class="fa-regular fa-sack-dollar"></i>
                    </div>
                    <div class="text-box">
                        <p class="mb-1">@lang('Total Deposit')</p>
                        <h4>{{trans(basicControl()->currency_symbol)}}{{fractionNumber(getAmount($totalDeposit))}}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6 card-item">
                <div class="box-card">
                    <div class="icon-box">
                        <i class="fa-sharp fa-regular fa-hands-holding-dollar"></i>
                    </div>
                    <div class="text-box">
                        <p class="mb-1">@lang('Total Invest')</p>
                        <h4>{{trans(basicControl()->currency_symbol)}}{{fractionNumber(getAmount($investment['totalInvestAmount']))}}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6 card-item">
                <div class="box-card">
                    <div class="icon-box">
                        <i class="fa-regular fa-circle-dollar"></i>
                    </div>
                    <div class="text-box">
                        <p class="mb-1">@lang('Total Payout')</p>
                        <h4>{{trans(basicControl()->currency_symbol)}}{{fractionNumber(getAmount($totalPayout))}}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6 card-item">
                <div class="box-card">
                    <div class="icon-box">
                        <i class="fa-regular fa-chart-line-up"></i>
                    </div>
                    <div class="text-box">
                        <p class="mb-1">@lang('Running Invest')</p>
                        <h4>{{trans(basicControl()->currency_symbol)}}{{fractionNumber(getAmount($investment['runningInvestAmount']))}}</h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dashboard card end -->
        <div class="card mt-25">

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xxl-3 col-sm-6">
                        <div class="box-card3">
                            <div class="icon-box">
                                <i class="fa-solid fa-chart-line-up"></i>
                            </div>
                            <div class="text-box">
                                <p class="mb-1">@lang('Total Investment')</p>
                                <h4 class="mb-0">{{ $investment['totalInvestment'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="box-card3">
                            <div class="icon-box">
                                <i class="fa-solid fa-chart-line-up"></i>
                            </div>
                            <div class="text-box">
                                <p class="mb-1">@lang('Running Investment')</p>
                                <h4 class="mb-0">{{ $investment['runningInvestment'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="box-card3">
                            <div class="icon-box">
                                <i class="fa-solid fa-chart-line-up"></i>
                            </div>
                            <div class="text-box">
                                <p class="mb-1">@lang('Due Investment')</p>
                                <h4 class="mb-0">{{ $investment['dueInvestment'] }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-sm-6">
                        <div class="box-card3">
                            <div class="icon-box">
                                <i class="fa-solid fa-chart-line-up"></i>
                            </div>
                            <div class="text-box">
                                <p class="mb-1">@lang('Completed Investment')</p>
                                <h4 class="mb-0">{{ $investment['completedInvestment'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="box-card3">
                            <div class="icon-box">
                                <i class="fa-regular fa-gift"></i>
                            </div>
                            <div class="text-box">
                                <p class="mb-1">@lang('Total Referral Bonus')</p>
                                <h4 class="mb-0">{{trans(basicControl()->currency_symbol)}}{{fractionNumber(getAmount($depositBonus + $investBonus + $profitBonus))}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="box-card3">
                            <div class="icon-box">
                                <i class="fa-regular fa-gift"></i>
                            </div>
                            <div class="text-box">
                                <p class="mb-1">@lang('Last Referral Bonus')</p>
                                <h4 class="mb-0">{{trans(basicControl()->currency_symbol)}}{{ getAmount($lastBonus) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="box-card3">
                            <div class="icon-box">
                                <i class="fa-regular fa-hand-holding-usd"></i>
                            </div>
                            <div class="text-box">
                                <p class="mb-1">@lang('Total Earn')</p>
                                <h4 class="mb-0">{{trans(basicControl()->currency_symbol)}}{{getAmount($totalInterestProfit, basicControl()->fraction_number)}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="box-card3">
                            <div class="icon-box">
                                <i class="fa-regular fa-ticket"></i>
                            </div>
                            <div class="text-box">
                                <p class="mb-1">@lang('Total Ticket')</p>
                                <h4 class="mb-0">{{$ticket}}</h4>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="mt-25">
            <div class="row g-4">
                <div class="col-xxl-9 col-lg-8">
                    <div id="columnChart"></div>
                </div>
                @if(basicControl()->is_first_deposit_bonus)
                    <div class="col-xxl-3 col-lg-4">
                        <div class="bonus-box">
                            <div class="icon-box">
                                <i class="fa-regular fa-gift"></i>
                            </div>
                            <div class="text-box">
                                <h4 class="mb-10">{{ basicControl()->currency_symbol }}{{ basicControl()->first_deposit_bonus }} @lang('Welcome Bonus')</h4>
                                <p>@lang('By making a first deposit of') {{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount(basicControl()->minimum_first_deposit)) }} @lang('or more, you will receive a') {{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount(basicControl()->first_deposit_bonus)) }} @lang('welcome
                                bonus in your balance!')</p>
                                <a href="{{ route('user.add.fund') }}" class="cmn-btn5 mt-10">@lang('get bonus')</a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="mb-10 text-center">@lang('All Ranks')</h4>
                            <div class="row g-4">
                                @foreach($allRanks as $key => $rank)
                                    <div class="col-lg-3 col-sm-3 col-6">
                                        <div class="badge-box">
                                            <img class="img" src="{{ getFile($rank->driver, $rank->rank_icon) }}"
                                                 alt="">
                                            <p class="title">@lang($rank->rank_name)</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h5 class="mb-20">@lang('Current Rank')</h5>
                            <div class="lavel-box">
                                <h6 class="title">
                                    @if($lastInvestorRank == null)
                                        <img src="{{ asset(template(true).'img/close.png') }}" alt="" width="50">
                                    @else
                                        @lang($investorRank->rank_level)
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h5 class="mb-20">@lang('Rank Bonus')</h5>
                            <h4 class="mt-10 mb-0">{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount($totalRankBonus)) }}</h4>
                        </div>
                    </div>

                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-20">@lang('Referral Link')</h4>
                            <div class="input-group">
                                <input id="referralURL" type="text" class="form-control"
                                       value="{{route('register.sponsor',[Auth::user()->username])}}"
                                       aria-label="Recipient's username" aria-describedby="basic-addon2"
                                       readonly>
                                <div class="input-group-text" id="copyBtn"><i
                                        class="fa-regular fa-copy"></i> @lang('copy')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="{{asset(template(true).'js/apexcharts.js')}}"></script>
    @if($firebaseNotify)
        <script type="module">

            import {initializeApp} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
            import {
                getMessaging,
                getToken,
                onMessage
            } from "https://www.gstatic.com/firebasejs/9.17.1/firebase-messaging.js";

            const firebaseConfig = {
                apiKey: "{{$firebaseNotify['apiKey']}}",
                authDomain: "{{$firebaseNotify['authDomain']}}",
                projectId: "{{$firebaseNotify['projectId']}}",
                storageBucket: "{{$firebaseNotify['storageBucket']}}",
                messagingSenderId: "{{$firebaseNotify['messagingSenderId']}}",
                appId: "{{$firebaseNotify['appId']}}",
                measurementId: "{{$firebaseNotify['measurementId']}}"
            };

            const app = initializeApp(firebaseConfig);
            const messaging = getMessaging(app);
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('{{ getProjectDirectory() }}' + `/firebase-messaging-sw.js`, {scope: './'}).then(function (registration) {
                        requestPermissionAndGenerateToken(registration);
                    }
                ).catch(function (error) {
                });
            } else {
            }

            onMessage(messaging, (payload) => {
                if (payload.data.foreground || parseInt(payload.data.foreground) == 1) {
                    const title = payload.notification.title;
                    const options = {
                        body: payload.notification.body,
                        icon: payload.notification.icon,
                    };
                    new Notification(title, options);
                }
            });

            function requestPermissionAndGenerateToken(registration) {
                document.addEventListener("click", function (event) {
                    if (event.target.id == 'allow-notification') {
                        Notification.requestPermission().then((permission) => {
                            if (permission === 'granted') {
                                getToken(messaging, {
                                    serviceWorkerRegistration: registration,
                                    vapidKey: "{{$firebaseNotify['vapidKey']}}"
                                })
                                    .then((token) => {
                                        $.ajax({
                                            url: "{{ route('user.save.token') }}",
                                            method: "post",
                                            data: {
                                                token: token,
                                            },
                                            success: function (res) {
                                            }
                                        });
                                        window.newApp.notificationPermission = 'granted';
                                    });
                            } else {
                                window.newApp.notificationPermission = 'denied';
                            }
                        });
                    }
                });
            }
        </script>
        <script>
            window.newApp = new Vue({
                el: "#firebase-app",
                data: {
                    user_foreground: '',
                    user_background: '',
                    notificationPermission: Notification.permission,
                    is_notification_skipped: sessionStorage.getItem('is_notification_skipped') == '1'
                },
                mounted() {
                    sessionStorage.clear();
                    this.user_foreground = "{{$firebaseNotify['user_foreground']}}";
                    this.user_background = "{{$firebaseNotify['user_background']}}";
                },
                methods: {
                    skipNotification() {
                        sessionStorage.setItem('is_notification_skipped', '1')
                        this.is_notification_skipped = true;
                    }
                }
            });
        </script>
    @endif

    <script>
        "use strict";

        // Apexcharts start
        // Columnchart
            var options = {
                series: [
                    {
                        name: "{{trans('Deposit')}}",
                        color: '#567eae',
                        data: {!! $monthly['funding']->flatten() !!}
                    },
                    {
                        name: '{{trans('Deposit Bonus')}}',
                        color: 'rgb(174,134,86)',
                        data: {!! $monthly['referralFundBonus']->flatten() !!}
                    },
                    {
                        name: '{{trans('Investment')}}',
                        color: '#5a56ae',
                        data: {!! $monthly['investment']->flatten() !!}
                    },
                    {
                        name: '{{trans('Investment Bonus')}}',
                        color: '#e7bb89',
                        data: {!! $monthly['referralInvestBonus']->flatten() !!}
                    },
                    {
                        name: '{{trans('Profit')}}',
                        color: '#000000',
                        data: {!! $monthly['monthlyGaveProfit']->flatten() !!}
                    },
                    {
                        name: '{{trans('Payout')}}',
                        color: '#3D5300',
                        data: {!! $monthly['payout']->flatten() !!}
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: {!! $monthly['investment']->keys() !!},

                },
                yaxis: {
                    title: {
                        text: ''
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "{{trans(basicControl()->currency_symbol)}}" + val + ""
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#columnChart"), options);
            chart.render();

        //


        $(document).on('click', '#details', function () {
            var title = $(this).data('servicetitle');
            var description = $(this).data('description');
            $('#title').text(title);
            $('#servicedescription').text(description);
        });

        $(document).ready(function () {
        let isActiveCronNotification = '{{ basicControl()->is_active_cron_notification }}';
        if (isActiveCronNotification == 1)
            $('#cron-info').modal('show');
        $(document).on('click', '.copy-btn', function () {
            var _this = $(this)[0];
            var copyText = $(this).parents('.input-group-append').siblings('input').val();
            navigator.clipboard.writeText(copyText).then(function () {
                $(_this).text('Copied');
                setTimeout(function () {
                    $(_this).text('');
                    $(_this).html('<i class="fa-solid fa-copy"></i>');
                }, 500);
            });
        });

        $(document).on('click', '.loginAccount', function () {
            var route = $(this).data('route');
            $('.loginAccountAction').attr('action', route)
        });

        $(document).on('click', '#copyBtn', function () {
            var copyText = $('#referralURL').val();
            navigator.clipboard.writeText(copyText).then(function () {
                $('#copyBtn').html('<i class="fa-regular fa-check"></i> @lang("Copied")');
                setTimeout(function () {
                    $('#copyBtn').html('<i class="fa-regular fa-copy"></i> @lang("copy")');
                }, 1500);
            });
        });

        $(document).on('click', '.copyReferalLink', function () {
            var _this = $(this)[0];
            var copyText = $(this).siblings('input').val();
            navigator.clipboard.writeText(copyText).then(function () {
                $(_this).text('Copied');
                setTimeout(function () {
                    $(_this).text('');
                    $(_this).html('<i class="fa-light fa-copy"></i>');
                }, 500);
            });
        });
    })
    </script>

@endpush


