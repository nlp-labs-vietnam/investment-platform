<?php

namespace App\Services\Translate;

Class BaseTranslateService {
    public function textTranslate($shortName)
    {

        $translateObj = 'Facades\\App\\Services\\Translate\\' . ucfirst(config('translateconfig.default')) . 'TranslateService';
        $textTranslated = $translateObj::allKeywordTranslate($shortName);

        return $textTranslated;

    }

    public function singleKeywordTranslated($shortName, $key)
    {
        $translateObj = 'Facades\\App\\Services\\Translate\\' . ucfirst(config('translateconfig.default')) . 'TranslateService';
        $singleKeywordTranslated = $translateObj::singleKeywordTranslate($shortName, $key);

        return $singleKeywordTranslated;
    }
}
