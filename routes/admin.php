<?php

use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\BasicControlController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailConfigController;
use App\Http\Controllers\Admin\FirebaseConfigController;
use App\Http\Controllers\Admin\InvestmentController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LogoController;
use App\Http\Controllers\Admin\ManageMenuController;
use App\Http\Controllers\Admin\ManualGatewayController;
use App\Http\Controllers\Admin\PaymentLogController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PayoutLogController;
use App\Http\Controllers\Admin\PayoutMethodController;
use App\Http\Controllers\Admin\PluginController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyImageController;
use App\Http\Controllers\Admin\PusherConfigController;
use App\Http\Controllers\Admin\RankController;
use App\Http\Controllers\Admin\ReferralCommissionController;
use App\Http\Controllers\Admin\RolesPermissionController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SmsConfigController;
use App\Http\Controllers\Admin\StorageController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\TransactionLogController;
use App\Http\Controllers\Admin\TranslateAPISettingController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\AmenitiesController;
use App\Http\Controllers\InAppNotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProfileSettingController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\MaintenanceModeController;
use App\Http\Controllers\Admin\NotificationTemplateController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\SocialiteController as AdminSocialiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('queue-work', function () {
    return Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

Route::get('schedule-run', function () {
    return Illuminate\Support\Facades\Artisan::call('schedule:run');
})->name('schedule:run');

$basicControl = basicControl();
Route::group(['prefix' => $basicControl->admin_prefix ?? 'admin', 'as' => 'admin.'], function () {
    Route::get('/themeMode/{themeType?}', function ($themeType = 'true') {
        session()->put('themeMode', $themeType);
        return $themeType;
    })->name('themeMode');

    /*== Authentication Routes ==*/
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest:admin');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request')
        ->middleware('guest:admin');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset')->middleware('guest:admin');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset.update');

    Route::get('/403', [DashboardController::class, 'forbidden'])->name('403');


    Route::middleware(['auth:admin','demo'])->group(function () {
        // Profile
        Route::get('profile', [AdminProfileSettingController::class, 'profile'])->name('profile');
        Route::put('profile', [AdminProfileSettingController::class, 'profileUpdate'])->name('profile.update');
        Route::put('password', [AdminProfileSettingController::class, 'passwordUpdate'])->name('password.update');
        Route::post('notification-permission', [AdminProfileSettingController::class, 'notificationPermission'])->name('notification.permission');

        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::post('save-token', [DashboardController::class, 'saveToken'])->name('save.token');


        Route::middleware('permission')->group(function () {

            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

            //profit schedule
            Route::get('profit/schedule', [ScheduleController::class, 'profitSchedule'])->name('profit.schedule');
            Route::post('profit/schedule/store', [ScheduleController::class, 'profitScheduleStore'])->name('profit.schedule.store');
            Route::post('profit/schedule/update/{id}', [ScheduleController::class, 'profitScheduleUpdate'])->name('profit.schedule.update');
            Route::delete('profit/schedule/delete/{id}', [ScheduleController::class, 'profitScheduleDelete'])->name('profit.schedule.delete');

            //amenities
            Route::get('amenities', [AmenitiesController::class, 'amenities'])->name('amenities');
            Route::get('amenities/create', [AmenitiesController::class, 'amenitiesCreate'])->name('amenities.create');
            Route::post('amenities/store', [AmenitiesController::class, 'amenitiesStore'])->name('amenities.store');
            Route::get('amenities/edit/{id}', [AmenitiesController::class, 'amenitiesEdit'])->name('amenities.edit');
            Route::post('amenities/update/{id}', [AmenitiesController::class, 'amenitiesUpdate'])->name('amenities.update');
            Route::delete('amenities/delete/{id}', [AmenitiesController::class, 'amenitiesDelete'])->name('amenities.delete');

            // Addresses
            Route::get('addresses', [AddressController::class, 'addresses'])->name('addresses');
            Route::get('address/create', [AddressController::class, 'addressCreate'])->name('address.create');
            Route::post('address/store', [AddressController::class, 'addressStore'])->name('address.store');
            Route::get('address/edit/{id}', [AddressController::class, 'addressEdit'])->name('address.edit');
            Route::post('address/update/{id}', [AddressController::class, 'addressUpdate'])->name('address.update');
            Route::delete('address/delete/{id}', [AddressController::class, 'addressDelete'])->name('address.delete');

            //properties
            Route::get('properties/{type}', [PropertyController::class, 'properties'])->name('properties');
            Route::get('property/create', [PropertyController::class, 'propertyCreate'])->name('property.create');
            Route::post('property/store', [PropertyController::class, 'propertyStore'])->name('property.store');
            Route::get('property/edit/{id}', [PropertyController::class, 'propertyEdit'])->name('property.edit');
            Route::post('property/update/{id}', [PropertyController::class, 'propertyUpdate'])->name('property.update');
            Route::get('property/details/{id}', [PropertyController::class, 'propertyDetails'])->name('property.details');
            Route::delete('property/delete/{id}', [PropertyController::class, 'propertyDelete'])->name('property.delete');

            //wishlist
            Route::get('property/wishlist', [PropertyController::class, 'propertyWishlist'])->name('property.wishlist');
            Route::delete('property/wishlist/delete/{id}', [PropertyController::class, 'propertyWishlistDelete'])->name('property.wishlist.delete');

            // Property Image Manager (AJAX)
            Route::delete('property/image/{id}', [PropertyImageController::class, 'destroy'])->name('property.image.destroy');
            Route::patch('property/image/reorder', [PropertyImageController::class, 'reorder'])->name('property.image.reorder');
            Route::patch('property/image/{id}/meta', [PropertyImageController::class, 'updateMeta'])->name('property.image.meta');
            Route::post('property/image/{id}/crop', [PropertyImageController::class, 'crop'])->name('property.image.crop');

            // Property SEO
            Route::post('property/{id}/seo', [PropertyController::class, 'seoStore'])->name('property.seo.store');

            // Property Revisions
            Route::get('property/{id}/revisions', [PropertyController::class, 'revisions'])->name('property.revisions');
            Route::post('property/rollback/{revId}', [PropertyController::class, 'rollback'])->name('property.rollback');

            // Manage Investments
            Route::get('investments', [InvestmentController::class, 'investments'])->name('investments');
            Route::get('investments/details/{id}', [InvestmentController::class, 'investmentDetails'])->name('investment.details');
            Route::post('investments/active/{id}', [InvestmentController::class, 'investmentActive'])->name('investment.active');
            Route::post('investments/deactive/{id}', [InvestmentController::class, 'investmentDeactive'])->name('investment.deactive');

            Route::get('running/investments', [InvestmentController::class, 'runningInvestments'])->name('running.investments');
            Route::get('running/investment/details/{id}', [InvestmentController::class, 'runningInvestmentDetails'])->name('running.investment.details');

            Route::get('due/investments', [InvestmentController::class, 'dueInvestments'])->name('due.investments');
            Route::get('due/investment/details/{id}', [InvestmentController::class, 'dueInvestmentDetails'])->name('due.investment.details');

            Route::get('expired/investments', [InvestmentController::class, 'expiredInvestments'])->name('expired.investments');
            Route::get('expired/investment/details/{id}', [InvestmentController::class, 'expiredInvestmentDetails'])->name('expired.investment.details');

            Route::get('completed/investments', [InvestmentController::class, 'completedInvestments'])->name('completed.investments');
            Route::get('completed/investment/details/{id}', [InvestmentController::class, 'completedInvestmentDetails'])->name('completed.investment.details');

            // Manage Rank
            Route::get('ranks', [RankController::class, 'ranks'])->name('ranks');
            Route::get('rank/create', [RankController::class, 'rankCreate'])->name('rank.create');
            Route::post('rank/store', [RankController::class, 'rankStore'])->name('rank.store');
            Route::get('rank/edit/{id}', [RankController::class, 'rankEdit'])->name('rank.edit');
            Route::post('rank/update/{id}', [RankController::class, 'rankUpdate'])->name('rank.update');
            Route::delete('rank/delete/{id}', [RankController::class, 'rankDelete'])->name('rank.delete');
            Route::get('rank/bonus', [RankController::class, 'rankBonus'])->name('rank.bonus');
            Route::post('rank/bonus/update', [RankController::class, 'rankBonusUpdate'])->name('rank.bonus.update');

            // Manage Referral Commission
            Route::get('referral/commission', [ReferralCommissionController::class, 'referralCommission'])->name('referral.commission');
            Route::post('referral/commission/store', [ReferralCommissionController::class, 'referralCommissionStore'])->name('referral.commission.store');
            Route::post('referral/commission/action', [ReferralCommissionController::class, 'referralCommissionAction'])->name('referral.commission.action');

            /*====Manage Users ====*/
            Route::get('login/as/user/{id}', [UsersController::class, 'loginAsUser'])->name('login.as.user');
            Route::post('block-profile/{id}', [UsersController::class, 'blockProfile'])->name('block.profile');
            Route::get('users/{state?}', [UsersController::class, 'index'])->name('users');
            Route::get('user/edit/{id}', [UsersController::class, 'userEdit'])->name('user.edit');

            Route::post('users-delete-multiple', [UsersController::class, 'deleteMultiple'])->name('user.delete.multiple');
            Route::post('user/update/{id}', [UsersController::class, 'userUpdate'])->name('user.update');
            Route::post('user/email/{id}', [UsersController::class, 'EmailUpdate'])->name('user.email.update');
            Route::post('user/username/{id}', [UsersController::class, 'usernameUpdate'])->name('user.username.update');
            Route::post('user/update-balance/{id}', [UsersController::class, 'updateBalanceUpdate'])->name('user.update.balance');
            Route::post('user/password/{id}', [UsersController::class, 'passwordUpdate'])->name('user.password.update');
            Route::post('user/preferences/{id}', [UsersController::class, 'preferencesUpdate'])->name('user.preferences.update');
            Route::post('user/two-fa-security/{id}', [UsersController::class, 'userTwoFaUpdate'])->name('user.twoFa.update');
            Route::post('user/balance-update/{id}', [UsersController::class, 'userBalanceUpdate'])->name('user-balance-update');

            Route::get('user/send-email/{id}', [UsersController::class, 'sendEmail'])->name('send.email');
            Route::post('user/send-email/{id?}', [UsersController::class, 'sendMailUser'])->name('user.email.send');
            Route::get('mail-all-user', [UsersController::class, 'mailAllUser'])->name('mail.all.user');

            /* ====== Transaction Log =====*/
            Route::get('transaction', [TransactionLogController::class, 'transaction'])->name('transaction');

            Route::get('commission', [TransactionLogController::class, 'commission'])->name('commission');

            /* ====== Payment Log =====*/
            Route::get('payment/log', [PaymentLogController::class, 'index'])->name('payment.log');
            Route::get('payment/pending', [PaymentLogController::class, 'pending'])->name('payment.pending');
            Route::get('payment/pending/request', [PaymentLogController::class, 'paymentRequest'])->name('payment.request');
            Route::put('payment/action/{id}', [PaymentLogController::class, 'action'])->name('payment.action');

            /* ====== Payout Log =====*/
            Route::get('withdraw/log', [PayoutLogController::class, 'index'])->name('payout.log');
            Route::get('withdraw/pending', [PayoutLogController::class, 'pending'])->name('payout.pending');
            Route::get('withdraw/pending/request', [PayoutLogController::class, 'payoutRequest'])->name('payout.request');
            Route::put('withdraw/action/{id}', [PayoutLogController::class, 'action'])->name('payout.action');

            /*= MANAGE KYC =*/
            Route::get('kyc-setting/list', [KycController::class, 'index'])->name('kyc.form.list');
            Route::get('kyc-setting/create', [KycController::class, 'create'])->name('kyc.create');
            Route::post('manage-kyc/store', [KycController::class, 'store'])->name('kyc.store');
            Route::get('manage-kyc/edit/{id}', [KycController::class, 'edit'])->name('kyc.edit');
            Route::post('manage-kyc/update/{id}', [KycController::class, 'update'])->name('kyc.update');
            Route::get('kyc/{status?}', [KycController::class, 'userKycList'])->name('kyc.list');
            Route::get('kyc/view/{id}', [KycController::class, 'view'])->name('kyc.view');
            Route::post('user/kyc/action/{id}', [KycController::class, 'action'])->name('kyc.action');

            /* ===== ADMIN SUPPORT TICKET ===== */
            Route::get('tickets/{status?}', [SupportTicketController::class, 'tickets'])->name('ticket');
            Route::get('tickets-view/{id}', [SupportTicketController::class, 'ticketView'])->name('ticket.view');
            Route::put('ticket-reply/{id}', [SupportTicketController::class, 'ticketReplySend'])->name('ticket.reply');
            Route::get('ticket-download/{ticket}', [SupportTicketController::class, 'ticketDownload'])->name('ticket.download');
            Route::post('ticket-closed/{id}', [SupportTicketController::class, 'ticketClosed'])->name('ticket.closed');
            Route::post('ticket-delete', [SupportTicketController::class, 'ticketDelete'])->name('ticket.delete');

            // Subscribers
            Route::get('subscriber/list', [SubscriberController::class, 'subscribers'])->name('subscribers');
            Route::get('subscriber/delete', [SubscriberController::class, 'subscriberDelete'])->name('subscriber.delete');

            // Manage Roles & Permissions
            Route::get('role/list', [RolesPermissionController::class, 'roleList'])->name('role');
            Route::get('create/role', [RolesPermissionController::class, 'createRole'])->name('createRole');
            Route::post('role/store', [RolesPermissionController::class, 'roleStore'])->name('roleStore');
            Route::get('edit/role/{id}', [RolesPermissionController::class, 'editRole'])->name('editRole');
            Route::post('role/update/{id}', [RolesPermissionController::class, 'roleUpdate'])->name('roleUpdate');
            Route::delete('delete/role/{id}', [RolesPermissionController::class, 'deleteRole'])->name('deleteRole');
            Route::get('manage/staffs', [RolesPermissionController::class, 'staffList'])->name('role.staff');
            Route::get('manage/staffs/login/{id}', [RolesPermissionController::class, 'staffLogin'])->name('role.staffLogin');
            Route::get('manage/staffs/edit/{id}', [RolesPermissionController::class, 'staffEdit'])->name('role.staffEdit');
            Route::get('manage/staffs/create', [RolesPermissionController::class, 'staffCreate'])->name('role.staffCreate');
            Route::post('manage/staffs/store', [RolesPermissionController::class, 'staffStore'])->name('role.staffStore');
            Route::post('manage/staffs/update/{id}', [RolesPermissionController::class, 'updateStaff'])->name('role.updateStaff');
            Route::delete('delete/staff/{id}', [RolesPermissionController::class, 'staffDelete'])->name('staffDelete');
            Route::get('role/search', [RolesPermissionController::class, 'roleSearch'])->name('roleSearch');
            Route::get('staff/search', [RolesPermissionController::class, 'staffSearch'])->name('staffSearch');

            /*== Control Panel ==*/
            Route::get('settings/{settings?}', [BasicControlController::class, 'index'])->name('settings');
            Route::get('basic-control', [BasicControlController::class, 'basicControl'])->name('basic.control');
            Route::post('basic-control-update', [BasicControlController::class, 'basicControlUpdate'])->name('basic.control.update');
            Route::post('basic-control-activity-update', [BasicControlController::class, 'basicControlActivityUpdate'])->name('basic.control.activity.update');
            Route::get('currency-exchange-api-config', [BasicControlController::class, 'currencyExchangeApiConfig'])->name('currency.exchange.api.config');
            Route::post('currency-exchange-api-config/update', [BasicControlController::class, 'currencyExchangeApiConfigUpdate'])->name('currency.exchange.api.config.update');

            /* ===== ADMIN SOCIALITE ===== */
            Route::get('socialite', [AdminSocialiteController::class, 'index'])->name('socialite.index');
            Route::match(['get', 'post'], 'google-config', [AdminSocialiteController::class, 'googleConfig'])->name('google.control');
            Route::match(['get', 'post'], 'facebook-config', [AdminSocialiteController::class, 'facebookConfig'])->name('facebook.control');
            Route::match(['get', 'post'], 'github-config', [AdminSocialiteController::class, 'githubConfig'])->name('github.control');

            /* ===== STORAGE ===== */
            Route::get('storage', [StorageController::class, 'index'])->name('storage.index');
            Route::any('storage/edit/{id}', [StorageController::class, 'edit'])->name('storage.edit');
            Route::any('storage/update/{id}', [StorageController::class, 'update'])->name('storage.update');
            Route::post('storage/set-default/{id}', [StorageController::class, 'setDefault'])->name('storage.setDefault');

            /* ===== Maintenance Mode ===== */
            Route::get('maintenance-mode', [MaintenanceModeController::class, 'index'])->name('maintenance.index');
            Route::post('maintenance-mode/update', [MaintenanceModeController::class, 'maintenanceModeUpdate'])->name('maintenance.mode.update');

            /* ===== LOGO, FAVICON UPDATE ===== */
            Route::get('logo-setting', [LogoController::class, 'logoSetting'])->name('logo.settings');
            Route::post('logo-update', [LogoController::class, 'logoUpdate'])->name('logo.update');


            /* ===== FIREBASE CONFIG ===== */
            Route::get('firebase-config', [FirebaseConfigController::class, 'firebaseConfig'])->name('firebase.config');
            Route::post('firebase-config-update', [FirebaseConfigController::class, 'firebaseConfigUpdate'])->name('firebase.config.update');
            Route::post('firebase-config-/file-upload', [FirebaseConfigController::class, 'firebaseConfigFileUpload'])->name('firebase.config.file.upload');
            Route::get('firebase-config-/file-download', [FirebaseConfigController::class, 'firebaseConfigFileDownload'])->name('firebase.config.file.download');

            /* ===== PUSHER CONFIG ===== */
            Route::get('pusher-config', [PusherConfigController::class, 'pusherConfig'])->name('pusher.config');
            Route::post('pusher-config-update', [PusherConfigController::class, 'pusherConfigUpdate'])->name('pusher.config.update');

            /* ===== EMAIL CONFIG ===== */
            Route::get('email-controls', [EmailConfigController::class, 'emailControls'])->name('email.control');
            Route::get('email-config/edit/{method}', [EmailConfigController::class, 'emailConfigEdit'])->name('email.config.edit');
            Route::post('email-config/update/{method}', [EmailConfigController::class, 'emailConfigUpdate'])->name('email.config.update');
            Route::post('email-config/set-as-default/{method}', [EmailConfigController::class, 'emailSetAsDefault'])->name('email.set.default');
            Route::post('test.email', [EmailConfigController::class, 'testEmail'])->name('test.email');


            /* Notification Templates Routes */
            Route::match(['get', 'post'], 'default-template', [NotificationTemplateController::class, 'defaultTemplate'])->name('email.template.default');
            Route::get('email-templates', [NotificationTemplateController::class, 'emailTemplates'])->name('email.templates');
            Route::get('email-template/edit/{id}', [NotificationTemplateController::class, 'editEmailTemplate'])->name('email.template.edit');
            Route::put('email-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updateEmailTemplate'])->name('email.template.update');

            Route::get('sms-templates', [NotificationTemplateController::class, 'smsTemplates'])->name('sms.templates');
            Route::get('sms-template/edit/{id}', [NotificationTemplateController::class, 'editSmsTemplate'])->name('sms.template.edit');
            Route::put('sms-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updateSmsTemplate'])->name('sms.template.update');

            Route::get('in-app-notification-templates', [NotificationTemplateController::class, 'inAppNotificationTemplates'])
                ->name('in.app.notification.templates');
            Route::get('in-app-notification-template/edit/{id}', [NotificationTemplateController::class, 'editInAppNotificationTemplate'])
                ->name('in.app.notification.template.edit');
            Route::put('in-app-notification-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updateInAppNotificationTemplate'])
                ->name('in.app.notification.template.update');
            Route::get('push-notification-templates', [NotificationTemplateController::class, 'pushNotificationTemplates'])->name('push.notification.templates');
            Route::get('push-notification-template/edit/{id}', [NotificationTemplateController::class, 'editPushNotificationTemplate'])->name('push.notification.template.edit');
            Route::put('push-notification-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updatePushNotificationTemplate'])->name('push.notification.template.update');


            /* ===== EMAIL CONFIG ===== */
            Route::get('sms-configuration', [SmsConfigController::class, 'index'])->name('sms.controls');
            Route::get('sms-config-edit/{method}', [SmsConfigController::class, 'smsConfigEdit'])->name('sms.config.edit');
            Route::post('sms-config-update/{method}', [SmsConfigController::class, 'smsConfigUpdate'])->name('sms.config.update');
            Route::post('sms-method-update/{method}', [SmsConfigController::class, 'manualSmsMethodUpdate'])->name('manual.sms.method.update');
            Route::post('sms-config/set-as-default/{method}', [SmsConfigController::class, 'smsSetAsDefault'])->name('sms.set.default');

            /* ===== PLUGIN CONFIG ===== */
            Route::get('plugin', [PluginController::class, 'pluginConfig'])->name('plugin.config');
            Route::get('plugin/tawk', [PluginController::class, 'tawkConfiguration'])->name('tawk.configuration');
            Route::post('plugin/tawk/Configuration/update', [PluginController::class, 'tawkConfigurationUpdate'])->name('tawk.configuration.update');
            Route::get('plugin/fb-messenger-configuration', [PluginController::class, 'fbMessengerConfiguration'])->name('fb.messenger.configuration');
            Route::post('plugin/fb-messenger-configuration/update', [PluginController::class, 'fbMessengerConfigurationUpdate'])->name('fb.messenger.configuration.update');
            Route::get('plugin/google-recaptcha', [PluginController::class, 'googleRecaptchaConfiguration'])->name('google.recaptcha.configuration');
            Route::post('plugin/google-recaptcha/update', [PluginController::class, 'googleRecaptchaConfigurationUpdate'])->name('google.recaptcha.Configuration.update');
            Route::get('plugin/google-analytics', [PluginController::class, 'googleAnalyticsConfiguration'])->name('google.analytics.configuration');
            Route::post('plugin/google-analytics', [PluginController::class, 'googleAnalyticsConfigurationUpdate'])->name('google.analytics.configuration.update');
            Route::get('plugin/manual-recaptcha', [PluginController::class, 'manualRecaptcha'])->name('manual.recaptcha');
            Route::post('plugin/manual-recaptcha/update', [PluginController::class, 'manualRecaptchaUpdate'])->name('manual.recaptcha.update');
            Route::post('plugin/active-recaptcha', [PluginController::class, 'activeRecaptcha'])->name('active.recaptcha');
            Route::get('plugin/popup-chat', [PluginController::class, 'popupChatConfiguration'])->name('popup.chat.configuration');
            Route::post('plugin/popup-chat/update', [PluginController::class, 'popupChatConfigurationUpdate'])->name('popup.chat.configuration.update');

            /* ===== ADMIN GOOGLE API SETTING ===== */
            Route::get('translate-api-setting', [TranslateAPISettingController::class, 'translateAPISetting'])->name('translate.api.setting');
            Route::get('translate-api-config/edit/{method}', [TranslateAPISettingController::class, 'translateAPISettingEdit'])->name('translate.api.config.edit');
            Route::post('translate-api-setting/update/{method}', [TranslateAPISettingController::class, 'translateAPISettingUpdate'])->name('translate.api.setting.update');
            Route::post('translate-api-setting/set-as-default/{method}', [TranslateAPISettingController::class, 'translateSetAsDefault'])->name('translate.set.default');


            /* ===== ADMIN LANGUAGE SETTINGS ===== */
            Route::get('languages', [LanguageController::class, 'index'])->name('language.index');
            Route::get('language/create', [LanguageController::class, 'create'])->name('language.create');
            Route::post('language/store', [LanguageController::class, 'store'])->name('language.store');
            Route::get('language/edit/{id}', [LanguageController::class, 'edit'])->name('language.edit');
            Route::put('language/update/{id}', [LanguageController::class, 'update'])->name('language.update');
            Route::delete('language-delete/{id}', [LanguageController::class, 'destroy'])->name('language.delete');
            Route::put('change-language-status/{id}', [LanguageController::class, 'changeStatus'])->name('change.language.status');


            Route::get('{short_name}/keywords', [LanguageController::class, 'keywords'])->name('language.keywords');
            Route::post('language-keyword/{short_name}', [LanguageController::class, 'addKeyword'])->name('add.language.keyword');
            Route::put('language-keyword/{short_name}/{key}', [LanguageController::class, 'updateKeyword'])->name('update.language.keyword');
            Route::delete('language-keyword/{short_name}/{key}', [LanguageController::class, 'deleteKeyword'])->name('delete.language.keyword');
            Route::post('language-import-json', [LanguageController::class, 'importJson'])->name('language.import.json');
            Route::put('update-key/{language}', [LanguageController::class, 'updateKey'])->name('language.update.key');
            Route::post('language/keyword/translate', [LanguageController::class, 'singleKeywordTranslate'])->name('single.keyword.translate');
            Route::post('language/all-keyword/translate/{shortName}', [LanguageController::class, 'allKeywordTranslate'])->name('all.keyword.translate');


            /* ===== InAppNotificationController SETTINGS ===== */
            Route::get('push-notification-show', [InAppNotificationController::class, 'showByAdmin'])->name('push.notification.show');
            Route::get('push.notification.readAll', [InAppNotificationController::class, 'readAllByAdmin'])->name('push.notification.readAll');
            Route::get('push-notification-readAt/{id}', [InAppNotificationController::class, 'readAt'])->name('push.notification.readAt');

            /* PAYMENT METHOD MANAGE BY ADMIN*/
            Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment.methods');
            Route::get('edit-payment-methods/{id}', [PaymentMethodController::class, 'edit'])->name('edit.payment.methods');
            Route::put('update-payment-methods/{id}', [PaymentMethodController::class, 'update'])->name('update.payment.methods');
            Route::post('sort-payment-methods', [PaymentMethodController::class, 'sortPaymentMethods'])->name('sort.payment.methods');
            Route::post('payment-methods/deactivate', [PaymentMethodController::class, 'deactivate'])->name('payment.methods.deactivate');


            /*=* MANUAL METHOD MANAGE BY ADMIN *=*/
            Route::get('payment-methods/manual', [ManualGatewayController::class, 'index'])->name('deposit.manual.index');
            Route::get('payment-methods/manual/create', [ManualGatewayController::class, 'create'])->name('deposit.manual.create');
            Route::post('payment-methods/manual/store', [ManualGatewayController::class, 'store'])->name('deposit.manual.store');
            Route::get('payment-methods/manual/edit/{id}', [ManualGatewayController::class, 'edit'])->name('deposit.manual.edit');
            Route::put('payment-methods/manual/update/{id}', [ManualGatewayController::class, 'update'])->name('deposit.manual.update');

            /*= PAYOUT METHOD MANAGE BY ADMIN =*/
            Route::get('withdraw-methods/list', [PayoutMethodController::class, 'index'])->name('payout.method.list');
            Route::get('withdraw-methods/create', [PayoutMethodController::class, 'create'])->name('payout.method.create');
            Route::post('withdraw-methods/store', [PayoutMethodController::class, 'store'])->name('payout.method.store');
            Route::get('withdraw-methods/manual/edit/{id}', [PayoutMethodController::class, 'manualEdit'])->name('payout.manual.method.edit');
            Route::get('withdraw-methods/edit/{id}', [PayoutMethodController::class, 'edit'])->name('payout.method.edit');
            Route::put('withdraw-methods/update/{id}', [PayoutMethodController::class, 'update'])->name('payout.method.update');
            Route::post('withdraw-methods/auto-update/{id}', [PayoutMethodController::class, 'autoUpdate'])->name('payout.method.auto.update');
            Route::post('withdraw-methods/active-deactivate', [PayoutMethodController::class, 'activeDeactivate'])->name('payout.active.deactivate');
            Route::get('withdraw-methods/days/setting', [PayoutMethodController::class, 'withdrawDays'])->name('payout.withdraw.days');
            Route::post('withdraw-methods/days/setting', [PayoutMethodController::class, 'withdrawDaysUpdate'])->name('withdrawal.days.update');

            /*= Frontend Manage =*/
            Route::get('frontend/pages/{theme}', [PageController::class, 'index'])->name('page.index');
            Route::get('frontend/create-page/{theme}', [PageController::class, 'create'])->name('create.page');
            Route::post('frontend/create-page/store/{theme}', [PageController::class, 'store'])->name('create.page.store');
            Route::get('frontend/edit-page/{id}/{theme}/{language?}', [PageController::class, 'edit'])->name('edit.page');
            Route::post('frontend/update-page/{id}/{theme}', [PageController::class, 'update'])->name('update.page');
            Route::post('frontend/page/update-slug', [PageController::class, 'updateSlug'])->name('update.slug');
            Route::delete('frontend/page/delete/{id}', [PageController::class, 'delete'])->name('page.delete');

            Route::get('frontend/edit-static-page/{id}/{theme}/{language?}', [PageController::class, 'editStaticPage'])->name('edit.static.page');
            Route::post('frontend/update-static-page/{id}/{theme}', [PageController::class, 'updateStaticPage'])->name('update.static.page');

            Route::get('frontend/page/seo/{id}', [PageController::class, 'pageSEO'])->name('page.seo');
            Route::post('frontend/page/seo/update/{id}', [PageController::class, 'pageSeoUpdate'])->name('page.seo.update');

            Route::get('frontend/manage-menu', [ManageMenuController::class, 'manageMenu'])->name('manage.menu');
            Route::post('frontend/header-menu-item/store', [ManageMenuController::class, 'headerMenuItemStore'])->name('header.menu.item.store');
            Route::post('frontend/footer-menu-item/store', [ManageMenuController::class, 'footerMenuItemStore'])->name('footer.menu.item.store');
            Route::post('frontend/manage-menu/add-custom-link', [ManageMenuController::class, 'addCustomLink'])->name('add.custom.link');
            Route::get('frontend/manage-menu/edit-custom-link/{pageId}', [ManageMenuController::class, 'editCustomLink'])->name('edit.custom.link');
            Route::post('frontend/manage-menu/update-custom-link/{pageId}', [ManageMenuController::class, 'updateCustomLink'])->name('update.custom.link');
            Route::delete('frontend/manage-menu/delete-custom-link/{pageId}', [ManageMenuController::class, 'deleteCustomLink'])->name('delete.custom.link');
            Route::get('frontend/manage-menu/get-custom-link-data', [ManageMenuController::class, 'getCustomLinkData'])->name('get.custom.link');

            Route::get('frontend/contents/{name}', [ContentController::class, 'index'])->name('manage.content');
            Route::post('frontend/contents/store/{name}/{language}', [ContentController::class, 'store'])->name('content.store');
            Route::get('frontend/contents/item/{name}', [ContentController::class, 'manageContentMultiple'])->name('manage.content.multiple');
            Route::post('frontend/contents/item/store/{name}/{language}', [ContentController::class, 'manageContentMultipleStore'])->name('content.multiple.store');
            Route::get('frontend/contents/item/edit/{name}/{id}', [ContentController::class, 'multipleContentItemEdit'])->name('content.item.edit');
            Route::post('frontend/contents/item/update/{name}/{id}/{language}', [ContentController::class, 'multipleContentItemUpdate'])->name('multiple.content.item.update');
            Route::delete('frontend/contents/delete/{id}', [ContentController::class, 'ContentDelete'])->name('content.item.delete');

            //Manage Theme
            Route::get('manage/theme', [ThemeController::class, 'manageTheme'])->name('manage.theme');
            Route::post('theme/active/{name}', [ThemeController::class, 'themeActive'])->name('theme.active');

            /* ====== Manage Blog =====*/
            Route::resource('blog-category', BlogCategoryController::class);
            Route::resource('blogs', BlogController::class);
            Route::get('blogs/edit/{id}/{language}', [BlogController::class, 'blogEdit'])->name('blog.edit');
            Route::post('blogs/update/{id}/{language}', [BlogController::class, 'blogUpdate'])->name('blog.update');
            Route::post('blogs/slug/update', [BlogController::class, 'slugUpdate'])->name('slug.update');
            Route::get('blogs/seo-page/{id}', [BlogController::class, 'blogSeo'])->name('blog.seo');
            Route::post('blogs/seo-update/{id}', [BlogController::class, 'blogSeoUpdate'])->name('blog.seo.update');


            Route::get('ajax/dashboard/monthly-deposit-withdraw', [DashboardController::class, 'monthlyDepositWithdraw'])->name('monthly.deposit.withdraw');
            Route::get('ajax/dashboard/chartUserRecords', [DashboardController::class, 'chartUserRecords'])->name('chartUserRecords');
            Route::get('ajax/dashboard/chartTicketRecords', [DashboardController::class, 'chartTicketRecords'])->name('chartTicketRecords');
            Route::get('ajax/dashboard/chartKycRecords', [DashboardController::class, 'chartKycRecords'])->name('chartKycRecords');
            Route::get('ajax/dashboard/chartTransactionRecords', [DashboardController::class, 'chartTransactionRecords'])->name('chartTransactionRecords');
            Route::get('ajax/dashboard/chartLoginHistory', [DashboardController::class, 'chartLoginHistory'])->name('chartLoginHistory');

            Route::get('ajax/chartBrowserHistory', [DashboardController::class, 'chartBrowserHistory'])->name('chart.browser.history');
            Route::get('ajax/chartOsHistory', [DashboardController::class, 'chartOsHistory'])->name('chart.os.history');
            Route::get('ajax/chartDeviceHistory', [DashboardController::class, 'chartDeviceHistory'])->name('chart.device.history');



            Route::get('user/kyc/{id}', [UsersController::class, 'userKyc'])->name('user.kyc.list');

            Route::get('user/transaction/{id}', [UsersController::class, 'transaction'])->name('user.transaction');

            Route::get('user/investment/{id}', [UsersController::class, 'investments'])->name('user.investments');
            Route::get('/user/investmentLog/{id}', [UsersController::class, 'investments'])->name('user.plan-purchaseLog');

            Route::get('user/commission/{id}', [UsersController::class, 'commissions'])->name('user.commissions');

            Route::get('user/payment/{id}', [UsersController::class, 'payment'])->name('user.payment');

            Route::get('user/withdraw/{id}', [UsersController::class, 'payout'])->name('user.payout');

            Route::get('/email-send', [UsersController::class, 'emailToUsers'])->name('email-send');
            Route::post('/email-send', [UsersController::class, 'sendEmailToUsers'])->name('email-send.store');
            Route::delete('user/delete/{id}', [UsersController::class, 'userDelete'])->name('user.delete');

            Route::get('users/add', [UsersController::class, 'userAdd'])->name('users.add');
            Route::post('users/store', [UsersController::class, 'userStore'])->name('user.store');
            Route::get('users/added-successfully/{id}', [UsersController::class, 'userCreateSuccessMessage'])
                ->name('user.create.success.message');
            Route::get('user/view-profile/{id}', [UsersController::class, 'userViewProfile'])->name('user.view.profile');
        });

        // Ajax Route
        Route::get('profit/schedule/search', [ScheduleController::class, 'profitScheduleSearch'])->name('profit.schedule.search');
        Route::get('amenities/search', [AmenitiesController::class, 'amenitiesSearch'])->name('amenities.search');
        Route::get('address/search', [AddressController::class, 'addressSearch'])->name('address.search');
        Route::get('property/search/{type}', [PropertyController::class, 'propertySearch'])->name('property.search');
        Route::get('property/wishlist/search', [PropertyController::class, 'propertyWishlistSearch'])->name('property.wishlist.search');
        Route::get('investment/search', [InvestmentController::class, 'investmentSearch'])->name('investment.search');
        Route::get('running/investment/search', [InvestmentController::class, 'runningInvestmentSearch'])->name('running.investment.search');
        Route::get('running/investment/details/search/{id}', [InvestmentController::class, 'runningInvestmentDetailsSearch'])->name('running.investment.details.search');
        Route::get('due/investment/search', [InvestmentController::class, 'dueInvestmentSearch'])->name('due.investment.search');
        Route::get('due/investment/details/search/{id}', [InvestmentController::class, 'dueInvestmentDetailsSearch'])->name('due.investment.details.search');
        Route::get('expired/investment/search', [InvestmentController::class, 'expiredInvestmentSearch'])->name('expired.investment.search');
        Route::get('expired/investment/details/search/{id}', [InvestmentController::class, 'expiredInvestmentDetailsSearch'])->name('expired.investment.details.search');
        Route::get('completed/investment/search', [InvestmentController::class, 'completedInvestmentSearch'])->name('completed.investment.search');
        Route::get('completed/investment/details/search/{id}', [InvestmentController::class, 'completedInvestmentDetailsSearch'])->name('completed.investment.details.search');
        Route::get('rank/search', [RankController::class, 'rankSearch'])->name('rank.search');
        Route::post('sort/rank', [RankController::class, 'sortRanks'])->name('sort.ranks');
        Route::get('users/search/{state?}', [UsersController::class, 'search'])->name('users.search');

        // dashboard ajax route
        Route::get('get-daily-transaction-chart-records', [DashboardController::class, 'getDailyTransactionAnalytics'])->name('get.daily.transaction.analytics');
        Route::get('get-daily-user-chart-records', [DashboardController::class, 'getDailyUserAnalytics'])->name('get.daily.user.analytics');
        Route::get('get-investment-stat-records', [DashboardController::class, 'getInvestmentStatRecords'])->name('getInvestmentStatRecords');
        Route::get('get-user-stat-records', [DashboardController::class, 'getUserStatRecords'])->name('getUserStatRecords');


        Route::get('transaction/search', [TransactionLogController::class, 'transactionSearch'])->name('transaction.search');
        Route::get('commission/search', [TransactionLogController::class, 'commissionSearch'])->name('commission.search');
        Route::get('payment/search', [PaymentLogController::class, 'search'])->name('payment.search');
        Route::get('withdraw/search', [PayoutLogController::class, 'search'])->name('payout.search');
        Route::get('kyc/search/{status?}', [KycController::class, 'userKycSearch'])->name('kyc.search');
        Route::get('user/kyc-search', [KycController::class, 'searchKyc'])->name('userKyc.search');
        Route::get('tickets-search/{status}', [SupportTicketController::class, 'ticketSearch'])->name('ticket.search');
        Route::get('subscriber/search', [SubscriberController::class, 'subscriberSearch'])->name('subscriber.search');
        Route::get('user/kyc/search/{id}', [UsersController::class, 'KycSearch'])->name('user.kyc.search');

        Route::get('user/transaction/search/{id}', [UsersController::class, 'userTransactionSearch'])->name('user.transaction.search');
        Route::get('user/investment/search/{id}', [UsersController::class, 'investmentSearch'])->name('user.investment.search');
        Route::get('user/commission/search/{id}', [UsersController::class, 'commissionSearch'])->name('user.commission.search');
        Route::get('user/payment/search/{id}', [UsersController::class, 'userPaymentSearch'])->name('user.payment.search');
        Route::get('user/withdraw/search/{id}', [UsersController::class, 'userPayoutSearch'])->name('user.payout.search');

    });
});
