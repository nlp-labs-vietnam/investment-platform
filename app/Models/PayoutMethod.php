<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'bank_name', 'banks', 'parameters', 'extra_parameters', 'inputForm', 'currency_lists',
        'supported_currency', 'payout_currencies', 'is_active', 'is_automatic', 'is_sandbox', 'environment', 'confirm_payout', 'is_auto_update',
        'currency_type', 'logo', 'driver'];

    protected $casts = [
        'bank_name' => 'object',
        'banks' => 'array',
        'parameters' => 'object',
        'extra_parameters' => 'object',
        'convert_rate' => 'object',
        'currency_lists' => 'object',
        'supported_currency' => 'object',
        'automatic_input_form' => 'object',
        'payout_currencies' => 'object',
        'inputForm' => 'object'
    ];
}
