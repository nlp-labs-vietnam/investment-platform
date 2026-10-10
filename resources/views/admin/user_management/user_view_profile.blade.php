@extends('admin.layouts.app')
@section('page_title',__('View Profile'))
@section('content')

    <!-- Content -->
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">

                @include('admin.user_management.components.header_user_profile')

                <div class="row">
                    <div class="col-lg-4">
                        <div class="card mb-3 mb-lg-5">
                            <div class="card-header card-header-content-between">
                                <h4 class="card-header-title">@lang('Profile')</h4>
                            </div>

                            <div class="card-body">
                                <ul class="list-unstyled list-py-2 text-dark mb-0">
                                    <li class="pb-0"><span class="card-subtitle">@lang('About')</span></li>
                                    <li>
                                        <i class="bi-person dropdown-item-icon"></i> @lang($user->firstname . ' ' . $user->lastname)
                                    </li>
                                    <li><i class="bi-briefcase dropdown-item-icon"></i> @lang('@' . $user->username)
                                    </li>
                                    @if(isset($user->country))
                                    <li><i class="bi-geo-alt dropdown-item-icon"></i> @lang($user->country)</li>
                                    @endif

                                    <li><i class="bi bi-wallet dropdown-item-icon"></i> {{ currencyPosition($user->balance) }}
                                    </li>

                                    <li class="pt-4 pb-0"><span class="card-subtitle">@lang('Contacts')</span></li>
                                    <li>
                                        <i class="bi-at dropdown-item-icon"></i> {{ $user->email }}
                                        <i
                                            class="bi-patch-check-fill text-{{ $user->email_verification == 1 ? 'success' : 'danger' }}"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="{{ $user->email_verification == 1 ? 'Email Verified' : 'Email Unverified' }}"
                                            data-bs-original-title="{{ $user->email_verification == 1 ? 'Email Verified' : 'Email Unverified' }}">
                                        </i>
                                    </li>
                                    <li><i class="bi-phone dropdown-item-icon"></i> {{ $user->phone }}</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Card -->
                        <div class="card card-lg mb-3 mb-lg-5">
                            <div class="card-body text-center">
                                <div class="mb-4">
                                    <img class="avatar avatar-xl avatar-4x3"
                                         src="{{ asset('assets/admin/img/oc-unlock.svg') }}" alt="Image Description"
                                         data-hs-theme-appearance="default"/>
                                    <img class="avatar avatar-xl avatar-4x3"
                                         src="{{ asset('assets/admin/img/oc-unlock-light.svg') }}"
                                         alt="Image Description"
                                         data-hs-theme-appearance="dark"/>
                                </div>
                                <div class="mb-3">
                                    <h3>@lang('2-step verification')</h3>
                                    <p>@lang('Protect your account now and enable 2-step verification in the settings.')</p>
                                </div>
                                <form action="{{ route('admin.user.twoFa.update', $user->id) }}" method="post">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-{{ $user->two_fa_verify == 1 ? 'danger' : 'primary' }}"
                                            name="two_fa_security" value="{{ $user->two_fa_verify }}">
                                        {{ $user->two_fa_verify == 1 ? 'Disable now' : 'Enable now' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card card-centered mb-3 mb-lg-5">
                            <div class="card-header card-header-content-between">
                                <h4 class="card-header-title">@lang('Transaction')</h4>
                            </div>


                            @if(count($transactions) == 0)
                                <div class="card-body card-body-height">
                                    <img class="avatar avatar-xxl mb-3"
                                         src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                         alt="Image Description" data-hs-theme-appearance="default"/>
                                    <img class="avatar avatar-xxl mb-3"
                                         src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                         alt="Image Description" data-hs-theme-appearance="dark"/>
                                    <p class="card-text">@lang('No data to show')</p>
                                </div>
                            @endif

                            @if(0 < count($transactions))
                                <div class="table-responsive">
                                    <table
                                        class="table table-thead-bordered table-nowrap table-align-middle card-table">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>@lang('No.')</th>
                                            <th>@lang('TRX')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Remarks')</th>
                                            <th>@lang('Date-Time')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($transactions as $key =>  $transaction)
                                            <tr>
                                                <td>
                                                    {{ $loop->index + 1 }}
                                                </td>
                                                <td>
                                                    {{ $transaction->trx_id }}
                                                </td>
                                                <td>
                                                    <h6 class="mb-0 {{ $transaction->trx_type == '+' ? 'text-success' : 'text-danger' }}">{{ $transaction->trx_type . currencyPosition($transaction->amount) }}</h6>
                                                </td>
                                                <td> @lang($transaction->remarks)</td>
                                                <td>{{ dateTime($transaction->created_at, 'd M Y h:i A') }}</td>
                                            </tr>
                                        @empty

                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>


                        <div class="card card-centered mb-3 mb-lg-5">
                            <div class="card-header card-header-content-between">
                                <h4 class="card-header-title">@lang('Payment Log')</h4>
                            </div>

                            @if(count($paymentLog) == 0)
                                <div class="card-body card-body-height card-body-centered">
                                    <img class="avatar avatar-xxl mb-3"
                                         src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                         alt="Image Description" data-hs-theme-appearance="default"/>
                                    <img class="avatar avatar-xxl mb-3"
                                         src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                         alt="Image Description" data-hs-theme-appearance="dark"/>
                                    <p class="card-text">@lang('No data to show')</p>
                                </div>
                            @endif

                            @if(0 < count($paymentLog))
                                <div class="table-responsive">
                                    <table
                                        class="table table-thead-bordered table-nowrap table-align-middle card-table">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>@lang('No.')</th>
                                            <th>@lang('TRX')</th>
                                            <th>@lang('Method')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('status')</th>
                                            <th>@lang('Date')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($paymentLog as $key =>  $payment)
                                            <tr>
                                                <td>
                                                    {{ $loop->index + 1 }}
                                                </td>
                                                <td>
                                                    {{ $payment->trx_id }}
                                                </td>
                                                <td>
                                                    <a class="d-flex align-items-center" href="javascript:void(0)">
                                                        <div class="avatar avatar-circle">
                                                            <img class="avatar-img"
                                                                 src="{{ getFile(optional($payment->gateway)->driver, optional($payment->gateway)->image) }}"
                                                                 alt="Image Description">
                                                        </div>
                                                        <div class="ms-3">
                                                            <span
                                                                class="d-block h5 text-inherit mb-0">@lang(optional($payment->gateway)->name)</span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0 {{ $payment->getStatusClass() }}">{{ currencyPosition($payment->payable_amount_in_base_currency) }}</h6>
                                                </td>
                                                <td>
                                                    @if ($payment->status == 1)
                                                        <span
                                                            class="badge bg-soft-success text-success">@lang('Successful')</span>
                                                    @elseif($payment->status == 2)
                                                        <span
                                                            class="badge bg-soft-warning text-warning">@lang('Pending')</span>
                                                    @elseif($payment->status == 3)
                                                        <span
                                                            class="badge bg-soft-danger text-danger">@lang('Cancel')</span>
                                                    @elseif($payment->status == 4)
                                                        <span
                                                            class="badge bg-soft-danger text-danger">@lang('Failed')</span>
                                                    @endif
                                                </td>
                                                <td>{{ dateTime($payment->created_at, 'd M Y h:i A') }}</td>
                                            </tr>
                                        @empty

                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>


                        <div class="card card-centered mb-3 mb-lg-5">
                            <div class="card-header card-header-content-between">
                                <h4 class="card-header-title">@lang('Withdraw Log')</h4>
                            </div>

                            @if(count($withDraws) == 0)
                                <div class="card-body card-body-height card-body-centered">
                                    <img class="avatar avatar-xxl mb-3"
                                         src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                         alt="Image Description" data-hs-theme-appearance="default"/>
                                    <img class="avatar avatar-xxl mb-3"
                                         src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                         alt="Image Description" data-hs-theme-appearance="dark"/>
                                    <p class="card-text">@lang('No data to show')</p>
                                </div>
                            @endif

                            @if(0 < count($withDraws))
                                <div class="table-responsive">
                                    <table
                                        class="table table-thead-bordered table-nowrap table-align-middle card-table">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>@lang('No.')</th>
                                            <th>@lang('TRX')</th>
                                            <th>@lang('Method')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('status')</th>
                                            <th>@lang('Date')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($withDraws as $key =>  $withdraw)
                                            <tr>
                                                <td>
                                                    {{ $loop->index + 1 }}
                                                </td>
                                                <td>
                                                    {{ $withdraw->trx_id }}
                                                </td>
                                                <td>
                                                    <a class="d-flex align-items-center" href="javascript:void(0)">
                                                        <div class="avatar avatar-circle">
                                                            <img class="avatar-img"
                                                                 src="{{ getFile(optional($withdraw->method)->driver, optional($withdraw->method)->logo) }}"
                                                                 alt="Image Description">
                                                        </div>
                                                        <div class="ms-3">
                                                            <span
                                                                class="d-block h5 text-inherit mb-0">@lang(optional($withdraw->method)->name)</span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0 {{ $withdraw->getStatusClass() }}">{{ currencyPosition($withdraw->amount_in_base_currency) }}</h6>
                                                </td>
                                                <td>
                                                    @if ($withdraw->status == 1)
                                                        <span
                                                            class="badge bg-soft-warning text-warning">@lang('Pending')</span>
                                                    @elseif($withdraw->status == 2)
                                                        <span
                                                            class="badge bg-soft-success text-success">@lang('Successful')</span>
                                                    @elseif($withdraw->status == 3)
                                                        <span
                                                            class="badge bg-soft-danger text-danger">@lang('Cancel')</span>
                                                    @endif
                                                </td>
                                                <td>{{ dateTime($payment->created_at, 'd M Y h:i A') }}</td>
                                            </tr>
                                        @empty

                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    @include('admin.user_management.components.login_as_user')
    @include('admin.user_management.components.update_balance_modal')
    @include('admin.user_management.components.block_profile_modal')

@endsection


@push('script')
    <script>
        'use strict';
        $(document).on('click', '.loginAccount', function () {
            let route = $(this).data('route');
            $('.loginAccountAction').attr('action', route)
        });

        $(document).on('click', '.blockProfile', function () {
            let route = $(this).data('route');
            $('.blockProfileAction').attr('action', route)
        });

    </script>
@endpush







