<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\UserContact;
use App\Models\ContactMessage;
use App\Models\User;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SendMailController extends Controller
{
    use Notify, Upload;

    public function sendMessageToPropertyInvestor(Request $request){
        $rules = [
            'name' => 'required|max:50',
            'message' => 'required',
        ];
        $message = [
            'name.required' => __('Please write your name'),
            'message.required' => __('Please Write your message'),
        ];

        $validate = Validator::make($request->all(), $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        if ($request->investor_id == Auth::id()){
            return back()->with('error', 'you can not message your profile');
        }

        $investor = User::findOrFail($request->investor_id);

        $senderName = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $reciverName = $investor->firstname . ' ' . $investor->lastname;

        $contactMessage = new ContactMessage();
        $contactMessage->investor_id = $request->investor_id;
        $contactMessage->client_id = Auth::id();
        $contactMessage->message = $request->message;
        $contactMessage->save();

        $userMsg = [
            'from' => $senderName??null,
            'site' => '['.config('basic.site_title').']',
        ];

        $adminMsg = [
            'from' => $senderName??null,
            'to' => $reciverName,
        ];

        $userAction = [
            "link" => "#",
            "icon" => "fa fa-sms text-white"
        ];

        $adminAction = [
            "link" => '#',
            "icon" => "fa fa-sms text-white"
        ];

        $this->userPushNotification($investor, 'NOTIFY_INVESTOR_CLIENT_SEND_CONTACT_MESSAGE_TO_INVESTOR', $userMsg, $userAction);
        $this->adminPushNotification('NOTIFY_ADMIN_CLIENT_SEND_CONTACT_MESSAGE_TO_INVESTOR', $adminMsg, $adminAction);

        $details = [
            'sub'          => '['.config('basic.site_title').']'.' Contact Message sent from '.$senderName,
            'replyToEmail' => Auth::user()->email,
            'replyToName'  => $senderName,
            'message'      => $request->message,
        ];

        Mail::to($investor->email)->send(new UserContact($details));
        return back()->with('success', __('Message has been sent'));
    }
}
