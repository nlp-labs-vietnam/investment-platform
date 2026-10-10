<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user->github_id || $user->google_id || $user->facebook_id) {
            return $next($request);
        }

        if($user->status && $user->email_verification && $user->sms_verification && $user->two_fa_verify){
            return $next($request);
        }

        return redirect(route('user.check'));

    }

}
