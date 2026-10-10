<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FA\Google2FA;

class VerificationController extends Controller
{
    use ApiResponse,Notify;

    public function twoFAverify(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'code' => 'required',
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }

        try {
            $user = Auth::user();
            $secret = $user->two_fa_code;

            $google2fa = new Google2FA();
            $valid = $google2fa->verifyKey($secret,$request->code);

            if ($valid) {
                $user->two_fa_verify = 1;
                $user->save();
                return response()->json($this->withSuccess('Verified Successfully.'));
            }
            return response()->json($this->withError('Wrong Verification Code.'));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function mailVerify(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'code' => 'required',
            ],
            [
                'code.required' => 'Email verification code is required',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }
        try {
            $user = auth()->user();
            if ($this->checkValidCode($user, $request->code)) {
                $user->email_verification = 1;
                $user->verify_code = null;
                $user->sent_at = null;
                $user->save();
                return response()->json($this->withSuccess('Verified Successfully.'));
            }
            return response()->json($this->withError('Verification code didn\'t match!'));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function smsVerify(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'code' => 'required',
            ],
            [
                'code.required' => 'Sms verification code is required',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }
        try {
            $user = Auth::user();
            if ($this->checkValidCode($user, $request->code)) {
                $user->sms_verification = 1;
                $user->verify_code = null;
                $user->sent_at = null;
                $user->save();

                return response()->json($this->withSuccess('Verified Successfully.'));
            }
            return response()->json($this->withError('Verification code didn\'t match!'));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function checkValidCode($user, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$user->sent_at) return false;
        if (Carbon::parse($user->sent_at)->addMinutes($add_min) < Carbon::now()) return false;
        if ($user->verify_code !== $code) return false;
        return true;
    }

    public function resendCode()
    {
        $type = request()->type;
        $user = auth()->user();
        if ($this->checkValidCode($user, $user->verify_code, 2)) {
            $target_time = Carbon::parse($user->sent_at)->addMinutes(2)->timestamp;
            $delay = $target_time - time();
            return response()->json($this->withError('Please Try after ' . gmdate("i:s", $delay) . ' minutes'));
        }
        if (!$this->checkValidCode($user, $user->verify_code)) {
            $user->verify_code = code(6);
            $user->sent_at = Carbon::now();
            $user->save();
        } else {
            $user->sent_at = Carbon::now();
            $user->save();
        }

        if ($type === 'mail') {
            $this->verifyToMail($user, 'VERIFICATION_CODE', [
                'code' => $user->verify_code
            ]);
            return response()->json($this->withSuccess('Email verification code has been sent'));
        } elseif ($type === 'sms') {
            $this->verifyToSms($user, 'VERIFICATION_CODE', [
                'code' => $user->verify_code
            ]);
            return response()->json($this->withSuccess('SMS verification code has been sent'));
        } else {
            return response()->json($this->withError('Sending Failed'));
        }
    }
}
