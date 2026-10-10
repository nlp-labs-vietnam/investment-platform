<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'flag',
        'flag_driver',
        'status',
        'rtl',
        'default_status',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function setShortNameAttribute($value)
    {
        $this->attributes['short_name'] = strtolower($value);
    }

    public function getStatusClass()
    {
        return [
            '0' => 'danger',
            '1' => 'success',
        ][$this->status] ?? 'danger';
    }
    public function notificationTemplates()
    {
        return $this->hasMany(NotificationTemplate::class,'language_id');
    }

    public function pageDetails()
    {
        return $this->hasMany(PageDetail::class,'language_id');
    }

    public function contentDetails()
    {
        return $this->hasMany(ContentDetails::class,'language_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            \Cache::forget('allLanguages');
            \Cache::forget('active_languages');
            \Cache::forget('default_language');
        });
    }

}
