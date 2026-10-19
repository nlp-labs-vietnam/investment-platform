<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal UI — NLP Icon Design Tokens Showcase</title>

    {{-- FontAwesome 6 Free từ cdnjs (không cần auth) --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />

    {{-- CSS animation tokens (nlp-icon--pulse, bounce, spin, ...) --}}
    <link rel="stylesheet" href="{{ asset('assets/themes/green/css/style.css') }}">

    <style>
        /* ── CSS Variables ───────────────────────────────────────── */
        :root {
            --color-bg:           #f5f6f8;
            --color-surface:      #ffffff;
            --color-border:       #e2e6ea;
            --color-text:         #1a2030;
            --color-text-muted:   #6b7280;
            --color-text-meta:    #9ca3af;
            --color-accent:       #2563eb;
            --color-accent-bg:    #eff6ff;
            --color-success:      #16a34a;
            --color-success-bg:   #f0fdf4;
            --color-toast-bg:     #1e293b;
            --color-toast-text:   #f8fafc;
            --color-toolbar-bg:   #ffffff;
            --color-toolbar-shadow: 0 1px 0 var(--color-border);
            --color-card-hover:   #f0f4ff;
            --color-card-shadow:  0 2px 8px rgba(0,0,0,.07);
            --radius-card:        10px;
            --radius-input:       8px;
            --radius-badge:       20px;
            --font-ui: -apple-system, "Segoe UI", system-ui, "Helvetica Neue", Arial, sans-serif;
            --font-mono: "SF Mono", "Fira Code", "Consolas", monospace;
            --transition-fast: .14s ease;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --color-bg:           #0f1117;
                --color-surface:      #1c1f26;
                --color-border:       #2d3242;
                --color-text:         #e8eaf0;
                --color-text-muted:   #9aa3b4;
                --color-text-meta:    #6b7280;
                --color-accent:       #60a5fa;
                --color-accent-bg:    #1e2d4a;
                --color-success:      #4ade80;
                --color-success-bg:   #052e16;
                --color-toast-bg:     #f1f5f9;
                --color-toast-text:   #0f172a;
                --color-toolbar-bg:   #1c1f26;
                --color-toolbar-shadow: 0 1px 0 var(--color-border);
                --color-card-hover:   #1e2540;
                --color-card-shadow:  0 2px 8px rgba(0,0,0,.28);
            }
        }

        /* ── Reset & Base ────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-ui);
            font-size: 14px;
            line-height: 1.5;
            color: var(--color-text);
            background: var(--color-bg);
            min-height: 100vh;
        }

        /* ── Toolbar ─────────────────────────────────────────────── */
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: var(--color-toolbar-bg);
            box-shadow: var(--color-toolbar-shadow);
        }

        .toolbar-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--color-accent);
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-right: auto;
            white-space: nowrap;
        }

        .toolbar-title span {
            font-weight: 400;
            color: var(--color-text-muted);
            font-size: 12px;
            margin-left: 6px;
        }

        /* ── Controls: search & selects ─────────────────────────── */
        .search-input,
        .select-control {
            height: 36px;
            padding: 0 12px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-input);
            background: var(--color-surface);
            color: var(--color-text);
            font-family: var(--font-ui);
            font-size: 13px;
            outline: none;
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        }

        .search-input {
            width: 220px;
            padding-left: 32px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 10px center;
        }

        .search-input:focus,
        .select-control:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }

        .select-control { cursor: pointer; }

        .control-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .control-label {
            font-size: 12px;
            color: var(--color-text-muted);
            white-space: nowrap;
        }

        /* ── Thống kê tổng hợp ───────────────────────────────────── */
        .stats-bar {
            display: flex;
            gap: 20px;
            padding: 10px 20px;
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            font-size: 12px;
            color: var(--color-text-muted);
        }

        .stats-item strong {
            color: var(--color-text);
            font-weight: 600;
        }

        /* ── Icon Grid ───────────────────────────────────────────── */
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
            padding: 20px;
        }

        /* ── Icon Card ───────────────────────────────────────────── */
        .icon-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 18px 12px 14px;
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-card);
            cursor: pointer;
            transition:
                background var(--transition-fast),
                box-shadow var(--transition-fast),
                transform var(--transition-fast);
            text-align: center;
            user-select: none;
        }

        .icon-card:hover {
            background: var(--color-card-hover);
            box-shadow: var(--color-card-shadow);
            transform: translateY(-2px);
        }

        .icon-card:active {
            transform: translateY(0);
        }

        /* ── Icon wrapper — vùng hiển thị icon ──────────────────── */
        .icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            border-radius: 12px;
            /* nền và màu được gán inline từ token */
        }

        /* Override nlp-icon size trong card */
        .icon-card .nlp-icon {
            width: 100%;
            height: 100%;
            border-radius: 12px;
            font-size: 22px;
        }

        /* Fallback <i> tag */
        .icon-node {
            line-height: 1;
            /* font-size và color gán inline qua JS / inline style */
        }

        /* ── Token name ──────────────────────────────────────────── */
        .token-name {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text);
            font-family: var(--font-mono);
            word-break: break-all;
        }

        /* ── SF Symbol name ──────────────────────────────────────── */
        .sf-name {
            font-size: 10px;
            color: var(--color-text-meta);
            font-family: var(--font-mono);
            word-break: break-all;
        }

        /* ── Meta tags ───────────────────────────────────────────── */
        .meta-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .meta-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 7px;
            border-radius: var(--radius-badge);
            font-size: 10px;
            font-weight: 500;
            background: var(--color-bg);
            color: var(--color-text-muted);
            border: 1px solid var(--color-border);
        }

        /* ── Badge màu HEX ───────────────────────────────────────── */
        .badge-color {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 7px;
            border-radius: var(--radius-badge);
            font-size: 10px;
            font-weight: 600;
            font-family: var(--font-mono);
            background: var(--color-bg);
            color: var(--color-text);
            border: 1px solid var(--color-border);
        }

        .color-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 1px solid rgba(0,0,0,.08);
            flex-shrink: 0;
        }

        /* ── Empty state ─────────────────────────────────────────── */
        .empty-state {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 60px 20px;
            color: var(--color-text-muted);
            font-size: 14px;
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 32px;
            opacity: .4;
        }

        /* ── Toast thông báo copy ────────────────────────────────── */
        #toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            padding: 10px 18px;
            border-radius: 10px;
            background: var(--color-toast-bg);
            color: var(--color-toast-text);
            font-size: 13px;
            font-weight: 500;
            box-shadow: 0 4px 20px rgba(0,0,0,.18);
            transform: translateY(12px);
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease, transform .2s ease;
            max-width: 320px;
        }

        #toast.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* ── Page footer ─────────────────────────────────────────── */
        .page-footer {
            padding: 20px;
            text-align: center;
            font-size: 11px;
            color: var(--color-text-meta);
            border-top: 1px solid var(--color-border);
            margin-top: 12px;
        }

        .page-footer code {
            font-family: var(--font-mono);
            background: var(--color-bg);
            padding: 1px 5px;
            border-radius: 4px;
            border: 1px solid var(--color-border);
            font-size: 11px;
        }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 600px) {
            .toolbar { padding: 10px 12px; }
            .toolbar-title { font-size: 12px; }
            .search-input { width: 160px; }
            .icon-grid { grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 8px; padding: 12px; }
        }
    </style>
