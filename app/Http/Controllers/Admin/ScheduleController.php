<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManageTime;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
    public function profitSchedule()
    {
        return view('admin.property.profit.schedule');
    }

    public function profitScheduleSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];

        $filterDuration = $request->filterDuration;
        $filterDurationType = $request->filterDurationType;
        $filterStatus = $request->filterStatus;

        $profitSchedules = ManageTime::when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
            return $query->where('time', 'LIKE', "%{$search}%")
                ->orWhere('time_type', 'LIKE', "%{$search}");
            })
            ->when(!empty($filterDuration), function ($query) use ($filterDuration) {
                $query->where('time', 'LIKE', "%{$filterDuration}%");
            })
            ->when(!empty($filterDurationType), function ($query) use ($filterDurationType) {
                $query->where('time_type', 'LIKE', "%{$filterDurationType}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->orderBy('time_type', 'ASC');

        return DataTables::of($profitSchedules)
            ->addColumn('duration', function ($item) {
                return $item->time;
            })
            ->addColumn('duration_type', function ($item) {
                return ($item->time_type == 'days' && $item->time == 1) ? __('Day') :
                    (($item->time_type == 'months' && $item->time == 1) ? __('Month') :
                        (($item->time_type == 'years' && $item->time == 1) ? __('Year') : $item->time_type));

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
                $updateRoute = route('admin.profit.schedule.update', $item->id);
                $deleteRoute = route('admin.profit.schedule.delete', $item->id);

                $update = '';
                $delete = '';

                if (adminAccessRoute(array_merge(config('permissionList.Manage_Property.Profit_Schedule.permission.edit'), config('permissionList.Manage_Property.Profit_Schedule.permission.delete')))) {
                    if (adminAccessRoute(config('permissionList.Manage_Property.Profit_Schedule.permission.edit'))) {
                        $update = "<a class='btn btn-white btn-sm updateSchedule' href='javascript:void(0)'
                           data-route='" . $updateRoute . "'
                           data-schedule='" . $item . "'
                           data-bs-toggle='modal' data-bs-target='#updateScheduleModal'>
                            <i class='bi bi-box-arrow-in-right dropdown-item-icon'></i>
                           " . trans("Update") . "
                        </a>";
                    }

                    if (adminAccessRoute(config('permissionList.Manage_Property.Profit_Schedule.permission.delete'))) {
                        $delete = "<div class='btn-group'>
                      <button type='button' class='btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty' id='userEditDropdown' data-bs-toggle='dropdown' aria-expanded='false'></button>
                      <div class='dropdown-menu dropdown-menu-end mt-1' aria-labelledby='userEditDropdown'>
                        <a class='dropdown-item deleteSchedule' href='javascript:void(0)'
                           data-route='" . $deleteRoute . "'
                           data-bs-toggle='modal' data-bs-target='#deleteScheduleModal'>
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


    public function profitScheduleStore(Request $request){
        $rules = [
          'time' => 'required|integer|min:1',
          'time_type' => 'required',
          'status' => 'required',
        ];

        $message = [
            'time.required' => 'Duration time is required',
            'time_type.required' => 'Duration time type is required',
        ];

        $this->validate($request, $rules, $message);

        $profitSchedule = new ManageTime();
        $profitSchedule->time = $request->time;
        $profitSchedule->time_type = $request->time_type;
        $profitSchedule->status = $request->status;
        $profitSchedule->save();

        return back()->with('success', 'Profit schedule created successfully!');
    }


    public function profitScheduleUpdate(Request $request, $id){
        $rules = [
            'time' => 'required|integer|min:1',
            'time_type' => 'required',
            'status' => 'required',
        ];

        $message = [
            'time.required' => 'Duration time is required',
            'time_type.required' => 'Duration time type is required',
        ];

        $this->validate($request, $rules, $message);

        $profitSchedule = ManageTime::findOrFail($id);
        $profitSchedule->time = $request->time;
        $profitSchedule->time_type = $request->time_type;
        $profitSchedule->status = $request->status;
        $profitSchedule->save();

        return back()->with('success', 'Profit schedule updated successfully!');
    }

    public function profitScheduleDelete(Request $request, $id){
        $profitSchedule = ManageTime::findOrFail($id);
        $profitSchedule->delete();
        return back()->with('success', 'Profit Schedule Deleted Successfully!');
    }


}
