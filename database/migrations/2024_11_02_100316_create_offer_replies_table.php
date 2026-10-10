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
        Schema::create('offer_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_offer_id')->index()->nullable();
            $table->foreignId('sender_id')->index()->nullable();
            $table->foreignId('receiver_id')->index()->nullable();
            $table->longText('reply')->nullable();
            $table->text('file')->nullable();
            $table->text('driver')->nullable();
            $table->string('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_replies');
    }
};
