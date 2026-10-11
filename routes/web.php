<?php

use App\Http\Controllers\Auth\LoginController as UserLoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\User\FavouriteController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\MoneyTransferController;
use App\Http\Controllers\User\PayoutController;
use App\Http\Controllers\ManualRecaptchaController;
use App\Http\Controllers\khaltiPaymentController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\PropertyController;
use App\Http\Controllers\User\RankController;
use App\Http\Controllers\User\ReferralController;
use App\Http\Controllers\User\SendMailController;
use App\Http\Controllers\User\SocialiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InAppNotificationController;
use App\Http\Controllers\User\SupportTicketController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\VerificationController;
use App\Http\Controllers\User\KycVerificationController;
use App\Http\Controllers\TwoFaSecurityController;
use App\Http\Controllers\API\PaymentController as ApiPayment;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
*/

$basicControl = basicControl();

Route::get('payment/view/{deposit_id}', [ApiPayment::class, 'paymentView'])->name('paymentView');

Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('lang', $locale);
    $back = url()->previous();
    // Avoid redirecting to instruction-page, admin, or external URLs
    $appUrl = config('app.url');
    if (
        !$back
        || !str_starts_with($back, $appUrl)
        || str_contains($back, '/instruction')
        || str_contains($back, '/admin')
        || str_contains($back, '/language/')
    ) {
        return redirect('/');
    }
    return redirect($back);
})->name('language');


Route::get('clear', function () {
    Illuminate\Support\Facades\Artisan::call('optimize:clear');
    $previousUrl = url()->previous();
    $keywords = ['push-notification','ajax'];
    if (array_filter($keywords, fn($keyword) => str_contains($previousUrl, $keyword))) {
        return redirect('/')->with('success', 'Cache Cleared Successfully');
    }
    return redirect()->back(fallback: '/')->with('success', 'Cache Cleared Successfully');
})->name('clear');

Route::get('maintenance-mode', function () {
    if (!basicControl()->is_maintenance_mode) {
        return redirect(route('page'));
    }
    $data['maintenanceMode'] = \App\Models\MaintenanceMode::first();
    return view(template() . 'maintenance', $data);
})->name('maintenance');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPassword'])->name('user.password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset.update');

Route::get('instruction/page', function () {
    return redirect('/');
})->name('instructionPage');

