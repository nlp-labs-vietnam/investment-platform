<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = ['meta_keywords' => 'array'];

    public function details()
    {
        return $this->hasOne(BlogDetails::class, 'blog_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function manyDetails()
    {
        return $this->hasMany(BlogDetails::class, 'blog_id', 'id');
    }
    public function getLanguageEditClass($languageId)
    {
        return $this->manyDetails->contains('language_id',$languageId)
            ? 'bi-check2'
            : 'bi-pencil';
    }

}
