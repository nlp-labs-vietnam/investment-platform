<div class="col-xl-7">
    <div class="card transaction-card mb-3 mb-lg-5">
        <!-- Header -->
        <div class="card-header card-header-content-sm-between">
            <h4 class="card-header-title mb-2 mb-sm-0">@lang("User Analytics")</h4>

            <div class="daterange-container">
                <div class="daterange-picker">
                    <input type="text" id="dailyUserAnalytics" value=""/>
                    <i class="fa fa-caret-down"></i>
                </div>
            </div>

            <!-- Nav -->
{{--            <ul class="nav nav-segment nav-fill" id="projectsTab" role="tablist">--}}
{{--                <li class="nav-item this-month" data-bs-toggle="chart" data-datasets="0"--}}
{{--                    data-trigger="click"--}}
{{--                    data-action="toggle">--}}
{{--                    <a class="nav-link active" href="javascript:void(0);"--}}
{{--                       data-bs-toggle="tab">@lang("This Month")</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item" data-bs-toggle="chart" data-datasets="1" data-trigger="click"--}}
{{--                    data-action="toggle">--}}
{{--                    <a class="nav-link" href="javascript:void(0);"--}}
{{--                       data-bs-toggle="tab">@lang("Last Month")</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
            <!-- End Nav -->
        </div>
        <!-- End Header -->

        <!-- Body -->
        <div class="card-body block-statistics">
            <div class="row align-items-sm-center mb-4">
                <div class="col-sm mb-3 mb-sm-0">
                    <div class="">
                        <canvas id="daily-user-analytics" height="266"></canvas>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

