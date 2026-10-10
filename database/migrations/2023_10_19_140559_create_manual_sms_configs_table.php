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
        Schema::create('manual_sms_configs', function (Blueprint $table) {
            $table->id();
            $table->string('action_method')->nullable();
            $table->string('action_url')->nullable();
            $table->text('header_data')->nullable();
            $table->text('param_data')->nullable();
            $table->text('form_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_sms_configs');
    }
};
