<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class LocalizationService
{
    public function createLang($defaultShortName, $shortName)
    {
        $defaultPaths = [
            resource_path("lang/"),
            resource_path("lang/default/"),
        ];

        foreach ($defaultPaths as $path) {
            if (file_exists($path . "{$defaultShortName}.json")) {
                $data = file_get_contents($path . "{$defaultShortName}.json");
                $destination = $path . strtolower($shortName) . '.json';
                File::put($destination, $data);
            }
        }
        return $defaultPaths;
    }

    public function deleteLang($shortName)
    {
        $defaultPaths = [
            resource_path("lang/{$shortName}.json"),
            resource_path("lang/default/{$shortName}.json"),
        ];

        foreach ($defaultPaths as $path) {
            file_exists($path) ? @unlink($path) : false;
        }
        return $defaultPaths;
    }

    public function renameLang($oldShortName, $newShortName)
    {
        $defaultPaths = [
            resource_path("lang/{$oldShortName}.json") => resource_path("lang/{$newShortName}.json"),
            resource_path("lang/default/{$oldShortName}.json") => resource_path("lang/default/{$newShortName}.json"),
        ];

        foreach ($defaultPaths as $key => $path) {
            file_exists($key) ? @rename($key, $path) : false;
        }
        return $defaultPaths;
    }

    public function deleteLangKeyword($shortName, $key)
    {
        $path = resource_path("lang/{$shortName}.json");

        if (file_exists($path)) {
            $contetns = json_decode(file_get_contents($path), true);
            unset($contetns[$key]);
            file_put_contents($path, stripslashes(json_encode($contetns, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)));
            return true;
        }
        return false;
    }

    public function updateLangKeyword($shortName, $key, $value, $default = false)
    {
        $path = $default ? resource_path("lang/default/{$shortName}.json") : resource_path("lang/{$shortName}.json");

        if (file_exists($path)) {
            $contetns = json_decode(file_get_contents($path), true);
            $contetns[$key] = $value;
            file_put_contents($path, stripslashes(json_encode($contetns, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)));
            return true;
        }
        return false;
    }
}
