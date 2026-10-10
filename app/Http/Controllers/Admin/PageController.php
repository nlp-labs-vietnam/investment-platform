<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\ManageMenu;
use App\Models\Page;
use App\Rules\AlphaDashWithoutSlashes;
use App\Traits\Upload;
use Http\Client\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    use Upload;

    public function index()
    {
        $theme = basicControl()->theme;
        $data['allTemplate'] = getThemesNames();
        abort_if(!in_array($theme, getThemesNames()), 404);
        $data['allLanguage'] = Language::select('id', 'name', 'short_name', 'flag', 'flag_driver')->where('status', 1)->orderBy('default_status', 'desc')->get();
        $defaultLanguage = Language::where('default_status', true)->first();
        $data['allPages'] = Page::with(['details' => function ($query) use ($defaultLanguage, $theme) {
            $query->where('language_id', $defaultLanguage->id);
        },'manyDetails'])
            ->where('template_name', $theme)
            ->whereNull('custom_link')->get();
        return view("admin.frontend_management.page.index", $data, compact("theme", 'defaultLanguage'));
    }

    public function create($theme)
    {
        abort_if(!in_array($theme, getThemesNames()), 404);
        $data['url'] = url('/') . "/";
        $data["sections"] = getPageSections();
        $data['defaultLanguage'] = Language::where('default_status', true)->first();
        return view("admin.frontend_management.page.create", $data, compact('theme'));
    }

    public function store(Request $request, $theme)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = DB::table('page_details')
                        ->join('pages', 'pages.id', '=', 'page_details.page_id')
                        ->where('pages.template_name', basicControl()->theme)
                        ->where('page_details.name', $value)
                        ->exists();
                    if ($exists) {
                        $fail('The name has already been taken.');
                    }
                },
            ],
            'slug' => [
                'required',
                'min:1',
                'max:100',
                Rule::unique('pages')->where(function ($query) use ($theme) {
                    return $query->where('template_name', $theme);
                }),
                new AlphaDashWithoutSlashes(),
                Rule::notIn(['login', 'register', 'signin', 'signup', 'sign-in', 'sign-up']),
            ],

            'page_content' => 'required|string|min:3',
            'breadcrumb_status' => 'nullable|integer|in:0,1',
            'breadcrumb_image' => ($request->input('breadcrumb_status') == 1) ? 'required|mimes:jpg,png,jpeg|max:3048' : 'nullable|mimes:jpg,png,jpeg|max:2048',
            'status' => 'nullable|integer|in:0,1',
        ]);

        if ($request->hasFile('breadcrumb_image')) {
            $image = $this->fileUpload($request->breadcrumb_image, config('filelocation.pageImage.path'), null, null, 'webp', 70);
            if ($image) {
                $breadCrumbImage = $image['path'];
                $breadCrumbImageDriver = $image['driver'];
            }
        }

        $sections = preg_match_all('/\[\[([^\]]+)\]\]/', strtolower($request->page_content), $matches) ? $matches[1] : null;

        try {
            $response = Page::create([
                "name" => strtolower($request->name),
                "slug" => $request->slug,
                "template_name" => $theme,
                "breadcrumb_image" => $breadCrumbImage ?? null,
                "breadcrumb_image_driver" => $breadCrumbImageDriver ?? 'local',
                "breadcrumb_status" => $request->breadcrumb_status,
                "status" => $request->status,
                "type" => 0
            ]);

            if (!$response) {
                throw new \Exception("Something went wrong, Please Try again");
            }

            $response->details()->create([
                "name" => $request->name,
                'language_id' => $request->language_id,
                'content' => $request->page_content,
                'sections' => $sections,
            ]);

            return redirect()->route('admin.page.index', $theme)->with('success', 'Page Saved Successfully');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function edit($id, $theme, $language = null)
    {
        abort_if(!in_array($theme, getThemesNames()), 404);
        $page = Page::with(['details' => function ($query) use ($language) {
            $query->where('language_id', $language);
        }])->where('id', $id)->first();

        $data["sections"] = getPageSections();
        $data['pageEditableLanguage'] = Language::where('id', $language)->select('id', 'name', 'short_name')->first();
        $data['allLanguage'] = Language::select('id', 'name', 'short_name', 'flag', 'flag_driver')->where('status', 1)->get();
        return view("admin.frontend_management.page.edit", $data, compact('page', 'language', 'theme'));
    }

    public function update(Request $request, $id, $theme)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:100',
                function ($attribute, $value, $fail) use ($request, $id,$theme) {  // Pass the current page_id
                    $exists = DB::table('page_details')
                        ->join('pages', 'pages.id', '=', 'page_details.page_id')
                        ->where('pages.template_name', $theme)
                        ->where('page_details.name', $value)
                        ->where('page_details.page_id', '!=', $id)  // Exclude current record by its page_id
                        ->exists();

                    if ($exists) {
                        $fail('The name has already been taken.');
                    }
                }
            ],
            'slug' => ['required', 'min:1', 'max:100',
                new AlphaDashWithoutSlashes(),
                Rule::notIn(['login', 'register', 'signin', 'signup', 'sign-in', 'sign-up']),
                function ($attribute, $value, $fail) use ($id, $theme) {
                    $page = Page::where(['template_name'=> $theme ,'slug' => $value])->first();
                    if ($page && $page->id != $id) {
                        $fail('The slug has already been taken.');
                    }
                }
            ],
            'page_content' => 'required|string|min:3',
            'breadcrumb_status' => 'nullable|integer|in:0,1',
            'breadcrumb_image' => ($request->input('breadcrumb_status') == 1) ? 'sometimes|required|mimes:jpg,png,jpeg|max:4048' : 'nullable|mimes:jpg,png,jpeg|max:2048',
            'status' => 'nullable|integer|in:0,1',
        ]);


        if ($request->status == 0) {
            $menu = $request->name;
            $menuHeader = ManageMenu::where('menu_section', 'header')->first();
            $nestedArr = $menuHeader->menu_items;
            removeValue($nestedArr, strtolower($menu));
            $menuHeader->menu_items = $nestedArr;
            $menuHeader->save();


            $menuFooter = ManageMenu::where('menu_section', 'footer')->first();

            $nestedArr2 = $menuFooter->menu_items;
            removeValue($nestedArr2, strtolower($menu));
            $menuHeader->menu_items = $nestedArr2;
            $menuHeader->save();
        }

        try {
            $page = Page::findOrFail($id);
            $sections = preg_match_all('/\[\[([^\]]+)\]\]/', strtolower($request->page_content), $matches) ? $matches[1] : null;

            if ($request->hasFile('breadcrumb_image')) {
                $image = $this->fileUpload($request->breadcrumb_image, config('filelocation.pagesImage.path'), null, null, 'webp', 70, $page->breadcrumb_image, $page->breadcrumb_image_driver);
                throw_if(empty($image['path']), 'Image could not be uploaded.');
                $breadCrumbImage = $image['path'];
                $breadCrumbImageDriver = $image['driver'] ?? 'local';
            }

            $response = $page->update([
                "slug" => $request->slug,
                "template_name" => $theme,
                "breadcrumb_image" => $breadCrumbImage ?? $page->breadcrumb_image,
                "breadcrumb_image_driver" => $breadCrumbImageDriver ?? $page->breadcrumb_image_driver,
                "breadcrumb_status" => $request->breadcrumb_status,
                "status" => $request->status,
            ]);

            if (!$response) {
                throw new \Exception("Something went wrong, Please Try again");
            }

            $page->details()->updateOrCreate([
                'language_id' => $request->language_id,
            ],
                [
                    "name" => $request->name,
                    'content' => $request->page_content,
                    'sections' => $sections,
                ]
            );

            return redirect()->route('admin.page.index', $theme)->with('success', 'Page Updated Successfully');

        } catch (Exception $e) {
            if (isset($breadCrumbImage, $breadCrumbImageDriver))
                $this->fileDelete($breadCrumbImageDriver, $breadCrumbImage);
            return back()->with('error', $e->getMessage());
        }

    }

    public function delete(Request $request, $id)
    {
        $selectedTheme = basicControl()->theme;
        try {
            $page = Page::where('id', $id)->firstOr(function () {
                throw new \Exception('Something went wrong, Please try again');
            });

            $headerMenu = ManageMenu::where('menu_section', 'header')->where('template_name', $selectedTheme)->first();
            $footerMenu = ManageMenu::where('menu_section', 'footer')->where('template_name', $selectedTheme)->first();

            $lookingKey = $page->name;

            $headerMenu->update([
                'menu_items' => filterCustomLinkRecursive($headerMenu->menu_items, $lookingKey)
            ]);
            $footerMenu->update([
                'menu_items' => filterCustomLinkRecursive($footerMenu->menu_items, $lookingKey)
            ]);
            $this->fileDelete($page->meta_image_driver, $page->meta_image);
            $page->delete();
            $page->details()->delete();

            return back()->with('success', 'Page deleted successfully');

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function editStaticPage($id, $theme, $language = null)
    {
        $data['allLanguage'] = Language::select('id', 'name', 'short_name', 'flag', 'flag_driver')->where('status', 1)->get();
        $data['pageEditableLanguage'] = Language::where('id', $language)->select('id', 'name', 'short_name')->first();
        $data['page'] = Page::with(['details' => function ($query) use ($language) {
            $query->where('language_id', $language);
        }])->where('id', $id)->first();

        return view("admin.frontend_management.page.edit_static", $data);
    }

    public function updateStaticPage(Request $request, $id, $theme)
    {

        $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:100',
                Rule::unique('page_details', 'page_id')->ignore($id),
            ],
            'language_id' => 'required|integer|exists:languages,id',
            'breadcrumb_status' => 'nullable|integer|in:0,1',
            'breadcrumb_image' => ($request->input('breadcrumb_status') == 1) ? 'sometimes|required|mimes:jpg,png,jpeg|max:2048' : 'nullable|mimes:jpg,png,jpeg|max:2048',
            'status' => 'nullable|integer|in:0,1',
        ]);

        try {

            $page = Page::findOrFail($id);

            if ($request->hasFile('breadcrumb_image')) {
                $image = $this->fileUpload($request->breadcrumb_image, config('filelocation.pagesImage.path'), null, null, 'webp', 80, $page->breadcrumb_image, $page->breadcrumb_image_driver);
                throw_if(empty($image['path']), 'Image could not be uploaded.');
            }

            $response = $page->update([
                "breadcrumb_image" => $image['path'] ?? $page->breadcrumb_image,
                "breadcrumb_image_driver" => $image['driver'] ?? $page->breadcrumb_image_driver,
                "breadcrumb_status" => $request->breadcrumb_status,
                "status" => $request->status,
            ]);

            if (!$response) {
                throw new \Exception("Something went wrong, Please Try again");
            }

            $page->details()->updateOrCreate([
                'language_id' => $request->language_id,
            ],
                [
                    "name" => $request->name,
                ]
            );
            return redirect()->route('admin.page.index', $theme)->with('success', 'Static Page Updated Successfully');
        } catch (Exception $e) {
            if (isset($image['path'], $image['driver']))
                $this->fileDelete($image['driver'], $image['path']);
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateSlug(Request $request)
    {
        $rules = [
            "pageId" => "required|exists:pages,id",
            "newSlug" => ["required", "min:1", "max:100",
                new AlphaDashWithoutSlashes(),
                Rule::unique('pages', 'slug')->ignore($request->pageId),
                Rule::notIn(['login', 'register', 'signin', 'signup', 'sign-in', 'sign-up'])
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $pageId = $request->pageId;
        $newSlug = $request->newSlug;
        $page = Page::find($pageId);

        if (!$page) {
            return back()->with("error", "Page not found");
        }

        $page->update([
            'slug' => $newSlug
        ]);

        return response([
            'success' => true,
            'slug' => $page->slug
        ]);
    }

    public function pageSEO($id)
    {
        try {
            $data['page'] = Page::where('id', $id)
                ->select('id', 'name', 'page_title', 'meta_title', 'meta_keywords', 'meta_description', 'og_description', 'meta_robots', 'meta_image', 'meta_image_driver')
                ->firstOr(function () {
                    throw new \Exception('Page is not available.');
                });
            return view("admin.frontend_management.page.seo", $data);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function pageSeoUpdate(Request $request, $id)
    {
        $request->validate([
            'page_title' => 'required|string|min:3|max:100',
            'meta_title' => 'nullable|string|min:3|max:191',
            'meta_keywords' => 'nullable|array',
            'meta_keywords.*' => 'nullable|string|min:1|max:255',
            'meta_description' => 'nullable|string|min:1|max:500',
            'og_description' => 'nullable|string|min:1|max:500',
            'meta_robots' => 'nullable|array',
            'meta_robots.*' => 'nullable|string|min:1|max:255',
            'meta_image' => 'nullable|mimes:jpeg,png,jpeg|max:2048'
        ]);

        try {
            $page = Page::where('id', $id)
                ->select('id', 'name', 'page_title', 'meta_title', 'meta_keywords', 'meta_description', 'og_description', 'meta_robots', 'meta_image', 'meta_image_driver')
                ->firstOr(function () {
                    throw new \Exception('Page is not available.');
                });

            if ($request->hasFile('meta_image')) {
                $metaImage = $this->fileUpload($request->meta_image, config('filelocation.seo.path'), null, null, 'webp', 60, $page->meta_image, $page->meta_image_driver);
                throw_if(empty($metaImage['path']), 'Image path not found');
            }

            if ($request->meta_robots) {
                $meta_robots = implode(",", $request->meta_robots);
            }
            $response = $page->update([
                'page_title' => $request->page_title,
                'meta_title' => $request->meta_title,
                'meta_keywords' => $request->meta_keywords,
                'meta_description' => $request->meta_description,
                'og_description' => $request->og_description,
                'meta_robots' => $meta_robots ?? null,
                'meta_image' => $metaImage['path'] ?? $page->meta_image,
                'meta_image_driver' => $metaImage['driver'] ?? $page->meta_image_driver,
            ]);
            throw_if(!$response, 'Something went wrong, While updating insert data.');
            return back()->with('success', 'Page Seo has been updated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
