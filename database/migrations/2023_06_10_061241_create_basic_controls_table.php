<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('basic_controls', function (Blueprint $table) {
            $table->id();
            $table->string('theme', 50)->nullable();
            $table->string('site_title', 255)->nullable();
            $table->string('primary_color', 50)->nullable();
            $table->string('secondary_color', 50)->nullable();
            $table->string('time_zone', 50)->nullable();
            $table->string('base_currency', 20)->nullable();
            $table->string('currency_symbol', 20)->nullable();
            $table->string('admin_prefix', 191)->nullable();
            $table->enum('is_currency_position', ['left', 'right'])->default('left')->comment('left , right');
            $table->boolean('has_space_between_currency_and_amount')->default(false);
            $table->boolean('is_force_ssl')->default(false);
            $table->boolean('is_maintenance_mode')->default(false);
            $table->integer('paginate')->nullable();
            $table->boolean('strong_password')->default(false);
            $table->boolean('registration')->default(false)->comment("0 => disable, 1 => enable");
            $table->integer('fraction_number')->nullable();
            $table->string('sender_email', 255)->nullable();
            $table->string('sender_email_name', 255)->nullable();
            $table->text('email_description')->nullable();
            $table->boolean('push_notification')->default(false);
            $table->boolean('in_app_notification')->default(false);
            $table->boolean('email_notification')->default(false);
            $table->boolean('email_verification')->default(false);
            $table->boolean('sms_notification')->default(false);
            $table->boolean('sms_verification')->default(false);
            $table->string('tawk_id', 255)->nullable();
            $table->boolean('tawk_status')->default(false);
            $table->boolean('fb_messenger_status')->default(false);
            $table->string('fb_app_id', 255)->nullable();
            $table->string('fb_page_id', 255)->nullable();
            $table->boolean('manual_recaptcha')->default(false)->comment("0 =>inactive, 1 => active");
            $table->boolean('google_recaptcha')->default(false)->comment("0=>inactive, 1 =>active");
            $table->boolean('google_reCaptcha_admin_login')->default(false)->comment("	0 => inactive, 1 => active");
            $table->boolean('google_reCaptcha_user_login')->default(false)->comment("	0 => inactive, 1 => active");
            $table->boolean('google_recaptcha_user_registration')->default(false)->comment("	0 => inactive, 1 => active");
            $table->boolean('manual_recaptcha_admin_login')->default(false)->comment("	0 => inactive, 1 => active");
            $table->boolean('manual_recaptcha_user_login')->default(false)->comment("	0 => inactive, 1 => active");
            $table->boolean('manual_recaptcha_user_registration')->default(false)->comment("	0 => inactive, 1 => active");
            $table->string('measurement_id', 255)->nullable();
            $table->boolean('analytic_status')->nullable();
            $table->boolean('error_log')->nullable();
            $table->boolean('is_active_cron_notification')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('logo_driver', 20)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('favicon_driver', 20)->nullable();
            $table->string('admin_logo', 255)->nullable();
            $table->string('admin_logo_driver', 20)->nullable();
            $table->string('admin_dark_mode_logo', 255)->nullable();
            $table->string('admin_dark_mode_logo_driver', 50)->nullable();
            $table->string('currency_layer_access_key')->nullable();
            $table->string('currency_layer_auto_update_at')->nullable();
            $table->string('currency_layer_auto_update',1)->nullable();
            $table->string('coin_market_cap_app_key')->nullable();
            $table->string('coin_market_cap_auto_update_at')->nullable();
            $table->string('coin_market_cap_auto_update',1)->nullable();
            $table->string('automatic_payout_permission',1)->nullable();
            $table->string('date_time_format', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_controls');
    }
};
