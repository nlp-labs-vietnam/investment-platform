<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'name',
        'subject',
        'template_key',
        'short_keys',
        'email',
        'sms',
        'in_app',
        'push',
        'status',
        'notify_for',
        'lang_code',
        'email_from'
    ];

    protected $casts = [
        'short_keys' => 'array',
        'status' => 'array',
    ];

}
