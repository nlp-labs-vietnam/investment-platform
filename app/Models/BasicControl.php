<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BasicControl extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function metaKeywords(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => explode(", ", $value),
        );
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            \Cache::forget('ConfigureSetting');
        });
    }
}
