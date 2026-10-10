<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferReply extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['sent_at'];

    public function getSentAtAttribute(){
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function get_sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function get_receiver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
