<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorReview extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['date_formatted'];

    public function review_user_info(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }


    public function getProperty(){
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }


    public function getDateFormattedAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }
}
