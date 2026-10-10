<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsControl extends Model
{
    use HasFactory;

    protected $fillable = ['method_name', 'configuration_parameters', 'status'];

    protected $casts = [
        'configuration_parameters' => 'object',
    ];
}
