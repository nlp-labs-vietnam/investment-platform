<?php

namespace App\Support\Icons;

/**
 * NLP Group — Design Token: Icon System
 *
 * Single Source of Truth cho cả Web (FA6 Pro) và iOS (SF Symbols).
 * Mỗi token gồm:
 *   - semantic  : tên ngữ nghĩa dùng làm CSS modifier (.nlp-icon--<semantic>)
 *   - fa        : Font Awesome 6 Pro icon name (web)
 *   - fa_style  : fa-regular | fa-solid | fa-light  (web)
 *   - sf        : SF Symbol systemName (iOS)
 *   - sf_mode   : monochrome | hierarchical | palette | multicolor (iOS)
 *   - color     : CSS hex — màu icon (web + tham chiếu iOS tintColor)
 *   - bg        : CSS hex — nền badge (web)
 *   - domain    : lĩnh vực NLP Group
 *   - label_vi  : nhãn tiếng Việt
 */
class NlpIcon
{
    /** @var array<string, array> */
    private static array $tokens = [

        // ── NLP Energy — Năng lượng xanh ──────────────────────────────
        'solar' => [
            'fa' => 'fa-sun',          'fa_style' => 'fa-regular',
            'sf' => 'sun.max.fill',    'sf_mode'  => 'multicolor',
            'color' => '#d97706',      'bg' => '#fff7e6',
            'domain' => 'energy',      'label_vi' => 'Điện mặt trời',
        ],
        'battery' => [
            'fa' => 'fa-battery-full', 'fa_style' => 'fa-regular',
            'sf' => 'battery.100',     'sf_mode'  => 'hierarchical',
            'color' => '#1d4ed8',      'bg' => '#eff6ff',
            'domain' => 'energy',      'label_vi' => 'Pin lưu trữ BESS',
        ],
        'bolt' => [
            'fa' => 'fa-bolt',         'fa_style' => 'fa-solid',
            'sf' => 'bolt.fill',       'sf_mode'  => 'multicolor',
            'color' => '#0369a1',      'bg' => '#e0f2fe',
            'domain' => 'energy',      'label_vi' => 'Điện / EV',
        ],
        'tool' => [
            'fa' => 'fa-wrench',       'fa_style' => 'fa-regular',
            'sf' => 'wrench.fill',     'sf_mode'  => 'monochrome',
            'color' => '#475569',      'bg' => '#f1f5f9',
            'domain' => 'energy',      'label_vi' => 'Công cụ kỹ thuật',
        ],
        'tools' => [
            'fa' => 'fa-screwdriver-wrench', 'fa_style' => 'fa-regular',
            'sf' => 'wrench.and.screwdriver.fill', 'sf_mode' => 'monochrome',
            'color' => '#475569',      'bg' => '#f1f5f9',
            'domain' => 'energy',      'label_vi' => 'Thi công & bảo trì',
        ],
        'chart-down' => [
            'fa' => 'fa-chart-line-down', 'fa_style' => 'fa-regular',
            'sf' => 'chart.line.downtrend.xyaxis', 'sf_mode' => 'monochrome',
            'color' => '#dc2626',      'bg' => '#fff1f2',
            'domain' => 'energy',      'label_vi' => 'Giảm chi phí',
        ],

        // ── NLP EV Charging — Trạm sạc xe điện ───────────────────────
        'plug' => [
            'fa' => 'fa-plug',         'fa_style' => 'fa-regular',
            'sf' => 'powerplug.fill',  'sf_mode'  => 'palette',
            'color' => '#1e40af',      'bg' => '#dbeafe',
            'domain' => 'ev',          'label_vi' => 'Trụ sạc',
        ],
        'ev-car' => [
            'fa' => 'fa-car-side',     'fa_style' => 'fa-regular',
            'sf' => 'bolt.car.fill',   'sf_mode'  => 'hierarchical',
            'color' => '#0f4c75',      'bg' => '#e0f2fe',
            'domain' => 'ev',          'label_vi' => 'Xe điện',
        ],
        'screen' => [
            'fa' => 'fa-display',      'fa_style' => 'fa-regular',
            'sf' => 'desktopcomputer', 'sf_mode'  => 'monochrome',
            'color' => '#334155',      'bg' => '#f8fafc',
            'domain' => 'ev',          'label_vi' => 'Phần mềm CSMS',
        ],
        'mobile' => [
            'fa' => 'fa-mobile-screen', 'fa_style' => 'fa-regular',
            'sf' => 'iphone',           'sf_mode'  => 'monochrome',
            'color' => '#0284c7',      'bg' => '#e0f2fe',
            'domain' => 'ev',          'label_vi' => 'Ứng dụng di động',
        ],
        'truck' => [
            'fa' => 'fa-truck',        'fa_style' => 'fa-regular',
            'sf' => 'box.truck.fill',  'sf_mode'  => 'monochrome',
            'color' => '#c2410c',      'bg' => '#fff7ed',
            'domain' => 'ev',          'label_vi' => 'Đội xe thương mại',
        ],
        'partner' => [
            'fa' => 'fa-handshake',    'fa_style' => 'fa-regular',
            'sf' => 'person.2.fill',   'sf_mode'  => 'hierarchical',
            'color' => '#15803d',      'bg' => '#f0fdf4',
            'domain' => 'ev',          'label_vi' => 'Đối tác & nhượng quyền',
        ],

        // ── NLP Technology — Công nghệ số ─────────────────────────────
        'laptop' => [
            'fa' => 'fa-laptop',       'fa_style' => 'fa-regular',
            'sf' => 'laptopcomputer',  'sf_mode'  => 'monochrome',
            'color' => '#0e7490',      'bg' => '#ecfeff',
            'domain' => 'technology',  'label_vi' => 'Phần mềm / ERP',
        ],
        'globe' => [
            'fa' => 'fa-globe',        'fa_style' => 'fa-regular',
            'sf' => 'globe',           'sf_mode'  => 'multicolor',
            'color' => '#1d4ed8',      'bg' => '#eff6ff',
            'domain' => 'technology',  'label_vi' => 'Website / API',
        ],
        'factory' => [
            'fa' => 'fa-industry',     'fa_style' => 'fa-regular',
            'sf' => 'building.2.fill', 'sf_mode'  => 'monochrome',
            'color' => '#374151',      'bg' => '#f1f5f9',
            'domain' => 'technology',  'label_vi' => 'Nhà máy / IoT',
        ],
        'link' => [
            'fa' => 'fa-link',         'fa_style' => 'fa-regular',
            'sf' => 'link',            'sf_mode'  => 'monochrome',
            'color' => '#7c3aed',      'bg' => '#faf5ff',
            'domain' => 'technology',  'label_vi' => 'Tích hợp hệ thống',
        ],
        'cloud' => [
            'fa' => 'fa-cloud',        'fa_style' => 'fa-regular',
            'sf' => 'cloud.fill',      'sf_mode'  => 'multicolor',
            'color' => '#0369a1',      'bg' => '#e0f2fe',
            'domain' => 'technology',  'label_vi' => 'Điện toán đám mây',
        ],
        'package' => [
            'fa' => 'fa-box',          'fa_style' => 'fa-regular',
            'sf' => 'shippingbox.fill','sf_mode'  => 'monochrome',
            'color' => '#64748b',      'bg' => '#f1f5f9',
            'domain' => 'technology',  'label_vi' => 'Phần mềm đóng gói',
        ],
        'rocket' => [
            'fa' => 'fa-rocket',       'fa_style' => 'fa-regular',
            'sf' => 'paperplane.fill', 'sf_mode'  => 'palette',
            'color' => '#be123c',      'bg' => '#fff1f2',
            'domain' => 'technology',  'label_vi' => 'Khởi nghiệp / tăng trưởng',
        ],

        // ── NLP AI & Digital — Trí tuệ nhân tạo ──────────────────────
        'ai' => [
            'fa' => 'fa-robot',        'fa_style' => 'fa-regular',
            'sf' => 'cpu.fill',        'sf_mode'  => 'hierarchical',
            'color' => '#4338ca',      'bg' => '#eef2ff',
            'domain' => 'ai',          'label_vi' => 'Trí tuệ nhân tạo',
        ],
        'chat' => [
            'fa' => 'fa-message',      'fa_style' => 'fa-regular',
            'sf' => 'bubble.left.and.bubble.right.fill', 'sf_mode' => 'palette',
            'color' => '#1d4ed8',      'bg' => '#eff6ff',
            'domain' => 'ai',          'label_vi' => 'Chatbot AI',
        ],
        'chart' => [
            'fa' => 'fa-chart-bar',    'fa_style' => 'fa-regular',
            'sf' => 'chart.bar.xaxis', 'sf_mode'  => 'palette',
            'color' => '#6d28d9',      'bg' => '#f5f3ff',
            'domain' => 'ai',          'label_vi' => 'Phân tích dữ liệu',
        ],
        'eye' => [
            'fa' => 'fa-eye',          'fa_style' => 'fa-regular',
            'sf' => 'eye.fill',        'sf_mode'  => 'monochrome',
            'color' => '#0891b2',      'bg' => '#ecfeff',
            'domain' => 'ai',          'label_vi' => 'Giám sát & quan sát',
        ],
        'chart-up' => [
            'fa' => 'fa-chart-line',   'fa_style' => 'fa-regular',
            'sf' => 'chart.line.uptrend.xyaxis', 'sf_mode' => 'monochrome',
            'color' => '#15803d',      'bg' => '#f0fdf4',
            'domain' => 'ai',          'label_vi' => 'Tăng trưởng / dự báo',
        ],
        'gear' => [
            'fa' => 'fa-gear',         'fa_style' => 'fa-regular',
            'sf' => 'gearshape.fill',  'sf_mode'  => 'monochrome',
            'color' => '#475569',      'bg' => '#f8fafc',
            'domain' => 'ai',          'label_vi' => 'Cấu hình / vận hành',
        ],

        // ── NLP ESG & Carbon — Phát triển bền vững ────────────────────
        'tree' => [
            'fa' => 'fa-tree',         'fa_style' => 'fa-regular',
            'sf' => 'tree.fill',       'sf_mode'  => 'multicolor',
            'color' => '#16a34a',      'bg' => '#f0fdf4',
            'domain' => 'esg',         'label_vi' => 'Rừng / carbon',
        ],
        'leaf' => [
            'fa' => 'fa-leaf',         'fa_style' => 'fa-regular',
            'sf' => 'leaf.fill',       'sf_mode'  => 'multicolor',
            'color' => '#15803d',      'bg' => '#dcfce7',
            'domain' => 'esg',         'label_vi' => 'Nông nghiệp bền vững',
        ],
        'wind' => [
            'fa' => 'fa-wind',         'fa_style' => 'fa-regular',
            'sf' => 'wind',            'sf_mode'  => 'monochrome',
            'color' => '#0284c7',      'bg' => '#e0f2fe',
            'domain' => 'esg',         'label_vi' => 'Điện gió / khí hậu',
        ],
        'building' => [
            'fa' => 'fa-building',     'fa_style' => 'fa-regular',
            'sf' => 'building.fill',   'sf_mode'  => 'monochrome',
            'color' => '#334155',      'bg' => '#f1f5f9',
            'domain' => 'esg',         'label_vi' => 'Tòa nhà xanh / ESG',
        ],
        'target' => [
            'fa' => 'fa-bullseye',     'fa_style' => 'fa-regular',
            'sf' => 'scope',           'sf_mode'  => 'monochrome',
            'color' => '#dc2626',      'bg' => '#fff1f2',
            'domain' => 'esg',         'label_vi' => 'Mục tiêu Net Zero',
        ],
        'check' => [
            'fa' => 'fa-circle-check', 'fa_style' => 'fa-regular',
            'sf' => 'checkmark.seal.fill', 'sf_mode' => 'multicolor',
            'color' => '#16a34a',      'bg' => '#f0fdf4',
            'domain' => 'esg',         'label_vi' => 'Xác nhận / chứng chỉ',
        ],

        // ── Chung / Cross-domain ───────────────────────────────────────
        'idea' => [
            'fa' => 'fa-lightbulb',    'fa_style' => 'fa-regular',
            'sf' => 'lightbulb.fill',  'sf_mode'  => 'multicolor',
            'color' => '#ca8a04',      'bg' => '#fefce8',
            'domain' => 'common',      'label_vi' => 'Ý tưởng / giải pháp',
        ],
        'star' => [
            'fa' => 'fa-star',         'fa_style' => 'fa-regular',
            'sf' => 'star.fill',       'sf_mode'  => 'multicolor',
            'color' => '#d97706',      'bg' => '#fff7ed',
            'domain' => 'common',      'label_vi' => 'Nổi bật / ưu điểm',
        ],
        'list' => [
            'fa' => 'fa-clipboard-list', 'fa_style' => 'fa-regular',
            'sf' => 'clipboard.fill',  'sf_mode'  => 'monochrome',
            'color' => '#1d4ed8',      'bg' => '#eff6ff',
            'domain' => 'common',      'label_vi' => 'Danh sách / quy trình',
        ],
        'pin' => [
            'fa' => 'fa-location-dot', 'fa_style' => 'fa-regular',
            'sf' => 'mappin.and.ellipse', 'sf_mode' => 'palette',
            'color' => '#dc2626',      'bg' => '#fef2f2',
            'domain' => 'common',      'label_vi' => 'Địa điểm / chi nhánh',
        ],
        'work' => [
            'fa' => 'fa-briefcase',    'fa_style' => 'fa-regular',
            'sf' => 'briefcase.fill',  'sf_mode'  => 'monochrome',
            'color' => '#7c3aed',      'bg' => '#faf5ff',
            'domain' => 'common',      'label_vi' => 'Công việc / tuyển dụng',
        ],
        'money' => [
            'fa' => 'fa-circle-dollar', 'fa_style' => 'fa-regular',
            'sf' => 'banknote.fill',   'sf_mode'  => 'hierarchical',
            'color' => '#15803d',      'bg' => '#f0fdf4',
            'domain' => 'common',      'label_vi' => 'Lương / thu nhập',
        ],
        'mail' => [
            'fa' => 'fa-envelope',     'fa_style' => 'fa-regular',
            'sf' => 'envelope.fill',   'sf_mode'  => 'multicolor',
            'color' => '#1d4ed8',      'bg' => '#eff6ff',
            'domain' => 'common',      'label_vi' => 'Email / liên hệ',
        ],
    ];

