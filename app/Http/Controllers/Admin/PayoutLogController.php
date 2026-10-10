<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\PayoutMethod;
use App\Models\Transaction;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PayoutLogController extends Controller
{
    use Notify;

    public function index()
    {
        $payoutRecord = \Cache::get('payoutRecord');
        if (!$payoutRecord) {
            $payoutRecord = Payout::selectRaw('COUNT(id) AS totalWithdrawLog')
                ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS pendingWithdraw')
                ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END) / COUNT(id)) * 100 AS pendingWithdrawPercentage')
                ->selectRaw('COUNT(CASE WHEN status = 2 THEN id END) AS successWithdraw')
                ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END) / COUNT(id)) * 100 AS successWithdrawPercentage')
                ->selectRaw('COUNT(CASE WHEN status = 3 THEN id END) AS cancelWithdraw')
                ->selectRaw('(COUNT(CASE WHEN status = 3 THEN id END) / COUNT(id)) * 100 AS cancelWithdrawPercentage')
                ->get()
                ->toArray();
            \Cache::put('payoutRecord', $payoutRecord);
        }

        $data['methods'] = PayoutMethod::where('is_active', 1)->orderBy('id', 'asc')->get();
        return view('admin.payout.logs', $data, compact('payoutRecord'));
    }

    public function search(Request $request)
    {
        $filterTransactionId = $request->filterTransactionID;
        $filterStatus = $request->filterStatus;
        $filterMethod = $request->filterMethod;
        $basicControl = basicControl();
        $search = $request->search['value'];

        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $payout = Payout::query()->with(['user:id,username,firstname,lastname,image,image_driver', 'method:id,name,logo,driver'])
            ->whereHas('user')
            ->whereHas('method')
            ->orderBy('id', 'desc')
            ->where('status', '!=', 0)
            ->orderBy('id', 'desc')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', $search)
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'LIKE', "%$search%")
                            ->orWhere('username', 'LIKE', "%$search%");
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
                return $query->whereHas('method', function ($subQuery) use ($filterMethod) {
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

        return DataTables::of($payout)
            ->addColumn('no', function ($item) {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('name', function ($item) {
                $url = route("admin.user.edit", optional($item->user)->id);
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

                return '<a class="d-flex align-items-center me-2 cursor-unset" href="javascript:void(0)">
                                <div class="flex-shrink-0">
                                  ' . $item->picture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->method)->name . '</h5>
                                </div>
                              </a>';
            })
            ->addColumn('amount', function ($item) {
                $statusClass = $item->getStatusClass();
                return "<h6 class='mb-0 $statusClass '>" . fractionNumber(getAmount($item->amount)). ' ' . $item->payout_currency_code . "</h6>";

            })
            ->addColumn('charge', function ($item) {
                return "<span class='text-danger'>".getAmount($item->charge) . ' ' . $item->payout_currency_code."</span>";
            })
            ->addColumn('net amount', function ($item) {
                return "<h6>".currencyPosition(getAmount($item->amount_in_base_currency))."</h6>";
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                } else if ($item->status == 1) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                } else if ($item->status == 2) {
                    return '<span class="badge bg-soft-success text-success">' . trans('Successful') . '</span>';
                } else if ($item->status == 3) {
                    return '<span class="badge bg-soft-danger text-danger">' . trans('Cancel') . '</span>';
                }
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at, 'd M Y h:i A');
            })
            ->addColumn('action', function ($item) use ($basicControl) {
                $details = null;
                if ($item->information) {
                    $details = [];
                    foreach ($item->information as $k => $v) {
                        if ($v->type == "file") {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => getFile(config('filesystems.default'), @$v->field_value ?? $v->field_name),
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

                $icon = $item->status == 1 ? 'pencil' : 'eye';

                $statusColor = '';
                $statusText = '';
                if ($item->status == 0) {
                    $statusColor = 'badge bg-soft-warning text-warning';
                    $statusText = 'Pending';
                } else if ($item->status == 1) {
                    $statusColor = 'badge bg-soft-warning text-warning';
                    $statusText = 'Pending';
                } else if ($item->status == 2) {
                    $statusColor = 'badge bg-soft-success text-success';
                    $statusText = 'Success';
                } else if ($item->status == 3) {
                    $statusColor = 'badge bg-soft-danger text-danger';
                    $statusText = 'Cancel';
                }

                $amount = getAmount($item->amount). ' ' . $item->payout_currency_code;

                return "<button type='button' class='btn btn-white btn-sm edit_btn' data-bs-target='#accountInvoiceReceiptModal'
                data-id='$item->id'
                data-info='" . json_encode($details) . "'
                data-userid='" . optional($item->user)->id . "'
                data-sendername='" . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . "'
                data-transactionid='$item->trx_id'
                data-feedback='$item->feedback'
                data-amount=' $amount'
                data-method='" . optional($item->method)->name . "'
                data-gatewayimage='" . getFile(optional($item->method)->driver, optional($item->method)->image) . "'
                data-datepaid='" . dateTime($item->created_at, 'd M Y') . "'
                data-status='$item->status'
                data-status_color='$statusColor'
                data-status_text='$statusText'
                data-username='" . optional($item->user)->username . "'
                data-action='" . route('admin.payout.action', $item->id) . "'
                data-bs-toggle='modal'
                data-bs-target='#accountInvoiceReceiptModal'>  <i class='bi-$icon fill me-1'></i> </button>";
            })
            ->rawColumns(['name', 'amount', 'charge', 'method', 'net amount', 'status', 'action'])
            ->make(true);

    }

    public function pending()
    {
        $data['methods'] = PayoutMethod::where('is_active', 1)->orderBy('id', 'asc')->get();
        return view('admin.payout.request', $data);
    }

    public function payoutRequest(Request $request)
    {
        $filterTransactionId = $request->filterTransactionID;
        $filterStatus = $request->filterStatus;
        $filterMethod = $request->filterMethod;
        $basicControl = basicControl();
        $search = $request->search['value'];

        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $payout = Payout::query()->with(['user:id,username,firstname,lastname,image,image_driver', 'method:id,name,logo,driver'])
            ->whereHas('user')
            ->whereHas('method')
            ->where('status', 0)
            ->orderBy('id', 'desc')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', $search)
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'LIKE', "%$search%")
                            ->orWhere('username', 'LIKE', "%$search%");
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
                return $query->whereHas('method', function ($subQuery) use ($filterMethod) {
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

        return DataTables::of($payout)
            ->addColumn('no', function ($item) {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('name', function ($item) {
                $url = route("admin.user.edit", optional($item->user)->id);
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

                return '<a class="d-flex align-items-center me-2 cursor-unset" href="javascript:void(0)">
                                <div class="flex-shrink-0">
                                  ' . $item->picture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->method)->name . '</h5>
                                </div>
                              </a>';
            })
            ->addColumn('amount', function ($item) {
                $statusClass = $item->getStatusClass();
                return "<h6 class='mb-0 $statusClass '>" . fractionNumber(getAmount($item->amount)). ' ' . $item->payout_currency_code . "</h6>";

            })
            ->addColumn('charge', function ($item) {
                return "<span class='text-danger'>".getAmount($item->charge) . ' ' . $item->payout_currency_code."</span>";
            })
            ->addColumn('net amount', function ($item) {
                return "<h6>".currencyPosition(getAmount($item->amount_in_base_currency))."</h6>";
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                } else if ($item->status == 1) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                } else if ($item->status == 2) {
                    return '<span class="badge bg-soft-success text-success">' . trans('Successful') . '</span>';
                } else if ($item->status == 3) {
                    return '<span class="badge bg-soft-danger text-danger">' . trans('Cancel') . '</span>';
                }
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at, 'd M Y h:i A');
            })
            ->addColumn('action', function ($item) use ($basicControl) {
                $details = null;
                if ($item->information) {
                    $details = [];
                    foreach ($item->information as $k => $v) {
                        if ($v->type == "file") {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => getFile(config('filesystems.default'), @$v->field_value ?? $v->field_name),
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

                $icon = $item->status == 1 ? 'pencil' : 'eye';

                $statusColor = '';
                $statusText = '';
                if ($item->status == 0) {
                    $statusColor = 'badge bg-soft-warning text-warning';
                    $statusText = 'Pending';
                } else if ($item->status == 1) {
                    $statusColor = 'badge bg-soft-warning text-warning';
                    $statusText = 'Pending';
                } else if ($item->status == 2) {
                    $statusColor = 'badge bg-soft-success text-success';
                    $statusText = 'Success';
                } else if ($item->status == 3) {
                    $statusColor = 'badge bg-soft-danger text-danger';
                    $statusText = 'Cancel';
                }

                return "<button type='button' class='btn btn-white btn-sm edit_btn' data-bs-target='#accountInvoiceReceiptModal'
                data-id='$item->id'
                data-info='" . json_encode($details) . "'
                data-userid='" . optional($item->user)->id . "'
                data-sendername='" . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . "'
                data-transactionid='$item->trx_id'
                data-feedback='$item->feedback'
                data-amount=' " . currencyPosition(getAmount($item->amount)) . "'
                data-method='" . optional($item->method)->name . "'
                data-gatewayimage='" . getFile(optional($item->method)->driver, optional($item->method)->image) . "'
                data-datepaid='" . dateTime($item->created_at, 'd M Y') . "'
                data-status='$item->status'
                data-status_color='$statusColor'
                data-status_text='$statusText'
                data-username='" . optional($item->user)->username . "'
                data-action='" . route('admin.payout.action', $item->id) . "'
                data-bs-toggle='modal'
                data-bs-target='#accountInvoiceReceiptModal'>  <i class='bi-$icon fill me-1'></i> </button>";
            })
            ->rawColumns(['name', 'amount', 'charge', 'method', 'net amount', 'status', 'action'])
            ->make(true);

    }


    public function action(Request $request, $id)
    {

        $this->validate($request, [
            'id' => 'required',
            'status' => ['required', Rule::in(['2', '3'])],
        ]);
        $payout = Payout::where('id', $request->id)->whereIn('status', [1])->with('user', 'method')->firstOrFail();


        if ($request->status == 3) {
            $this->cancelPayout($request->id, $request->feedback);
            return back()->with('success', "Payment Rejected.");
        }

        if (optional($payout->payoutMethod)->is_automatic == 1) {
            $methodObj = 'App\\Services\\Payout\\' . optional($payout->payoutMethod)->code . '\\Card';
            $data = $methodObj::payouts($payout);

            if (!$data) {
                return back()->with('alert', 'Method not available or unknown errors occur');
            }

            if ($data['status'] == 'error') {
                $payout->feedback = $request->feedback;
                $payout->last_error = $data['data'];
                $payout->status = 3;
                $payout->save();
                return back()->with('error', $data['data']);
            }
        }

        if (optional($payout->payoutMethod)->is_automatic == 0) {
            $payout->feedback = $request->feedback;
            $transaction = new Transaction();
            $transaction->user_id = $payout->user_id;
            $transaction->amount = $payout->amount_in_base_currency;

            $transaction->charge = $payout->charge_in_base_currency;
            $transaction->trx_type = '-';
            $transaction->trx_id = $payout->trx_id;
            $transaction->remarks = 'Withdraw ' . optional($payout->payoutMethod)->name;
            $payout->transactional()->save($transaction);
            $payout->status = 2;
            $payout->save();
            $this->userNotify($payout);

        } else {
            if (optional($payout->payoutMethod)->code == 'coinbase' || optional($payout->payoutMethod)->code == 'perfectmoney') {
                $payout->feedback = $request->note;
                $transaction = new Transaction();
                $transaction->user_id = $payout->user_id;
                $transaction->amount = $payout->amount_in_base_currency;
                $transaction->charge = $payout->charge_in_base_currency;
                $transaction->trx_type = '-';
                $transaction->trx_id = $payout->trx_id;
                $transaction->remarks = 'Withdraw ' . optional($payout->payoutMethod)->name;
                $payout->transactional()->save($transaction);
                $payout->status = 2;
                $payout->save();
            } else {
                $payout->note = $request->feedback;
                $payout->response_id = $data['response_id'];
                $payout->save();
            }
        }

        return back()->with('success', 'Payment Confirmed');
    }

    public function cancelPayout($id, $feedback)
    {

        $payout = Payout::where('id', $id)->whereIn('status', [1])->with('user', 'method')->firstOrFail();

        if (!$payout) {
            return back()->with('error', 'Transaction not found');
        } elseif ($payout->status != 1) {
            return back()->with('error', 'Action not possible');
        }

        /* Add money from Sender Wallet */
        $addBalance = updateBalance($payout->user_id, $payout->net_amount, 1);

        $payout->feedback = $feedback;
        $payout->status = 3;
        $payout->save();

        $receivedUser = $payout->user;
        $params = [
            'sender' => $receivedUser->name,
            'amount' => getAmount($payout->amount),
            'currency' => $payout->payout_currency_code,
            'transaction' => $payout->trx_id,
        ];

        $action = [
            "link" => "#",
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $firebaseAction = "#";
        $this->sendMailSms($receivedUser, 'PAYOUT_CANCEL', $params);
        $this->userPushNotification($receivedUser, 'PAYOUT_CANCEL', $params, $action);
        $this->userFirebasePushNotification($receivedUser, 'PAYOUT_CANCEL', $params, $firebaseAction);

    }

    public function userNotify($payout)
    {
        $msg = [
            'username' => optional($payout->user)->username,
            'amount' => getAmount($payout->amount),
            'currency' => $payout->payout_currency_code,
            'gateway' => optional($payout->method)->name,
            'transaction' => $payout->trx_id,
        ];
        $action = [
            "link" => '#',
            "icon" => "fas fa-money-bill-alt text-white"
        ];
        $fireBaseAction = "#";
        $this->userPushNotification($payout->user, 'PAYOUT_APPROVED', $msg, $action);
        $this->userFirebasePushNotification('PAYOUT_APPROVED', $msg, $fireBaseAction);
        $this->sendMailSms($payout->user, 'PAYOUT_APPROVED', [
            'gateway_name' => optional($payout->method)->name,
            'amount' => currencyPosition($payout->amount),
            'charge' => currencyPosition($payout->charge),
            'transaction' => $payout->trx_id,
            'feedback' => $payout->note,
        ]);
    }

}
