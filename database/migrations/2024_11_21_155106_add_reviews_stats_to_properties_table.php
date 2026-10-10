<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('get_favourite_count')->default(0)->after('status');
            $table->integer('reviews_count')->default(0)->after('get_favourite_count');
            $table->decimal('avg_rating', 5, 2)->default(0)->after('reviews_count');
        });
    }


    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['get_favourite_count, reviews_count', 'avg_rating']);
        });
    }
};
