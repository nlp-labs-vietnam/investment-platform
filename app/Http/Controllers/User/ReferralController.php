<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function referral()
    {
        $userId = Auth::id();
        $data['title'] = "My Referrals";
//        $data['directReferralUsers'] = getDirectReferralUsers($userId);

        $data['directReferralUsers'] = User::query()
            ->withCount('referrals')
            ->where('referral_id', $userId)
            ->get();


        return view(template().'user.referral.referral',$data);
    }

/*    public function getReferralUser(Request $request)
    {
        $data = getDirectReferralUsers($request->userId);
        $directReferralUsers = $data->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'count_direct_referral' => count(getDirectReferralUsers($user->id)),
                'joined_at' => dateTime($user->created_at),
            ];
        });

        return response()->json(['data' => $directReferralUsers]);
    }*/

    public function getReferralUser(Request $request)
    {

        $data = User::query()
            ->where('referral_id', $request->userId)
            ->withCount('referrals')
            ->get();

        $directReferralUsers = $data->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'count_direct_referral' => $user->referrals_count,
                'joined_at' => dateTime($user->created_at),
            ];
        });

        return response()->json(['data' => $directReferralUsers]);
    }


    public function referralBonus()
    {
        $title = "Referral Bonus";
        $user = $this->user;
        $transactions = $this->user->referralBonusLog()->orderBy('id', 'DESC')->with('bonusBy:id,firstname,lastname')->get();

        $totalReferralTransaction = [];
        foreach ($transactions as $trx) {
            $type = $trx->type;
            if (empty($totalReferralTransaction[$type])) {
                $totalReferralTransaction[$type] = 0;
            }
            $totalReferralTransaction[$type] += $trx->amount;
        }

        $totalReferralTransaction['total_referral_bonous'] = array_sum($totalReferralTransaction);
        $transactions = paginate($transactions, basicControl()->paginate, $page = null, $options = ["path" => route('user.referral.bonus')]);
        return view(template() . 'user.referral.referralBonus', compact('title', 'transactions', 'totalReferralTransaction', 'user'));
    }

    public function referralBonusSearch(Request $request)
    {
        $title = "Referral Bonus";
        $user = $this->user;
        $search = $request->all();
        $fromDate = Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date)->addDay();

        $transactions = $this->user->referralBonusLog()->latest()
            ->with('bonusBy:id,firstname,lastname')
            ->when(isset($search['search_user']), function ($query) use ($search) {
                return $query->whereHas('bonusBy', function ($q) use ($search) {
                    $q->where(DB::raw('concat(firstname, " ", lastname)'), 'LIKE', "%{$search['search_user']}%")
                        ->orWhere('firstname', 'LIKE', '%' . $search['search_user'] . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search['search_user'] . '%')
                        ->orWhere('username', 'LIKE', '%' . $search['search_user'] . '%');
                });
            })
            ->when(isset($search['type']), function ($query) use ($search) {
                return $query->where('type', 'LIKE', '%' . $search['type'] . '%');
            })
            ->when(isset($search['remark']), function ($query) use ($search) {
                return $query->where('remarks', 'LIKE', '%' . $search['remark'] . '%');
            })
            ->when(isset($search['from_date']), function ($q2) use ($fromDate) {
                return $q2->whereDate('created_at', '>=', $fromDate);
            })
            ->when(isset($search['to_date']), function ($q2) use ($fromDate, $toDate) {
                return $q2->whereBetween('created_at', [$fromDate, $toDate]);
            })->get();

        $totalReferralTransaction = [];
        foreach ($transactions as $trx) {
            $type = $trx->type;
            if (empty($totalReferralTransaction[$type])) {
                $totalReferralTransaction[$type] = 0;
            }
            $totalReferralTransaction[$type] += $trx->amount;
        }

        $totalReferralTransaction['total_referral_bonous'] = array_sum($totalReferralTransaction);
        $transactions = paginate($transactions, basicControl()->paginate, $page = null, $options = ["path" => route('user.referral.bonus')]);

        $transactions = $transactions->appends($search);

        return view(template() . 'user.referral.referralBonus', compact('title', 'transactions', 'totalReferralTransaction', 'user'));
    }
}
