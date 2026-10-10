<?php

namespace App\Http\Middleware;

use App\Models\Kyc as KYCModel;
use App\Models\UserKyc;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class KYC
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);

        $kyc = KYCModel::where('status', 1)->get();

        foreach ($kyc as $item) {
            $userKyc = UserKyc::where('user_id', Auth::user()->id)->where('kyc_id', $item->id)->where('status', 1)->first();
            if (!$userKyc) {
                return redirect()->route('user.dashboard')->with('error', 'Kyc verification failed');
            }
        }
        return $next($request);
    }


}
