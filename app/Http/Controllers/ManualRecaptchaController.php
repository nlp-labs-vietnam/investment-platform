<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManualRecaptchaController extends Controller
{
    public function reCaptCha()
    {
        renderCaptCha(request()->rand);
    }
}
