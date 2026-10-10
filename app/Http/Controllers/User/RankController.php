<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function ranks()
    {
        $data['allRanks'] = Rank::where('status', 1)->orderBy('sort_by', 'ASC')->get();
        return view(template() . 'user.rank.index', $data);
    }
}
