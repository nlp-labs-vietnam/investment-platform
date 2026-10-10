<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PageDetail extends Model
{
    use HasFactory, Translatable;

    protected $fillable = ['page_id', 'language_id', 'name', 'content', 'sections'];

    protected $casts = ['sections' => 'array'];


    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
