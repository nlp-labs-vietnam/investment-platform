<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PropertyOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function getInvestment()
    {
        return $this->belongsTo(Investment::class, 'investment_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'offered_from');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'offered_to');
    }

    public function propertyShare()
    {
        return $this->belongsTo(PropertyShare::class, 'property_share_id')->withTrashed();
    }

    public function totalOfferList()
    {
        return DB::selectOne('SELECT COUNT(*) as count FROM property_offers WHERE property_share_id = ?', [$this->property_share_id])->count;
    }

    public function offerlock()
    {
        return $this->hasOne(OfferLock::class, 'property_share_id', 'property_share_id'); // offer lock see for all offered person //
    }

    public function receiveMyOffer()
    {
        return $this->hasOne(OfferLock::class, 'property_offer_id', 'id'); // offer lock for me.
    }

    public function lockInfo()
    {
        return OfferLock::where('property_offer_id', $this->id)->first();
    }
}
