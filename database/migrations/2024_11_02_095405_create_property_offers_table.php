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
        Schema::create('property_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_share_id')->index()->nullable();
            $table->foreignId('offered_from')->index()->nullable();
            $table->foreignId('offered_to')->index()->nullable();
            $table->foreignId('investment_id')->index()->nullable();
            $table->foreignId('property_id')->index()->nullable();
            $table->decimal('amount')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_offers');
    }
};
