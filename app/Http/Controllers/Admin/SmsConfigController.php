<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManualSmsConfig;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;


class SmsConfigController extends Controller
{
    public function index()
    {
        $data['smsControlMethod'] = config('SMSConfig.SMS');
        $data['smsMethodDefault'] = config('SMSConfig.default');
        return view('admin.sms_controls.index', $data);
    }

    public function smsConfigEdit($method)
    {
        try {
            $basicControl = basicControl();
            $smsControlMethod = config('SMSConfig.SMS');
            $smsMethodParameters = $smsControlMethod[$method] ?? null;

            if ($method == "manual") {
                $manualSMSMethod  = ManualSmsConfig::first();
                return view('admin.sms_controls.manual_sms_config', compact("method", "manualSMSMethod", "basicControl"));
            }

            return view('admin.sms_controls.sms_config', compact('smsMethodParameters', 'method', 'basicControl'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function smsConfigUpdate(Request $request, $method)
    {
        $rules = [
            'method_name' => 'required|string|min:3|max:100',
            'sms_notification' => 'nullable|integer|min:0|in:0,1',
            'sms_verification' => 'nullable|integer|min:0|in:0,1',
        ];

        $smsControlMethod = config('SMSConfig.SMS');
        $smsMethodParameters = $smsControlMethod[$method] ?? null;

        foreach ($request->except('_token', '_method', 'method_name', 'sms_notification', 'sms_verification') as $key => $value) {
            if (array_key_exists($key, $smsMethodParameters)) {
                $rules[$key] = 'required|max:191';
            }
        }

        $request->validate($rules);

        try {
            $env = [
                'TWILIO_ACCOUNT_SID' => $request->twilio_account_sid ?? $smsControlMethod['twilio']['twilio_account_sid']['value'],
                'TWILIO_AUTH_TOKEN' => $request->twilio_auth_token ?? $smsControlMethod['twilio']['twilio_auth_token']['value'],
                'TWILIO_PHONE_NUMBER' => $request->twilio_phone_number ?? $smsControlMethod['twilio']['twilio_phone_number']['value'],
                'INFOBIP_API_KEY' => $request->infobip_api_key ?? $smsControlMethod['infobip']['infobip_api_key']['value'],
                'INFOBIP_URL_BASE_PATH' => $request->infobip_url_base_path ?? $smsControlMethod['infobip']['infobip_url_base_path']['value'],
                'PLIVO_ID' => $request->mailgun_domain ?? $smsControlMethod['plivo']['plivo_id']['value'],
                'PLIVO_AUTH_ID' => $request->mailgun_secret ?? $smsControlMethod['plivo']['plivo_auth_id']['value'],
                'PLIVO_AUTH_TOKEN' => $request->postmark_token ?? $smsControlMethod['plivo']['plivo_auth_token']['value'],
                'VONAGE_FROM' => $request->postmark_token ?? "VONAGE_FROM",
                'VONAGE_API_KEY' => $request->postmark_token ?? $smsControlMethod['vonage']['vonage_api_key']['value'],
                'VONAGE_API_SECRET' => $request->postmark_token ?? $smsControlMethod['vonage']['vonage_api_secret']['value'],
            ];

            BasicService::setEnv($env);

            $basicControl = basicControl();
            $basicControl->update([
                'sms_notification' => $request->sms_notification,
                'sms_verification' => $request->sms_verification

            ]);

            return back()->with('success', 'SMS Configuration has been updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function manualSmsMethodUpdate(Request $request, $method)
    {

        $this->validate($request, [
            'actionMethod' => 'required|in:GET,POST',
            'actionUrl' => 'required|url',
            'headerDataKeys.*' => 'nullable|string|min:2|required_with:headerValue.*',
            'headerDataValues.*' => 'nullable|string|min:2|required_with:headerKey.*',
            'paramKeys.*' => 'nullable|string|min:2|required_with:paramValue.*',
            'paramValues.*' => 'nullable|string|min:2|required_with:paramKey.*',
            'formDataKeys.*' => 'nullable|string|min:2|required_with:formDataValue.*',
            'formDataValues.*' => 'nullable|string|min:2|required_with:formDataKey.*',
            'sms_notification' => 'nullable|integer|in:0,1',
            'sms_verification' => 'nullable|integer|in:0,1'
        ], [
            'min' => 'This field must be at least :min characters.',
            'string' => 'This field must be :string.',
            'required_with' => 'This field is requird',
        ]);

        $headerData = array_combine($request->headerDataKeys, $request->headerDataValues);
        $paramData = array_combine($request->paramKeys, $request->paramValues);
        $formData = array_combine($request->formDataKeys, $request->formDataValues);

        $headerData = (empty(array_filter($headerData))) ? null : json_encode(array_filter($headerData));
        $paramData = (empty(array_filter($paramData))) ? null : json_encode(array_filter($paramData));
        $formData = (empty(array_filter($formData))) ? null : json_encode(array_filter($formData));


        $smsControl = ManualSmsConfig::firstOrCreate(['id' => 1]);
        $smsControl->action_method = $request->actionMethod;
        $smsControl->action_url = $request->actionUrl;
        $smsControl->form_data = $formData;
        $smsControl->param_data = $paramData;
        $smsControl->header_data = $headerData;
        $smsControl->save();

        $basicControl = basicControl();
        $basicControl->sms_notification = $request->sms_notification;
        $basicControl->sms_verification = $request->sms_verification;
        $basicControl->save();

        return back()->with('success', 'SMS Configuration has been updated successfully.');
    }

    public function smsSetAsDefault($method)
    {
        try {
            $env = [
                'SMS_METHOD' => $method
            ];
            BasicService::setEnv($env);

            return back()->with('success', 'SMS method set as default successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
