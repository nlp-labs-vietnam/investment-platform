<?php

namespace App\Services;


use App\Models\PayoutMethod;
use Illuminate\Support\Facades\Http;
use Exception;

class CurrencyLayerService
{
    public function getCurrencyRate()
    {
        $endpoint = 'live';
        $source = basicControl()->base_currency;
        $currency_layer_url = "http://api.currencylayer.com";
        $currency_layer_access_key = basicControl()->currency_layer_access_key;

        $payoutCurrencies = PayoutMethod::where('is_automatic', 1)
            ->where('code', '!=', 'coinbase')
            ->where('is_auto_update', 1)
            ->pluck('supported_currency')->toArray();


        $currencyLists = array();
        foreach ($payoutCurrencies as $currency) {
            foreach ($currency as $singleCurrency) {
                $currencyLists[] = $singleCurrency;
            }
        }

        $currencyLists = array_unique($currencyLists);


        $currencies = implode(',', $currencyLists);
        $CurrencyAPIUrl = "$currency_layer_url/$endpoint?access_key=$currency_layer_access_key&source=$source&currencies=$currencies";

        $response = Http::acceptJson()
            ->get($CurrencyAPIUrl);

        if ($response->status() == 200)
            return json_decode($response->body());

        throw new Exception("Something went wrong while fetching user's data from Envato.");
    }

}
