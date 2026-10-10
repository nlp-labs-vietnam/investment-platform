<?php

namespace App\Traits;


trait ContentDelete
{
    public static function booted()
    {
        static::deleting(function ($model) {
            if (isset($model->contentMedia->description->image)) {
				file_exists(config('location.content.path') . '/' . $model->contentMedia->description->image) && is_file(config('location.content.path') . '/' . $model->contentMedia->description->image) ? @unlink(config('location.content.path') . '/' . $model->contentMedia->description->image) : '';
            };
            $model->contentMedia()->delete();
            $model->contentDetails()->delete();
        });
    }
}
