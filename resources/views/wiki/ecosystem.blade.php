@extends(template().'layouts.app')
@section('title', 'Hệ sinh thái NLP Group')

@section('content')

@include(template().'partials.banner')

<div class="eco-page">
    <div class="container">

        {{-- ══ INTRO ══ --}}
        <section class="eco-section py-5" data-aos="fade-up">
            <div class="text-center mb-2">
                <span class="eco-eyebrow">NLP Group Ecosystem</span>
                <h1 class="eco-page-title">Hệ sinh thái NLP Group</h1>
                <p class="eco-page-tagline">
                    <strong>5 lĩnh vực chiến lược</strong> — tích hợp liền mạch từ sản xuất điện sạch đến
                    chuyển đổi số &amp; quản trị carbon, tạo nên hệ sinh thái năng lượng – công nghệ toàn diện nhất Việt Nam.
                </p>
            </div>
            <div class="eco-pillars-row" data-aos="fade-up" data-aos-delay="100">
                <div class="eco-pillar-chip"><i class="fa-light fa-solar-panel"></i> Năng lượng</div>
                <div class="eco-pillar-arrow">→</div>
                <div class="eco-pillar-chip"><i class="fa-light fa-charging-station"></i> EV Charging</div>
                <div class="eco-pillar-arrow">→</div>
                <div class="eco-pillar-chip"><i class="fa-light fa-laptop-code"></i> Technology</div>
                <div class="eco-pillar-arrow">→</div>
                <div class="eco-pillar-chip"><i class="fa-light fa-brain-circuit"></i> AI &amp; Digital</div>
                <div class="eco-pillar-arrow">→</div>
                <div class="eco-pillar-chip"><i class="fa-light fa-leaf"></i> ESG &amp; Carbon</div>
            </div>
        </section>

        {{-- ══ 5 ECOSYSTEM CARDS ══ --}}
        <section class="eco-section py-3" data-aos="fade-up">
            <div class="section-label">5 lĩnh vực chiến lược</div>
            <h2 class="eco-section-title">Các công ty thành viên</h2>

            {{-- Card 1: NLP Energy --}}
            <div class="eco-card" data-aos="fade-up" data-aos-delay="50">
                <div class="eco-card-icon eco-card-icon--energy">
                    <i class="fa-light fa-solar-panel"></i>
                </div>
                <div class="eco-card-body">
                    <div class="eco-card-label">Thành viên 01</div>
                    <h3 class="eco-card-title">NLP Energy</h3>
                    <p class="eco-card-desc">
                        Đơn vị tiên phong trong lĩnh vực năng lượng tái tạo tại Việt Nam. NLP Energy cung cấp
                        giải pháp điện mặt trời toàn diện từ khảo sát, thiết kế, thi công đến vận hành bảo trì,
                        kết hợp hệ thống lưu trữ BESS và quản lý năng lượng thông minh EMS.
                    </p>
                    <div class="eco-tags">
                        <span class="eco-tag">Điện mặt trời</span>
                        <span class="eco-tag">BESS</span>
                        <span class="eco-tag">EMS</span>
                        <span class="eco-tag">EPC</span>
                        <span class="eco-tag">O&amp;M</span>
                    </div>
                    <a href="{{ route('page', 'nlp-energy') }}" class="eco-btn">
                        Tìm hiểu thêm <i class="fa-light fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            {{-- Card 2: NLP EV Charging --}}
            <div class="eco-card eco-card--alt" data-aos="fade-up" data-aos-delay="80">
                <div class="eco-card-icon eco-card-icon--ev">
                    <i class="fa-light fa-charging-station"></i>
                </div>
                <div class="eco-card-body">
                    <div class="eco-card-label">Thành viên 02</div>
                    <h3 class="eco-card-title">NLP EV Charging</h3>
                    <p class="eco-card-desc">
                        Đơn vị xây dựng và vận hành hạ tầng sạc xe điện thông minh. NLP EV Charging triển khai
                        mạng lưới trụ sạc AC/DC theo chuẩn OCPP, tích hợp hệ thống quản lý CSMS và ứng dụng
                        di động, phục vụ từ hộ gia đình đến khu công nghiệp và trạm dừng nghỉ cao tốc.
                    </p>
                    <div class="eco-tags">
                        <span class="eco-tag">Trụ sạc AC/DC</span>
                        <span class="eco-tag">CSMS</span>
                        <span class="eco-tag">OCPP 2.0.1</span>
                        <span class="eco-tag">Nhượng quyền</span>
                    </div>
                    <a href="{{ route('page', 'nlp-ev-charging') }}" class="eco-btn">
                        Tìm hiểu thêm <i class="fa-light fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            {{-- Card 3: NLP Technology --}}
            <div class="eco-card" data-aos="fade-up" data-aos-delay="110">
                <div class="eco-card-icon eco-card-icon--tech">
                    <i class="fa-light fa-laptop-code"></i>
                </div>
                <div class="eco-card-body">
                    <div class="eco-card-label">Thành viên 03</div>
                    <h3 class="eco-card-title">NLP Technology</h3>
                    <p class="eco-card-desc">
                        Cung cấp giải pháp phần mềm doanh nghiệp và hạ tầng số toàn diện. NLP Technology phát
                        triển các hệ thống ERP, CRM, IoT và Cloud cho các tổ chức trong lĩnh vực năng lượng,
                        sản xuất và dịch vụ tại Việt Nam.
                    </p>
                    <div class="eco-tags">
                        <span class="eco-tag">ERP</span>
                        <span class="eco-tag">CRM</span>
                        <span class="eco-tag">IoT Platform</span>
                        <span class="eco-tag">Cloud</span>
                        <span class="eco-tag">SaaS</span>
                    </div>
                    <a href="{{ route('page', 'nlp-technology') }}" class="eco-btn">
                        Tìm hiểu thêm <i class="fa-light fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            {{-- Card 4: NLP AI & Digital --}}
            <div class="eco-card eco-card--alt" data-aos="fade-up" data-aos-delay="140">
                <div class="eco-card-icon eco-card-icon--ai">
                    <i class="fa-light fa-brain-circuit"></i>
                </div>
                <div class="eco-card-body">
                    <div class="eco-card-label">Thành viên 04</div>
                    <h3 class="eco-card-title">NLP AI &amp; Digital</h3>
                    <p class="eco-card-desc">
                        Đơn vị R&amp;D và triển khai các giải pháp trí tuệ nhân tạo tiên tiến. NLP AI &amp; Digital
                        xây dựng AI Agent, hệ thống RAG/LLM, Computer Vision và các mô hình phân tích dữ liệu
                        phục vụ tối ưu hoá vận hành năng lượng và chuyển đổi số doanh nghiệp.
                    </p>
                    <div class="eco-tags">
                        <span class="eco-tag">AI Agent</span>
                        <span class="eco-tag">RAG/LLM</span>
                        <span class="eco-tag">Computer Vision</span>
                        <span class="eco-tag">Data Analytics</span>
                    </div>
                    <a href="{{ route('page', 'nlp-ai-digital') }}" class="eco-btn">
                        Tìm hiểu thêm <i class="fa-light fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            {{-- Card 5: NLP ESG & Carbon --}}
            <div class="eco-card" data-aos="fade-up" data-aos-delay="170">
                <div class="eco-card-icon eco-card-icon--esg">
                    <i class="fa-light fa-leaf"></i>
                </div>
                <div class="eco-card-body">
                    <div class="eco-card-label">Thành viên 05</div>
                    <h3 class="eco-card-title">NLP ESG &amp; Carbon</h3>
                    <p class="eco-card-desc">
                        Tư vấn và triển khai giải pháp quản trị bền vững, đo lường kiểm soát phát thải (MRV)
                        và phát triển tín chỉ carbon. NLP ESG &amp; Carbon đồng hành cùng doanh nghiệp trên
                        hành trình hướng đến Net Zero 2050, tuân thủ tiêu chuẩn GHG Protocol và ISO 14064.
                    </p>
                    <div class="eco-tags">
                        <span class="eco-tag">MRV</span>
                        <span class="eco-tag">Tín chỉ carbon</span>
                        <span class="eco-tag">Net Zero</span>
                        <span class="eco-tag">GHG Protocol</span>
                        <span class="eco-tag">ISO 14064</span>
                    </div>
                    <a href="{{ route('page', 'nlp-esg-carbon') }}" class="eco-btn">
                        Tìm hiểu thêm <i class="fa-light fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </section>

        {{-- ══ WHY NLP GROUP ══ --}}
        <section class="eco-section py-5" data-aos="fade-up">
            <hr class="eco-divider mb-5">
            <div class="section-label">Lợi thế cạnh tranh</div>
            <h2 class="eco-section-title">Tại sao chọn NLP Group?</h2>
            <div class="row g-4 mt-2">
                <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="50">
                    <div class="diff-card">
                        <div class="diff-icon"><i class="fa-light fa-puzzle-piece"></i></div>
                        <h5>Giải pháp tích hợp</h5>
                        <p>Một đối tác duy nhất cho toàn bộ chuỗi giá trị: năng lượng → sạc EV → phần mềm → AI → ESG. Loại bỏ phức tạp khi làm việc với nhiều nhà cung cấp.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="diff-card">
                        <div class="diff-icon"><i class="fa-light fa-shield-halved"></i></div>
                        <h5>Chứng nhận quốc tế</h5>
                        <p>ISO 9001, ISO 14001, ISO 45001. Đội ngũ kỹ sư được chứng nhận NABCEP. Thiết bị từ thương hiệu Tier 1 toàn cầu với bảo hành đầy đủ.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="150">
                    <div class="diff-card">
                        <div class="diff-icon"><i class="fa-light fa-chart-line-up"></i></div>
                        <h5>Hiệu quả đo lường được</h5>
                        <p>Mỗi giải pháp đều gắn với KPI rõ ràng: % tiết kiệm điện, tấn CO₂ giảm, ROI dự án. Báo cáo vận hành minh bạch theo thời gian thực.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="diff-card">
                        <div class="diff-icon"><i class="fa-light fa-handshake"></i></div>
                        <h5>Hỗ trợ toàn vòng đời</h5>
                        <p>Từ tư vấn thiết kế đến thi công, vận hành và bảo trì. Dịch vụ O&amp;M 24/7 với SLA cam kết, đảm bảo hệ thống luôn hoạt động tối ưu.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ INTERCONNECTION DIAGRAM ══ --}}
        <section class="eco-section py-5" data-aos="fade-up">
            <hr class="eco-divider mb-5">
            <div class="section-label">Hệ thống tích hợp</div>
            <h2 class="eco-section-title">5 trụ cột hoạt động cộng hưởng</h2>
            <p class="eco-section-desc mt-2">Các đơn vị thành viên NLP không hoạt động độc lập — chúng liên kết và hỗ trợ lẫn nhau, tạo ra giá trị tổng hợp vượt trội cho khách hàng.</p>
            <div class="interconnect-diagram mt-4">
                <div class="ic-center">
                    <div class="ic-center-inner">
                        <i class="fa-light fa-building-columns fa-2x mb-2"></i>
                        <div>NLP Group</div>
                        <div class="ic-center-sub">Hệ sinh thái</div>
                    </div>
                </div>
                <div class="ic-spokes">
                    <div class="ic-spoke">
                        <div class="ic-spoke-node ic-spoke-node--energy">
                            <i class="fa-light fa-solar-panel"></i>
                            <span>NLP Energy</span>
                        </div>
                        <div class="ic-spoke-arrow">Điện sạch → Hạ tầng sạc</div>
                    </div>
                    <div class="ic-spoke">
                        <div class="ic-spoke-node ic-spoke-node--ev">
                            <i class="fa-light fa-charging-station"></i>
                            <span>NLP EV</span>
                        </div>
                        <div class="ic-spoke-arrow">Sạc xanh → Giao thông bền vững</div>
                    </div>
                    <div class="ic-spoke">
                        <div class="ic-spoke-node ic-spoke-node--tech">
                            <i class="fa-light fa-laptop-code"></i>
                            <span>NLP Tech</span>
                        </div>
                        <div class="ic-spoke-arrow">Nền tảng số → Kết nối hệ sinh thái</div>
                    </div>
                    <div class="ic-spoke">
                        <div class="ic-spoke-node ic-spoke-node--ai">
                            <i class="fa-light fa-brain-circuit"></i>
                            <span>NLP AI</span>
                        </div>
                        <div class="ic-spoke-arrow">AI → Tối ưu hoá toàn hệ thống</div>
                    </div>
                    <div class="ic-spoke">
                        <div class="ic-spoke-node ic-spoke-node--esg">
                            <i class="fa-light fa-leaf"></i>
                            <span>NLP ESG</span>
                        </div>
                        <div class="ic-spoke-arrow">ESG → Minh bạch &amp; Net Zero</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ CTA ══ --}}
        <section class="eco-cta-section py-5 mb-5" data-aos="fade-up">
            <div class="eco-cta-box text-center">
                <h3>Khám phá giải pháp phù hợp với bạn</h3>
                <p>Đội ngũ chuyên gia NLP Group sẵn sàng tư vấn và thiết kế gói giải pháp tối ưu.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                    <a href="{{ route('page', 'contact') }}" class="eco-cta-btn eco-cta-btn--primary">
                        <i class="fa-light fa-paper-plane"></i> Liên hệ ngay
                    </a>
                    <a href="{{ route('page', 'wiki/about') }}" class="eco-cta-btn eco-cta-btn--outline">
                        <i class="fa-light fa-circle-info"></i> Về NLP Group
                    </a>
                </div>
            </div>
        </section>

    </div>{{-- /.container --}}
