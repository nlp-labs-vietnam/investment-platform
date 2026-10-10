<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\Language;
use App\Models\UserKyc;
use App\Models\UserSocial;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    use Upload;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function profile()
    {
        $data['languages'] = Language::all();
        $data['user'] = $this->user;
        $data['social_links'] = UserSocial::where('user_id', $data['user']->id)->get();

        // Eager load the 'userKyc' relationship with a condition
        $data['kyc'] = Kyc::where('status', 1)
            ->with(['userKyc' => function ($query) {
                $query->where('user_id', auth()->user()->id);
            }])
            ->get();

        return view(template() . 'user.profile.my_profile', $data);
    }

    public function updateInformation(Request $request)
    {
        $user = $this->user;
        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });

        $allowedExtensions = array('jpg', 'png', 'jpeg');
        $image = $request->image;
        if (isset($image)){
            $this->validate($request, [
                'image' => [
                    'required',
                    'max:4096',
                    function ($fail) use ($image, $allowedExtensions) {
                        $ext = strtolower($image->getClientOriginalExtension());
                        if (($image->getSize() / 1000000) > 2) {
                            throw ValidationException::withMessages(['image' => "Images MAX  2MB ALLOW!"]);
                        }
                        if (!in_array($ext, $allowedExtensions)) {
                            throw ValidationException::withMessages(['image' => "Only png, jpg, jpeg images are allowed"]);
                        }
                    }
                ]
            ]);

            if ($request->hasFile('image')) {
                $oldFile = $user->image ?? null;
                $oldDriver = $user->image_driver ?? 'local';
                $image = $this->fileUpload($request->image, config('filelocation.userProfile.path'), null, null, 'webp', null, $oldFile, $oldDriver);
                if ($image) {
                    $user->image = $image['path'];
                    $user->image_driver = $image['driver'];
                }
            }
            $user->save();
        }



        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => "sometimes|required|alpha_dash|min:5|unique:users,username," . $user->id,
            'language_id' => Rule::in($languages),
        ];
        $message = [
            'firstname.required' => 'First Name field is required',
            'lastname.required' => 'Last Name field is required',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            $validator->errors()->add('profile', '1');
            return back()->withErrors($validator)->withInput();
        }
        $user->language_id = $request['language_id'];
        $user->firstname = $request['firstname'];
        $user->lastname = $request['lastname'];
        $user->username = $request['username'];
        $user->address = $request['address'];
        $user->bio = $request['bio'];
        $user->phone = $request['phone'];
        $user->save();

        if ($request->social_icon) {
            UserSocial::where('user_id', $user->id)->delete();
            foreach ($request->social_icon as $key => $value) {
                UserSocial::create([
                    'user_id' => $user->id,
                    'social_icon' => $request->social_icon[$key],
                    'social_url' => $request->social_url[$key],
                    'updated_at' => \Illuminate\Support\Carbon::now(),
                ]);
            }
        }

        return back()->with('success', 'Updated Successfully.');
    }

    public function profileUpdateImage(Request $request)
    {
        $allowedExtensions = array('jpg', 'png', 'jpeg');
        $image = $request->image;
        $this->validate($request, [
            'profile_image' => [
                'required',
                'max:4096',
                function ($fail) use ($image, $allowedExtensions) {
                    $ext = strtolower($image->getClientOriginalExtension());
                    if (($image->getSize() / 1000000) > 2) {
                        return $fail("Images MAX  2MB ALLOW!");
                    }
                    if (!in_array($ext, $allowedExtensions)) {
                        return $fail("Only png, jpg, jpeg images are allowed");
                    }
                }
            ]
        ]);

        $user = Auth::user();
        if ($request->hasFile('image')) {
            $image = $this->fileUpload($request->image, config('filelocation.userProfile.path'), config('filesystems.default'), $user->image_driver, $user->image, null, 'webp', 80);
            if ($image) {
                $profileImage = $image['path'];
                $ImageDriver = $image['driver'];
            }
        }
        $user->image = $profileImage ?? $user->image;
        $user->image_driver = $ImageDriver ?? $user->image_driver;
        $user->save();
        return back()->with('success', 'Updated Successfully.');
    }


    public function profileImageUpdate(Request $request)
    {
        $allowedExtensions = array('jpg', 'png', 'jpeg');
        $image = $request->profile_image;

        $this->validate($request, [
            'profile_image' => [
                'required',
                'max:4096',
                function ($fail) use ($image, $allowedExtensions) {
                    $ext = strtolower($image->getClientOriginalExtension());
                    if (($image->getSize() / 1000000) > 2) {
                        return $fail("Images MAX  2MB ALLOW!");
                    }
                    if (!in_array($ext, $allowedExtensions)) {
                        return $fail("Only png, jpg, jpeg images are allowed");
                    }
                }
            ]
        ]);

        $user = $this->user;

        if ($request->hasFile('profile_image')) {
            $oldFile = $user->image ?? null;
            $oldDriver = $user->image_driver ?? 'local';
            $image = $this->fileUpload($request->profile_image, config('filelocation.userProfile.path'), null, null, 'webp', null, $oldFile, $oldDriver);
            if ($image) {
                $user->image = $image['path'];
                $user->image_driver = $image['driver'];
            }
        }

        $user->save();

        $src = getFile($user->image_driver, $user->image);

        return response()->json(['src' => $src]);
    }


    public function profileUpdate(Request $request)
    {
        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });
        throw_if(!$languages, 'Language not found.');

        $req = $request->except('_method', '_token');
        $user = Auth::user();
        $rules = [
            'first_name' => 'required|string|min:1|max:100',
            'last_name' => 'required|string|min:1|max:100',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required|min:1|max:50',
            'username' => "sometimes|required|alpha_dash|min:5|unique:users,username," . $user->id,
            'address' => 'required|string|min:2|max:500',
            'language_id' => Rule::in($languages),
        ];
        $message = [
            'firstname.required' => 'First name field is required',
            'lastname.required' => 'Last name field is required',
        ];

        $validator = Validator::make($req, $rules, $message);
        if ($validator->fails()) {
            $validator->errors()->add('profile', '1');
            return back()->withErrors($validator)->withInput();
        }
        try {
            $response = $user->update([
                'language_id' => $req['language_id'],
                'firstname' => $req['first_name'],
                'lastname' => $req['last_name'],
                'email' => $req['email'],
                'phone' => $req['phone'],
                'username' => $req['username'],
                'address_one' => $req['address'],
            ]);

            throw_if(!$response, 'Something went wrong, While updating profile data');
            return back()->with('success', 'Profile updated Successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function passwordSetting()
    {
        $data['kycs'] = Kyc::orderBy('id', 'asc')->where('status', 1)->get();
        return view(template() . 'user.profile.passwordSetting', $data);
    }
    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => "required",
            'password' => "required|min:5|confirmed",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = Auth::user();
        try {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
                return back()->with('success', 'Password Changes successfully.');
            } else {
                throw new \Exception('Current password did not match');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
