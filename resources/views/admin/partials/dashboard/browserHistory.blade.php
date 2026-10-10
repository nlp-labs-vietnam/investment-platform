<div class="row">
    <div class="col-lg-4 mb-3 mb-lg-5" id="browserHistory">
        <!-- Card -->
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-header-title">@lang("Browser History")</h4>
                <button id="js-daterangepicker-browser-predefined" class="btn btn-white btn-sm dropdown-toggle">
                    <i class="bi-calendar-week"></i>
                    <span class="js-daterangepicker-browser-predefined-preview ms-1"></span>
                </button>
            </div>
            <!-- Body -->
            <div class="card-body text-center">
                <div class="chart-section-browser">
                    <div class="h3">
                            <span class="badge bg-soft-info text-info rounded-pill">
                              <i class="bi-check-circle-fill me-1"></i> @lang("On track")
                            </span>
                    </div>

                    <!-- Chart Half -->
                    <!-- Pie Chart -->
                    <div class="chartjs-custom mb-3 mb-sm-5" style="height: 14rem;">
                        <canvas class="" id="chartBrowserHistoryGraph">
                        </canvas>
                    </div>
                    <!-- End Pie Chart -->
                    <hr>
                    <div class="row col-divider">
                        <div class="col text-center">
                            <span class="d-block"><span class="selected-date-value-browser"></span>@lang(" Browser History")</span>
                        </div>
                    </div>
                    <!-- End Row -->
                </div>

                <div class="text-center p-4 error-message-chart-browser">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="No Data"
                         data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                         alt="No Data" data-hs-theme-appearance="dark">
                    <p class="mb-0">@lang("The browser history is currently unavailable.")</p>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->
    </div>
    <div class="col-lg-4 mb-3 mb-lg-5" id="OSHistory">
        <!-- Card -->
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-header-title">@lang("Operating System History")</h4>
                <button id="js-daterangepicker-os-predefined" class="btn btn-white btn-sm dropdown-toggle">
                    <i class="bi-calendar-week"></i>
                    <span class="js-daterangepicker-os-predefined-preview ms-1"></span>
                </button>
            </div>
            <!-- Body -->
            <div class="card-body text-center">
                <div class="os-history-chart">
                    <div class="h3">
                            <span class="badge bg-soft-info text-info rounded-pill">
                              <i class="bi-check-circle-fill me-1"></i> @lang("On track")
                            </span>
                    </div>

                    <!-- Chart Half -->
                    <!-- Pie Chart -->
                    <div class="chartjs-custom mb-3 mb-sm-5" style="height: 14rem;">
                        <canvas class="" id="chartOSHistoryGraph">
                        </canvas>
                    </div>
                    <!-- End Pie Chart -->

                    <hr>

                    <div class="row col-divider">
                        <div class="col text-center">
                            <span class="d-block"><span class="selected-date-value-os"></span>@lang(" OS History")</span>
                        </div>
                    </div>
                </div>

                <div class="text-center p-4 error-message-chart-os">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="No Data"
                         data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                         alt="No Data" data-hs-theme-appearance="dark">
                    <p class="mb-0">@lang("The os history is currently unavailable.")</p>
                </div>

            </div>
        </div>
        <!-- End Card -->
    </div>
    <div class="col-lg-4 mb-3 mb-lg-5" id="deviceHistory">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-header-title">@lang("Device History")</h4>
                <button id="js-daterangepicker-device-predefined" class="btn btn-white btn-sm dropdown-toggle">
                    <i class="bi-calendar-week"></i>
                    <span class="js-daterangepicker-device-predefined-preview ms-1"></span>
                </button>
            </div>
            <!-- Body -->
            <div class="card-body text-center">
                <div class="device-history-chart">
                    <div class="h3">
                            <span class="badge bg-soft-info text-info rounded-pill">
                              <i class="bi-check-circle-fill me-1"></i> @lang("On track")
                            </span>
                    </div>
                    <!-- Pie Chart -->
                    <div class="chartjs-custom mb-3 mb-sm-5" style="height: 14rem;">
                        <canvas class="" id="chartDeviceHistoryGraph">
                        </canvas>
                    </div>
                    <!-- End Pie Chart -->

                    <hr>

                    <div class="row col-divider">
                        <div class="col text-center">
                            <span class="d-block"><span class="selected-date-value-device"></span>@lang(" Device History")</span>
                        </div>
                    </div>
                    <!-- End Row -->
                </div>

                <div class="text-center p-4 error-message-chart-device">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="No Data"
                         data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                         alt="No Data" data-hs-theme-appearance="dark">
                    <p class="mb-0">@lang("The device history is currently unavailable.")</p>
                </div>

            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->
    </div>
