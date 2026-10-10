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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('short_name')->nullable();
            $table->string('flag')->nullable();
            $table->string('flag_driver')->nullable();
            $table->boolean('status')->default(1)->comment("0 => Inactive, 1 => Active");
            $table->boolean('rtl')->default(0)->comment("0 => Inactive, 1 => Active");
            $table->boolean('default_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
