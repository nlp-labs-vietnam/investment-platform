<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralBonus;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionLogController extends Controller
{
    public function transaction()
    {
        return view('admin.transaction.index');
    }

    public function transactionSearch(Request $request)
    {
        $search = $request->search['value'];
        $filterTransactionId = $request->filterTransactionID;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $transaction = Transaction::query()->with(['user:id,username,firstname,lastname,image,image_driver'])
            ->whereHas('user')
            ->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('trx_id', 'LIKE', "%$search%")
                        ->orWhere('remarks', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('firstname', 'LIKE', "%$search%")
                                ->orWhere('lastname', 'LIKE', "%{$search}%")
                                ->orWhere('username', 'LIKE', "%{$search}%");
                        });
                });
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
            ->when(!empty($filterTransactionId), function ($query) use ($filterTransactionId) {
                return $query->where('trx_id', $filterTransactionId);
            });

        return DataTables::of($transaction)
            ->addColumn('no', function ($item) {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('trx', function ($item) {
                return $item->trx_id;
            })
            ->addColumn('user', function ($item) {
                $url = route("admin.user.view.profile", optional($item->user)->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                    ' . optional($item->user)->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->user)->username ?? 'Unknown' . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('amount', function ($item) {
                $statusClass = $item->trx_type == '+' ? 'text-success' : 'text-danger';
                return "<h6 class='mb-0 $statusClass '>" . $item->trx_type .  currencyPosition(getAmount($item->amount)) . "</h6>";
            })
            ->addColumn('remarks', function ($item) {
                return $item->remarks;
            })
            ->addColumn('date-time', function ($item) {
                return dateTime($item->created_at, 'd M Y h:i A');
            })
            ->rawColumns(['user', 'amount'])
            ->make(true);
    }


    public function commission()
    {
        return view('admin.transaction.commission');
    }

    public function commissionSearch(Request $request)
    {
        $search = $request->search['value'];
        $filterFromUser = $request->filterFromUser;
        $filterToUser = $request->filterToUser;
        $filterRemark = $request->filterRemark;
        $filterBonusType = $request->filterBonusType;

        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $commission = ReferralBonus::query()->with(['user', 'bonusBy'])
            ->when(!empty($search), function ($query) use ($search) {
                return $query->whereHas('user', function ($subquery) use ($search) {
                    $subquery->where('firstname', 'LIKE', "%$search%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhere('username', 'LIKE', "%{$search}%");
                })->where('remarks', 'LIKE', "%{$search}%")
                    ->orWhere('type', 'LIKE', "%{$search}%");
            })
            ->when(!empty($filterFromUser), function ($query) use ($filterFromUser) {
                return $query->whereHas('bonusBy', function ($subquery) use ($filterFromUser) {
                    $subquery->where('firstname', 'LIKE', "%$filterFromUser%")
                        ->orWhere('lastname', 'LIKE', "%{$filterFromUser}%")
                        ->orWhere('username', 'LIKE', "%{$filterFromUser}%");
                });
            })
            ->when(!empty($filterToUser), function ($query) use ($filterToUser) {
                return $query->whereHas('user', function ($subquery) use ($filterToUser) {
                    $subquery->where('firstname', 'LIKE', "%$filterToUser%")
                        ->orWhere('lastname', 'LIKE', "%{$filterToUser}%")
                        ->orWhere('username', 'LIKE', "%{$filterToUser}%");
                });
            })
            ->when(!empty($filterRemark), function ($query) use ($filterRemark) {
                $query->where('remarks', 'LIKE', "%$filterRemark%");
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

        return DataTables::of($commission)
            ->addColumn('no', function ($item) {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('bonus_to', function ($item) {
                $url = route("admin.user.view.profile", optional($item->user)->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                    ' . optional($item->user)->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->user)->username ?? 'Unknown' . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('bonus_from', function ($item) {
                $url = route("admin.user.view.profile", optional($item->bonusBy)->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                    ' . optional($item->bonusBy)->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->bonusBy)->firstname . ' ' . optional($item->bonusBy)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->bonusBy)->username ?? 'Unknown' . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('amount', function ($item) {
                return currencyPosition($item->amount);
            })
            ->addColumn('remarks', function ($item) {
                return $item->remarks;
            })
            ->addColumn('bonus_type', function ($item) {
                return ucwords(str_replace('_', ' ', $item->type));
            })
            ->addColumn('date-time', function ($item) {
                return dateTime($item->created_at, 'd M Y h:i A');
            })
            ->rawColumns(['bonus_to', 'bonus_from'])
            ->make(true);
    }
}
