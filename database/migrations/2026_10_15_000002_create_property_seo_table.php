<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_seo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->string('meta_title', 191)->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedBigInteger('og_image_id')->nullable(); // FK → images.id
            $table->string('slug', 191)->nullable()->unique();
            $table->json('schema_json')->nullable();
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_seo');
    }
};
