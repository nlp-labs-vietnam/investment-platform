<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['support_ticket_message_id', 'file', 'driver'];

    public function supportMessage()
    {
        return $this->belongsTo(SupportTicketMessage::class,'support_ticket_message_id');
    }
}
