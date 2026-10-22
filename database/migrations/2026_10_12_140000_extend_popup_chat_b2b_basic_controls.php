<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mở rộng cấu hình Popup Chat cho kịch bản B2B nội bộ.
 *
 * Thêm vào bảng basic_controls:
 *   popup_chat_greeting   : lời chào hiển thị trên panel (vd: "Xin chào! Chúng tôi có thể hỗ trợ gì?")
 *   popup_chat_tagline    : dòng phụ nhỏ dưới greeting (vd: "Đội ngũ NLP Group phản hồi trong 15 phút")
 *   popup_chat_agent_name : tên nhân viên / tên nhóm hiển thị (vd: "NLP Support")
 *   popup_chat_agent_avatar : URL avatar agent (tuỳ chọn)
 *   popup_chat_channels   : JSON array các kênh liên lạc
 *                           [{type, label, value, icon, color}]
 *                           type: zalo | whatsapp | phone | email | custom
 *
 * Các cột cũ (popup_chat_type, popup_chat_id, popup_chat_icon, popup_chat_color, popup_chat_label)
 * vẫn giữ nguyên để tương thích — popup_chat_channels thay thế logic hiển thị.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('basic_controls', function (Blueprint $table) {
            $table->string('popup_chat_greeting', 255)->nullable()->after('popup_chat_icon');
            $table->string('popup_chat_tagline', 255)->nullable()->after('popup_chat_greeting');
            $table->string('popup_chat_agent_name', 100)->nullable()->after('popup_chat_tagline');
            $table->string('popup_chat_agent_avatar', 500)->nullable()->after('popup_chat_agent_name');
            $table->text('popup_chat_channels')->nullable()->after('popup_chat_agent_avatar');
        });
    }

    public function down(): void
    {
        Schema::table('basic_controls', function (Blueprint $table) {
            $table->dropColumn([
                'popup_chat_greeting',
                'popup_chat_tagline',
                'popup_chat_agent_name',
                'popup_chat_agent_avatar',
                'popup_chat_channels',
            ]);
        });
    }
};
