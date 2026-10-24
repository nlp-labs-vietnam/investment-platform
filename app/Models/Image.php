<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Khai báo fillable cho các cột meta mới
    protected $fillable = ['imageable_type', 'imageable_id', 'image', 'driver', 'alt_text', 'caption', 'sort_order'];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function propertySeoOg()
    {
        return $this->hasOne(PropertySeo::class, 'og_image_id');
    }
}
