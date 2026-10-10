<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Fund extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'gateway_id', 'fundable_id', 'fundable_type', 'gateway_currency', 'amount', 'charge', 'percentage_charge',
        'fixed_charge', 'final_amount', 'payable_amount_base_currency', 'btc_amount', 'btc_wallet', 'transaction', 'status', 'detail', 'feedback', 'validation_token',
        'referenceno', 'reason', 'information', 'api_response'];

    protected $table = "funds";

    protected $casts = [
        'detail' => 'object',
        'information' => 'object'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }

    public function getStatusClass()
    {
        return [
            '1' => 'text-success',
            '2' => 'text-pending',
            '3' => 'text-danger',
            '4' => 'text-danger',
        ][$this->status] ?? 'text-danger';
    }

    public function picture()
    {
        $image = optional($this->gateway)->image;
        if (!$image) {
            $firstLetter = substr(optional($this->gateway)->name, 0, 1);
            return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                        <span class="avatar-initials">' . $firstLetter . '</span>
                     </div>';

        } else {
            $url = getFile(optional($this->gateway)->driver, optional($this->gateway)->image);
            return '<div class="avatar avatar-sm avatar-circle">
                        <img class="avatar-img" src="' . $url . '" alt="Image Description">
                     </div>';
        }
    }

    public function transactional()
    {
        return $this->morphOne(Transaction::class, 'transactional');
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Fund $fund) {
            if (empty($fund->transaction)) {
                $fund->transaction = self::generateOrderNumber();
            }
        });
    }
    public static function generateOrderNumber()
    {
        return DB::transaction(function () {
            $lastOrder = self::lockForUpdate()->orderBy('id', 'desc')->first();
            if ($lastOrder && isset($lastOrder->transaction)) {
                $lastOrderNumber = (int)filter_var($lastOrder->transaction, FILTER_SANITIZE_NUMBER_INT);
                $newOrderNumber = $lastOrderNumber + 1;
            } else {
                $newOrderNumber = strRandomNum(12);
            }

            // Check again to ensure the new trx_id doesn't already exist (extra safety)
            while (self::where('transaction', 'F'.$newOrderNumber)->exists()) {
                $newOrderNumber = (int)$newOrderNumber + 1;
            }
            return 'F' . $newOrderNumber;
        });
    }


}
