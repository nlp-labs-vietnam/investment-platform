<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PluginController extends Controller
{
    public function pluginConfig()
    {
        $basicControl = basicControl();
        return view('admin.plugin_controls.plugin_config', compact('basicControl'));
    }

    public function tawkConfiguration()
    {
        $basicControl = basicControl();
        return view('admin.plugin_controls.tawk_control', compact('basicControl'));
    }

    public function tawkConfigurationUpdate(Request $request)
    {
        try {
            $request->validate([
                'tawk_id' => 'required|string|min:3',
                'status' => 'required|integer|in:0,1',
            ]);

            $basicControl = basicControl();
            $basicControl->update([
                "tawk_id" => $request->tawk_id,
                "tawk_status" => $request->status
            ]);
            return back()->with('success', 'Tawk has been configured successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function fbMessengerConfiguration()
    {
        $basicControl = basicControl();
        return view('admin.plugin_controls.fb_messenger_control', compact('basicControl'));
    }

    public function fbMessengerConfigurationUpdate(Request $request)
    {
        try {
            $request->validate([
                'fb_app_id' => 'required|string|min:3',
                'fb_page_id' => 'required|string|min:3',
                'fb_messenger_status' => 'required|integer|min:0|in:0,1',
            ]);

            $basicControl = basicControl();
            $basicControl->update([
                "fb_app_id" => $request->fb_app_id,
                "fb_page_id" => $request->fb_page_id,
                "fb_messenger_status" => $request->fb_messenger_status
            ]);
            return back()->with('success', 'Fb messenger has been configured successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function googleRecaptchaConfiguration()
    {
        $data['googleRecaptchaSiteKey'] = config('google.recaptcha_site_key');
        $data['googleRecaptchaSecretKey'] = config('google.recaptcha_secret_key');
        $data['googleRecaptchaSiteVerifyUrl'] = config('google.recaptcha_site_verify_url');
        $data['basicControl'] = basicControl();
        return view('admin.plugin_controls.google_recaptcha_control', $data);
    }

    public function googleRecaptchaConfigurationUpdate(Request $request)
    {
        try {
            $request->validate([
                'google_recaptcha_site_key' => 'required|string|min:1',
                'google_recaptcha_secret_key' => 'required|string|min:1',
                'google_recaptcha_site_verify_url' => 'required|string|min:1',
                'admin_login_recaptcha' => 'nullable|integer|in:0,1',
                'user_login_recaptcha' => 'nullable|integer|in:0,1',
                'user_registration_recaptcha' => 'nullable|integer|in:0,1',
                'google_recaptcha' => 'nullable|integer|in:0,1',
            ]);

            $basicControl = basicControl();
            $basicControl->update([
                'google_recaptcha_admin_login' => $request->admin_login_recaptcha,
                'google_recaptcha_login' => $request->user_login_recaptcha,
                'google_recaptcha_register' => $request->user_registration_recaptcha,
                'google_recaptcha' => $request->google_recaptcha
            ]);

            $env = [
                'GOOGLE_RECAPTCHA_SITE_KEY' => $request->google_recaptcha_site_key,
                'GOOGLE_RECAPTCHA_SECRET_KEY' => $request->google_recaptcha_secret_key,
                'GOOGLE_RECAPTCHA_SITE_VERIFY_URL' => $request->google_recaptcha_site_verify_url,
            ];

            BasicService::setEnv($env);
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            return back()->with('success', 'Google recaptcha has been configured successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function googleAnalyticsConfiguration()
    {
        $basicControl = basicControl();
        return view('admin.plugin_controls.analytic_control', compact('basicControl'));
    }

    public function googleAnalyticsConfigurationUpdate(Request $request)
    {
        try {
            $request->validate([
                'MEASUREMENT_ID' => 'required|min:3',
                'analytic_status' => 'required|integer|in:0,1',
            ], [
                'MEASUREMENT_ID.required' => " The MEASUREMENT ID field is required."
            ]);

            $basicControl = basicControl();
            $basicControl->update([
                "measurement_id" => $request->MEASUREMENT_ID,
                "analytic_status" => $request->analytic_status,
            ]);
            return back()->with('success', 'Google Analytics has been configured successfully.');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function manualRecaptcha()
    {
        $basicControl = basicControl();
        return view('admin.plugin_controls.manual_recaptcha', compact('basicControl'));
    }

    public function manualRecaptchaUpdate(Request $request)
    {
        try {
            $request->validate([
                'manual_recaptcha_admin_login' => 'nullable|numeric|in:0,1',
                'manual_recaptcha_login' => 'nullable|numeric|in:0,1',
                'manual_recaptcha_register' => 'nullable|numeric|in:0,1',
                'manual_recaptcha' => 'nullable|numeric|in:0,1',
            ]);

            $basicControl = basicControl();
            $response = $basicControl->update([
                'manual_recaptcha_admin_login' => $request->manual_recaptcha_admin_login,
                'manual_recaptcha_login' => $request->manual_recaptcha_user_login,
                'manual_recaptcha_register' => $request->manual_recaptcha_user_registration,
                'manual_recaptcha' => $request->manual_recaptcha
            ]);

            if (!$response) {
                return back()->with('error', 'Something went wrong, while updating date');
            }
            return back()->with('success', 'Manual recaptcha updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function activeRecaptcha(Request $request)
    {

        try {
            $request->validate([
                'googleRecaptcha' => 'nullable|integer|in:0,1',
                'manualRecaptcha' => 'nullable|integer|in:0,1',
            ]);

            $basicControl = basicControl();
            $basicControl->manual_recaptcha = $request->manualRecaptcha;
            $basicControl->google_recaptcha = $request->googleRecaptcha;
            $basicControl->save();

            return response([
                'success' => true,
                'message' => "Recaptcha Updated Successfully"
            ]);
        } catch (\Exception $e) {
            return response([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
