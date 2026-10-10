<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FirebaseConfigController extends Controller
{
    public function firebaseConfig()
    {
        $data['basicControl'] = basicControl();
        $data['firebaseNotify'] = config('firebase');
        return view('admin.control_panel.firebase_config', $data);
    }

    public function firebaseConfigUpdate(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'vapid_key' => 'required|string',
            'auth_domain' => 'required|string',
            'project_id' => 'required|string',
            'storage_bucket' => 'required|string',
            'messaging_sender_id' => 'required|string',
            'app_id' => 'required|string',
            'measurement_id' => 'required|string',
            'push_notification' => 'nullable|integer|in:0,1',
            'user_foreground' => 'nullable|integer|in:0,1',
            'user_background' => 'nullable|integer|in:0,1',
            'admin_foreground' => 'nullable|integer|in:0,1',
            'admin_background' => 'nullable|integer|in:0,1',
        ]);

        try {
            $env = [
                'FIREBASE_API_KEY' => $request->api_key,
                'FIREBASE_VAPID_KEY' => $request->vapid_key,
                'FIREBASE_AUTH_DOMAIN' => $request->auth_domain,
                'FIREBASE_PROJECT_ID' => $request->project_id,
                'FIREBASE_STORAGE_BUCKET' => $request->storage_bucket,
                'FIREBASE_MESSAGING_SENDER_ID' => $request->messaging_sender_id,
                'FIREBASE_API_ID' => $request->app_id,
                'FIREBASE_MEASUREMENT_ID' => $request->measurement_id,
                'USER_FOREGROUND' => $request->user_foreground,
                'USER_BACKGROUND' => $request->user_background,
                'ADMIN_FOREGROUND' => $request->admin_foreground,
                'ADMIN_BACKGROUND' => $request->admin_background,
            ];

            BasicService::setEnv($env);

            $basicControl = basicControl();
            $basicControl->update([
                'push_notification' => $request->push_notification
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Firebase Configure Successfully.');
    }

    public function firebaseConfigFileUpload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimetypes:application/json|mimes:json',
        ]);

        $file = $request->file('file');
        $file->move(base_path(), getFirebaseFileName());

        return back()->with('success', 'Uploaded Successfully');
    }

    public function firebaseConfigFileDownload()
    {
        $filePath = base_path(getFirebaseFileName());
        if (File::exists($filePath)) {
            return response()->download($filePath, getFirebaseFileName());
        } else {
            return response()->json(['error' => 'File not found!'], 404);
        }
    }
}
