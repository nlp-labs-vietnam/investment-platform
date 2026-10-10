<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationPermission;
use App\Models\NotificationTemplate;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationPermissionController extends Controller
{
    use ApiResponse;

    public function notificationPermission()
    {
        try {

            $notifications = NotificationTemplate::where('notify_for', 0)->get();

            if (!$notifications) {
                return response()->json($this->withError('Notification Template Not Found'));
            }

            $statusLabel = [
                '0 = Inactive',
                '1 = Active',
            ];

            $formattedNotifications = $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'name' => $notification->name,
                    'subject' => $notification->subject,
                    'template_key' => $notification->template_key,
                    'status' => $notification->status,
                ];
            });

            $user = auth()->user();
            $data['statusLabel'] = $statusLabel;
            $data['notification'] = $formattedNotifications;
            $data['userNotificationPermission'] = $user->notificationPermission;
            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function notificationPermissionUpdate(Request $request)
    {
        try {
            $user = Auth::user();
            $rules = [
                'email_key' => 'required',
                'sms_key' => 'required',
                'in_app_key' => 'required',
                'push_key' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($this->withError(collect($validator->errors())->collapse()));
            }

            $userTemplate = NotificationPermission::where('notifyable_id', $user->id)
                ->where('notifyable_type', User::class)
                ->first();

            if (!$userTemplate) {
                return response()->json($this->withError('Notification not found'));
            }

            $userTemplate->template_email_key = $request->email_key;
            $userTemplate->template_sms_key = $request->sms_key;
            $userTemplate->template_in_app_key = $request->in_app_key;
            $userTemplate->template_push_key = $request->push_key;
            $userTemplate->save();
            return response()->json($this->withSuccess('Notification Permission Updated Successfully.'));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function pusherConfiguration()
    {
        try {
            $data['apiKey'] = env('PUSHER_APP_KEY');
            $data['cluster'] = env('PUSHER_APP_CLUSTER');
            $data['channel'] = 'user-notification.' . Auth::id();
            $data['event'] = 'UserNotification';
            $data['chattingChannel'] = 'offer-chat-notification.' . Auth::id();
            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }
}
