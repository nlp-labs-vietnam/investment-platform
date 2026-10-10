<?php

namespace App\Services\Google;

use Illuminate\Support\Facades\Http;

class GoogleRecaptchaService
{

    public function responseRecaptcha($response)
    {
        try {
            $response = Http::asForm()->post(config("google.recaptcha_site_verify_url"), [
                'secret' => config('google.recaptcha_site_key'),
                'response' => $response,
            ]);

            $result = $response->json();

            if ($result['success']) {
                return true;
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
