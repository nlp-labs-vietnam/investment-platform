<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('github_id')->nullable()->after('is_bonus_amount');
            $table->string('google_id')->nullable()->after('github_id');
            $table->string('facebook_id')->nullable()->after('google_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['github_id', 'google_id', 'facebook_id']);
        });
    }
};
