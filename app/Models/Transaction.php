<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['transactional_id', 'transactional_type', 'user_id', 'amount', 'balance', 'charge', 'trx_type', 'remarks', 'trx_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transactional()
    {
        return $this->morphTo();
    }


    public static function boot(): void
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('paymentRecord');
        });

        static::creating(function (Transaction $transaction) {
            if (empty($transaction->trx_id)) {
                $transaction->trx_id = self::generateOrderNumber();
            }
        });
    }
    public static function generateOrderNumber()
    {
        return DB::transaction(function () {
            $lastOrder = self::lockForUpdate()->orderBy('id', 'desc')->first();
            if ($lastOrder && isset($lastOrder->trx_id)) {
                $lastOrderNumber = (int)filter_var($lastOrder->trx_id, FILTER_SANITIZE_NUMBER_INT);
                $newOrderNumber = $lastOrderNumber + 1;
            } else {
                $newOrderNumber = strRandomNum(12);
            }

            // Check again to ensure the new trx_id doesn't already exist (extra safety)
            while (self::where('trx_id', 'T'.$newOrderNumber)->exists()) {
                $newOrderNumber = (int)$newOrderNumber + 1;
            }
            return 'T' . $newOrderNumber;
        });
    }

}