</div>

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/daterangepicker.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>

        var start = moment().subtract(6, 'days');
        var end = moment();

        function cb(start, end) {
            $('#js-daterangepicker-browser-predefined .js-daterangepicker-browser-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
        }

        $('#js-daterangepicker-browser-predefined').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        function cbOs(start, end) {
            $('#js-daterangepicker-os-predefined .js-daterangepicker-os-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
        }

        $('#js-daterangepicker-os-predefined').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cbOs);

        cbOs(start, end);

        function cbDesvice(start, end) {
            $('#js-daterangepicker-device-predefined .js-daterangepicker-device-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
        }

        $('#js-daterangepicker-device-predefined').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cbDesvice);

        cbDesvice(start, end);

        Notiflix.Block.standard('#browserHistory');
        HSCore.components.HSChartJS.init(document.querySelector('#chartBrowserHistoryGraph'), {
            type: "doughnut",
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ["#0072C6", "#FF9500", "#FF5733", "#4285F4", "#0078D4", "#FF1B2D", "#006699", "#002558", "#003399", "#3E74A0", "#5EAA51", "#999999"],
                    borderWidth: 0.5,
                    hoverBorderColor: "#fff"
                }]
            },
            options: {
                cutout: "70%",
                plugins: {
                    tooltip: {
                        hasIndicator: true,
                        mode: "index",
                        intersect: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: true
                }
            },
        });
        const chartBrowserHistoryGraph = HSCore.components.HSChartJS.getItem('chartBrowserHistoryGraph');

        async function updateChartBrowserHistoryGraph(event = null) {

            let dateRangeBrowser = $('#js-daterangepicker-browser-predefined').data('daterangepicker');
            let dateRangeValue = dateRangeBrowser.chosenLabel ?? 'Last 7 Days'
            $('.selected-date-value-browser').text(dateRangeValue);

            startDate = dateRangeBrowser.startDate.format('YYYY-MM-DD');
            endDate = dateRangeBrowser.endDate.format('YYYY-MM-DD');


            let $url = "{{ route('admin.chart.browser.history') }}"
            await axios.get($url, {
                params: {
                    startDate: startDate,
                    endDate: endDate
                }
            })
                .then(function (res) {
                    if (res.data.browserPerformance.browserKeys == 0 || res.data.browserPerformance.browserKeys.browserValue == 0) {
                        $('.error-message-chart-browser').addClass('d-block')
                        $('.chart-section-browser').hide();
                    } else {
                        $('.error-message-chart-browser').removeClass('d-block')
                        $('.chart-section-browser').show();
                    }
                    browserHistory(res.data.browserPerformance);
                    Notiflix.Block.remove('#browserHistory');
                })
                .catch(function (error) {
                });
        }

        function browserHistory(browserPerformance) {
            chartBrowserHistoryGraph.data.labels = browserPerformance.browserKeys
            chartBrowserHistoryGraph.data.datasets[0].data = browserPerformance.browserValue
            chartBrowserHistoryGraph.update();
        }


        $('#js-daterangepicker-browser-predefined').on('apply.daterangepicker', updateChartBrowserHistoryGraph);
        updateChartBrowserHistoryGraph();

        Notiflix.Block.standard('#OSHistory');
        HSCore.components.HSChartJS.init(document.querySelector('#chartOSHistoryGraph'), {
            type: "doughnut",
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ["#0072C6", "#FF9500", "#FF5733", "#4285F4", "#0078D4", "#FF1B2D", "#006699", "#002558", "#003399", "#3E74A0", "#5EAA51", "#999999"],
                    borderWidth: 0.5,
                    hoverBorderColor: "#fff"
                }]
            },
            options: {
                cutout: "70%",
                plugins: {
                    tooltip: {
                        hasIndicator: true,
                        mode: "index",
                        intersect: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: true
                }
            },
        });
        const chartOSHistoryGraph = HSCore.components.HSChartJS.getItem('chartOSHistoryGraph');

        async function updateChartOsHistoryGraph(event = null) {

            let dateRangeBrowser = $('#js-daterangepicker-os-predefined').data('daterangepicker');
            let dateRangeValue = dateRangeBrowser.chosenLabel ?? 'Last 7 Days'
            $('.selected-date-value-os').text(dateRangeValue);
            startDate = dateRangeBrowser.startDate.format('YYYY-MM-DD');
            endDate = dateRangeBrowser.endDate.format('YYYY-MM-DD');

            let $url = "{{ route('admin.chart.os.history') }}"
            await axios.get($url, {
                params: {
                    startDate: startDate,
                    endDate: endDate
                }
            })
                .then(function (res) {

                    if (res.data.osPerformance.osKeys == 0 || res.data.osPerformance.osValue == 0) {
                        $('.error-message-chart-os').addClass('d-block')
                        $('.os-history-chart').hide();
                    } else {
                        $('.error-message-chart-os').removeClass('d-block')
                        $('.os-history-chart').show();
                    }

                    operatingHistory(res.data.osPerformance);
                    Notiflix.Block.remove('#OSHistory');
                })
                .catch(function (error) {
                });
        }

        function operatingHistory(osPerformance) {
            chartOSHistoryGraph.data.labels = osPerformance.osKeys
            chartOSHistoryGraph.data.datasets[0].data = osPerformance.osValue
            chartOSHistoryGraph.update();
        }

        $('#js-daterangepicker-os-predefined').on('apply.daterangepicker', updateChartOsHistoryGraph);
        updateChartOsHistoryGraph();

        // Device
        Notiflix.Block.standard('#deviceHistory');
        HSCore.components.HSChartJS.init(document.querySelector('#chartDeviceHistoryGraph'), {
            type: "doughnut",
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ["#0072C6", "#FF9500", "#FF5733", "#4285F4", "#0078D4", "#FF1B2D", "#006699", "#002558", "#003399", "#3E74A0", "#5EAA51", "#999999"],
                    borderWidth: 0.5,
                    hoverBorderColor: "#fff"
                }]
            },
            options: {
                cutout: "70%",
                plugins: {
                    tooltip: {
                        hasIndicator: true,
                        mode: "index",
                        intersect: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: true
                }
            },
        });
        const chartDeviceHistoryGraph = HSCore.components.HSChartJS.getItem('chartDeviceHistoryGraph');

        async function updateChartDeviceHistoryGraph(event = null) {

            let dateRangeBrowser = $('#js-daterangepicker-device-predefined').data('daterangepicker');
            let dateRangeValue = dateRangeBrowser.chosenLabel ?? 'Last 7 Days'
            $('.selected-date-value-device').text(dateRangeValue);
            startDate = dateRangeBrowser.startDate.format('YYYY-MM-DD');
            endDate = dateRangeBrowser.endDate.format('YYYY-MM-DD');

            let $url = "{{ route('admin.chart.device.history') }}"
            await axios.get($url, {
                params: {
                    startDate: startDate,
                    endDate: endDate,
                }
            })
                .then(function (res) {

                    if (res.data.deviceHistory.deviceKeys == 0 || res.data.deviceHistory.deviceValue == 0) {
                        $('.error-message-chart-device').addClass('d-block')
                        $('.device-history-chart').hide();
                    } else {
                        $('.error-message-chart-device').removeClass('d-block')
                        $('.device-history-chart').show();
                    }

                    deviceHistory(res.data.deviceHistory);
                    Notiflix.Block.remove('#deviceHistory');
                })
                .catch(function (error) {
                });
        }

        function deviceHistory(deviceHistory) {
            chartDeviceHistoryGraph.data.labels = deviceHistory.deviceKeys
            chartDeviceHistoryGraph.data.datasets[0].data = deviceHistory.deviceValue
            chartDeviceHistoryGraph.update();
        }

        $('#js-daterangepicker-device-predefined').on('apply.daterangepicker', updateChartDeviceHistoryGraph);
        updateChartDeviceHistoryGraph();

    </script>
@endpush
