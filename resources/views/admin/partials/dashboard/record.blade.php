<div class="col-xl-5">
    <div class="row g-5">
        <div class="col-xl-6 col-sm-6 col-lg-3">
            <!-- Card -->
            <a class="card user-card card-hover-shadow" href="{{route('admin.users')}}"
               id="userRecord">
                <div class="card-body">
                    <div class="card-title-top">
                        <i class="fa-light fa-user"></i>
                        <h6 class="card-subtitle">@lang('Total Users')</h6>
                    </div>

                    <div class="row align-items-center gx-2 mb-1">
                        <div class="col-6">
                            <h2 class="card-title text-inherit userRecord-totalUsers"></h2>
                        </div>
                        <div class="col-6">
                            <div class="chartjs-custom" style="height: 3rem;">
                                <canvas class="" id="chartUserRecordsGraph">
                                </canvas>
                            </div>

                        </div>
                    </div>
                    <span class="badge userRecord-followupGrapClass">
                        <i class="bi-graph-up"></i> <span class="userRecord-followupGrap"></span>%
                      </span>
                    <span
                        class="text-body fs-6 ms-1 userRecord-chartPercentageIncDec"></span>
                </div>
            </a>
        </div>

        <div class="col-xl-6 col-sm-6 col-lg-3">
            <!-- Card -->
            <a class="card user-card card-hover-shadow" href="{{route('admin.users')}}"
               id="userRecord">
                <div class="card-body">
                    <div class="card-title-top">
                        <i class="fa-light fa-user"></i>
                        <h6 class="card-subtitle">@lang('Banned Users')</h6>
                    </div>

                    <div class="row align-items-center gx-2 mb-1">
                        <div class="col-6">
                            <h2 class="card-title text-inherit userRecord-bannedUsers"></h2>
                        </div>
                        <div class="col-6">
                            <div class="chartjs-custom" style="height: 3rem;">
                                <canvas class="" id="chartUserRecordsGraph">
                                </canvas>
                            </div>

                        </div>
                    </div>
                    <span class="badge userRecord-followupGrapClass">
                        <i class="bi-graph-up"></i> <span class="userRecord-followupGrap"></span>%
                      </span>
                    <span
                        class="text-body fs-6 ms-1 userRecord-chartPercentageIncDec"></span>
                </div>
            </a>
        </div>

        <div class="col-xl-6 col-sm-6 col-lg-3">
            <!-- Card -->
            <a class="card user-card card-hover-shadow" href="{{ route("admin.ticket", 'tickets') }}"
               id="ticketRecord">
                <div class="card-body">
                    <div class="card-title-top">
                        <i class="fa-light fa-headset nav-icon"></i>
                        <h6 class="card-subtitle">@lang("Pending Tickets")</h6>
                    </div>

                    <div class="row align-items-center gx-2 mb-1">
                        <div class="col-6">
                            <h2 class="card-title text-inherit ticketRecord-totalTickets"></h2>
                        </div>
                        <div class="col-6">
                            <div class="chartjs-custom" style="height: 3rem;">
                                <canvas class="" id="chartTicketRecordsGraph">
                                </canvas>
                            </div>

                        </div>
                    </div>
                    <span class="badge ticketRecord-followupGrapClass">
                        <i class="bi-graph-up"></i> <span class="ticketRecord-followupGrap"></span>%
                      </span>
                    <span
                        class="text-body fs-6 ms-1 ticketRecord-chartPercentageIncDec"></span>
                </div>
            </a>
        </div>

        <div class="col-xl-6 col-sm-6 col-lg-3">
            <!-- Card -->
            <a class="card user-card card-hover-shadow h-100" href="{{ route("admin.kyc.list") }}" id="kycRecord">
                <div class="card-body">
                    <div class="card-title-top">
                        <i class="fa-light fa-hourglass-end"></i>
                        <h6 class="card-subtitle">@lang("Pending KYC")</h6>
                    </div>
                    <div class="row align-items-center gx-2 mb-1">
                        <div class="col-6">
                            <h2 class="card-title text-inherit kycRecord-pendingKycs"></h2>
                        </div>
                        <div class="col-6">
                            <div class="chartjs-custom" style="height: 3rem;">
                                <canvas class="" id="chartKycRecordsGraph">
                                </canvas>
                            </div>
                        </div>
                    </div>
                    <span class="badge kycRecord-followupGrapClass">
                        <i class="bi-graph-up"></i> <span class="kycRecord-followupGrap"></span>%
                      </span>
                    <span
                        class="text-body fs-6 ms-1 kycRecord-chartPercentageIncDec"></span>
                </div>
            </a>
        </div>

    </div>
</div>

