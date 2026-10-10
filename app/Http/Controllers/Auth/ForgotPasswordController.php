<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Mail\SendResetPasswordMail;
use App\Models\Page;
use App\Models\User;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    use Notify;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        $data['pageSeo'] = Page::where('name', 'about')->first();
        $data['pageSeo']['page_title'] = 'Reset Password';
        $data['pageSeo']['breadcrumb_image'] = getFile($data['pageSeo']['breadcrumb_image_driver'], $data['pageSeo']['breadcrumb_image']);
        return view(template().'auth.passwords.email', $data);
    }

    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        try {
            $userEmail = $request->email;
            $user = User::where('email', $userEmail)->first();

            $token = Str::random(64);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now()
                ]
            );

            $resetUrl = url('password/reset', $token) . '?email=' . $userEmail;
            $message = 'Your Password Recovery Link: <a href="' . $resetUrl . '" target="_blank">Click To Reset Password</a>';

//            $email_from = basicControl()->sender_email;
//            Mail::to($userEmail)->send(new SendMail($email_from, "Password Recovery", $message));

            $params = [
                'message' => $message
            ];
            $this->mail($user, 'PASSWORD_RESET', $params);

            return back()->with('success', 'We have e-mailed your password reset link!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
