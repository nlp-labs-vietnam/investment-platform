<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class SocialiteController extends Controller
{
    public function index()
    {
        return view('admin.control_panel.socialiteConfig');
    }

    public function githubConfig(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.control_panel.githubControl');
        } elseif ($request->isMethod('post')) {
            $purifiedData = Purify::clean($request->all());

            $validator = Validator::make($purifiedData, [
                'github_client_id' => 'required|min:3',
                'github_client_secret' => 'required|min:3',
                'github_status' => 'nullable|integer|min:0|in:0,1',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $purifiedData = (object)$purifiedData;
            config(['socialite.github_status' => $purifiedData->github_status]);
            $fp = fopen(base_path() . '/config/socialite.php', 'w');
            fwrite($fp, '<?php return ' . var_export(config('socialite'), true) . ';');
            fclose($fp);

            $envPath = base_path('.env');
            $env = file($envPath);
            $env = $this->set('GITHUB_CLIENT_ID', $purifiedData->github_client_id, $env);
            $env = $this->set('GITHUB_CLIENT_SECRET', $purifiedData->github_client_secret, $env);
            $env = $this->set('GITHUB_REDIRECT_URL', route('socialiteCallback', 'github'), $env);

            $fp = fopen($envPath, 'w');
            fwrite($fp, implode($env));
            fclose($fp);

            Artisan::call('optimize:clear');
            return back()->with('success', 'Successfully Updated');
        }
    }

    public function googleConfig(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.control_panel.googleControl');
        } elseif ($request->isMethod('post')) {
            $purifiedData = Purify::clean($request->all());

            $validator = Validator::make($purifiedData, [
                'google_client_id' => 'required|min:3',
                'google_client_secret' => 'required|min:3',
                'google_status' => 'nullable|integer|min:0|in:0,1',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $purifiedData = (object)$purifiedData;
            config(['socialite.google_status' => $purifiedData->google_status]);
            $fp = fopen(base_path() . '/config/socialite.php', 'w');
            fwrite($fp, '<?php return ' . var_export(config('socialite'), true) . ';');
            fclose($fp);

            $envPath = base_path('.env');
            $env = file($envPath);
            $env = $this->set('GOOGLE_CLIENT_ID', $purifiedData->google_client_id, $env);
            $env = $this->set('GOOGLE_CLIENT_SECRET', $purifiedData->google_client_secret, $env);
            $env = $this->set('GOOGLE_REDIRECT_URL', route('socialiteCallback', 'google'), $env);

            $fp = fopen($envPath, 'w');
            fwrite($fp, implode($env));
            fclose($fp);

            Artisan::call('optimize:clear');
            return back()->with('success', 'Successfully Updated');
        }
    }

    public function facebookConfig(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.control_panel.facebookControl');
        } elseif ($request->isMethod('post')) {
            $purifiedData = Purify::clean($request->all());

            $validator = Validator::make($purifiedData, [
                'facebook_client_id' => 'required|min:3',
                'facebook_client_secret' => 'required|min:3',
                'facebook_status' => 'nullable|integer|min:0|in:0,1',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $purifiedData = (object)$purifiedData;

            config(['socialite.facebook_status' => $purifiedData->facebook_status]);
            $fp = fopen(base_path() . '/config/socialite.php', 'w');
            fwrite($fp, '<?php return ' . var_export(config('socialite'), true) . ';');
            fclose($fp);

            $envPath = base_path('.env');
            $env = file($envPath);
            $env = $this->set('FACEBOOK_CLIENT_ID', $purifiedData->facebook_client_id, $env);
            $env = $this->set('FACEBOOK_CLIENT_SECRET', $purifiedData->facebook_client_secret, $env);
            $env = $this->set('FACEBOOK_REDIRECT_URL', route('socialiteCallback', 'facebook'), $env);

            $fp = fopen($envPath, 'w');
            fwrite($fp, implode($env));
            fclose($fp);

            Artisan::call('optimize:clear');
            return back()->with('success', 'Successfully Updated');
        }
    }

    private function set($key, $value, $env)
    {
        foreach ($env as $env_key => $env_value) {
            $entry = explode("=", $env_value, 2);
            if ($entry[0] == $key) {
                $env[$env_key] = $key . "=" . $value . "\n";
            } else {
                $env[$env_key] = $env_value;
            }
        }
        return $env;
    }
}
