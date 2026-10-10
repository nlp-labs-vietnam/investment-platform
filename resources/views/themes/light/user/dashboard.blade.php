@extends(template().'layouts.user')
@section('title',trans('Dashboard'))
@section('content')
    @push('style')
        <style>
            .balance-box {
                background: linear-gradient(to right,{{hex2rgba(basicControl()->primary_color)}},{{hex2rgba(basicControl()->secondary_color)}});
            }
        </style>
    @endpush

    <div class="container-fluid" id="firebase-app">
        <div class="main row">
            <div class="col-12">
                <div class="row g-3">
                    <div class="col-xl-4 col-lg-6">
                        <div class="card-box balance-box p-0 h-100">
                            <div class="user-account-number p-4 h-100">
                                <i class="account-wallet far fa-wallet"></i>
                                <div class="mb-4">
                                    <h5 class="text-white mb-2">
                                        @lang('Main Balance')
                                    </h5>
                                    <h3>
                                    <span
                                        class="text-white"><small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($walletBalance, basicControl()->fraction_number)}}</span>
                                    </h3>
                                </div>
                                <div class="">
                                    <h5 class="text-white mb-2">
                                        @lang('Interest Balance')
                                    </h5>
                                    <h3><span
                                            class="text-white otal_available__balance"><small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($interestBalance, basicControl()->fraction_number)}}</span>
                                    </h3>
                                </div>
                                <a href="{{ route('user.add.fund') }}" class="cash-in"><i
                                        class="fal fa-plus me-1"></i> @lang('Cash In')</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 d-sm-block d-none">
                        <div class="row g-3">
                            <div class="col-lg-12 col-6">
                                <div class="dashboard-box gr-bg-1">
                                    <h5 class="text-white">@lang('Total Deposit')</h5>
                                    <h3 class="text-white">
                                        <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{getAmount($totalDeposit, basicControl()->fraction_number)}}</span>
                                    </h3>
                                    <i class="fal fa-file-invoice-dollar text-white"></i>
                                </div>
                            </div>

                            <div class="col-lg-12 col-6">
                                <div class="dashboard-box gr-bg-2">
                                    <h5 class="text-white">@lang('Total Payout')</h5>
                                    <h3 class="text-white">
                                        <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{getAmount($totalPayout)}}</span>
                                    </h3>
                                    <i class="fal fa-usd-circle text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 d-sm-block d-none">
                        <div class="row g-3">
                            <div class="col-xl-12 col-6">
                                <div class="dashboard-box gr-bg-3">
                                    <h5 class="text-white">@lang('Total Invest')</h5>
                                    <h3 class="text-white">
                                        <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{getAmount($investment['totalInvestAmount'])}}</span>
                                    </h3>
                                    <i class="far fa-funnel-dollar text-white"></i>
                                </div>
                            </div>
                            <div class="col-xl-12 col-6 box">
                                <div class="dashboard-box gr-bg-4">
                                    <h5 class="text-white">@lang('Running Invest')</h5>
                                    <h3 class="text-white">
                                        <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span> {{getAmount($investment['runningInvestAmount'])}}</span>
                                    </h3>
                                    <i class="far fa-funnel-dollar text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- main -->
        <div class="main row">
            <div class="col-12">
                <div class="dashboard-box-wrapper d-none d-lg-block">
                    <div class="row g-3 mb-4">

                        <div class="col-xl-3 col-md-6 box">
                            <div class="dashboard-box">
                                <h5>@lang('Total Investment')</h5>
                                <h3>{{ $investment['totalInvestment'] }}</h3>
                                <i class="fal fa-lightbulb-dollar"></i>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 box">
                            <div class="dashboard-box">
                                <h5>@lang('Running Investment')</h5>
                                <h3>{{ $investment['runningInvestment'] }}</h3>
                                <i class="fal fa-lightbulb-dollar"></i>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 box">
                            <div class="dashboard-box">
                                <h5>@lang('Due Investment')</h5>
                                <h3>{{ $investment['dueInvestment'] }}</h3>
                                <i class="fal fa-lightbulb-dollar"></i>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 box">
                            <div class="dashboard-box">
                                <h5>@lang('Completed Investment')</h5>
                                <h3>{{ $investment['completedInvestment'] }}</h3>
                                <i class="fal fa-lightbulb-dollar"></i>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 box">
                            <div class="dashboard-box">
                                <h5>@lang('Total Referral Bonus')</h5>
                                <h3>
                                    <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($depositBonus + $investBonus + $profitBonus)}}
                                </h3>
                                <i class="fal fa-lightbulb-dollar"></i>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 box">
                            <div class="dashboard-box">
                                <h5>@lang('Last Referral Bonus')</h5>
                                <h3>
                                    <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{ getAmount($lastBonus) }}</span>
                                </h3>
                                <i class="far fa-badge-dollar"></i>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 box">
                            <div class="dashboard-box">
                                <h5>@lang('Total Earn')</h5>
                                <h3>
                                    <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{getAmount($totalInterestProfit, basicControl()->fraction_number)}}</span>
                                </h3>
                                <i class="far fa-hand-holding-usd"></i>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 box">
                            <div class="dashboard-box">
                                <h5>@lang('Total Ticket')</h5>
                                <h3>{{$ticket}}</h3>
                                <i class="fal fa-ticket"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-lg-none mb-4">
                    <div class="card-box-wrapper owl-carousel card-boxes">
                        <div class="dashboard-box gr-bg-1">
                            <h5 class="text-white">@lang('Main Balance')</h5>
                            <h3 class="text-white">
                                <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($walletBalance, basicControl()->fraction_number)}}
                            </h3>
                            <i class="fal fa-funnel-dollar text-white"></i>
                        </div>
                        <div class="dashboard-box gr-bg-2">
                            <h5 class="text-white">@lang('Interest Balance')</h5>
                            <h3 class="text-white">
                                <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($interestBalance, basicControl()->fraction_number)}}
                            </h3>
                            <i class="fal fa-hand-holding-usd text-white"></i>
                        </div>
                        <div class="dashboard-box gr-bg-3">
                            <h5 class="text-white">@lang('Total Deposit')</h5>
                            <h3 class="text-white">
                                <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($totalDeposit, basicControl()->fraction_number)}}
                            </h3>
                            <i class="fal fa-box-usd text-white"></i>
                        </div>
                        <div class="dashboard-box gr-bg-5">
                            <h5 class="text-white">@lang('Total Invest')</h5>
                            <h3 class="text-white">
                                <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($investment['totalInvestAmount'])}}
                            </h3>
                            <i class="fal fa-search-dollar text-white"></i>
                        </div>
                        <div class="dashboard-box gr-bg-5">
                            <h5 class="text-white">@lang('Running Invest')</h5>
                            <h3 class="text-white">
                                <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($investment['runningInvestAmount'])}}
                            </h3>
                            <i class="fal fa-search-dollar text-white"></i>
                        </div>
                        <div class="dashboard-box gr-bg-4">
                            <h5 class="text-white">@lang('Total Earn')</h5>
                            <h3 class="text-white">
                                <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($totalInterestProfit, basicControl()->fraction_number)}}
                            </h3>
                            <i class="fal fa-badge-dollar text-white"></i>
                        </div>
                        <div class="dashboard-box gr-bg-6">
                            <h5 class="text-white">@lang('Total Payout')</h5>
                            <h3 class="text-white">
                                <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($totalPayout)}}
                            </h3>
                            <i class="fal fa-usd-circle text-white"></i>
                        </div>
                        <div class="dashboard-box gr-bg-7">
                            <h5 class="text-white">@lang('Total Referral Bonus')</h5>
                            <h3 class="text-white">
                                <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($depositBonus + $investBonus)}}
                            </h3>
                            <i class="fal fa-lightbulb-dollar text-white"></i>
                        </div>

                        <div class="dashboard-box gr-bg-8">
                            <h5 class="text-white">@lang('Last Referral Bonus')</h5>
                            <h3 class="text-white">
                                <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small>{{getAmount($lastBonus, basicControl()->fraction_number)}}
                            </h3>
                            <i class="fal fa-box-open text-white"></i>
                        </div>

                        <div class="dashboard-box gr-bg-9">
                            <h5 class="text-white">@lang('Total Ticket')</h5>
                            <h3 class="text-white">{{$ticket}}</h3>
                            <i class="fal fa-ticket text-white"></i>
                        </div>
                    </div>
                </div>

                <!---- charts ----->
                <div class="chart-information ">
                    <div class="row">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="progress-wrapper">
                                <div id="container" class="apexcharts-canvas"></div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="progress-wrapper2">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 box mb-3">
                                        <div class="badge-dashboard-box2" id="custom_badge_dashboad_box2">
                                            <h5 class="mb-0">@lang('Current Level')</h5>
                                            <div>

                                                <div class="level-box">
                                                    <h6 class="m-0">
                                                        @if($lastInvestorRank == null)
                                                            <i class="fa fa-times"></i>
                                                        @else
                                                            @lang($investorRank->rank_level)
                                                        @endif
                                                    </h6>
                                                    @if($lastInvestorRank != null && $investorRank->rank_level != null)
                                                        <img
                                                            src="{{ getFile($lastInvestorRank->driver, $lastInvestorRank->rank_icon) }}"
                                                            alt="" class="level-rank"/>
                                                    @endif
                                                </div>

                                                @if($lastInvestorRank != null)
                                                    <p class="custom__p {{ $investorRank->rank_level == null ? 'opacity-0' : '' }}"> {{ $investorRank->rank_level == null ? '...' : trans($investorRank->rank_name) }}</p>
                                                @else
                                                    <p class="opacity-0">@lang('no level')</p>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 box mb-3">
                                        <div class="badge-dashboard-box1">
                                            <h5>@lang('Level Bonus')</h5>
                                            <h3>
                                                <small><sup>{{trans(basicControl()->currency_symbol)}}</sup></small><span>{{ $totalRankBonus }}</span>
                                            </h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div>
                                            <div class="badge-dashboard-box2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h5>@lang('All Ranks')</h5>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    @foreach($allRanks as $key => $rank)
                                                        <div class="col-xl-3 col-md-6 box">
                                                            <div
                                                                class="badge-box badge-box-two {{ Auth::user()->ranking($rank->id) == 'true' ? '' : 'locked' }}"
                                                                id="badge-box-two">
                                                                <img
                                                                    src="{{ getFile($rank->driver, $rank->rank_icon) }}"
                                                                    alt=""/>
                                                                <p class="mb-3 text-center m-auto">@lang($rank->rank_name)</p>
                                                                <div class="lock-icon">
                                                                    <i class="far fa-lock-alt"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- refferal-information -->
                <div class="search-bar refferal-link  g-4 mt-4 mb-4 coin-box-wrapper">
                    <form class="mb-3">
                        <div class="row g-3 align-items-end">
                            <div class="input-box col-lg-12">
                                <label for="">@lang('Referral Link')</label>
                                <div class="input-group mt-0">
                                    <input
                                        type="text"
                                        value="{{route('register.sponsor',[Auth::user()->username])}}"
                                        class="form-control"
                                        id="sponsorURL"
                                        readonly/>
                                    <button class="gold-btn copyReferalLink" type="button"><i
                                            class="fal fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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
                var options = {
                    theme: {
                        mode: "light",
                    },

                    series: [
                        {
                            name: "{{trans('Deposit')}}",
                            color: 'rgba(255, 72, 0, 1)',
                            data: {!! $monthly['funding']->flatten() !!}
                        },
                        {
                            name: "{{trans('Deposit Bonus')}}",
                            color: 'rgba(39, 144, 195, 1)',
                            data: {!! $monthly['referralFundBonus']->flatten() !!}
                        },
                        {
                            name: "{{trans('Investment')}}",
                            color: 'rgba(247, 147, 26, 1)',
                            data: {!! $monthly['investment']->flatten() !!}
                        },
                        {
                            name: "{{trans('Investment Bonus')}}",
                            color: 'rgba(136, 203, 245, 1)',
                            data: {!! $monthly['referralInvestBonus']->flatten() !!}
                        },
                        {
                            name: "{{trans('Profit')}}",
                            color: 'rgba(247, 147, 26, 1)',
                            data: {!! $monthly['monthlyGaveProfit']->flatten() !!}
                        },
                        {
                            name: "{{trans('Payout')}}",
                            color: 'rgba(240, 16, 16, 1)',
                            data: {!! $monthly['payout']->flatten() !!}
                        },
                    ],
                    chart: {
                        type: 'bar',
                        height: 350,
                        background: '#fff',
                        toolbar: {
                            show: false
                        }

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
                            text: ""
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        colors: ['#000'],
                        y: {
                            formatter: function (val) {
                                return "{{trans(basicControl()->currency_symbol)}}" + val + ""
                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#container"), options);
                chart.render();

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
                                $(_this).html('<i class="fas fa-copy"></i>');
                            }, 500);
                        });
                    });

                    $(document).on('click', '.loginAccount', function () {
                        var route = $(this).data('route');
                        $('.loginAccountAction').attr('action', route)
                    });

                    $(document).on('click', '.copyReferalLink', function () {
                        var _this = $(this)[0];
                        var copyText = $(this).siblings('input').val();
                        navigator.clipboard.writeText(copyText).then(function () {
                            $(_this).text('Copied');
                            setTimeout(function () {
                                $(_this).text('');
                                $(_this).html('<i class="fal fa-copy"></i>');
                            }, 500);
                        });
                    });
                })
            </script>

    @endpush


