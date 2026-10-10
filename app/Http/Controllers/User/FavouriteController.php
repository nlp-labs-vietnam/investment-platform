<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use App\Models\ManageProperty;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FavouriteController extends Controller
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

    public function wishList(Request $request)
    {
        $userId = $this->user->id;
        $property = Property::with('getFavourite')->find($request->property_id);

        if ($property->getFavourite) {
            $stage='remove';
            $favourite = Favourite::where('property_id',$request->property_id)->where('client_id', $this->user->id)->first();
            $favourite->delete();

        } else {
            $stage ='added';
            $data = new Favourite();
            $data->client_id = $this->user->id;
            $data->property_id =$request->property_id;
            $data->save();
        }
        return response()->json([
            'data' => $stage
        ]);
    }

    public function wishListProperty(Request $request)
    {
        $search = $request->all();
        $fromDate = Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date)->addDay();

        $data['favourite_properties'] = Favourite::with(['get_property'])
            ->when(isset($search['name']), function ($query) use ($search) {
                    $query->whereHas('get_property', function ($q) use ($search){
                        $q->where('title', 'LIKE', "%{$search['name']}%");
                    });
            })
            ->when(isset($search['from_date']), function ($q2) use ($fromDate) {
                return $q2->whereDate('created_at', '>=', $fromDate);
            })
            ->when(isset($search['to_date']), function ($q2) use ($fromDate, $toDate) {
                return $q2->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->where('client_id', $this->user->id)
            ->latest()
            ->paginate(basicControl()->paginate);

        return view(template() . 'user.wishList', $data);
    }

    public function wishListDelete($id)
    {

        Favourite::where('client_id', $this->user->id)->findOrfail($id)->delete();
        return back()->with('success', __('Delete Successful!'));
    }
}
