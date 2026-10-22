<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thêm cột cấu hình Popup Chat vào bảng basic_controls.
 *
 *   popup_chat_status  : bật/tắt widget
 *   popup_chat_type    : "zalo" | "whatsapp" | "phone" | "custom"
 *   popup_chat_id      : số Zalo OA / số WhatsApp / số điện thoại / URL tuỳ chỉnh
 *   popup_chat_label   : nhãn hiển thị trên nút (vd: "Chat Zalo")
 *   popup_chat_color   : màu nền nút HEX (vd: "#0068FF")
 *   popup_chat_icon    : class Font Awesome (vd: "fa-brands fa-square-phone")
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('basic_controls', function (Blueprint $table) {
            $table->boolean('popup_chat_status')->default(false)->after('fb_page_id');
            $table->string('popup_chat_type', 20)->default('zalo')->after('popup_chat_status');
            $table->string('popup_chat_id', 255)->nullable()->after('popup_chat_type');
            $table->string('popup_chat_label', 100)->nullable()->after('popup_chat_id');
            $table->string('popup_chat_color', 20)->nullable()->after('popup_chat_label');
            $table->string('popup_chat_icon', 80)->nullable()->after('popup_chat_color');
        });
    }

    public function down(): void
    {
        Schema::table('basic_controls', function (Blueprint $table) {
            $table->dropColumn([
                'popup_chat_status',
                'popup_chat_type',
                'popup_chat_id',
                'popup_chat_label',
                'popup_chat_color',
                'popup_chat_icon',
            ]);
        });
    }
};
