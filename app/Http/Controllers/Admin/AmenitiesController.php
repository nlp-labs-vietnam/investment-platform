<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AmenitiesController extends Controller
{

    public function amenities()
    {
        return view('admin.property.amenity.amenities');
    }

    public function amenitiesSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];

        $filterTitle = $request->filterTitle;
        $filterStatus = $request->filterStatus;

        $amenities = Amenity::when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
            return $query->where('title', 'LIKE', "%{$search}%");
        })
            ->when(!empty($filterTitle), function ($query) use ($filterTitle) {
                $query->where('title', 'LIKE', "%{$filterTitle}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->orderBy('id', 'DESC');

        return DataTables::of($amenities)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('title', function ($item) {
                return $item->title;
            })
            ->addColumn('icon', function ($item) {
                return '<i class="' . $item->icon . '"></i>';

            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                  </span>';

                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('In Active') . '
                  </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $updateRoute = route('admin.amenities.edit', $item->id);
                $deleteRoute = route('admin.amenities.delete', $item->id);

                $update = '';
                $delete = '';

                if (adminAccessRoute(array_merge(config('permissionList.Manage_Property.Amenities.permission.edit'), config('permissionList.Manage_Property.Amenities.permission.delete')))) {
                    if (adminAccessRoute(config('permissionList.Manage_Property.Amenities.permission.edit'))) {
                        $update = "<a class='btn btn-white btn-sm updateSchedule' href='" . $updateRoute . "'>
                            <i class='bi bi-box-arrow-in-right dropdown-item-icon'></i>
                           " . trans("Edit") . "
                        </a>";
                    }

                    if (adminAccessRoute(config('permissionList.Manage_Property.Amenities.permission.delete'))) {
                        $delete = "<div class='btn-group'>
                      <button type='button' class='btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty' id='userEditDropdown' data-bs-toggle='dropdown' aria-expanded='false'></button>
                      <div class='dropdown-menu dropdown-menu-end mt-1' aria-labelledby='userEditDropdown'>
                        <a class='dropdown-item deleteAmenities' href='javascript:void(0)'
                           data-route='" . $deleteRoute . "'
                           data-bs-toggle='modal' data-bs-target='#deleteAmenitiesModal'>
                            <i class='bi bi-box-arrow-in-right dropdown-item-icon'></i>
                           " . trans("Delete") . "
                        </a>
                      </div>
                    </div>";
                    }
                } else {
                    $update = '-';
                }

                return "<div class='btn-group' role='group'>
                      $update
                      $delete
                  </div>";
            })
            ->rawColumns(['icon', 'status', 'action'])
            ->make(true);

    }

    public function amenitiesCreate()
    {
        return view('admin.property.amenity.create');
    }

    public function amenitiesStore(Request $request)
    {
        $rules = [
            'title' => 'required',
            'icon' => 'nullable',
        ];

        $message = [
            'title.required' => 'Title field is required'
        ];

        $this->validate($request, $rules, $message);


        $amenity = new Amenity();
        $amenity->title = $request->title;
        $amenity->icon = $request->icon ?? 'fa-regular fa-face-smile';
        $amenity->status = $request->status;
        $amenity->save();

        return back()->with('success', 'Amenity created successfully!');
    }

    public function amenitiesEdit($id)
    {
        $data['amenity'] = Amenity::findOrFail($id);
        return view('admin.property.amenity.edit', $data);
    }


    public function amenitiesUpdate(Request $request, $id)
    {
        $rules = [
            'title' => 'required',
            'icon' => 'nullable',
        ];

        $message = [
            'title.required' => 'Title field is required'
        ];

        $this->validate($request, $rules, $message);


        $amenity = Amenity::findOrFail($id);
        $amenity->title = $request->title;
        $amenity->icon = $request->icon ?? 'fa-regular fa-face-smile';
        $amenity->status = $request->status;
        $amenity->save();

        return back()->with('success', 'Amenity updated successfully!');
    }

    public function amenitiesDelete($id)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->delete();
        return back()->with('success', 'Amenity deleted successfully!');
    }

}