@push('script')
    <script>
        Notiflix.Block.standard('#userRecord');
        HSCore.components.HSChartJS.init(document.querySelector('#chartUserRecordsGraph'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                    borderColor: "#377dff",
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 0
                }]
            },
            options: {
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        display: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        postfix: "",
                        hasIndicator: true,
                        intersect: false
                    }
                }
            },
        });
        const chartUserRecordsGraph = HSCore.components.HSChartJS.getItem('chartUserRecordsGraph');

        updateChartUserRecordsGraph();

        async function updateChartUserRecordsGraph() {
            let $url = "{{ route('admin.chartUserRecords') }}"
            await axios.get($url)
                .then(function (res) {
                    $('.userRecord-totalUsers').text(res.data.userRecord.totalUsers);
                    $('.userRecord-bannedUsers').text(res.data.userRecord.bannedUsers);
                    $('.userRecord-followupGrapClass').addClass(res.data.userRecord.followupGrapClass);
                    $('.userRecord-followupGrap').text(res.data.userRecord.followupGrap);
                    $('.userRecord-chartPercentageIncDec').text(`from ${res.data.userRecord.chartPercentageIncDec}`);

                    chartUserRecordsGraph.data.labels = res.data.current_month_data_dates
                    chartUserRecordsGraph.data.datasets[0].data = res.data.current_month_datas
                    chartUserRecordsGraph.update();
                    Notiflix.Block.remove('#userRecord');
                })
                .catch(function (error) {

                });
        }

    </script>

    <script>
        Notiflix.Block.standard('#ticketRecord');
        HSCore.components.HSChartJS.init(document.querySelector('#chartTicketRecordsGraph'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                    borderColor: "#377dff",
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 0
                }]
            },
            options: {
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        display: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        postfix: "",
                        hasIndicator: true,
                        intersect: false
                    }
                }
            },
        });
        const chartTicketRecordsGraph = HSCore.components.HSChartJS.getItem('chartTicketRecordsGraph');

        updateChartTicketRecordsGraph();

        async function updateChartTicketRecordsGraph() {
            let $url = "{{ route('admin.chartTicketRecords') }}"
            await axios.get($url)
                .then(function (res) {
                    $('.ticketRecord-totalTickets').text(res.data.ticketRecord.pending);
                    $('.ticketRecord-followupGrapClass').addClass(res.data.ticketRecord.followupGrapClass);
                    $('.ticketRecord-followupGrap').text(res.data.ticketRecord.followupGrap);
                    $('.ticketRecord-chartPercentageIncDec').text(`from ${res.data.ticketRecord.chartPercentageIncDec}`);

                    chartTicketRecordsGraph.data.labels = res.data.current_month_data_dates
                    chartTicketRecordsGraph.data.datasets[0].data = res.data.current_month_datas
                    chartTicketRecordsGraph.update();
                    Notiflix.Block.remove('#ticketRecord');
                })
                .catch(function (error) {

                });
        }

    </script>

    <script>
        Notiflix.Block.standard('#kycRecord');
        HSCore.components.HSChartJS.init(document.querySelector('#chartKycRecordsGraph'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                    borderColor: "#377dff",
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 0
                }]
            },
            options: {
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        display: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        postfix: "",
                        hasIndicator: true,
                        intersect: false
                    }
                }
            },
        });
        const chartKycRecordsGraph = HSCore.components.HSChartJS.getItem('chartKycRecordsGraph');

        updateChartKycRecordsGraph();

        async function updateChartKycRecordsGraph() {
            let $url = "{{ route('admin.chartKycRecords') }}"
            await axios.get($url)
                .then(function (res) {
                    $('.kycRecord-pendingKycs').text(res.data.kycRecord.pendingKYC);
                    $('.kycRecord-followupGrapClass').addClass(res.data.kycRecord.followupGrapClass);
                    $('.kycRecord-followupGrap').text(res.data.kycRecord.followupGrap);
                    $('.kycRecord-chartPercentageIncDec').text(`from ${res.data.kycRecord.chartPercentageIncDec}`);

                    chartKycRecordsGraph.data.labels = res.data.current_month_data_dates
                    chartKycRecordsGraph.data.datasets[0].data = res.data.current_month_datas
                    chartKycRecordsGraph.update();
                    Notiflix.Block.remove('#kycRecord');
                })
                .catch(function (error) {

                });
        }

    </script>

    <script>
        // Notiflix.Block.standard('#transactionRecord');
        HSCore.components.HSChartJS.init(document.querySelector('#chartTransactionRecordsGraph'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                    borderColor: "#377dff",
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 0
                }]
            },
            options: {
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        display: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        postfix: "",
                        hasIndicator: true,
                        intersect: false
                    }
                }
            },
        });
        const chartTransactionRecordsGraph = HSCore.components.HSChartJS.getItem('chartTransactionRecordsGraph');

        updateChartTransactionRecordsGraph();

        async function updateChartTransactionRecordsGraph() {
            let $url = "{{ route('admin.chartTransactionRecords') }}"
            await axios.get($url)
                .then(function (res) {
                    $('.transactionRecord-totalTransaction').text(res.data.transactionRecord.totalTransaction);
                    $('.transactionRecord-followupGrapClass').addClass(res.data.transactionRecord.followupGrapClass);
                    $('.transactionRecord-followupGrap').text(res.data.transactionRecord.followupGrap);
                    $('.transactionRecord-chartPercentageIncDec').text(`from ${res.data.transactionRecord.chartPercentageIncDec}`);

                    chartTransactionRecordsGraph.data.labels = res.data.current_month_data_dates
                    chartTransactionRecordsGraph.data.datasets[0].data = res.data.current_month_datas
                    chartTransactionRecordsGraph.update();
                    Notiflix.Block.remove('#transactionRecord');
                })
                .catch(function (error) {

                });
        }

    </script>
@endpush
