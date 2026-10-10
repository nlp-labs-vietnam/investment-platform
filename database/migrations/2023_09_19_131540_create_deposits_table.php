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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->integer('depositable_id')->nullable();
            $table->string('depositable_type')->nullable();
            $table->foreignId('user_id')->index()->nullable();
            $table->foreignId('payment_method_id')->index()->nullable();
            $table->string('payment_method_currency')->nullable();
            $table->decimal('amount', 18, 8)->default(0.00000000)->comment("in base currency");
            $table->decimal('percentage_charge', 18, 8)->default(0.00000000);
            $table->decimal('fixed_charge', 18, 8)->default(0.00000000);
            $table->decimal('base_currency_charge', 18, 8)->default(0.00000000);
            $table->decimal('payable_amount', 18, 8)->default(0.00000000)->comment("Amount payed");
            $table->decimal('payable_amount_in_base_currency', 18, 8)->default(0.00000000)->comment("Amount payed in base currency");
            $table->decimal('btc_amount', 18, 8)->nullable();
            $table->string('btc_wallet')->nullable();
            $table->string('payment_id')->nullable();
            $table->text('information')->nullable();
            $table->string('trx_id')->nullable();
            $table->tinyInteger('status')->default(0)->comment("0=pending, 1=success, 2=request, 3=rejected");
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
