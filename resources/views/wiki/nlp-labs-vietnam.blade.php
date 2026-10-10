@extends(template().'layouts.app')
@section('title', 'NLP Labs Vietnam — Wiki')

@section('content')
<div class="wiki-page">

    {{-- ══════════════════════════════════════════════
         WIKI HEADER BANNER
    ══════════════════════════════════════════════ --}}
    <div class="wiki-top-banner">
        <div class="container">
            <nav class="wiki-breadcrumb" aria-label="breadcrumb">
                <ol>
                    <li><a href="{{ route('page') }}">{{ basicControl()->site_title }}</a></li>
                    <li>›</li>
                    <li><a href="#">Wiki</a></li>
                    <li>›</li>
                    <li class="active">NLP Labs Vietnam</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         MAIN LAYOUT: sidebar + article
    ══════════════════════════════════════════════ --}}
    <div class="container wiki-container">
        <div class="wiki-layout">

            {{-- ───── LEFT SIDEBAR / TOC ───── --}}
            <aside class="wiki-sidebar" id="wikiSidebar">
                <div class="wiki-toc-box">
                    <div class="toc-header">
                        <span class="toc-icon">☰</span>
                        <strong>@lang('Mục lục')</strong>
                        <button class="toc-toggle" id="tocToggle" aria-label="toggle toc">▲</button>
                    </div>
                    <nav class="toc-nav" id="tocNav">
                        <ol>
                            <li><a href="#gioi-thieu">1 &nbsp;Giới thiệu</a></li>
                            <li><a href="#lich-su">2 &nbsp;Lịch sử hình thành</a>
                                <ol>
                                    <li><a href="#thanh-lap">2.1 &nbsp;Thành lập</a></li>
                                    <li><a href="#mo-rong">2.2 &nbsp;Mở rộng</a></li>
                                </ol>
                            </li>
                            <li><a href="#linh-vuc">3 &nbsp;Lĩnh vực nghiên cứu</a>
                                <ol>
                                    <li><a href="#quang-dien">3.1 &nbsp;Công nghệ quang điện</a></li>
                                    <li><a href="#bess">3.2 &nbsp;Lưu trữ năng lượng (BESS)</a></li>
                                    <li><a href="#epc">3.3 &nbsp;Tổng thầu EPC</a></li>
                                    <li><a href="#om">3.4 &nbsp;Vận hành & Bảo dưỡng (O&M)</a></li>
                                </ol>
                            </li>
                            <li><a href="#chung-nhan">4 &nbsp;Chứng nhận & Giấy phép</a></li>
                            <li><a href="#doi-tac">5 &nbsp;Đối tác & Nhà cung cấp</a></li>
                            <li><a href="#du-an">6 &nbsp;Dự án tiêu biểu</a></li>
                            <li><a href="#doi-ngu">7 &nbsp;Đội ngũ lãnh đạo</a></li>
                            <li><a href="#lien-he">8 &nbsp;Liên hệ</a></li>
                            <li><a href="#xem-them">9 &nbsp;Xem thêm</a></li>
                            <li><a href="#tham-khao">10 Tham khảo</a></li>
                        </ol>
                    </nav>
                </div>

                {{-- Infobox --}}
                <div class="wiki-infobox">
                    <div class="infobox-header">NLP Labs Vietnam</div>
                    <div class="infobox-img-placeholder">
                        <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"
                             alt="NLP Group Logo" style="max-width:120px;margin:12px auto;display:block;">
                    </div>
                    <table class="infobox-table">
                        <tr><th>Loại hình</th><td>Tư nhân — R&D / EPC</td></tr>
                        <tr><th>Thành lập</th><td>2018, Việt Nam</td></tr>
                        <tr><th>Trụ sở</th><td>Hồ Chí Minh, Việt Nam</td></tr>
                        <tr><th>Lĩnh vực</th><td>Năng lượng tái tạo</td></tr>
                        <tr><th>Website</th><td><a href="https://nlpgroup.vn" target="_blank" rel="noopener">nlpgroup.vn</a></td></tr>
                        <tr><th>Tiêu chuẩn</th><td>ISO 9001 · ISO 14001 · ISO 45001</td></tr>
                        <tr><th>Giấy phép</th><td>Điện lực · Xây dựng</td></tr>
                    </table>
                </div>
            </aside>

            {{-- ───── MAIN ARTICLE ───── --}}
            <article class="wiki-article">

                <div class="wiki-article-header">
                    <h1 class="wiki-title">NLP Labs Vietnam</h1>
                    <div class="wiki-subtitle">Bách khoa toàn thư mở về NLP Group — Tổng thầu EPC & Nhà phân phối thiết bị điện mặt trời</div>
                    <div class="wiki-meta-bar">
                        <span class="wiki-meta-item"><i class="fa-regular fa-calendar"></i> Cập nhật: {{ date('d/m/Y') }}</span>
                        <span class="wiki-meta-item"><i class="fa-regular fa-eye"></i> Bài viết xác minh</span>
                        <span class="wiki-meta-item wiki-badge-verified">✔ Đã xác thực</span>
                    </div>
                    <hr class="wiki-divider">
                </div>

                {{-- 1. Giới thiệu --}}
                <section id="gioi-thieu" class="wiki-section">
                    <p class="wiki-lead">
                        <strong>NLP Labs Vietnam</strong> (tên đầy đủ: <em>NLP Group Vietnam Co., Ltd</em>) là đơn vị chuyên nghiên cứu,
                        phát triển và triển khai các giải pháp <strong>năng lượng tái tạo</strong> tại Việt Nam, đặc biệt là lĩnh vực
                        <strong>điện mặt trời quang điện (Solar PV)</strong>. Công ty hoạt động theo mô hình kép: vừa là <em>nhà phân phối
                        chính thức</em> thiết bị solar của các thương hiệu quốc tế Tier 1, vừa là <em>tổng thầu EPC</em>
                        (Engineering, Procurement, Construction) cho các dự án điện mặt trời dân dụng và công nghiệp (C&I).
                    </p>
                    <p>
                        Được chứng nhận <strong>ISO 9001:2015</strong>, <strong>ISO 14001:2015</strong>, <strong>ISO 45001:2018</strong>
                        cùng đầy đủ <em>giấy phép hoạt động điện lực</em> và <em>giấy phép xây dựng</em>, NLP Labs Vietnam đặt tiêu
                        chuẩn kỹ thuật và an toàn lên hàng đầu trong mọi dự án triển khai.
                    </p>
                </section>

                {{-- 2. Lịch sử --}}
                <section id="lich-su" class="wiki-section">
                    <h2 class="wiki-h2"><span class="wiki-h2-num">2</span> Lịch sử hình thành</h2>

                    <h3 id="thanh-lap" class="wiki-h3">2.1 Thành lập</h3>
                    <p>
                        NLP Group được thành lập vào năm <strong>2018</strong> tại Thành phố Hồ Chí Minh trong bối cảnh
                        Chính phủ Việt Nam ban hành <em>Quyết định 11/2017/QĐ-TTg</em> về cơ chế khuyến khích phát triển
                        điện mặt trời, tạo ra làn sóng đầu tư mạnh mẽ vào năng lượng tái tạo.
                        Nhận thấy khoảng trống về nguồn cung thiết bị chính hãng và năng lực thi công đạt chuẩn,
                        các sáng lập viên đã xây dựng NLP với định hướng trở thành đầu mối tin cậy trên toàn chuỗi giá trị solar.
                    </p>

                    <h3 id="mo-rong" class="wiki-h3">2.2 Mở rộng</h3>
                    <p>
                        Giai đoạn 2019–2022, NLP mở rộng hoạt động từ thị trường dân dụng sang phân khúc
                        <strong>công nghiệp & thương mại (C&I)</strong>, phục vụ nhà máy, khu công nghiệp và trung tâm thương mại.
                        Đến năm 2023, NLP hoàn thiện hệ thống quản lý chất lượng và nhận chứng nhận ISO ba tiêu chuẩn,
                        đồng thời ra mắt bộ phận <em>O&M (Operation & Maintenance)</em> chuyên trách vận hành bảo dưỡng
                        hệ thống sau lắp đặt.
                    </p>
                </section>

                {{-- 3. Lĩnh vực --}}
                <section id="linh-vuc" class="wiki-section">
                    <h2 class="wiki-h2"><span class="wiki-h2-num">3</span> Lĩnh vực nghiên cứu & hoạt động</h2>

                    <h3 id="quang-dien" class="wiki-h3">3.1 Công nghệ quang điện (Solar PV)</h3>
                    <p>
                        NLP Labs nghiên cứu và phân phối <strong>tấm pin mặt trời Tier 1</strong> đạt tiêu chuẩn IEC 61215,
                        bao gồm công nghệ Mono PERC, TOPCon và HJT. Danh mục sản phẩm bao gồm:
                    </p>
                    <div class="wiki-table-wrapper">
                        <table class="wiki-table">
                            <thead>
                                <tr><th>Công nghệ</th><th>Hiệu suất tiêu biểu</th><th>Ứng dụng chính</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Mono PERC</td><td>20–22%</td><td>Dân dụng, áp mái nhỏ</td></tr>
                                <tr><td>TOPCon</td><td>22–24%</td><td>C&I, khu công nghiệp</td></tr>
                                <tr><td>HJT (Heterojunction)</td><td>24–26%</td><td>Dự án lớn, utility-scale</td></tr>
                                <tr><td>Bifacial</td><td>+5–10% (rear gain)</td><td>Hệ thống ground-mount</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <h3 id="bess" class="wiki-h3">3.2 Lưu trữ năng lượng (BESS)</h3>
                    <p>
                        Hệ thống <strong>Battery Energy Storage System (BESS)</strong> được NLP tích hợp song song với
                        hệ thống PV để tối ưu hóa tự tiêu thụ và cung cấp điện dự phòng. NLP phân phối pin lithium-iron
                        phosphate (LiFePO₄) với chu kỳ tuổi thọ &gt; 6,000 cycles, phù hợp với khí hậu nhiệt đới Việt Nam.
                    </p>

                    <h3 id="epc" class="wiki-h3">3.3 Tổng thầu EPC (Engineering, Procurement, Construction)</h3>
                    <p>
                        Mô hình EPC trọn gói của NLP bao gồm:
                    </p>
                    <ul class="wiki-list">
                        <li><strong>Engineering (Thiết kế kỹ thuật):</strong> Khảo sát hiện trường, thiết kế hệ thống, mô phỏng phát điện (PVsyst), tính toán kết cấu mái.</li>
                        <li><strong>Procurement (Mua sắm):</strong> Cung cấp thiết bị chính hãng — tấm pin, inverter, mounting, cáp DC/AC, tủ điện, đồng hồ đo đếm.</li>
                        <li><strong>Construction (Thi công):</strong> Lắp đặt, kéo cáp, đấu nối điện, commissioning, thủ tục đăng ký đấu nối EVN.</li>
                    </ul>

                    <h3 id="om" class="wiki-h3">3.4 Vận hành & Bảo dưỡng (O&M)</h3>
                    <p>
                        Dịch vụ O&M bao gồm giám sát hiệu suất 24/7 qua hệ thống SCADA/monitoring cloud,
                        bảo dưỡng định kỳ (vệ sinh tấm pin, kiểm tra điện trở cách điện, thermography IR),
                        và bảo hành hệ thống trọn gói theo hợp đồng dài hạn.
                    </p>
                </section>

                {{-- 4. Chứng nhận --}}
                <section id="chung-nhan" class="wiki-section">
                    <h2 class="wiki-h2"><span class="wiki-h2-num">4</span> Chứng nhận & Giấy phép</h2>
                    <div class="wiki-cert-grid">
                        <div class="wiki-cert-card">
                            <div class="cert-icon">🏆</div>
                            <div class="cert-name">ISO 9001:2015</div>
                            <div class="cert-desc">Hệ thống quản lý chất lượng</div>
                        </div>
                        <div class="wiki-cert-card">
                            <div class="cert-icon">🌿</div>
                            <div class="cert-name">ISO 14001:2015</div>
                            <div class="cert-desc">Hệ thống quản lý môi trường</div>
                        </div>
                        <div class="wiki-cert-card">
                            <div class="cert-icon">⛑️</div>
                            <div class="cert-name">ISO 45001:2018</div>
                            <div class="cert-desc">An toàn & sức khỏe nghề nghiệp</div>
                        </div>
                        <div class="wiki-cert-card">
                            <div class="cert-icon">⚡</div>
                            <div class="cert-name">Giấy phép Điện lực</div>
                            <div class="cert-desc">Hoạt động điện lực cấp Bộ Công Thương</div>
                        </div>
                        <div class="wiki-cert-card">
                            <div class="cert-icon">🏗️</div>
                            <div class="cert-name">Giấy phép Xây dựng</div>
                            <div class="cert-desc">Nhà thầu xây dựng đủ điều kiện</div>
                        </div>
                        <div class="wiki-cert-card">
                            <div class="cert-icon">✅</div>
                            <div class="cert-name">Tier 1 Distributor</div>
                            <div class="cert-desc">Nhà phân phối chính thức thương hiệu quốc tế</div>
                        </div>
                    </div>
                </section>

                {{-- 5. Đối tác --}}
                <section id="doi-tac" class="wiki-section">
                    <h2 class="wiki-h2"><span class="wiki-h2-num">5</span> Đối tác & Nhà cung cấp</h2>
                    <p>
                        NLP Labs duy trì quan hệ đối tác chính thức với các nhà sản xuất thiết bị solar hàng đầu thế giới,
                        đảm bảo nguồn cung ổn định và chính sách bảo hành nhà sản xuất đầy đủ:
                    </p>
                    <div class="wiki-table-wrapper">
                        <table class="wiki-table">
                            <thead>
                                <tr><th>Loại thiết bị</th><th>Nhà sản xuất / Thương hiệu</th><th>Xuất xứ</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Tấm pin mặt trời</td><td>LONGi, JA Solar, Trina Solar, Canadian Solar</td><td>Trung Quốc / Quốc tế</td></tr>
                                <tr><td>Biến tần (Inverter)</td><td>Sungrow, Huawei, Growatt, SMA, Fronius</td><td>Trung Quốc / Đức / Áo</td></tr>
                                <tr><td>Pin lưu trữ (BESS)</td><td>BYD, CATL, Pylontech, Dyness</td><td>Trung Quốc</td></tr>
                                <tr><td>Kết cấu lắp đặt</td><td>K2 Systems, IronRidge, Schletter</td><td>Đức / Mỹ</td></tr>
                                <tr><td>Đồng hồ & giám sát</td><td>Fronius, Sungrow Logger, Huawei FusionSolar</td><td>Áo / Trung Quốc</td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- 6. Dự án --}}
                <section id="du-an" class="wiki-section">
                    <h2 class="wiki-h2"><span class="wiki-h2-num">6</span> Dự án tiêu biểu</h2>
                    <div class="wiki-project-list">
                        <div class="wiki-project-item">
                            <div class="project-badge project-badge-ci">C&I</div>
                            <div class="project-info">
                                <strong>Hệ thống điện mặt trời áp mái nhà xưởng — Bình Dương</strong>
                                <span>Công suất: 500 kWp · Inverter: Sungrow · Thời gian hoàn vốn: &lt; 4 năm</span>
                            </div>
                        </div>
                        <div class="wiki-project-item">
                            <div class="project-badge project-badge-ci">C&I</div>
                            <div class="project-info">
                                <strong>Cụm công nghiệp Long An — Hệ thống PV + BESS</strong>
                                <span>Công suất: 1 MWp · Pin lưu trữ: 200 kWh BYD · Giám sát 24/7</span>
                            </div>
                        </div>
                        <div class="wiki-project-item">
                            <div class="project-badge project-badge-res">Dân dụng</div>
                            <div class="project-info">
                                <strong>Chương trình điện mặt trời hộ gia đình — TP.HCM & vùng phụ cận</strong>
                                <span>&gt; 500 hộ lắp đặt · Tấm pin JA Solar Mono PERC · Bảo hành 25 năm tấm pin</span>
                            </div>
                        </div>
                        <div class="wiki-project-item">
                            <div class="project-badge project-badge-ci">C&I</div>
                            <div class="project-info">
                                <strong>Trung tâm thương mại — Đồng Nai</strong>
                                <span>Công suất: 300 kWp · Inverter: Huawei · Giảm 40% chi phí điện</span>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 7. Đội ngũ --}}
                <section id="doi-ngu" class="wiki-section">
                    <h2 class="wiki-h2"><span class="wiki-h2-num">7</span> Đội ngũ lãnh đạo</h2>
                    <p>
                        NLP Labs Vietnam vận hành bởi đội ngũ kỹ sư và chuyên gia có kinh nghiệm trong lĩnh vực
                        điện-điện tử, xây dựng công trình điện và quản lý dự án năng lượng tái tạo.
                        Đội ngũ kỹ thuật được đào tạo định kỳ theo tiêu chuẩn IEC và giữ chứng chỉ NABCEP (kỹ sư
                        lắp đặt solar quốc tế) và chứng chỉ an toàn điện Bộ Công Thương.
                    </p>
                    <div class="wiki-note">
                        <span class="wiki-note-icon">ℹ</span>
                        Thông tin chi tiết về đội ngũ lãnh đạo được cập nhật qua kênh chính thức tại
                        <a href="{{ route('page', 'about') }}">trang Giới thiệu</a> của NLP Group.
                    </div>
                </section>

                {{-- 8. Liên hệ --}}
                <section id="lien-he" class="wiki-section">
                    <h2 class="wiki-h2"><span class="wiki-h2-num">8</span> Liên hệ</h2>
                    <div class="wiki-contact-box">
                        <div class="wiki-contact-item">
                            <i class="fa-light fa-globe"></i>
                            <span>Website: <a href="https://nlpgroup.vn" target="_blank" rel="noopener">https://nlpgroup.vn</a></span>
                        </div>
                        <div class="wiki-contact-item">
                            <i class="fa-light fa-location-dot"></i>
                            <span>Trụ sở chính: Thành phố Hồ Chí Minh, Việt Nam</span>
                        </div>
                        <div class="wiki-contact-item">
                            <i class="fa-light fa-envelope"></i>
                            <span>Email liên hệ thông qua: <a href="{{ route('page', 'contact') }}">Trang Liên Hệ</a></span>
                        </div>
                    </div>
                </section>

                {{-- 9. Xem thêm --}}
                <section id="xem-them" class="wiki-section">
                    <h2 class="wiki-h2"><span class="wiki-h2-num">9</span> Xem thêm</h2>
                    <ul class="wiki-seealso-list">
                        <li><a href="{{ route('page', 'solutions') }}">Giải pháp & Sản phẩm — NLP Group</a></li>
                        <li><a href="{{ route('page', 'about') }}">Giới thiệu công ty — NLP Group</a></li>
                        <li><a href="{{ route('page', 'faq') }}">Hỏi đáp về Điện mặt trời & EPC</a></li>
                        <li><a href="{{ route('blog') }}">Tin tức & Kiến thức kỹ thuật</a></li>
                        <li><a href="{{ route('page', 'contact') }}">Liên hệ tư vấn & Báo giá</a></li>
                    </ul>
                </section>

                {{-- 10. Tham khảo --}}
                <section id="tham-khao" class="wiki-section">
                    <h2 class="wiki-h2"><span class="wiki-h2-num">10</span> Tham khảo</h2>
                    <ol class="wiki-refs">
                        <li id="ref-1">Quyết định 11/2017/QĐ-TTg của Thủ tướng Chính phủ về cơ chế khuyến khích phát triển điện mặt trời tại Việt Nam.</li>
                        <li id="ref-2">IEC 61215: Crystalline silicon terrestrial photovoltaic (PV) modules — Design qualification and type approval.</li>
                        <li id="ref-3">ISO 9001:2015 — Hệ thống quản lý chất lượng — Các yêu cầu.</li>
                        <li id="ref-4">BloombergNEF, <em>Solar PV Market Outlook Vietnam 2023</em>.</li>
                        <li id="ref-5">NABCEP — North American Board of Certified Energy Practitioners, Solar PV Installation Professional.</li>
                        <li id="ref-6">Bộ Công Thương Việt Nam — Giấy phép hoạt động điện lực, danh mục điều kiện kinh doanh ngành điện.</li>
                    </ol>
                </section>

                {{-- Footer article meta --}}
                <div class="wiki-article-footer">
                    <div class="wiki-footer-tags">
                        <span class="wiki-tag">Năng lượng tái tạo</span>
                        <span class="wiki-tag">Điện mặt trời</span>
                        <span class="wiki-tag">EPC Solar</span>
                        <span class="wiki-tag">Solar PV Vietnam</span>
                        <span class="wiki-tag">BESS</span>
                        <span class="wiki-tag">ISO 9001</span>
                        <span class="wiki-tag">Tổng thầu điện</span>
                    </div>
                    <div class="wiki-footer-actions">
                        <a href="{{ route('page', 'contact') }}" class="wiki-cta-btn">
                            <i class="fa-regular fa-paper-plane"></i> @lang('Liên hệ tư vấn')
                        </a>
                        <a href="{{ route('blog') }}" class="wiki-cta-btn wiki-cta-btn--outline">
                            <i class="fa-regular fa-newspaper"></i> @lang('Đọc thêm bài viết')
                        </a>
                    </div>
                </div>

            </article>
        </div>{{-- /.wiki-layout --}}
    </div>{{-- /.container --}}
