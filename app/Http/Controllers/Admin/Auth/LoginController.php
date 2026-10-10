<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Facades\App\Services\Google\GoogleRecaptchaService;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $maxAttempts = 3; // Change this to 4 if you want 4 tries
    protected $decayMinutes = 5; // Change this according to your
    protected $redirectTo = 'admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        $data['basicControl'] = basicControl();
        return view('admin.auth.login', $data);
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function login(Request $request)
    {
        $basicControl = basicControl();
        $input = $request->all();

        $rules[$this->username()] = 'required';
        $rules ['password'] = 'required';
        if ($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_admin_login == 1) {
            $rules['captcha'] = ['required',
                Rule::when((!empty($request->captcha) && strcasecmp(session()->get('captcha'), $_POST['captcha']) != 0), ['confirmed']),
            ];
        }

        if ($basicControl->google_recaptcha_admin_login == 1 && $basicControl->google_recaptcha == 1) {
            GoogleRecaptchaService::responseRecaptcha($request['g-recaptcha-response']);
            $rules['g-recaptcha-response'] = 'sometimes|required';
        }

        $message['captcha.confirmed'] = "The captcha does not match.";
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $remember_me = $request->has('remember_me') ? true : false;
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (auth()->guard('admin')->attempt(array($fieldType => $input['username'], 'password' => $input['password']), $remember_me)) {
            return $this->sendLoginResponse($request);
        } else {
            return redirect()->route('admin.login')
                ->with('error', 'Email-Address And Password Are Wrong.');
        }
    }

    public function username()
    {
        $login = request()->input('username');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);
        return $field;
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function logout(Request $request)
    {
        $this->guard('guard')->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect()->route('admin.login');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard('admin')->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }


    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->status == 0) {
            $this->guard('guard')->logout();
            return redirect()->route('admin.login')->with('error', 'You are banned from this application. Please contact with system Administrator.');
        }
        $user->last_login = Carbon::now();
        $user->save();

        if (config('demo.IS_DEMO')) {
            $checkUser = \App\Models\User::find(3);
            $checkUser->username = 'demouser';
            $checkUser->password = bcrypt('demouser');
            $checkUser->two_fa = 0;
            $checkUser->two_fa_verify = 1;
            $checkUser->status = 1;
            $checkUser->save();
        }
    }
}
