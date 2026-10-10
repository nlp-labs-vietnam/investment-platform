<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ActivityByUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {


        if (Auth::guard('web')->check()) {
            $expiresAt = Carbon::now()->addMinutes(2); // keep online for 1 min
            Cache::put('user-is-online-' . Auth::guard('web')->user()->id, true, $expiresAt);
            User::where('id', Auth::guard('web')->user()->id)->update(['last_seen' => (new \DateTime())->format("Y-m-d H:i:s")]);
        }
        if (Auth::guard('admin')->check()) {
            $expiresAt = Carbon::now()->addMinutes(2); // keep online for 1 min
            Cache::put('admin-is-online-' . Auth::guard('admin')->user()->id, true, $expiresAt);
            Admin::where('id', Auth::guard('admin')->user()->id)->update(['last_seen' => (new \DateTime())->format("Y-m-d H:i:s")]);
        }
        return $next($request);
    }
}




