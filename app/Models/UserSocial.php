<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function get_user_social_links(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
