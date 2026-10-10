<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::guard('admin')->user();
        if ($user->role_id == null) {
            return $next($request);
        }

        $userPermission = optional($user->role)->permission;

        if ($user->role) {
            if (in_array($request->route()->getName(), $userPermission)) {
                return $next($request);
            } elseif ($request->route()->getName() == 'admin.dashboard'){
                route('admin.dashboard');
            }
            else{
                return redirect()->route('admin.403');
            }
        }

        return $next($request);
    }


}
