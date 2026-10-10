<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentDetails extends Model
{
    use HasFactory, Translatable;

    public $fillable = ['content_id', 'language_id', 'description'];

    public $casts = ['description' => "object"];

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'id');
    }
}
