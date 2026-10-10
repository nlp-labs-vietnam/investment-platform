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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('transactional_id')->nullable();
            $table->string('transactional_type')->nullable();
            $table->foreignId('user_id')->index()->nullable();
            $table->double('amount')->nullable()->default(0.00);
            $table->double('balance')->nullable()->default(0.00);
            $table->double('amount_in_base')->nullable()->default(0.00);
            $table->decimal('charge', 11, 2)->nullable()->default(0.00);
            $table->string('trx_type')->nullable();
            $table->string('remarks')->nullable();
            $table->string('trx_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
