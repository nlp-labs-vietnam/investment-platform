<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UserSystemInfo;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FA\Google2FA;

class TwoFaSecurityController extends Controller
{
    use ApiResponse, Notify;

    public function twoStepSecurity()
    {
        try {
            $basic = basicControl();
            $user = auth()->user();
            $google2fa = new Google2FA();
            $secret = $user->two_fa_code ?? $this->generateSecretKeyForUser($user);

            $qrCodeUrl = $google2fa->getQRCodeUrl(
                auth()->user()->username,
                $basic->site_title,
                $secret
            );

            $data = [
                'twoFactorEnable' => $user->two_fa == 0 ? false : true,
                'secret' => $secret,
                'qrCodeUrl' => $qrCodeUrl,
                'downloadApp' => 'https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en',
                'iosApp' => 'https://apps.apple.com/us/app/google-authenticator/id388497605',
            ];
            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    private function generateSecretKeyForUser(User $user)
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        $user->update(['two_fa_code' => $secret]);

        return $secret;
    }

    public function twoStepEnable(Request $request)
    {
        try {

            $rules = [
                'code' => 'required',
            ];

            $validate = Validator::make($request->all(), $rules);

            if ($validate->fails()) {
                return response()->json($this->withError(collect($validate->errors())->collapse()));
            }

            $user = auth()->user();
            $secret = auth()->user()->two_fa_code;

            if ($secret == $request->code) {
                $user['two_fa'] = 1;
                $user['two_fa_verify'] = 1;
                $user->save();

                $this->mail($user, 'TWO_STEP_ENABLED', [
                    'action' => 'Enabled',
                    'code' => $request->code,
                    'ip' => request()->ip(),
                    'browser' => UserSystemInfo::get_browsers() . ', ' . UserSystemInfo::get_os(),
                    'time' => date('d M, Y h:i:s A'),
                ]);

                return response()->json($this->withSuccess('Google Authenticator Has Been Enabled.'));
            } else {
                return response()->json($this->withError('Wrong Verification Code.'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }

    }


    public function twoStepDisable(Request $request)
    {
        try {
            $user = auth()->user();

            $rules = [
                'code' => 'required',
            ];

            $validate = Validator::make($request->all(), $rules);

            if ($validate->fails()) {
                return response()->json($this->withError(collect($validate->errors())->collapse()));
            }

            $secret = $user->two_fa_code;
            $userCode = $request->code;

            if ($secret == $userCode) {
                auth()->user()->update([
                    'two_fa' => 0,
                    'two_fa_verify' => 1,
                ]);
                return response()->json($this->withSuccess('Two-step authentication disabled successfully.'));
            } else {
                return response()->json($this->withError('Incorrect Code. Please try again.'));
            }

        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }
}
