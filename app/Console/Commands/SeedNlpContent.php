<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * nlp:seed-content
 *
 * Seeds:
 *  1. 10 EV-blog bài (id 1–10) — mở rộng nội dung đầy đủ
 *  2. Trang /tuyen-dung — danh sách vị trí tuyển dụng thực tế
 */
class SeedNlpContent extends Command
{
    protected $signature   = 'nlp:seed-content {--force : Overwrite existing full-length content}';
    protected $description = 'Seed full content: 10 EV blog posts + /tuyen-dung jobs page';

    private int $langId = 21; // Tiếng Việt

    public function handle(): int
    {
        $this->seedEvBlogs();
        $this->seedTuyenDung();
        $this->info('nlp:seed-content completed.');
        return self::SUCCESS;
    }

    // =========================================================================
    // 1. EV Blog posts (id 1-10) — expand to full articles
    // =========================================================================
    private function seedEvBlogs(): void
    {
        $articles = $this->evBlogArticles();
        $updated  = 0;
        $skipped  = 0;

        foreach ($articles as $blogId => $html) {
            $row = DB::table('blog_details')
                ->where('blog_id', $blogId)
                ->where('language_id', $this->langId)
                ->first();

            if (!$row) {
                $this->warn("  blog_id {$blogId}: no row found — skip");
                continue;
            }

            $currentLen = strlen($row->description ?? '');

            if ($currentLen >= 1500 && !$this->option('force')) {
                $skipped++;
                $this->line("  skip  blog#{$blogId} (len={$currentLen}; use --force to overwrite)");
                continue;
            }

            DB::table('blog_details')
                ->where('blog_id', $blogId)
                ->where('language_id', $this->langId)
                ->update(['description' => $html]);

            $updated++;
            $this->info("  ✔ blog#{$blogId} updated (" . strlen($html) . " chars)");
        }

        $this->info("EV blogs: {$updated} updated, {$skipped} skipped.");
    }

