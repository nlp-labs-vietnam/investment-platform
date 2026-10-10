<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Facades\App\Services\BasicService;

class NotificationTemplateController extends Controller
{

    public function defaultTemplate(Request $request)
    {
        $basicControl = basicControl();
        if ($request->isMethod('get')) {
            return view('admin.notification_templates.email_template.default', ['basicControl' => $basicControl]);
        } elseif ($request->isMethod('post')) {

            $request->validate([
                'sender_email' => 'required|email:rfc,dns',
                'sender_email_name' => 'required|string|max:100',
                'email_description' => 'required|string',
            ]);

            try {
                $basicControl->update([
                    'sender_email' => $request->sender_email,
                    'sender_email_name' => $request->sender_email_name,
                    'email_description' => $request->email_description
                ]);

                $env = [
                    'MAIL_FROM_ADDRESS' => $request->sender_email,
                    'MAIL_FROM_NAME' => '"' . $request->sender_email_name . '"'
                ];

                BasicService::setEnv($env);
                return back()->with('success', 'Default email template updated successfully.');
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }
    }

    public function emailTemplates(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $emailTemplates = NotificationTemplate::select('id', 'language_id', 'name', 'template_key', 'status')->get()->unique('template_key');
        return view('admin.notification_templates.email_template.index', ['emailTemplates' => $emailTemplates]);
    }

    public function editEmailTemplate($id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $data['languages'] = Language::select('id', 'name')->get();
            $data["template"] = NotificationTemplate::where('id', $id)->firstOr(function () {
                throw new \Exception('Email template is not available.');
            });

            $templateKey = $data["template"]->template_key;
            $templates = NotificationTemplate::where('template_key', $templateKey)->get();
            return view('admin.notification_templates.email_template.edit', $data, compact('templates'));

        } catch (\Exception $exception) {
            return back()->with('alert', $exception->getMessage());
        }
    }

