<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Address;
use App\Models\Amenity;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\ContentDetails;
use App\Models\InvestorReview;
use App\Models\Page;
use App\Models\PageDetail;
use App\Models\Property;
use App\Models\Subscriber;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller
{
    use Frontend, ApiResponse;

    public function page($slug = '/')
    {

        try {
            $connection = DB::connection()->getPdo();
            $selectedTheme = session()->get('active_theme') ?? basicControl()->theme ?? 'light';

            // Normalize: route /{slug?} passes null when visiting /, DB stores slug as '/'
            if ($slug === null || $slug === '') {
                $slug = '/';
            }

            $existingSlugs = collect([]);
            DB::table('pages')->where('template_name', $selectedTheme)->select('slug')->get()->map(function ($item) use ($existingSlugs) {
                $existingSlugs->push($item->slug);
            });
            if (!in_array($slug, $existingSlugs->toArray())) {
                abort(404);
            }

            // Query page details for the current locale (Translatable global scope applied).
            $pageDetails = PageDetail::with('page')
                ->whereHas('page', function ($query) use ($slug, $selectedTheme) {
                    $query->where(['slug' => $slug, 'template_name' => $selectedTheme]);
                })
                ->first();

            // Safety net: if the current locale has no translation yet,
            // fall back to any available language so the page still renders.
            if (!$pageDetails) {
                $pageDetails = PageDetail::withoutGlobalScope('language')
                    ->with('page')
                    ->whereHas('page', function ($query) use ($slug, $selectedTheme) {
                        $query->where(['slug' => $slug, 'template_name' => $selectedTheme]);
                    })
                    ->orderBy('language_id') // prefer lower id (typically English)
                    ->first();
            }

            $pageSeo = [
                'page_title' => optional(optional($pageDetails)->page)->page_title ?? '',
                'meta_title' => optional(optional($pageDetails)->page)->meta_title,
                'meta_keywords' => implode(',', optional(optional($pageDetails)->page)->meta_keywords ?? []),
                'meta_description' => optional(optional($pageDetails)->page)->meta_description,
                'og_description' => optional(optional($pageDetails)->page)->og_description,
                'meta_robots' => optional(optional($pageDetails)->page)->meta_robots,
                'meta_image' => $pageDetails?->page
                    ? getFile($pageDetails->page->meta_image_driver, $pageDetails->page->meta_image)
                    : null,
                'breadcrumb_image' => $pageDetails?->page?->breadcrumb_status
                    ? getFile($pageDetails->page->breadcrumb_image_driver, $pageDetails->page->breadcrumb_image)
                    : null,
            ];

            if (!$pageDetails) {
                // No page_details for this slug — redirect to homepage instead of looping
                return redirect('/');
            }

            $sectionsData = $this->getSectionsData($pageDetails->sections, $pageDetails->content, $selectedTheme);
            return view("themes.{$selectedTheme}.page", compact('sectionsData', 'pageSeo'));

        } catch (\Exception $exception) {
            $this->handleDatabaseException($exception);
        }
    }

    public function blog()
    {
        $data['categories'] = BlogCategory::get();
        $data['pageSeo'] = Page::where('name', 'blog')->first();
        $data['pageSeo']['breadcrumb_image'] = getFile($data['pageSeo']['breadcrumb_image_driver'], $data['pageSeo']['breadcrumb_image']);
        $data['blogs'] = Blog::with('category', 'details')->where('status', 1)->latest()->get();

        $section = ['blog'];
        $selectedTheme = basicControl()->theme;
        $data['blogSingleContent'] = ContentDetails::with('content')
            ->whereHas('content', function ($query) use ($section, $selectedTheme) {
                $query->whereIn('name', $section)->where('theme', $selectedTheme);
            })
            ->first();

        return view(template() . 'blog', $data);
    }

    public function CategoryWiseBlog($id, $slug = null)
    {
        $data['categories'] = BlogCategory::get();
        $data['pageSeo'] = Page::where('name', 'blog')->first();
        $data['pageSeo']['breadcrumb_image'] = getFile($data['pageSeo']['breadcrumb_image_driver'], $data['pageSeo']['breadcrumb_image']);

        $data['blogs'] = Blog::with(['details', 'category'])
            ->where('category_id', $id)->where('status', 1)->latest()->paginate(3);

        $section = ['blog'];
        $selectedTheme = basicControl()->theme;
        $data['blogSingleContent'] = ContentDetails::with('content')
            ->whereHas('content', function ($query) use ($section, $selectedTheme) {
                $query->whereIn('name', $section)->where('theme', $selectedTheme);
            })
            ->first();

        return view(template() . 'blog', $data);
    }

    public function blogDetails($id, $slug = null)
    {
        $data['categories'] = BlogCategory::with('blog')->withCount('blog')->get();
        $data['pageSeo'] = Page::where('name', 'blog')->first();
        $data['pageSeo']['breadcrumb_image'] = getFile($data['pageSeo']['breadcrumb_image_driver'], $data['pageSeo']['breadcrumb_image']);
        $data['singleBlog'] = Blog::with('details')->where('status', 1)->findOrFail($id);

        $data['relatedBlogs'] = Blog::with(['details', 'category'])->where('id', '!=', $id)->where('category_id', $data['singleBlog']->category_id)->where('status', 1)->limit(3)->get();

        return view(template() . 'blog_details', $data);
    }

    public function blogSearch(Request $request)
    {
        $data['categories'] = BlogCategory::get();
        $data['pageSeo'] = Page::where('name', 'blog')->first();
        $data['pageSeo']['breadcrumb_image'] = getFile($data['pageSeo']['breadcrumb_image_driver'], $data['pageSeo']['breadcrumb_image']);

        $section = ['blog'];
        $selectedTheme = basicControl()->theme;
        $data['blogSingleContent'] = ContentDetails::with('content')
            ->whereHas('content', function ($query) use ($section, $selectedTheme) {
                $query->whereIn('name', $section)->where('theme', $selectedTheme);
            })
            ->first();

        $search = $request->search;

        $data['blogs'] = Blog::with('details', 'category')
            ->whereHas('category', function ($qq) use ($search) {
                $qq->where('name', 'Like', '%' . $search . '%');
            })
            ->orWhereHas('details', function ($qq2) use ($search) {
                $qq2->where('title', 'Like', '%' . $search . '%');
                $qq2->orWhere('description', 'Like', '%' . $search . '%');
            })
            ->where('status', 1)
            ->latest()->paginate(3);

        return view(template() . 'blog', $data);
    }

    public function property(Request $request, $type = null, $id = null)
    {
        $data['pageSeo'] = Page::where('name', 'property')->first();
        if ($data['pageSeo']) {
            $data['pageSeo']['breadcrumb_image'] = getFile($data['pageSeo']['breadcrumb_image_driver'], $data['pageSeo']['breadcrumb_image']);
        }
        $data['addresses'] = Address::where('status', 1)->get();
        $data['amenities'] = Amenity::where('status', 1)->get();

        $funding = Property::selectRaw('min(available_funding) as min_funding, max(available_funding) as max_funding')->first();
        $min = $funding->min_funding;
        $max = $funding->max_funding;
        $minRange = $min;
        $maxRange = $max;

        if ($request->has('my_range')) {
            $range = explode(';', $request->my_range);
            $minRange = $range[0];
            $maxRange = $range[1];
        }

        $search = $request->all();

        $data['properties'] = Property::query()
            ->with(['address', 'managetime', 'favoritedByUser'])
            ->where('status', 1)
            ->whereDate('expire_date', '>', now())
            ->when(isset($search['name']), function ($query) use ($search) {
                return $query->whereRaw("title REGEXP '[[:<:]]{$search['name']}[[:>:]]'");
            })
            ->when(isset($search['location']), function ($query) use ($search) {
                return $query->whereHas('address', function ($query2) use ($search) {
                    $query2->where('id', 'LIKE', "%{$search['location']}%");
                });
            })
            ->when(isset($search['amenity_id']), function ($query) use ($search) {
                return $query->whereJsonContains('amenity_id', $search['amenity_id']);
            })
            ->when(!empty($search['rating']), function ($query) use ($search) {
                return $query->whereHas('getReviews', function ($query2) use ($search) {
                    $query2->whereIn('rating2', $search['rating']);
                });
            })
            ->when(isset($search['my_range']), function ($query) use ($search, $minRange, $maxRange) {
                $query->whereBetween('total_investment_amount', [$minRange, $maxRange]);
            })
            ->orderBy('properties.start_date')
            ->paginate(6);

        return view(template() . 'property', $data, compact('min', 'max', 'minRange', 'maxRange'));
    }

    public function propertyDetails($id = null, $title = null)
    {
        $data['pageSeo'] = Page::where('name', 'property')->first();
        $data['pageSeo']['page_title'] = 'Property Details';
        $data['pageSeo']['breadcrumb_image'] = getFile($data['pageSeo']['breadcrumb_image_driver'], $data['pageSeo']['breadcrumb_image']);

        $data['singlePropertyDetails'] = Property::query()
            ->with(['address', 'image'])
            ->where('status', 1)
            ->findOrFail($id);
        $data['property'] = $data['singlePropertyDetails'];


        $data['investors'] = User::query()
            ->whereHas('invests', function ($query) use ($id) {
                $query->where('property_id', $id);
            })
            ->withCount('invests')
            ->get();
        $investor = $data['investors']->pluck('id')->toArray();

        $data['latestProperties'] = Property::query()
            ->with('address')
            ->where('status', 1)
            ->whereDate('expire_date', '>', now())
            ->inRandomOrder()->limit(3)->orderBy('start_date')->get();

        if (Auth::check()) {
            $data['reviewDone'] = InvestorReview::where('property_id', $id)->where('user_id', Auth::user()->id)->count();
        } else {
            $data['reviewDone'] = '0';
        }

        $data['all_reviews'] = InvestorReview::with('review_user_info')->where('property_id', $id)->get();
        $data['totalReview'] = $data['all_reviews']->count();
        $data['average_review'] = $data['all_reviews']->avg('rating2');

        return view(template() . 'property_details', $data, compact('investor'));
    }

    public function reviewPush(Request $request)
    {
        $review = new InvestorReview();
        $review->property_id = $request->propertyId;
        $review->user_id = auth()->id();
        $review->rating2 = $request->rating;
        $review->review = $request->feedback;
        $review->save();

        $data['review'] = $review->review;
        $data['review_user_info'] = $review->review_user_info;
        $data['rating2'] = $review->rating2;
        $data['date_formatted'] = dateTime($review->created_at, 'd M, Y h:i A');

        return response([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getReview($id)
    {
        $data = InvestorReview::with('review_user_info')->where('property_id', $id)->latest()->paginate(10);
        return response([
            'data' => $data
        ]);
    }

    public function investorProfile($id = null, $username = null)
    {
        $data['pageSeo'] = Page::where('name', 'property')->first();
        $data['pageSeo']['page_title'] = 'Investor Profile';
        $data['pageSeo']['breadcrumb_image'] = getFile($data['pageSeo']['breadcrumb_image_driver'], $data['pageSeo']['breadcrumb_image']);

        $data['investorInfo'] = User::findOrFail($id);

        $data['properties'] = Property::query()
            ->where('status', 1)
            ->where('expire_date', '>', now())
            ->with(['address', 'managetime'])
            ->whereHas('getInvestment', function ($query) use ($id) {
                $query->where('user_id', $id);
            })
            ->latest()
            ->paginate(20);


        return view(template() . 'investor_profile', $data);
    }

    public function subscribe(Request $request)
    {
        $validationRules = [
            'email' => 'required|email|min:8|max:100|unique:subscribes',
        ];

        $validate = Validator::make($request->all(), $validationRules);

        if ($validate->fails()) {
            session()->flash('error', 'Email Field is required');
            return back()->withErrors($validate)->withInput();
        }

        $subscribe = new Subscriber();
        $subscribe->email = $request->email;
        $subscribe->save();

        return back()->with('success', 'Subscribed successfully');
    }

    public function contactSend(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:91',
            'subject' => 'required|max:100',
            'message' => 'required|max:1000',
        ]);

        $name = $request->name;
        $email_from = $request->email;
        $subject = $request->subject;
        $message = $request->message . "<br>Regards<br>" . $name;
        $from = $email_from;

        Mail::to(basicControl()->sender_email)->send(new SendMail($from, $subject, $message));
        return back()->with('success', 'Mail has been sent');
    }
}
