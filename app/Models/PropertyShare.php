<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PropertyShare extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public function property(){
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function getInvestment(){
        return $this->belongsTo(Investment::class, 'investment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'investor_id');
    }

    public function propertyOffer(){
        return $this->hasOne(PropertyOffer::class, 'property_share_id')->where('offered_from', Auth::id());
    }

    public function forAllLock()
    {
        return $this->belongsTo(OfferLock::class, 'property_share_id', 'id')
            ->where('status', 0); // Add the status condition here
    }

}

