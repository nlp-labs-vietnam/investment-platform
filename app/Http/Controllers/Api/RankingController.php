<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    use ApiResponse;

    public function rankings()
    {
        $rankings = Rank::where('status', 1)->orderBy('sort_by', 'ASC')->get();

        $basic = basicControl();
        $formattedRankings = $rankings->map(function ($rank) use ($basic){
           return [
               'id' => $rank->id,
               'currency_symbol' => $basic->currency_symbol,
               'base_currency' => $basic->base_currency,
               'rank_name' => $rank->rank_name,
               'rank_level' => $rank->rank_level,
               'min_invest' => $basic->currency_symbol.fractionNumber(getAmount($rank->min_invest)),
               'min_deposit' => $basic->currency_symbol.fractionNumber(getAmount($rank->min_deposit)),
               'min_earning' => $basic->currency_symbol.fractionNumber(getAmount($rank->min_earning)),
               'bonus' => $basic->currency_symbol.fractionNumber(getAmount($rank->bonus)),
               'rank_icon' => getFile($rank->driver, $rank->rank_icon),
           ];
        });

        return response()->json($this->withSuccess($formattedRankings));
    }
}
