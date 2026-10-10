<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Facades\App\Services\BasicService;
use App\Traits\Upload;

class TranslateAPISettingController extends Controller
{

    use Upload;

    public function translateAPISetting()
    {
        $translateMethod = Config('translateconfig.translate_method');
        $activeMethod = Config('translateconfig.default');
        return view('admin.translate_controls.index', compact('translateMethod', 'activeMethod'));
    }

    public function translateAPISettingEdit($method)
    {
        try {
            $data['basicControl'] = basicControl();
            $translateControlMethod = config('translateconfig.translate_method');
            $translateMethodParameters = $translateControlMethod[$method] ?? null;

            return view('admin.translate_controls.translate_api_config', $data, compact('translateMethodParameters', 'method'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function translateAPISettingUpdate(Request $request, $method)
    {

        $rules = [];

        $translateControlMethod = config('translateconfig.translate_method');
        $translateMethodParameters = $translateControlMethod[$method] ?? null;

        foreach ($request->except('_token', '_method') as $key => $value) {
            if (array_key_exists($key, $translateMethodParameters)) {
                $rules[$key] = 'required|max:191';
            }
        }

        $request->validate($rules);


        try {
            $env = [
                'END_POINT_URL' => $request->end_point_url ?? $translateControlMethod['azure']['end_point_url']['value'],
                'SUBSCRIPTION_KEY' => $request->subscription_key ?? $translateControlMethod['azure']['subscription_key']['value'],
                'SUBSCRIPTION_REGION' => $request->subscription_region ?? $translateControlMethod['azure']['subscription_region']['value'],
                'PROJECT_ID' => $request->project_id ?? $translateControlMethod['google']['project_id']['value'] ?? null,
            ];

            BasicService::setEnv($env);

            return back()->with('success', 'Translate Configuration has been updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function translateSetAsDefault($method)
    {
        $env = [
            'TRANSLATE_METHOD' => $method
        ];

        BasicService::setEnv($env);

        return back()->with('success', 'Translate method set as default successfully.');
    }


}
