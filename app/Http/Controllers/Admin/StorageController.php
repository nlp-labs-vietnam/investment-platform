<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileStorage;
use Illuminate\Http\Request;
use App\Traits\Upload;

class StorageController extends Controller
{
    use Upload;

    public function index()
    {
        $data['fileStorageMethod'] = FileStorage::orderBy('id', 'asc')->get();
        return view('admin.storage.index', $data);
    }

    public function edit($id)
    {
        $fileStorageMethod = FileStorage::where('code', '!=', 'local')->findOrFail($id);
        return view('admin.storage.edit', compact('fileStorageMethod'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|min:2|max:20|string',
            'logo' => 'sometimes|required|mimes:jpg,png,jpeg|max:2048'
        ];

        $storage = FileStorage::where('code', '!=', 'local')->findOrFail($id);
        $parameters = [];
        foreach ($request->except('_token', '_method', 'image') as $k => $v) {
            foreach ($storage->parameters as $key => $cus) {
                if ($k != $key) {
                    continue;
                } else {
                    $rules[$key] = 'required|string|min:1|max:191';
                    $parameters[$key] = $v;
                }
            }
        }

        $request->validate($rules);
        if ($request->hasFile('logo')) {
            try {
                $image = $this->fileUpload($request->logo, config('filelocation.driver.path'), null, null, 'webp', 70, $storage->logo, $storage->driver);
                if ($image) {
                    $storageLogo = $image['path'];
                    $storageDriver = $image['driver'];
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Image could not be uploaded');
            }
        }

        try {
            $response = $storage->update([
                'name' => $request->name,
                'parameters' => $parameters,
                'logo' => $storageLogo ?? $storage->logo,
                'driver' => $storageDriver ?? $storage->driver
            ]);

            if (!$response) {
                throw new \Exception("Something went wrong, Please try again.");
            }
            $this->envWrite($storage->code, $storage->parameters);
        } catch (\Exception $e) {
            return back()->with("error", $e->getMessage());
        }
        return back()->with('success', 'File Storage System Updated Successfully.');
    }

    public function setDefault(Request $request, $id)
    {
        try {
            $activeStorage = FileStorage::findOrFail($id);
            $activeStorage->update([
                'status' => 1
            ]);

            $storages = FileStorage::where('id', '!=', $id)->get();
            foreach ($storages as $storage) {
                $storage->update([
                    'status' => 0
                ]);
            }
            return back()->with('success', 'File system Set As Default Updated Successfully');
        } catch (\Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }

    public function envWrite($storageType, $parameters)
    {
        $envPath = base_path('.env');
        $env = file($envPath);
        if ($storageType == 's3') {
            $env = $this->set('AWS_ACCESS_KEY_ID', $parameters['access_key_id'], $env);
            $env = $this->set('AWS_SECRET_ACCESS_KEY', $parameters['secret_access_key'], $env);
            $env = $this->set('AWS_DEFAULT_REGION', $parameters['default_region'], $env);
            $env = $this->set('AWS_BUCKET', $parameters['bucket'], $env);
        } elseif ($storageType == 'sftp') {
            $env = $this->set('SFTP_USERNAME', $parameters['sftp_username'], $env);
            $env = $this->set('SFTP_PASSWORD', $parameters['sftp_password'], $env);
        } elseif ($storageType == 'do') {
            $env = $this->set('DIGITALOCEAN_SPACES_KEY', $parameters['spaces_key'], $env);
            $env = $this->set('DIGITALOCEAN_SPACES_SECRET', $parameters['spaces_secret'], $env);
            $env = $this->set('DIGITALOCEAN_SPACES_ENDPOINT', $parameters['spaces_endpoint'], $env);
            $env = $this->set('DIGITALOCEAN_SPACES_REGION', $parameters['spaces_region'], $env);
            $env = $this->set('DIGITALOCEAN_SPACES_BUCKET', $parameters['spaces_bucket'], $env);
        } elseif ($storageType == 'ftp') {
            $env = $this->set('FTP_HOST', $parameters['ftp_host'], $env);
            $env = $this->set('FTP_USERNAME', $parameters['ftp_username'], $env);
            $env = $this->set('FTP_PASSWORD', $parameters['ftp_password'], $env);
        }
        $fp = fopen($envPath, 'w');
        fwrite($fp, implode($env));
        fclose($fp);
        return 0;
    }

    private function set($key, $value, $env)
    {
        foreach ($env as $env_key => $env_value) {
            $entry = explode("=", $env_value, 2);
            if ($entry[0] == $key) {
                $env[$env_key] = $key . "=" . $value . "\n";
            } else {
                $env[$env_key] = $env_value;
            }
        }
        return $env;
    }
}
