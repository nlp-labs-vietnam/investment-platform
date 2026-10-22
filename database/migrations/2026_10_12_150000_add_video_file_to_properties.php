<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thêm hỗ trợ upload file video trực tiếp lên server cho bảng properties.
 *
 *   video_file   : đường dẫn file video đã lưu (mp4/webm/ogg)
 *   video_driver : driver lưu trữ (local | s3 | ...)
 *
 * Trường `video` cũ (YouTube/embed URL) vẫn giữ nguyên.
 * Ưu tiên render: video_file > video (URL).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('video_file', 500)->nullable()->after('video');
            $table->string('video_driver', 20)->nullable()->after('video_file');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['video_file', 'video_driver']);
        });
    }
};
