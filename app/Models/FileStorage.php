<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileStorage extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'logo', 'driver', 'status', 'parameters'];

    protected $casts = ['parameters' => 'array'];
}
