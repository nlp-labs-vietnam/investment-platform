<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketMessage extends Model
{
    use HasFactory;

    protected $fillable = ['support_ticket_id', 'admin_id', 'message'];

    public function ticket(){
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id', 'id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(SupportTicketAttachment::class,'support_ticket_message_id','id');
    }



}
