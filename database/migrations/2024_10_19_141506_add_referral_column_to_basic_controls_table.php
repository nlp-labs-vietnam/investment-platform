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
        Schema::table('basic_controls', function (Blueprint $table) {
            $table->boolean('deposit_commission')->default(0)->nullable()->after('date_time_format');
            $table->boolean('investment_commission')->default(0)->nullable()->after('deposit_commission');
            $table->boolean('profit_commission')->default(0)->nullable()->after('investment_commission');
            $table->boolean('rank_commission')->default(0)->nullable()->after('profit_commission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('basic_controls', function (Blueprint $table) {
            $table->dropColumn('deposit_commission');
            $table->dropColumn('investment_commission');
            $table->dropColumn('profit_commission');
            $table->dropColumn('rank_commission');
        });
    }

};
