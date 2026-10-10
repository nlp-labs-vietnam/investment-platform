<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Facades\App\Services\BasicService;
use Yajra\DataTables\Facades\DataTables;

class PaymentLogController extends Controller
{
    use Notify;

    public function index()
    {
        $paymentRecord = \Cache::get('paymentRecord');
        if (!$paymentRecord) {
            $paymentRecord = deposit::selectRaw('COUNT(id) AS totalPaymentLog')
                ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS paymentSuccess')
                ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END) / COUNT(id)) * 100 AS paymentSuccessPercentage')
                ->selectRaw('COUNT(CASE WHEN status = 2 THEN id END) AS pendingPayment')
                ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END) / COUNT(id)) * 100 AS pendingPaymentPercentage')
                ->selectRaw('COUNT(CASE WHEN status = 3 THEN id END) AS cancelPayment')
                ->selectRaw('(COUNT(CASE WHEN status = 3 THEN id END) / COUNT(id)) * 100 AS cancelPaymentPercentage')
                ->get()
                ->toArray();
            \Cache::put('paymentRecord', $paymentRecord);
        }

        $data['methods'] = Gateway::where('status', 1)->orderBy('sort_by', 'asc')->get();
        return view('admin.payment.logs', $data, compact('paymentRecord'));
    }

    public function search(Request $request)
    {
        $filterTransactionId = $request->filterTransactionID;
        $filterStatus = $request->filterStatus;
        $filterMethod = $request->filterMethod;
        $search = $request->search['value'];

        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $deposit = Deposit::query()->with(['user:id,username,firstname,lastname,image,image_driver', 'gateway:id,name,image,driver'])
            ->whereHas('user')
            ->whereHas('gateway')
            ->orderBy('id', 'desc')
            ->where('status', '!=', 0)
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('transaction', 'LIKE', "%$search%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('firstname', 'LIKE', "%$search%")
                                ->orWhere('lastname', 'LIKE', "%{$search}%")
                                ->orWhere('username', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when(!empty($filterTransactionId), function ($query) use ($filterTransactionId) {
                return $query->where('trx_id', $filterTransactionId);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == "all") {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->when(isset($filterMethod), function ($query) use ($filterMethod) {
                return $query->whereHas('gateway', function ($subQuery) use ($filterMethod) {
                    if ($filterMethod == "all") {
                        $subQuery->where('id', '!=', null);
                    } else {
                        $subQuery->where('id', $filterMethod);
                    }
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
            });


        return DataTables::of($deposit)
            ->addColumn('no', function ($item) {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('name', function ($item) {
                $url = route('admin.user.view.profile', optional($item->user)->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . optional($item->user)->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('trx', function ($item) {
                return $item->trx_id;
            })
            ->addColumn('method', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="javascript:void(0)">
                                <div class="flex-shrink-0">
                                  ' . $item->picture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->gateway)->name . '</h5>
                                </div>
                              </a>';


            })
            ->addColumn('amount', function ($item) {
                $statusClass = $item->getStatusClass();
                return "<h6 class='mb-0 $statusClass '>" . fractionNumber(getAmount($item->amount)) . ' ' . $item->payment_method_currency . "</h6>";
            })
            ->addColumn('charge', function ($item) {
                return "<span class='text-danger'>" . fractionNumber(getAmount($item->percentage_charge) + getAmount($item->fixed_charge)) . ' ' . $item->payment_method_currency . "</span>";
            })
            ->addColumn('payable', function ($item) {
                return "<h6>" . currencyPosition($item->payable_amount_in_base_currency) . "</h6>";
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                } else if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">' . trans('Successful') . '</span>';
                } else if ($item->status == 2) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                } else if ($item->status == 3) {
                    return '<span class="badge bg-soft-danger text-danger">' . trans('Cancel') . '</span>';
                }
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('action', function ($item) {
                $details = null;
                if ($item->information) {
                    $details = [];
                    foreach ($item->information as $k => $v) {
                        if ($v->type == "file") {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => getFile(config('filesystems.default'), $v->field_value),
                            ];
                        } else {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => @$v->field_value ?? $v->field_name
                            ];
                        }
                    }
                }


                if (optional($item->gateway)->id > 999) {
                    $icon = $item->status == 2 ? 'pencil' : 'eye';
                    return "<button type='button' class='btn btn-white btn-sm edit_btn' data-bs-target='#accountInvoiceReceiptModal'
                data-detailsinfo='" . json_encode($details) . "'
                data-id='$item->id'
                data-feedback='$item->note'
                data-amount='" . currencyPosition(getAmount($item->payable_amount_in_base_currency)) . "'
                data-method='" . optional($item->gateway)->name . "'
                data-gatewayimage='" . getFile(optional($item->gateway)->driver, optional($item->gateway)->image) . "'
                data-datepaid='" . dateTime($item->created_at) . "'
                data-status='$item->status'
                data-username='" . optional($item->user)->username . "'
                data-action='" . route('admin.payment.action', $item->id) . "'
                data-bs-toggle='modal'
                data-bs-target='#accountInvoiceReceiptModal'>  <i class='bi-$icon fill me-1'></i> </button>";
                } else {
                    return '-';
                }

            })
            ->rawColumns(['name', 'method', 'amount', 'charge', 'payable', 'status', 'action'])->make(true);

    }


    public function pending()
    {
        $data['methods'] = Gateway::where('status', 1)->orderBy('sort_by', 'asc')->get();
        return view('admin.payment.request', $data);
    }

    public function paymentRequest(Request $request)
    {
        $filterTransactionId = $request->filterTransactionID;
        $filterStatus = $request->filterStatus;
        $filterMethod = $request->filterMethod;
        $search = $request->search['value'];

        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;


        $funds = Deposit::with('user', 'gateway')
            ->where('status', 2)->where('payment_method_id', '>', 999)->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('transaction', 'LIKE', "%$search%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('firstname', 'LIKE', "%$search%")
                                ->orWhere('lastname', 'LIKE', "%{$search}%")
                                ->orWhere('username', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when(!empty($filterTransactionId), function ($query) use ($filterTransactionId) {
                return $query->where('trx_id', $filterTransactionId);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == "all") {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->when(isset($filterMethod), function ($query) use ($filterMethod) {
                return $query->whereHas('gateway', function ($subQuery) use ($filterMethod) {
                    if ($filterMethod == "all") {
                        $subQuery->where('id', '!=', null);
                    } else {
                        $subQuery->where('id', $filterMethod);
                    }
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
            ->get();

        return DataTables::of($funds)
            ->addColumn('no', function ($item) {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('name', function ($item) {
                $url = route('admin.user.view.profile', optional($item->user)->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . optional($item->user)->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">' . optional($item->user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('trx', function ($item) {
                return $item->trx_id;
            })
            ->addColumn('method', function ($item) {
                return '<a class="d-flex align-items-center me-2 cursor-unset" href="javascript:void(0)">
                                <div class="flex-shrink-0">
                                  ' . $item->picture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->gateway)->name . '</h5>
                                </div>
                              </a>';
            })
            ->addColumn('amount', function ($item) {
                $statusClass = $item->getStatusClass();
                return "<h6 class='mb-0 $statusClass '>" . fractionNumber(getAmount($item->amount)) . ' ' . $item->payment_method_currency . "</h6>";
            })
            ->addColumn('charge', function ($item) {
                return "<span class='text-danger'>" . fractionNumber(getAmount($item->percentage_charge) + getAmount($item->fixed_charge)) . ' ' . $item->payment_method_currency . "</span>";
            })
            ->addColumn('payable', function ($item) {
                return "<h6>" . currencyPosition($item->payable_amount_in_base_currency) . "</h6>";
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 2) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                }
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('action', function ($item) {
                $details = null;
                if ($item->information) {
                    $details = [];
                    foreach ($item->information as $k => $v) {
                        if ($v->type == "file") {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => getFile(config('filesystems.default'), $v->field_value),
                            ];
                        } else {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => @$v->field_value ?? $v->field_name
                            ];
                        }
                    }
                }

                $icon = $item->status == 2 ? 'pencil' : 'eye';
                return "<button type='button' class='btn btn-white btn-sm edit_btn'
                data-detailsinfo='" . json_encode($details) . "'
                data-id='$item->id'
                data-feedback='$item->note'
                data-amount='" . currencyPosition($item->payable_amount_in_base_currency) . "'
                data-method='" . optional($item->gateway)->name . "'
                data-gatewayimage='" . getFile(optional($item->gateway)->driver, optional($item->gateway)->image) . "'
                data-datepaid='" . dateTime($item->created_at) . "'
                data-status='$item->status'
                data-username='" . optional($item->user)->username . "'
                data-action='" . route('admin.payment.action', $item->id) . "'
                data-bs-toggle='modal'
                data-bs-target='#accountInvoiceReceiptModal'>  <i class='bi-$icon fill me-1'></i> </button>";

            })
            ->rawColumns(['name', 'method', 'amount', 'charge', 'payable', 'status', 'action'])->make(true);
    }

    public function action(Request $request, $id)
    {

        $this->validate($request, [
            'id' => 'required',
            'status' => ['required', Rule::in(['1', '3'])],
            'feedback' => 'required|string|min:3|max:300'
        ]);

        $data = Deposit::where('id', $id)->whereIn('status', [2])->with('user', 'gateway')->firstOrFail();

        if ($request->status == '1') {

            $data->update([
                //'status' => 1,
                'note' => $request->feedback
            ]);

            BasicService::preparePaymentUpgradation($data);

            $msg = [
                'username' => optional($data->user)->username,
                'amount' => currencyPosition($data->amount),
                'gateway' => optional($data->gateway)->name,
            ];
            $action = [
                "link" => '#',
                "icon" => "fas fa-money-bill-alt text-white"
            ];
            $fireBaseAction = "#";
            $this->userPushNotification($data->user, 'PAYMENT_APPROVED', $msg, $action);
            $this->userFirebasePushNotification('PAYMENT_APPROVED', $msg, $fireBaseAction);
            $this->sendMailSms($data->user, 'PAYMENT_APPROVED', [
                'gateway_name' => optional($data->gateway)->name,
                'amount' => currencyPosition($data->amount),
                'charge' => currencyPosition($data->charge),
                'transaction' => $data->trx_id,
                'feedback' => $data->note,
            ]);

            session()->flash('success', 'Payment approved successfully.');
            return back();

        } elseif ($request->status == '3') {

            $data->update([
                'status' => 3,
                'note' => $request->feedback
            ]);

            $msg = [
                'username' => optional($data->user)->username,
                'amount' => currencyPosition($data->amount),
                'gateway' => optional($data->gateway)->name,
            ];
            $action = [
                "link" => '#',
                "icon" => "fas fa-money-bill-alt text-white"
            ];
            $firebaseAction = "#";
            $this->userPushNotification($data->user, 'PAYMENT_REJECTED', $msg, $action);
            $this->userFirebasePushNotification('PAYMENT_REJECTED', $msg, $action, $firebaseAction);
            $this->sendMailSms($data->user, 'PAYMENT_REJECTED', [
                'gateway_name' => optional($data->gateway)->name,
                'amount' => currencyPosition($data->amount),
                'charge' => currencyPosition($data->charge),
                'transaction' => $data->trx_id,
                'feedback' => $data->note,
            ]);

            session()->flash('success', 'Payment rejected successfully.');
            return back();
        }
        return back();
    }
}


