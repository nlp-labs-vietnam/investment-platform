<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function get_user(){
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function get_property(){
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

}
