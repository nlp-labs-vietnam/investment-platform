<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Facades\App\Services\BasicService;

class PaymentController extends Controller
{
    use ApiResponse, Upload, Notify;

    public function paymentView($deposit_id)
    {
        $deposit = Deposit::latest()->find($deposit_id);
        try {
            if ($deposit) {
                $getwayObj = 'App\\Services\\Gateway\\' . $deposit->gateway->code . '\\Payment';
                $data = $getwayObj::prepareData($deposit, $deposit->gateway);
                $data = json_decode($data);

                if (isset($data->error)) {
                    $result['status'] = false;
                    $result['message'] = $data->message;
                    return response($result, 200);
                }

                if (isset($data->redirect)) {
                    return redirect($data->redirect_url);
                }

                if ($data->view) {
                    $parts = explode(".", $data->view);
                    $desiredValue = end($parts);
                    $newView = 'mobile-payment.' . $desiredValue;
                    return view($newView, compact('data', 'deposit'));
                }

                abort(404);
            }
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }


    public function depositConfirm($trx_id)
    {
        try {
            $deposit = Deposit::with('user', 'depositable')->where(['trx_id' => $trx_id, 'status' => 0])->first();

            if (!$deposit) {
                return response()->json($this->withError('Invalid Payment Request'));
            }

            $gateway = Gateway::findOrFail($deposit->payment_method_id);

            if (!$gateway) {
                return response()->json($this->withError('Invalid Payment Gateway'));
            }

            if (999 < $gateway->id) {
                return response()->json($this->withError('Invalid Gateway ID'));
            }

            $gatewayObj = 'App\\Services\\Gateway\\' . $gateway->code . '\\Payment';
            $data = $gatewayObj::prepareData($deposit, $gateway);
            $data = json_decode($data);

        } catch (\Exception $exception) {
            return response()->json($this->withError($exception->getMessage()));
        }

        if (isset($data->error)) {
            return response()->json($this->withError($data->message));
        }

        if (isset($data->redirect)) {
            return response()->json(['redirectUrl' => $data->redirect_url]);
        }

        $page_title = 'Payment Confirm';
        $confirmPayment = compact('data', 'page_title', 'deposit');
        return response()->json($this->withSuccess($confirmPayment));
    }

    public function fromSubmit(Request $request, $trx_id)
    {
        $data = Deposit::where('trx_id', $trx_id)->orderBy('id', 'DESC')->with(['gateway', 'user'])->first();

        if (is_null($data)) {
            return response()->json($this->withError('Invalid Request'));
        }

        if ($data->status != 0) {
            return response()->json($this->withError('Invalid Request'));
        }

        $params = optional($data->gateway)->parameters;
        $reqData = $request->except('_token', '_method');
        $rules = [];
        if ($params !== null) {
            foreach ($params as $key => $cus) {
                $rules[$key] = [$cus->validation == 'required' ? $cus->validation : 'nullable'];
                if ($cus->type === 'file') {
                    $rules[$key][] = 'image';
                    $rules[$key][] = 'mimes:jpeg,jpg,png';
                    $rules[$key][] = 'max:2048';
                } elseif ($cus->type === 'text') {
                    $rules[$key][] = 'max:191';
                } elseif ($cus->type === 'number') {
                    $rules[$key][] = 'integer';
                } elseif ($cus->type === 'textarea') {
                    $rules[$key][] = 'min:3';
                    $rules[$key][] = 'max:300';
                }
            }
        }

        $validator = Validator::make($reqData, $rules);

        if ($validator->fails()) {
            return response()->json($this->withError(collect($validator->errors())->collapse()));
        }

        $reqField = [];
        if ($params != null) {
            foreach ($request->except('_token', '_method', 'type') as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k == $inKey) {
                        if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                            try {
                                $file = $this->fileUpload($request[$inKey], config('filelocation.deposit.path'), config('filesystems.default'), null, null, null, null, 40);
                                $reqField[$inKey] = [
                                    'field_name' => $inVal->field_name,
                                    'field_value' => $file['path'],
                                    'field_driver' => $file['driver'],
                                    'validation' => $inVal->validation,
                                    'type' => $inVal->type,
                                ];
                            } catch (\Exception $exp) {
                                return response()->json($this->withError(" Could not upload your {$inKey} "));
                            }
                        } else {
                            $reqField[$inKey] = [
                                'field_name' => $inVal->field_name,
                                'validation' => $inVal->validation,
                                'field_value' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
        }

        $data->update([
            'information' => $reqField,
            'created_at' => Carbon::now(),
            'status' => 2,
        ]);

        $msg = [
            'username' => optional($data->user)->username,
            'amount' => currencyPosition($data->amount),
            'gateway' => optional($data->gateway)->name
        ];

        $action = [
            "name" => optional($data->user)->firstname . ' ' . optional($data->user)->lastname,
            "image" => getFile(optional($data->user)->image_driver, optional($data->user)->image),
            "link" => route('admin.user.payment', $data->user_id),
            "icon" => "fa fa-money-bill-alt text-white"
        ];

        $this->adminPushNotification('PAYMENT_REQUEST', $msg, $action);
        $this->adminFirebasePushNotification('PAYMENT_REQUEST', $msg, $action);
        $this->adminMail('PAYMENT_REQUEST', $msg);

        $confirmData = [
            'message' => 'You request has been taken.',
            'redirect_url' => route('user.fund.history')
        ];

        return response()->json($this->withSuccess($confirmData));

    }

    public function gatewayIpn(Request $request, $code, $trx = null, $type = null)
    {
        if (isset($request->m_orderid)) {
            $trx = $request->m_orderid;
        }

        if ($code == 'coinbasecommerce') {
            $gateway = Gateway::where('code', $code)->first();
            $postdata = file_get_contents("php://input");
            $res = json_decode($postdata);

            if (isset($res->event)) {
                $deposit = Deposit::with('user')->where('trx_id', $res->event->data->metadata->trx)->orderBy('id', 'DESC')->first();
                $sentSign = $request->header('X-Cc-Webhook-Signature');
                $sig = hash_hmac('sha256', $postdata, $gateway->parameters->secret);

                if ($sentSign == $sig) {
                    if ($res->event->type == 'charge:confirmed' && $deposit->status == 0) {
                        BasicService::preparePaymentUpgradation($deposit);
                    }
                }
            }
            session()->flash('success', 'You request has been processing.');

            return redirect()->route('success');
        }

        try {
            $gateway = Gateway::where('code', $code)->first();
            if (!$gateway) {
                throw new Exception('Invalid Payment Gateway.');
            }
            if (isset($trx)) {
                $deposit = Deposit::with('user')->where('trx_id', $trx)->first();
                if (!$deposit) throw new Exception('Invalid Payment Request.');
            }

            $gatewayObj = 'App\\Services\\Gateway\\' . $code . '\\Payment';
            $data = $gatewayObj::ipn($request, $gateway, $deposit ?? null, $trx ?? null, $type ?? null);

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
        if (isset($data['redirect'])) {
            return redirect($data['redirect'])->with($data['status'], $data['msg']);
        }
    }
}
