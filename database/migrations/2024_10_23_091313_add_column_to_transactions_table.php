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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('property_id')->index()->nullable()->after('user_id');
            $table->foreignId('investment_id')->index()->nullable()->after('property_id');
            $table->string('balance_type')->nullable()->after('trx_type');
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('property_id');
            $table->dropColumn('investment_id');
            $table->dropColumn('balance_type');
        });
    }
};
