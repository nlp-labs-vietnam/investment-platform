<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InvestmentController extends Controller
{
    public function investments()
    {
        return view('admin.investment.investments');
    }

    public function investmentSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];

        $filterUser = $request->filterUser;
        $filterProperty = $request->filterProperty;
        $filterPayment = $request->filterPayment;
        $filterProfit = $request->filterProfit;
        $filterInvestment = $request->filterInvestment;

        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $investments = Investment::with('property', 'user')
            ->when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhere('username', 'LIKE', "%{$search}%");
                })->orWhereHas('property', function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($filterUser), function ($query) use ($filterUser) {
                $query->whereHas('user', function ($q) use ($filterUser) {
                    $q->where('firstname', 'LIKE', "%{$filterUser}%")
                        ->orWhere('lastname', 'LIKE', "%{$filterUser}%")
                        ->orWhere('username', 'LIKE', "%{$filterUser}%");
                });
            })
            ->when(!empty($filterProperty), function ($query) use ($filterProperty) {
                $query->whereHas('property', function ($q) use ($filterProperty) {
                    $q->where('title', 'LIKE', "%{$filterProperty}%");
                });
            })
            ->when(isset($filterPayment), function ($query) use ($filterPayment) {
                if ($filterPayment == 'all') {
                    return $query->where('invest_status', '!=', null);
                }
                return $query->where('invest_status', $filterPayment);
            })
            ->when(isset($filterProfit), function ($query) use ($filterProfit) {
                if ($filterProfit == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterProfit);
            })
            ->when(isset($filterInvestment), function ($query) use ($filterInvestment) {
                if ($filterInvestment == 'all') {
                    return $query->where('is_active', '!=', null);
                }
                return $query->where('is_active', $filterInvestment);
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
            ->orderBy('id', 'ASC');


        return DataTables::of($investments)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('property', function ($item) {
                return $item->property?->title;
            })
            ->addColumn('user', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('investment', function ($item) {
                return currencyPosition($item->amount);
            })
            ->addColumn('profit', function ($item) {
                return $item->invest_status == 1 ? currencyPosition($item->net_profit) : 'N/A';
            })
            ->addColumn('upcoming_payment', function ($item) {
                if ($item->invest_status == 0) {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="bg-danger"></span>' . trans('after installments complete') . '
                  </span>';
                } else {
                    return '<span> ' . dateTime($item->return_date) . ' </span>';
                }
            })
            ->addColumn('profit_status', function ($item) {
                if ($item->status == 1 && $item->invest_status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>' . trans('Completed') . '
                  </span>';

                } elseif ($item->status == 0 && $item->invest_status == 0) {
                    return '<span class="badge bg-soft-warning text-warning">
                    <span class="bg-warning"></span>' . trans('Upcoming') . '
                  </span>';
                } elseif ($item->status == 0 && $item->invest_status == 1) {
                    return '<span class="badge bg-soft-primary text-primary">
                    <span class="bg-primary"></span>' . trans('Running') . '
                  </span>';
                }
            })
            ->addColumn('investment_status', function ($item) {
                if ($item->is_active == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>' . trans('Active') . '
                  </span>';

                } else {
                    return '<span class="badge bg-soft-warning text-warning">
                    <span class="bg-warning"></span>' . trans('Deactive') . '
                  </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $investActive = route('admin.investment.active', $item->id);
                $investDeactive = route('admin.investment.deactive', $item->id);
                $InvestmentDetailsRoute = route('admin.investment.details', $item->id);

                $update = '';

                if (adminAccessRoute(config('permissionList.Manage_Investment.All_Investment.permission.edit'))) {
                    if ($item->is_active == 0){
                        $update = "<a class='btn btn-white btn-sm investmentActive' href='javascript:void(0)' data-route='" . $investActive . "'
                           data-bs-toggle='modal' data-bs-target='#investmentActiveModal'>
                            <i class='fa fa-toggle-on pr-2 text-success dropdown-item-icon'></i>
                           " . trans("Active") . "
                        </a>";
                    }
                    else {
                        $update = "<a class='btn btn-white btn-sm investmentDeactive' href='javascript:void(0)' data-route='" . $investDeactive . "'
                           data-bs-toggle='modal' data-bs-target='#investmentDeactiveModal'>
                            <i class='fa fa-toggle-on pr-2 text-muted dropdown-item-icon'></i>
                           " . trans("Deactive") . "
                        </a>";
                    }
                }

                $InvestmentDetails = "<div class='btn-group'>
                      <button type='button' class='btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty' id='userEditDropdown' data-bs-toggle='dropdown' aria-expanded='false'></button>
                      <div class='dropdown-menu dropdown-menu-end mt-1' aria-labelledby='userEditDropdown'>
                        <a class='dropdown-item deleteProperty' href='" . $InvestmentDetailsRoute . "'>
                            <i class='fal fa-eye dropdown-item-icon'></i>
                           " . trans("Investment Details") . "
                        </a>
                      </div>
                    </div>";

                return "<div class='btn-group' role='group'>
                      $update
                      $InvestmentDetails
                  </div>";
            })
            ->rawColumns(['user', 'upcoming_payment', 'profit_status', 'investment_status', 'action'])
            ->make(true);
    }


    public function investmentDetails($id)
    {
        $data['singleInvestDetails'] = Investment::with('property')->findOrFail($id);
        return view('admin.investment.investmentDetails', $data);
    }

    public function investmentActive(Request $request, $id)
    {
        $investment = Investment::findOrFail($id);
        $investment->is_active = 1;
        $investment->save();

        return back()->with('success', 'Investment Activated Successfully');
    }

    public function investmentDeactive(Request $request, $id)
    {
        $investment = Investment::findOrFail($id);
        $investment->is_active = 0;
        $investment->save();

        return back()->with('success', 'Investment Deactivated Successfully');
    }


    public function runningInvestments()
    {
        return view('admin.investment.runningInvestment');
    }

    public function runningInvestmentSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];
        $filterProperty = $request->filterProperty;

        $runningInvestments = Investment::with('property', 'user')
            ->join('properties', 'properties.id', '=', 'investments.property_id') // Join properties table
            ->where('properties.expire_date', '>', now())
            ->where('investments.status', 0)
            ->where('investments.is_active', 1)
            ->when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->whereHas('property', function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($filterProperty), function ($query) use ($filterProperty) {
                $query->whereHas('property', function ($q) use ($filterProperty) {
                    $q->where('title', 'LIKE', "%{$filterProperty}%");
                });
            })
            ->groupBy('investments.property_id')
            ->orderBy('properties.id', 'asc')
            ->get();

        return DataTables::of($runningInvestments)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('property', function ($item) {
                return $item->property->title;
            })
            ->addColumn('investment_expire_date', function ($item) {
                return dateTime(optional($item->property)->expire_date);
            })
            ->addColumn('invested_user', function ($item) {
                return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>' . optional($item->property)->totalRunningInvestUserAndAmount()['totalInvestedUser'] . '
                  </span>';
            })
            ->addColumn('total_investment_amount', function ($item) {
                return currencyPosition(optional($item->property)->totalRunningInvestUserAndAmount()['totalInvestedAmount']) ;
            })
            ->addColumn('need_investment_amount', function ($item) {
                return currencyPosition(optional($item->property)->total_investment_amount);
            })
            ->addColumn('action', function ($item) {
                $detailsRoute = route('admin.running.investment.details', optional($item->property)->id);

                $details = "<a class='btn btn-white btn-sm investmentActive' href='" . $detailsRoute . "'>
                            <i class='fal fa-eye dropdown-item-icon'></i>
                           " . trans("Details") . "
                        </a>";

                return "<div class='btn-group' role='group'>
                      $details
                  </div>";
            })
            ->rawColumns(['invested_user', 'action'])
            ->make(true);
    }

    public function runningInvestmentDetails($propertyId)
    {
        $singleProperty = Property::findOrFail($propertyId);
        return view('admin.investment.runningInvestmentDetails', compact('singleProperty'));
    }

    public function runningInvestmentDetailsSearch(Request $request, $propertyId)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];
        $filterUser = $request->filterUser;

        $runningInvestments = Investment::with('property', 'user')
            ->join('properties', 'properties.id', '=', 'investments.property_id') // Join properties table
            ->where('properties.expire_date', '>', now())
            ->where('investments.status', 0)
            ->where('investments.is_active', 1)
            ->where('properties.id', $propertyId)
            ->when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhere('username', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($filterUser), function ($query) use ($filterUser) {
                $query->whereHas('user', function ($q) use ($filterUser) {
                    $q->where('firstname', 'LIKE', "%{$filterUser}%")
                        ->orWhere('lastname', 'LIKE', "%{$filterUser}%")
                        ->orWhere('username', 'LIKE', "%{$filterUser}%");
                });
            })
            ->orderBy('investments.id', 'desc')
            ->get();

        return DataTables::of($runningInvestments)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('user', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('invested_amount', function ($item) {
                return currencyPosition($item->amount);
            })
            ->addColumn('profit', function ($item) {
                $profit = currencyPosition($item->profit);
                $netProfit = currencyPosition($item->net_profit);
                if ($item->how_many_times != 0 && $item->how_many_times != null && $item->return_date != null) {
                    return $item->profit_type == 0 ? $profit : $netProfit;
                } elseif ($item->how_many_times != null && $item->return_date == null) {
                    return currencyPosition(0);
                } elseif ($item->how_many_times == null && $item->return_date != null) {
                    return $netProfit;
                }
            })
            ->addColumn('profit_return_date', function ($item) {
                if ($item->how_many_times != 0 && $item->how_many_times != null && $item->return_date != null) {
                    return dateTime($item->return_date);
                } elseif ($item->how_many_times != null && $item->return_date == null) {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="bg-danger"></span>After Installments Clear
                  </span>';
                } elseif ($item->how_many_times == null && $item->return_date != null) {
                    return dateTime($item->return_date);
                }
            })
            ->addColumn('return_times', function ($item) {
                if (($item->how_many_times == 0 && $item->how_many_times != null) && $item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>Completed
                  </span>';
                } elseif ($item->how_many_times == null && $item->status == 0) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>Life Time
                  </span>';
                } elseif (($item->how_many_times >= 0 && $item->how_many_times != null) && $item->status == 0) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>' . $item->how_many_times . 'times
                  </span>';
                } else {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>N/A
                  </span>';
                }
            })
            ->rawColumns(['user', 'profit_return_date', 'return_times'])
            ->make(true);
    }


    public function dueInvestments()
    {
        return view('admin.investment.dueInvestment');
    }

    public function dueInvestmentSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];
        $filterProperty = $request->filterProperty;

        $dueInvestments = Investment::with('property', 'user')
            ->join('properties', 'properties.id', '=', 'investments.property_id') // Join properties table
            ->where('investments.invest_status', 0)
            ->where('investments.status', 0)
            ->where('investments.is_active', 1)
            ->when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->whereHas('property', function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($filterProperty), function ($query) use ($filterProperty) {
                $query->whereHas('property', function ($q) use ($filterProperty) {
                    $q->where('title', 'LIKE', "%{$filterProperty}%");
                });
            })
            ->groupBy('investments.property_id')
            ->orderBy('properties.id', 'asc')
            ->get();

        return DataTables::of($dueInvestments)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('property', function ($item) {
                return $item->property?->title;
            })
            ->addColumn('investment_expire_date', function ($item) {
                return dateTime(optional($item->property)->expire_date);
            })
            ->addColumn('invested_user', function ($item) {
                return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>' . optional($item->property)->totalDueInvestUserAndAmount()['totalInvestedUser'] . '
                  </span>';
            })
            ->addColumn('total_investment_amount', function ($item) {
                return currencyPosition(optional($item->property)->totalDueInvestUserAndAmount()['totalInvestedAmount']) ;
            })
            ->addColumn('total_due_amount', function ($item) {
                return currencyPosition(optional($item->property)->totalDueInvestUserAndAmount()['totalDueAmount']) ;
            })
            ->addColumn('action', function ($item) {
                $detailsRoute = route('admin.due.investment.details', optional($item->property)->id);

                $details = "<a class='btn btn-white btn-sm investmentActive' href='" . $detailsRoute . "'>
                            <i class='fal fa-eye dropdown-item-icon'></i>
                           " . trans("Details") . "
                        </a>";

                return "<div class='btn-group' role='group'>
                      $details
                  </div>";
            })
            ->rawColumns(['invested_user', 'action'])
            ->make(true);
    }

    public function dueInvestmentDetails($propertyId)
    {
        $singleProperty = Property::findOrFail($propertyId);
        return view('admin.investment.dueInvestmentDetails', compact('singleProperty'));
    }

    public function dueInvestmentDetailsSearch(Request $request, $propertyId)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];
        $filterUser = $request->filterUser;

        $dueInvestments = Investment::with('property', 'user')
            ->join('properties', 'properties.id', '=', 'investments.property_id') // Join properties table
            ->where('investments.invest_status', 0)
            ->where('investments.status', 0)
            ->where('investments.is_active', 1)
            ->where('properties.id', $propertyId)
            ->when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhere('username', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($filterUser), function ($query) use ($filterUser) {
                $query->whereHas('user', function ($q) use ($filterUser) {
                    $q->where('firstname', 'LIKE', "%{$filterUser}%")
                        ->orWhere('lastname', 'LIKE', "%{$filterUser}%")
                        ->orWhere('username', 'LIKE', "%{$filterUser}%");
                });
            })
            ->orderBy('investments.id', 'desc')
            ->get();

        return DataTables::of($dueInvestments)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('user', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('invested_amount', function ($item) {
                return currencyPosition($item->amount);
            })
            ->addColumn('due_amount', function ($item) {
                return currencyPosition(optional(($item)->property)->dueInvestmentAmount($item->amount));
            })
            ->addColumn('due_installments', function ($item) {
                return '<span class="badge bg-soft-info text-info">
                    <span class="bg-info"></span>' . $item->due_installments . '
                  </span>';
            })
            ->addColumn('installment_last_date', function ($item) {
                return dateTime($item->next_installment_date_end);
            })
            ->addColumn('action', function ($item) {
                $sendMailRoute = route('admin.send.email', $item->user_id);

                $sendMail = "<a class='btn btn-white btn-sm investmentActive' href='" . $sendMailRoute . "'>
                            <i class='fas fa-envelope-open dropdown-item-icon'></i>
                           " . trans("Send Mail") . "
                        </a>";

                return "<div class='btn-group' role='group'>
                      $sendMail
                  </div>";
            })
            ->rawColumns(['user', 'due_installments', 'action'])
            ->make(true);
    }


    public function expiredInvestments()
    {
        return view('admin.investment.expiredInvestment');
    }

    public function expiredInvestmentSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];
        $filterProperty = $request->filterProperty;

        $expiredInvestments = Investment::with('property', 'user')
            ->join('properties', 'properties.id', '=', 'investments.property_id') // Join properties table
            ->where('properties.expire_date', '<', now())
            ->where('investments.status', 0)
            ->where('investments.is_active', 1)
            ->when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->whereHas('property', function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($filterProperty), function ($query) use ($filterProperty) {
                $query->whereHas('property', function ($q) use ($filterProperty) {
                    $q->where('title', 'LIKE', "%{$filterProperty}%");
                });
            })
            ->groupBy('investments.property_id')
            ->orderBy('properties.id', 'asc')
            ->get();

        return DataTables::of($expiredInvestments)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('property', function ($item) {
                return $item->property?->title;
            })
            ->addColumn('investment_expire_date', function ($item) {
                return dateTime(optional($item->property)->expire_date);
            })
            ->addColumn('invested_user', function ($item) {
                return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>' . optional($item->property)->totalRunningInvestUserAndAmount()['totalInvestedUser'] . '
                  </span>';
            })
            ->addColumn('total_investment_amount', function ($item) {
                return currencyPosition(optional($item->property)->totalRunningInvestUserAndAmount()['totalInvestedAmount']) ;
            })
            ->addColumn('profit_return_date', function ($item) {
                return dateTime($item->return_date);
            })
            ->addColumn('return_times', function ($item) {
                if (($item->how_many_times == 0 && $item->how_many_times != null) && $item->status == 1) {
                    return '<span class="badge bg-soft-info text-info">
                    <span class="bg-info"></span>Completed
                  </span>';
                } elseif ($item->how_many_times == null && $item->status == 0) {
                    return '<span class="badge bg-soft-info text-info">
                    <span class="bg-info"></span>Life Time
                  </span>';
                } else {
                    return '<span class="badge bg-soft-info text-info">
                    <span class="bg-info"></span>' . $item->how_many_times . ' times
                  </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $detailsRoute = route('admin.expired.investment.details', optional($item->property)->id);

                $details = "<a class='btn btn-white btn-sm investmentActive' href='" . $detailsRoute . "'>
                            <i class='fal fa-eye dropdown-item-icon'></i>
                           " . trans("Details") . "
                        </a>";

                return "<div class='btn-group' role='group'>
                      $details
                  </div>";
            })
            ->rawColumns(['invested_user', 'return_times', 'action'])
            ->make(true);
    }

    public function expiredInvestmentDetails($propertyId)
    {
        $singleProperty = Property::findOrFail($propertyId);
        return view('admin.investment.expiredInvestmentDetails', compact('singleProperty'));
    }

    public function expiredInvestmentDetailsSearch(Request $request, $propertyId)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];
        $filterUser = $request->filterUser;

        $expiredInvestments = Investment::with('property', 'user')
            ->join('properties', 'properties.id', '=', 'investments.property_id') // Join properties table
            ->where('properties.expire_date', '<', now())
            ->where('investments.status', 0)
            ->where('investments.is_active', 1)
            ->where('properties.id', $propertyId)
            ->when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhere('username', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($filterUser), function ($query) use ($filterUser) {
                $query->whereHas('user', function ($q) use ($filterUser) {
                    $q->where('firstname', 'LIKE', "%{$filterUser}%")
                        ->orWhere('lastname', 'LIKE', "%{$filterUser}%")
                        ->orWhere('username', 'LIKE', "%{$filterUser}%");
                });
            })
            ->orderBy('investments.id', 'desc')
            ->get();

        return DataTables::of($expiredInvestments)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('user', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('invested_amount', function ($item) {
                return currencyPosition($item->amount);
            })
            ->addColumn('profit', function ($item) {
                $profit = currencyPosition($item->profit);
                $netProfit = currencyPosition($item->net_profit);
                if ($item->how_many_times != 0 && $item->how_many_times != null && $item->return_date != null) {
                    return $item->profit_type == 0 ? $profit : $netProfit;
                } elseif ($item->how_many_times != null && $item->return_date == null) {
                    return currencyPosition(0);
                } elseif ($item->how_many_times == null && $item->return_date != null) {
                    return $netProfit;
                }
            })
            ->addColumn('profit_return_date', function ($item) {
                if ($item->how_many_times != 0 && $item->how_many_times != null && $item->return_date != null) {
                    return dateTime($item->return_date);
                } elseif ($item->how_many_times != null && $item->return_date == null) {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="bg-danger"></span>After Installments Clear
                  </span>';
                } elseif ($item->how_many_times == null && $item->return_date != null) {
                    return dateTime($item->return_date);
                }
            })
            ->addColumn('return_times', function ($item) {
                if (($item->how_many_times == 0 && $item->how_many_times != null) && $item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>Completed
                  </span>';
                } elseif ($item->how_many_times == null && $item->status == 0) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>Life Time
                  </span>';
                } elseif (($item->how_many_times >= 0 && $item->how_many_times != null) && $item->status == 0) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>' . $item->how_many_times . 'times
                  </span>';
                } else {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>N/A
                  </span>';
                }
            })
            ->rawColumns(['user', 'profit_return_date', 'return_times'])
            ->make(true);
    }


    public function completedInvestments()
    {
        return view('admin.investment.completedInvestment');
    }

    public function completedInvestmentSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];
        $filterProperty = $request->filterProperty;

        $completedInvestments = Investment::with('property', 'user')
            ->join('properties', 'properties.id', '=', 'investments.property_id') // Join properties table
            ->where('investments.status', 1)
            ->where('investments.is_active', 1)
            ->when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->whereHas('property', function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($filterProperty), function ($query) use ($filterProperty) {
                $query->whereHas('property', function ($q) use ($filterProperty) {
                    $q->where('title', 'LIKE', "%{$filterProperty}%");
                });
            })
            ->groupBy('investments.property_id')
            ->orderBy('properties.id', 'asc')
            ->get();

        return DataTables::of($completedInvestments)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('property', function ($item) {
                return $item->property->title;
            })
            ->addColumn('investment_expire_date', function ($item) {
                return dateTime(optional($item->property)->expire_date);
            })
            ->addColumn('invested_user', function ($item) {
                return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>' . optional($item->property)->totalCompletedInvestUserAndAmount()['totalInvestedUser'] . '
                  </span>';
            })
            ->addColumn('total_investment_amount', function ($item) {
                return currencyPosition(optional($item->property)->totalCompletedInvestUserAndAmount()['totalInvestedAmount']) ;
            })
            ->addColumn('last_return_date', function ($item) {
                return dateTime($item->last_return_date);
            })
            ->addColumn('profit_return_times', function ($item) {
                if (($item->how_many_times == 0 && $item->how_many_times != null) && $item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>Completed
                  </span>';
                } elseif ($item->how_many_times == null && $item->status == 0) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>Life Time
                  </span>';
                } else {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>' . $item->how_many_times . ' times
                  </span>';
                }

            })
            ->addColumn('action', function ($item) {
                $detailsRoute = route('admin.completed.investment.details', optional($item->property)->id);

                $details = "<a class='btn btn-white btn-sm investmentActive' href='" . $detailsRoute . "'>
                            <i class='fal fa-eye dropdown-item-icon'></i>
                           " . trans("Details") . "
                        </a>";

                return "<div class='btn-group' role='group'>
                      $details
                  </div>";
            })
            ->rawColumns(['invested_user', 'profit_return_times', 'action'])
            ->make(true);
    }

    public function completedInvestmentDetails($propertyId)
    {
        $singleProperty = Property::findOrFail($propertyId);
        return view('admin.investment.completedInvestmentDetails', compact('singleProperty'));
    }

    public function completedInvestmentDetailsSearch(Request $request, $propertyId)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];
        $filterUser = $request->filterUser;

        $completedInvestments = Investment::with('property', 'user')
            ->join('properties', 'properties.id', '=', 'investments.property_id') // Join properties table
            ->where('investments.status', 1)
            ->where('investments.is_active', 1)
            ->where('properties.id', $propertyId)
            ->when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhere('username', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($filterUser), function ($query) use ($filterUser) {
                $query->whereHas('user', function ($q) use ($filterUser) {
                    $q->where('firstname', 'LIKE', "%{$filterUser}%")
                        ->orWhere('lastname', 'LIKE', "%{$filterUser}%")
                        ->orWhere('username', 'LIKE', "%{$filterUser}%");
                });
            })
            ->orderBy('investments.id', 'desc')
            ->get();

        return DataTables::of($completedInvestments)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('user', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('invested_amount', function ($item) {
                return currencyPosition($item->amount);
            })
            ->addColumn('profit', function ($item) {
                return  $item->profit_type == 0 ? currencyPosition($item->profit) : currencyPosition($item->net_profit);
            })
            ->addColumn('last_return_date', function ($item) {
                return dateTime($item->last_return_date);
            })
            ->addColumn('profit_return_times', function ($item) {
                return '<span class="badge bg-soft-success text-success">
                    <span class="bg-success"></span>Completed
                  </span>';

            })
            ->rawColumns(['user', 'profit_return_times'])
            ->make(true);
    }
}
