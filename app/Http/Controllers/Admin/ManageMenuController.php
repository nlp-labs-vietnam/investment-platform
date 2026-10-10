<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\ManageMenu;
use App\Models\Page;
use App\Models\PageDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManageMenuController extends Controller
{
    public function manageMenu()
    {
        $selectedTheme = basicControl()->theme;
        $defaultLanguage = Language::where('default_status', true)->first();
        $data['pages'] = Page::with(['details' => function ($query) use ($defaultLanguage) {
            $query->where('language_id', $defaultLanguage->id);
        }])
            ->where('template_name', $selectedTheme)
            ->get();


        $data['languages'] = Language::orderBy('default_status', 'desc')->get();
        $data['headerMenus'] = ManageMenu::where('template_name', $selectedTheme)->where('menu_section', 'header')->first();
        $data['footerMenus'] = ManageMenu::where('template_name', $selectedTheme)->where('menu_section', 'footer')->first();

        return view('admin.frontend_management.manage-menu', $data);
    }

    public function headerMenuItemStore(Request $request)
    {
        $selectedTheme = basicControl()->theme;

        $request->validate([
            'menu_item' => 'nullable|array',
        ]);

        try {

            $menu = ManageMenu::firstOrNew(
                ['template_name' => $selectedTheme, 'menu_section' => 'header']
            );

            $menu->menu_items = $request->menu_item;

            if (!$menu->save()) {
                throw new \Exception('Something went wrong, please try again');
            }

            return back()->with('success', 'Header menu saved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function footerMenuItemStore(Request $request)
    {
        $selectedTheme = basicControl()->theme;

        $request->validate([
            'menu_item' => 'nullable|array',
        ]);

        try {
            $menu = ManageMenu::firstOrNew(
                ['template_name' => $selectedTheme, 'menu_section' => 'footer']
            );

            $menu->menu_items = $request->menu_item;

            if (!$menu->save()) {
                throw new \Exception('Something went wrong, please try again');
            }
            return back()->with('success', 'Footer menu saved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function addCustomLink(Request $request)
    {
        $selectedTTheme = basicControl()->theme;
        $rules = [
            'link_text' => 'required|string|min:2|max:100',
            'link' => 'required|url',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $addError = $validator->getMessageBag();
            $addError->add('errorMessage', 1);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pageForMenu = Page::create([
                'name' => strtolower($request->link_text),
                'slug' => slug($request->link_text),
                'template_name' => $selectedTTheme,
                'custom_link' => $request->link,
                'type' => 3
            ]);

            if (!$pageForMenu) {
                return back()->with('error', 'Something went wrong, when storing custom link data');
            }

            $defaultLanguage = Language::where('default_status', true)->first();
            $pageForMenu->details()->create([
                'language_id' => $defaultLanguage->id,
                'name' => $request->link_text,
            ]);

            return back()->with('success', 'Custom link added to the menu.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function updateCustomLink(Request $request, $id)
    {

        $rules = [
            'language_id' => 'required',
            'link_text.*' => 'required|max:100',
            'link.*' => 'required|max:100',
        ];

        $message = [
            'link_text.*.required' => 'This link text field is required.',
            'link.*.required' => 'This link field is required.',
        ];

        $language = $request->language_id;
        $inputData = $request->except('_token', '_method');
        $validate = Validator::make($inputData, $rules, $message);

        if ($validate->fails()) {
            $validate->errors()->add('errActive', $language);
            return back()->withInput()->withErrors($validate);
        }

        try {
            $customPage = Page::findOrFail($id);
            $response = $customPage->update([
                'custom_link' => $request->link[$language]
            ]);

            throw_if(!$response, 'Something went wrong while updating custom menu data.');

            if ($language != 0) {
                $pageDetails = PageDetail::updateOrCreate(
                    ['page_id' => $id, 'language_id' => $language],
                    ['page_id' => $id, 'language_id' => $language, 'name' => $request->link_text[$language]]
                );
            }
            throw_if(!$pageDetails, 'Something went wrong while updating custom menu data.');

            return back()->with('success', 'Custom menu updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteCustomLink(Request $request, $pageId)
    {
        $selectedTheme = basicControl()->theme;
        $customPage = Page::findOrFail($pageId);

        $headerMenu = ManageMenu::where('menu_section', 'header')->where('template_name', $selectedTheme)->first();
        $footerMenu = ManageMenu::where('menu_section', 'footer')->where('template_name', $selectedTheme)->first();

        $lookingKey = $customPage->name;

        $headerMenu->update([
            'menu_items' => filterCustomLinkRecursive($headerMenu->menu_items, $lookingKey)
        ]);

        $footerMenu->update([
            'menu_items' => filterCustomLinkRecursive($footerMenu->menu_items, $lookingKey)
        ]);

        $customPage->delete();
        return back()->with('success', 'Custom link deleted from the menu.');
    }

    public function getCustomLinkData(Request $request)
    {
        $pageId = $request->pageId;
        $languageId = $request->languageId;

        $customPage = PageDetail::with('page:id,name,custom_link')
            ->where('page_id', $pageId)
            ->where('language_id', $languageId)
            ->first();

        return response()->json([
            'name' => $customPage ? $customPage->name : '',
            'custom_link' => $customPage ? optional($customPage->page)->custom_link : ''
        ]);
    }
}
