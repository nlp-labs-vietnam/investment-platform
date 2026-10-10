<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentDetails;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Investment;
use App\Models\Language;
use App\Models\Payout;
use App\Models\Rank;
use App\Models\SupportTicket;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use ApiResponse;

    public function onBoardList()
    {
        try {
            $defaultLanguage = Language::orderBy('default_status', 'desc')
                ->where('default_status', true)
                ->first();

            $onboards = ContentDetails::with('content')
                ->whereHas('content', function ($query) {
                    $query->where('name', 'app_onboard');
                    $query->where('type', 'multiple');
                })
                ->where('language_id', $defaultLanguage->id)
                ->get();

            $formattedOnboards = $onboards->map(function ($onboard) {
                $description = $onboard->description;
                $content = $onboard->content;

                return [
                    'title' => $description->title ?? null,
                    'subtitle' => $description->subtitle ?? null,
                    'image' =>  getFile($content['media']->image->driver,$content['media']->image->path)  ?? null,
                ];
            });

            return response()->json($this->withSuccess($formattedOnboards));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }


    public function language(Request $request)
    {
        try {
            if (!$request->id) {
                $data['languages'] = Language::select(['id', 'name', 'short_name'])->where('status', 1)->get();
                return response()->json($this->withSuccess($data));
            }
            $lang = Language::where('status', 1)->find($request->id);
            if (!$lang) {
                return response()->json($this->withError('Record not found'));
            }

            $json = file_get_contents(resource_path('lang/') . $lang->short_name . '.json');
            if (empty($json)) {
                return response()->json($this->withError('File Not Found.'));
            }

            $json = json_decode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return response()->json($this->withSuccess($json));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function dashboard()
    {
        $user = Auth::user();
        $basic = basicControl();
        $walletBalance = getAmount($user->balance);
        $interestBalance = getAmount($user->interest_balance);
        $totalDeposit = getAmount($user->deposit()->whereStatus(1)->sum('amount'));
        $totalPayout = getAmount($user->payout()->whereStatus(2)->sum('amount'));
        $depositBonus = getAmount($user->referralBonusLog()->where('type', 'deposit')->sum('amount'));
        $investBonus = getAmount($user->referralBonusLog()->where('type', 'invest')->sum('amount'));
        $profitBonus = getAmount($user->referralBonusLog()->where('type', 'profit_commission')->sum('amount'));
        $lastBonus = getAmount(optional($user->referralBonusLog()->orderBy('id', 'DESC')->first())->amount);
        $totalRankBonus = getAmount($user->total_rank_bonous);

        $investment = collect(Investment::with('property')->where('user_id', $user->id)
            ->selectRaw('SUM(amount) AS total_invest')
            ->selectRaw('SUM(CASE WHEN invest_status = 1 AND status = 0 AND is_active = 1 THEN amount END) AS running_invest')
            ->selectRaw('COUNT(id) AS total_investment')
            ->selectRaw('COUNT(CASE WHEN invest_status = 1 AND status = 0 AND is_active = 1 THEN id END) AS running_investment')
            ->selectRaw('COUNT(CASE WHEN invest_status = 0 AND status = 0 AND is_active = 1 THEN id END) AS due_investment')
            ->selectRaw('COUNT(CASE WHEN invest_status = 1 AND status = 1 AND is_active = 1 THEN id END) AS completed_investment')
            ->get()->toArray())->collapse();

        $totalInterestProfit = getAmount(\auth()->user()->transaction()->where('balance_type', 'interest_balance')->where('trx_type', '+')->sum('amount'));
        $totalTicket = SupportTicket::where('user_id', $user->id)->count();

        $ranks = Rank::where('status', 1)->orderBy('sort_by', 'ASC')->get();

        $investorRank = BasicService::getInvestorCurrentRank($user);
        if ($investorRank != null) {
            $lastInvestorRank = $investorRank->first();
        } else {
            $lastInvestorRank = null;
        }


        $monthlyInvestment = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        Investment::where('user_id', $user->id)
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyInvestment) {
                $monthlyInvestment->put($item['months'], round($item['totalAmount'], 2));
            });
        $monthly['investment'] = $monthlyInvestment;

        $monthlyPayout = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        $user->payout()->whereStatus(2)
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyPayout) {
                $monthlyPayout->put($item['months'], round($item['totalAmount'], 2));
            });
        $monthly['payout'] = $monthlyPayout;

        $monthlyFunding = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        $user->funds()->whereStatus(1)
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyFunding) {
                $monthlyFunding->put($item['months'], round($item['totalAmount'], 2));
            });

        $monthly['funding'] = $monthlyFunding;

        $monthlyReferralInvestBonus = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        $user->referralBonusLog()->where('type', 'invest')
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyReferralInvestBonus) {
                $monthlyReferralInvestBonus->put($item['months'], round($item['totalAmount'], 2));
            });
        $monthly['referralInvestBonus'] = $monthlyReferralInvestBonus;

        $monthlyReferralFundBonus = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);

        $user->referralBonusLog()->where('type', 'deposit')
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyReferralFundBonus) {
                $monthlyReferralFundBonus->put($item['months'], round($item['totalAmount'], 2));
            });

        $monthly['referralFundBonus'] = $monthlyReferralFundBonus;

        $monthlyProfit = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        $user->transaction()->whereBetween('created_at', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        ])
            ->select(
                DB::raw('SUM((CASE WHEN remarks LIKE "%Interest From%" THEN amount END)) AS totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyProfit) {
                $monthlyProfit->put($item['months'], round($item['totalAmount'], 2));
            });
        $monthly['monthlyGaveProfit'] = $monthlyProfit;

        $data['dashboard_basic_data'] = [
            'currency_symbol' => $basic->currency_symbol,
            'base_currency' => $basic->base_currency,
            'welcome_bonus' => $basic->currency_symbol . fractionNumber(getAmount(basicControl()->first_deposit_bonus)),
            'minimum_first_depost' => $basic->currency_symbol . fractionNumber(getAmount(basicControl()->minimum_first_deposit)),
            'main_balance' => $basic->currency_symbol . $walletBalance,
            'interest_balance' => $basic->currency_symbol . $interestBalance,
            'total_deposit' => $basic->currency_symbol . $totalDeposit,
            'investment' => $investment,
            'total_payout' => $basic->currency_symbol . $totalPayout,
            'total_referral_bonus' => basicControl()->currency_symbol . ($depositBonus + $investBonus + $profitBonus),
            'last_bonus' => $basic->currency_symbol . $lastBonus,
            'total_earn' => basicControl()->currency_symbol . $totalInterestProfit,
            'total_ticket' => $totalTicket,
            'total_rank_bonus' => $basic->currency_symbol . $totalRankBonus,
            'current_rank' => $lastInvestorRank ? $lastInvestorRank->rank_name : null,
            'ranks' => $ranks->map(function ($rank) {
                return [
                    'id' => $rank->id,
                    'rank_name' => $rank->rank_name,
                    'rank_level' => $rank->rank_level,
                    'rank_icon' => getFile($rank->driver, $rank->rank_icon),
                ];
            }),
            'monthly' => $monthly
        ];


        return response()->json($this->withSuccess($data));
    }


    public function transaction()
    {
        $user = auth()->user();
        $transactions = $user->transaction()->paginate(10);

        $formattedTransactions = $transactions->map(function ($transaction, $key) {
            return [
                'SL' => $key + 1,
                'id' => $transaction->id,
                'trx_id' => $transaction->trx_id,
                'amount' => $transaction->trx_type . $transaction->amount . ' ' . basicControl()->base_currency,
                'remarks' => $transaction->remarks,
                'time' => dateTime($transaction->created_at),
            ];
        });

        $transactions->setCollection($formattedTransactions);

        return response()->json($this->withSuccess($transactions));
    }


    public function fundList(Request $request)
    {
        try {
            $search = $request->all();
            $basic = basicControl();
            $userId = Auth::id();
            $funds = Deposit::with(['gateway:id,name'])
                ->where('user_id', $userId)
                ->where('status', '!=', 0)
                ->whereNull('depositable_type')
                ->when(isset($search['transaction']), fn($query) => $query->where('trx_id', 'LIKE', '%' . $search['transaction'] . '%'))
                ->when(isset($search['gateway']), fn($query) => $query->whereHas('gateway', fn($subquery) => $subquery->where('name', 'LIKE', '%' . $search['gateway'] . '%')))
                ->when(isset($search['status']), fn($query) => $query->where('status', '=', $search['status']))
                ->when(isset($search['start_date'], $search['end_date']) && $search['start_date'] !== '' && $search['end_date'] !== '',
                    fn($query) => $query->whereBetween('created_at', [
                        Carbon::parse($search['start_date']),
                        Carbon::parse($search['end_date'])->endOfDay()
                    ])
                )
                ->latest()->paginate(20);

            $statusLabels = [
                0 => 'Pending',
                1 => 'Success',
                2 => 'Requested',
                3 => 'Rejected',
            ];
            $formattedData = $funds->map(fn($item) => [
                'id' => $item->id,
                'trx_id' => $item->trx_id,
                'payment_method_id' => $item->payment_method_id,
                'amount' => getAmount($item->amount, 2),
                'payment_method_currency' => $item->payment_method_currency,
                'percentage_charge' => getAmount($item->percentage_charge, 2),
                'fixed_charge' => getAmount($item->fixed_charge, 2),
                'base_currency_charge' => getAmount($item->base_currency_charge, 2),
                'payable_amount_in_base_currency' => getAmount($item->payable_amount_in_base_currency, 2),
                'status' => $statusLabels[$item->status] ?? 'Unknown',
                'created_at' => dateTime($item->created_at),
                'gateway' => $item->gateway?->name,
                ...($item->note ? ['note' => $item->note] : []),
            ]);

            $funds->setCollection($formattedData);
            $data['funds'] = $funds;
            return response()->json($this->withSuccess($data));

        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }

    }


    public function payoutList(Request $request)
    {
        try {
            $search = $request->all();
            $basic = basicControl();
            $userId = Auth::id();
            $payouts = Payout::with(['method:id,name'])
                ->where('user_id', $userId)
                ->where('status', '!=', 0)
                ->when(isset($search['transaction']), fn($query) => $query->where('trx_id', 'LIKE', '%' . $search['transaction'] . '%'))
                ->when(isset($search['method']), fn($query) => $query->whereHas('method', fn($subquery) => $subquery->where('name', 'LIKE', '%' . $search['method'] . '%')))
                ->when(isset($search['status']), fn($query) => $query->where('status', '=', $search['status']))
                ->when(isset($search['start_date'], $search['end_date']) && $search['start_date'] !== '' && $search['end_date'] !== '',
                    fn($query) => $query->whereBetween('created_at', [
                        Carbon::parse($search['start_date']),
                        Carbon::parse($search['end_date'])->endOfDay()
                    ])
                )
                ->latest()->paginate($basic->paginate);

            $statusLabels = [
                0 => 'Pending',
                1 => 'Generated',
                2 => 'Success',
                3 => 'Rejected',
            ];
            $formattedData = $payouts->map(fn($item) => [
                'id' => $item->id,
                'trx_id' => $item->trx_id,
                'payout_method_id' => $item->payout_method_id,
                'amount' => getAmount($item->amount, 2),
                'payout_currency_code' => $item->payout_currency_code,
                'charge' => getAmount($item->charge, 2),
                'net_amount' => getAmount($item->net_amount, 2),
                'amount_in_base_currency' => getAmount($item->amount_in_base_currency, 2),
                'charge_in_base_currency' => getAmount($item->charge_in_base_currency, 2),
                'net_amount_in_base_currency' => getAmount($item->net_amount_in_base_currency, 2),
                'status' => $statusLabels[$item->status] ?? 'Unknown',
                'created_at' => dateTime($item->created_at),
                'method' => $item->method?->name,
                ...($item->feedback ? ['feedback' => $item->feedback] : []),
            ]);
            $payouts->setCollection($formattedData);
            $data['payouts'] = $payouts;
            return response()->json($this->withSuccess($data));

        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }

    }


}
