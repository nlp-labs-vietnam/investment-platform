<?php

use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PayoutLogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\MoneyTransferController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\RankingController;
use App\Http\Controllers\Api\SupportTicketController;
use App\Http\Controllers\Api\TwoFaSecurityController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\NotificationPermissionController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PayoutController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('onboard-list', [HomeController::class, 'onBoardList']);

//Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// reset password
Route::post('/reset/password/email', [AuthController::class, 'emailPasswordReset']);
Route::post('/reset/password/code', [AuthController::class, 'codePasswordReset']);
Route::post('/password/reset', [AuthController::class, 'passwordReset']);

Route::middleware('auth:sanctum')->group(function () {

    /*--Verification--*/
    Route::post('twofa-verify', [VerificationController::class, 'twoFAverify']);
    Route::post('mail-verify', [VerificationController::class, 'mailVerify']);
    Route::post('sms-verify', [VerificationController::class, 'smsVerify']);
    Route::get('resend-code', [VerificationController::class, 'resendCode']);

    //dashboard
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('language/{id?}', [HomeController::class, 'language']);

    //Add Fund Payment Start
    Route::get('add/fund', [DepositController::class, 'addFund']);
    Route::get('deposit/check/amount', [DepositController::class, 'checkAmount']);
    //Automatic Payment
    Route::post('payment/request', [DepositController::class, 'paymentRequest']);
    Route::post('payment/done', [DepositController::class, 'paymentDone']);
    //Card Payment
    Route::post('card/payment', [DepositController::class, 'cardPayment']);
    //Other Payment
    Route::post('other/payment', [DepositController::class, 'otherPayment']);
    //Manual Payment
    Route::post('manual/payment', [DepositController::class, 'manualPayment']);

    //Payout
    Route::get('payout', [PayoutController ::class, 'payout']);
    Route::get('payout/check/amount', [PayoutController::class, 'checkAmount']);
    Route::post('payout/get/bank/list', [PayoutController::class, 'payoutGetBankList']);
    Route::post('payout/get/bank/from', [PayoutController::class, 'payoutGetBankFrom']);
    Route::post('payout/paystack/submit', [PayoutController::class, 'payoutPaystackSubmit']);
    Route::post('payout/flutterwave/submit', [PayoutController::class, 'payoutFlutterwaveSubmit']);
    Route::post('payout/submit/confirm', [PayoutController::class, 'payoutSubmit']);

    //optional start
    Route::get('payment/process/{trx_id}', [PaymentController::class, 'depositConfirm']);
    Route::post('add/fund/confirm/{trx_id}', [PaymentController::class, 'fromSubmit']);

    // invest history
    Route::post('invest/property/{id}', [PropertyController::class, 'investProperty']);
    Route::get('invest/history', [PropertyController::class, 'investHistory']);
    Route::get('invest/history/details/{id}', [PropertyController::class, 'investHistoryDetails']);
    Route::post('complete/due/payment/{id}', [PropertyController::class, 'completeDuePayment'])->name('completeDuePayment');

    // transactions
    Route::get('transaction', [HomeController::class, 'transaction']);
    Route::get('fund-list', [HomeController::class, 'fundList']);
    Route::get('payout-list', [HomeController::class, 'payoutList']);

    // collections
    Route::get('wishlists', [CollectionController::class, 'wishlist']);
    Route::post('wishlist/add', [CollectionController::class, 'addWishlist']);
    Route::post('wishlist/remove', [CollectionController::class, 'removeWishlist']);

    Route::post('wishlist/delete', [CollectionController::class, 'wishListDelete']);
    Route::delete('wishlist/delete/{id?}', [CollectionController::class, 'wishListDelete']);

    //money transfer
    Route::get('money/transfer', [MoneyTransferController::class, 'moneyTransfer']);
    Route::post('money/transfer/store', [MoneyTransferController::class, 'moneyTransferStore']);
    Route::get('money/transfer/history', [MoneyTransferController::class, 'moneyTransferHistory']);

    //referral
    Route::get('referral', [ReferralController::class, 'referral']);
    Route::get('referral/bonus', [ReferralController::class, 'referralBonus']);

    //rankings
    Route::get('rankings', [RankingController::class, 'rankings']);

    //support ticket
    Route::post('support/ticket/store', [SupportTicketController::class, 'supportTicketStore']);
    Route::get('support/ticket/list', [SupportTicketController::class, 'supportTicketList']);
    Route::get('support/ticket/view/{ticket}', [SupportTicketController::class, 'supportTicketView']);
    Route::post('support/ticket/reply/{ticket}', [SupportTicketController::class, 'supportTicketReply']);
    Route::patch('close-ticket/{id}', [SupportTicketController::class, 'closeTicket']);

    // Two-step security
    Route::get('two/step/security', [TwoFaSecurityController::class, 'twoStepSecurity']);
    Route::post('two/step/enable', [TwoFaSecurityController::class, 'twoStepEnable']);
    Route::post('two/step/disable', [TwoFaSecurityController::class, 'twoStepDisable']);

    //User Profile
    Route::get('user/profile', [ProfileController::class, 'profile']);
    Route::post('update/user/profile', [ProfileController::class, 'updateProfile']);
    Route::post('change/password', [ProfileController::class, 'changePassword']);
    Route::post('delete-account', [ProfileController::class, 'deleteAccount']);

    //kyc
    Route::get('kycs', [ProfileController::class, 'kycs']);
    Route::post('kyc-submit', [ProfileController::class, 'kycVerificationSubmit']);

    //Notification Permission
    Route::get('notification/permission', [NotificationPermissionController::class, 'notificationPermission']);
    Route::post('notification/permission/update', [NotificationPermissionController::class, 'notificationPermissionUpdate']);
    Route::get('pusher/configuration', [NotificationPermissionController::class, 'pusherConfiguration']);

    //Property Market
    Route::get('investment/properties', [PropertyController::class, 'investmentProperties']);
    Route::get('share/market', [PropertyController::class, 'shareMarket']);
    Route::post('property/make/offer/{id}', [PropertyController::class, 'propertyMakeOfferStore']);
    Route::post('direct/buy/share/{id}', [PropertyController::class, 'directBuyShare']);
    Route::get('my/properties', [PropertyController::class, 'myProperties']);
    Route::post('property/share/store/{id}', [PropertyController::class, 'propertyShareStore']);
    Route::get('my/shared/properties', [PropertyController::class, 'mySharedProperties']);
    Route::post('property/share/update/{id}', [PropertyController::class, 'propertyShareUpdate']);
    Route::delete('property/share/remove/{id}', [PropertyController::class, 'propertyShareRemove']);
    Route::get('send/offer/properties', [PropertyController::class, 'sendOfferProperties']);
    Route::delete('property/offer/remove/{id}', [PropertyController::class, 'propertyOfferRemove']);
    Route::get('receive/offer/properties', [PropertyController::class, 'receiveOfferProperties']);

    Route::get('property', [PropertyController::class, 'property']);
    Route::get('property-details/{id}', [PropertyController::class, 'propertyDetails']);
    Route::get('/investor-profile/{id}/{username}', [PropertyController::class, 'investorProfile']);

    //Offer List
    Route::get('offer/list/{id}', [PropertyController::class, 'offerList'])->name('offerList');
    Route::post('offer/accept/{id}', [PropertyController::class, 'offerAccept'])->name('offerAccept');
    Route::post('offer/reject/{id}', [PropertyController::class, 'offerReject'])->name('offerReject');
    Route::delete('offer/remove/{id}', [PropertyController::class, 'offerRemove'])->name('offerRemove');

    Route::get('offer/conversation/{id}', [PropertyController::class, 'offerConversation']);
    Route::post('offer/reply/message', [PropertyController::class, 'offerReplyMessage']);
    Route::post('offer/payment/lock/{id}', [PropertyController::class, 'paymentLock'])->name('paymentLock');
    Route::post('offer/payment/lock/update/{id}', [PropertyController::class, 'paymentLockUpdate']);
    Route::get('payment/lock/cancel/{id}', [PropertyController::class, 'paymentLockCancel'])->name('paymentLockCancel');
    Route::post('payment/lock/confirm/{id}', [PropertyController::class, 'paymentLockConfirm'])->name('paymentLockConfirm');
});


Route::get('/propertyReviews/{id?}', [FrontendController::class, 'getReview'])->name('api-propertyReviews');

Route::match(['get', 'post'], 'payment/{code}/{trx?}/{type?}', [PaymentController::class, 'gatewayIpn'])->name('ipn');
Route::post('payout/{code}', [PayoutLogController::class, 'payout'])->name('payout');

