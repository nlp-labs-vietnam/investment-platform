<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $data['blogCategory'] = BlogCategory::orderBy('id', 'desc')->paginate(10);
        return view('admin.blog_category.list', $data);
    }

    public function create()
    {
        return view('admin.blog_category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
                'name' => 'required',
        ]);
        try {
            $response = BlogCategory::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);
            throw_if(!$response, 'Something went wrong while storing blog category data. Please try again later.');
            return back()->with('success', 'Blog category save successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $data['blogCategory'] = BlogCategory::where('id', $id)->firstOr(function () {
                throw new \Exception('No blog category data found.');
            });
            return view('admin.blog_category.edit', $data);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
        try {
            $blogCategory = BlogCategory::where('id', $id)->firstOr(function () {
                throw new \Exception('No blog category data found.');
            });

            $response = $blogCategory->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);
            throw_if(!$response, 'Something went wrong while storing blog category data. Please try again later.');
            return back()->with('success', 'Blog category save successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $blogCategory = BlogCategory::where('id', $id)->firstOr(function () {
                throw new \Exception('No blog category data found.');
            });
            $blogCategory->delete();
            return redirect()->back()->with('success', 'Blog category deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
