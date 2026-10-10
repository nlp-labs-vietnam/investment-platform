<?php

namespace App\Services\Translate;
use Illuminate\Support\Facades\Http;


Class AzureTranslateService {


    public function allKeywordTranslate($shortName)
    {

        $azureCredential = config('translateconfig.translate_method.azure');
        $endpoint = $azureCredential['end_point_url']['value'];
        $subscriptionKey = $azureCredential['subscription_key']['value'];
        $url = $endpoint."translator/text/v3.0/translate?from=en&to=$shortName";

        $path = resource_path("lang/$shortName.json");
        if (file_exists($path)) {
            $contents = json_decode(file_get_contents($path), true);
        }


        $textToTranslate = array_keys($contents);

        $requestData = [];
        foreach ($textToTranslate as $text) {
            $requestData[] = ['text' => $text];
        }

        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
            'Ocp-Apim-Subscription-Region' => $azureCredential['subscription_region']['value'],
            'Content-type' => 'application/json',
        ])->post($url, $requestData);

        $translatedData = $response->json();


        $translatedText = [];
        foreach ($translatedData as $index => $translation) {
            $translatedText[$textToTranslate[$index]] = $translation['translations'][0]['text'];
        }

        return $translatedText;

    }

    public function singleKeywordTranslate($shortName, $key)
    {

        $azureCredential = config('translateconfig.translate_method.azure');
        $endpoint = $azureCredential['end_point_url']['value'];
        $subscriptionKey = $azureCredential['subscription_key']['value'];
        $url = $endpoint."translator/text/v3.0/translate?from=en&to=$shortName";

        $path = resource_path("lang/$shortName.json");
        if (file_exists($path)) {
            $contents = json_decode(file_get_contents($path), true);
        }

        $text = $key;
        $requestData[] = ['text' => $text];;

        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
            'Ocp-Apim-Subscription-Region' => $azureCredential['subscription_region']['value'],
            'Content-type' => 'application/json',
        ])->post($url, $requestData);

        $translatedData = $response->json();
        foreach ($translatedData as $index => $translation) {
            $translatedText = $translation['translations'][0]['text'];
        }

        return $translatedText;

    }

}
