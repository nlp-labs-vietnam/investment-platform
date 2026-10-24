<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertySeo extends Model
{
    use HasFactory;

    protected $table = 'property_seo';

    protected $guarded = ['id'];

    protected $casts = [
        'schema_json' => 'array',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function ogImage()
    {
        return $this->belongsTo(Image::class, 'og_image_id');
    }
}
