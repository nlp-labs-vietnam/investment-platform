<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedNlpProducts extends Command
{
    protected $signature = 'nlp:seed-products {--force : Xóa và tạo lại toàn bộ dữ liệu}';
    protected $description = 'Seed trang sản phẩm EV Charging & Energy với thông số kỹ thuật chi tiết';

    public function handle()
    {
        $force  = $this->option('force');
        $langId = 21;

        $pages = $this->getPages();

        foreach ($pages as $page) {
            $this->seedPage($page, $langId, $force);
        }

        $this->newLine();
        $this->info('✅ NLP Products seed hoàn tất!');
    }

    private function seedPage(array $page, int $langId, bool $force): void
    {
        // Upsert pages
        $existingPage = DB::table('pages')->where('slug', $page['slug'])->where('template_name', 'green')->first();
        if ($existingPage) {
            DB::table('pages')->where('id', $existingPage->id)->update([
                'name'             => $page['name'],
                'meta_title'       => $page['meta_title'],
                'meta_keywords'    => json_encode($page['meta_keywords']),
                'meta_description' => $page['meta_description'],
                'updated_at'       => now(),
            ]);
            $pageId = $existingPage->id;
            $this->line("  [update] Page: {$page['slug']}");
        } else {
            $pageId = DB::table('pages')->insertGetId([
                'name'             => $page['name'],
                'slug'             => $page['slug'],
                'template_name'    => 'green',
                'meta_title'       => $page['meta_title'],
                'meta_keywords'    => json_encode($page['meta_keywords']),
                'meta_description' => $page['meta_description'],
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
            $this->info("  [create] Page: {$page['slug']} (id={$pageId})");
        }

        // Delete + insert page_details (avoid duplicates)
        DB::table('page_details')
            ->where('page_id', $pageId)
            ->where('language_id', $langId)
            ->delete();

        DB::table('page_details')->insert([
            'page_id'     => $pageId,
            'language_id' => $langId,
            'content'     => $page['content'],
            'sections'    => null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        $this->info("    → page_details seeded");
    }

    // ====================================================================
    // TRANG SẢN PHẨM
    // ====================================================================
    private function getPages(): array
    {
        return [
            [
                'name'             => 'nlp-ev-products',
                'slug'             => 'nlp-ev-products',
                'meta_title'       => 'Sản phẩm Trạm Sạc Xe Điện — Thông số kỹ thuật AC & DC | NLP EV Charging',
                'meta_keywords'    => ['trụ sạc xe điện', 'trạm sạc AC', 'trạm sạc DC', 'thông số kỹ thuật trụ sạc', 'NLP EV Charging'],
                'meta_description' => 'Danh mục sản phẩm trụ sạc xe điện NLP — AC 7kW đến 22kW, DC 30kW đến 360kW. Thông số kỹ thuật đầy đủ, tiêu chuẩn OCPP 2.0.1, bảo hành 24 tháng.',
                'content'          => $this->evProductsPage(),
            ],
            [
                'name'             => 'nlp-energy-products',
                'slug'             => 'nlp-energy-products',
                'meta_title'       => 'Sản phẩm Điện Mặt Trời & Lưu Trữ Năng Lượng — Thông số kỹ thuật | NLP Energy',
                'meta_keywords'    => ['tấm pin mặt trời', 'inverter năng lượng mặt trời', 'pin lưu trữ BESS', 'thông số kỹ thuật solar', 'NLP Energy'],
                'meta_description' => 'Danh mục sản phẩm năng lượng mặt trời NLP — tấm pin Mono PERC, inverter on-grid/off-grid, hệ thống BESS. Thông số kỹ thuật đầy đủ, bảo hành 25 năm.',
                'content'          => $this->energyProductsPage(),
            ],
        ];
    }

    // ====================================================================
    // NỘI DUNG TRANG SẢN PHẨM EV CHARGING
    // ====================================================================
    private function evProductsPage(): string
    {
        return '
<section class="page-hero" style="background:linear-gradient(135deg,#0f4c75 0%,#1b6ca8 100%);color:#fff;padding:60px 0;text-align:center;">
  <div class="container">
    <h1 style="font-size:2.4rem;font-weight:700;margin-bottom:12px;">Sản phẩm Trạm Sạc Xe Điện NLP</h1>
    <p style="font-size:1.1rem;opacity:.9;max-width:640px;margin:0 auto;">Trụ sạc AC & DC chuẩn quốc tế — Công nghệ OCPP 2.0.1 — Bảo hành 24 tháng toàn quốc</p>
  </div>
</section>

<!-- ===== TRẠM SẠC AC ===== -->
<section style="padding:60px 0;background:#f8f9fa;">
<div class="container">
<h2 style="font-size:1.9rem;font-weight:700;color:#0f4c75;margin-bottom:8px;">⚡ Trụ Sạc AC — Sạc Chậm & Sạc Bán Nhanh</h2>
<p style="color:#555;margin-bottom:40px;">Phù hợp cho nhà riêng, chung cư, văn phòng, khách sạn, bãi đỗ xe</p>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:28px;">

<!-- AC-7 -->
<div style="background:#fff;border-radius:12px;padding:28px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.06);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#e3f2fd;color:#1565c0;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">AC-7</div>
    <div><h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-AC7K Trụ Sạc 7 kW</h3><p style="margin:0;color:#777;font-size:.85rem;">Sạc chậm — Hộ gia đình</p></div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:55%;">Công suất đầu ra</td><td style="padding:7px 4px;font-weight:600;">7,4 kW</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp đầu vào</td><td style="padding:7px 4px;font-weight:600;">220V AC / 1 pha</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Chuẩn kết nối</td><td style="padding:7px 4px;font-weight:600;">Type 2 (IEC 62196)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Thời gian sạc</td><td style="padding:7px 4px;font-weight:600;">6–8 giờ (60 kWh)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Giao thức</td><td style="padding:7px 4px;font-weight:600;">OCPP 1.6 / 2.0.1</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Kết nối</td><td style="padding:7px 4px;font-weight:600;">4G / WiFi / Ethernet</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Màn hình</td><td style="padding:7px 4px;font-weight:600;">LCD 4.3 inch</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Cấp bảo vệ</td><td style="padding:7px 4px;font-weight:600;">IP54 / IK08</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Nhiệt độ vận hành</td><td style="padding:7px 4px;font-weight:600;">-20°C đến +55°C</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Kích thước (H×W×D)</td><td style="padding:7px 4px;font-weight:600;">520×180×120 mm</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">24 tháng</td></tr>
  </table>
  <div style="margin-top:16px;padding:12px;background:#f0f9ff;border-radius:8px;font-size:.82rem;color:#0369a1;">
    💡 <strong>Ứng dụng:</strong> Nhà riêng, chung cư, bãi đỗ xe văn phòng
  </div>
</div>

<!-- AC-11 -->
<div style="background:#fff;border-radius:12px;padding:28px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.06);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#e8f5e9;color:#2e7d32;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">AC-11</div>
    <div><h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-AC11K Trụ Sạc 11 kW</h3><p style="margin:0;color:#777;font-size:.85rem;">Sạc bán nhanh — Doanh nghiệp</p></div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:55%;">Công suất đầu ra</td><td style="padding:7px 4px;font-weight:600;">11 kW</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp đầu vào</td><td style="padding:7px 4px;font-weight:600;">380V AC / 3 pha</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Chuẩn kết nối</td><td style="padding:7px 4px;font-weight:600;">Type 2 (IEC 62196)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Thời gian sạc</td><td style="padding:7px 4px;font-weight:600;">4–6 giờ (60 kWh)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Số cổng sạc</td><td style="padding:7px 4px;font-weight:600;">2 cổng</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Giao thức</td><td style="padding:7px 4px;font-weight:600;">OCPP 1.6 / 2.0.1</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Kết nối</td><td style="padding:7px 4px;font-weight:600;">4G / WiFi / Ethernet</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Thanh toán</td><td style="padding:7px 4px;font-weight:600;">RFID / App / QR Code</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Cấp bảo vệ</td><td style="padding:7px 4px;font-weight:600;">IP55 / IK10</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Kích thước (H×W×D)</td><td style="padding:7px 4px;font-weight:600;">600×220×140 mm</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">24 tháng</td></tr>
  </table>
  <div style="margin-top:16px;padding:12px;background:#f0fdf4;border-radius:8px;font-size:.82rem;color:#15803d;">
    💡 <strong>Ứng dụng:</strong> Khách sạn, văn phòng, khu đô thị, trường học
  </div>
</div>

<!-- AC-22 -->
<div style="background:#fff;border-radius:12px;padding:28px;border:2px solid #3b82f6;box-shadow:0 4px 12px rgba(59,130,246,.15);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#dbeafe;color:#1d4ed8;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">AC-22</div>
    <div>
      <h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-AC22K Trụ Sạc 22 kW</h3>
      <p style="margin:0;font-size:.8rem;"><span style="background:#3b82f6;color:#fff;padding:2px 8px;border-radius:10px;">Bán chạy nhất</span></p>
    </div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:55%;">Công suất đầu ra</td><td style="padding:7px 4px;font-weight:600;">22 kW</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp đầu vào</td><td style="padding:7px 4px;font-weight:600;">380V AC / 3 pha</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Chuẩn kết nối</td><td style="padding:7px 4px;font-weight:600;">Type 2 (IEC 62196)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Thời gian sạc</td><td style="padding:7px 4px;font-weight:600;">2–3 giờ (60 kWh)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Số cổng sạc</td><td style="padding:7px 4px;font-weight:600;">2 cổng</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Màn hình</td><td style="padding:7px 4px;font-weight:600;">LCD 7 inch màu cảm ứng</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Thanh toán</td><td style="padding:7px 4px;font-weight:600;">RFID / App / QR / NFC</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Giao thức</td><td style="padding:7px 4px;font-weight:600;">OCPP 2.0.1</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Cấp bảo vệ</td><td style="padding:7px 4px;font-weight:600;">IP55 / IK10</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Bảo vệ</td><td style="padding:7px 4px;font-weight:600;">OVP, OCP, OTP, Leakage</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Kích thước (H×W×D)</td><td style="padding:7px 4px;font-weight:600;">780×280×170 mm</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">24 tháng</td></tr>
  </table>
  <div style="margin-top:16px;padding:12px;background:#eff6ff;border-radius:8px;font-size:.82rem;color:#1d4ed8;">
    💡 <strong>Ứng dụng:</strong> Trung tâm thương mại, bệnh viện, khu công nghiệp
  </div>
</div>

</div>
</div>
</section>

<!-- ===== TRẠM SẠC DC ===== -->
<section style="padding:60px 0;background:#fff;">
<div class="container">
<h2 style="font-size:1.9rem;font-weight:700;color:#7c3aed;margin-bottom:8px;">🔋 Trụ Sạc DC — Sạc Nhanh & Sạc Siêu Nhanh</h2>
<p style="color:#555;margin-bottom:40px;">Phù hợp cho trung tâm thương mại, trạm dừng cao tốc, đội xe thương mại</p>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:28px;">

<!-- DC-30 -->
<div style="background:#fff;border-radius:12px;padding:28px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.06);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#f3e8ff;color:#7c3aed;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">DC-30</div>
    <div><h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-DC30K Trụ Sạc DC 30 kW</h3><p style="margin:0;color:#777;font-size:.85rem;">Sạc nhanh — Đô thị</p></div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:55%;">Công suất đầu ra</td><td style="padding:7px 4px;font-weight:600;">30 kW</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp đầu vào</td><td style="padding:7px 4px;font-weight:600;">380V AC / 3 pha</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp đầu ra DC</td><td style="padding:7px 4px;font-weight:600;">200–1.000 V DC</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Dòng điện đầu ra</td><td style="padding:7px 4px;font-weight:600;">Max 75A</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Chuẩn kết nối</td><td style="padding:7px 4px;font-weight:600;">CCS2 / CHAdeMO</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Thời gian sạc</td><td style="padding:7px 4px;font-weight:600;">~90 phút (60 kWh)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Hiệu suất</td><td style="padding:7px 4px;font-weight:600;">≥ 94%</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Giao thức</td><td style="padding:7px 4px;font-weight:600;">OCPP 2.0.1</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Làm mát</td><td style="padding:7px 4px;font-weight:600;">Quạt thông minh</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Cấp bảo vệ</td><td style="padding:7px 4px;font-weight:600;">IP54 / IK10</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">24 tháng</td></tr>
  </table>
</div>

<!-- DC-60 -->
<div style="background:#fff;border-radius:12px;padding:28px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.06);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#fce7f3;color:#be185d;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">DC-60</div>
    <div><h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-DC60K Trụ Sạc DC 60 kW</h3><p style="margin:0;color:#777;font-size:.85rem;">Sạc nhanh — Đội xe</p></div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:55%;">Công suất đầu ra</td><td style="padding:7px 4px;font-weight:600;">60 kW</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp đầu vào</td><td style="padding:7px 4px;font-weight:600;">380V AC / 3 pha</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp đầu ra DC</td><td style="padding:7px 4px;font-weight:600;">200–1.000 V DC</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Dòng điện đầu ra</td><td style="padding:7px 4px;font-weight:600;">Max 150A</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Chuẩn kết nối</td><td style="padding:7px 4px;font-weight:600;">CCS2 / CHAdeMO / GB/T</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Thời gian sạc</td><td style="padding:7px 4px;font-weight:600;">~45 phút (60 kWh)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Số cổng sạc</td><td style="padding:7px 4px;font-weight:600;">2 cổng (30 kW/cổng)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Hiệu suất</td><td style="padding:7px 4px;font-weight:600;">≥ 95%</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Màn hình</td><td style="padding:7px 4px;font-weight:600;">LCD 10 inch màu</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Thanh toán</td><td style="padding:7px 4px;font-weight:600;">RFID / App / QR / NFC</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">24 tháng</td></tr>
  </table>
</div>

<!-- DC-120 -->
<div style="background:#fff;border-radius:12px;padding:28px;border:2px solid #7c3aed;box-shadow:0 4px 12px rgba(124,58,237,.15);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#7c3aed;color:#fff;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;">DC-120</div>
    <div>
      <h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-DC120K Trụ Sạc DC 120 kW</h3>
      <p style="margin:0;font-size:.8rem;"><span style="background:#7c3aed;color:#fff;padding:2px 8px;border-radius:10px;">Cao tốc / Fleet</span></p>
    </div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:55%;">Công suất đầu ra</td><td style="padding:7px 4px;font-weight:600;">120 kW</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp đầu vào</td><td style="padding:7px 4px;font-weight:600;">380–480V AC / 3 pha</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp đầu ra DC</td><td style="padding:7px 4px;font-weight:600;">200–1.000 V DC</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Dòng điện đầu ra</td><td style="padding:7px 4px;font-weight:600;">Max 300A</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Chuẩn kết nối</td><td style="padding:7px 4px;font-weight:600;">CCS2 / CHAdeMO / GB/T</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Thời gian sạc</td><td style="padding:7px 4px;font-weight:600;">~22 phút (60 kWh)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Số cổng sạc</td><td style="padding:7px 4px;font-weight:600;">2 cổng (60 kW/cổng)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Hiệu suất</td><td style="padding:7px 4px;font-weight:600;">≥ 96%</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Làm mát</td><td style="padding:7px 4px;font-weight:600;">Liquid cooling (tùy chọn)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Cấp bảo vệ</td><td style="padding:7px 4px;font-weight:600;">IP55 / IK10</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Bảo vệ</td><td style="padding:7px 4px;font-weight:600;">OVP, OCP, OTP, SCP, Leakage, SPD</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">24 tháng</td></tr>
  </table>
  <div style="margin-top:16px;padding:12px;background:#f5f3ff;border-radius:8px;font-size:.82rem;color:#6d28d9;">
    💡 <strong>Ứng dụng:</strong> Trạm dừng nghỉ cao tốc, đội xe taxi & logistics, sân bay
  </div>
</div>

<!-- DC-360 -->
<div style="background:linear-gradient(135deg,#1e1b4b 0%,#3730a3 100%);border-radius:12px;padding:28px;color:#fff;">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:rgba(255,255,255,.2);width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;">DC-360</div>
    <div>
      <h3 style="margin:0;font-size:1.15rem;color:#fff;">NLP-DC360K Trạm Sạc 360 kW</h3>
      <p style="margin:0;font-size:.8rem;"><span style="background:#f59e0b;color:#000;padding:2px 8px;border-radius:10px;">Ultra-Fast Charging</span></p>
    </div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;color:rgba(255,255,255,.9);">
    <tr style="border-bottom:1px solid rgba(255,255,255,.1);"><td style="padding:7px 4px;width:55%;opacity:.8;">Tổng công suất</td><td style="padding:7px 4px;font-weight:600;">360 kW</td></tr>
    <tr style="border-bottom:1px solid rgba(255,255,255,.1);"><td style="padding:7px 4px;opacity:.8;">Cấu hình</td><td style="padding:7px 4px;font-weight:600;">6 cổng × 60 kW (linh hoạt)</td></tr>
    <tr style="border-bottom:1px solid rgba(255,255,255,.1);"><td style="padding:7px 4px;opacity:.8;">Điện áp đầu vào</td><td style="padding:7px 4px;font-weight:600;">10 kV / 22 kV (qua MBA)</td></tr>
    <tr style="border-bottom:1px solid rgba(255,255,255,.1);"><td style="padding:7px 4px;opacity:.8;">Điện áp đầu ra DC</td><td style="padding:7px 4px;font-weight:600;">200–1.000 V DC</td></tr>
    <tr style="border-bottom:1px solid rgba(255,255,255,.1);"><td style="padding:7px 4px;opacity:.8;">Công nghệ</td><td style="padding:7px 4px;font-weight:600;">Dynamic Power Sharing</td></tr>
    <tr style="border-bottom:1px solid rgba(255,255,255,.1);"><td style="padding:7px 4px;opacity:.8;">Thời gian sạc</td><td style="padding:7px 4px;font-weight:600;">&lt;10 phút (10–80%)</td></tr>
    <tr style="border-bottom:1px solid rgba(255,255,255,.1);"><td style="padding:7px 4px;opacity:.8;">Giao thức</td><td style="padding:7px 4px;font-weight:600;">OCPP 2.0.1 / ISO 15118</td></tr>
    <tr style="border-bottom:1px solid rgba(255,255,255,.1);"><td style="padding:7px 4px;opacity:.8;">Tích hợp</td><td style="padding:7px 4px;font-weight:600;">V2G / Smart Grid</td></tr>
    <tr style="border-bottom:1px solid rgba(255,255,255,.1);"><td style="padding:7px 4px;opacity:.8;">Hiệu suất</td><td style="padding:7px 4px;font-weight:600;">≥ 97%</td></tr>
    <tr><td style="padding:7px 4px;opacity:.8;">Bảo hành</td><td style="padding:7px 4px;font-weight:600;color:#4ade80;">24 tháng + hỗ trợ kỹ thuật</td></tr>
  </table>
  <div style="margin-top:16px;padding:12px;background:rgba(255,255,255,.1);border-radius:8px;font-size:.82rem;">
    💡 <strong>Ứng dụng:</strong> Hub sạc lớn, sân bay, cảng biển, khu công nghiệp, dịch vụ xe tự hành
  </div>
</div>

</div>
</div>
</section>

<!-- ===== BẢNG SO SÁNH ===== -->
<section style="padding:60px 0;background:#f8f9fa;">
<div class="container">
<h2 style="font-size:1.9rem;font-weight:700;color:#1a1a1a;margin-bottom:40px;text-align:center;">📊 Bảng So Sánh Thông Số Sản Phẩm</h2>
<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);">
  <thead style="background:#0f4c75;color:#fff;">
    <tr>
      <th style="padding:14px 16px;text-align:left;">Model</th>
      <th style="padding:14px 16px;text-align:center;">Loại</th>
      <th style="padding:14px 16px;text-align:center;">Công suất</th>
      <th style="padding:14px 16px;text-align:center;">Điện áp vào</th>
      <th style="padding:14px 16px;text-align:center;">Thời gian sạc</th>
      <th style="padding:14px 16px;text-align:center;">Giao thức</th>
      <th style="padding:14px 16px;text-align:center;">Bảo hành</th>
    </tr>
  </thead>
  <tbody>
    <tr style="border-bottom:1px solid #f0f0f0;">
      <td style="padding:12px 16px;font-weight:600;">NLP-AC7K</td>
      <td style="padding:12px 16px;text-align:center;"><span style="background:#e3f2fd;color:#1565c0;padding:3px 10px;border-radius:10px;font-size:.8rem;">AC Sạc chậm</span></td>
      <td style="padding:12px 16px;text-align:center;">7,4 kW</td>
      <td style="padding:12px 16px;text-align:center;">220V / 1 pha</td>
      <td style="padding:12px 16px;text-align:center;">6–8 giờ</td>
      <td style="padding:12px 16px;text-align:center;">OCPP 1.6/2.0.1</td>
      <td style="padding:12px 16px;text-align:center;color:#16a34a;font-weight:600;">24 tháng</td>
    </tr>
    <tr style="border-bottom:1px solid #f0f0f0;background:#f9fafb;">
      <td style="padding:12px 16px;font-weight:600;">NLP-AC11K</td>
      <td style="padding:12px 16px;text-align:center;"><span style="background:#e8f5e9;color:#2e7d32;padding:3px 10px;border-radius:10px;font-size:.8rem;">AC Bán nhanh</span></td>
      <td style="padding:12px 16px;text-align:center;">11 kW</td>
      <td style="padding:12px 16px;text-align:center;">380V / 3 pha</td>
      <td style="padding:12px 16px;text-align:center;">4–6 giờ</td>
      <td style="padding:12px 16px;text-align:center;">OCPP 1.6/2.0.1</td>
      <td style="padding:12px 16px;text-align:center;color:#16a34a;font-weight:600;">24 tháng</td>
    </tr>
    <tr style="border-bottom:1px solid #f0f0f0;">
      <td style="padding:12px 16px;font-weight:600;">NLP-AC22K</td>
      <td style="padding:12px 16px;text-align:center;"><span style="background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:10px;font-size:.8rem;">AC Bán nhanh</span></td>
      <td style="padding:12px 16px;text-align:center;">22 kW</td>
      <td style="padding:12px 16px;text-align:center;">380V / 3 pha</td>
      <td style="padding:12px 16px;text-align:center;">2–3 giờ</td>
      <td style="padding:12px 16px;text-align:center;">OCPP 2.0.1</td>
      <td style="padding:12px 16px;text-align:center;color:#16a34a;font-weight:600;">24 tháng</td>
    </tr>
    <tr style="border-bottom:1px solid #f0f0f0;background:#f9fafb;">
      <td style="padding:12px 16px;font-weight:600;">NLP-DC30K</td>
      <td style="padding:12px 16px;text-align:center;"><span style="background:#f3e8ff;color:#7c3aed;padding:3px 10px;border-radius:10px;font-size:.8rem;">DC Nhanh</span></td>
      <td style="padding:12px 16px;text-align:center;">30 kW</td>
      <td style="padding:12px 16px;text-align:center;">380V / 3 pha</td>
      <td style="padding:12px 16px;text-align:center;">~90 phút</td>
      <td style="padding:12px 16px;text-align:center;">OCPP 2.0.1</td>
      <td style="padding:12px 16px;text-align:center;color:#16a34a;font-weight:600;">24 tháng</td>
    </tr>
    <tr style="border-bottom:1px solid #f0f0f0;">
      <td style="padding:12px 16px;font-weight:600;">NLP-DC60K</td>
      <td style="padding:12px 16px;text-align:center;"><span style="background:#fce7f3;color:#be185d;padding:3px 10px;border-radius:10px;font-size:.8rem;">DC Nhanh</span></td>
      <td style="padding:12px 16px;text-align:center;">60 kW</td>
      <td style="padding:12px 16px;text-align:center;">380V / 3 pha</td>
      <td style="padding:12px 16px;text-align:center;">~45 phút</td>
      <td style="padding:12px 16px;text-align:center;">OCPP 2.0.1</td>
      <td style="padding:12px 16px;text-align:center;color:#16a34a;font-weight:600;">24 tháng</td>
    </tr>
    <tr style="border-bottom:1px solid #f0f0f0;background:#f9fafb;">
      <td style="padding:12px 16px;font-weight:600;">NLP-DC120K</td>
      <td style="padding:12px 16px;text-align:center;"><span style="background:#ede9fe;color:#6d28d9;padding:3px 10px;border-radius:10px;font-size:.8rem;">DC Siêu nhanh</span></td>
      <td style="padding:12px 16px;text-align:center;">120 kW</td>
      <td style="padding:12px 16px;text-align:center;">380–480V / 3 pha</td>
      <td style="padding:12px 16px;text-align:center;">~22 phút</td>
      <td style="padding:12px 16px;text-align:center;">OCPP 2.0.1</td>
      <td style="padding:12px 16px;text-align:center;color:#16a34a;font-weight:600;">24 tháng</td>
    </tr>
    <tr style="background:#1e1b4b;color:#fff;">
      <td style="padding:12px 16px;font-weight:600;">NLP-DC360K</td>
      <td style="padding:12px 16px;text-align:center;"><span style="background:#f59e0b;color:#000;padding:3px 10px;border-radius:10px;font-size:.8rem;">Ultra-Fast</span></td>
      <td style="padding:12px 16px;text-align:center;">360 kW</td>
      <td style="padding:12px 16px;text-align:center;">10/22 kV MBA</td>
      <td style="padding:12px 16px;text-align:center;">&lt;10 phút</td>
      <td style="padding:12px 16px;text-align:center;">OCPP 2.0.1 / ISO 15118</td>
      <td style="padding:12px 16px;text-align:center;color:#4ade80;font-weight:600;">24 tháng</td>
    </tr>
  </tbody>
</table>
</div>
</div>
</section>

<!-- ===== CTA ===== -->
<section style="padding:60px 0;background:linear-gradient(135deg,#0f4c75 0%,#1b6ca8 100%);color:#fff;text-align:center;">
  <div class="container">
    <h2 style="font-size:1.8rem;font-weight:700;margin-bottom:16px;">Tư vấn giải pháp trạm sạc phù hợp</h2>
    <p style="font-size:1rem;opacity:.9;max-width:560px;margin:0 auto 28px;">Đội ngũ kỹ sư NLP EV Charging sẵn sàng khảo sát, tư vấn và thiết kế hệ thống trạm sạc tối ưu cho dự án của bạn.</p>
    <a href="/contact" style="display:inline-block;background:#fff;color:#0f4c75;padding:14px 36px;border-radius:8px;font-weight:700;text-decoration:none;font-size:1rem;margin-right:12px;">Liên hệ tư vấn ngay</a>
    <a href="/nlp-ev-charging" style="display:inline-block;background:transparent;color:#fff;padding:14px 36px;border-radius:8px;font-weight:600;text-decoration:none;font-size:1rem;border:2px solid rgba(255,255,255,.6);">Xem thêm về EV Charging</a>
  </div>
</section>';
    }

    // ====================================================================
    // NỘI DUNG TRANG SẢN PHẨM ENERGY
    // ====================================================================
    private function energyProductsPage(): string
    {
        return '
<section class="page-hero" style="background:linear-gradient(135deg,#14532d 0%,#16a34a 100%);color:#fff;padding:60px 0;text-align:center;">
  <div class="container">
    <h1 style="font-size:2.4rem;font-weight:700;margin-bottom:12px;">Sản phẩm Điện Mặt Trời & Lưu Trữ Năng Lượng</h1>
    <p style="font-size:1.1rem;opacity:.9;max-width:640px;margin:0 auto;">Tấm pin Mono PERC — Inverter On/Off-Grid — Hệ thống BESS — Bảo hành 25 năm sản lượng</p>
  </div>
</section>

<!-- TẤM PIN MẶT TRỜI -->
<section style="padding:60px 0;background:#f8f9fa;">
<div class="container">
<h2 style="font-size:1.9rem;font-weight:700;color:#14532d;margin-bottom:8px;">☀️ Tấm Pin Mặt Trời</h2>
<p style="color:#555;margin-bottom:40px;">Công nghệ Mono PERC & TOPCon — Hiệu suất cao, tuổi thọ 30+ năm</p>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:28px;">

<!-- MONO PERC 400W -->
<div style="background:#fff;border-radius:12px;padding:28px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.06);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#dcfce7;color:#15803d;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;">400W</div>
    <div><h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-PV400 Tấm pin 400 Wp</h3><p style="margin:0;color:#777;font-size:.85rem;">Mono PERC — Nhà ở & SME</p></div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:60%;">Công suất đỉnh (Wp)</td><td style="padding:7px 4px;font-weight:600;">400 Wp</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Hiệu suất tế bào</td><td style="padding:7px 4px;font-weight:600;">21,5%</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp hở mạch (Voc)</td><td style="padding:7px 4px;font-weight:600;">37,2 V</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Dòng điện ngắn mạch (Isc)</td><td style="padding:7px 4px;font-weight:600;">13,52 A</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp MPP (Vmpp)</td><td style="padding:7px 4px;font-weight:600;">30,8 V</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Dòng điện MPP (Impp)</td><td style="padding:7px 4px;font-weight:600;">12,99 A</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Kích thước (mm)</td><td style="padding:7px 4px;font-weight:600;">1.755 × 1.038 × 35</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Trọng lượng</td><td style="padding:7px 4px;font-weight:600;">20,5 kg</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Chịu tải tuyết/gió</td><td style="padding:7px 4px;font-weight:600;">5.400 / 2.400 Pa</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Nhiệt độ vận hành</td><td style="padding:7px 4px;font-weight:600;">-40°C đến +85°C</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành sản lượng</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">25 năm (≥80%)</td></tr>
  </table>
</div>

<!-- TOPCON 550W -->
<div style="background:#fff;border-radius:12px;padding:28px;border:2px solid #16a34a;box-shadow:0 4px 12px rgba(22,163,74,.15);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#16a34a;color:#fff;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;">550W</div>
    <div>
      <h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-PV550 Tấm pin 550 Wp</h3>
      <p style="margin:0;font-size:.8rem;"><span style="background:#16a34a;color:#fff;padding:2px 8px;border-radius:10px;">TOPCon N-Type</span></p>
    </div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:60%;">Công suất đỉnh (Wp)</td><td style="padding:7px 4px;font-weight:600;">550 Wp</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Hiệu suất module</td><td style="padding:7px 4px;font-weight:600;">22,8%</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp hở mạch (Voc)</td><td style="padding:7px 4px;font-weight:600;">49,5 V</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Dòng điện ngắn mạch (Isc)</td><td style="padding:7px 4px;font-weight:600;">14,05 A</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp MPP (Vmpp)</td><td style="padding:7px 4px;font-weight:600;">41,8 V</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Dòng điện MPP (Impp)</td><td style="padding:7px 4px;font-weight:600;">13,15 A</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Kích thước (mm)</td><td style="padding:7px 4px;font-weight:600;">2.278 × 1.134 × 35</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Trọng lượng</td><td style="padding:7px 4px;font-weight:600;">28,6 kg</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Hệ số nhiệt độ (Pmax)</td><td style="padding:7px 4px;font-weight:600;">-0,29%/°C</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Bifacial</td><td style="padding:7px 4px;font-weight:600;">Có (+15% sản lượng)</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành sản lượng</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">30 năm (≥88%)</td></tr>
  </table>
</div>

</div>
</div>
</section>

<!-- INVERTER -->
<section style="padding:60px 0;background:#fff;">
<div class="container">
<h2 style="font-size:1.9rem;font-weight:700;color:#92400e;margin-bottom:8px;">🔌 Inverter Hòa Lưới & Độc Lập</h2>
<p style="color:#555;margin-bottom:40px;">On-Grid, Off-Grid và Hybrid — Hiệu suất ≥98%, MPPT kép</p>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:28px;">

<!-- INVERTER 5kW ON-GRID -->
<div style="background:#fff;border-radius:12px;padding:28px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.06);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#fef3c7;color:#b45309;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;">5kW</div>
    <div><h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-INV5K Inverter 5 kW</h3><p style="margin:0;color:#777;font-size:.85rem;">On-Grid — Nhà ở</p></div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:60%;">Công suất AC đầu ra</td><td style="padding:7px 4px;font-weight:600;">5.000 W</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">MPPT</td><td style="padding:7px 4px;font-weight:600;">2 MPPT</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp DC vào (Vmax)</td><td style="padding:7px 4px;font-weight:600;">600 V</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Dải điện áp MPP</td><td style="padding:7px 4px;font-weight:600;">80–550 V</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp AC đầu ra</td><td style="padding:7px 4px;font-weight:600;">220V / 50Hz</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Hiệu suất tối đa</td><td style="padding:7px 4px;font-weight:600;">98,2%</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Bảo vệ</td><td style="padding:7px 4px;font-weight:600;">IP65</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Kết nối</td><td style="padding:7px 4px;font-weight:600;">WiFi + App</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">10 năm</td></tr>
  </table>
</div>

<!-- HYBRID INVERTER 10kW -->
<div style="background:#fff;border-radius:12px;padding:28px;border:2px solid #f59e0b;box-shadow:0 4px 12px rgba(245,158,11,.15);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#f59e0b;color:#fff;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;">10kW</div>
    <div>
      <h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-HYB10K Hybrid 10 kW</h3>
      <p style="margin:0;font-size:.8rem;"><span style="background:#f59e0b;color:#fff;padding:2px 8px;border-radius:10px;">Bán chạy nhất</span></p>
    </div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:60%;">Công suất AC đầu ra</td><td style="padding:7px 4px;font-weight:600;">10.000 W</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">MPPT</td><td style="padding:7px 4px;font-weight:600;">2 MPPT</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Công suất pin tối đa</td><td style="padding:7px 4px;font-weight:600;">20 kWh</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp DC vào (Vmax)</td><td style="padding:7px 4px;font-weight:600;">1.000 V</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp pin</td><td style="padding:7px 4px;font-weight:600;">48–60 V</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Chế độ</td><td style="padding:7px 4px;font-weight:600;">Grid-tied / Off-grid / Backup</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Hiệu suất tối đa</td><td style="padding:7px 4px;font-weight:600;">98,6%</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Thời gian chuyển off-grid</td><td style="padding:7px 4px;font-weight:600;">&lt;20ms</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Kết nối</td><td style="padding:7px 4px;font-weight:600;">WiFi / RS485 / CAN</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">10 năm</td></tr>
  </table>
</div>

</div>
</div>
</section>

<!-- BESS -->
<section style="padding:60px 0;background:#f8f9fa;">
<div class="container">
<h2 style="font-size:1.9rem;font-weight:700;color:#1d4ed8;margin-bottom:8px;">🔋 Hệ Thống Lưu Trữ Năng Lượng BESS</h2>
<p style="color:#555;margin-bottom:40px;">Pin LiFePO4 — Chu kỳ >6.000 lần — Hệ thống BMS thông minh</p>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:28px;">

<!-- BESS 10kWh -->
<div style="background:#fff;border-radius:12px;padding:28px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.06);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#dbeafe;color:#1d4ed8;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.72rem;">10kWh</div>
    <div><h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-BESS10 — 10 kWh</h3><p style="margin:0;color:#777;font-size:.85rem;">Hộ gia đình</p></div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:60%;">Dung lượng sử dụng được</td><td style="padding:7px 4px;font-weight:600;">9,5 kWh</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Hóa học pin</td><td style="padding:7px 4px;font-weight:600;">LiFePO4 (LFP)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Chu kỳ sạc</td><td style="padding:7px 4px;font-weight:600;">&gt;6.000 chu kỳ</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp hệ thống</td><td style="padding:7px 4px;font-weight:600;">48 V</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Công suất sạc/xả</td><td style="padding:7px 4px;font-weight:600;">5 kW / 5 kW</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Hiệu suất vòng lặp</td><td style="padding:7px 4px;font-weight:600;">≥96%</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Nhiệt độ vận hành</td><td style="padding:7px 4px;font-weight:600;">-10°C đến +50°C</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Cấp bảo vệ</td><td style="padding:7px 4px;font-weight:600;">IP65</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">10 năm</td></tr>
  </table>
</div>

<!-- BESS 100kWh Commercial -->
<div style="background:#fff;border-radius:12px;padding:28px;border:2px solid #1d4ed8;box-shadow:0 4px 12px rgba(29,78,216,.15);">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
    <div style="background:#1d4ed8;color:#fff;width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.7rem;">100kWh</div>
    <div>
      <h3 style="margin:0;font-size:1.15rem;color:#1a1a1a;">NLP-BESS100 — 100 kWh</h3>
      <p style="margin:0;font-size:.8rem;"><span style="background:#1d4ed8;color:#fff;padding:2px 8px;border-radius:10px;">Thương mại & Công nghiệp</span></p>
    </div>
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;width:60%;">Dung lượng sử dụng được</td><td style="padding:7px 4px;font-weight:600;">95 kWh</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Hóa học pin</td><td style="padding:7px 4px;font-weight:600;">LiFePO4 (LFP)</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Chu kỳ sạc</td><td style="padding:7px 4px;font-weight:600;">&gt;8.000 chu kỳ</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Điện áp hệ thống</td><td style="padding:7px 4px;font-weight:600;">614,4 V</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Công suất sạc/xả</td><td style="padding:7px 4px;font-weight:600;">50 kW / 50 kW</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Tích hợp BMS</td><td style="padding:7px 4px;font-weight:600;">Có — BMS cấp tế bào</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Kết nối EMS</td><td style="padding:7px 4px;font-weight:600;">Modbus / CAN / BACnet</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Phòng cháy</td><td style="padding:7px 4px;font-weight:600;">Hệ thống BFFS tích hợp</td></tr>
    <tr style="border-bottom:1px solid #f0f0f0;"><td style="padding:7px 4px;color:#555;">Tuổi thọ</td><td style="padding:7px 4px;font-weight:600;">≥15 năm</td></tr>
    <tr><td style="padding:7px 4px;color:#555;">Bảo hành</td><td style="padding:7px 4px;font-weight:600;color:#16a34a;">10 năm</td></tr>
  </table>
</div>

</div>
</div>
</section>

<!-- CTA Energy -->
<section style="padding:60px 0;background:linear-gradient(135deg,#14532d 0%,#16a34a 100%);color:#fff;text-align:center;">
  <div class="container">
    <h2 style="font-size:1.8rem;font-weight:700;margin-bottom:16px;">Tư vấn hệ thống điện mặt trời phù hợp</h2>
    <p style="font-size:1rem;opacity:.9;max-width:560px;margin:0 auto 28px;">NLP Energy cung cấp giải pháp trọn gói — khảo sát, thiết kế, thi công và bảo trì suốt vòng đời dự án.</p>
    <a href="/contact" style="display:inline-block;background:#fff;color:#14532d;padding:14px 36px;border-radius:8px;font-weight:700;text-decoration:none;font-size:1rem;margin-right:12px;">Nhận báo giá miễn phí</a>
    <a href="/nlp-energy" style="display:inline-block;background:transparent;color:#fff;padding:14px 36px;border-radius:8px;font-weight:600;text-decoration:none;font-size:1rem;border:2px solid rgba(255,255,255,.6);">Xem về NLP Energy</a>
  </div>
</section>';
    }
}
