<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Traits\Upload;
use Facades\App\Services\LocalizationService;
use Exception;
use Facades\App\Services\Translate\BaseTranslateService;

class LanguageController extends Controller
{
    use Upload;

    public function index()
    {
        $languages = Language::all();
        return view('admin.language.list', compact('languages'));
    }

    public function create()
    {
        $shortNames = config('languages.langCode');
        return view('admin.language.create', compact('shortNames'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:languages,name',
            'short_name' => 'required|string|max:3|unique:languages,short_name',
            'rtl' => 'nullable|integer|in:0,1',
            'status' => 'nullable|integer|in:0,1',
            'default_lang' => 'nullable|integer|in:0,1',
            'flag' => ' sometimes|required|mimes:jpg,png,jpeg|max:2048',
        ]);

        try {
            if ($request->file('flag') && $request->file('flag')->isValid()) {
                $image = $this->fileUpload($request->flag, config('filelocation.language.path'), null, null, 'webp', 70);
                if ($image) {
                    $flagImage = $image['path'];
                    $driver = $image['driver'] ?? 'local';
                }
            }

            $response = Language::create([
                'name' => $request->name,
                'short_name' => $request->short_name,
                'status' => $request->status,
                'rtl' => $request->rtl,
                'default_status' => $request->default_lang,
                'flag' => $flagImage ?? null,
                'flag_driver' => $driver ?? 'local'
            ]);

            if (!$response) {
                throw new Exception('Something went wrong while storing language. Please try again later.');
            }

            LocalizationService::createLang(defaultLang()->short_name, $request->short_name);

            if ($response->default_status == 1) {
                session()->put('lang', $response->short_name);
                session()->put('rtl', $response ? $response->rtl : 0);
                Language::whereNotIn('id', [$response->id])->get()->map(function ($item) {
                    $item->default_status = 0;
                    $item->save();
                });
            }
            return redirect()->route('admin.language.index')->with('success', "`{$response->name}` language has been created successfully.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $language = Language::where('id', $id)->firstOr(function () {
                throw new Exception('No Language found.');
            });

            $shortNames = config('languages.langCode');
            return view('admin.language.edit', compact('language', 'shortNames'));
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:languages,name,' . $id,
            'short_name' => 'required|string|max:3|unique:languages,short_name,' . $id,
            'rtl' => 'nullable|integer|in:0,1',
            'status' => 'nullable|integer|in:0,1',
            'default_lang' => 'nullable|integer|in:0,1',
            'flag' => 'sometimes|required|mimes:jpg,png,jpeg,svg|max:2048',
        ]);

        try {
            $language = Language::where('id', $id)->firstOr(function () {
                throw new \Exception('No Language found.');
            });

            $oldShortName = $language->short_name;

            if ($request->file('flag') && $request->file('flag')->isValid()) {
                $image = $this->fileUpload($request->flag, config('filelocation.language.path'), null, null, 'webp', 70, $language->flag, $language->flag_driver);
                if ($image) {
                    $flagImage = $image['path'];
                    $flagDriver = $image['driver'];
                }
            }

            $response = $language->update([
                'name' => $request->name,
                'short_name' => $request->short_name,
                'status' => $request->status,
                'rtl' => $request->rtl,
                'flag' => $flagImage ?? $language->flag,
                'flag_driver' => $flagDriver ?? $language->flag_driver,
                'default_status' => $request->default_lang == 1 ? 1 : 0,
            ]);

            if (!$response) {
                throw new Exception('Something went wrong. Please try again later.');
            }

            LocalizationService::renameLang($oldShortName, $language->short_name);

            if ($language->default_status == 1) {
                session()->put('lang', $language->short_name);
                session()->put('rtl', $language ? $language->rtl : 0);
                Language::whereNotIn('id', [$language->id])->get()->map(function ($item) {
                    $item->default_status = 0;
                    $item->save();
                });
            }

            return back()->with('success', "`{$language->name}` language has been updated successfully");

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    function destroy(Request $request, $id)
    {
        try {
            $language = Language::where(['id' => $id, 'default_status' => false])->firstOr(function () {
                throw new \Exception('No language found or may be you had selected default language.');
            });

            if (strtolower($language->short_name) != 'en') {
                $language->notificationTemplates()->delete();
                $language->pageDetails()->delete();
                $language->contentDetails()->delete();
                $response = $language->delete();
            }

            throw_if(!$response, 'Something went wrong. Please try again later.');
            LocalizationService::deleteLang($language->short_name);
            return back()->with('success', "`{$language->name}` language has been deleted successfully.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function keywords($shortName)
    {
        try {
            $language = Language::where('short_name', $shortName)->firstOr(function () {
                throw new \Exception('No Language found.');
            });
            $languages = Language::all();
            $keywords = json_decode(file_get_contents(resource_path('lang/') . strtolower($shortName) . '.json'));
            $pageTitle = $language->name . " Language Keyword";
            return view('admin.language.keywords', compact('language', 'keywords', 'languages', 'pageTitle'));
        } catch (\Exception $exception) {
            return back()->with('alert', $exception->getMessage());
        }
    }

    public function addKeyword(Request $request, $shortName)
    {
        $this->validate($request, [
            'key' => 'required|string|min:1|max:100',
            'value' => 'required|string|min:1|max:100'
        ], [
            'required' => 'This field is required.',
            'string' => 'This field must be string.'
        ]);
        try {
            $response = LocalizationService::updateLangKeyword($shortName, $request->key, $request->value);

            throw_if(!$response, 'Something went wrong. Please try again later.');

            session()->flash('success', "`{$request->key}` keyword has been added successfully.");
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
        return response()->json(['url' => route('admin.language.keywords', $shortName)]);
    }


    public function updateKeyword(Request $request, $shortName, $key)
    {
        $this->validate($request, [
            'value' => 'required|string'
        ], [
            'required' => 'This field is required.',
            'string' => 'This field must be string.'
        ]);

        try {
            $key = urldecode($key);
            $routeName = request()->route()->getName();
            $default = $routeName == 'admin.update.language.default.keyword' ? true : false;
            $response = LocalizationService::updateLangKeyword($shortName, $key, $request->value, $default);
            throw_if(!$response, 'Something went wrong. Please try again later.');
            session()->flash('success', "`{$key}` keyword has been updated successfully.");
        } catch (\Exception $exception) {
            session()->flash('alert', $exception->getMessage());
        }
        return response()->json(['url' => route('admin.language.keywords', $shortName)]);
    }

    public function deleteKeyword($shortName, $key)
    {
        try {
            $key = urldecode($key);
            $response = LocalizationService::deleteLangKeyword($shortName, $key);

            throw_if(!$response, 'Something went wrong. Please try again later.');
            return back()->with('success', "`{$key}` keyword has been deleted successfully.");
        } catch (\Exception $exception) {
            return back()->with('alert', $exception->getMessage());
        }
    }


    public function importJson(Request $request)
    {
        try {
            $myLang = Language::where(['id' => $request->my_lang, 'default_status' => false])->firstOr(function () {
                throw new \Exception('No language found or may be you selected default language.');
            });

            $lang = Language::findOrFail($request->lang_id);
            $json = file_get_contents(resource_path('lang/') . $lang->short_name . '.json');
            $jsonArray = json_decode($json, true);

            file_put_contents(resource_path('lang/') . $myLang->short_name . '.json', json_encode($jsonArray));
            return back()->with('success', 'Import data successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function changeStatus($id)
    {
        try {
            $language = Language::where(['id' => $id, 'default_status' => false])->firstOr(function () {
                throw new \Exception('No language found or may be you selected default language.');
            });

            $response = $language->update([
                'status' => !$language->status
            ]);

            throw_if(!$response, 'Something went wrong. Please try again later.');

            $status = $language->status == 1 ? 'activated' : 'deactivated';
            return back()->with('success', "`{$language->name}` language has been {$status} successfully.");
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function singleKeywordTranslate(Request $request)
    {
        $shortName = $request->shortName;
        $key = $request->key;
        $singleTranslatedText = BaseTranslateService::singleKeywordTranslated($shortName, $key);

        $path = resource_path("lang/{$shortName}.json");
        if (file_exists($path)) {
            $contents = json_decode(file_get_contents($path), true);
        }

        $contents[$key] = $singleTranslatedText;
        file_put_contents($path, stripslashes(json_encode($contents, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)));
        return response([
            'message' => 'Keyword Translation Successfully.',
            'translatedText' => $singleTranslatedText
        ]);
    }

    public function allKeywordTranslate(Request $request, $shortName)
    {
        $allTranslatedText = BaseTranslateService::textTranslate($shortName);
        $path = resource_path("lang/{$shortName}.json");
        if (file_exists($path)) {
            $contents = json_decode(file_get_contents($path), true);
        }
        $mergedTranslations = array_merge($contents, $allTranslatedText);
        file_put_contents($path, json_encode($mergedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return back()->with('success', 'Keyword Translation Successfully.');
    }
}
