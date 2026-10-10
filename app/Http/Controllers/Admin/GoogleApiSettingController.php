<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoogleSheetApi;
use Illuminate\Http\Request;
use App\Traits\Upload;

class GoogleApiSettingController extends Controller
{
    use Upload;
    public function googleAPISetting()
    {
        return view('admin.control_panel.google_api_setting');
    }

    public function googleAPICredentialUpload(Request $request)
    {

        $googleSheetCredential = GoogleSheetApi::firstOrFail();

        $request->validate([
              'credential' => 'nullable',
        ]);

        if ($request->hasFile('credential')) {
            try {
                $file = $this->fileUpload($request->credential, config('filelocation.googleTranslateCredential.path'), config('filesystems.default'));
                if ($file) {
                    $file_credential = $file['path'];
                    $file_driver = 'local';
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'File could not be uploaded.');
            }
        }

        $response = $googleSheetCredential->update([
            'api_credential_file' => $file_credential,
            'file_driver' => $file_driver,
        ]);

        if (!$response){
            throw  new \Exception('Something went wrong');
        }


        return back()->with('success', 'File uploaded successfully.');

    }
}
