<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferLock extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function propertyOffer()
    {
        return $this->belongsTo(PropertyOffer::class, 'property_offer_id');
    }

    public function getDurationAttribute($value)
    {
        return $this->localDateFormat($value);
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->localDateFormat($value);
    }

    protected function localDateFormat($value)
    {
        if (isset($value)) {
            if (isset(auth()->user()->timezone)) {
                return Carbon::parse(Carbon::parse($value)->setTimezone(auth()->user()->timezone)->toDateTimeString());
            }
            return Carbon::parse($value);
        }
        return null;
    }
}