    // =========================================================================
    // 2. /tuyen-dung page
    // =========================================================================
    private function seedTuyenDung(): void
    {
        $page = DB::table('pages')->where('slug', 'tuyen-dung')->first();
        if (!$page) {
            $this->warn('Page tuyen-dung not found — skip.');
            return;
        }

        $existing = DB::table('page_details')
            ->where('page_id', $page->id)
            ->where('language_id', $this->langId)
            ->first();

        if ($existing && strlen($existing->content ?? '') >= 1000 && !$this->option('force')) {
            $this->line("  skip  tuyen-dung (already has content; use --force)");
            return;
        }

        $html = $this->tuyenDungContent();

        if ($existing) {
            DB::table('page_details')
                ->where('page_id', $page->id)
                ->where('language_id', $this->langId)
                ->update(['content' => $html, 'sections' => null]);
        } else {
            DB::table('page_details')->insert([
                'page_id'     => $page->id,
                'language_id' => $this->langId,
                'name'        => 'Tuyển Dụng',
                'content'     => $html,
                'sections'    => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $this->info('  ✔ tuyen-dung updated (' . strlen($html) . ' chars)');
    }

    // =========================================================================
    // EV Blog article content
    // =========================================================================
    private function evBlogArticles(): array
    {
        return [

            // ─── Blog 1: CSMS ────────────────────────────────────────────────
            1 => <<<HTML
<div class="nlp-blog-content">
<h2>Phần mềm Quản lý Sạc EV (CSMS): Điều hành Toàn bộ Mạng lưới từ Một Backend</h2>

<p>Khi số lượng trạm sạc xe điện tại Việt Nam tăng trưởng hàng trăm phần trăm mỗi năm, bài toán <strong>quản lý tập trung</strong> trở thành yếu tố sống còn. Hệ thống CSMS (<em>Charge Station Management System</em>) là giải pháp cho phép vận hành viên giám sát, điều khiển và tối ưu toàn bộ mạng lưới trạm sạc từ một giao diện duy nhất.</p>

<h3>CSMS là gì?</h3>
<p>CSMS là nền tảng phần mềm đóng vai trò "não bộ" của mạng lưới sạc EV. Nó kết nối với từng trụ sạc qua giao thức <strong>OCPP 1.6-J</strong> hoặc <strong>OCPP 2.0.1</strong> (Open Charge Point Protocol), cho phép:</p>
<ul>
  <li>Giám sát trạng thái từng trụ sạc theo thời gian thực (Available / Charging / Faulted / Offline)</li>
  <li>Khởi động/dừng phiên sạc từ xa</li>
  <li>Cập nhật firmware trụ sạc qua mạng (OTA)</li>
  <li>Chẩn đoán lỗi và gửi cảnh báo tức thì</li>
  <li>Quản lý giá động theo giờ cao/thấp điểm</li>
</ul>

<h3>CSMS của NLP Group — Tính năng nổi bật</h3>
<p>Nền tảng CSMS do <strong>NLP Group</strong> phát triển và vận hành tích hợp AI để dự báo tải và tự động phân bổ công suất:</p>
<ul>
  <li><strong>Dashboard đa trạm:</strong> Theo dõi tất cả trụ sạc trên bản đồ tương tác, lọc theo tỉnh/thành phố hoặc khu vực.</li>
  <li><strong>Billing tự động:</strong> Xuất hóa đơn điện tử tích hợp VAT, hỗ trợ VietQR, MoMo, ZaloPay và thẻ VISA/Mastercard.</li>
  <li><strong>Phân tích tiêu thụ:</strong> Báo cáo kWh tiêu thụ, doanh thu, số phiên sạc theo ngày/tuần/tháng. Export CSV/PDF.</li>
  <li><strong>Xác thực người dùng:</strong> RFID card, QR code, app di động (iOS/Android) hoặc plug-and-charge (PnC).</li>
  <li><strong>Smart Load Balancing:</strong> Tự động phân chia công suất giữa các trụ khi nhiều xe sạc đồng thời, tránh quá tải tủ điện.</li>
  <li><strong>NOC 24/7:</strong> Trung tâm giám sát mạng, cảnh báo SMS/email khi có sự cố.</li>
</ul>

<h3>Tại sao cần OCPP 2.0.1?</h3>
<p>OCPP 2.0.1 là phiên bản mới nhất, bổ sung hỗ trợ Plug & Charge (ISO 15118), quản lý năng lượng thông minh (Smart Charging), và bảo mật TLS mạnh hơn so với OCPP 1.6. Các dự án trạm sạc triển khai từ 2025 trở đi nên ưu tiên hỗ trợ OCPP 2.0.1 để đảm bảo tương thích lâu dài.</p>

<h3>Triển khai tại NLP Group</h3>
<p>NLP Group đang vận hành mạng lưới trạm sạc tại hệ thống <strong>CD Restop</strong> trên 34 tỉnh thành với CSMS tích hợp AI. Mỗi trụ sạc Grasen VP10/02 đều kết nối qua OCPP 2.0.1, tự động báo cáo về backend và nhận lệnh điều khiển trong vòng dưới 2 giây.</p>

<p>Liên hệ NLP Group để tìm hiểu gói CSMS phù hợp cho dự án trạm sạc của bạn: <a href="/contact">info@nlpgroup.vn</a></p>
</div>
HTML,

            // ─── Blog 2: EV Chargers comparison ──────────────────────────────
            2 => <<<HTML
<div class="nlp-blog-content">
<h2>Các Bộ Sạc EV Có Giống Nhau Không? Câu Trả Lời Là Không — Hướng dẫn So sánh Đầy đủ</h2>

<p>Một trong những câu hỏi phổ biến nhất khi triển khai hạ tầng sạc EV là: <em>"Bộ sạc nào cũng giống nhau cả thôi?"</em> Thực tế không phải vậy. Sự khác biệt giữa các loại sạc có thể quyết định hiệu quả đầu tư, trải nghiệm người dùng và chi phí vận hành dài hạn.</p>

<h3>1. Sạc AC vs Sạc DC — Khác nhau cơ bản</h3>
<table style="width:100%;border-collapse:collapse;margin:16px 0">
  <thead style="background:#f0f9f4">
    <tr><th style="padding:8px;border:1px solid #d1fae5;text-align:left">Tiêu chí</th><th style="padding:8px;border:1px solid #d1fae5">Sạc AC</th><th style="padding:8px;border:1px solid #d1fae5">Sạc DC</th></tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Công suất</td><td style="padding:8px;border:1px solid #e5e7eb;text-align:center">7 – 22 kW</td><td style="padding:8px;border:1px solid #e5e7eb;text-align:center">30 – 480 kW</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Bộ chuyển đổi</td><td style="padding:8px;border:1px solid #e5e7eb;text-align:center">Trong xe (OBC)</td><td style="padding:8px;border:1px solid #e5e7eb;text-align:center">Tích hợp trong trụ</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Thời gian sạc</td><td style="padding:8px;border:1px solid #e5e7eb;text-align:center">4–10 giờ</td><td style="padding:8px;border:1px solid #e5e7eb;text-align:center">20–60 phút</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Chi phí đầu tư</td><td style="padding:8px;border:1px solid #e5e7eb;text-align:center">Thấp</td><td style="padding:8px;border:1px solid #e5e7eb;text-align:center">Cao hơn</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Ứng dụng</td><td style="padding:8px;border:1px solid #e5e7eb;text-align:center">Nhà, văn phòng, khách sạn</td><td style="padding:8px;border:1px solid #e5e7eb;text-align:center">Trạm dừng chân, freeway</td></tr>
  </tbody>
</table>

<h3>2. Tiêu chuẩn đầu cắm tại Việt Nam</h3>
<ul>
  <li><strong>CCS2 (Combo 2):</strong> Chuẩn DC phổ biến nhất cho xe Châu Âu — BMW, Mercedes, Hyundai, Kia, VinFast (một số model)</li>
  <li><strong>CHAdeMO:</strong> Chuẩn Nhật Bản — Nissan Leaf, một số xe Mitsubishi. Dần bị thay thế bởi CCS2</li>
  <li><strong>GB/T:</strong> Tiêu chuẩn Trung Quốc — hầu hết xe EV xuất xứ Trung Quốc, bao gồm nhiều model VinFast</li>
  <li><strong>Type 2 (AC):</strong> Đầu cắm AC phổ biến tại Châu Âu, được sử dụng rộng rãi tại Việt Nam</li>
</ul>

<h3>3. Mức công suất — Chọn đúng cho từng ứng dụng</h3>
<p>NLP Group cung cấp dải sản phẩm đầy đủ từ <strong>7 kW AC</strong> cho đến <strong>480 kW DC</strong>:</p>
<ul>
  <li><strong>7–11 kW AC:</strong> Hộ gia đình, văn phòng, bãi đậu xe toà nhà</li>
  <li><strong>22 kW AC:</strong> Khách sạn, trung tâm thương mại, bãi xe công cộng</li>
  <li><strong>30–60 kW DC:</strong> Trạm dừng chân nhỏ, khu công nghiệp, đại lý ô tô</li>
  <li><strong>120–360 kW DC:</strong> Trạm dừng chân cao tốc, fleet charging lớn</li>
  <li><strong>480 kW DC (T480Q):</strong> Siêu trạm sạc — nạp đầy 80% trong 15 phút</li>
</ul>

<h3>Kết luận</h3>
<p>Lựa chọn sai loại sạc có thể dẫn đến chi phí điện cao, trải nghiệm người dùng kém và tỷ suất hoàn vốn thấp. Hãy để NLP Group tư vấn giải pháp phù hợp nhất với dự án của bạn. <a href="/contact">Liên hệ ngay →</a></p>
</div>
HTML,

            // ─── Blog 3: EV charging guide for businesses ─────────────────────
            3 => <<<HTML
<div class="nlp-blog-content">
<h2>Hướng dẫn Sạc EV cho Doanh nghiệp: 6 Bước Chọn Giải Pháp Phù Hợp</h2>

<p>Triển khai hạ tầng sạc EV cho doanh nghiệp không chỉ là "mua và lắp trụ sạc". Để tối ưu chi phí đầu tư và mang lại trải nghiệm tốt nhất, cần có một chiến lược rõ ràng từ khảo sát đến vận hành.</p>

<h3>Bước 1: Xác định nhu cầu sử dụng</h3>
<p>Trả lời các câu hỏi: Bao nhiêu xe cần sạc mỗi ngày? Thời gian đậu xe trung bình? Loại xe EV phổ biến nhất trong đội xe hoặc khách hàng?</p>
<p>Ví dụ: Một toà nhà văn phòng 500 nhân viên cần ~30–50 điểm sạc AC 11 kW để phục vụ xe cá nhân trong giờ làm việc.</p>

<h3>Bước 2: Khảo sát điện năng</h3>
<p>Yêu cầu điện thực tế phụ thuộc vào tổng công suất lắp đặt. NLP Group cung cấp dịch vụ <strong>khảo sát điện miễn phí</strong>: kiểm tra tủ điện hiện có, dung lượng máy biến áp, khả năng nâng cấp và tính toán chi phí hạ tầng điện.</p>

<h3>Bước 3: Chọn loại trụ sạc</h3>
<ul>
  <li><strong>Văn phòng / toà nhà:</strong> AC 7–22 kW — chi phí thấp, lắp đặt đơn giản</li>
  <li><strong>Khách sạn / resort:</strong> Kết hợp AC 22 kW (khách lưu trú) + DC 30–60 kW (khách vãng lai)</li>
  <li><strong>Trung tâm thương mại:</strong> DC 60–120 kW — tốc độ nhanh, tăng lưu lượng khách</li>
  <li><strong>Đội xe doanh nghiệp (fleet):</strong> AC 22 kW + Smart Load Balancing để sạc qua đêm với chi phí tối ưu</li>
</ul>

<h3>Bước 4: Tích hợp phần mềm quản lý</h3>
<p>CSMS của NLP Group cho phép quản lý toàn bộ mạng lưới sạc qua web/app, theo dõi doanh thu, cài đặt giá sạc theo giờ và xuất báo cáo tự động — không cần nhân sự quản lý tại chỗ.</p>

<h3>Bước 5: Mô hình kinh doanh</h3>
<p>Có ba mô hình phổ biến:</p>
<ol>
  <li><strong>Tự đầu tư — thu phí:</strong> ROI 3–5 năm, phù hợp trung tâm thương mại, khách sạn</li>
  <li><strong>Nhượng quyền NLP:</strong> NLP đầu tư thiết bị, bạn cung cấp mặt bằng, chia doanh thu</li>
  <li><strong>Dịch vụ cho nhân viên miễn phí:</strong> Phúc lợi doanh nghiệp, tăng sức hút tuyển dụng</li>
</ol>

<h3>Bước 6: Bảo trì &amp; vận hành</h3>
<p>NLP Group cung cấp gói bảo trì bao gồm giám sát NOC 24/7, bảo hành phần cứng 3 năm, phản hồi sự cố trong 4 giờ và cập nhật firmware định kỳ. <a href="/contact">Liên hệ ngay để nhận báo giá →</a></p>
</div>
HTML,

            // ─── Blog 4: Revenue from EV stations ────────────────────────────
            4 => <<<HTML
<div class="nlp-blog-content">
<h2>6 Cách Kiếm Doanh Thu từ Trạm Sạc EV Thương Mại</h2>

<p>Nhiều nhà đầu tư nhìn vào trạm sạc EV như một dịch vụ thuần túy. Thực tế, một trạm sạc được vận hành tốt có thể tạo ra <strong>nhiều luồng doanh thu đồng thời</strong>, nâng cao đáng kể tỷ suất hoàn vốn.</p>

<h3>1. Phí sạc trực tiếp (kWh hoặc phút)</h3>
<p>Nguồn thu chính: thu phí từ mỗi kWh điện tiêu thụ. Tại Việt Nam, mức phổ biến dao động <strong>3.500–6.000 VNĐ/kWh</strong> tùy thương hiệu và loại trụ. Giá mua điện từ EVN ~1.800–2.000 VNĐ/kWh → biên lợi nhuận 40–60%.</p>

<h3>2. Phí dừng đỗ (idle fee)</h3>
<p>Khi xe đã sạc đầy nhưng vẫn chiếm chỗ trụ sạc, trụ tự động tính thêm <em>idle fee</em> (ví dụ: 500 VNĐ/phút sau khi sạc xong). Cơ chế này kích thích xoay vòng sử dụng, tăng doanh thu giờ cao điểm.</p>

<h3>3. Cho thuê mặt bằng/quảng cáo trên màn hình</h3>
<p>Màn hình LCD tích hợp trên trụ DC (thường 10–15 inch) là không gian quảng cáo có giá trị, đặc biệt tại trạm dừng chân cao tốc. Các thương hiệu ô tô, dầu nhớt, bảo hiểm thường sẵn sàng trả phí đặt quảng cáo theo tháng.</p>

<h3>4. Dịch vụ đăng ký thành viên (subscription)</h3>
<p>Gói thành viên tháng/năm cung cấp mức giá ưu đãi (ví dụ: 3.200 VNĐ/kWh thay vì 4.500 VNĐ) và ưu tiên slot sạc. Thu trước dòng tiền, giảm biến động doanh thu.</p>

<h3>5. Doanh thu từ tiện ích đi kèm</h3>
<p>Thời gian sạc DC trung bình 30–45 phút là cơ hội vàng cho F&B, bán lẻ tiện lợi, rửa xe, hoặc kiosk ATM. Mô hình <em>combined charging + amenities</em> giúp tăng doanh thu tổng thể 20–30%.</p>

<h3>6. Chương trình nhượng quyền với NLP Group</h3>
<p>NLP Group cung cấp mô hình nhượng quyền toàn diện: NLP đầu tư thiết bị và CSMS, đối tác cung cấp mặt bằng và điện. Doanh thu chia theo tỷ lệ thỏa thuận — ROI bắt đầu từ tháng thứ 18.</p>

<p><strong>Kết luận:</strong> Một trạm sạc DC 120 kW tại vị trí đắc địa có thể đạt doanh thu <strong>50–150 triệu VNĐ/tháng</strong> khi khai thác đủ 6 nguồn thu trên. <a href="/contact">Tìm hiểu mô hình nhượng quyền NLP →</a></p>
</div>
HTML,

            // ─── Blog 5: Top DC Fast Charger manufacturers ────────────────────
            5 => <<<HTML
<div class="nlp-blog-content">
<h2>Top 10 Nhà Sản Xuất DC Fast Charger tại Trung Quốc (2026) — Đánh giá cho Thị trường Việt Nam</h2>

<p>Trung Quốc hiện chiếm hơn 65% sản lượng DC fast charger toàn cầu. Việc lựa chọn đúng nhà sản xuất ảnh hưởng trực tiếp đến độ tin cậy, chi phí bảo trì và khả năng hỗ trợ kỹ thuật tại Việt Nam.</p>

<h3>Tiêu chí đánh giá</h3>
<ul>
  <li>Hỗ trợ OCPP 2.0.1</li>
  <li>Bảo hành thiết bị (tối thiểu 2 năm)</li>
  <li>Có đại lý/kho phụ tùng tại Việt Nam hoặc Đông Nam Á</li>
  <li>Dải công suất (30–480 kW)</li>
  <li>Hỗ trợ CCS2 + GB/T dual-standard</li>
</ul>

<h3>Top 5 thương hiệu được NLP Group khuyến nghị</h3>
<ol>
  <li>
    <strong>Grasen (佳思特)</strong> — Đối tác chính thức của NLP Group. Dải sản phẩm VP10/02, R-Series, T480Q. OCPP 2.0.1, IP55, hỗ trợ kỹ thuật tiếng Việt. Phụ tùng có sẵn tại kho NLP.
  </li>
  <li>
    <strong>STAR Charge</strong> — Thương hiệu lớn, nhiều model từ 30–360 kW, hỗ trợ smart charging. Phổ biến tại các dự án hạ tầng lớn.
  </li>
  <li>
    <strong>SETEC Power</strong> — Chuyên DC fast charger công suất cao, nổi tiếng về độ bền. Phù hợp trạm dừng chân cao tốc.
  </li>
  <li>
    <strong>Increase (英可)</strong> — Giá cạnh tranh, hỗ trợ CCS2+CHAdeMO+GB/T triple-standard. Phù hợp thị trường mới nổi.
  </li>
  <li>
    <strong>BENY Electric</strong> — Mạnh về AC charger và DC charger thương mại tầm trung 30–120 kW, thiết kế nhỏ gọn.
  </li>
</ol>

<h3>Lưu ý khi nhập khẩu vào Việt Nam</h3>
<p>Thiết bị sạc EV nhập khẩu cần có <strong>chứng nhận hợp quy QCVN</strong> và kiểm định của Cục Đo lường Chất lượng. NLP Group hỗ trợ toàn bộ quy trình thông quan, kiểm định và cấp chứng nhận cho đối tác.</p>

<p><a href="/nlp-ev-products">Xem catalogue sản phẩm EV của NLP Group →</a></p>
</div>
HTML,

            // ─── Blog 6: EV Charging App ──────────────────────────────────────
            6 => <<<HTML
<div class="nlp-blog-content">
<h2>Hướng dẫn Sử Dụng App Sạc EV NLP: Tìm Trạm, Thanh Toán và Theo Dõi Phiên Sạc</h2>

<p>Ứng dụng di động là cầu nối giữa người lái xe và mạng lưới trạm sạc. App sạc EV của NLP Group cung cấp trải nghiệm end-to-end: từ tìm trạm gần nhất đến thanh toán và theo dõi lịch sử.</p>

<h3>Các tính năng chính</h3>

<h4>1. Tìm trạm sạc gần nhất</h4>
<p>Bản đồ tương tác hiển thị toàn bộ trạm NLP Group trên 34 tỉnh thành. Bộ lọc theo loại sạc (AC/DC), công suất, tiêu chuẩn đầu cắm (CCS2/GB/T), và trạng thái Available/Busy.</p>

<h4>2. Đặt chỗ sạc trước (reservation)</h4>
<p>Đặt trước slot sạc tối đa 30 phút trước khi đến. Trụ tự động hold cho xe của bạn, tránh tình trạng đến nơi trụ đầy.</p>

<h4>3. Khởi động phiên sạc</h4>
<p>Quét QR code trên trụ sạc hoặc chọn trụ từ app → nhấn "Bắt đầu sạc". Không cần thẻ RFID, không cần tiền mặt.</p>

<h4>4. Theo dõi thời gian thực</h4>
<p>App hiển thị: kWh đã sạc, % pin hiện tại (đối với xe hỗ trợ), thời gian còn lại dự kiến và chi phí tích lũy.</p>

<h4>5. Thanh toán đa phương thức</h4>
<p>Hỗ trợ ví điện tử (MoMo, ZaloPay, VNPay), VietQR, thẻ tín dụng VISA/Mastercard và ví NLP Points tích lũy.</p>

<h4>6. Lịch sử &amp; hóa đơn</h4>
<p>Tất cả phiên sạc lưu trữ đầy đủ, xuất hóa đơn điện tử PDF tích hợp VAT để thanh toán công tác phí.</p>

<h3>Chương trình tích điểm NLP Points</h3>
<p>Mỗi kWh sạc qua app nhận 1 NLP Point. Tích điểm đổi lấy kWh miễn phí, quà tặng hoặc ưu đãi từ đối tác. Thành viên Premium được ưu tiên đặt chỗ và giá sạc thấp hơn 15%.</p>

<p><a href="/nlp-ev-charging">Tìm hiểu thêm về mạng lưới trạm sạc NLP →</a></p>
</div>
HTML,

            // ─── Blog 7: T480Q 4-gun charger ─────────────────────────────────
            7 => <<<HTML
<div class="nlp-blog-content">
<h2>Trụ Sạc DC 4 Súng 480kW T480Q — Giải Pháp Siêu Trạm cho Lưu Lượng Cao</h2>

<p>T480Q là thế hệ trụ sạc DC công suất siêu cao của Grasen, được NLP Group triển khai tại các siêu trạm sạc trên cao tốc. Với 4 súng sạc đồng thời và tổng công suất lên đến <strong>480 kW</strong>, T480Q có thể sạc đầy 80% pin xe điện trong chưa đến 15 phút.</p>

<h3>Thông số kỹ thuật T480Q</h3>
<table style="width:100%;border-collapse:collapse;margin:16px 0">
  <thead style="background:#f0f9f4">
    <tr><th style="padding:8px;border:1px solid #d1fae5;text-align:left">Thông số</th><th style="padding:8px;border:1px solid #d1fae5">Giá trị</th></tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Tổng công suất</td><td style="padding:8px;border:1px solid #e5e7eb">480 kW (4 × 120 kW)</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Số súng sạc</td><td style="padding:8px;border:1px solid #e5e7eb">4 súng (2× CCS2 + 2× GB/T)</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Điện áp đầu ra</td><td style="padding:8px;border:1px solid #e5e7eb">200–1.000 V DC</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Dòng điện tối đa</td><td style="padding:8px;border:1px solid #e5e7eb">600 A / súng</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Hiệu suất</td><td style="padding:8px;border:1px solid #e5e7eb">≥ 95%</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Tiêu chuẩn bảo vệ</td><td style="padding:8px;border:1px solid #e5e7eb">IP55 / IK10</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Giao thức</td><td style="padding:8px;border:1px solid #e5e7eb">OCPP 2.0.1</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Màn hình</td><td style="padding:8px;border:1px solid #e5e7eb">15.6 inch LCD cảm ứng</td></tr>
    <tr><td style="padding:8px;border:1px solid #e5e7eb">Nhiệt độ hoạt động</td><td style="padding:8px;border:1px solid #e5e7eb">-30°C đến +55°C</td></tr>
  </tbody>
</table>

<h3>Công nghệ Smart Power Sharing</h3>
<p>T480Q ứng dụng thuật toán <strong>Dynamic Power Distribution</strong>: khi chỉ 1 xe kết nối, trụ tập trung toàn bộ 480 kW cho xe đó. Khi 4 xe kết nối đồng thời, hệ thống phân chia công suất thông minh dựa trên trạng thái pin và yêu cầu sạc của từng xe — không xe nào bị thiếu điện.</p>

<h3>Ứng dụng triển khai</h3>
<p>T480Q phù hợp nhất tại: trạm dừng chân cao tốc, depot đội xe thương mại, trung tâm phân phối logistics, sân bay và cảng biển. NLP Group đã triển khai T480Q tại hệ thống CD Restop, phục vụ hàng nghìn lượt sạc mỗi ngày.</p>

<p><a href="/nlp-ev-products">Xem thêm sản phẩm trụ sạc EV của NLP Group →</a></p>
</div>
HTML,

            // ─── Blog 8: DC 2-gun power sharing ──────────────────────────────
            8 => <<<HTML
<div class="nlp-blog-content">
<h2>Trụ Sạc DC 2 Súng: Cơ Chế Chia Sẻ Công Suất Hoạt Động Như Thế Nào?</h2>

<p>Khi mua trụ sạc DC 2 súng 120 kW, nhiều người hiểu nhầm rằng mỗi xe sẽ nhận được 120 kW. Thực tế, đây là tổng công suất của cả trụ — và cơ chế chia sẻ công suất (<em>power sharing</em>) quyết định mỗi xe thực sự nhận được bao nhiêu.</p>

<h3>Hai kiểu chia công suất phổ biến</h3>

<h4>1. Static Power Sharing (Chia đều cố định)</h4>
<p>Khi 2 xe kết nối, mỗi xe nhận đúng 60 kW (50/50). Đơn giản nhưng không tối ưu: nếu một xe chỉ cần 20 kW (pin gần đầy), 40 kW còn lại bị "lãng phí" thay vì dồn cho xe kia.</p>

<h4>2. Dynamic Power Sharing (Chia thông minh)</h4>
<p>Hệ thống liên tục theo dõi nhu cầu thực tế của từng xe qua giao thức ISO 15118 hoặc OCPP. Nếu xe A cần 100 kW và xe B chỉ cần 15 kW, trụ phân bổ 100/15 thay vì 60/60. Kết quả: xe A sạc nhanh hơn nhiều, tổng công suất không bị lãng phí.</p>

<h3>Ví dụ thực tế với Grasen VP10/02</h3>
<p>Trụ sạc Grasen VP10/02 (120 kW, 2 súng CCS2+GB/T) của NLP Group sử dụng Dynamic Power Sharing:</p>
<ul>
  <li>Xe 1 đang sạc ở SoC 10% → nhận 100 kW</li>
  <li>Xe 2 đang sạc ở SoC 75% → nhận 20 kW</li>
  <li>Tổng: 120 kW — tối đa hiệu quả</li>
</ul>
<p>Khi xe 2 đạt 80% và giảm tốc độ sạc xuống còn 10 kW, xe 1 tự động nhận thêm công suất lên 110 kW.</p>

<h3>Tại sao điều này quan trọng với nhà đầu tư trạm sạc?</h3>
<p>Dynamic Power Sharing tăng doanh thu trên mỗi trụ: cùng tổng công suất 120 kW nhưng phục vụ nhiều xe hơn, thời gian sạc ngắn hơn → xoay vòng nhanh hơn → thu nhập cao hơn mỗi ngày.</p>

<p><a href="/contact">Nhận tư vấn chọn trụ sạc phù hợp →</a></p>
</div>
HTML,

            // ─── Blog 9: CCS2 DC Fast Charger buyer guide ─────────────────────
            9 => <<<HTML
<div class="nlp-blog-content">
<h2>Trụ Sạc DC Nhanh CCS2 cho Trạm Thương Mại: 7 Điều Người Mua Cần Biết</h2>

<p>CCS2 (Combined Charging System 2) đang trở thành tiêu chuẩn DC phổ biến nhất tại Việt Nam, được hầu hết xe EV mới nhập khẩu hỗ trợ. Nếu bạn đang cân nhắc đầu tư trụ sạc DC cho trạm thương mại, đây là 7 điều quan trọng cần kiểm tra trước khi ký hợp đồng.</p>

<h3>1. Xác nhận tương thích CCS2 của xe trong khu vực</h3>
<p>Kiểm tra thực tế các hãng xe EV đang lưu hành tại địa điểm của bạn. Nhiều khu vực tại Việt Nam có tỷ lệ xe GB/T cao hơn CCS2 — mua trụ dual-standard CCS2+GB/T sẽ an toàn hơn.</p>

<h3>2. Công suất thực vs công suất danh nghĩa</h3>
<p>Một trụ ghi "120 kW" có thể chỉ đạt 90–100 kW ở điều kiện thực tế (nhiệt độ cao, điện lưới dao động). Yêu cầu nhà cung cấp cung cấp <strong>test report IEC 61851</strong> để xác nhận công suất thực.</p>

<h3>3. Hỗ trợ OCPP 2.0.1 (không chỉ OCPP 1.6)</h3>
<p>OCPP 1.6 sắp lỗi thời. Thiết bị chỉ hỗ trợ OCPP 1.6 sẽ không tương thích với các tính năng Smart Charging và Plug & Charge trong tương lai gần.</p>

<h3>4. Tiêu chuẩn bảo vệ IP &amp; chứng nhận</h3>
<p>Môi trường ngoài trời tại Việt Nam (mưa, nắng nóng, độ ẩm cao) đòi hỏi tối thiểu <strong>IP54</strong>. Các trạm dừng chân cao tốc nên chọn IP55/IP65. Chứng nhận CE, CB, TUV là bắt buộc.</p>

<h3>5. Bảo hành &amp; dịch vụ tại chỗ</h3>
<p>Trụ sạc hỏng mà không có kỹ thuật viên trong nước xử lý có thể mất 2–4 tuần downtime. Yêu cầu hợp đồng bảo hành rõ ràng: thời gian phản hồi tối đa, kho phụ tùng tại Việt Nam.</p>

<h3>6. Tích hợp thanh toán nội địa</h3>
<p>Khách hàng Việt Nam chủ yếu thanh toán qua MoMo, ZaloPay, VietQR. Trụ sạc cần hỗ trợ hoặc tích hợp được với các cổng thanh toán này — không phải mọi trụ nhập khẩu đều làm được.</p>

<h3>7. Hỗ trợ kỹ thuật tiếng Việt</h3>
<p>Khi trụ báo lỗi lúc 2 giờ sáng, bạn cần gọi được cho ai đó hiểu tiếng Việt và có thể hỗ trợ từ xa ngay lập tức. NLP Group cung cấp NOC 24/7 với đội kỹ thuật tiếng Việt cho toàn bộ hệ thống.</p>

<p><a href="/nlp-ev-products">Xem thông số chi tiết trụ sạc CCS2 của NLP Group →</a></p>
</div>
HTML,

            // ─── Blog 10: Canton Fair ─────────────────────────────────────────
            10 => <<<HTML
<div class="nlp-blog-content">
<h2>Gặp Gỡ Tập Đoàn NLP tại Canton Fair 140 — Tháng 10/2026</h2>

<p>Hội chợ Canton Fair lần thứ 140 (tháng 10/2026) là sự kiện thương mại quốc tế lớn nhất Trung Quốc, thu hút hơn 200.000 khách hàng từ 214 quốc gia và vùng lãnh thổ. Tập đoàn NLP tham dự với tư cách nhà nhập khẩu và phân phối độc quyền thiết bị sạc EV thương hiệu Grasen tại Việt Nam.</p>

<h3>NLP Group tại Canton Fair 140</h3>
<p>Tại gian hàng trong khu vực Thiết bị Điện &amp; Năng lượng, đội ngũ NLP Group trực tiếp gặp gỡ nhà sản xuất Grasen để:</p>
<ul>
  <li>Cập nhật roadmap sản phẩm 2026–2027: trụ sạc 800V architecture, Plug & Charge ISO 15118-20</li>
  <li>Đàm phán hợp đồng phân phối thế hệ tiếp theo cho thị trường Việt Nam và Đông Nam Á</li>
  <li>Trải nghiệm thực tế trụ sạc T480Q phiên bản mới với màn hình 21.5 inch và tích hợp quảng cáo DOOH</li>
  <li>Khảo sát giải pháp battery storage tích hợp trạm sạc (BESS + EVCS combined)</li>
</ul>

<h3>Xu hướng nổi bật tại Canton Fair 140</h3>
<p>Quan sát tại hội chợ cho thấy một số xu hướng sẽ định hình thị trường EV charging 2027:</p>
<ol>
  <li><strong>Megawatt Charging (MCS):</strong> Tiêu chuẩn sạc 1–4 MW cho xe tải điện hạng nặng</li>
  <li><strong>V2G (Vehicle-to-Grid):</strong> Xe điện như nguồn lưu trữ điện phân tán cho lưới điện</li>
  <li><strong>BESS + EVCS:</strong> Kết hợp pin lưu trữ BESS với trạm sạc để giảm công suất đỉnh và hóa đơn điện</li>
  <li><strong>AI-powered load forecasting:</strong> Dự báo nhu cầu sạc theo giờ để tối ưu mua điện giờ thấp điểm</li>
</ol>

<h3>Kế hoạch triển khai tại Việt Nam</h3>
<p>Dựa trên kết quả Canton Fair 140, NLP Group sẽ bổ sung vào danh mục sản phẩm 2027: trụ sạc 800V CCS2 (hỗ trợ xe BYD, Zeekr thế hệ mới) và giải pháp BESS-integrated charging cho khu công nghiệp. Liên hệ để được cập nhật sớm nhất khi sản phẩm về Việt Nam.</p>

<p><a href="/contact">Đăng ký nhận thông tin sản phẩm mới →</a></p>
</div>
HTML,

        ]; // end return
    }

    // =========================================================================
    // Tuyển Dụng page content
    // =========================================================================
    private function tuyenDungContent(): string
    {
        return <<<HTML
<div class="nlp-page-content">

<!-- Hero -->
<section class="nlp-hero" style="background:linear-gradient(135deg,#1a3c2e 0%,#2d6a4f 100%);color:#fff;padding:80px 0 60px">
  <div class="container text-center">
    <span style="display:inline-block;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);border-radius:20px;padding:6px 18px;font-size:13px;margin-bottom:20px;letter-spacing:1px">TUYỂN DỤNG 2025–2026</span>
    <h1 style="font-size:clamp(28px,5vw,48px);font-weight:700;margin-bottom:20px">Gia nhập Đội ngũ NLP Group</h1>
    <p style="font-size:18px;opacity:0.9;max-width:640px;margin:0 auto 32px">Cùng chúng tôi kiến tạo hệ sinh thái đổi mới bền vững — Năng lượng xanh · Xe điện · Công nghệ · AI · ESG</p>
    <a href="#open-positions" style="background:#fff;color:#1a3c2e;border-radius:8px;padding:14px 32px;font-weight:600;text-decoration:none;display:inline-block">Xem vị trí đang tuyển ↓</a>
  </div>
</section>

<!-- Why NLP -->
<section style="padding:64px 0;background:#fff">
  <div class="container">
    <h2 style="text-align:center;font-size:32px;font-weight:700;color:#1a3c2e;margin-bottom:12px">Tại sao chọn NLP Group?</h2>
    <p style="text-align:center;color:#57606a;max-width:580px;margin:0 auto 48px">Môi trường làm việc năng động, đồng đội tài năng và cơ hội phát triển thật sự</p>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px">
      <div style="background:#f0f9f4;border-radius:12px;padding:28px 24px;text-align:center">
        <div style="font-size:36px;margin-bottom:12px">🌱</div>
        <h3 style="font-size:16px;font-weight:700;color:#1a3c2e;margin-bottom:8px">Sứ mệnh có ý nghĩa</h3>
        <p style="font-size:14px;color:#57606a;margin:0">Mỗi sản phẩm và dịch vụ đều hướng đến mục tiêu giảm phát thải và phát triển bền vững cho Việt Nam</p>
      </div>
      <div style="background:#f0f9f4;border-radius:12px;padding:28px 24px;text-align:center">
        <div style="font-size:36px;margin-bottom:12px">🚀</div>
        <h3 style="font-size:16px;font-weight:700;color:#1a3c2e;margin-bottom:8px">Tăng trưởng nhanh</h3>
        <p style="font-size:14px;color:#57606a;margin:0">Tập đoàn tăng trưởng 3× trong 2 năm, mở ra nhiều cơ hội thăng tiến cho nhân sự cốt lõi</p>
      </div>
      <div style="background:#f0f9f4;border-radius:12px;padding:28px 24px;text-align:center">
        <div style="font-size:36px;margin-bottom:12px">💡</div>
        <h3 style="font-size:16px;font-weight:700;color:#1a3c2e;margin-bottom:8px">Công nghệ tiên tiến</h3>
        <p style="font-size:14px;color:#57606a;margin:0">Làm việc với AI, IoT, OCPP 2.0.1, phần mềm CSMS và hạ tầng cloud hiện đại</p>
      </div>
      <div style="background:#f0f9f4;border-radius:12px;padding:28px 24px;text-align:center">
        <div style="font-size:36px;margin-bottom:12px">🏆</div>
        <h3 style="font-size:16px;font-weight:700;color:#1a3c2e;margin-bottom:8px">Đãi ngộ cạnh tranh</h3>
        <p style="font-size:14px;color:#57606a;margin:0">Lương thị trường + thưởng hiệu suất + ESOP (cổ phần dành cho nhân sự cấp cao)</p>
      </div>
    </div>
  </div>
</section>

<!-- Open Positions -->
<section id="open-positions" style="padding:64px 0;background:#f7f8fa">
  <div class="container">
    <h2 style="text-align:center;font-size:32px;font-weight:700;color:#1a3c2e;margin-bottom:12px">Vị trí đang tuyển</h2>
    <p style="text-align:center;color:#57606a;max-width:560px;margin:0 auto 48px">Cập nhật tháng 10/2026 · 12 vị trí mở</p>

    <!-- Category: Technology & Engineering -->
    <h3 style="font-size:18px;font-weight:700;color:#2d6a4f;border-bottom:2px solid #d1fae5;padding-bottom:8px;margin:0 0 20px">⚙️ Công nghệ & Kỹ thuật</h3>
    <div style="display:grid;gap:16px;margin-bottom:40px">

      <div style="background:#fff;border-radius:10px;padding:24px;border:1px solid #e5e7eb">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px">
          <div>
            <h4 style="font-size:17px;font-weight:700;color:#1f2328;margin:0 0 6px">Kỹ sư Phần mềm Backend (Laravel/PHP)</h4>
            <p style="color:#57606a;font-size:14px;margin:0">NLP Technology · TP. Hồ Chí Minh · Toàn thời gian</p>
          </div>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <span style="background:#dcfce7;color:#166534;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600">2–4 năm kinh nghiệm</span>
            <span style="background:#dbeafe;color:#1e40af;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600">Thương lượng</span>
          </div>
        </div>
        <ul style="margin:12px 0 0;padding-left:20px;color:#57606a;font-size:14px">
          <li>Phát triển và bảo trì hệ thống CSMS (Charge Station Management System)</li>
          <li>Tích hợp OCPP 2.0.1 với trụ sạc EV thực tế</li>
          <li>Laravel 10+, MySQL, Redis, REST API, WebSocket</li>
          <li>Thưởng: Tham gia dự án AI integration, cơ hội trở thành Tech Lead</li>
        </ul>
        <a href="/contact?subject=Apply%3A+Backend+Engineer" style="display:inline-block;margin-top:14px;background:#1a3c2e;color:#fff;border-radius:6px;padding:8px 20px;font-size:13px;font-weight:600;text-decoration:none">Ứng tuyển ngay →</a>
      </div>

      <div style="background:#fff;border-radius:10px;padding:24px;border:1px solid #e5e7eb">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px">
          <div>
            <h4 style="font-size:17px;font-weight:700;color:#1f2328;margin:0 0 6px">Kỹ sư AI/ML (NLP AI &amp; Digital)</h4>
            <p style="color:#57606a;font-size:14px;margin:0">NLP AI · TP. Hồ Chí Minh · Toàn thời gian / Remote</p>
          </div>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <span style="background:#dcfce7;color:#166534;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600">2–5 năm kinh nghiệm</span>
            <span style="background:#dbeafe;color:#1e40af;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600">Thương lượng</span>
          </div>
        </div>
        <ul style="margin:12px 0 0;padding-left:20px;color:#57606a;font-size:14px">
          <li>Xây dựng AI Agent và workflow tự động cho khách hàng doanh nghiệp</li>
          <li>Python, LangChain/LlamaIndex, RAG, OpenAI/Claude API</li>
          <li>Triển khai AI trên hạ tầng cloud (AWS/GCP)</li>
          <li>Kinh nghiệm với Vector DB (Pinecone, Weaviate) là lợi thế</li>
        </ul>
        <a href="/contact?subject=Apply%3A+AI+Engineer" style="display:inline-block;margin-top:14px;background:#1a3c2e;color:#fff;border-radius:6px;padding:8px 20px;font-size:13px;font-weight:600;text-decoration:none">Ứng tuyển ngay →</a>
      </div>

      <div style="background:#fff;border-radius:10px;padding:24px;border:1px solid #e5e7eb">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px">
          <div>
            <h4 style="font-size:17px;font-weight:700;color:#1f2328;margin:0 0 6px">Kỹ sư Điện / Kỹ sư Năng lượng Tái tạo</h4>
            <p style="color:#57606a;font-size:14px;margin:0">NLP Energy · TP. Hồ Chí Minh + công trình toàn quốc</p>
          </div>
          <span style="background:#dcfce7;color:#166534;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600">3–7 năm kinh nghiệm</span>
        </div>
        <ul style="margin:12px 0 0;padding-left:20px;color:#57606a;font-size:14px">
          <li>Thiết kế, giám sát thi công hệ thống điện mặt trời áp mái (50 kW – 5 MW)</li>
          <li>Tính toán kỹ thuật, lập hồ sơ đấu thầu EPC</li>
          <li>Kinh nghiệm với hệ thống lưu trữ điện BESS là lợi thế lớn</li>
          <li>Bằng kỹ sư điện, điện tử hoặc năng lượng tái tạo</li>
        </ul>
        <a href="/contact?subject=Apply%3A+Energy+Engineer" style="display:inline-block;margin-top:14px;background:#1a3c2e;color:#fff;border-radius:6px;padding:8px 20px;font-size:13px;font-weight:600;text-decoration:none">Ứng tuyển ngay →</a>
      </div>

    </div>

    <!-- Category: Business Development -->
    <h3 style="font-size:18px;font-weight:700;color:#2d6a4f;border-bottom:2px solid #d1fae5;padding-bottom:8px;margin:0 0 20px">📈 Kinh doanh & Phát triển thị trường</h3>
    <div style="display:grid;gap:16px;margin-bottom:40px">

      <div style="background:#fff;border-radius:10px;padding:24px;border:1px solid #e5e7eb">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px">
          <div>
            <h4 style="font-size:17px;font-weight:700;color:#1f2328;margin:0 0 6px">Business Development Manager — EV Charging</h4>
            <p style="color:#57606a;font-size:14px;margin:0">NLP EV Charging · Toàn quốc · Toàn thời gian</p>
          </div>
          <span style="background:#fef3c7;color:#92400e;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600">Ưu tiên tuyển gấp</span>
        </div>
        <ul style="margin:12px 0 0;padding-left:20px;color:#57606a;font-size:14px">
          <li>Phát triển mạng lưới đối tác nhượng quyền trạm sạc EV trên toàn quốc</li>
          <li>Thương thảo hợp đồng địa điểm với TTTM, khách sạn, trạm dừng chân</li>
          <li>Kinh nghiệm B2B, hiểu biết về hạ tầng EV hoặc năng lượng là lợi thế</li>
          <li>Thu nhập: lương cứng + hoa hồng theo dự án (không giới hạn)</li>
        </ul>
        <a href="/contact?subject=Apply%3A+BD+Manager+EV" style="display:inline-block;margin-top:14px;background:#1a3c2e;color:#fff;border-radius:6px;padding:8px 20px;font-size:13px;font-weight:600;text-decoration:none">Ứng tuyển ngay →</a>
      </div>

      <div style="background:#fff;border-radius:10px;padding:24px;border:1px solid #e5e7eb">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px">
          <div>
            <h4 style="font-size:17px;font-weight:700;color:#1f2328;margin:0 0 6px">Chuyên viên Tư vấn Điện Mặt Trời (B2B)</h4>
            <p style="color:#57606a;font-size:14px;margin:0">NLP Energy · TP. Hồ Chí Minh / Hà Nội · Toàn thời gian</p>
          </div>
          <span style="background:#dcfce7;color:#166534;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600">1–3 năm kinh nghiệm</span>
        </div>
        <ul style="margin:12px 0 0;padding-left:20px;color:#57606a;font-size:14px">
          <li>Tư vấn và bán hệ thống điện mặt trời cho doanh nghiệp, nhà máy, khu công nghiệp</li>
          <li>Lập báo giá, phân tích ROI, hỗ trợ khách hàng từ khảo sát đến nghiệm thu</li>
          <li>Kiến thức điện cơ bản hoặc đã bán hàng kỹ thuật</li>
          <li>Thu nhập: 12–25 triệu/tháng + hoa hồng dự án</li>
        </ul>
        <a href="/contact?subject=Apply%3A+Solar+Consultant" style="display:inline-block;margin-top:14px;background:#1a3c2e;color:#fff;border-radius:6px;padding:8px 20px;font-size:13px;font-weight:600;text-decoration:none">Ứng tuyển ngay →</a>
      </div>

      <div style="background:#fff;border-radius:10px;padding:24px;border:1px solid #e5e7eb">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px">
          <div>
            <h4 style="font-size:17px;font-weight:700;color:#1f2328;margin:0 0 6px">Chuyên viên ESG &amp; Carbon Credits</h4>
            <p style="color:#57606a;font-size:14px;margin:0">NLP ESG · TP. Hồ Chí Minh · Toàn thời gian</p>
          </div>
          <span style="background:#dcfce7;color:#166534;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600">1–3 năm kinh nghiệm</span>
        </div>
        <ul style="margin:12px 0 0;padding-left:20px;color:#57606a;font-size:14px">
          <li>Tư vấn lập báo cáo ESG, kiểm kê phát thải GHG cho khách hàng doanh nghiệp</li>
          <li>Phát triển dự án tín chỉ carbon theo tiêu chuẩn VCS/Gold Standard</li>
          <li>Kiến thức về Net Zero, CBAM, thị trường carbon là bắt buộc</li>
          <li>Chứng chỉ GHG Protocol hoặc ISO 14064 là lợi thế</li>
        </ul>
        <a href="/contact?subject=Apply%3A+ESG+Specialist" style="display:inline-block;margin-top:14px;background:#1a3c2e;color:#fff;border-radius:6px;padding:8px 20px;font-size:13px;font-weight:600;text-decoration:none">Ứng tuyển ngay →</a>
      </div>

    </div>

    <!-- Category: Operations & Support -->
    <h3 style="font-size:18px;font-weight:700;color:#2d6a4f;border-bottom:2px solid #d1fae5;padding-bottom:8px;margin:0 0 20px">🛠️ Vận hành & Hỗ trợ</h3>
    <div style="display:grid;gap:16px;margin-bottom:40px">

      <div style="background:#fff;border-radius:10px;padding:24px;border:1px solid #e5e7eb">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px">
          <div>
            <h4 style="font-size:17px;font-weight:700;color:#1f2328;margin:0 0 6px">Kỹ thuật viên Điện / Lắp đặt Trạm Sạc EV</h4>
            <p style="color:#57606a;font-size:14px;margin:0">NLP EV Charging · Toàn quốc · Toàn thời gian</p>
          </div>
          <span style="background:#dcfce7;color:#166534;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600">1–3 năm kinh nghiệm</span>
        </div>
        <ul style="margin:12px 0 0;padding-left:20px;color:#57606a;font-size:14px">
          <li>Lắp đặt, vận hành và bảo trì trụ sạc EV AC/DC tại các địa điểm đối tác</li>
          <li>Chẩn đoán và xử lý sự cố thiết bị điện và trụ sạc</li>
          <li>Có bằng điện trung cấp/cao đẳng trở lên, chứng chỉ an toàn điện</li>
          <li>Sẵn sàng di công tác theo yêu cầu</li>
        </ul>
        <a href="/contact?subject=Apply%3A+EV+Technician" style="display:inline-block;margin-top:14px;background:#1a3c2e;color:#fff;border-radius:6px;padding:8px 20px;font-size:13px;font-weight:600;text-decoration:none">Ứng tuyển ngay →</a>
      </div>

      <div style="background:#fff;border-radius:10px;padding:24px;border:1px solid #e5e7eb">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px">
          <div>
            <h4 style="font-size:17px;font-weight:700;color:#1f2328;margin:0 0 6px">Chuyên viên Marketing &amp; Content (Xanh / ESG)</h4>
            <p style="color:#57606a;font-size:14px;margin:0">NLP Group · TP. Hồ Chí Minh · Toàn thời gian</p>
          </div>
          <span style="background:#dcfce7;color:#166534;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600">1–2 năm kinh nghiệm</span>
        </div>
        <ul style="margin:12px 0 0;padding-left:20px;color:#57606a;font-size:14px">
          <li>Sản xuất nội dung (bài viết, video short, infographic) về năng lượng, EV, ESG</li>
          <li>Quản lý fanpage, website blog, email marketing</li>
          <li>SEO cơ bản, Google Analytics, Meta Ads</li>
          <li>Đam mê lĩnh vực xanh, bền vững; viết tốt tiếng Việt và tiếng Anh</li>
        </ul>
        <a href="/contact?subject=Apply%3A+Marketing+Content" style="display:inline-block;margin-top:14px;background:#1a3c2e;color:#fff;border-radius:6px;padding:8px 20px;font-size:13px;font-weight:600;text-decoration:none">Ứng tuyển ngay →</a>
      </div>

    </div>

  </div>
</section>

<!-- How to apply -->
<section style="padding:64px 0;background:#1a3c2e;color:#fff">
  <div class="container" style="max-width:760px;margin:0 auto;text-align:center">
    <h2 style="font-size:30px;font-weight:700;margin-bottom:16px">Quy trình ứng tuyển</h2>
    <p style="opacity:0.85;margin-bottom:40px">Gửi CV → Phỏng vấn nhanh → Phỏng vấn kỹ thuật → Offer → Onboard</p>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:20px;text-align:left">
      <div style="background:rgba(255,255,255,0.1);border-radius:10px;padding:20px">
        <div style="font-size:28px;font-weight:700;color:#6ee7b7;margin-bottom:6px">01</div>
        <p style="font-size:14px;margin:0;opacity:0.9">Gửi CV qua link ứng tuyển hoặc email <strong>tuyen-dung@nlpgroup.vn</strong></p>
      </div>
      <div style="background:rgba(255,255,255,0.1);border-radius:10px;padding:20px">
        <div style="font-size:28px;font-weight:700;color:#6ee7b7;margin-bottom:6px">02</div>
        <p style="font-size:14px;margin:0;opacity:0.9">HR liên hệ trong <strong>48 giờ</strong> làm việc để xác nhận và hẹn lịch</p>
      </div>
      <div style="background:rgba(255,255,255,0.1);border-radius:10px;padding:20px">
        <div style="font-size:28px;font-weight:700;color:#6ee7b7;margin-bottom:6px">03</div>
        <p style="font-size:14px;margin:0;opacity:0.9">Phỏng vấn 2 vòng (30–60 phút mỗi vòng, online hoặc tại văn phòng)</p>
      </div>
      <div style="background:rgba(255,255,255,0.1);border-radius:10px;padding:20px">
        <div style="font-size:28px;font-weight:700;color:#6ee7b7;margin-bottom:6px">04</div>
        <p style="font-size:14px;margin:0;opacity:0.9">Nhận offer và onboard trong <strong>2–4 tuần</strong></p>
      </div>
    </div>
    <a href="/contact" style="display:inline-block;margin-top:40px;background:#6ee7b7;color:#1a3c2e;border-radius:8px;padding:14px 36px;font-weight:700;text-decoration:none;font-size:16px">Gửi hồ sơ ứng tuyển →</a>
    <p style="margin-top:16px;opacity:0.7;font-size:13px">Hoặc email trực tiếp: tuyen-dung@nlpgroup.vn</p>
  </div>
</section>

</div>
HTML;
    }
}
