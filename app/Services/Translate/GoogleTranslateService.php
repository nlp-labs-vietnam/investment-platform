<?php

namespace App\Services\Translate;

use Google\Cloud\Translate\V2\TranslateClient;

class GoogleTranslateService
{

    protected $translate;

    public function __construct()
    {
        $projectId = env("PROJECT_ID"); //main-cedar-391910
        $keyFilePath = base_path('main-cedar-391910-cd7c5ebfdd8b.json');
        $this->translate = new TranslateClient([
            'projectId' => $projectId,
            'keyFilePath' => $keyFilePath,
        ]);
    }

    public function allKeywordTranslate($shortName)
    {
        $path = resource_path("lang/$shortName.json");
        if (file_exists($path)) {
            $contents = json_decode(file_get_contents($path), true);
        }

        $textToTranslate = array_keys($contents);
        $translatedValue = [];
        foreach ($textToTranslate as $key => $text) {
            $translatedValue[] = $text;
        }

        try {
            $this->translate->translateBatch($translatedValue, [
                'target' => 'en',
            ]);

            return $translatedValue;
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function editKeywordTranslate($shortName, $key)
    {
        $path = resource_path("lang/$shortName.json");
        if (file_exists($path)) {
            $contents = json_decode(file_get_contents($path), true);
        }
        try {
            $text = $key;
            $translation = $this->translate->translate($text, [
                'target' => $shortName,
            ]);

            return $translation['text'];
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

}
