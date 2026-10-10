<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Amenity;
use App\Models\Investment;
use App\Models\InvestorReview;
use App\Models\OfferLock;
use App\Models\OfferReply;
use App\Models\Page;
use App\Models\Property;
use App\Models\PropertyOffer;
use App\Models\PropertyShare;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use App\Traits\Upload;
use Facades\App\Services\BasicService;
use Facades\App\Services\InvestmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    use ApiResponse, Notify, Upload;

    public function investmentProperties()
    {
        $basic = basicControl();
        $basicControl = [
            'currency_symbol' => $basic->currency_symbol,
            'base_currency' => $basic->base_currency,
            'logged_in_user_balance' => auth()->user()->balance,
            'logged_in_user_interest_balance' => auth()->user()->interest_balance,
            'is_share_investment' => $basic->is_share_investment,
            'text_for_understand' => 'if is_share_investment is 1 then show button `Share Market`, `My Share Properties`, `Send Offer`, `Receive Offer` if is_share_investment is 0 then dont show theme buttons'
        ];
        $data['basicControl'] = $basicControl;

        $properties = Property::with(['address', 'getReviews', 'favoritedByUser'])
            ->withCount(['getReviews'])
            ->where('expire_date', '>', now())
            ->where('status', 1)
            ->orderBy('start_date')
            ->paginate(10);

        $formattedProperties = $this->formatProperties($properties);
        $properties->setCollection($formattedProperties);

        $data['properties'] = $properties;

        return response()->json($this->withSuccess($data));
    }

    public function shareMarket()
    {
        $basic = basicControl();
        $basicControl = [
            'currency_symbol' => $basic->currency_symbol,
            'base_currency' => $basic->base_currency,
            'logged_in_user_balance' => auth()->user()->balance,
            'logged_in_user_interest_balance' => auth()->user()->interest_balance,
            'is_share_investment' => $basic->is_share_investment,
            'text_for_understand' => 'if is_share_investment is 1 then show button `Share Market`, `My Share Properties`, `Send Offer`, `Receive Offer` if is_share_investment is 0 then dont show theme buttons'
        ];

        $data['basicControl'] = $basicControl;

        $sharedProperties = PropertyShare::query()
            ->with(['getInvestment', 'property.getReviews', 'property.address', 'property.managetime', 'propertyOffer.offerlock', 'forAllLock'])
            ->where('status', 1)
            ->latest()
            ->paginate(10);


        $formattedSharedProperties = $sharedProperties->map(function ($shareProperty) {
            $propertyOfferButton = [];
            if ($shareProperty->propertyOffer) {
                $propertyOfferButton = [
                    'disabled_attribute' => true,
                    'button_text' => 'Already Offered',
                ];
            } else {
                $propertyOfferButton = [
                    'disabled_attribute' => optional($shareProperty->user)->id == Auth::id() ? true : false,
                    'button_text' => 'Make Offer',
                ];
            }

            $propertyShareButton = [];
            if (($shareProperty->propertyOffer && optional($shareProperty->propertyOffer)->offerlock) && (optional(optional($shareProperty->propertyOffer)->offerlock)->status == 0) || $shareProperty->forAllLock) {
                $propertyShareButton = [
                    'disabled_attribute' => optional($shareProperty->user)->id == Auth::id() ? true : false,
                    'button_text' => 'Share Locked',
                ];
            } else {
                $propertyShareButton = [
                    'disabled_attribute' => true,
                    'button_text' => 'Share Locked',
                ];
            }
            $property = $shareProperty->property;

            $property = [
                'id' => $property->id,
                'title' => $property->title,
                'image' => getFile($property->driver, $property->thumbnail),
                'address' => $property->address ? [
                    'id' => $property->address->id,
                    'title' => $property->address->title,
                ] : null,
                'fixed_amount' => $property->fixed_amount,
                'investment_amount' => $property->investmentAmount,
                'total_investment_amount' => $property->total_investment_amount,
                'total_reviews' => $property->reviews_count,
                'review_text' => $property->getReviewCountText(),
                'avg_rating' => fractionNumber($property->avg_rating),
                'profit' => $property->profit,
                'profit_type' => $property->profit_type,
                'profit_type_text' => $property->profit_type == 1 ? fractionNumber(getAmount($property->profit)) . '% Fixed' : basicControl()->currency_symbol . fractionNumber(getAmount($property->profit)),
                'favoritedByUser' => (bool)$property->favoritedByUser,
                'status' => $property->status,
                'property_offer_button' => $propertyOfferButton,
                'property_share_button' => $propertyShareButton,
            ];

            return [
                'id' => $shareProperty->id,
                'property_id' => $shareProperty->property_id,
                'investment_id' => $shareProperty->investment_id,
                'investor_id' => $shareProperty->investor_id,
                'amount' => $shareProperty->amount,
                'property' => $property,
                'property_owner' => $shareProperty->user ? [
                    'id' => $shareProperty->user->id,
                    'name' => $shareProperty->user->fullname,
                ] : null,
            ];
        });

        $sharedProperties->setCollection($formattedSharedProperties);
        $data['properties'] = $sharedProperties;

        return response()->json($this->withSuccess($data));

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
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        DB::beginTransaction();
        try {

            $propertyShare = PropertyShare::find($id);

            if (!$propertyShare) {
                return response()->json($this->withError('Property Share Not Found!'));
            }

            $propertyOffer = new PropertyOffer();

            $propertyOffer->property_share_id = $propertyShare->id;
            $propertyOffer->offered_from = \auth()->user()->id;
            $propertyOffer->offered_to = $propertyShare->investor_id;
            $propertyOffer->investment_id = $propertyShare->investment_id;
            $propertyOffer->property_id = $propertyShare->property_id;
            $propertyOffer->sell_amount = $propertyShare->amount;
            $propertyOffer->amount = $request->amount;
            $propertyOffer->description = $request->description;
            $propertyOffer->save();

            DB::commit();
            return response()->json($this->withSuccess('Offer Send Successfully!'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($this->withError($exception->getMessage()));
        }
    }

    public function directBuyShare(Request $request, $id)
    {
        $rules = [
            'balance_type' => 'required|string|max:50',
            'amount' => 'required|numeric',
        ];

        $message = [
            'balance_type.required' => __('Please select you wallet'),
            'amount.required' => __('Amount field is required!'),
        ];

        $validate = Validator::make($request->all(), $rules, $message);

        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $balance_type = $request->balance_type;

        if (!in_array($balance_type, ['balance', 'interest_balance'])) {
            return response()->json($this->withError('Invalid Wallet Type'));
        }

        $buyer = \auth()->user();
        $propertyShare = PropertyShare::with('property', 'getInvestment')->find($id);

        if (!$propertyShare) {
            return response()->json($this->withError('Property share not found'));
        }

        $amount = $propertyShare->amount;

        if ($amount > $buyer->$balance_type) {
            return response()->json($this->withError('Insufficient Balance'));
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

        return response()->json($this->withSuccess('Share successfully purchased'));
    }

    public function myProperties()
    {
        $basic = basicControl();
        $basicControl = [
            'currency_symbol' => $basic->currency_symbol,
            'base_currency' => $basic->base_currency,
            'logged_in_user_balance' => auth()->user()->balance,
            'logged_in_user_interest_balance' => auth()->user()->interest_balance,
            'is_share_investment' => $basic->is_share_investment,
            'text_for_understand' => 'if is_share_investment is 1 then show button `Share Market`, `My Share Properties`, `Send Offer`, `Receive Offer` if is_share_investment is 0 then dont show theme buttons'
        ];

        $data['basicControl'] = $basicControl;
        $myProperties = Investment::query()
            ->with(['property.getReviews', 'propertyShare', 'user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        $formattedMyProperties = $myProperties->map(function ($myProperty) {
            $propertySellShareButton = [];
            if ($myProperty->propertyShare) {
                $propertySellShareButton = [
                    'disabled_attribute' => true,
                    'button_text' => 'Already Shared',
                ];
            } else {
                if (optional($myProperty->property)->is_investor == 1 && basicControl()->is_share_investment == 1) {
                    $propertySellShareButton = [
                        'disabled_attribute' => ($myProperty->invest_status == 1 && $myProperty->status == 1) || ($myProperty->invest_status == 0) || (optional($myProperty->property)->is_investor == 0) ? true : false,
                        'button_text' => 'Sell Share',
                    ];
                } else {
                    $propertySellShareButton = [
                        'button_class' => 'optacity-0',
                        'text_for_understand' => 'if you see button_class variable then you add the opacity-0 for this button',
                    ];
                }
            }

            $property = $myProperty->property;
            $property = $property ? [
                'id' => $property->id,
                'title' => $property->title,
                'image' => getFile($property->driver, $property->thumbnail),
                'address' => $property->address ? [
                    'id' => $property->address->id,
                    'title' => $property->address->title,
                ] : null,
                'fixed_amount' => $property->fixed_amount,
                'investment_amount' => $property->investmentAmount,
                'total_investment_amount' => $property->total_investment_amount,
                'total_reviews' => $property->reviews_count,
                'review_text' => $property->getReviewCountText(),
                'avg_rating' => fractionNumber($property->avg_rating),
                'profit' => $property->profit,
                'profit_type' => $property->profit_type,
                'profit_type_text' => $property->profit_type == 1 ? fractionNumber(getAmount($property->profit)) . '% Fixed' : basicControl()->currency_symbol . fractionNumber(getAmount($property->profit)),
                'favoritedByUser' => (bool)$property->favoritedByUser,
                'status' => $property->status,
                'property_sell_share_button' => $propertySellShareButton,
            ] : null;

            $myProperty = [
                'id' => $myProperty->id,
                'user_id' => $myProperty->user_id,
                'property_id' => $myProperty->property_id,
                'amount' => $myProperty->amount,
                'status' => $myProperty->status,
                'invest_status' => $myProperty->invest_status,
                'status_text' => ($myProperty->status == 0 && $myProperty->invest_status == 1 ? 'Running' : ($myProperty->status == 1 && $myProperty->invest_status == 1 ? 'Completed' : 'Due')),
                'property' => $property,
            ];
            return $myProperty;
        });

        $myProperties->setCollection($formattedMyProperties);
        $data['my_properties'] = $myProperties;

        return response()->json($this->withSuccess($data));
    }

    public function propertyShareStore(Request $request, $id)
    {
        $rules = [
            'amount' => 'required',
        ];
        $message = [
            'amount.required' => __('Amount field is required!'),
        ];

        $validate = Validator::make($request->all(), $rules, $message);
        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $investment = Investment::select(['id', 'user_id', 'property_id'])->where('user_id', \auth()->user()->id)->find($id);

        if (!$investment) {
            return response()->json($this->withError('Investment not found'));
        }

        $propertyShare = new PropertyShare();
        $propertyShare->amount = $request->amount;
        $propertyShare->investment_id = $investment->id;
        $propertyShare->investor_id = $investment->user_id;
        $propertyShare->property_id = $investment->property_id;
        $propertyShare->save();

        return response()->json($this->withSuccess("Property share successfully! now it's visible share market"));
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

        $propertyShare = PropertyShare::where('investor_id', \auth()->user()->id)->find($id);

        if (!$propertyShare) {
            return response()->json($this->withError('Property share not found!'));
        }

        $propertyShare->amount = $request->amount;
        $propertyShare->save();

        return response()->json($this->withError('Share successfully updated!'));
    }

    public function propertyShareRemove($id)
    {
        $propertyShare = PropertyShare::where('investor_id', \auth()->user()->id)->find($id);
        $propertyShare->delete();
        return response()->json($this->withSuccess('Share removed successfully!'));
    }

    public function mySharedProperties()
    {
        $basic = basicControl();
        $basicControl = [
            'currency_symbol' => $basic->currency_symbol,
            'base_currency' => $basic->base_currency,
            'logged_in_user_balance' => auth()->user()->balance,
            'logged_in_user_interest_balance' => auth()->user()->interest_balance,
            'is_share_investment' => $basic->is_share_investment,
            'text_for_understand' => 'if is_share_investment is 1 then show button `Share Market`, `My Share Properties`, `Send Offer`, `Receive Offer` if is_share_investment is 0 then dont show theme buttons'
        ];
        $data['basicControl'] = $basicControl;

        $mySharedProperties = PropertyShare::query()
            ->with(['property','property.getReviews','property.address'])
            ->where('investor_id',auth()->id())
            ->where('status', 1)
            ->latest()
            ->paginate(10);

        $formattedMySharedProperties = $mySharedProperties->map(function ($mySharedProperty) {
            $property = $mySharedProperty->property;
            $property = $property ? [
                'id' => $property->id,
                'title' => $property->title,
                'image' => getFile($property->driver, $property->thumbnail),
                'address' => $property->address ? [
                    'id' => $property->address->id,
                    'title' => $property->address->title,
                ] : null,
                'fixed_amount' => $property->fixed_amount,
                'investment_amount' => $property->investmentAmount,
                'total_investment_amount' => $property->total_investment_amount,
                'total_reviews' => $property->reviews_count,
                'review_text' => $property->getReviewCountText(),
                'avg_rating' => fractionNumber($property->avg_rating),
                'profit' => $property->profit,
                'profit_type' => $property->profit_type,
                'profit_type_text' => $property->profit_type == 1 ? fractionNumber(getAmount($property->profit)) . '% Fixed' : basicControl()->currency_symbol . fractionNumber(getAmount($property->profit)),
                'favoritedByUser' => (bool)$property->favoritedByUser,
                'status' => $property->status,
            ] : null;

            $mySharedProperty = [
                'id' => $mySharedProperty->id,
                'investment_id' => $mySharedProperty->investment_id,
                'investor_id' => $mySharedProperty->investor_id,
                'property_id' => $mySharedProperty->property_id,
                'amount' => $mySharedProperty->amount,
                'property' => $property
            ];
            return $mySharedProperty;
        });

        $mySharedProperties->setCollection($formattedMySharedProperties);
        $data['my_shared_properties'] = $mySharedProperties;

        return response()->json($this->withSuccess($data));
    }

    public function sendOfferProperties()
    {
        $basic = basicControl();
        $basicControl = [
            'currency_symbol' => $basic->currency_symbol,
            'base_currency' => $basic->base_currency,
            'logged_in_user_balance' => auth()->user()->balance,
            'logged_in_user_interest_balance' => auth()->user()->interest_balance,
            'is_share_investment' => $basic->is_share_investment,
            'text_for_understand' => 'if is_share_investment is 1 then show button `Share Market`, `My Share Properties`, `Send Offer`, `Receive Offer` if is_share_investment is 0 then dont show theme buttons'
        ];
        $data['basicControl'] = $basicControl;

        $mySendOfferedProperties = PropertyOffer::query()
            ->with(['property', 'propertyShare'])
            ->where('offered_from',auth()->id())
            ->latest()
            ->paginate(10);

        $formattedMySendOfferedProperties = $mySendOfferedProperties->map(function ($mySendOfferProperty) {

            $updateOfferButton = [];
            if ($mySendOfferProperty->status == 0 && optional($mySendOfferProperty->propertyShare)->status == 1) {
                $updateOfferButton = [
                    'disabled_attribute' => false,
                    'button_text' => 'Update Offer',
                ];
            } elseif (optional($mySendOfferProperty->propertyShare)->status == 0 && $mySendOfferProperty->payment_status == 0) {
                $updateOfferButton = [
                    'disabled_attribute' => true,
                    'button_text' => 'Sold Out',
                ];
            }

            $property = $mySendOfferProperty->property;
            $property = $property ? [
                'id' => $property->id,
                'title' => $property->title,
                'image' => getFile($property->driver, $property->thumbnail),
                'address' => $property->address ? [
                    'id' => $property->address->id,
                    'title' => $property->address->title,
                ] : null,
                'fixed_amount' => $property->fixed_amount,
                'investment_amount' => $property->investmentAmount,
                'total_investment_amount' => $property->total_investment_amount,
                'total_reviews' => $property->reviews_count,
                'review_text' => $property->getReviewCountText(),
                'avg_rating' => fractionNumber($property->avg_rating),
                'profit' => $property->profit,
                'profit_type' => $property->profit_type,
                'profit_type_text' => $property->profit_type == 1 ? fractionNumber(getAmount($property->profit)) . '% Fixed' : basicControl()->currency_symbol . fractionNumber(getAmount($property->profit)),
                'favoritedByUser' => (bool)$property->favoritedByUser,
                'status' => $property->status,
                'update_offer_button' => $updateOfferButton,
            ] : null;

            $mySendOfferProperty = [
                'id' => $mySendOfferProperty->id,
                'property_share_id' => $mySendOfferProperty->property_share_id,
                'offered_from' => $mySendOfferProperty->offered_from,
                'offered_to' => $mySendOfferProperty->offered_to,
                'investment_id' => $mySendOfferProperty->investment_id,
                'property_id' => $mySendOfferProperty->property_id,
                'sell_amount' => $mySendOfferProperty->sell_amount,
                'offer_amount' => $mySendOfferProperty->amount,
                'description' => $mySendOfferProperty->description,
                'status' => $mySendOfferProperty->status,
                'payment_status' => $mySendOfferProperty->payment_status,
                'date' => customDateTime($mySendOfferProperty->created_at),
                'property' => $property
            ];
            return $mySendOfferProperty;
        });

        $mySendOfferedProperties->setCollection($formattedMySendOfferedProperties);

        $data['my_send_offered_properties'] = $mySendOfferedProperties;

        return response()->json($this->withSuccess($data));
    }

    public function propertyOfferRemove($id)
    {
        $propertyOffer = PropertyOffer::where('offered_from', \auth()->user()->id)->find($id);
        if ($propertyOffer == null) {
            $propertyOffer = PropertyOffer::where('offered_to', \auth()->user()->id)->findOrFail($id);
            $propertyOffer->delete();
        } else {
            $propertyOffer->delete();
        }

        return response()->json($this->withSuccess('Offer remove successfully!'));
    }

    public function receiveOfferProperties()
    {
        $basic = basicControl();
        $basicControl = [
            'currency_symbol' => $basic->currency_symbol,
            'base_currency' => $basic->base_currency,
            'logged_in_user_balance' => auth()->user()->balance,
            'logged_in_user_interest_balance' => auth()->user()->interest_balance,
            'is_share_investment' => $basic->is_share_investment,
            'text_for_understand' => 'if is_share_investment is 1 then show button `Share Market`, `My Share Properties`, `Send Offer`, `Receive Offer` if is_share_investment is 0 then dont show theme buttons'
        ];
        $data['basicControl'] = $basicControl;

        $receiveOfferProperties = PropertyOffer::query()
            ->with(['property', 'propertyShare', 'owner', 'user'])
            ->where('offered_to',auth()->id())
            ->withCount('propertyShare')
            ->groupBy('property_share_id')
            ->latest()
            ->paginate(10);

        $formattedReceiveOfferProperties = $receiveOfferProperties->map(function ($receiveOfferProperty) {

            $property = $receiveOfferProperty->property;
            $property = $property ? [
                'id' => $property->id,
                'title' => $property->title,
                'image' => getFile($property->driver, $property->thumbnail),
                'address' => $property->address ? [
                    'id' => $property->address->id,
                    'title' => $property->address->title,
                ] : null,
                'fixed_amount' => $property->fixed_amount,
                'investment_amount' => $property->investmentAmount,
                'total_investment_amount' => $property->total_investment_amount,
                'total_reviews' => $property->reviews_count,
                'review_text' => $property->getReviewCountText(),
                'avg_rating' => fractionNumber($property->avg_rating),
                'profit' => $property->profit,
                'profit_type' => $property->profit_type,
                'profit_type_text' => $property->profit_type == 1 ? fractionNumber(getAmount($property->profit)) . '% Fixed' : basicControl()->currency_symbol . fractionNumber(getAmount($property->profit)),
                'favoritedByUser' => (bool)$property->favoritedByUser,
                'status' => $property->status,
            ] : null;

            $myReceiveOfferProperty = [
                'id' => $receiveOfferProperty->id,
                'property_share_id' => $receiveOfferProperty->property_share_id,
                'offered_from' => $receiveOfferProperty->offered_from,
                'offered_to' => $receiveOfferProperty->offered_to,
                'investment_id' => $receiveOfferProperty->investment_id,
                'property_id' => $receiveOfferProperty->property_id,
                'sell_amount' => $receiveOfferProperty->sell_amount,
                'offer_amount' => $receiveOfferProperty->amount,
                'description' => $receiveOfferProperty->description,
                'status' => $receiveOfferProperty->status,
                'payment_status' => $receiveOfferProperty->payment_status,
                'total_offer' => $receiveOfferProperty->totalOfferList(),
                'date' => customDateTime($receiveOfferProperty->created_at),
                'property' => $property
            ];
            return $myReceiveOfferProperty;
        });

        $receiveOfferProperties->setCollection($formattedReceiveOfferProperties);
        $data['receive_offer_properties'] = $receiveOfferProperties;

        return response()->json($this->withSuccess($data));
    }

    public function investProperty(Request $request, $id)
    {
        $rules = [
            'balance_type' => ['required', 'string', 'max:50', Rule::in(['balance', 'interest_balance'])],
            'amount' => 'required|numeric',
            'pay_installment' => 'nullable',
        ];

        $message = [
            'balance_type.*.required' => __('Please select your wallet'),
            'amount.required' => __('Amount field is required'),
        ];

        $validate = Validator::make($request->all(), $rules, $message);

        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $user = \auth()->user();

        $property = Property::where('id', $id)->where('status', 1)->where('expire_date', '>', now())->first();

        if (!$property) {
            return response()->json($this->withError('Invalid Invest Request'));
        }

        $balance_type = $request->balance_type;
        $pay_installment = $request->pay_installment;

        if (!in_array($balance_type, ['balance', 'interest_balance'])) {
            return response()->json($this->withError('Invalid Wallet Type'));
        }

        $amount = $request->amount;
        $basic = basicControl();

        if ($property->fixed_amount == null && $amount < $property->minimum_amount) {
            return response()->json($this->withError("Minimum Invest Limit " . getAmount($property->minimum_amount)));
        } elseif ($property->fixed_amount == null && $amount > $property->maximum_amount) {
            return response()->json($this->withError("Maximum Invest Limit " . getAmount($property->maximum_amount)));
        } elseif (($property->fixed_amount != null && $amount != $property->fixed_amount) && ($pay_installment == null && $property->available_funding != $amount)) {
            return response()->json($this->withError("Please invest " . getAmount($property->fixed_amount)));
        } elseif ($pay_installment != null && $amount != $property->installment_amount) {
            return response()->json($this->withError("Please invest " . getAmount($property->installment_amount)));
        }

        if ($amount > $user->$balance_type) {
            return response()->json($this->withError("Insufficient Balance"));
        }

        DB::beginTransaction();

        try {

            $new_balance = $user->$balance_type - $amount;
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
            return response()->json($this->withSuccess($property->title . " has been invested successfully"));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function investHistory()
    {
        $user = \auth()->user();
        $basic = basicControl();
        $investments = $user->invests()->with('property', 'propertyShare')->latest()->paginate(basicControl()->paginate);

        $formatedInvestments = $investments->map(function ($investment) use ($basic) {

            if ($investment->invest_status == 0) {
                $upcomingPayment = 'After Installments complete';
            } elseif ($investment->invest_status == 1 && $investment->return_date == null && $investment->status == 1) {
                $upcomingPayment = 'All completed';
            } else {
                $upcomingPayment = customDate($investment->return_date);
            }

            return [
                'id' => $investment->id,
                'currency_symbol' => $basic->currency_symbol,
                'base_currency' => $basic->base_currency,
                'user_id' => $investment->user_id,
                'property_id' => $investment->property_id,
                'invest_status' => $investment->invest_status,
                'status' => $investment->status,
                'is_share_investment' => basicControl()->is_share_investment,

                'profit' => ($investment->invest_status == 1 ? ($investment->profit_type == 0 ? basicControl()->currency_symbol . fractionNumber(getAmount($investment->profit)) : basicControl()->currency_symbol . fractionNumber(getAmount($investment->net_profit))) : 'N/A'),
                'upcoming_payment' => $upcomingPayment,

                'property' => $investment->property ? [
                    'id' => $investment->property->id,
                    'title' => $investment->property->title,
                    'image' => getFile($investment->property->driver, $investment->property->thumbnail),
                    'investmentAmount' => $investment->property->investmentAmount,
                    'is_investor' => $investment->property->is_investor,
                ] : null,
                'installment' => $investment->is_installment ? [
                    'fixed_invest' => $basic->currency_symbol . getAmount($investment->property->fixed_amount),
                    'total_installments' => $investment->total_installments,
                    'due_installments' => $investment->due_installments,
                    'installment_amount' => $basic->currency_symbol . getAmount($investment->property->installment_amount),
                    'installment_duration' => $investment->property->installment_duration . ' ' . $investment->property->installment_duration_type,
                    'installment_last_date' => customDate($investment->next_installment_date_end),
                    'installment_late_fee' => $basic->currency_symbol . getAmount($investment->property->installment_late_fee),
                    'previous_total_pay' => basicControl()->currency_symbol . fractionNumber(getAmount($investment->amount)),
                    'total_due_amount' => $basic->currency_symbol . getAmount($investment->property->fixed_amount - $investment->amount),
                ] : null,
                'propertyShare' => $investment->propertyShare ? [
                    'id' => $investment->propertyShare->id,
                    'amount' => $investment->propertyShare->amount,
                ] : null,
            ];
        });

        $investments->setCollection($formatedInvestments);

        return response()->json($this->withSuccess($investments));
    }

    public function investHistoryDetails($id)
    {
        $user = \auth()->user();
        $basic = basicControl();

        $singleInvestDetails = Investment::with('property')->where('user_id', Auth::id())->find($id);

        if (!$singleInvestDetails) {
            return response()->json($this->withError('Investment Not Found'));
        }

        $formattedInvestDetails = [
            'id' => $singleInvestDetails->id,
            'currency_symbol' => $basic->currency_symbol,
            'base_currency' => $basic->base_currency,
            'investment_date' => dateTime($singleInvestDetails->created_at),
            'property_title' => $singleInvestDetails->property->title,
            'trx_id' => $singleInvestDetails->trx,
            'invest' => $basic->currency_symbol . fractionNumber(getAmount($singleInvestDetails->amount)),
            'profit' => $basic->currency_symbol . fractionNumber(getAmount($singleInvestDetails->net_profit)),
            'is_installment' => $singleInvestDetails->is_installment,
            'is_installment_details' => 'if is_installment is 1 then show the `[Total Installments, Due Installments, Next Installment Start, Next Installment End]` if is_installment is 0 then dont show these options',
            'total_installments' => $singleInvestDetails->total_installments,
            'due_installments' => $singleInvestDetails->due_installments,
            'next_installment_date_start' => dateTime($singleInvestDetails->next_installment_date_start),
            'next_installment_date_end' => dateTime($singleInvestDetails->next_installment_date_end),
            'profit_return_interval' => $singleInvestDetails->return_time . ' ' . $singleInvestDetails->return_time_type,
            'return_how_many_times' => $singleInvestDetails->how_many_times == null ? 'Lifetime' : $singleInvestDetails->how_many_times,
            'next_profit_return_date' => ($singleInvestDetails->invest_status == 0 ? 'After All Installment completed' : ($singleInvestDetails->invest_status == 1 && $singleInvestDetails->return_date == null && $singleInvestDetails->status == 1 ? 'Completed' : customDate($singleInvestDetails->return_date))),
            'last_profit_return_date' => ($singleInvestDetails->last_return_date != null ? customDate($singleInvestDetails->last_return_date) : 'N/A'),
            'investment_payment_status' => ($singleInvestDetails->invest_status == 1 ? 'Complete' : 'Due'),
            'profit_return_status' => (
            $singleInvestDetails->status == 1 && $singleInvestDetails->invest_status == 1
                ? 'Completed'
                : (
            $singleInvestDetails->status == 0 && $singleInvestDetails->invest_status == 0
                ? 'Upcoming'
                : (
            $singleInvestDetails->status == 0 && $singleInvestDetails->invest_status == 1
                ? 'Running'
                : ''
            )
            )
            ),
            'investment_status' => $singleInvestDetails->is_active == 1 ? 'Active' : 'Deactive',
        ];

        return response()->json($this->withSuccess($formattedInvestDetails));

    }


    public function completeDuePayment(Request $request, $id)
    {
        $rules = [
            'balance_type' => ['required', 'string', 'max:50', Rule::in(['balance', 'interest_balance'])],
            'amount' => 'required|numeric',
            'pay_installment' => 'nullable',
        ];

        $message = [
            'balance_type.*.required' => __('Please select your wallet'),
            'amount.required' => __('Amount field is required'),
        ];

        $validate = Validator::make($request->all(), $rules, $message);

        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $user = \auth()->user();
        $investment = Investment::findOrFail($id);
        $amount = $request->amount;

        $balance_type = $request->balance_type;
        $pay_installment = $request->pay_installment;

        $basic = basicControl();

        if (!in_array($balance_type, ['balance', 'interest_balance'])) {
            return response()->json($this->withError('Invalid Wallet Type'));
        }

        if ($amount > $user->$balance_type) {
            return response()->json($this->withError('Insufficient Balance'));
        }

        InvestmentService::dueInstallmentPayment($user, $request, $pay_installment, $investment, $amount, $balance_type);

        $trx = strRandom();
        $remarks = 'Due make payment On ' . optional($investment->property)->title;

        BasicService::makeTransaction($user, $amount, 0, $trx_type = '-', $balance_type, $trx, $remarks, $investment->property, $investment);

        if ($basic->investment_commission == 1) {
            BasicService::setBonus($user, getAmount($request->amount), $type = 'invest',$investment);
        }

        return response()->json($this->withSuccess('due investment payment successfull.'));
    }

    public function offerList($id)
    {
        $offerList = PropertyOffer::with(['getInvestment.propertyShare', 'property', 'user'])
            ->where('property_share_id', $id)->where('offered_to', \auth()->user()->id)->latest()->paginate(basicControl()->paginate);

        $formattedOfferList = $offerList->map(function ($offer) {
            return [
                'id' => $offer->id,
                'property_share_id' => $offer->property_share_id,
                'offered_from_user' => $offer->user ? [
                    'id' => $offer->user->id,
                    'name' => $offer->user->firstname . ' ' . $offer->user->lastname,
                ] : null,
                'sell_amount' => $offer->sell_amount,
                'offer_amount' => $offer->amount,
                'status' => ($offer->status == 0 ? 'Pending' : ($offer->status == 1 ? 'Accepted' : 'Rejected')),
            ];
        });

        $offerList->setCollection($formattedOfferList);

        return response()->json($this->withSuccess($offerList));
    }

    public function offerAccept($id)
    {
        $propertyOffer = PropertyOffer::where('offered_to', auth()->id())->find($id);
        if (!$propertyOffer) {
            return response()->json($this->withError('Offer not found'));
        }

        $propertyOffer->status = 1;
        $propertyOffer->save();
        return response()->json($this->withSuccess('Offer Accepted Successfully'));
    }

    public function offerReject($id)
    {
        $propertyOffer = PropertyOffer::with('offerlock')->where('offered_to', \auth()->user()->id)->find($id);

        if (!$propertyOffer) {
            return response()->json($this->withError('Offer not found'));
        }

        if ($propertyOffer->offerlock && $propertyOffer->lockInfo() != null) {
            $lockOffer = $propertyOffer->lockInfo();
            $lockOffer->status = 2;
            $lockOffer->save();
        }

        $propertyOffer->status = 2;
        $propertyOffer->save();
        return response()->json($this->withSuccess('Offer Rejected Successfully!'));
    }

    public function offerRemove($id)
    {
        $propertyOffer = PropertyOffer::where('offered_to', \auth()->user()->id)->find($id);

        if (!$propertyOffer) {
            return response()->json($this->withError('Offer not found'));
        }

        $propertyOffer->delete();
        return response()->json($this->withSuccess('Offer Deleted Successfully!'));
    }

    public function offerConversation($id)
    {
        $user = \auth()->user();
        $conversation = PropertyOffer::query()
            ->with(['property', 'propertyShare', 'owner', 'user', 'offerlock', 'receiveMyOffer', 'offerReply'])
            ->where('id', $id)->first();

        $formattedConversation = [
            'id' => $conversation->id,
            'currency_symbol' => basicControl()->currency_symbol,
            'base_currency' => basicControl()->base_currency,
            'property_id' => $conversation->property_id,
            'property_share_id' => $conversation->property_share_id,
            'investment_id' => $conversation->investment_id,
            'selling_amount' => $conversation->sell_amount,
            'offer_amount' => $conversation->amount,
            'question' => $conversation->description,
            'status' => $conversation->status,
            'payment_status' => $conversation->payment_status,
            'offer_information' => $conversation->property ? [
                'property_id' => $conversation->property->id,
                'property_name' => $conversation->property->title,
                'property_image' => getFile($conversation->property->driver, $conversation->property->thumbnail),
            ] : null,
            'offered_from_id' => $conversation->offered_from,
            'buyer_information' => $conversation->user ? [
                'id' => $conversation->user->id,
                'name' => $conversation->user->firstname . ' ' . $conversation->user->lastname,
                'image' => getFile($conversation->user->image_driver, $conversation->user->image),
                'phone' => $conversation->user->phone . ' ' . $conversation->user->phone,
                'email' => $conversation->user->email . ' ' . $conversation->user->email,
                'address' => $conversation->user->address . ' ' . $conversation->user->address,
            ] : null,
            'offered_to_id' => $conversation->offered_to,
            'owner_information' => $conversation->owner ? [
                'id' => $conversation->owner->id,
                'name' => $conversation->owner->firstname . ' ' . $conversation->owner->lastname,
                'image' => getFile($conversation->user->image_driver, $conversation->user->image),
                'phone' => $conversation->owner->phone . ' ' . $conversation->owner->phone,
                'email' => $conversation->owner->phone . ' ' . $conversation->owner->phone,
                'address' => $conversation->owner->address . ' ' . $conversation->owner->address,
            ] : null,
            'messages' => $conversation->offerReply ? $conversation->offerReply->map(function ($reply) {
                return [
                    'id' => $reply->id,
                    'sender_id' => $reply->sender_id,
                    'receiver_id' => $reply->receiver_id,
                    'message' => $reply->reply,
                    'image' => getFile($reply->driver, $reply->file),
                    'sent_at' => $reply->sent_at,
                ];
            }) : null,
            'payment_lock_information' => $conversation->lockInfo() ? [
                'id' => $conversation->lockInfo()->id,
                'lock_amount' => $conversation->lockInfo()->lock_amount,
                'duration' => $conversation->lockInfo()->duration,
            ] : null,
        ];


        $data['offer_conversation'] = $formattedConversation;

        return response()->json($this->withSuccess($data));
    }

    public function offerReplyMessage(Request $request)
    {
        $rules = [
            'message' => 'required',
            'file' => 'nullable|mimes:jpg,png,jpeg,PNG|max:10000',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $offerReply = new OfferReply();
        $offerReply->sender_id = \auth()->user()->id;
        $offerReply->receiver_id = $request->client_id;
        $offerReply->property_offer_id = $request->property_offer_id;
        $offerReply->reply = $request->message;

        if ($request->hasFile('file')) {
            $messageFile = $this->fileUpload($request->file, config('filelocation.message.path'), null, null, 'webp', null, null, null);
            if (!$messageFile) {
                return response()->json($this->withError('File could not be uploaded.'));
            }

            if ($messageFile) {
                $offerReply->file = $messageFile['path'];
                $offerReply->driver = $messageFile['driver'] ?? 'local';
            }
        }

        $offerReply->save();

        return response()->json($this->withSuccess('Reply send successfully'));

    }

    public function paymentLock(Request $request, $id)
    {
        $rules = [
            'amount' => 'required',
            'duration' => 'required',
        ];

        $messages = [
            'amount.required' => 'The amount field is required',
            'duration.required' => 'Payment Duration field is required',
        ];

        $validate = Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $propertyOffer = PropertyOffer::where('offered_to', \auth()->user()->id)->findOrFail($id);

        if ($propertyOffer->status == 2 || $propertyOffer->status == 0) {
            return response()->json($this->withError('Please accept the offer first. Then lock the payment'));
        }

        $offerLock = new OfferLock();
        $offerLock->property_offer_id = $id;
        $offerLock->property_share_id = $propertyOffer->property_share_id;
        $offerLock->offer_amount = $propertyOffer->amount;
        $offerLock->lock_amount = $request->amount;
        $offerLock->duration = $request->duration;

        $offerLock->save();
        return response()->json($this->withSuccess('Offer is locked successfully'));
    }

    public function paymentLockUpdate(Request $request, $id)
    {
        $rules = [
            'amount' => 'required',
            'duration' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $offerLock = OfferLock::find($id);

        if (!$offerLock) {
            return response()->json($this->withError('payment lock not found'));
        }

        $offerLock->lock_amount = $request->amount;
        $offerLock->duration = $request->duration;
        $offerLock->save();
        return response()->json($this->withSuccess('Payment lock successfully updated'));
    }


    public function property(Request $request, $type = null, $id = null)
    {
        $funding = Property::selectRaw('min(available_funding) as min_funding, max(available_funding) as max_funding')
            ->first();
        $min = $funding->min_funding;
        $max = $funding->max_funding;
        $minRange = $min;
        $maxRange = $max;

        if ($request->has('my_range')) {
            $range = explode(';', $request->my_range);
            $minRange = $range[0];
            $maxRange = $range[1];
        }
        $search = $request->all();
        $properties = Property::with(['address', 'managetime'])
            ->where('status', 1)
            ->whereDate('expire_date', '>', now())
            ->when(isset($search['name']), function ($query) use ($search) {
                return $query->whereRaw("title REGEXP '[[:<:]]{$search['name']}[[:>:]]'");
            })
            ->when(isset($search['location']), function ($query) use ($search) {
                return $query->whereHas('address', function ($query2) use ($search) {
                    $query2->where('id', 'LIKE', "%{$search['location']}%");
                });
            })
            ->when(isset($search['amenity_id']), function ($query) use ($search) {
                return $query->whereJsonContains('amenity_id', $search['amenity_id']);
            })
            ->when(!empty($search['rating']), function ($query) use ($search) {
                return $query->whereHas('getReviews', function ($query2) use ($search) {
                    $query2->whereIn('rating2', $search['rating']);
                });
            })
            ->when(isset($search['my_range']), function ($query) use ($search, $minRange, $maxRange) {
                $query->whereBetween('total_investment_amount', [$minRange, $maxRange]);
            })
            ->orderBy('properties.start_date')
            ->paginate(10);

        $formattedProperties = $this->formatProperties($properties);
        $properties->setCollection($formattedProperties);

        $data['properties'] = $properties;

        return response()->json($this->withSuccess($data));

    }

    public function propertyDetails($id)
    {
        $property = Property::with(['address', 'getInvestment.user', 'image'])
            ->where('status', 1)
            ->findOrFail($id);

        $investors = User::query()
            ->whereHas('invests', function ($query) use ($id) {
                $query->where('property_id', $id);
            })
            ->distinct()
            ->withCount('invests')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'username' => $user->username,
                    'email' => $user->email,
                    'address' => $user->address,
                    'image' => getFile($user->image_driver, $user->image),
                    'count_invest' => $user->invests_count,
                ];
            });

        $formattedProperty = $this->formatProperty($property);
        $formattedProperty['investors'] = $investors;
        $data['property'] = $formattedProperty;
        return response()->json($this->withSuccess($data));
    }
    private function formatProperties($properties)
    {
        return $properties->map(function ($property) {
            return [
                'id' => $property->id,
                'title' => $property->title,
                'image' => getFile($property->driver, $property->thumbnail),
                'address' => $property->address ? [
                    'id' => $property->address->id,
                    'title' => $property->address->title,
                ] : null,
                'fixed_amount' => $property->fixed_amount,
                'investment_amount' => $property->investmentAmount,
                'total_investment_amount' => $property->total_investment_amount,
                'total_reviews' => $property->reviews_count,
                'review_text' => $property->getReviewCountText(),
                'avg_rating' => fractionNumber($property->avg_rating),
                'profit' => $property->profit,
                'profit_type' => $property->profit_type,
                'profit_type_text' => $property->profit_type == 1 ? fractionNumber(getAmount($property->profit)) . '% Fixed' : basicControl()->currency_symbol . fractionNumber(getAmount($property->profit)),
                'favoritedByUser' => (bool)$property->favoritedByUser,
                'status' => $property->status,
            ];
        });
    }

    private function formatProperty($property)
    {
        return [
            'id' => $property->id,
            'title' => $property->title,
            'image' => getFile($property->driver, $property->thumbnail),
            'images' => $property->address ?
                $property->image->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'image' => getFile($image->driver, $image->image),
                    ];
                }) : null,
            'address' => $property->address ? [
                'id' => $property->address->id,
                'title' => $property->address->title,
            ] : null,
            'investment_start_date' => $property->start_date,
            'investment_expire_date' => $property->expire_date,
            'is_invest_type' => $property->is_invest_type,
            'is_invest_type_text' => $property->is_invest_type == 1 ? 'Fixed Invest' : 'Invest Range',
            'is_return_type' => $property->is_return_type,
            'is_return_type_text' => $property->is_return_type == 1 ? 'Lifetime' : $property->return_time . ' ' . $property->return_time_type,
            'is_installment' => $property->is_installment,
            'is_installment_text' => $property->is_installment == 1 ? 'Installment' : 'No Installment',
            'is_capital_back' => $property->is_capital_back,
            'is_capital_back_text' => $property->is_capital_back == 1 ? 'Yes' : 'No',
            'is_investor' => $property->is_investor,
            'is_payment' => $property->is_payment,
            'is_available_funding' => $property->is_available_funding,
            'fixed_amount' => $property->fixed_amount,
            'minimum_amount' => $property->minimum_amount,
            'maximum_amount' => $property->maximum_amount,
            'investment_amount' => $property->investmentAmount,
            'total_investment_amount' => $property->total_investment_amount,
            'available_funding' => $property->available_funding,
            'how_many_days' => $property->how_many_days,
            'how_many_times' => $property->how_many_times,
            'profit' => $property->profit,
            'profit_type' => $property->profit_type,
            'profit_type_text' => $property->profit_type == 1 ? fractionNumber(getAmount($property->profit)) . '% Fixed' : basicControl()->currency_symbol . fractionNumber(getAmount($property->profit)),
            'total_installments' => $property->total_installments,
            'installment_amount' => $property->installment_amount,
            'installment_duration' => $property->installment_duration,
            'installment_duration_type' => $property->installment_duration_type,
            'installment_late_fee' => $property->installment_late_fee,
            'details' => strip_tags($property->details),
            'video' => $property->video,
            'location' => $property->location,
            'total_reviews' => $property->reviews_count,
            'review_text' => $property->getReviewCountText(),
            'avg_rating' => fractionNumber($property->avg_rating),
            'reviews' => $property->getReviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => $review->user ? [
                        'id' => $review->user->id,
                        'firstname' => $review->user->firstname,
                        'lastname' => $review->user->lastname,
                        'username' => $review->user->username,
                        'email' => $review->user->email,
                        'image' => getFile($review->user->image_driver, $review->user->image),
                    ] : null,
                    'property_id' => $review->property_id,
                    'rating' => $review->rating2,
                    'review' => $review->review,
                    'date' => customDateTime($review->created_at),
                ];
            }),
            'favoritedByUser' => (bool)$property->favoritedByUser,
            'amenity' => $property->limitamenity(),
            'status' => $property->status,
            'profit_return_time' => $property->managetime ? [
                'id' => $property->managetime->id,
                'time' => $property->managetime->time,
                'time_type' => $property->managetime->time_type,
            ] : null,
            'invest_now_button' => [
                'disabled_attribute' => $property->rud()['upcomingProperties'] ? true : false,
                'button_text' => $property->rud()['upcomingProperties'] ? $property->rud()['difference']->d . 'D ' . $property->rud()['difference']->h . 'H ' . $property->rud()['difference']->i . 'M' : 'Invest Now',
            ],
            'shared_properties' => $property->propertyShare
        ];

    }

}
