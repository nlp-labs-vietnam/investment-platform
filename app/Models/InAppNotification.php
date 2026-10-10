<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InAppNotification extends Model
{
    use HasFactory;

    protected $fillable = ['in_app_notificationable_id', 'in_app_notificationable_type', 'description'];

    protected $casts = [
        'description' => 'object'
    ];

    protected $appends = ['formatted_date'];

    public function getFormattedDateAttribute(){
        return $this->created_at->format('F d, Y h:i A');
    }

    public function inAppNotificationable()
    {
        return $this->morphTo(__FUNCTION__, 'in_app_notificationable_type', 'in_app_notificationable_id');
    }
}
