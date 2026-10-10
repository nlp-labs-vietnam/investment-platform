<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\UserAllRecordDeleteJob;
use App\Models\Kyc;
use App\Models\Language;
use App\Models\User;
use App\Models\UserKyc;
use App\Models\UserSocial;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use ApiResponse, Upload;

    public function profile()
    {
        try {
            $user = User::with('get_social_links_user')->where('id', auth()->id())->first();

            if (!$user) {
                return response()->json($this->withError('User not found'));
            }

            $languages = Language::where('status', 1)->select('id', 'name', 'short_name', 'flag', 'flag_driver', 'rtl', 'status', 'default_status')->get();

            $formattedUser = [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'username' => $user->username,
                'email' => $user->email,
                'phone_code' => $user->phone_code,
                'phone' => $user->phone,
                'country_code' => $user->country_code,
                'language_id' => $user->language_id,
                'image' => getFile($user->image_driver, $user->image),
                'address' => $user->address,
                'bio' => $user->bio,
                'status' => $user->status,
                'email_verification' => $user->email_verification,
                'sms_verification' => $user->sms_verification,
                'two_fa_verify' => $user->two_fa_verify,
                'user_socials' => $user->get_social_links_user->map(function ($social) {
                    return [
                        'id' => $social->id,
                        'user_id' => $social->user_id,
                        'social_icon' => $social->social_icon,
                        'social_url' => $social->social_url,
                    ];
                }),
            ];

            $data['user'] = $formattedUser;

            $data['languages'] = $languages->map(function ($item) {
                $item->flag = getFile($item->flag_driver, $item->flag);
                return $item;
            });

            $kycs = Kyc::where('status', 1)
                ->with(['userKyc' => function ($query) {
                    $query->where('user_id', auth()->user()->id);
                }])
                ->get();

            $data['kyc'] = $kycs->map(function ($kyc) {
                return [
                    'id' => $kyc->id,
                    'name' => $kyc->name,
                    'slug' => $kyc->slug,
                    'status' => $kyc->status,
                ];
            });

            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function updateProfile(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = \auth()->user();
            $languages = Language::all()->map(function ($item) {
                return $item->id;
            });

            $rules = [
                'firstname' => 'required|string|min:1',
                'lastname' => 'required|string|min:1',
                'username' => "sometimes|required|alpha_dash|min:5|unique:users,username," . $user->id,
                'email' => 'required',
                'phone' => 'nullable',
                'address' => 'nullable',
                'bio' => 'nullable',
                'image' => 'image|mimes:jpg,png,jpeg|max:5000',
                'language_id' => Rule::in($languages),
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($this->withError(collect($validator->errors())->collapse()));
            }

            if ($request->hasFile('image')) {
                $image = $this->fileUpload($request->image, config('filelocation.userProfile.path'), null, null, 'webp', 80, $user->image, $user->image_driver);
                if ($image) {
                    $profileImage = $image['path'];
                    $ImageDriver = $image['driver'];
                }
            }

            $user->image = $profileImage ?? $user->image;
            $user->image_driver = $ImageDriver ?? $user->image_driver;

            $user->language_id = $request['language_id'];
            $user->firstname = $request['firstname'];
            $user->lastname = $request['lastname'];
            $user->username = $request['username'];
            $user->email = $request['email'];
            $user->address = $request['address'] ?? $user->bio;
            $user->bio = $request['bio'] ?? $user->bio;
            $user->phone = $request['phone'] ?? $user->phone;
            $user->phone_code = $request['phone_code'] ?? $user->phone_code;
            $user->country = $request['country'] ?? $user->country;
            $user->country_code = $request['country_code'] ?? $user->country_code;

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
            DB::commit();
            return response()->json($this->withSuccess('Updated Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $rules = [
                'current_password' => "required",
                'password' => "required|min:5|confirmed",
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($this->withError(collect($validator->errors())->collapse()));
            }
            $user = Auth::user();
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
                return response()->json($this->withSuccess('Password Updated Successfully'));
            } else {
                return response()->json($this->withError('Current password did not match'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function kycs()
    {
        try {
            $checkUserKyc = UserKyc::with('kyc')->where('user_id', auth()->id())->get();

            foreach ($checkUserKyc as $userKyc) {
                if ($userKyc->status === 0) {
                    $data['is_admin_action'] = false;
                    $data['message'] = 'Your KYC verification is pending';
                }
                if ($userKyc->status === 1) {
                    $data['is_admin_action'] = true;
                    $data['message'] = 'Your KYC verification is verified';
                }
                if ($userKyc->status === 2) {
                    $data['is_admin_action'] = true;
                    $data['message'] = 'Your KYC verification is rejected';
                }
            }

            $data['kyc'] = Kyc::where('status', 1)->get();
            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function kycVerificationSubmit(Request $request)
    {
        try {
            $checkKyc = UserKyc::where('user_id', auth()->id())->get();

            if ($checkKyc->contains('status', 0) || $checkKyc->contains('status', 1)) {
                return response()->json($this->withError('Your kyc already submitted!'));
            }

            $checkKyc->where('status', 2)->each->delete();

            $kyc = Kyc::where('id', $request->type)->where('status', 1)->first();

            if (!$kyc) {
                return response()->json($this->withError('Kyc not found.'));
            }

            $params = $kyc->input_form;
            $reqData = $request->except('_token', '_method');
            $rules = [];
            if ($params !== null) {
                foreach ($params as $key => $cus) {
                    $rules[$key] = [$cus->validation == 'required' ? $cus->validation : 'nullable'];
                    if ($cus->type === 'file') {
                        $rules[$key][] = 'image';
                        $rules[$key][] = 'mimes:jpeg,jpg,png';
                        $rules[$key][] = 'max:2048';
                    } elseif ($cus->type === 'text') {
                        $rules[$key][] = 'max:191';
                    } elseif ($cus->type === 'number') {
                        $rules[$key][] = 'numeric';
                    } elseif ($cus->type === 'textarea') {
                        $rules[$key][] = 'min:3';
                        $rules[$key][] = 'max:300';
                    }
                }
            }

            $validator = Validator::make($reqData, $rules);
            if ($validator->fails()) {
                return response()->json($this->withError(collect($validator->errors())->collapse()));
            }

            $reqField = [];
            foreach ($request->except('_token', '_method', 'type') as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k == $inKey) {
                        if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                            try {
                                $file = $this->fileUpload($request[$inKey], config('filelocation.kyc.path'));
                                $reqField[$inKey] = [
                                    'field_name' => $inVal->field_name,
                                    'field_label' => $inVal->field_label,
                                    'field_value' => $file['path'],
                                    'field_driver' => $file['driver'],
                                    'validation' => $inVal->validation,
                                    'type' => $inVal->type,
                                ];
                            } catch (\Exception $exp) {
                                return response()->json($this->withError("Could not upload your {$inKey}"));
                            }
                        } else {
                            $reqField[$inKey] = [
                                'field_name' => $inVal->field_name,
                                'field_label' => $inVal->field_label,
                                'validation' => $inVal->validation,
                                'field_value' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            UserKyc::create([
                'user_id' => auth()->id(),
                'kyc_id' => $kyc->id,
                'kyc_type' => $kyc->name,
                'kyc_info' => $reqField
            ]);

            return response()->json($this->withSuccess("KYC Sent Successfully"));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function deleteAccount()
    {
        if(config('demo.IS_DEMO')){
            return response()->json($this->withError('This is DEMO version. You can just explore all the features but can\'t take any action.'));
        }
        try {
            $user = auth()->user();
            if ($user) {
                UserAllRecordDeleteJob::dispatch($user);
                $user->delete();
                return response()->json($this->withSuccess('Your account has been deleted successfully.'));
            } else {
                return response()->json($this->withError('Invalid user'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }


}
