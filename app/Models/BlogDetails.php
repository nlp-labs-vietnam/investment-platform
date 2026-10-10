<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogDetails extends Model
{
    use HasFactory, Translatable;

    protected $guarded = ['id'];

    public function blog(){
        return $this->belongsTo(Blog::class, 'blog_id');
    }

}
