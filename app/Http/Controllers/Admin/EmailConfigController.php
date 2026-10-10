<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\sendTestMail;
use App\Models\NotificationTemplate;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;

class EmailConfigController extends Controller
{
    public function emailControls()
    {
        $data['mailMethod'] = config('mailconfig');
        $data['mailMethodDefault'] = config('mail.default');
        return view('admin.email_controls.index', $data);
    }

    public function emailConfigEdit($method)
    {
        try {
            $data['basicControl'] = basicControl();
            $mailMethod = config('mailconfig');
            $mailParameters = $mailMethod[$method] ?? null;
            return view('admin.email_controls.email_config', $data, compact('mailParameters', 'method'));

        } catch (\Exception $exception) {
            return back()->with('alert', $exception->getMessage());
        }
    }

    public function emailConfigUpdate(Request $request, $method)
    {
        $rules = [
            'sender_email' => 'required|email:rfc,dns',
            'email_method' => 'required|string',
            'email_notification' => 'nullable|integer|in:0,1',
            'email_verification' => 'nullable|integer|in:0,1',
        ];

        $mailMethod = config('mailconfig');
        $mailParameters = $mailMethod[$method] ?? null;

        foreach ($request->except('_token', '_method') as $key => $value) {
            if (array_key_exists($key, $mailParameters)) {
                $rules[$key] = 'required|max:191';
            }
        }
        $request->validate($rules);

        try {
            $env = [
                'MAIL_FROM_ADDRESS' => $request->sender_email,
                'MAIL_HOST' => $request->mail_host ?? $mailMethod['SMTP']['mail_host']['value'],
                'MAIL_PORT' => $request->mail_port ?? $mailMethod['SMTP']['mail_port']['value'],
                'MAIL_USERNAME' => $request->mail_username ?? $mailMethod['SMTP']['mail_username']['value'],
                'MAIL_PASSWORD' => isset($request->mail_password) ? '"' . $request->mail_password . '"' : $mailMethod['SMTP']['mail_password']['value'],
                'MAIL_ENCRYPTION' => isset($request->mail_encryption) ? '"' . $request->mail_encryption . '"' : $mailMethod['SMTP']['mail_encryption']['value'],
                'MAILGUN_DOMAIN' => $request->mailgun_domain ?? $mailMethod['mailgun']['mailgun_domain']['value'],
                'MAILGUN_SECRET' => $request->mailgun_secret ?? $mailMethod['mailgun']['mailgun_secret']['value'],
                'POSTMARK_TOKEN' => $request->postmark_token ?? $mailMethod['postmark']['postmark_token']['value'],
                'AWS_ACCESS_KEY_ID' => $request->aws_secret_access_key ?? $mailMethod['SES']['aws_access_key_id']['value'],
                'AWS_SECRET_ACCESS_KEY' => $request->aws_default_region ?? $mailMethod['SES']['aws_secret_access_key']['value'],
                'AWS_DEFAULT_REGION' => $request->aws_default_region ?? $mailMethod['SES']['aws_default_region']['value'],
                'AWS_SESSION_TOKEN' => $request->aws_session_token ?? $mailMethod['SES']['aws_session_token']['value'],
                'MAILERSEND_API_KEY' => $request->mailersend_api_key ?? $mailMethod['mailersend']['mailersend_api_key']['value'],
                'SENDINBLUE_API_KEY' => $request->sendinblue_api_key ?? $mailMethod['sendinblue']['sendinblue_api_key']['value'],
                'SENDGRID_API_KEY' => $request->sendgrid_api_key ?? $mailMethod['sendgrid']['sendgrid_api_key']['value'],
                'MAILCHIMP_API_KEY' => $request->mailchimp_api_key ?? $mailMethod['mailchimp']['mailchimp_api_key']['value'],
            ];


            BasicService::setEnv($env);

            $basicControl = basicControl();
            $basicControl->update([
                "email_notification" => $request->email_notification,
                "email_verification" => $request->email_verification,
                "sender_email" => $request->sender_email,
            ]);

            NotificationTemplate::get()->map(function ($item) use ($request){
                $item->email_from = $request->sender_email;
                $item->save();

            });

            return back()->with('success', 'Email Configuration has been updated successfully.');

        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function emailSetAsDefault(Request $request, $method)
    {
        try {
            $env = [
                'MAIL_MAILER' => $method
            ];
            BasicService::setEnv($env);

            return back()->with('success', 'Mail method set as default successfully.');
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns',
        ]);

        $basic = basicControl();

        if ($basic->email_notification !== 1) {
            return back()->with('warning', 'Your email notification is disabled');
        }

        $email_from = $basic->sender_email;
        Mail::to($request->email)->send(new sendTestMail($email_from, "Test Email", "Your " . $_SERVER['SERVER_NAME'] . " email is working."));

        return back()->with('success', 'Email has been sent successfully.');
    }

}
