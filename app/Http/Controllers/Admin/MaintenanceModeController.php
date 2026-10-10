<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceMode;
use Illuminate\Http\Request;
use App\Traits\Upload;


class MaintenanceModeController extends Controller
{
    use Upload;

    public function index()
    {
        $data['basicControl'] = basicControl();
        $data['maintenanceMode'] = MaintenanceMode::firstOrFail();
        return view("admin.control_panel.maintenance_mode", $data);
    }

    public function maintenanceModeUpdate(Request $request)
    {

        $request->validate([
            "heading" => "required|string|min:3",
            "description" => "required|string|min:3",
            "is_maintenance_mode" => "nullable|integer|in:0,1",
            "image" => "sometimes|required|mimes:jpg,png,jpeg,gif,svg",
        ]);

        try {
            $maintenanceMode = MaintenanceMode::firstOrCreate();

            if ($request->hasFile('image')) {
                $image = $this->fileUpload($request->image, config('filelocation.maintenanceMode.path'), null, null,'webp', 70,$maintenanceMode->image,$maintenanceMode->image_driver);
                throw_if(empty($image['path']), 'Image could not be uploaded.');
                $image = $image['path'];
                $imageDriver = $image['driver'] ?? 'local';
            }

            $maintenanceMode->update([
                'heading' => $request->heading,
                'description' => $request->description,
                'image' => $image ?? $maintenanceMode->image,
                'image_driver' => $imageDriver ?? $maintenanceMode->image_driver,
            ]);

            $basicControl = basicControl();
            $basicControl->update([
                'is_maintenance_mode' => $request->is_maintenance_mode
            ]);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Maintenance Mode updated successfully");

    }
}
