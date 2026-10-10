<?php

namespace App\Http\Controllers\User;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\OfferLock;
use App\Models\OfferReply;
use App\Models\Property;
use App\Models\PropertyOffer;
use App\Models\PropertyShare;
use App\Models\User;
use App\Traits\Notify;
use App\Traits\Upload;
use Facades\App\Services\BasicService;
use Facades\App\Services\InvestmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    use Upload, Notify;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function propertyMarket($type = null)
    {
        $paginate = 15;

        $data['properties'] = Property::query()
            ->with(['address','managetime','favoritedByUser'])
            ->where('expire_date', '>', now())
            ->where('status', 1)
            ->orderBy('start_date')
            ->paginate($paginate);

        $sharedPropertyQuery = PropertyShare::query()
            ->with([
                'property',
                'property.address',
            ])
            ->has('property')
            ->where('status', 1);

        $data['sharedProperties'] = (clone $sharedPropertyQuery)
            ->with(['propertyOffer','user'])
            ->latest()
            ->paginate($paginate);

        $data['mySharedProperties'] = (clone $sharedPropertyQuery)
            ->whereHas('getInvestment', function ($query) {
                $query->with('propertyShare');
            })
            ->where('investor_id', $this->user->id)
            ->latest()
            ->paginate($paginate);


        $data['myProperties'] = Investment::query()
            ->with(['property','property.address','property.managetime','propertyShare'])
            ->has('property')
            ->where('user_id', $this->user->id)
            ->latest()
            ->paginate($paginate);


        $propertyOfferBaseQuery = PropertyOffer::query()
            ->with(['propertyShare', 'property.address', 'property']);

        $data['myOfferedProperties'] = (clone $propertyOfferBaseQuery)
            ->with('owner')
            ->where('offered_from', $this->user->id)
            ->latest()
            ->paginate($paginate);

        $data['receivedOfferedList'] = (clone $propertyOfferBaseQuery)
            ->where('offered_to', $this->user->id)
            ->groupBy('property_share_id')
            ->latest()
            ->paginate($paginate);

        return view(template() . 'user.property.index', $data, compact('type'));
    }

    public function propertyShareStore(Request $request, $id)
    {
        $investment = Investment::select(['id', 'user_id', 'property_id'])->where('user_id', $this->user->id)->findOrFail($id);

        $rules = [
            'amount' => 'required',
        ];

        $message = [
            'amount.required' => __('Amount field is required!'),
        ];

        $validate = Validator::make($request->all(), $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $propertyShare = new PropertyShare();
        $propertyShare->amount = $request->amount;
        $propertyShare->investment_id = $investment->id;
        $propertyShare->investor_id = $investment->user_id;
        $propertyShare->property_id = $investment->property_id;
        $propertyShare->save();
        return redirect()->route('user.propertyMarket', 'my-shared-properties')->with('success', 'Share successfully saved!');
    }


    public function propertyShareUpdate(Request $request, $id)
    {
        $rules = [
            'amount' => 'required',
        ];

        $message = [
            'amount.required' => __('Amount field is required!'),
        ];

        $validate = Validator::make($request->all(), $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $propertyShare = PropertyShare::where('investor_id', $this->user->id)->findOrFail($id);
        $propertyShare->amount = $request->amount;
        $propertyShare->save();
        return back()->with('success', 'Share successfully updated!');
    }

    public function propertyShareRemove($id)
    {
        $propertyShare = PropertyShare::where('investor_id', $this->user->id)->findOrFail($id);
        $propertyShare->delete();
        return back()->with('success', 'Share remove successfully!');
    }


    public function propertyMakeOfferStore(Request $request, $id)
    {
        $rules = [
            'amount' => 'required|numeric',
            'description' => 'nullable',
        ];

        $message = [
            'amount.required' => __('Amount field is required!'),
        ];

        $validate = Validator::make($request->all(), $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $propertyShare = PropertyShare::findOrFail($id);
        $propertyOffer = new PropertyOffer();

        $propertyOffer->property_share_id = $propertyShare->id;
        $propertyOffer->offered_from = $this->user->id;
        $propertyOffer->offered_to = $propertyShare->investor_id;
        $propertyOffer->investment_id = $propertyShare->investment_id;
        $propertyOffer->property_id = $propertyShare->property_id;
        $propertyOffer->sell_amount = $propertyShare->amount;
        $propertyOffer->amount = $request->amount;
        $propertyOffer->description = $request->description;
        $propertyOffer->save();
        return redirect()->route('user.propertyMarket', 'my-offered-properties')->with('success', 'Offer successfully Send!');

    }

    public function propertyOfferUpdate(Request $request, $id)
    {
        $propertyOffer = PropertyOffer::where('offered_from', $this->user->id)->findOrFail($id);
        $propertyOffer->amount = $request->amount;
        $propertyOffer->description = $request->description;
        $propertyOffer->save();
        return back()->with('success', 'Offer successfully updated!');
    }

    public function propertyOfferRemove($id)
    {
        $propertyOffer = PropertyOffer::where('offered_from', $this->user->id)->find($id);
        if ($propertyOffer == null) {
            $propertyOffer = PropertyOffer::where('offered_to', $this->user->id)->findOrFail($id);
            $propertyOffer->delete();
        } else {
            $propertyOffer->delete();
        }

        return back()->with('success', 'Offer remove successfully!');
    }

    public function offerList(Request $request, $id)
    {
        $search = $request->all();
        $fromDate = \Carbon\Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date)->addDay();

        $data['allOfferList'] = PropertyOffer::with(['getInvestment.propertyShare', 'property', 'user'])
            ->when(isset($search['property']), function ($query) use ($search) {
                return $query->whereHas('property', function ($q) use ($search) {
                    $q->where('title', 'LIKE', '%' . $search['property'] . '%');
                });
            })
            ->when((isset($search['sort_by']) && $search['sort_by'] == 1), function ($query) use ($search) {
                return $query->orderBy('id', 'DESC');
            })
            ->when((isset($search['sort_by']) && $search['sort_by'] == 2), function ($query) use ($search) {
                return $query->orderBy('id', 'ASC');
            })
            ->when((isset($search['sort_by']) && $search['sort_by'] == 3), function ($query) use ($search) {
                return $query->orderBy('amount', 'DESC');
            })
            ->when((isset($search['sort_by']) && $search['sort_by'] == 4), function ($query) use ($search) {
                return $query->orderBy('amount', 'ASC');
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->when(isset($search['from_date']), function ($q2) use ($fromDate) {
                return $q2->whereDate('created_at', '>=', $fromDate);
            })
            ->when(isset($search['to_date']), function ($q2) use ($fromDate, $toDate) {
                return $q2->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->where('property_share_id', $id)->where('offered_to', $this->user->id)->latest()->paginate(basicControl()->paginate);

        return view(template() . 'user.property.offerList', $data);
    }

    public function offerConversation($id)
    {
        $user = $this->user;
        $data['singlePropertyOffer'] = PropertyOffer::with(['property', 'propertyShare', 'owner', 'user', 'offerlock', 'receiveMyOffer'])->findOrFail($id);
        return view(template() . 'user.property.offerConversation', $data, compact('id'));
    }

    public function offerAccept($id)
    {
        $propertyOffer = PropertyOffer::where('offered_to', $this->user->id)->findOrFail($id);
        $propertyOffer->status = 1;
        $propertyOffer->save();
        return back()->with('success', 'Offer Accepted');
    }

    public function offerReject($id)
    {
        $propertyOffer = PropertyOffer::with('offerlock')->where('offered_to', $this->user->id)->findOrFail($id);

        if ($propertyOffer->offerlock && $propertyOffer->lockInfo() != null) {
            $lockOffer = $propertyOffer->lockInfo();
            $lockOffer->status = 2;
            $lockOffer->save();
        }

        $propertyOffer->status = 2;
        $propertyOffer->save();
        return back()->with('success', 'Offer Rejected');
    }

    public function offerRemove($id)
    {
        $propertyOffer = PropertyOffer::where('offered_to', $this->user->id)->findOrFail($id);
        $propertyOffer->delete();
        return back()->with('success', 'offer deleted successfully!');
    }

    public function offerReplyMessageRender(Request $request)
    {
        $messages = OfferReply::with('get_sender:id,firstname,lastname,username,image', 'get_receiver:id,firstname,lastname,username,image')->where('property_offer_id', $request->offerId)->orderBy('id', 'ASC')
            ->get()
            ->map(function ($item) {
                $image = getFile($item->get_sender->image_driver, $item->get_sender->image);
                $item['sender_image'] = $image;
                return $item;
            })
            ->map(function ($item) {
                $image = getFile($item->get_receiver->image_driver, $item->get_receiver->image);
                $item['receiver_image'] = $image;
                return $item;
            })
            ->map(function ($item) {
                if (isset($item->file)) {
                    $file = getFile($item->driver, $item->file);
                    $item['fileImage'] = $file;
                }
                return $item;
            });

        $messages->push(auth()->user());

        return response()->json($messages);
    }

    public function offerReplyMessage(Request $request)
    {
        $this->validate($request, [
            'file' => 'nullable|mimes:jpg,png,jpeg,PNG|max:10000',
        ]);

        $offerReply = new OfferReply();
        $offerReply->sender_id = $this->user->id;
        $offerReply->receiver_id = $request->client_id;
        $offerReply->property_offer_id = $request->property_offer_id;
        $offerReply->reply = $request->reply;

        if ($request->hasFile('file')) {
            $messageFile = $this->fileUpload($request->file, config('filelocation.message.path'), null, null, 'webp', null, null, null);
            throw_if(!$messageFile, __('File could not be uploaded.'));
            if ($messageFile) {
                $offerReply->file = $messageFile['path'];
                $offerReply->driver = $messageFile['driver'] ?? 'local';
            }
            $fileImage = getFile($messageFile['driver'], $messageFile['path']);
        } else {
            $fileImage = null;
        }

        $offerReply->save();

        $sender_image = getFile($this->user->image_driver, $this->user->image);

        $response = [
            'user_id' => $offerReply->sender_id,
            'client_id' => $offerReply->receiver_id,
            'property_offer_id' => $offerReply->property_offer_id,
            'reply' => $offerReply->reply,
            'fileImage' => $fileImage,
            'sender_image' => $sender_image,
        ];

        ChatEvent::dispatch((object)$response);

        return response()->json($response);
    }

    public function paymentLock(Request $request, $id)
    {
        $this->validate($request, [
            'amount' => 'required',
            'duration' => 'required',
        ]);

        $propertyOffer = PropertyOffer::where('offered_to', $this->user->id)->findOrFail($id);
        if ($propertyOffer->status == 2 || $propertyOffer->status == 0) {
            return back()->with('error', "Please accept the offer first. Then lock the payment");
        }

        $offerLock = new OfferLock();
        $offerLock->property_offer_id = $id;
        $offerLock->property_share_id = $propertyOffer->property_share_id;
        $offerLock->offer_amount = $propertyOffer->amount;
        $offerLock->lock_amount = $request->amount;
        $offerLock->duration = $request->duration;

        $offerLock->save();
        return back()->with('success', "Offer is locked successfully");
    }

    public function paymentLockUpdate(Request $request, $id)
    {
        $offerLock = OfferLock::findOrFail($id);
        $offerLock->lock_amount = $request->amount;
        $offerLock->duration = $request->duration;
        $offerLock->save();
        return back()->with('success', 'Payment lock successfully Updated');
    }

    public function paymentLockCancel(Request $request, $id)
    {
        $offerLock = OfferLock::findOrFail($id);
        $offerLock->status = 2;
        $offerLock->save();
        return back()->with('success', 'Payment lock successfully cancelled');
    }

    public function paymentLockConfirm(Request $request, $id)
    {

        $this->validate($request, [
            'balance_type' => 'required|string|max:50',
            'amount' => 'required|numeric',
        ]);


        $balance_type = $request->balance_type;

        if (!in_array($balance_type, ['balance', 'interest_balance'])) {
            return back()->with('error', 'Invalid Wallet Type');
        }

        if ($request->expired_time == 'expired') {
            return back()->with('error', 'Payment duration time is expired');
        }

        $buyer = $this->user;
        $offerLock = OfferLock::with('propertyOffer.property', 'propertyOffer.getInvestment', 'propertyOffer.propertyShare')->findOrFail($id);
        $amount = $offerLock->lock_amount;

        if ($amount > $buyer[$balance_type]) {
            return back()->with('error', 'Insufficient Balance');
        }


        // balance deduct from buyer
        $new_balance = $buyer[$balance_type] - $amount; // new balance = user new balance
        $buyer[$balance_type] = $new_balance;
        $buyer->total_invest += $request->amount;
        $buyer->save();
        $trx = strRandom();
        $remarks = 'Share purchesed on ' . optional(optional($offerLock->propertyOffer)->property)->title;

        BasicService::makeTransaction($buyer, $amount, 0, $trx_type = '-', $balance_type, $trx, $remarks);

        $offerLock->status = 1;
        $offerLock->save(); // payment completed

        $propertyOffer = $offerLock->propertyOffer;
        $propertyOffer->status = 1; // offer completed
        $propertyOffer->payment_status = 1; // offer completed
        $propertyOffer->save();

        $investment = (optional($offerLock->propertyOffer)->getInvestment);
        $investment->user_id = $buyer->id; // transfer share
        $investment->save();

        // balance add to seller
        $seller = User::findOrFail(optional($offerLock->propertyOffer)->offered_to);
        $seller_new_balance = $seller['interest_balance'] + $amount;
        $seller['interest_balance'] = $seller_new_balance;
        $seller->save();

        $trx = strRandom();
        $remarks = 'Sell investment share ' . optional(optional($offerLock->propertyOffer)->property)->title . ' ' . 'amount add to your interest balance';
        BasicService::makeTransaction($seller, $amount, 0, $trx_type = '+', 'interest_balance', $trx, $remarks);

        $propertyShare = (optional($offerLock->propertyOffer)->propertyShare);
        $propertyShare->status = 0;
        $propertyShare->save(); // remove share market

        return back()->with('success', 'A share of' . '`' . optional(optional($offerLock->propertyOffer)->property)->property_title . '`' . 'property has been successfully purchased.');
    }

    public function BuyShare(Request $request, $id)
    {

        $this->validate($request, [
            'balance_type' => 'required|string|max:50',
            'amount' => 'required|numeric',
        ]);

        $balance_type = $request->balance_type;
        if (!in_array($balance_type, ['balance', 'interest_balance'])) {
            return back()->with('error', 'Invalid Wallet Type');
        }

        $buyer = $this->user;
        $propertyShare = PropertyShare::with('property', 'getInvestment')->findOrFail($id);

        $amount = $propertyShare->amount;

        if ($amount > $buyer->$balance_type) {
            return back()->with('error', 'Insufficient Balance');
        }

        $new_balance = $buyer->$balance_type - $amount; // new balance = user new balance
        $buyer->$balance_type = $new_balance;
        $buyer->total_invest += $request->amount;
        $buyer->save();
        $trx = strRandom();
        $remarks = 'Share purchesed on ' . optional($propertyShare->property)->title;

        BasicService::makeTransaction($buyer, $amount, 0, $trx_type = '-', $balance_type, $trx, $remarks);

        $propertyShare->status = 0;
        $propertyShare->save();

        $investment = $propertyShare->getInvestment;
        $investment->user_id = $buyer->id; // transfer share
        $investment->save();

        // balance add to seller
        $seller = User::findOrFail($propertyShare->investor_id);
        $seller_new_balance = $seller['interest_balance'] + $amount;
        $seller['interest_balance'] = $seller_new_balance;
        $seller->save();

        $trx = strRandom();
        $remarks = 'Sell investment share ' . optional($propertyShare->property)->title . ' ' . 'amount add to your interest balance';
        BasicService::makeTransaction($seller, $amount, 0, $trx_type = '+', 'interest_balance', $trx, $remarks);

        return redirect()->route('user.propertyMarket', 'my-investment-properties')->with('success', 'A share of' . '`' . optional($propertyShare->property)->title . '`' . 'property has been successfully purchased.');
    }

    public function investProperty(Request $request, $id)
    {
        $rules = [
            'balance_type' => ['required', 'string', 'max:50', Rule::in(['balance', 'interest_balance'])],
            'amount' => 'required|numeric',
        ];
        $message = [
            'balance_type.*.required' => __('Please select your wallet'),
            'amount.required' => __('Amount field is required'),
        ];

        $validate = Validator::make($request->all(), $rules, $message);
        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }
        $user = $this->user;

        $property = Property::where('id', $id)->where('status', 1)->where('expire_date', '>', now())->first();
        if (!$property) {
            return back()->with('error', 'Invalid Invest Request');
        }
        if ($property->available_funding < $request->amount) {
            return back()->with('error', 'This property is fully funded. Investment is no longer available');
        }

        $balance_type = $request->balance_type;
        $pay_installment = $request->pay_installment;

        if (!in_array($balance_type, ['balance', 'interest_balance'])) {
            return back()->with('error', 'Invalid Wallet Type');
        }

        $amount = $request->amount;
        $basic = basicControl();

        if ($property->fixed_amount == null && $amount < $property->minimum_amount) {
            return back()->with('error', "Minimum Invest Limit " . getAmount($property->minimum_amount));
        } elseif ($property->fixed_amount == null && $amount > $property->maximum_amount) {
            return back()->with('error', "Maximum Invest Limit " . getAmount($property->maximum_amount));
        } elseif (($property->fixed_amount != null && $amount != $property->fixed_amount) && ($pay_installment == null && $property->available_funding != $amount)) {
            return back()->with('error', "Please invest " . getAmount($property->fixed_amount));
        } elseif ($pay_installment != null && $amount != $property->installment_amount) {

            return back()->with('error', "Please invest " . getAmount($property->installment_amount));
        }

        if ($amount > $user->$balance_type) {
            return back()->with('error', 'Insufficient Balance');
        }

        DB::beginTransaction();
        try {

            $new_balance = getAmount($user->$balance_type - $amount); // new balance = user new balance
            $user->$balance_type = $new_balance;
            $user->total_invest += $request->amount;
            $user->save();

            $trx = strRandom();
            $remarks = 'Invested On ' . $property->title;

            BasicService::makeTransaction($user, $amount, 0, '-', $balance_type, $trx, $remarks, $property);

            InvestmentService::profitLossCalculate($request, $property);
            InvestmentService::forAllInvest($user, $property, $amount, $pay_installment, $trx, $request);


            if ($basic->investment_commission == 1) {
                BasicService::setBonus($user, $request->amount, 'invest',$property);
            }

            DB::commit();

            $currentDate = Carbon::now();
            $msg = [
                'username' => $user->username,
                'amount' => getAmount($amount),
                'currency' => basicControl()->currency_symbol,
                'property_name' => $property->title
            ];

            $action = [
                "link" => route('admin.user.plan-purchaseLog', $user->id),
                "icon" => "fa fa-money-bill-alt"
            ];

            $userAction = [
                "link" => route('user.invest-history'),
                "icon" => "fa fa-money-bill-alt"
            ];

            $this->adminPushNotification('PROPERTY_INVEST_NOTIFY_TO_ADMIN', $msg, $action);
            $this->userPushNotification($user, 'PROPERTY_INVEST_NOTIFY_TO_USER', $msg, $userAction);

            $this->sendMailSms($user, $type = 'PROPERTY_INVEST_NOTIFY_TO_USER', [
                'username' => $user->username,
                'amount' => getAmount($amount),
                'currency' => basicControl()->currency_symbol,
                'property_name' => $property->title,
                'transaction_id' => $trx,
                'date' => $currentDate,
            ]);

            $this->adminMail($type = 'PROPERTY_INVEST_NOTIFY_TO_ADMIN', [
                'username' => $user->username,
                'amount' => getAmount($amount),
                'currency' => $basic->currency_symbol,
                'property_name' => $property->title,
                'transaction_id' => $trx,
                'date' => $currentDate,
            ]);

            return back()->with('success', '`' . $property->title . '`' . ' has been invested successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function investHistory(Request $request)
    {
        $search = $request->all();
        $fromDate = \Carbon\Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date)->addDay();

        $investments = $this->user->invests()
            ->with('property', 'propertyShare')
            ->has('property')
            ->when(isset($search['property']), function ($query) use ($search) {
                return $query->whereHas('property', function ($q) use ($search) {
                    $q->where('title', 'LIKE', '%' . $search['property'] . '%');
                });
            })
            ->when(isset($search['invest_status']), function ($query) use ($search) {
                return $query->where('invest_status', $search['invest_status']);
            })
            ->when(isset($search['return_status']), function ($query) use ($search) {
                return $query->where('status', $search['return_status']);
            })
            ->when(isset($search['from_date']), function ($q2) use ($fromDate) {
                return $q2->whereDate('created_at', '>=', $fromDate);
            })
            ->when(isset($search['to_date']), function ($q2) use ($fromDate, $toDate) {
                return $q2->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->latest()->paginate(basicControl()->paginate);

        return view(template() . 'user.transaction.investLog', compact('investments'));
    }

    public function investHistoryDetails($id)
    {
        $singleInvestDetails = Investment::with('property')->where('user_id', Auth::id())->findOrFail($id);
        return view(template() . 'user.transaction.investDetails', compact('singleInvestDetails'));
    }

    public function completeDuePayment(Request $request, $id)
    {
        $this->validate($request, [
            'balance_type' => 'required|string|max:50',
            'amount' => 'required|numeric',
        ]);

        $user = $this->user;
        $investment = Investment::findOrFail($id);
        $amount = $request->amount;

        $balance_type = $request->balance_type;
        $pay_installment = $request->pay_installment;

        $basic = basicControl();

        if (!in_array($balance_type, ['balance', 'interest_balance'])) {
            return back()->with('error', 'Invalid Wallet Type');
        }

        if ($amount > $user->$balance_type) {
            return back()->with('error', 'Insufficient Balance');
        }

        InvestmentService::dueInstallmentPayment($user, $request, $pay_installment, $investment, $amount, $balance_type);

        $trx = strRandom();
        $remarks = 'Due make payment On ' . optional($investment->property)->title;

        BasicService::makeTransaction($user, $amount, 0, $trx_type = '-', $balance_type, $trx, $remarks, $investment->property, $investment);

        if ($basic->investment_commission == 1) {
            BasicService::setBonus($user, getAmount($request->amount), $type = 'invest',$investment);
        }

        return back()->with('success', '`' . optional($investment->property)->title . '`' . ' due investment payment successfull.');
    }


}
