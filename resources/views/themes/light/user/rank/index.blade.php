@extends(template().'layouts.user')
@section('title', 'ranks')

@section('content')
    <section class="payment-gateway">
        <div class="container-fluid">
            <div class="main row mt-4 mb-2">
                <div class="col ms-2">
                    <div class="header-text-full">
                        <h3 class="dashboard_breadcurmb_heading mb-1">@lang('All Ranks')</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">@lang('Ranks')</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            @if(isset($allRanks) && count($allRanks) > 0)
                <div class="badge-box-wrapper">
                    <div class="main row g-4 mb-4">
                        @foreach($allRanks as $key => $rank)
                            <div class="col-xl-4 col-md-4 col-12 box">
                                <div class="badge-box {{ Auth::user()->ranking($rank->id, $rank->min_deposit) == 'true' ? '' : 'locked' }}">
                                    <img src="{{ getFile($rank->driver, $rank->rank_icon) }}" alt="" />
                                    <h3>@lang($rank->rank_level)</h3>
                                    <p class="mb-3">@lang($rank->rank_name)</p>
                                    <div class="text-start">
                                        <h5>@lang('Minimum Invest'): <span>{{ basicControl()->currency_symbol }}{{ $rank->min_invest }}</span></h5>
                                        <h5>@lang('Minimum Deposit'): <span>{{ basicControl()->currency_symbol }}{{ $rank->min_deposit }}</span></h5>
                                        <h5>@lang('Minimum Earning'): <span>{{ basicControl()->currency_symbol }}{{ $rank->min_earning }}</span></h5>
                                        <h5>@lang('Bonus'): <span>{{ basicControl()->currency_symbol }}{{ $rank->bonus }}</span></h5>
                                    </div>
                                    <div class="lock-icon">
                                        <i class="far fa-lock-alt"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>
@endsection

