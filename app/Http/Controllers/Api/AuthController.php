<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        $basic = basicControl();
        $rules = [
            'firstname' => 'required|string|max:91',
            'lastname' => 'required|string|max:91',
            'username' => 'required|alpha_dash|min:5|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'phone_code' => 'required|string',
            'password' => $basic->strong_password == 0 ?
                ['required', 'confirmed', 'min:6'] :
                ['required', 'confirmed', Password::min(6)->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($this->withError(collect($validator->errors())->collapse()));
        }

        try {
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'phone_code' => $request->phone_code,
                'country_code' => $request->country_code,
                'password' => Hash::make($request->password)
            ]);

            $data['message'] = 'Registration successful';
            $data['bearer_token'] = $user->createToken($request->email)->plainTextToken;
            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($this->withError(collect($validator->errors())->collapse()));
        }

        $user = User::where('username', $request->username)
            ->orWhere('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json($this->withError('The provided credentials are incorrect.'));
        }

        $data['message'] = 'Your are logged in successfully!';
        $data['bearer_token'] = $user->createToken($user->email)->plainTextToken;
        return response()->json($this->withSuccess($data));
    }


    public function username()
    {
        $login = request()->input('username');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);
        return $field;
    }

    public function logout(Request $request)
    {
        // Revoke the current user's token
        $request->user()->currentAccessToken()->delete();
        return response()->json($this->withSuccess('User logged out successfully'));
    }


    public function emailPasswordReset(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email',
            ];

            $validate = Validator::make($request->all(), $rules);

            if ($validate->fails()) {
                return response()->json($this->withError(collect($validate->errors())->collapse()));
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json($this->withError('Email does not exit!'));
            }

            $code = rand(100000, 999999);
            $user->verify_code = $code;
            $user->save();

            $basic = basicControl();
            $email_from = $basic->sender_email;
            $subject = "Password Reset";
            $message = 'Your Password Reset Code is ' . $code;
            @Mail::to($request->email)->send(new SendMail($email_from, $subject, $message));

            $data['email'] = $request->email;
            $data['message'] = 'Reset Code has been send';
            return response()->json($this->withSuccess($data));

        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function codePasswordReset(Request $request)
    {
        try {

            $rules = [
                'code' => 'required',
                'email' => 'required|email',
            ];

            $validate = Validator::make($request->all(), $rules);

            if ($validate->fails()) {
                return response()->json($this->withError(collect($validate->errors())->collapse()));
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json($this->withError('Email does not exit'));
            }

            if ($user->verify_code == $request->code && $user->updated_at > Carbon::now()->subMinutes(5)) {
                $token = Str::random(60);
                $user->verify_code = null;
                $user->reset_code_token = $token;
                $user->save();
                $resetCodeResponse = [
                    'message' => 'code matched',
                    'token' => $token,
                ];
                return response()->json($this->withSuccess($resetCodeResponse));
            }
            return response()->json($this->withError('Invalid Code'));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function passwordReset(Request $request)
    {
        try {
            $basic = basicControl();
            $rules = [
                'email' => 'required|email|exists:users,email',
                'token' => 'required',
                'password' => $basic->strong_password == 0 ?
                    ['required', 'confirmed', 'min:6'] :
                    ['required', 'confirmed', Password::min(6)->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()],
                'password_confirmation' => 'required|min:6',
            ];


            $validate = Validator::make($request->all(), $rules);

            if ($validate->fails()) {
                return response()->json($this->withError(collect($validate->errors())->collapse()));
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json($this->withError('Your email does not match!'));
            }

            if ($user->reset_code_token != null && $user->reset_code_token == $request->token){
                $user->password = Hash::make($request->password);
                $user->reset_code_token = null;
                $user->save();

                return response()->json($this->withSuccess('Password Reset Successfully'));
            } elseif ($user->reset_code_token == null){
                return response()->json($this->withError('Reset token expired. Please request a new one.'));
            }
            else{
                return response()->json($this->withError('Password reset token does not match!'));
            }

        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }


}
