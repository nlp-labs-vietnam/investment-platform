<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ticket', 'subject', 'status', 'last_reply'];

    protected $dates = ['last_reply'];

    public function getUsernameAttribute()
    {
        return $this->name;
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('ticketRecord');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages(){
        return $this->hasMany(SupportTicketMessage::class)->latest();
    }

    public function lastReply(){
        return $this->hasOne(SupportTicketMessage::class)->latest();
    }
    public function  getLastMessageAttribute(){
        return Str::limit($this->lastReply->message,40);
    }

}
