@extends('admin.layouts.app')
@section('page_title', __('Withdrawal Days Setup'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link"
                                   href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Withdraw Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Withdrawal Days Setup')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Withdrawal Days Setup')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-6">
                <div class="d-grid gap-3 gap-lg-5">

                    <div id="connectedAccountsSection" class="card">
                        <div class="card-header">
                            <h4 class="card-title">@lang("Withdrawal Days Setting")</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route("admin.withdrawal.days.update") }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="list-group list-group-lg list-group-flush list-group-no-gutters">
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs"
                                                     src="{{ asset("assets/admin/img/day-off1.png") }}"
                                                     alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0">@lang("Monday")</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("Enable to withdrawal days setting")</p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="monday" value="0">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="monday"
                                                                   id="mondayWithdrawal"
                                                                   value="1" {{ $withdrawalConfig['Monday'] ? "checked" : "" }}>
                                                            <label class="form-check-label"
                                                                   for="mondayWithdrawal"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- List Item -->
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs"
                                                     src="{{ asset("assets/admin/img/day-off1.png") }}"
                                                     alt="Image Description">
                                            </div>

                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0">@lang("Tuesday")</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("Enable to withdrawal days setting")</p>
                                                    </div>
                                                    <!-- End Col -->

                                                    <div class="col-auto">
                                                        <!-- Form Switch -->
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="tuesday" value="0">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="tuesday"
                                                                   id="tuesdayWithdrawal"
                                                                   value="1" {{ $withdrawalConfig['Tuesday'] ? "checked" : "" }}>
                                                            <label class="form-check-label"
                                                                   for="tuesdayWithdrawal"></label>
                                                        </div>
                                                        <!-- End Form Switch -->
                                                    </div>
                                                    <!-- End Col -->
                                                </div>
                                                <!-- End Row -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End List Item -->

                                    <!-- List Item -->
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs"
                                                     src="{{ asset("assets/admin/img/day-off1.png") }}"
                                                     alt="Image Description">
                                            </div>

                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0">@lang("Wednesday")</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("Enable to withdrawal days setting")</p>
                                                    </div>
                                                    <!-- End Col -->

                                                    <div class="col-auto">
                                                        <!-- Form Switch -->
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="wednesday" value="0">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="wednesday"
                                                                   id="wednesdayWithdrawal"
                                                                   value="1" {{ $withdrawalConfig['Wednesday'] ? "checked" : "" }}>
                                                            <label class="form-check-label"
                                                                   for="wednesdayWithdrawal"></label>
                                                        </div>
                                                        <!-- End Form Switch -->
                                                    </div>
                                                    <!-- End Col -->
                                                </div>
                                                <!-- End Row -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End List Item -->

                                    <!-- List Item -->
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs"
                                                     src="{{ asset("assets/admin/img/day-off1.png") }}"
                                                     alt="Image Description">
                                            </div>

                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0">@lang("Thursday")</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("Enable to withdrawal days setting")</p>
                                                    </div>
                                                    <!-- End Col -->

                                                    <div class="col-auto">
                                                        <!-- Form Switch -->
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="thursday" value="0">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="thursday"
                                                                   id="thursdayWithdrawal"
                                                                   value="1" {{ $withdrawalConfig['Thursday'] ? "checked" : "" }}>
                                                            <label class="form-check-label"
                                                                   for="thursdayWithdrawal"></label>
                                                        </div>
                                                        <!-- End Form Switch -->
                                                    </div>
                                                    <!-- End Col -->
                                                </div>
                                                <!-- End Row -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End List Item -->

                                    <!-- List Item -->
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs"
                                                     src="{{ asset("assets/admin/img/day-off1.png") }}"
                                                     alt="Image Description">
                                            </div>

                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0">@lang("Friday")</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("Enable to withdrawal days setting")</p>
                                                    </div>
                                                    <!-- End Col -->

                                                    <div class="col-auto">
                                                        <!-- Form Switch -->
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="friday" value="0">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="friday"
                                                                   id="fridayWithdrawal"
                                                                   value="1" {{ $withdrawalConfig['Friday'] ? "checked" : "" }}>
                                                            <label class="form-check-label"
                                                                   for="fridayWithdrawal"></label>
                                                        </div>
                                                        <!-- End Form Switch -->
                                                    </div>
                                                    <!-- End Col -->
                                                </div>
                                                <!-- End Row -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs"
                                                     src="{{ asset("assets/admin/img/day-off1.png") }}"
                                                     alt="Image Description">
                                            </div>

                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0">@lang("Saturday")</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("Enable to withdrawal days setting")</p>
                                                    </div>
                                                    <!-- End Col -->

                                                    <div class="col-auto">
                                                        <!-- Form Switch -->
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="saturday" value="0">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="saturday"
                                                                   id="saturdayWithdrawal"
                                                                   value="1" {{ $withdrawalConfig['Saturday'] ? "checked" : "" }}>
                                                            <label class="form-check-label"
                                                                   for="saturdayWithdrawal"></label>
                                                        </div>
                                                        <!-- End Form Switch -->
                                                    </div>
                                                    <!-- End Col -->
                                                </div>
                                                <!-- End Row -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs"
                                                     src="{{ asset("assets/admin/img/day-off1.png") }}"
                                                     alt="Image Description">
                                            </div>

                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0">@lang("Sunday")</h4>
                                                        <p class="fs-5 text-body mb-0">@lang("Enable to withdrawal days setting")</p>
                                                    </div>
                                                    <!-- End Col -->

                                                    <div class="col-auto">
                                                        <!-- Form Switch -->
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="sunday" value="0">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="sunday"
                                                                   id="sundayWithdrawal"
                                                                   value="1" {{ $withdrawalConfig['Sunday'] ? "checked" : "" }}>
                                                            <label class="form-check-label"
                                                                   for="sundayWithdrawal"></label>
                                                        </div>
                                                        <!-- End Form Switch -->
                                                    </div>
                                                    <!-- End Col -->
                                                </div>
                                                <!-- End Row -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(adminAccessRoute(config('permissionList.Settings_Panel.Withdraw_Settings.permission.edit')))
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                    </div>
                                @endif
                            </form>
                            <!-- End Form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection








