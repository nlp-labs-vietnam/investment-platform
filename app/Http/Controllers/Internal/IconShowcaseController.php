<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Support\Icons\NlpIcon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Controller nội bộ: Hiển thị toàn bộ Design Token Icon System.
 *
 * Chỉ hoạt động trong môi trường development/staging.
 * Truy cập: GET /_internal/icons
 */
class IconShowcaseController extends Controller
{
    /**
     * Hiển thị trang trưng bày toàn bộ icon token.
     */
    public function index(Request $request)
    {
        // ── Bảo vệ môi trường production ─────────────────────────────
        // Trang này chỉ khả dụng khi app đang ở chế độ debug hoặc không phải production.
        // Để bỏ qua kiểm tra này trong staging, đặt APP_DEBUG=true trong .env.
        if (App::isProduction() && ! config('app.debug')) {
            abort(403, 'Trang này chỉ khả dụng trong môi trường phát triển.');
        }

        // ── Lấy toàn bộ design token icon ────────────────────────────
        // NlpIcon::all() trả về mảng 38 token, mỗi token gồm:
        //   fa, fa_style, sf, sf_mode, color, bg, domain, label_vi
        //
        // Nếu method tên khác (ví dụ: getTokens(), tokens(), toArray()):
        //   Thay dòng dưới thành: $tokens = NlpIcon::getTokens();
        $tokens = NlpIcon::all();

        // ── Định nghĩa 6 hiệu ứng animation ──────────────────────────
        // Mỗi entry: 'key' => ['label' => '...', 'symbol' => '...']
        // Key khớp với CSS class: .nlp-icon--{key}
        // Tương đương SF Symbols symbolEffect trên iOS 17+
        $animations = [
            'pulse'  => [
                'label'  => 'Nhấp nháy (Pulse)',
                'symbol' => '◉ opacity 1 → 0.35 → 1',
            ],
            'bounce' => [
                'label'  => 'Nảy lên (Bounce)',
                'symbol' => '↑ translateY 0 → -5px → 0',
            ],
            'spin'   => [
                'label'  => 'Xoay tròn (Spin / Rotate)',
                'symbol' => '↻ rotate 0° → 360°',
            ],
            'beat'   => [
                'label'  => 'Phóng to thu nhỏ (Beat / Scale)',
                'symbol' => '⊕ scale 1 → 1.25 → 1',
            ],
            'shake'  => [
                'label'  => 'Rung lắc khi hover (Shake)',
                'symbol' => '↔ translateX ±4px (hover)',
            ],
            'fade'   => [
                'label'  => 'Mờ dần (Fade)',
                'symbol' => '◌ opacity 1 → 0.2 → 1',
            ],
        ];

        return view('internal.icons-showcase', compact('tokens', 'animations'));
    }
}
