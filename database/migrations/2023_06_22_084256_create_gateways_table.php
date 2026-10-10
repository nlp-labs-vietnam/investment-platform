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
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->string('code', 191);
            $table->string('name', 191);
            $table->integer('sort_by')->default(1);
            $table->string('image', 191)->nullable();
            $table->string('driver', 20)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0: inactive, 1: active');
            $table->text('parameters')->nullable();
            $table->text('currencies')->nullable();
            $table->text('extra_parameters')->nullable();
            $table->string('supported_currency', 255)->nullable();
            $table->text('receivable_currencies')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('currency_type')->default(1);
            $table->tinyInteger('is_sandbox')->default(0);
            $table->enum('environment', ['test', 'live'])->default('live');
            $table->tinyInteger('is_manual')->default(0)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateways');
    }
};
