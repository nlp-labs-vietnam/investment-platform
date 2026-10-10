<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ReferralCommissionController extends Controller
{
    public function referralCommission()
    {
        $data['basicControl'] = basicControl();
        $data['referrals'] = Referral::get();
        return view('admin.referral.commission', $data);
    }

    public function referralCommissionAction(Request $request)
    {
        try {
            $basic = BasicControl();
            $response = BasicControl::updateOrCreate([
                'id' => $basic->id ?? ''
            ], [
                'deposit_commission' => $request->deposit_commission,
                'investment_commission' => $request->investment_commission,
                'profit_commission' => $request->profit_commission,
                'rank_commission' => $request->rank_commission,
            ]);

            if (!$response)
                throw new Exception('Something went wrong, when updating the data.');

            session()->flash('success', 'Commission Status Updated successfully');
            Artisan::call('optimize:clear');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function referralCommissionStore(Request $request)
    {
        Referral::where('commission_type', $request->commission_type)->delete();

        for ($i = 0; $i < count($request->level); $i++) {
            $referral = new Referral();
            $referral->commission_type = $request->commission_type;
            $referral->level = $request->level[$i];
            $referral->percent = $request->percent[$i];
            $referral->save();
        }

        return back()->with('success', 'Level Bonus Has been Updated.');
    }
}
