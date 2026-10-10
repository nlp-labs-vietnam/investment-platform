<?php

namespace App\Traits;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait Upload
{
    public function makeDirectory($path)
    {
        if (file_exists($path)) return true;
        return mkdir($path, 0755, true);
    }

    public function removeFile($path)
    {
        return file_exists($path) && is_file($path) ? @unlink($path) : false;
    }

    public function fileUpload($file, $location, $fileName = null, $size = null, $encodedFormat = null, $encodedQuality = 90, $oldFileName = null, $oldDriver = 'local')
    {
        $activeDisk = config('filesystems.default');

        if (!empty($oldFileName) && Storage::disk($oldDriver)->exists($oldFileName))
            Storage::disk($oldDriver)->delete($oldFileName);

        if (!is_string($file)) {
            $file = new File($file);
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $path = $this->makeImage($activeDisk, $file, $location, $fileName, $size, $encodedFormat, $encodedQuality, $file->extension());
            } else {
                $path = Storage::disk($activeDisk)->putFileAs($location, $file, $fileName ?? $file->hashName());
            }
        } else {
            if ($this->isImageUrl($file)) {
                $path = $this->makeImage($activeDisk, $file, $location, $fileName, $size, $encodedFormat, $encodedQuality, pathinfo($file, PATHINFO_FILENAME));
            } else {
                Storage::disk($activeDisk)->put($location, $file);
                $path = $location;
            }
        }

        return [
            'path' => $path,
            'driver' => $activeDisk,
        ];
    }

    protected function makeImage($activeDisk, $file, $location, $fileName, $size, $encodedFormat, $encodedQuality, $fileExtension)
    {
        $image = Image::make($file);
        if (!empty($size)) {
            $size = explode('x', strtolower($size));
            $image->resize($size[0], $size[1]);
        }

        $path = $location . '/' . ($fileName ?? Str::random(30)) . '.' . ($encodedFormat ?? $fileExtension);
        Storage::disk($activeDisk)->put($path, !empty($encodedFormat) ? $image->encode($encodedFormat, $encodedQuality) : $image->encode());
        return $path;
    }

    protected function isImageUrl($url)
    {
        $imageInfo = @getimagesize($url);
        if ($imageInfo != false && str_starts_with($imageInfo['mime'], 'image/'))
            return true;

        return false;
    }

    public function fileDelete($driver = 'local', $old)
    {
        if (!empty($old)) {
            if (Storage::disk($driver)->exists($old)) {
                Storage::disk($driver)->delete($old);
            }
        }
        return 0;
    }
}

