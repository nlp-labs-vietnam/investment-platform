<?php

namespace App\Traits;

use App\Events\AdminNotification;
use App\Events\UserNotification;
use App\Mail\SendMail;
use App\Models\Admin;
use App\Models\FireBaseToken;
use App\Models\InAppNotification;
use App\Models\ManualSmsConfig;
use App\Models\NotificationTemplate;
use App\Models\SmsControl;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Facades\App\Services\BasicCurl;
use Facades\App\Services\SMS\BaseSmsService;
use Google\Auth\CredentialsLoader;
use Google\Auth\Middleware\AuthTokenMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;


trait Notify
{
    public function sendMailSms($user, $templateKey, $params = [], $subject = null, $requestMessage = null)
    {
        $this->mail($user, $templateKey, $params, $subject, $requestMessage);
        $this->sms($user, $templateKey, $params, $requestMessage = null);
    }

    public function mail($user, $templateKey = null, $params = [], $subject = null, $requestMessage = null)
    {
        try {
            $basic = basicControl();
            if ($basic->email_notification != 1) {
                return false;
            }

            $email_body = $basic->email_description;
            $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('language_id', $user->language_id)->where('notify_for', 0)->first();
            if (!$templateObj) {
                $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 0)->first();
            }

            $message = str_replace("[[name]]", $user->username, $email_body);

            if (!$templateObj && $subject == null) {
                return false;
            } else {
                if ($templateObj) {
                    $message = str_replace("[[message]]", $templateObj->email, $message);
                    if (empty($message)) {
                        $message = $email_body;
                    }
                    foreach ($params as $code => $value) {
                        $message = str_replace('[[' . $code . ']]', $value, $message);
                    }
                } else {
                    $message = str_replace("[[message]]", $requestMessage, $message);
                }
            }

            $subject = ($subject == null) ? $templateObj->subject : $subject;
            $email_from = $basic->sender_email;

//            dd($user->username, $email_from, $subject, $message);

            Mail::to($user)->queue(new SendMail($email_from, $subject, $message));
        } catch (\Exception $exception) {
            return true;
        }
    }

    public function sms($user, $templateKey, $params = [], $requestMessage = null)
    {
        $basic = basicControl();
        if ($basic->sms_notification != 1) {
            return false;
        }

        $smsControl = ManualSmsConfig::firstOrCreate();

        $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('language_id', $user->language_id)->where('notify_for', 0)->first();
        if (!$templateObj) {
            $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 0)->first();
        }
        if (!$templateObj) {
            return 0;
        }

        if (!$templateObj->status['sms']) {
            return false;
        }

        if (!$templateObj && $requestMessage == null) {
            return false;
        } else {
            if ($templateObj) {
                $template = $templateObj->sms;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
            } else {
                $template = $requestMessage;
            }
        }

        if (config('SMSConfig.default') == 'manual') {
            $headerData = is_null($smsControl->header_data) ? [] : json_decode($smsControl->header_data, true);
            $paramData = is_null($smsControl->header_data) ? [] : json_decode($smsControl->header_data, true);
            $paramData = http_build_query($paramData);
            $actionUrl = $smsControl->header_data;
            $actionMethod = $smsControl->action_method;
            $formData = is_null($smsControl->form_data) ? [] : json_decode($smsControl->form_data, true);
            $formData = isset($headerData['Content-Type']) && $headerData['Content-Type'] == "application/x-www-form-urlencoded" ? http_build_query($formData) : (isset($headerData['Content-Type']) && $headerData['Content-Type'] == "application/json" ? json_encode($formData) : $formData);

            foreach ($headerData as $key => $data) {
                $headerData[] = "{$key}:$data";
            }

            if ($actionMethod == 'GET') {
                $actionUrl = $actionUrl . '?' . $paramData;
            }

            $formData = recursive_array_replace("[[receiver]]", $user->phone, recursive_array_replace("[[message]]", $template, $formData));
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $actionUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $actionMethod,
                CURLOPT_POSTFIELDS => $formData,
                CURLOPT_HTTPHEADER => $headerData
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } else {
            BaseSmsService::sendSMS($user->phone_code . $user->phone, $template);
            return true;
        }
    }


    public function verifyToMail($user, $templateKey = null, $params = [], $subject = null, $requestMessage = null)
    {

        $basic = basicControl();
        if ($basic->email_verification != 1) {
            return false;
        }

        $email_body = $basic->email_description;
        $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('language_id', $user->language_id)->first();
        if (!$templateObj) {
            $templateObj = NotificationTemplate::where('template_key', $templateKey)->first();
        }

        if (!$templateObj) {
            return 0;
        }

        if (!$templateObj->status['mail']) {
            return false;
        }

        $message = str_replace("[[name]]", $user->username, $email_body);

        if (!$templateObj && $subject == null) {
            return false;
        } else {
            if ($templateObj) {
                $message = str_replace("[[message]]", $templateObj->email, $message);
                if (empty($message)) {
                    $message = $email_body;
                }
                foreach ($params as $code => $value) {
                    $message = str_replace('[[' . $code . ']]', $value, $message);
                }
            } else {
                $message = str_replace("[[message]]", $requestMessage, $message);
            }
        }

        $subject = ($subject == null) ? $templateObj->subject : $subject;
        $email_from = ($templateObj) ? $templateObj->email_from : $basic->sender_email;

        Mail::to($user)->queue(new SendMail($email_from, $subject, $message));
    }

    public function verifyToSms($user, $templateKey, $params = [], $requestMessage = null)
    {

        $basic = basicControl();
        if ($basic->sms_verification == 1) {
            return false;
        }

        $smsControl = ManualSmsConfig::firstOrCreate(['id' => 1]);
        $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('language_id', $user->language_id)->where('notify_for', 0)->first();
        if (!$templateObj) {
            $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 0)->first();
        }


        if (!$templateObj || $templateObj->status['sms'] == 0) {
            return false;
        }


        if (!$templateObj && $requestMessage == null) {
            return false;
        } else {
            if ($templateObj) {
                $template = $templateObj->sms;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
            } else {
                $template = $requestMessage;
            }
        }

        if (config('SMSConfig.default') == 'manual') {

            $phone = $user->phone_code . '' . $user->phone;
            $headerData = is_null($smsControl->header_data) ? [] : json_decode($smsControl->header_data, true);
            $paramData = is_null($smsControl->header_data) ? [] : json_decode($smsControl->header_data, true);
            $paramData = http_build_query($paramData);
            $actionUrl = $smsControl->header_data;
            $actionMethod = $smsControl->action_method;
            $formData = is_null($smsControl->form_data) ? [] : json_decode($smsControl->form_data, true);
            $formData = recursive_array_replace("[[receiver]]", $phone, recursive_array_replace("[[message]]", $template, $formData));
            $formData = isset($headerData['Content-Type']) && $headerData['Content-Type'] == "application/x-www-form-urlencoded" ? http_build_query($formData) : (isset($headerData['Content-Type']) && $headerData['Content-Type'] == "application/json" ? json_encode($formData) : $formData);

            foreach ($headerData as $key => $data) {
                $headerData[] = "{$key}:$data";
            }

            if ($actionMethod == 'GET') {
                $actionUrl = $actionUrl . '?' . $paramData;
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $actionUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $actionMethod,
                CURLOPT_POSTFIELDS => $formData,
                CURLOPT_HTTPHEADER => $headerData
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } else {
            BaseSmsService::sendSMS($user->phone_code . $user->phone, $template);
            return true;
        }
    }

    public function userFirebasePushNotification($user, $templateKey, $params = [], $action = null)
    {
        try {
            $basic = basicControl();
            $notify = config('firebase');

            if (!$basic->push_notification) {
                return false;
            }
            if ($notify['user_foreground'] == 0 && $notify['user_background'] == 0) {
                return false;
            }

            $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('language_id', $user->language_id)->first();
            if (!$templateObj->status['push']) {
                return false;
            }

            if (!$templateObj) {
                $templateObj = NotificationTemplate::where('template_key', $templateKey)->first();
                if (!$templateObj->status['push']) {
                    return false;
                }
            }

            $template = '';
            if ($templateObj) {
                $template = $templateObj->push;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
            }

            $users = FireBaseToken::where('tokenable_id', $user->id)->latest()->get();

            $auth = CredentialsLoader::makeCredentials(
                ['https://www.googleapis.com/auth/cloud-platform'],
                json_decode(file_get_contents(base_path(getFirebaseFileName())), true)
            );
            $stack = HandlerStack::create();
            $middleware = new AuthTokenMiddleware($auth);
            $stack->push($middleware);
            $client = new Client(['handler' => $stack]);

            foreach ($users as $user) {
                try {
                    $data = [
                        'message' => [
                            'token' => $user->token,
                            'notification' => [
                                'title' => $templateObj->name . ' from ' . $basic->site_title,
                                'body' => $template,
                            ],
                            "data" => [
                                'foreground' => (string)$notify['user_foreground'],
                                'background' => (string)$notify['user_background'],
                                "click_action" => $action,
                                'redirect' => 'notification',
                            ],
                        ],
                    ];

                    $url = 'https://fcm.googleapis.com/v1/projects/' . $notify['projectId'] . '/messages:send';
                    $response = $client->post($url, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $auth->fetchAuthToken()['access_token'],
                            'Content-Type' => 'application/json',
                        ],
                        'json' => $data,
                    ]);
                } catch (\Exception $e) {
                    continue;
                }
            }


        } catch (\Exception $e) {
            return 0;
        }
    }

    public function userPushNotification($user, $templateKey, $params = [], $action = [])
    {
        try {
            $basic = basicControl();
            if ($basic->in_app_notification != 1) {
                return false;
            }

            $templateObj = NotificationTemplate::where('template_key', $templateKey)
                ->where('language_id', $user->language_id)->where('notify_for', 0)
                ->first();

            if (!$templateObj) {
                $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 0)->first();
                if (!$templateObj || !$templateObj->status['in_app']) {
                    return false;
                }
            }

            if ($templateObj) {
                $template = $templateObj->in_app;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
                $action['text'] = $template;
            }

            $inAppNotification = new InAppNotification();
            $inAppNotification->description = $action;
            $user->inAppNotification()->save($inAppNotification);
            event(new UserNotification($inAppNotification, $user->id));

        } catch (\Exception $e) {
            return 0;
        }

    }

    public function adminFirebasePushNotification($templateKey, $params = [], $action = null)
    {
        try {
            $basic = basicControl();
            $notify = config('firebase');
            if (!$notify) {
                return false;
            }

            if (!$basic->push_notification) {
                return false;
            }

            $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 1)->first();
            if (!$templateObj->status['push']) {
                return false;
            }

            if (!$templateObj) {
                return false;
            }

            $template = '';
            if ($templateObj) {
                $template = $templateObj->push;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
            }
            $admins = FireBaseToken::where('tokenable_type', Admin::class)->get();

            foreach ($admins as $admin) {
                $data = [
                    "to" => $admin->token,
                    "notification" => [
                        "title" => $templateObj->name,
                        "body" => $template,
                        "icon" => getFile(config('filesystems.default'), basicControl()->favicon),
                        "data" => [
                            "foreground" => (int)$notify['admin_foreground'],
                            "background" => (int)$notify['admin_background'],
                            "click_action" => $action
                        ],
                        "content_available" => true,
                        "mutable_content" => true
                    ]
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $notify['serverKey']
                ])
                    ->acceptJson()
                    ->post('https://fcm.googleapis.com/fcm/send', $data);
            }
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function adminPushNotification($templateKey, $params = [], $action = [])
    {

        try {
            $basic = basicControl();
            if ($basic->in_app_notification != 1) {
                return false;
            }

            $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 1)->first();
            if (!$templateObj->status['in_app']) {
                return false;
            }

            if ($templateObj) {
                $template = $templateObj->in_app;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
                $action['text'] = $template;
            }

            $admins = Admin::all();
            foreach ($admins as $admin) {
                $inAppNotification = new InAppNotification();
                $inAppNotification->description = $action;
                $admin->inAppNotification()->save($inAppNotification);
                event(new AdminNotification($inAppNotification, $admin->id));
            }
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function adminMail($templateKey = null, $params = [], $subject = null, $requestMessage = null)
    {
        $basic = basicControl();

        if ($basic->email_notification != 1) {
            return false;
        }

        $email_body = $basic->email_description;
        $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 1)->first();
        if (!$templateObj) {
            $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 1)->first();
        }
        if (!isset($templateObj->status['mail'])) {
            return false;
        }

        $message = $email_body;
        if ($templateObj) {
            $message = str_replace("[[message]]", $templateObj->email, $message);

            if (empty($message)) {
                $message = $email_body;
            }
            foreach ($params as $code => $value) {
                $message = str_replace('[[' . $code . ']]', $value, $message);
            }
        } else {
            $message = str_replace("[[message]]", $requestMessage, $message);
        }

        $subject = ($subject == null) ? $templateObj->subject : $subject;
        $email_from = $basic->sender_email;
        $admins = Admin::all();
        foreach ($admins as $admin) {
            $message = str_replace("[[name]]", $admin->username, $message);
            Mail::to($admin)->queue(new SendMail($email_from, $subject, $message));
        }
    }
}
