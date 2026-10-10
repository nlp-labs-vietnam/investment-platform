<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\ManageMenu;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManageMenuControllerBackup extends Controller
{
    public function manageMenu()
    {
        $defaultLanguage = Language::where('default_status', true)->first();
        $data['pages'] = Page::with(['details' => function ($query) use ($defaultLanguage) {
            $query->where('language_id', $defaultLanguage->id);
        }])->get();
        $data['headerMenus'] = ManageMenu::where('menu_section', 'header')->firstOrFail();
        $data['footerMenus'] = ManageMenu::where('menu_section', 'footer')->firstOrFail();
        return view('admin.frontend_management.manage-menu', $data);
    }

    public function headerMenuItemStore(Request $request)
    {
        $request->validate([
            'menu_item' => 'nullable|array',
        ]);

        try {
            $menu = ManageMenu::where('menu_section', 'header')->firstOrFail();

            $response = $menu->update([
                'menu_items' => $request->menu_item
            ]);

            if (!$response) {
                throw new \Exception('Something went something, Please try again');
            }
            return back()->with('success', 'Header menu saved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function footerMenuItemStore(Request $request)
    {
        $request->validate([
            'menu_item' => 'nullable|array',
        ]);
        try {
            $menu = ManageMenu::where('menu_section', 'footer')->firstOrFail();

            $response = $menu->update([
                'menu_items' => $request->menu_item
            ]);

            if (!$response) {
                throw new \Exception('Something went something, Please try again');
            }
            return back()->with('success', 'Footer menu saved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function addCustomLink(Request $request)
    {
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
                'slug' => null,
                'custom_link' => $request->link,
                'type' => 3
            ]);

            if (!$pageForMenu) {
                return back()->with('error', 'Something went wrong, when storing custom link data');
            }

            return back()->with('success', 'Custom link added to the menu.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteCustomLink(Request $request, $pageId)
    {

        $customPage = Page::findOrFail($pageId);

        $headerMenu = ManageMenu::where('menu_section', 'header')->first();
        $footerMenu = ManageMenu::where('menu_section', 'footer')->first();

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

}
