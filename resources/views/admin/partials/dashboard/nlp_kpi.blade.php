{{-- ============================================================
     NLP Group KPI Banner — Admin Dashboard
     Hiển thị: thống kê NLP ecosystem + live platform stats
     ============================================================ --}}
@php
    $nlpKpi = [
        [
            'icon'    => '☀️',
            'value'   => '12 MW',
            'label'   => 'Công suất Solar lắp đặt',
            'color'   => '#2d6a4f',
            'bg'      => '#f0fdf4',
            'border'  => '#bbf7d0',
        ],
        [
            'icon'    => '🏗️',
            'value'   => '150+',
            'label'   => 'Dự án hoàn thành',
            'color'   => '#1e40af',
            'bg'      => '#eff6ff',
            'border'  => '#bfdbfe',
        ],
        [
            'icon'    => '⚡',
            'value'   => '34 tỉnh',
            'label'   => 'Phủ sóng EV Charging',
            'color'   => '#b45309',
            'bg'      => '#fffbeb',
            'border'  => '#fde68a',
        ],
        [
            'icon'    => '🌿',
            'value'   => '8.400 T',
            'label'   => 'CO₂ giảm / năm',
            'color'   => '#065f46',
            'bg'      => '#ecfdf5',
            'border'  => '#a7f3d0',
        ],
        [
            'icon'    => '📰',
            'value'   => \Illuminate\Support\Facades\DB::table('blogs')->where('status', 1)->count(),
            'label'   => 'Bài blog đã xuất bản',
            'color'   => '#5b21b6',
            'bg'      => '#f5f3ff',
            'border'  => '#ddd6fe',
        ],
        [
            'icon'    => '👥',
            'value'   => \Illuminate\Support\Facades\DB::table('users')->where('status', 1)->count(),
            'label'   => 'Người dùng đang hoạt động',
            'color'   => '#0f766e',
            'bg'      => '#f0fdfa',
            'border'  => '#99f6e4',
        ],
    ];
@endphp

<div class="card mb-3 mb-lg-5" style="border:none;background:linear-gradient(135deg,#1a3c2e 0%,#2d6a4f 100%);color:#fff;border-radius:12px;overflow:hidden">
    <div class="card-body py-4 px-4">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <div style="width:36px;height:36px;background:rgba(255,255,255,0.15);border-radius:8px;display:flex;align-items:center;justify-content:center">
                    <i class="bi bi-bar-chart-fill" style="color:#6ee7b7;font-size:18px"></i>
                </div>
                <div>
                    <h5 class="mb-0" style="color:#fff;font-weight:700">NLP Group — Tổng quan Hệ sinh thái</h5>
                    <small style="color:rgba(255,255,255,0.65)">Cập nhật: {{ now()->format('d/m/Y H:i') }}</small>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ url('/') }}" target="_blank" class="btn btn-sm"
                   style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.25);border-radius:6px">
                    <i class="bi-globe me-1"></i> Xem website
                </a>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-sm"
                   style="background:#6ee7b7;color:#1a3c2e;font-weight:600;border-radius:6px">
                    <i class="bi-pencil me-1"></i> Quản lý Blog
                </a>
            </div>
        </div>

        <div class="row g-3">
            @foreach($nlpKpi as $kpi)
            <div class="col-6 col-sm-4 col-lg-2">
                <div style="background:{{ $kpi['bg'] }};border:1px solid {{ $kpi['border'] }};border-radius:10px;padding:16px 14px;height:100%">
                    <div style="font-size:22px;margin-bottom:4px">{{ $kpi['icon'] }}</div>
                    <div style="font-size:22px;font-weight:700;color:{{ $kpi['color'] }};line-height:1.2">{{ $kpi['value'] }}</div>
                    <div style="font-size:11px;color:#57606a;margin-top:3px;line-height:1.3">{{ $kpi['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
