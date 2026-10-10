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
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->string('rank_name')->nullable();
            $table->string('rank_level')->nullable();
            $table->decimal('min_invest', 11, 4)->nullable();
            $table->decimal('min_deposit', 11, 4)->nullable();
            $table->decimal('min_earning', 11, 4)->nullable();
            $table->decimal('bonus', 11, 4)->nullable();
            $table->string('rank_icon')->nullable();
            $table->string('driver')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_by')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranks');
    }
};
