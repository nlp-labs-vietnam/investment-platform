<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Upload;

class LogoController extends Controller
{
    use Upload;

    public function logoSetting()
    {
        $basicControl = basicControl();
        return view('admin.control_panel.logo', compact('basicControl'));
    }

    public function logoUpdate(Request $request)
    {

        $request->validate([
            'logo' => 'sometimes|required|mimes:jpg,png,jpeg|max:2048',
            'favicon' => 'sometimes|required|mimes:jpg,png,jpeg|max:2048',
            'admin_logo' => 'sometimes|required|mimes:jpg,png,jpeg|max:2048',
        ]);

        $basicControl = basicControl();
        if ($request->hasFile('logo')) {
            try {
                $image = $this->fileUpload($request->logo, config('filelocation.logo.path'), null, null,'webp', 70, $basicControl->logo, $basicControl->logo_driver);
                if ($image) {
                    $basicControl->logo = $image['path'];
                    $basicControl->logo_driver = $image['driver'];
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'Logo could not be uploaded.');
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                $image = $this->fileUpload($request->favicon, config('filelocation.logo.path'), null, null, 'webp', 70, $basicControl->favicon,$basicControl->favicon_driver);
                if ($image) {
                    $basicControl->favicon = $image['path'];
                    $basicControl->favicon_driver = $image['driver'];
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'Favicon could not be uploaded.');
            }
        }

        if ($request->hasFile('admin_logo')) {
            try {
                $image = $this->fileUpload($request->admin_logo, config('filelocation.logo.path'), null, null, 'webp', 70,$basicControl->admin_logo, $basicControl->admin_logo_driver);
                if ($image) {
                    $basicControl->admin_logo = $image['path'];
                    $basicControl->admin_logo_driver = $image['driver'];
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'Admin Logo could not be uploaded.');
            }
        }

        if ($request->hasFile('admin_dark_mode_logo')) {
            try {
                $image = $this->fileUpload($request->admin_dark_mode_logo, config('filelocation.logo.path'), null, null, 'webp', 70,$basicControl->admin_dark_mode_logo,$basicControl->admin_dark_mode_logo_driver);
                if ($image) {
                    $basicControl->admin_dark_mode_logo = $image['path'];
                    $basicControl->admin_dark_mode_logo_driver = $image['driver'];
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'Admin Logo could not be uploaded.');
            }
        }

        $basicControl->save();

        return back()->with('success', 'Logo, favicon and breadcrumb has been updated.');

    }

}
