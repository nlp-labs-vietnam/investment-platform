<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;

class PusherConfigController extends Controller
{
    public function pusherConfig()
    {
        $basicControl = basicControl();
        $data['pusherAppId'] = config('pusher.app_id');
        $data['pusherAppKey'] = config('pusher.app_key');
        $data['pusherAppSecret'] = config('pusher.app_secret');
        $data['pusherAppCluster'] = config('pusher.app_cluster');

        return view('admin.control_panel.pusher_config', $data, compact('basicControl'));
    }

    public function pusherConfigUpdate(Request $request)
    {
        $request->validate([
            'pusher_app_id' => 'required|string|regex:/^[A-Za-z0-9_.-]+$/',
            'pusher_app_key' => 'required|string|regex:/^[A-Za-z0-9_.-]+$/',
            'pusher_app_secret' => 'required|string|regex:/^[A-Za-z0-9_.-]+$/',
            'pusher_app_cluster' => 'required|string|regex:/^[A-Za-z0-9_.-]+$/',
            'push_notification' => 'nullable|integer|min:0|in:0,1',
        ]);

        $env = [
            'PUSHER_APP_ID' => $request->pusher_app_id,
            'PUSHER_APP_KEY' => $request->pusher_app_key,
            'PUSHER_APP_SECRET' => $request->pusher_app_secret,
            'PUSHER_APP_CLUSTER' => $request->pusher_app_cluster,
        ];

        BasicService::setEnv($env);

        $basicControl = basicControl();
        $basicControl->update([
           'in_app_notification' => $request->in_app_notification
        ]);

        return back()->with('success', 'Pusher Configuration Successfully');

    }
}
