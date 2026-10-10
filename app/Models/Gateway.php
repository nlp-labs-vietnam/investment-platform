<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'sort_by',
        'image',
        'driver',
        'status',
        'parameters',
        'currencies',
        'description',
        'extra_parameters',
        'currency',
        'symbol',
        'is_sandbox',
        'environment',
        'min_amount',
        'max_amount',
        'percentage_charge',
        'fixed_charge',
        'convention_rate',
        'supported_currency',
        'receivable_currencies',
        'note'
    ];
    protected $casts = [
        'currency' => 'object',
        'supported_currency' => 'object',
        'receivable_currencies' => 'object',
        'parameters' => 'object',
        'currencies' => 'object',
        'extra_parameters' => 'object',
    ];

    public function scopeAutomatic()
    {
        return $this->where('id', '<', 1000);
    }

    public function scopeManual()
    {
        return $this->where('id', '>=', 1000);
    }

    public function countGatewayCurrency()
    {
        $currencyLists = $this->currencies->{0} ?? $this->currencies->{1};
        $count = 0;
        foreach ($currencyLists as $currency) {
            $count++;
        }
        return $count;
    }
}
