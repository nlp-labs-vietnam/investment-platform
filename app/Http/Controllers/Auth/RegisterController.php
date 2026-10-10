<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UserSystemInfo;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Rules\PhoneLength;
use Facades\App\Services\Google\GoogleRecaptchaService;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    protected $redirectTo = '/user/dashboard';

    protected $maxAttempts = 3; // Change this to 4 if you want 4 tries
    protected $decayMinutes = 5; // Change this according to your


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->theme = template();
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $data['pageSeo'] = Page::where('name', 'about')->first();
        $data['pageSeo']['page_title'] = 'Register';
        $data['pageSeo']['breadcrumb_image'] = getFile($data['pageSeo']['breadcrumb_image_driver'], $data['pageSeo']['breadcrumb_image']);
        $basic = basicControl();
        if ($basic->registration == 0) {
            return redirect('/')->with('warning', 'Registration Has Been Disabled.');
        }
        return view(template() . 'auth.register', $data);
    }

    public function sponsor($sponsor = null)
    {
        $data['pageSeo'] = Page::where('name', 'about')->first();
        $data['pageSeo']['page_title'] = 'Register';
        $data['pageSeo']['breadcrumb_image'] = getFile($data['pageSeo']['breadcrumb_image_driver'], $data['pageSeo']['breadcrumb_image']);

        if (basicControl()->registration == 0) {
            return redirect('/')->with('warning', 'Registration Has Been Disabled.');
        }

        session()->put('sponsor', $sponsor);
        $info = json_decode(json_encode(getIpInfo()), true);
        $country_code  = null;
        if(!empty($info['code'])){
            $country_code = @$info['code'][0];
        }

        $countries = config('country');

        return view(template().'auth.register', $data, compact('sponsor', 'countries','country_code'));

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $basicControl = basicControl();
        $phoneLength = 11;

        foreach (config('country') as $country) {
            if ($country['phone_code'] == $data['phone_code']) {
                $phoneLength = $country['phoneLength'];
                break;
            }
        }

        if ($basicControl->strong_password == 0) {
            $rules['password'] = ['required', 'min:6', 'confirmed'];
        } else {
            $rules['password'] = ["required", 'confirmed',
                Password::min(6)->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()];
        }

        if (basicControl()->reCaptcha_status_registration) {
            $rules['g-recaptcha-response'] = ['sometimes', 'required'];
        }

        $rules['firstname'] = ['required', 'string', 'max:91'];
        $rules['lastname'] = ['required', 'string', 'max:91'];
        $rules['username'] = ['required', 'alpha_dash', 'min:5', 'unique:users,username'];
        $rules['email'] = ['required', 'string', 'email', 'max:255',  'unique:users,email'];
        $rules['phone'] = ['required', 'string', 'unique:users,phone'];
        $rules['phone_code'] = ['required', 'string'];
        return Validator::make($data, $rules, [
            'first_name.required' => 'First Name Field is required',
            'last_name.required' => 'Last Name Field is required',
            'g-recaptcha-response.required' => 'The reCAPTCHA field is required.',
        ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $basic = basicControl();
        $sponsorId = null;
        if (isset($data['sponsor'])) {
            $sponsor = User::where('username', $data['sponsor'])->where('email_verification', 1)->where('sms_verification', 1)->where('status', 1)->first();
            if ($sponsor) {
                $sponsorId = $sponsor->id;
            }
        }
        $softDeletedUser = User::withTrashed()->where('email', $data['email'])->first();
        if ($softDeletedUser) {
            $softDeletedUser->forceDelete();
        }

        return User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_code' => $data['phone_code'],
            'phone' => $data['phone'],
            'email_verification' => ($basic->email_verification) ? 0 : 1,
            'sms_verification' => ($basic->sms_verification) ? 0 : 1,
            'referral_id' => $sponsorId,
            'is_bonus_amount' => $basic->is_first_deposit_bonus == 1 ? 0 : 1,
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        if ($request->ajax()) {
            return route('user.dashboard');
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        $user->last_login = Carbon::now();
        $user->last_seen = Carbon::now();
        $user->two_fa_verify = ($user->two_fa == 1) ? 0 : 1;
        $user->save();

        $info = @json_decode(json_encode(getIpInfo()), true);
        $ul['user_id'] = $user->id;

        $ul['longitude'] = (!empty(@$info['long'])) ? implode(',', $info['long']) : null;
        $ul['latitude'] = (!empty(@$info['lat'])) ? implode(',', $info['lat']) : null;
        $ul['country_code'] = (!empty(@$info['code'])) ? implode(',', $info['code']) : null;
        $ul['location'] = (!empty(@$info['city'])) ? implode(',', $info['city']) . (" - " . @implode(',', @$info['area']) . "- ") . @implode(',', $info['country']) . (" - " . @implode(',', $info['code']) . " ") : null;
        $ul['country'] = (!empty(@$info['country'])) ? @implode(',', @$info['country']) : null;

        $ul['ip_address'] = UserSystemInfo::get_ip();
        $ul['browser'] = UserSystemInfo::get_browsers();
        $ul['os'] = UserSystemInfo::get_os();
        $ul['get_device'] = UserSystemInfo::get_device();

        UserLogin::create($ul);

    }

    protected function guard()
    {
        return Auth::guard();
    }

}