</head>
<body>

{{-- ── Toolbar ─────────────────────────────────────────────────────── --}}
<header class="toolbar">
    <div class="toolbar-title">
        NLP Icon Tokens
        <span>/ Internal Showcase</span>
    </div>

    {{-- Tìm kiếm theo tên token --}}
    <input
        type="search"
        id="searchInput"
        class="search-input"
        placeholder="Tìm kiếm token hoặc SF Symbol…"
        autocomplete="off"
        spellcheck="false"
    >

    {{-- Chọn hiệu ứng animation --}}
    <div class="control-group">
        <label class="control-label" for="animSelect">Hiệu ứng:</label>
        <select id="animSelect" class="select-control">
            <option value="">Tĩnh (None)</option>
            @foreach($animations as $key => $anim)
                <option value="{{ $key }}">{{ $anim['label'] }}</option>
            @endforeach
        </select>
    </div>

    {{-- Chọn cỡ icon --}}
    <div class="control-group">
        <label class="control-label" for="sizeSelect">Cỡ:</label>
        <select id="sizeSelect" class="select-control">
            <option value="20">Nhỏ — 20px</option>
            <option value="28" selected>Vừa — 28px</option>
            <option value="36">Lớn — 36px</option>
            <option value="48">Rất lớn — 48px</option>
        </select>
    </div>
</header>

{{-- ── Thanh thống kê ───────────────────────────────────────────────── --}}
<div class="stats-bar">
    <div class="stats-item">
        Tổng tokens: <strong>{{ count($tokens) }}</strong>
    </div>
    <div class="stats-item">
        Domains:
        @php $domains = array_unique(array_column($tokens, 'domain')); sort($domains); @endphp
        <strong>{{ count($domains) }}</strong>
        ({{ implode(', ', $domains) }})
    </div>
    <div class="stats-item">
        Animations: <strong>{{ count($animations) }}</strong>
    </div>
    <div class="stats-item">
        Nguồn:
        <strong><code style="font-family:inherit;background:none;border:none;padding:0;">NlpIcon.php</code></strong>
        → <strong><code style="font-family:inherit;background:none;border:none;padding:0;">AppSymbol.swift</code></strong>
    </div>
