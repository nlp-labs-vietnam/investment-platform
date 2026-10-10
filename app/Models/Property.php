<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Property extends Model
{
    use HasFactory, softDeletes;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at', 'start_date', 'expire_date'];
    protected $casts = ['amenity_id' => 'array', 'faq' => 'object'];
    protected $appends = ['investmentAmount'];

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($property) {
            Cache::forget("property_{$property->id}_limit_amenities");
        });
    }

    public function getInvestmentAmountAttribute()
    {
        if ($this->fixed_amount == null) {
            return basicControl()->currency_symbol . fractionNumber(getAmount($this->minimum_amount)) . ' - ' . basicControl()->currency_symbol . fractionNumber(getAmount($this->maximum_amount));
        }
        return basicControl()->currency_symbol . fractionNumber(getAmount($this->fixed_amount));
    }

    Public function address(){
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    public function image()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getLimitAmenityAttribute()
    {
        if (!$this->amenity_id || !is_array($this->amenity_id)) {
            return collect();
        }

        return Cache::remember("property_{$this->id}_limit_amenities", now()->addMinutes(30), function () {
            return Amenity::query()
                ->whereIn('id', $this->amenity_id)
                ->where('status', 1)
                ->orderBy('id', 'ASC')
                ->limit(3)
                ->get();
        });
    }

    public function limitamenity(){
        if($this->amenity_id == null){
            return [];
        }
        return Amenity::whereIn('id', $this->amenity_id)->where('status', 1)->limit(3)->orderBy('id', 'ASC')->get();
    }

    public function getAllAmenityAttribute()
    {
        if($this->amenity_id == null){
            return [];
        }
        return Amenity::whereIn('id', $this->amenity_id)->where('status', 1)->orderBy('id', 'ASC')->get();
    }

    public function managetime()
    {
        return $this->belongsTo(ManageTime::class, 'how_many_days', 'id');
    }

    public function totalRunningInvestUserAndAmount(){
        $totalInvestedUser = 0;
        $totalInvestedAmount = 0;
        $investment = Investment::where('property_id', $this->id)->where('invest_status', 1)->where('status', 0)->where('is_active', 1)->get();
        foreach ($investment as $key => $invest){
            $totalInvestedAmount += $invest->amount;
            $totalInvestedUser = $key+1;
        }
        return [
            'totalInvestedUser' => $totalInvestedUser,
            'totalInvestedAmount' => $totalInvestedAmount,
        ];
    }

    public function totalDueInvestUserAndAmount(){
        $totalInvestedUser = 0;
        $totalInvestedAmount = 0;
        $totalDueAmount = 0;
        $investment = Investment::with('property')->where('property_id', $this->id)->where('invest_status', 0)->where('status', 0)->where('is_active', 1)->get();
        foreach ($investment as $key => $invest){
            $totalInvestedAmount += $invest->amount;
            $totalInvestedUser = $key+1;
            $totalDueAmount += optional($invest->property)->fixed_amount - $invest->amount;
        }
        return [
            'totalInvestedUser' => $totalInvestedUser,
            'totalInvestedAmount' => $totalInvestedAmount,
            'totalDueAmount' => $totalDueAmount,
        ];
    }

    public function dueInvestmentAmount($investAmount){
        $dueInvestAmount = $this->fixed_amount - $investAmount;
        return $dueInvestAmount;
    }

    public function totalCompletedInvestUserAndAmount(){
        $totalInvestedUser = 0;
        $totalInvestedAmount = 0;
        $investment = Investment::where('property_id', $this->id)->where('invest_status', 1)->where('status', 1)->where('is_active', 1)->get();
        foreach ($investment as $key => $invest){
            $totalInvestedAmount += $invest->amount;
            $totalInvestedUser = $key+1;
        }
        return [
            'totalInvestedUser' => $totalInvestedUser,
            'totalInvestedAmount' => $totalInvestedAmount,
        ];
    }

    public function getInvestment()
    {
        return $this->hasMany(Investment::class, 'property_id', 'id');
    }

    public function getReviews()
    {
        return $this->hasMany(InvestorReview::class, 'property_id');
    }

    public function avgRating(){
        return  $this->getReviews()->avg('rating2');
    }

    public function getReviewCountText(): string
    {
        $reviewCount = $this->reviews_count;
        $reviewText = $reviewCount == 1 ? trans('review') : trans('reviews');
        return "{$reviewCount} {$reviewText}";
    }


    public function rud(){
        $todayDate = now();
        $startDate = new DateTime($this->start_date);
        $expireDate = new DateTime($this->expire_date);

        $datetime1 = new DateTime($todayDate);

        $runningProperties = $datetime1 > $expireDate;
        $upcomingProperties = $startDate > $datetime1;
        $difference = $datetime1->diff($startDate);

//        dd($runningProperties);
        return [
            'runningProperties'  => $runningProperties,
            'upcomingProperties' => $upcomingProperties,
            'difference'         => $difference
        ];
    }

    public function getFavourite()
    {
        $clientId = null;
        if (Auth::check()) {
            $clientId = Auth::user()->id;
        }
        return $this->hasOne(Favourite::class, 'property_id')->where('client_id', $clientId);

    }

    public function isInvested()
    {
        return in_array(auth()->id(), $this->getInvestment->pluck('user_id')->toArray());
    }

    public function investableAmount()
    {
        return ($this->fixed_amount > $this->available_funding && $this->available_funding > 0) ?
            getAmount($this->available_funding) :
            ($this->available_funding < $this->minimum_amount && $this->available_funding != 0 ?
                getAmount($this->minimum_amount) :
                ($this->is_invest_type == 1 ?
                    getAmount($this->fixed_amount) : ''));
    }

    public function favoritedByUser()
    {
        return $this->hasOne(Favourite::class, 'property_id')
            ->where('client_id', Auth::id());
    }

}
