<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualSmsConfig extends Model
{
    use HasFactory;

    protected $fillable = ['action_method', 'action_url', 'header_data', 'param_data', 'form_data'];

}
