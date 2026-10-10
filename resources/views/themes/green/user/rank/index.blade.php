@extends(template().'layouts.user')
@section('title', 'ranks')

@section('content')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('All Ranks')</li>
            </ol>
        </nav>
    </div>


    @if(isset($allRanks) && count($allRanks) > 0)
        <div class="mt-30">
            <div class="row g-4">
                @foreach($allRanks as $key => $rank)
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div
                            class="ranking-box {{ Auth::user()->ranking($rank->id, $rank->min_deposit) == 'true' ? '' : 'locked' }}">
                            <div class="box-top">
                                <div class="img-box">
                                    <img src="{{ getFile($rank->driver, $rank->rank_icon) }}" alt="">
                                </div>
                                <div class="img-text">
                                    <p class="mb-0">@lang($rank->rank_level)</p>
                                    <h4 class="mb-0">@lang($rank->rank_name)</h4>
                                </div>
                            </div>
                            <div class="box-bottom">
                                <h6 class="mb-0">@lang('Minimum Personal Invest'):</h6>
                                <h5 class="mb-1">{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount($rank->min_invest)) }}</h5>
                                <h6 class="mb-0">@lang('Minimum Personal Deposit'):</h6>
                                <h5 class="mb-1">{{ basicControl()->currency_symbol }}{{ fractionNumber(getAmount($rank->min_deposit)) }}</h5>
                                <h6 class="mb-0">@lang('Minimum Earning'):</h6>
                                <h5 class="mb-1">{{ basicControl()->currency_symbol }}{{ $rank->min_earning }}</h5>
                                <h6 class="mb-0">@lang('Bonus to receive'):</h6>
                                <h5 class="bonus-receive">{{ basicControl()->currency_symbol }}{{ $rank->bonus }}</h5>
                            </div>
                            @if(Auth::user()->ranking($rank->id, $rank->min_deposit) == 'true')
                                <div class="lock-icon">
                                    <i class="fa-regular fa-check-double"></i>
                                </div>
                            @else
                                <div class="lock-icon">
                                    <i class="fa-regular fa-lock"></i>
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