    // ──────────────────────────────────────────────────────────────────
    // Public API
    // ──────────────────────────────────────────────────────────────────

    /**
     * Lấy toàn bộ token map.
     */
    public static function all(): array
    {
        return self::$tokens;
    }

    /**
     * Lấy một token theo semantic key.
     */
    public static function get(string $semantic): ?array
    {
        return self::$tokens[$semantic] ?? null;
    }

    /**
     * Render HTML badge cho web:
     * <span class="nlp-icon nlp-icon--{semantic} {extraClass}">
     *   <i class="{fa_style} {fa}"></i>
     * </span>
     *
     * @param string $semantic  Token key (vd: 'solar', 'ai', 'bolt')
     * @param string $extra     CSS class bổ sung (vd: 'nlp-icon--pulse')
     */
    public static function html(string $semantic, string $extra = ''): string
    {
        $token = self::$tokens[$semantic] ?? null;
        if (! $token) {
            return '';
        }

        $extraClass = $extra ? ' ' . trim($extra) : '';

        return sprintf(
            '<span class="nlp-icon nlp-icon--%s%s"><i class="%s %s"></i></span>',
            e($semantic),
            e($extraClass),
            e($token['fa_style']),
            e($token['fa'])
        );
    }

    /**
     * Render HTML với animation:
     * Hỗ trợ: pulse | bounce | spin | beat | shake
     */
    public static function animated(string $semantic, string $effect = 'pulse'): string
    {
        return self::html($semantic, "nlp-icon--{$effect}");
    }

    /**
     * Xuất danh sách CSS variables cho tất cả tokens.
     * Dùng trong <style> hoặc CSS file.
     */
    public static function cssVars(): string
    {
        $lines = [':root {'];
        foreach (self::$tokens as $key => $t) {
            $lines[] = "  --nlp-icon-{$key}-color: {$t['color']};";
            $lines[] = "  --nlp-icon-{$key}-bg: {$t['bg']};";
        }
        $lines[] = '}';
        return implode("\n", $lines);
    }

    /**
     * Xuất JSON token map (cho JS / iOS handoff).
     */
    public static function toJson(bool $pretty = false): string
    {
        $flags = JSON_UNESCAPED_UNICODE;
        if ($pretty) {
            $flags |= JSON_PRETTY_PRINT;
        }
        return json_encode(self::$tokens, $flags);
    }

    /**
     * Lọc tokens theo domain.
     * Domain: energy | ev | technology | ai | esg | common
     */
    public static function byDomain(string $domain): array
    {
        return array_filter(
            self::$tokens,
            fn($t) => $t['domain'] === $domain
        );
    }
}
