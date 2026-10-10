<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AddressController extends Controller
{
    public function addresses()
    {
        return view('admin.property.address.addresses');
    }

    public function addressSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];

        $filterAddress = $request->filterAddress;
        $filterStatus = $request->filterStatus;

        $addresses = Address::when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
            return $query->where('title', 'LIKE', "%{$search}%");
        })
            ->when(!empty($filterAddress), function ($query) use ($filterAddress) {
                $query->where('title', 'LIKE', "%{$filterAddress}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->orderBy('id', 'DESC');

        return DataTables::of($addresses)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('address', function ($item) {
                return $item->title;
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
                $updateRoute = route('admin.address.edit', $item->id);
                $deleteRoute = route('admin.address.delete', $item->id);

                $update = '';
                $delete = '';

                if (adminAccessRoute(array_merge(config('permissionList.Manage_Property.Address.permission.edit'), config('permissionList.Manage_Property.Address.permission.delete')))) {
                    if (adminAccessRoute(config('permissionList.Manage_Property.Address.permission.edit'))) {
                        $update = "<a class='btn btn-white btn-sm updateSchedule' href='" . $updateRoute . "'>
                            <i class='bi bi-box-arrow-in-right dropdown-item-icon'></i>
                           " . trans("Edit") . "
                        </a>";
                    }

                    if (adminAccessRoute(config('permissionList.Manage_Property.Address.permission.delete'))) {
                        $delete = "<div class='btn-group'>
                      <button type='button' class='btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty' id='userEditDropdown' data-bs-toggle='dropdown' aria-expanded='false'></button>
                      <div class='dropdown-menu dropdown-menu-end mt-1' aria-labelledby='userEditDropdown'>
                        <a class='dropdown-item deleteAddress' href='javascript:void(0)'
                           data-route='" . $deleteRoute . "'
                           data-bs-toggle='modal' data-bs-target='#deleteAddressModal'>
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
            ->rawColumns(['status', 'action'])
            ->make(true);

    }

    public function addressCreate()
    {
        return view('admin.property.address.create');
    }

    public function addressStore(Request $request)
    {
        $rules = [
            'title' => 'required',
        ];

        $message = [
            'title.required' => 'Address Title field is required'
        ];

        $this->validate($request, $rules, $message);


        $address = new Address();
        $address->title = $request->title;
        $address->status = $request->status;
        $address->save();

        return back()->with('success', 'Address created successfully!');
    }

    public function addressEdit($id)
    {
        $data['address'] = Address::findOrFail($id);
        return view('admin.property.address.edit', $data);
    }

    public function addressUpdate(Request $request, $id)
    {
        $rules = [
            'title' => 'required',
        ];

        $message = [
            'title.required' => 'Address Title field is required'
        ];

        $this->validate($request, $rules, $message);

        $address = Address::findOrFail($id);
        $address->title = $request->title;
        $address->status = $request->status;
        $address->save();

        return back()->with('success', 'Address updated successfully!');
    }

    public function addressDelete($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();
        return back()->with('success', 'Address deleted successfully!');
    }
}
