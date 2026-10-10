<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends Controller
{
    public function subscribers()
    {
        return view('admin.subscriber.index');
    }

    public function subscriberSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];

        $filterEmail = $request->filterEmail;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $subscribers = Subscriber::when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
            return $query->where('email', 'LIKE', "%{$search}%");
        })
            ->when(!empty($filterEmail), function ($query) use ($filterEmail) {
                $query->where('email', 'LIKE', "%{$filterEmail}%");
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->orderBy('id', 'DESC');

        return DataTables::of($subscribers)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('email', function ($item) {
                return $item->email;
            })
            ->addColumn('subscriber_at', function ($item) {
                return dateTime($item->created_at, 'd M Y h:i A');
            })
            ->addColumn('action', function ($item) {
                $deleteRoute = route('admin.subscriber.delete', $item->id);

                $delete = '';
                if (adminAccessRoute(config('permissionList.Subscribers.Subscriber_List.permission.delete'))) {
                    $delete = "<a class='dropdown-item deleteSubscriber' href='javascript:void(0)'
                           data-route='" . $deleteRoute . "'
                           data-bs-toggle='modal' data-bs-target='#deleteSubscriberModal'>
                            <i class='bi bi-box-arrow-in-right dropdown-item-icon'></i>
                           " . trans("Delete") . "
                        </a>";
                }else{
                    $delete = '-';
                }


                return "<div class='btn-group' role='group'>
                      $delete
                  </div>";
            })
            ->rawColumns(['status', 'action'])
            ->make(true);

    }

    public function subscriberDelete($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();
        return back()->with('success', 'Subscriber deleted successfully!');
    }
}
