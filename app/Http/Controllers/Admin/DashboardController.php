<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Investment;
use App\Models\Payout;
use App\Models\ReferralBonus;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserKyc;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    use Upload, Notify;

    public function getDailyUserAnalytics(Request $request)
    {

        $start = Carbon::createFromFormat('d/m/Y', $request->start);
        $end = Carbon::createFromFormat('d/m/Y', $request->end);

        $dailyUser = DB::table('users')
            ->selectRaw('DATE(created_at) as date, COUNT(CASE WHEN status = 1 THEN id END) AS totalUsers')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->get();

        $dailyDeposit = DB::table('deposits')
            ->selectRaw('DATE(created_at) as date, SUM(CASE WHEN status = 1 THEN amount END) AS totalDeposit')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->get();

        $dailyPayout = DB::table('payouts')
            ->selectRaw('DATE(created_at) as date, SUM(CASE WHEN status = 2 THEN amount END) AS totalPayout')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->get();



        $start = new \DateTime($start);
        $end = new \DateTime($end);
        $data = [];

        for ($day = $start; $day <= $end; $day->modify('+1 day')) {
            $date = $day->format('Y-m-d');
            $data['labels'][] = $day->format('jS M');
            $data['dataTotalUsers'][] = $dailyUser->where('date', $date)->first()->totalUsers ?? 0;
            $data['dataTotalDeposit'][] = $dailyDeposit->where('date', $date)->first()->totalDeposit ?? 0;
            $data['dataTotalPayout'][] = $dailyPayout->where('date', $date)->first()->totalPayout ?? 0;
        }

        return response()->json($data);
    }

    public function getDailyTransactionAnalytics(Request $request)
    {
        $start = Carbon::createFromFormat('d/m/Y', $request->start);
        $end = Carbon::createFromFormat('d/m/Y', $request->end);

        $dailyInvestments = DB::table('investments')
            ->selectRaw('DATE(created_at) as date, SUM(CASE WHEN is_active = 1 THEN amount END) AS totalInvestmentAmount')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->get();

        $dailyProfits = DB::table('transactions')
            ->selectRaw('DATE(created_at) as date, SUM((CASE WHEN remarks LIKE "%Interest From%" THEN amount END)) AS totalProfitAmount')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->get();

        $dailyReferralBonuses = DB::table('referral_bonuses')
            ->selectRaw('DATE(created_at) as date, SUM(amount) AS totalReferralBonusAmount')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->get();


        $start = new \DateTime($start);
        $end = new \DateTime($end);
        $data = [];

        for ($day = $start; $day <= $end; $day->modify('+1 day')) {
            $date = $day->format('Y-m-d');
            $data['labels'][] = $day->format('jS M');
            $data['dataTotalInvestments'][] = $dailyInvestments->where('date', $date)->first()->totalInvestmentAmount ?? 0;
            $data['dataTotalProfit'][] = $dailyProfits->where('date', $date)->first()->totalProfitAmount ?? 0;
            $data['dataTotalReferralBonus'][] = $dailyReferralBonuses->where('date', $date)->first()->totalReferralBonusAmount ?? 0;
        }

        return response()->json($data);
    }

    public function getInvestmentStatRecords(){

        $basic = basicControl();

        $investment = Investment::selectRaw('SUM(CASE WHEN is_active = 1 THEN amount END) AS totalInvestmentAmount')
            ->get()
            ->toArray();

        $data['investmentRecords']['totalInvestments'] = $basic->currency_symbol.fractionNumber(getAmount(collect($investment)->collapse()['totalInvestmentAmount'])) ?? 0;

        $profit = Transaction::selectRaw('SUM((CASE WHEN remarks LIKE "%Interest From%" THEN amount END)) AS totalProfitAmount')
            ->get()
            ->toArray();

        $data['investmentRecords']['totalProfit'] = $basic->currency_symbol.fractionNumber(getAmount(collect($profit)->collapse()['totalProfitAmount'])) ?? 0;

        $referralBonus = User::selectRaw('SUM(total_referral_bonous) AS totalReferralBonusAmount')
            ->get()
            ->toArray();

        $data['investmentRecords']['totalReferralBonus'] = $basic->currency_symbol.fractionNumber(getAmount(collect($referralBonus)->collapse()['totalReferralBonusAmount'])) ?? 0;

        return response()->json($data);
    }

    public function index()
    {
        $data['firebaseNotify'] = config('firebase');
        $data['latestUser'] = User::latest()->limit(5)->get();
        $statistics['schedule'] = $this->dayList();
        $user = Auth::guard('admin')->user();
        $userPermission = optional($user->role)->permission;

        if ($user->role_id == null || in_array('admin.dashboard', $userPermission)) {

            return view('admin.dashboard', $data, compact("statistics"));
        } else {
            return view('admin.dashboard-alternative', $data, compact("statistics"));
        }
    }

    public function monthlyDepositWithdraw(Request $request)
    {
        $keyDataset = $request->keyDataset;

        $dailyDeposit = $this->dayList();

        Deposit::when($keyDataset == '0', function ($query) {
            $query->whereMonth('created_at', Carbon::now()->month);
        })
            ->when($keyDataset == '1', function ($query) {
                $lastMonth = Carbon::now()->subMonth();
                $query->whereMonth('created_at', $lastMonth->month);
            })
            ->select(
                DB::raw('SUM(payable_amount_in_base_currency) as totalDeposit'),
                DB::raw('DATE_FORMAT(created_at,"Day %d") as date')
            )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get()->map(function ($item) use ($dailyDeposit) {
                $dailyDeposit->put($item['date'], $item['totalDeposit']);
            });

        return response()->json([
            "totalDeposit" => currencyPosition($dailyDeposit->sum()),
            "dailyDeposit" => $dailyDeposit,
        ]);
    }

    public function saveToken(Request $request)
    {
        $admin = Auth::guard('admin')->user()
            ->fireBaseToken()
            ->create([
                'token' => $request->token,
            ]);
        return response()->json([
            'msg' => 'token saved successfully.',
        ]);
    }


    public function dayList()
    {
        $totalDays = Carbon::now()->endOfMonth()->format('d');
        $daysByMonth = [];
        for ($i = 1; $i <= $totalDays; $i++) {
            array_push($daysByMonth, ['Day ' . sprintf("%02d", $i) => 0]);
        }

        return collect($daysByMonth)->collapse();
    }

    protected function followupGrap($todaysRecords, $lastDayRecords = 0)
    {

        if (0 < $lastDayRecords) {
            $percentageIncrease = (($todaysRecords - $lastDayRecords) / $lastDayRecords) * 100;
        } else {
            $percentageIncrease = 0;
        }
        if ($percentageIncrease > 0) {
            $class = "bg-soft-success text-success";
        } elseif ($percentageIncrease < 0) {
            $class = "bg-soft-danger text-danger";
        } else {
            $class = "bg-soft-secondary text-body";
        }

        return [
            'class' => $class,
            'percentage' => round($percentageIncrease, 2)
        ];
    }


    public function chartUserRecords()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $userRecord = collect(User::selectRaw('COUNT(id) AS totalUsers')
            ->selectRaw('COUNT(CASE WHEN status = 0 THEN id END) AS bannedUsers')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDateUserCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDateUserCount')
            ->get()->makeHidden(['last-seen-activity', 'fullname'])
            ->toArray())->collapse();

        $followupGrap = $this->followupGrap($userRecord['currentDateUserCount'], $userRecord['previousDateUserCount']);

        $userRecord->put('followupGrapClass', $followupGrap['class']);
        $userRecord->put('followupGrap', $followupGrap['percentage']);

        $current_month_data = DB::table('users')
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();

        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $userRecord['chartPercentageIncDec'] = fractionNumber($userRecord['totalUsers'] - $userRecord['currentDateUserCount'], false);
        return response()->json(['userRecord' => $userRecord, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }

    public function chartTicketRecords()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $ticketRecord = collect(SupportTicket::selectRaw('COUNT(id) AS totalTickets')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDateTicketsCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDateTicketsCount')
            ->selectRaw('count(CASE WHEN status = 2  THEN status END) AS replied')
            ->selectRaw('count(CASE WHEN status = 1  THEN status END) AS answered')
            ->selectRaw('count(CASE WHEN status = 0  THEN status END) AS pending')
            ->get()
            ->toArray())->collapse();

        $followupGrap = $this->followupGrap($ticketRecord['currentDateTicketsCount'], $ticketRecord['previousDateTicketsCount']);
        $ticketRecord->put('followupGrapClass', $followupGrap['class']);
        $ticketRecord->put('followupGrap', $followupGrap['percentage']);

        $current_month_data = DB::table('support_tickets')
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();

        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $ticketRecord['chartPercentageIncDec'] = fractionNumber($ticketRecord['totalTickets'] - $ticketRecord['currentDateTicketsCount'], false);
        return response()->json(['ticketRecord' => $ticketRecord, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }

    public function chartKycRecords()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $kycRecords = collect(UserKyc::selectRaw('COUNT(id) AS totalKYC')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDateKYCCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDateKYCCount')
            ->selectRaw('count(CASE WHEN status = 0  THEN status END) AS pendingKYC')
            ->get()
            ->toArray())->collapse();
        $followupGrap = $this->followupGrap($kycRecords['currentDateKYCCount'], $kycRecords['previousDateKYCCount']);
        $kycRecords->put('followupGrapClass', $followupGrap['class']);
        $kycRecords->put('followupGrap', $followupGrap['percentage']);


        $current_month_data = DB::table('user_kycs')
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();

        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $kycRecords['chartPercentageIncDec'] = fractionNumber($kycRecords['totalKYC'] - $kycRecords['currentDateKYCCount'], false);
        return response()->json(['kycRecord' => $kycRecords, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }

    public function chartTransactionRecords()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        $transaction = collect(Transaction::selectRaw('COUNT(id) AS totalTransaction')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDateTransactionCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDateTransactionCount')
            ->whereRaw('YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW())')
            ->get()
            ->toArray())
            ->collapse();

        $followupGrap = $this->followupGrap($transaction['currentDateTransactionCount'], $transaction['previousDateTransactionCount']);
        $transaction->put('followupGrapClass', $followupGrap['class']);
        $transaction->put('followupGrap', $followupGrap['percentage']);


        $current_month_data = DB::table('transactions')
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();

        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $transaction['chartPercentageIncDec'] = fractionNumber($transaction['totalTransaction'] - $transaction['currentDateTransactionCount'], false);
        return response()->json(['transactionRecord' => $transaction, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }


    public function chartLoginHistory()
    {
        $userLoginsData = DB::table('user_logins')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->select('browser', 'os', 'get_device')
            ->get();

        $userLoginsBrowserData = $userLoginsData->groupBy('browser')->map->count();
        $data['browserKeys'] = $userLoginsBrowserData->keys();
        $data['browserValue'] = $userLoginsBrowserData->values();

        $userLoginsOSData = $userLoginsData->groupBy('os')->map->count();
        $data['osKeys'] = $userLoginsOSData->keys();
        $data['osValue'] = $userLoginsOSData->values();

        $userLoginsDeviceData = $userLoginsData->groupBy('get_device')->map->count();
        $data['deviceKeys'] = $userLoginsDeviceData->keys();
        $data['deviceValue'] = $userLoginsDeviceData->values();

        return response()->json(['loginPerformance' => $data]);
    }

    public function forbidden()
    {
        return view('admin.errors.403');
    }

    public function chartBrowserHistory(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $panelType = $request->panelType;

        $userLoginsData = DB::table('user_logins')
            ->when(isset($panelType) && $panelType === 'main_dashboard', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->when(isset($panelType) && $panelType === 'child_panel_dashboard', function ($query) {
                $query->whereNotNull('child_panel_id');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('browser', 'os', 'get_device')
            ->get();

        $userLoginsBrowserData = $userLoginsData->groupBy('browser')->map->count();
        $data['browserKeys'] = $userLoginsBrowserData->keys();
        $data['browserValue'] = $userLoginsBrowserData->values();

        return response()->json(['browserPerformance' => $data]);
    }

    public function chartOsHistory(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $panelType = $request->panelType;

        $userLoginsData = DB::table('user_logins')
            ->when(isset($panelType) && $panelType === 'main_dashboard', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->when(isset($panelType) && $panelType === 'child_panel_dashboard', function ($query) {
                $query->whereNotNull('child_panel_id');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('browser', 'os', 'get_device')
            ->get();

        $userLoginsOSData = $userLoginsData->groupBy('os')->map->count();
        $data['osKeys'] = $userLoginsOSData->keys();
        $data['osValue'] = $userLoginsOSData->values();

        return response()->json(['osPerformance' => $data]);
    }

    public function chartDeviceHistory(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $panelType = $request->panelType;

        $userLoginsData = DB::table('user_logins')
            ->when(isset($panelType) && $panelType === 'main_dashboard', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->when(isset($panelType) && $panelType === 'child_panel_dashboard', function ($query) {
                $query->whereNotNull('child_panel_id');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('browser', 'os', 'get_device')
            ->get();

        $userLoginsDeviceData = $userLoginsData->groupBy('get_device')->map->count();
        $data['deviceKeys'] = $userLoginsDeviceData->keys();
        $data['deviceValue'] = $userLoginsDeviceData->values();

        return response()->json(['deviceHistory' => $data]);
    }


}
