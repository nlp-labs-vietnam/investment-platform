<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogDetails;
use App\Models\Content;
use App\Models\Page;
use App\Models\PageDetail;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blog()
    {
        $data['pageSeo'] = Page::where('name', 'blog')->select('page_title')->first();
        $data['content'] = Content::with('contentDetails')->where('name','blog')
            ->where('type', 'single')
            ->first();
        $data['blogs'] = Blog::with('category', 'details')->orderBy('id', 'desc')->get();
        return view('themes.light.blog', $data);
    }

    public function blogDetails($slug)
    {
        dd('asdf');
        $data['blogDetails'] = BlogDetails::with('blog')->where('slug', $slug)->first();
        return view('themes.light.blog_details', $data);
    }
}
