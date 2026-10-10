<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Hash;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdminProfileSettingController extends Controller
{
    use Upload;

    public function profile()
    {
        $basicControl = basicControl();
        $admin = Auth::guard('admin')->user();
        $templates = NotificationTemplate::where('notify_for', 1)->get()->unique('template_key');
        return view('admin.profile', compact('admin', 'basicControl', 'templates'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'username' => 'required|string|min:3',
            'email' => 'required|email|min:3|email:rfc,dns',
            'phone' => 'required|string|min:3',
            'addressLine' => 'required|string|min:3',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif'
        ]);

        try {
            $admin = Auth::guard('admin')->user();

            if ($request->file('image')) {
                $image = $this->fileUpload($request->image, config('filelocation.adminProfileImage.path'), null,null, 'webp', 70, $admin->image, $admin->image_driver);
                if ($image) {
                    $adminImage = $image['path'];
                    $adminImageDriver = $image['driver'] ?? 'local';
                }
            }

            $response = $admin->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->addressLine,
                'image' => $adminImage ?? $admin->image,
                'image_driver' => $adminImageDriver ?? $admin->image_driver,
            ]);

            if (!$response) {
                throw new Exception("Something went wrong");
            }

            return back()->with("success", "Admin Profile Updated Successfully.");

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function passwordUpdate(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', "Password didn't match");
        }
        $admin->update([
            'password' => bcrypt($request->password)
        ]);
        return back()->with('success', 'Password has been Changed');
    }

    public function notificationPermission(Request $request)
    {
        $templates = $request->input('templates', []);
        foreach ($templates as $templateId => $templateData) {
            $template = NotificationTemplate::findOrFail($templateId);
            $template->update([
                'status' => $templateData
            ]);
        }
        return back()->with('success', 'Permissions updated successfully.');
    }


}
