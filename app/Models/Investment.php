<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates   = ['deleted_at'];

    protected $appends = [ 'invest'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id')->withTrashed();
    }

    public function propertyShare(){
        return $this->hasOne(PropertyShare::class, 'investment_id')->where('status', 1);
    }

    public function getInstallmentDate(){
        $currentDate = Carbon::today()->toDateString();
        $installmentLastDate = Carbon::parse($this->next_installment_date_end)->format('Y-m-d');
        if ($installmentLastDate >= $currentDate){
            return 1;
        }else{
            return 0;
        }
    }


    public function getInvestAttribute()
    {
        if (auth()->check() == false) {
            return false;
        }
        $userId = auth()->id();
        if ($this->user_id == $userId) {
            return true;
        } else {
            return false;
        }
    }

}
