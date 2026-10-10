<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ThemeController extends Controller
{
    public function manageTheme()
    {
        $theme = config('theme');
        return view('admin.theme.themes', compact('theme'));
    }

    public function themeActive(Request $request, $name)
    {
        try {
            $basic = BasicControl();
            $response = BasicControl::updateOrCreate([
                'id' => $basic->id ?? ''
            ], [
                'theme' => $name,
            ]);

            if (!$response)
                throw new Exception('Something went wrong, when updating the data.');

            session()->flash('success', 'Theme Activated Successfully');
            Artisan::call('optimize:clear');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