</div>

{{-- ── Icon Grid ───────────────────────────────────────────────────── --}}
<main>
    <div class="icon-grid" id="iconGrid">

        {{-- Trạng thái rỗng — hiện khi không có kết quả tìm kiếm --}}
        <div class="empty-state" id="emptyState">
            <i class="fa-regular fa-face-sad-sweat"></i>
            <span>Không tìm thấy token nào phù hợp.</span>
        </div>

        @foreach($tokens as $token => $meta)
            @php
                /* Lấy FA icon class từ token, fallback an toàn nếu thiếu field */
                $faClass   = $meta['fa']       ?? 'fa-circle-question';
                $faStyle   = $meta['fa_style'] ?? 'fa-regular';
                $sfSymbol  = $meta['sf']       ?? '';
                $hexColor  = $meta['color']    ?? '#6b7280';
                $hexBg     = $meta['bg']       ?? '#f1f5f9';
                $labelVi   = $meta['label_vi'] ?? $token;
                $domain    = $meta['domain']   ?? 'common';
            @endphp

            <div
                class="icon-card"
                data-token="{{ $token }}"
                data-fa="{{ $faClass }}"
                data-sf="{{ $sfSymbol }}"
                data-domain="{{ $domain }}"
                title="Nhấp để sao chép directive @nlpicon('{{ $token }}')"
                onclick="copyDirective('{{ $token }}')"
            >
                {{-- ── Vùng hiển thị icon ─────────────────────────── --}}
                <div
                    class="icon-wrapper"
                    style="background: {{ $hexBg }}; color: {{ $hexColor }};"
                >
                    @if(\Illuminate\Support\Facades\View::exists('components.nlpicon'))
                        {{-- Dùng Blade directive nếu component đã đăng ký --}}
                        @nlpicon($token)
                    @else
                        {{-- Fallback: <i> tag trực tiếp với class icon-node --}}
                        {{-- class "icon-node" bắt buộc để JS selector hoạt động --}}
                        <i
                            class="{{ $faStyle }} {{ $faClass }} icon-node"
                            style="font-size: 28px; color: {{ $hexColor }};"
                        ></i>
                    @endif
                </div>

                {{-- ── Tên token ngữ nghĩa ────────────────────────── --}}
                <span class="token-name">{{ $token }}</span>

                {{-- ── Nhãn tiếng Việt ────────────────────────────── --}}
                <span class="sf-name" title="SF Symbol: {{ $sfSymbol }}">{{ $sfSymbol }}</span>

                {{-- ── Meta: domain + màu ─────────────────────────── --}}
                <div class="meta-row">
                    <span class="meta-tag">{{ $domain }}</span>
                    <span class="badge-color">
                        <span
                            class="color-dot"
                            style="background: {{ $hexColor }};"
                        ></span>
                        {{ strtoupper($hexColor) }}
                    </span>
                </div>
            </div>
        @endforeach

    </div>{{-- /#iconGrid --}}
</main>

{{-- ── Footer ─────────────────────────────────────────────────────── --}}
<footer class="page-footer">
    <p>
        Internal Tool — NLP Group Design System &nbsp;|&nbsp;
        Nguồn SSOT: <code>app/Support/Icons/NlpIcon.php</code> &nbsp;|&nbsp;
        Xem token JSON: <code><a href="{{ route('nlp.icons.json') }}" target="_blank" style="color:inherit;">/api/nlp-icons</a></code>
    </p>
</footer>

{{-- ── Toast thông báo copy ────────────────────────────────────────── --}}
<div id="toast" role="status" aria-live="polite"></div>

{{-- ── JavaScript thuần — không dùng framework ───────────────────── --}}
<script>
'use strict';

/* ── Tham chiếu DOM ──────────────────────────────────────────────── */
const searchInput = document.getElementById('searchInput');
const animSelect  = document.getElementById('animSelect');
const sizeSelect  = document.getElementById('sizeSelect');
const iconGrid    = document.getElementById('iconGrid');
const emptyState  = document.getElementById('emptyState');
const toast       = document.getElementById('toast');

/* Lấy tất cả icon card và tất cả icon node (FA <i> tag) */
function getCards()     { return iconGrid.querySelectorAll('.icon-card'); }
function getIconNodes() { return iconGrid.querySelectorAll('.icon-node'); }

/* ── 1. Bộ lọc tìm kiếm theo tên token và SF Symbol ─────────────── */
searchInput.addEventListener('input', function () {
    const keyword = this.value.trim().toLowerCase();
    let visibleCount = 0;

    getCards().forEach(function (card) {
        const tokenName = (card.dataset.token || '').toLowerCase();
        const sfName    = (card.dataset.sf    || '').toLowerCase();

        /* Hiện card nếu keyword rỗng hoặc khớp token/SF name */
        const matched = !keyword
            || tokenName.includes(keyword)
            || sfName.includes(keyword);

        card.style.display = matched ? '' : 'none';
        if (matched) visibleCount++;
    });

    /* Hiện thông báo rỗng nếu không có kết quả */
    emptyState.style.display = visibleCount === 0 ? 'flex' : 'none';
});

/* ── 2. Áp dụng / gỡ animation class hàng loạt ─────────────────── */
animSelect.addEventListener('change', function () {
    const selectedAnim = this.value; /* vd: 'pulse', 'spin', hoặc '' (tĩnh) */

    getCards().forEach(function (card) {
        /* Gỡ tất cả animation modifier cũ khỏi wrapper */
        card.querySelector('.icon-wrapper')
            ?.classList.remove(
                'nlp-icon--pulse', 'nlp-icon--bounce', 'nlp-icon--spin',
                'nlp-icon--beat',  'nlp-icon--shake',  'nlp-icon--fade',
                'nlp-icon--pulse-hover', 'nlp-icon--bounce-hover', 'nlp-icon--beat-hover'
            );

        /* Gỡ khỏi nlp-icon span nếu có */
        card.querySelector('.nlp-icon')
            ?.classList.remove(
                'nlp-icon--pulse', 'nlp-icon--bounce', 'nlp-icon--spin',
                'nlp-icon--beat',  'nlp-icon--shake',  'nlp-icon--fade'
            );

        /* Áp animation mới nếu được chọn */
        if (selectedAnim) {
            const animClass = 'nlp-icon--' + selectedAnim;

            /* Thêm vào wrapper (hoạt động cả khi dùng .nlp-icon hoặc fallback) */
            card.querySelector('.icon-wrapper')?.classList.add(animClass);

            /* Thêm thẳng vào .nlp-icon span nếu tồn tại */
            card.querySelector('.nlp-icon')?.classList.add(animClass);
        }
    });
});

/* ── 3. Thay đổi cỡ icon hàng loạt ─────────────────────────────── */
sizeSelect.addEventListener('change', function () {
    const newSize = this.value + 'px'; /* vd: '28px' */

    /* Cập nhật fallback <i class="icon-node"> */
    getIconNodes().forEach(function (node) {
        node.style.fontSize = newSize;
    });

    /* Cập nhật .nlp-icon badge (dùng CSS font-size override) */
    iconGrid.querySelectorAll('.nlp-icon').forEach(function (el) {
        el.style.fontSize = parseFloat(this.value) * 0.55 + 'px';
    }, this);

    /* Cập nhật .icon-wrapper kích thước tương ứng */
    const wrapperSize = (parseFloat(this.value) + 24) + 'px';
    iconGrid.querySelectorAll('.icon-wrapper').forEach(function (el) {
        el.style.width  = wrapperSize;
        el.style.height = wrapperSize;
    });
});

/* ── 4. Sao chép Blade directive vào clipboard ─────────────────── */
function copyDirective(token) {
    const selectedAnim = animSelect.value;

    /* Xây dựng snippet tùy trạng thái animation dropdown */
    let snippet;
    if (selectedAnim) {
        /* Có animation: @nlpicon('solar', 'nlp-icon--pulse') */
        snippet = "@nlpicon('" + token + "', 'nlp-icon--" + selectedAnim + "')";
    } else {
        /* Không animation: @nlpicon('solar') */
        snippet = "@nlpicon('" + token + "')";
    }

    /* Ghi vào clipboard; hiện toast thông báo kết quả */
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(snippet)
            .then(function () {
                showToast('✅ Đã sao chép: ' + snippet);
            })
            .catch(function () {
                /* Fallback khi clipboard API bị chặn (HTTP hoặc iframe) */
                fallbackCopy(snippet);
            });
    } else {
        fallbackCopy(snippet);
    }
}

/* Fallback copy cho môi trường không hỗ trợ Clipboard API */
function fallbackCopy(text) {
    const el = document.createElement('textarea');
    el.value = text;
    el.setAttribute('readonly', '');
    el.style.cssText = 'position:fixed;top:-9999px;left:-9999px;';
    document.body.appendChild(el);
    el.select();
    try {
        document.execCommand('copy');
        showToast('✅ Đã sao chép: ' + text);
    } catch (err) {
        showToast('⚠️ Không thể sao chép. Hãy copy thủ công: ' + text);
    }
    document.body.removeChild(el);
}

/* Hiển thị toast trong 2 giây */
let toastTimer = null;
function showToast(message) {
    toast.textContent = message;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () {
        toast.classList.remove('show');
    }, 2000);
}
</script>

</body>
</html>
