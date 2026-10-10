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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('interest_balance', 11, 4)->default(0)->after('balance');
            $table->decimal('total_interest_balance', 11, 4)->default(0)->after('interest_balance');
            $table->decimal('joining_bonus', 11, 4)->default(0)->after('total_interest_balance');
            $table->decimal('total_referral_joining_bonus', 11, 4)->default(0)->after('joining_bonus');
            $table->decimal('deposit_referral_bonous', 11, 4)->default(0)->after('total_referral_joining_bonus');
            $table->decimal('invest_referral_bonous', 11, 4)->default(0)->after('deposit_referral_bonous');
            $table->decimal('profit_referral_bonous', 11, 4)->default(0)->after('invest_referral_bonous');
            $table->decimal('rank_referral_bonous', 11, 4)->default(0)->after('profit_referral_bonous');
            $table->decimal('total_rank_bonous', 11, 4)->default(0)->after('rank_referral_bonous');
            $table->decimal('total_referral_bonous', 11, 4)->default(0)->after('total_rank_bonous');
            $table->decimal('total_invest', 11, 4)->default(0)->after('total_referral_bonous');
            $table->decimal('total_deposit', 11, 4)->default(0)->after('total_invest');
            $table->integer('admin_update_rank')->default(0)->after('total_deposit');
            $table->integer('last_level')->nullable()->after('admin_update_rank');
            $table->text('all_ranks')->nullable()->after('last_level');
            $table->text('address')->nullable()->after('all_ranks');
            $table->text('bio')->nullable()->after('address');
            $table->boolean('premium_user')->default(0)->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('interest_balance');
            $table->dropColumn('total_interest_balance');
            $table->dropColumn('joining_bonus');
            $table->dropColumn('total_referral_joining_bonus');
            $table->dropColumn('deposit_referral_bonous');
            $table->dropColumn('invest_referral_bonous');
            $table->dropColumn('profit_referral_bonous');
            $table->dropColumn('rank_referral_bonous');
            $table->dropColumn('total_rank_bonous');
            $table->dropColumn('total_referral_bonous');
            $table->dropColumn('total_invest');
            $table->dropColumn('total_deposit');
            $table->dropColumn('admin_update_rank');
            $table->dropColumn('last_level');
            $table->dropColumn('all_ranks');
            $table->dropColumn('address');
            $table->dropColumn('bio');
            $table->dropColumn('premium_user');
        });
    }
};
