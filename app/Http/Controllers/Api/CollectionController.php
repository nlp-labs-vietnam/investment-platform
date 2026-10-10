<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use App\Models\Property;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
    use ApiResponse;
    public function wishlist(Request $request)
    {
        $collections = Favourite::query()
            ->with(['get_property'])
            ->where('client_id', Auth::id())
            ->latest()
            ->paginate(10);

        $formattedCollections = $collections->map(function ($collection, $key){
           return [
               'SL' => $key + 1,
               'id' => $collection->id,
               'property_id' => $collection->property_id,
               'property' => $collection->get_property ? [
                   'title' => $collection->get_property->title,
                   'image' => getFile($collection->get_property->driver, $collection->get_property->thumbnail),
               ] : null,
               'date' => customDateTime($collection->created_at),
           ];
        });

        $collections->setCollection($formattedCollections);

        return response()->json($this->withSuccess($collections));
    }


    public function addWishlist(Request $request)
    {
        $rules = [
          'property_id' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()){
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $property = Property::with('getFavourite')->find($request->property_id);

        if (!$property->getFavourite){
            $user = \auth()->user();
            $data = new Favourite();
            $data->client_id = $user->id;
            $data->property_id =$request->property_id;
            $data->save();
            return response()->json($this->withSuccess('Wishlist added successfully!'));
        } else{
            return response()->json($this->withError('Already added to wishlist'));
        }
    }


    public function removeWishlist(Request $request)
    {
        $rules = [
            'property_id' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()){
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $property = Property::with('getFavourite')->find($request->property_id);

        if ($property->getFavourite){
            $user = \auth()->user();
            $favourite = Favourite::where('property_id',$request->property_id)->where('client_id', $user->id)->first();
            $favourite->delete();
            return response()->json($this->withSuccess('Wishlist removed successfully!'));
        } else{
            return response()->json($this->withError('You have not added it to wishlist'));
        }
    }

    public function wishListDelete($id)
    {
        $user = \auth()->user();
        $favourite = Favourite::where('client_id', $user->id)->find($id);

        if (!$favourite){
            return response()->json($this->withError('wishlist not found'));
        }

        $favourite->delete();

        return response()->json($this->withError('wishlist deleted successfully!'));
    }

}
