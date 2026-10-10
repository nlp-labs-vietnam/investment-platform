<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    public function get_client(){
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function get_user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
