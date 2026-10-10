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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->nullable();
            $table->foreignId('payout_method_id')->index()->nullable();
            $table->string('payout_currency_code')->nullable();
            $table->decimal('amount', 18, 8)->default(0.00000000);
            $table->decimal('charge', 18, 8)->default(0.00000000);
            $table->decimal('net_amount', 18, 8)->default(0.00000000);
            $table->decimal('amount_in_base_currency', 18, 8)->default(0.00000000);
            $table->decimal('charge_in_base_currency', 18, 8)->default(0.00000000);
            $table->decimal('net_amount_in_base_currency', 18, 8)->default(0.00000000);
            $table->string('response_id')->nullable();
            $table->string('last_error')->nullable();
            $table->text('information')->nullable();
            $table->string('meta_field')->nullable();
            $table->text('feedback')->nullable();
            $table->string('trx_id',50)->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