</div>{{-- /.eco-page --}}
@endsection

@push('style')
<style>
/* ═══════════════════════════════════════
   ECOSYSTEM PAGE — NLP Group
   Primary: #1a4731 | Accent: #2d7a4f | Light: #3aad6e
═══════════════════════════════════════ */
.eco-page { background: #fff; min-height: 80vh; }

.eco-divider { border: none; border-top: 1px solid #d8e8df; margin: 0; }
.section-label {
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: #3aad6e; margin-bottom: 6px;
}
.eco-section-title { font-size: 26px; font-weight: 700; color: #1a4731; margin-bottom: 8px; }
.eco-section-desc { color: #57606a; font-size: 15px; max-width: 700px; }

/* Hero */
.eco-eyebrow {
    display: inline-block; font-size: 12px; font-weight: 700;
    letter-spacing: 1.5px; text-transform: uppercase;
    color: #3aad6e; margin-bottom: 10px;
}
.eco-page-title { font-size: 36px; font-weight: 800; color: #1a4731; margin-bottom: 14px; }
.eco-page-tagline { font-size: 16px; color: #374151; max-width: 700px; margin: 0 auto 28px; line-height: 1.75; }

/* Pillars row */
.eco-pillars-row {
    display: flex; flex-wrap: wrap; align-items: center;
    justify-content: center; gap: 8px; padding: 20px 0;
}
.eco-pillar-chip {
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 20px; padding: 8px 18px;
    font-size: 13px; font-weight: 600; color: #1a4731;
    display: flex; align-items: center; gap: 7px;
}
.eco-pillar-chip i { color: #2d7a4f; }
.eco-pillar-arrow { color: #3aad6e; font-size: 18px; font-weight: 700; }

/* Eco cards */
.eco-card {
    display: flex; align-items: flex-start; gap: 28px;
    border: 1px solid #c3ddd0; border-radius: 14px;
    padding: 28px 28px; margin-bottom: 20px;
    background: #fff; transition: box-shadow .2s;
}
.eco-card--alt { background: #f8fdf9; }
.eco-card:hover { box-shadow: 0 6px 24px rgba(26,71,49,.1); }
.eco-card-icon {
    width: 72px; height: 72px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; flex-shrink: 0;
}
.eco-card-icon--energy { background: #dff2e9; color: #2d7a4f; }
.eco-card-icon--ev     { background: #d0eafb; color: #0550ae; }
.eco-card-icon--tech   { background: #fdf3d0; color: #7d5a00; }
.eco-card-icon--ai     { background: #ede9ff; color: #5b2bd0; }
.eco-card-icon--esg    { background: #dff2e9; color: #1a4731; }
.eco-card-body { flex: 1; }
.eco-card-label {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1px; color: #3aad6e; margin-bottom: 4px;
}
.eco-card-title { font-size: 20px; font-weight: 700; color: #1a4731; margin-bottom: 10px; }
.eco-card-desc { font-size: 14px; color: #374151; line-height: 1.75; margin-bottom: 14px; }
.eco-tags { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 18px; }
.eco-tag {
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 12px; padding: 3px 10px;
    font-size: 12px; color: #2d7a4f; font-weight: 600;
}
.eco-btn {
    display: inline-flex; align-items: center;
    padding: 9px 20px; border-radius: 7px;
    background: #1a4731; color: #fff;
    font-size: 13px; font-weight: 700; text-decoration: none;
    transition: background .15s;
}
.eco-btn:hover { background: #2d7a4f; color: #fff; }

/* Differentiator cards */
.diff-card {
    border: 1px solid #c3ddd0; border-radius: 12px;
    padding: 26px 22px; height: 100%; background: #f8fdf9;
    text-align: center; transition: transform .2s, box-shadow .2s;
}
.diff-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(26,71,49,.1); }
.diff-icon { font-size: 32px; color: #2d7a4f; margin-bottom: 12px; }
.diff-card h5 { font-size: 15px; font-weight: 700; color: #1a4731; margin-bottom: 8px; }
.diff-card p { font-size: 13px; color: #57606a; line-height: 1.7; margin: 0; }

/* Interconnect diagram */
.interconnect-diagram {
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 16px; padding: 36px 28px; text-align: center;
}
.ic-center {
    display: inline-flex; align-items: center; justify-content: center;
    margin-bottom: 24px;
}
.ic-center-inner {
    background: #1a4731; color: #fff; border-radius: 50%;
    width: 110px; height: 110px; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700;
}
.ic-center-inner i { color: #3aad6e; }
.ic-center-sub { font-size: 11px; color: #c3ddd0; margin-top: 2px; }
.ic-spokes {
    display: flex; flex-wrap: wrap; justify-content: center; gap: 16px;
}
.ic-spoke {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    min-width: 140px;
}
.ic-spoke-node {
    width: 90px; height: 90px; border-radius: 50%;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; gap: 6px;
}
.ic-spoke-node i { font-size: 22px; }
.ic-spoke-node--energy { background: #dff2e9; color: #2d7a4f; border: 2px solid #2d7a4f; }
.ic-spoke-node--ev     { background: #d0eafb; color: #0550ae; border: 2px solid #0550ae; }
.ic-spoke-node--tech   { background: #fdf3d0; color: #7d5a00; border: 2px solid #e3a800; }
.ic-spoke-node--ai     { background: #ede9ff; color: #5b2bd0; border: 2px solid #7c5cd8; }
.ic-spoke-node--esg    { background: #dff2e9; color: #1a4731; border: 2px solid #1a4731; }
.ic-spoke-arrow { font-size: 11px; color: #57606a; text-align: center; max-width: 140px; }

/* CTA */
.eco-cta-box {
    background: linear-gradient(135deg, #1a4731 60%, #2d7a4f 100%);
    border-radius: 16px; padding: 56px 32px; color: #fff;
}
.eco-cta-box h3 { font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 10px; }
.eco-cta-box p { font-size: 15px; color: #c3ddd0; margin: 0; }
.eco-cta-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 26px; border-radius: 8px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    transition: all .2s;
}
.eco-cta-btn--primary { background: #3aad6e; color: #fff; border: 2px solid #3aad6e; }
.eco-cta-btn--primary:hover { background: #2d7a4f; border-color: #2d7a4f; color: #fff; }
.eco-cta-btn--outline { background: transparent; color: #fff; border: 2px solid rgba(255,255,255,.5); }
.eco-cta-btn--outline:hover { background: rgba(255,255,255,.1); border-color: #fff; color: #fff; }

@media (max-width: 767px) {
    .eco-card { flex-direction: column; }
    .eco-card-icon { width: 56px; height: 56px; font-size: 24px; }
    .eco-page-title { font-size: 26px; }
    .ic-spoke-node { width: 74px; height: 74px; }
    .eco-pillar-arrow { display: none; }
}
</style>
@endpush
