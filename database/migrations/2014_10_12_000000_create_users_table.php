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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('username')->nullable();
            $table->integer('referral_id')->nullable();
            $table->integer('language_id')->nullable();
            $table->string('email')->unique();
            $table->string('country_code')->nullable();
            $table->string('country')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('balance', 11, 2)->nullable();
            $table->string('image')->nullable();
            $table->string('image_driver', 50)->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable()->comment("Zip Or Postal Code");
            $table->text('address_one')->nullable();
            $table->text('address_two')->nullable();
            $table->string('provider', 191)->nullable();
            $table->integer('provider_id')->nullable();
            $table->boolean('status')->default(1);
            $table->tinyInteger('identity_verify')->default(0)->comment("0 => Not Applied, 1=> Applied, 2=> Approved, 3 => Rejected	");
            $table->tinyInteger('address_verify')->default(0)->comment("0 => Not Applied, 1=> Applied, 2=> Approved, 3 => Rejected");
            $table->boolean('two_fa')->default(0);
            $table->boolean('two_fa_verify')->default(1);
            $table->string('two_fa_code')->nullable();
            $table->boolean('email_verification')->default(1);
            $table->boolean('sms_verification')->default(1);
            $table->string('verify_code')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('last_seen')->nullable();
            $table->string('time_zone')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