Route::group(['middleware' => ['maintenanceMode']], function () use ($basicControl) {
    Route::group(['middleware' => ['guest']], function () {
        Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserLoginController::class, 'login'])->name('login.submit');
        Route::get('register/{sponsor?}', [RegisterController::class, 'sponsor'])->name('register.sponsor');

        Route::get('auth/{socialite}', [SocialiteController::class, 'socialiteLogin'])->name('socialiteLogin');
        Route::get('auth/callback/{socialite}', [SocialiteController::class, 'socialiteCallback'])->name('socialiteCallback');
    });

    Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {

        Route::get('check', [VerificationController::class, 'check'])->name('check');
        Route::get('resend_code', [VerificationController::class, 'resendCode'])->name('resend.code');
        Route::post('mail-verify', [VerificationController::class, 'mailVerify'])->name('mail.verify');
        Route::post('sms-verify', [VerificationController::class, 'smsVerify'])->name('sms.verify');
        Route::post('twoFA-Verify', [VerificationController::class, 'twoFAverify'])->name('twoFA-Verify');

        Route::middleware('userCheck')->group(function () {

            Route::middleware('kyc')->group(function () {
                // user Property Market
                Route::get('/property-market/{type?}', [PropertyController::class, 'propertyMarket'])->name('propertyMarket');
                Route::post('/property-share-store/{id}', [PropertyController::class, 'propertyShareStore'])->name('propertyShareStore');
                Route::post('/property-share-update/{id}', [PropertyController::class, 'propertyShareUpdate'])->name('propertyShareUpdate');
                Route::delete('/property-share-remove/{id}', [PropertyController::class, 'propertyShareRemove'])->name('propertyShareRemove');
                Route::post('/property-offer-update/{id}', [PropertyController::class, 'propertyOfferUpdate'])->name('propertyOfferUpdate');
                Route::delete('/property-offer-remove/{id}', [PropertyController::class, 'propertyOfferRemove'])->name('propertyOfferRemove');
                Route::get('/offer-list/{id}', [PropertyController::class, 'offerList'])->name('offerList');
                Route::get('/offer-accept/{id}', [PropertyController::class, 'offerAccept'])->name('offerAccept');
                Route::get('/offer-reject/{id}', [PropertyController::class, 'offerReject'])->name('offerReject');
                Route::delete('/offer-remove/{id}', [PropertyController::class, 'offerRemove'])->name('offerRemove');
                Route::get('/offer-conversation/{id}', [PropertyController::class, 'offerConversation'])->name('offerConversation');
                Route::post('/offer/reply/message/render/', [PropertyController::class, 'offerReplyMessageRender'])->name('offerReplyMessageRender');
                Route::post('/offer/reply/message', [PropertyController::class, 'offerReplyMessage'])->name('offerReplyMessage');
                Route::post('/offer/payment/lock/{id}', [PropertyController::class, 'paymentLock'])->name('paymentLock');
                Route::post('/offer/payment/lock/update/{id}', [PropertyController::class, 'paymentLockUpdate'])->name('paymentLockUpdate');
                Route::get('/payment/lock/cancel/{id}', [PropertyController::class, 'paymentLockCancel'])->name('paymentLockCancel');
                Route::post('/payment/lock/confirm/{id}', [PropertyController::class, 'paymentLockConfirm'])->name('paymentLockConfirm');
                Route::post('/buy/share/{id}', [PropertyController::class, 'BuyShare'])->name('directBuyShare');

                // Invest History
                Route::get('invest-history', [PropertyController::class, 'investHistory'])->name('invest-history');
                Route::get('invest-history-details/{id}', [PropertyController::class, 'investHistoryDetails'])->name('invest-history-details');
                Route::put('/complete-due-payment/{id}', [PropertyController::class, 'completeDuePayment'])->name('completeDuePayment');
                Route::post('/invest-property/{id}', [PropertyController::class, 'investProperty'])->name('invest-property');
                Route::post('/property-make-offer/{id}', [PropertyController::class, 'propertyMakeOfferStore'])->name('propertyMakeOfferStore');

                // wishlist section
                Route::post('/wish-list', [FavouriteController::class, 'wishList'])->name('wishList');
                Route::get('/wish-list-property', [FavouriteController::class, 'wishListProperty'])->name('wishListProperty');
                Route::delete('/wish-list-delete/{id?}', [FavouriteController::class, 'wishListDelete'])->name('wishListDelete');

                // money-transfer
                Route::get('/money-transfer', [MoneyTransferController::class, 'moneyTransfer'])->name('money.transfer');
                Route::post('/money-transfer-store', [MoneyTransferController::class, 'moneyTransferStore'])->name('money.transfer.store');
                Route::get('/money/transfer/history', [MoneyTransferController::class, 'moneyTransferHistory'])->name('money.transfer.history');

                // referral
                Route::get('/referral', [ReferralController::class, 'referral'])->name('referral');
                Route::get('/referral-bonus', [ReferralController::class, 'referralBonus'])->name('referral.bonus');
                Route::get('/referral-bonus-search', [ReferralController::class, 'referralBonusSearch'])->name('referral.bonus.search');
                Route::post('get-referral-user',[ReferralController::class,'getReferralUser'])->name('myGetDirectReferralUser');

                //ranks
                Route::get('/ranks', [RankController::class, 'ranks'])->name('ranks');

                // review
                Route::post('/property-details/review', [FrontendController::class, 'reviewPush'])->name('review.push');

                Route::post('/send-message-to-property-investor', [SendMailController::class, 'sendMessageToPropertyInvestor'])->name('sendMessageToPropertyInvestor');

                Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
                Route::post('save-token', [HomeController::class, 'saveToken'])->name('save.token');
                Route::get('add-fund', [HomeController::class, 'addFund'])->name('add.fund');
                Route::get('fund/history', [HomeController::class, 'fundHistory'])->name('fund.history');
                Route::get('fund-history-search', [HomeController::class, 'fundHistorySearch'])->name('fund.history.search');

                Route::get('transaction', [HomeController::class, 'transaction'])->name('transaction');
                Route::get('transaction-search', [HomeController::class, 'transactionSearch'])->name('transaction.search');

                /* ===== Manage Two Step ===== */
                Route::get('two-step-security', [TwoFaSecurityController::class, 'twoStepSecurity'])->name('twostep.security');
                Route::post('twoStep-enable', [TwoFaSecurityController::class, 'twoStepEnable'])->name('twoStepEnable');
                Route::post('twoStep-disable', [TwoFaSecurityController::class, 'twoStepDisable'])->name('twoStepDisable');
                Route::post('twoStep/re-generate', [TwoFaSecurityController::class, 'twoStepRegenerate'])->name('twoStepRegenerate');


                /* PAYMENT REQUEST BY USER */
                Route::get('payout/history', [PayoutController::class, 'payoutHistory'])->name('payout.history');
                Route::get('payout/history/search', [PayoutController::class, 'payoutHistorySearch'])->name('payout.history.search');

                Route::get('payout', [PayoutController::class, 'payout'])->name('payout');
                Route::get('payout-supported-currency', [PayoutController::class, 'payoutSupportedCurrency'])->name('payout.supported.currency');
                Route::get('payout-check-amount', [PayoutController::class, 'checkAmount'])->name('payout.checkAmount');
                Route::post('request-payout', [PayoutController::class, 'payoutRequest'])->name('payout.request');

                Route::match(['get', 'post'], 'confirm-payout/{trx_id}', [PayoutController::class, 'confirmPayout'])->name('payout.confirm');
                Route::post('confirm-payout/flutterwave/{trx_id}', [PayoutController::class, 'flutterwavePayout'])->name('payout.flutterwave');
                Route::post('confirm-payout/paystack/{trx_id}', [PayoutController::class, 'paystackPayout'])->name('payout.paystack');
                Route::get('payout-check-limit', [PayoutController::class, 'checkLimit'])->name('payout.checkLimit');
                Route::post('payout-bank-form', [PayoutController::class, 'getBankForm'])->name('payout.getBankForm');
                Route::post('payout-bank-list', [PayoutController::class, 'getBankList'])->name('payout.getBankList');

                /* ===== Push Notification ===== */
                Route::get('push-notification-show', [InAppNotificationController::class, 'show'])->name('push.notification.show');
                Route::get('push.notification.readAll', [InAppNotificationController::class, 'readAll'])->name('push.notification.readAll');
                Route::get('push-notification-readAt/{id}', [InAppNotificationController::class, 'readAt'])->name('push.notification.readAt');

                Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
                    Route::get('/', [SupportTicketController::class, 'index'])->name('list');
                    Route::get('/create', [SupportTicketController::class, 'create'])->name('create');
                    Route::post('/create', [SupportTicketController::class, 'store'])->name('store');
                    Route::get('/view/{ticket}', [SupportTicketController::class, 'ticketView'])->name('view');
                    Route::put('/reply/{ticket}', [SupportTicketController::class, 'reply'])->name('reply');
                    Route::get('/download/{ticket}', [SupportTicketController::class, 'download'])->name('download');
                });

            });

            Route::get('verification/kyc', [KycVerificationController::class, 'kyc'])->name('verification.kyc');
            Route::get('verification/kyc-form/{id}', [KycVerificationController::class, 'kycForm'])->name('verification.kyc.form');
            Route::post('verification/kyc/submit', [KycVerificationController::class, 'verificationSubmit'])->name('kyc.verification.submit');
            Route::get('verification/kyc/history', [KycVerificationController::class, 'history'])->name('verification.kyc.history');

            Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
            Route::post('profile-update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
            Route::post('profile/image/update', [ProfileController::class, 'profileImageUpdate'])->name('profileImageUpdate');
            Route::put('/updateInformation', [ProfileController::class, 'updateInformation'])->name('updateInformation');

            Route::get('password/setting', [ProfileController::class, 'passwordSetting'])->name('password.setting');
            Route::post('update/password', [ProfileController::class, 'updatePassword'])->name('updatePassword');
        });
    });


    Route::get('captcha', [ManualRecaptchaController::class, 'reCaptCha'])->name('captcha');

    /* Manage User Deposit */
    Route::get('supported-currency', [DepositController::class, 'supportedCurrency'])->name('supported.currency');
    Route::post('payment-request', [DepositController::class, 'paymentRequest'])->name('payment.request');
    Route::get('deposit-check-amount', [DepositController::class, 'checkAmount'])->name('deposit.checkAmount');

    Route::get('payment-process/{trx_id}', [PaymentController::class, 'depositConfirm'])->name('payment.process');
    Route::post('addFundConfirm/{trx_id}', [PaymentController::class, 'fromSubmit'])->name('addFund.fromSubmit');
    Route::match(['get', 'post'], 'success', [PaymentController::class, 'success'])->name('success');
    Route::match(['get', 'post'], 'failed', [PaymentController::class, 'failed'])->name('failed');

    Route::post('khalti/payment/verify/{trx}', [\App\Http\Controllers\khaltiPaymentController::class, 'verifyPayment'])->name('khalti.verifyPayment');
    Route::post('khalti/payment/store', [khaltiPaymentController::class, 'storePayment'])->name('khalti.storePayment');

    // Wiki pages
    Route::get('wiki/nlp-labs-vietnam', [\App\Http\Controllers\WikiController::class, 'nlpLabsVietnam'])->name('wiki.nlp-labs-vietnam');

    Route::get('blog', [FrontendController::class, 'blog'])->name('blog');
    Route::get('blog-details/{slug}', [FrontendController::class, 'blogDetails'])->name('blog.details');
    Route::get('/blog-category/{id}/{slug?}', [FrontendController::class, 'categoryWiseBlog'])->name('Category.wise.blog');
    Route::get('blog-search', [FrontendController::class, 'blogSearch'])->name('blog.search');

    //property
    Route::get('/property', [FrontendController::class, 'property'])->name('property');
    Route::get('/property/details/{id}/{slug}', [FrontendController::class, 'propertyDetails'])->name('property.details');
    Route::get('/investor-profile/{id}/{username}', [FrontendController::class, 'investorProfile'])->name('investor.profile');

    //Subscribe
    Route::post('subscribe', [FrontendController::class, 'subscribe'])->name('subscribe');
    Route::post('/contact/send', [FrontendController::class, 'contactSend'])->name('contact.send');

    Auth::routes();

    // NLP Group & site named routes
    Route::get('/about',            [FrontendController::class, 'page'])->defaults('slug', 'about')->name('about');
    Route::get('/nlp-energy',       [FrontendController::class, 'page'])->defaults('slug', 'nlp-energy')->name('nlp-energy');
    Route::get('/nlp-ev-charging',  [FrontendController::class, 'page'])->defaults('slug', 'nlp-ev-charging')->name('nlp-ev-charging');
    Route::get('/nlp-technology',   [FrontendController::class, 'page'])->defaults('slug', 'nlp-technology')->name('nlp-technology');
    Route::get('/nlp-esg-carbon',   [FrontendController::class, 'page'])->defaults('slug', 'nlp-esg-carbon')->name('nlp-esg-carbon');
    Route::get('/ecosystem',        [FrontendController::class, 'page'])->defaults('slug', 'ecosystem')->name('ecosystem');

    /*= Frontend Manage Controller =*/
    Route::get("/{slug?}", [FrontendController::class, 'page'])->name('page');
});
