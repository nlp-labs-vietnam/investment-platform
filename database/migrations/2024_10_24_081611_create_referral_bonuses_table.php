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
        Schema::create('referral_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('to_user_id')->index()->nullable()->comment('This user is getting bonus');
            $table->foreignId('from_user_id')->index()->nullable()->comment('To user Getting bonus for it');
            $table->integer('level')->nullable();
            $table->decimal('amount', 11, 4)->default(0);
            $table->decimal('main_balance', 11, 4)->default(0);
            $table->string('transaction')->nullable();
            $table->string('type')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_bonuses');
    }
};