    public function updateEmailTemplate(Request $request, $id, $language_id): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name.*' => 'required|string|max:255',
            'subject.*' => 'required|string|max:200',
            'email_from.*' => 'required|string|max:100',
            'email_template.*' => 'required|string',
            'mail_status' => 'nullable|integer|in:0,1'
        ], [
            'name.*.required' => 'The name field is required.',
            'name.*.string' => 'The name must be a string.',
            'name.*.max' => 'The name may not be greater than 255.',
            'subject.*.required' => 'The subject field is required.',
            'email_from.*.required' => 'The email from field is required.',
            'email_template.*.required' => 'The message field is required.',
        ]);

        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator->getMessageBag());
        }

        try {
            $template = NotificationTemplate::where('id', $id)->firstOr(function () {
                throw new \Exception('Email template is not available.');
            });


            $language = Language::where('id', $request['language_id'])->firstOr(function () {
                throw new \Exception('language is not available.');
            });


            $status = [];
            if ($template->status) {
                foreach ($template->status as $key => $oldStatus) {
                    if ($key == 'mail') {
                        $status[$key] = $request->mail_status;
                    }
                }
            }


            $newStatus = array_replace($template->status, $status);
            $response = $template->updateOrCreate([
                'id' => $template->id,
                'language_id' => $language_id,
            ], [
                'name' => $request->name[$language->id] ?? null,
                'template_key' => $template->template_key,
                'subject' => $request->subject[$language->id] ?? null,
                'email_from' => $request->email_from[$language->id] ?? null,
                'short_keys' => $template->short_keys,
                'email' => strip_tags($request->email_template[$language->id]) ?? null,
                'status' => $newStatus,
                'lang_code' => $language->short_name ?? null
            ]);

            throw_if(!$response, 'Something went wrong, Please try again later.');
            return back()->with('success', 'Email template has been updated successfully.')->withInput($request->all());
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }


    public function smsTemplates(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $smsTemplates = NotificationTemplate::select('id', 'language_id', 'name', 'template_key', 'status')->get()->unique('template_key');
        return view('admin.notification_templates.sms_template.index', compact('smsTemplates'));
    }

    public function editSmsTemplate($id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $data['languages'] = Language::select('id', 'name')->get();
            $data['template'] = NotificationTemplate::where('id', $id)->firstOr(function () {
                throw new \Exception('SMS template is not available.');
            });

            $templateKey = $data['template']->template_key;
            $data['templates'] = NotificationTemplate::where('template_key', $templateKey)->get();
            return view('admin.notification_templates.sms_template.edit', $data);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function updateSmsTemplate(Request $request, $id, $language_id): \Illuminate\Http\RedirectResponse
    {

        $validator = Validator::make($request->all(), [
            'name.*' => 'required|string|max:255',
            'sms_template.*' => 'required|string',
            'sms_status' => 'nullable|integer|in:0,1'
        ], [
            'name.*.required' => 'The name field is required.',
            'name.*.string' => 'The name must be a string.',
            'name.*.max' => 'The name may not be greater than 255.',
            'sms_template.*.required' => 'The message field is required.',
        ]);

        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator->getMessageBag());
        }

        try {
            $template = NotificationTemplate::where('id', $id)->firstOr(function () {
                throw new \Exception('No template found.');
            });

            $language = Language::where('id', $request['language_id'])->firstOr(function () {
                throw new \Exception('language is not available.');
            });

            $status = [];
            if ($template->status) {
                foreach ($template->status as $key => $oldStatus) {
                    if ($key == 'sms') {
                        $status[$key] = $request->sms_status;
                    }
                }
            }

            $newStatus = array_replace($template->status, $status);
            $response = $template->updateOrCreate([
                'id' => $template->id,
                'language_id' => $language_id,
            ], [
                'name' => $request->name[$language->id] ?? null,
                'template_key' => $template->template_key,
                'short_keys' => $template->short_keys,
                'sms' => strip_tags($request->sms_template[$language->id]) ?? null,
                'status' => $newStatus,
                'lang_code' => $language->short_name
            ]);

            throw_if(!$response, 'Something went wrong, Please try again later.');
            return back()->with('success', 'SMS template has been updated successfully.')->withInput($request->all());
        } catch (\Exception $exception) {
            return back()->with('alert', $exception->getMessage())->withInput($request->all());
        }
    }


    public function inAppNotificationTemplates(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $templates = NotificationTemplate::select('id', 'language_id', 'name', 'template_key', 'status')->get()->unique('template_key');;
        return view('admin.notification_templates.in_app_notification_template.index', compact('templates'));
    }

    public function editInAppNotificationTemplate($id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $languages = Language::select('id', 'name')->get();
            $template = NotificationTemplate::where('id', $id)->firstOr(function () {
                throw new \Exception('In-app template is not available.');
            });

            $templateKey = $template->template_key;
            $templates = NotificationTemplate::where('template_key', $templateKey)->get();
            return view('admin.notification_templates.in_app_notification_template.edit', compact('template', 'languages', 'templates'));
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function updateInAppNotificationTemplate(Request $request, $id, $language_id): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name.*' => 'required|string|max:255',
            'in_app_notification_template.*' => 'required|string',
            'in_app_status' => 'nullable|integer|in:0,1'
        ], [
            'name.*.required' => 'The name field is required.',
            'name.*.string' => 'The name must be a string.',
            'name.*.max' => 'The name may not be greater than 255.',
            'in_app_notification_template.*.required' => 'The message field is required.',
        ]);


        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator->getMessageBag());
        }

        try {
            $template = NotificationTemplate::where('id', $id)->firstOr(function () {
                throw new \Exception('No template found.');
            });

            $status = [];
            if ($template->status) {
                foreach ($template->status as $key => $oldStatus) {
                    if ($key == 'in_app') {
                        $status[$key] = $request->in_app_status;
                    }
                }
            }

            $newStatus = array_replace($template->status, $status);
            $language = Language::where('id', $request['language_id'])->firstOr(function () {
                throw new \Exception('language is not available.');
            });
            $response = $template->updateOrCreate([
                'id' => $template->id,
                'language_id' => $language_id,
            ], [
                'name' => $request->name[$language->id] ?? null,
                'template_key' => $template->template_key,
                'short_keys' => $template->short_keys,
                'in_app' => strip_tags($request->in_app_notification_template[$language->id]) ?? null,
                'status' => $newStatus,
                'lang_code' => $language->short_name
            ]);

            throw_if(!$response, 'Something went wrong, Please try again later.');
            return back()->with('success', 'In App Notification template has been updated successfully.')->withInput($request->all());
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function pushNotificationTemplates(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $templates = NotificationTemplate::select('id', 'language_id', 'name', 'template_key', 'status')->get()->unique('template_key');;
        return view('admin.notification_templates.push_notification_template.index', compact('templates'));
    }

    public function editPushNotificationTemplate($id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $languages = Language::select('id', 'name')->get();
            $template = NotificationTemplate::where('id', $id)->firstOr(function () {
                throw new \Exception('Push template is not available.');
            });
            $templateKey = $template->template_key;
            $templates = NotificationTemplate::where('template_key', $templateKey)->get();
            return view('admin.notification_templates.push_notification_template.edit', compact('template', 'languages', 'templates'));
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function updatePushNotificationTemplate(Request $request, $id, $language_id): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name.*' => 'required|string|max:255',
            'push_notification_template.*' => 'required|string',
            'push_status' => 'nullable|integer|in:0,1'
        ], [
            'name.*.required' => 'The name field is required.',
            'name.*.string' => 'The name must be a string.',
            'name.*.max' => 'The name may not be greater than 255.',
            'push_notification_template.*.required' => 'The message field is required.',
        ]);

        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator->getMessageBag());
        }

        try {
            $template = NotificationTemplate::where('id', $id)->firstOr(function () {
                throw new \Exception('No template found.');
            });


            $status = [];
            if ($template->status) {
                foreach ($template->status as $key => $oldStatus) {
                    if ($key == 'push') {
                        $status[$key] = $request->push_status;
                    }
                }
            }

            $newStatus = array_replace($template->status, $status);
            $language = Language::where('id', $request['language_id'])->firstOr(function () {
                throw new \Exception('language is not available.');
            });
            $response = $template->updateOrCreate([
                'id' => $template->id,
                'language_id' => $language_id,
            ], [
                'name' => $request->name[$language->id] ?? null,
                'template_key' => $template->template_key,
                'short_keys' => $template->short_keys,
                'push' => strip_tags($request->push_notification_template[$language->id]) ?? null,
                'status' => $newStatus,
                'lang_code' => $language->short_name

            ]);
            throw_if(!$response, 'Something went wrong, Please try again later.');
            return back()->with('success', 'Push Notification template has been updated successfully.')->withInput($request->all());
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }
}