</div>{{-- /.wiki-page --}}
@endsection

@push('style')
<style>
/* ─────────────────────────────────────────
   WIKI PAGE STYLES
───────────────────────────────────────── */
.wiki-page { background: #fff; min-height: 80vh; }

/* Top banner */
.wiki-top-banner {
    background: #f6f8fa;
    border-bottom: 1px solid #d0d7de;
    padding: 10px 0;
}
.wiki-breadcrumb ol {
    list-style: none; display: flex; flex-wrap: wrap;
    gap: 4px; align-items: center; margin: 0; padding: 0;
    font-size: 13px; color: #57606a;
}
.wiki-breadcrumb a { color: #0550ae; text-decoration: none; }
.wiki-breadcrumb a:hover { text-decoration: underline; }
.wiki-breadcrumb .active { color: #1f2328; font-weight: 500; }

/* Layout */
.wiki-container { padding-top: 24px; padding-bottom: 48px; }
.wiki-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 32px;
    align-items: start;
}
@media (max-width: 991px) {
    .wiki-layout { grid-template-columns: 1fr; }
    .wiki-sidebar { order: 2; }
    .wiki-article { order: 1; }
}

/* ─── SIDEBAR ─── */
.wiki-sidebar { position: sticky; top: 80px; }

/* TOC */
.wiki-toc-box {
    border: 1px solid #d0d7de;
    border-radius: 6px;
    background: #f6f8fa;
    margin-bottom: 20px;
    overflow: hidden;
}
.toc-header {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 14px;
    border-bottom: 1px solid #d0d7de;
    background: #eaecef;
    font-size: 13px; font-weight: 600; color: #1f2328;
}
.toc-icon { font-size: 15px; }
.toc-toggle {
    margin-left: auto; background: none; border: none;
    cursor: pointer; font-size: 11px; color: #57606a;
    padding: 0 4px;
}
.toc-nav { padding: 10px 14px 14px; }
.toc-nav ol {
    list-style: none; padding: 0; margin: 0;
    counter-reset: none;
}
.toc-nav > ol > li { margin-bottom: 4px; }
.toc-nav ol ol { margin-left: 16px; margin-top: 3px; }
.toc-nav a {
    font-size: 12.5px; color: #0550ae; text-decoration: none;
    line-height: 1.5;
}
.toc-nav a:hover { text-decoration: underline; }
.toc-nav ol ol a { font-size: 12px; color: #57606a; }

/* Infobox */
.wiki-infobox {
    border: 1px solid #d0d7de;
    border-radius: 6px;
    overflow: hidden;
    font-size: 12.5px;
}
.infobox-header {
    background: #2a5caa;
    color: #fff;
    font-weight: 700;
    font-size: 13px;
    padding: 10px 14px;
    text-align: center;
}
.infobox-img-placeholder { background: #f6f8fa; border-bottom: 1px solid #d0d7de; }
.infobox-table { width: 100%; border-collapse: collapse; }
.infobox-table tr:nth-child(odd) td, .infobox-table tr:nth-child(odd) th { background: #f6f8fa; }
.infobox-table th, .infobox-table td {
    padding: 6px 12px;
    border-bottom: 1px solid #eaecef;
    vertical-align: top;
}
.infobox-table th { font-weight: 600; color: #1f2328; width: 38%; white-space: nowrap; }
.infobox-table td { color: #24292f; }
.infobox-table a { color: #0550ae; }

/* ─── ARTICLE ─── */
.wiki-article { font-size: 14.5px; line-height: 1.75; color: #1f2328; }

.wiki-article-header { margin-bottom: 20px; }
.wiki-title { font-size: 26px; font-weight: 700; color: #1f2328; margin-bottom: 4px; font-family: "Linux Libertine","Georgia",serif; }
.wiki-subtitle { font-size: 13px; color: #57606a; margin-bottom: 10px; }
.wiki-meta-bar { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; font-size: 12px; color: #57606a; }
.wiki-meta-item i { margin-right: 4px; }
.wiki-badge-verified { background: #dafbe1; color: #116329; padding: 2px 8px; border-radius: 12px; font-weight: 600; font-size: 11px; }
.wiki-divider { border: none; border-top: 1px solid #d0d7de; margin: 16px 0 20px; }

/* Lead paragraph */
.wiki-lead { font-size: 15px; line-height: 1.8; }

/* Sections */
.wiki-section { margin-bottom: 28px; }
.wiki-h2 {
    font-size: 19px; font-weight: 700; color: #1f2328;
    border-bottom: 1px solid #d0d7de;
    padding-bottom: 6px; margin: 24px 0 14px;
    font-family: "Linux Libertine","Georgia",serif;
    display: flex; align-items: center; gap: 10px;
}
.wiki-h2-num {
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px; border-radius: 50%;
    background: #2a5caa; color: #fff;
    font-size: 12px; font-weight: 700; flex-shrink: 0;
}
.wiki-h3 {
    font-size: 15.5px; font-weight: 700; color: #1f2328;
    margin: 18px 0 10px;
}

/* Lists */
.wiki-list { padding-left: 22px; margin-bottom: 12px; }
.wiki-list li { margin-bottom: 6px; }

/* Tables */
.wiki-table-wrapper { overflow-x: auto; margin: 14px 0; }
.wiki-table {
    width: 100%; border-collapse: collapse; font-size: 13px;
    border: 1px solid #d0d7de;
}
.wiki-table th {
    background: #f6f8fa; border: 1px solid #d0d7de;
    padding: 8px 12px; text-align: left; font-weight: 600; color: #1f2328;
}
.wiki-table td { border: 1px solid #d0d7de; padding: 7px 12px; }
.wiki-table tr:nth-child(even) td { background: #f6f8fa; }

/* Cert grid */
.wiki-cert-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 12px; margin-top: 14px;
}
.wiki-cert-card {
    border: 1px solid #d0d7de; border-radius: 8px;
    padding: 14px 12px; text-align: center; background: #f6f8fa;
}
.cert-icon { font-size: 24px; margin-bottom: 6px; }
.cert-name { font-weight: 700; font-size: 12.5px; color: #1f2328; margin-bottom: 4px; }
.cert-desc { font-size: 11.5px; color: #57606a; line-height: 1.4; }

/* Project list */
.wiki-project-list { display: flex; flex-direction: column; gap: 10px; margin-top: 14px; }
.wiki-project-item {
    display: flex; align-items: flex-start; gap: 12px;
    border: 1px solid #d0d7de; border-radius: 6px;
    padding: 12px 14px; background: #f6f8fa;
}
.project-badge {
    font-size: 11px; font-weight: 700; padding: 3px 8px;
    border-radius: 12px; white-space: nowrap; margin-top: 1px;
}
.project-badge-ci { background: #ddf4ff; color: #0550ae; }
.project-badge-res { background: #dafbe1; color: #116329; }
.project-info { display: flex; flex-direction: column; gap: 3px; }
.project-info strong { font-size: 13.5px; color: #1f2328; }
.project-info span { font-size: 12.5px; color: #57606a; }

/* Note box */
.wiki-note {
    background: #ddf4ff; border: 1px solid #54aeff66;
    border-left: 4px solid #0969da;
    border-radius: 6px; padding: 10px 14px;
    font-size: 13px; color: #1f2328; margin: 14px 0;
    display: flex; gap: 10px; align-items: flex-start;
}
.wiki-note-icon { font-size: 16px; color: #0969da; flex-shrink: 0; margin-top: 1px; }
.wiki-note a { color: #0550ae; }

/* Contact box */
.wiki-contact-box { display: flex; flex-direction: column; gap: 10px; margin-top: 12px; }
.wiki-contact-item {
    display: flex; align-items: center; gap: 12px;
    font-size: 13.5px; color: #1f2328;
}
.wiki-contact-item i { color: #2a5caa; font-size: 15px; width: 18px; text-align: center; }
.wiki-contact-item a { color: #0550ae; }

/* See also */
.wiki-seealso-list { padding-left: 22px; margin-top: 10px; }
.wiki-seealso-list li { margin-bottom: 5px; }
.wiki-seealso-list a { color: #0550ae; font-size: 13.5px; }

/* References */
.wiki-refs { padding-left: 22px; font-size: 12.5px; color: #57606a; line-height: 1.6; }
.wiki-refs li { margin-bottom: 6px; }

/* Article footer */
.wiki-article-footer {
    margin-top: 32px; padding-top: 20px;
    border-top: 2px solid #d0d7de;
}
.wiki-footer-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; }
.wiki-tag {
    background: #f6f8fa; border: 1px solid #d0d7de;
    border-radius: 12px; padding: 3px 10px;
    font-size: 12px; color: #57606a;
}
.wiki-footer-actions { display: flex; flex-wrap: wrap; gap: 10px; }
.wiki-cta-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 20px; border-radius: 6px;
    font-size: 13.5px; font-weight: 600; text-decoration: none;
    background: #2a5caa; color: #fff; border: 2px solid #2a5caa;
    transition: background .15s, color .15s;
}
.wiki-cta-btn:hover { background: #1d4384; border-color: #1d4384; color: #fff; }
.wiki-cta-btn--outline { background: transparent; color: #2a5caa; }
.wiki-cta-btn--outline:hover { background: #2a5caa; color: #fff; }
</style>
@endpush

@push('script')
<script>
'use strict';
// TOC toggle
document.getElementById('tocToggle').addEventListener('click', function () {
    var nav = document.getElementById('tocNav');
    var btn = this;
    if (nav.style.display === 'none') {
        nav.style.display = '';
        btn.textContent = '▲';
    } else {
        nav.style.display = 'none';
        btn.textContent = '▼';
    }
});

// Smooth scroll for TOC links
document.querySelectorAll('.toc-nav a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
        var target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            history.pushState(null, null, this.getAttribute('href'));
        }
    });
});

// Active TOC highlight on scroll
var sections = document.querySelectorAll('.wiki-section[id], .wiki-h3[id]');
var tocLinks = document.querySelectorAll('.toc-nav a');
window.addEventListener('scroll', function () {
    var scrollY = window.scrollY + 120;
    var current = '';
    sections.forEach(function (s) {
        if (s.offsetTop <= scrollY) current = s.id;
    });
    tocLinks.forEach(function (a) {
        a.style.fontWeight = '';
        a.style.color = '';
        if (a.getAttribute('href') === '#' + current) {
            a.style.fontWeight = '700';
            a.style.color = '#1f2328';
        }
    });
});
</script>
@endpush
