<?php

namespace App\Http\Controllers\User;


use App\Helpers\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Investment;
use App\Models\Language;
use App\Models\Rank;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use Facades\App\Services\BasicService;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;


class HomeController extends Controller
{
    use Upload;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function saveToken(Request $request)
    {
        try {
            Auth::user()
                ->fireBaseToken()
                ->create([
                    'token' => $request->token,
                ]);
            return response()->json([
                'msg' => 'token saved successfully.',
            ]);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function index()
    {
        $data['user'] = Auth::user();
        $data['walletBalance'] = getAmount($this->user->balance);
        $data['interestBalance'] = getAmount($this->user->interest_balance);
        $data['totalDeposit'] = getAmount($this->user->deposit()->whereStatus(1)->sum('amount'));
        $data['totalPayout'] = getAmount($this->user->payout()->whereStatus(2)->sum('amount'));
        $data['totalRankBonus'] = getAmount($this->user->total_rank_bonous);

        $referralBonuses = $this->user->referralBonusLog()
        ->selectRaw('
            SUM(CASE WHEN type = "deposit" THEN amount ELSE 0 END) AS depositBonus,
            SUM(CASE WHEN type = "invest" THEN amount ELSE 0 END) AS investBonus,
            SUM(CASE WHEN type = "profit_commission" THEN amount ELSE 0 END) AS profitBonus,
            MAX(amount) AS lastBonus
        ')->first();
        $data['depositBonus'] = getAmount($referralBonuses->depositBonus);
        $data['investBonus'] = getAmount($referralBonuses->investBonus);
        $data['profitBonus'] = getAmount($referralBonuses->profitBonus);
        $data['lastBonus'] = getAmount($referralBonuses->lastBonus);

        $data['totalInterestProfit'] = getAmount($this->user->transaction()->where('balance_type', 'interest_balance')->where('trx_type', '+')->sum('amount'));

        $data['investment'] = collect(Investment::with('property')->where('user_id', $this->user->id)
            ->selectRaw('SUM(amount) AS totalInvestAmount')
            ->selectRaw('SUM(CASE WHEN invest_status = 1 AND status = 0 AND is_active = 1 THEN amount END) AS runningInvestAmount')
            ->selectRaw('COUNT(id) AS totalInvestment')
            ->selectRaw('COUNT(CASE WHEN invest_status = 1 AND status = 0 AND is_active = 1 THEN id END) AS runningInvestment')
            ->selectRaw('COUNT(CASE WHEN invest_status = 0 AND status = 0 AND is_active = 1 THEN id END) AS dueInvestment')
            ->selectRaw('COUNT(CASE WHEN invest_status = 1 AND status = 1 AND is_active = 1 THEN id END) AS completedInvestment')
            ->get()->toArray())->collapse();

        $data['ticket'] = SupportTicket::where('user_id', $this->user->id)->count();

        $monthlyInvestment = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        Investment::where('user_id', $this->user->id)
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

//        dd($monthly['investment']->flatten());

        $monthlyPayout = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        $this->user->payout()->whereStatus(2)
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
        $this->user->funds()->whereStatus(1)
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
        $this->user->referralBonusLog()->where('type', 'invest')
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

        $this->user->referralBonusLog()->where('type', 'deposit')
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
        $this->user->transaction()->whereBetween('created_at', [
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

        $latestRegisteredUser = User::where('referral_id', $this->user->id)->latest()->first();
        $data['allRanks'] = Rank::where('status', 1)->orderBy('sort_by', 'ASC')->get();

        $user = $this->user;

        $data['investorRank'] = BasicService::getInvestorCurrentRank($user);
        if ($data['investorRank'] != null) {
            $data['lastInvestorRank'] = $data['investorRank']->first();
        } else {
            $data['lastInvestorRank'] = null;
        }

        $data['firebaseNotify'] = config('firebase');
        return view(template() . 'user.dashboard', $data, compact('monthly', 'latestRegisteredUser'));
    }


    public function profile()
    {
        $data['languages'] = Language::all();
        $data['user'] = $this->user;
        return view(template() . 'user.profile.my_profile', $data);
    }


    public function profileUpdate(Request $request)
    {
        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });
        throw_if(!$languages, 'Language not found.');

        $req = $request->except('_method', '_token');
        $user = Auth::user();
        $rules = [
            'first_name' => 'required|string|min:1|max:100',
            'last_name' => 'required|string|min:1|max:100',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required|min:1|max:50',
            'username' => "sometimes|required|alpha_dash|min:5|unique:users,username," . $user->id,
            'address' => 'required|string|min:2|max:500',
            'language_id' => Rule::in($languages),
        ];
        $message = [
            'firstname.required' => 'First name field is required',
            'lastname.required' => 'Last name field is required',
        ];

        $validator = Validator::make($req, $rules, $message);
        if ($validator->fails()) {
            $validator->errors()->add('profile', '1');
            return back()->withErrors($validator)->withInput();
        }
        try {
            $response = $user->update([
                'language_id' => $req['language_id'],
                'firstname' => $req['first_name'],
                'lastname' => $req['last_name'],
                'email' => $req['email'],
                'phone' => $req['phone'],
                'username' => $req['username'],
                'address_one' => $req['address'],
            ]);

            throw_if(!$response, 'Something went wrong, While updating profile data');
            return back()->with('success', 'Profile updated Successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => "required",
            'password' => "required|min:5|confirmed",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = Auth::user();
        try {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
                return back()->with('success', 'Password Changes successfully.');
            } else {
                throw new \Exception('Current password did not match');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function addFund()
    {
        $data['totalPayment'] = null;
        $data['basic'] = basicControl();
        $data['gateways'] = Gateway::where('status', 1)->orderBy('sort_by', 'ASC')->get();
        return view(template() . 'user.fund.add_fund', $data);
    }

    public function fundHistory(Request $request)
    {
        $userId = Auth::id();
        $funds = Deposit::with(['depositable', 'gateway'])
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->latest()->paginate(basicControl()->paginate);
        return view(template() . 'user.fund.index', compact('funds'));
    }

    public function fundHistorySearch(Request $request)
    {
        $search = $request->all();
        $fromDate = Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date)->addDay();

        $funds = Deposit::orderBy('id', 'DESC')->where('user_id', $this->user->id)->where('status', '!=', 0)
            ->when(isset($search['name']), function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', $search['name']);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->when(isset($search['from_date']), function ($q2) use ($fromDate) {
                return $q2->whereDate('created_at', '>=', $fromDate);
            })
            ->when(isset($search['to_date']), function ($q2) use ($fromDate, $toDate) {
                return $q2->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->with('gateway')
            ->paginate(basicControl()->paginate);

        $funds->appends($search);

        return view(template() . 'user.fund.index', compact('funds'));

    }

    public function transaction()
    {
        $transactions = $this->user->transaction()->paginate(basicControl()->paginate);
        return view(template() . 'user.transaction.index', compact('transactions'));
    }

    public function transactionSearch(Request $request)
    {
        $search = $request->all();
        $fromDate = Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date)->addDay();

        $transaction = Transaction::where('user_id', $this->user->id)->with('user')
            ->when(@$search['transaction_id'], function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when(@$search['remark'], function ($query) use ($search) {
                return $query->where('remarks', 'LIKE', "%{$search['remark']}%");
            })
            ->when(isset($search['from_date']), function ($q2) use ($fromDate) {
                return $q2->whereDate('created_at', '>=', $fromDate);
            })
            ->when(isset($search['to_date']), function ($q2) use ($fromDate, $toDate) {
                return $q2->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->paginate(20);
        $transactions = $transaction->appends($search);

        return view(template() . 'user.transaction.index', compact('transactions'));
    }

}
