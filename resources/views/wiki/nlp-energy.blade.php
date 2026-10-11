@extends(template().'layouts.app')
@section('title', 'NLP Energy — Năng lượng xanh')

@section('content')

@include(template().'partials.banner')

<div class="energy-page">
    <div class="container">

        {{-- ══ INTRO ══ --}}
        <section class="energy-section py-5" data-aos="fade-up">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="energy-eyebrow">Thành viên NLP Group</span>
                    <h1 class="energy-hero-title">NLP Energy</h1>
                    <p class="energy-hero-subtitle">Năng lượng xanh — Giải pháp bền vững</p>
                    <p class="energy-hero-desc">
                        <strong>NLP Energy</strong> là đơn vị thành viên thuộc NLP Group, chuyên nghiên cứu, phát triển
                        và triển khai các giải pháp <strong>năng lượng tái tạo</strong> toàn diện tại Việt Nam.
                        Hoạt động theo mô hình tích hợp, NLP Energy đảm nhận toàn bộ vòng đời dự án từ tư vấn
                        thiết kế, thi công EPC đến vận hành bảo trì O&amp;M, giúp khách hàng tối ưu chi phí năng
                        lượng và thực hiện cam kết giảm phát thải carbon.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('page', 'contact') }}" class="energy-btn energy-btn--primary">
                            <i class="fa-light fa-paper-plane"></i> Liên hệ tư vấn miễn phí
                        </a>
                        <a href="#services" class="energy-btn energy-btn--outline">
                            <i class="fa-light fa-list-check"></i> Xem dịch vụ
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="energy-stats-grid">
                        <div class="energy-stat">
                            <i class="fa-light fa-solar-panel"></i>
                            <span class="estat-num">150+</span>
                            <span class="estat-label">Dự án hoàn thành</span>
                        </div>
                        <div class="energy-stat">
                            <i class="fa-light fa-bolt"></i>
                            <span class="estat-num">12 MW</span>
                            <span class="estat-label">Tổng công suất lắp đặt</span>
                        </div>
                        <div class="energy-stat">
                            <i class="fa-light fa-cloud"></i>
                            <span class="estat-num">8,400T</span>
                            <span class="estat-label">CO₂ giảm phát thải / năm</span>
                        </div>
                        <div class="energy-stat">
                            <i class="fa-light fa-house"></i>
                            <span class="estat-num">2,000+</span>
                            <span class="estat-label">Hộ & doanh nghiệp phục vụ</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="energy-divider">

        {{-- ══ SERVICES ══ --}}
        <section class="energy-section py-5" id="services" data-aos="fade-up">
            <div class="section-label">Danh mục dịch vụ</div>
            <h2 class="energy-section-title">Dịch vụ của NLP Energy</h2>
            <p class="energy-section-desc">Giải pháp năng lượng toàn diện cho mọi đối tượng khách hàng.</p>
            <div class="row g-4 mt-2">

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fa-light fa-solar-panel"></i>
                        </div>
                        <h4 class="service-title">Điện mặt trời áp mái</h4>
                        <p class="service-desc">Thiết kế và thi công hệ thống điện mặt trời áp mái cho nhà dân, nhà xưởng và khu công nghiệp. Tấm pin Tier 1, inverter Sungrow / Huawei.</p>
                        <ul class="service-list">
                            <li>Nhà dân, biệt thự</li>
                            <li>Nhà xưởng sản xuất</li>
                            <li>Khu công nghiệp (C&amp;I)</li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fa-light fa-battery-full"></i>
                        </div>
                        <h4 class="service-title">Hệ thống lưu trữ BESS</h4>
                        <p class="service-desc">Tích hợp Battery Energy Storage System (BESS) song song với hệ thống PV, tối ưu tự tiêu thụ và cung cấp điện dự phòng an toàn.</p>
                        <ul class="service-list">
                            <li>Pin LiFePO₄ &gt; 6,000 cycles</li>
                            <li>BMS tích hợp bảo vệ thông minh</li>
                            <li>Giám sát 24/7 qua cloud</li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fa-light fa-chart-line"></i>
                        </div>
                        <h4 class="service-title">Quản lý năng lượng EMS</h4>
                        <p class="service-desc">Hệ thống Energy Management System giúp theo dõi, phân tích và tối ưu hoá tiêu thụ điện năng theo thời gian thực cho toàn bộ cơ sở sản xuất.</p>
                        <ul class="service-list">
                            <li>Dashboard thời gian thực</li>
                            <li>Cảnh báo bất thường tự động</li>
                            <li>Báo cáo ESG định kỳ</li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fa-light fa-hard-hat"></i>
                        </div>
                        <h4 class="service-title">Tổng thầu EPC</h4>
                        <p class="service-desc">Dịch vụ Engineering – Procurement – Construction trọn gói. NLP Energy đảm nhận toàn bộ quy trình từ thiết kế kỹ thuật đến bàn giao vận hành.</p>
                        <ul class="service-list">
                            <li>Thiết kế hệ thống (PVsyst)</li>
                            <li>Cung cấp thiết bị chính hãng</li>
                            <li>Đăng ký đấu nối EVN</li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fa-light fa-tools"></i>
                        </div>
                        <h4 class="service-title">Vận hành &amp; Bảo trì O&amp;M</h4>
                        <p class="service-desc">Dịch vụ O&amp;M toàn diện sau lắp đặt: giám sát SCADA 24/7, bảo dưỡng định kỳ, kiểm tra thermography IR và bảo hành hệ thống dài hạn.</p>
                        <ul class="service-list">
                            <li>Giám sát SCADA / cloud</li>
                            <li>Vệ sinh tấm pin định kỳ</li>
                            <li>Hợp đồng O&amp;M 5–10 năm</li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fa-light fa-bolt"></i>
                        </div>
                        <h4 class="service-title">Giải pháp tiết kiệm năng lượng</h4>
                        <p class="service-desc">Kiểm toán năng lượng và tư vấn giải pháp tiết kiệm điện tổng thể: thay thế thiết bị hiệu suất cao, tối ưu hệ thống HVAC và chiếu sáng LED.</p>
                        <ul class="service-list">
                            <li>Kiểm toán năng lượng ISO 50001</li>
                            <li>Thay thế thiết bị tiết kiệm điện</li>
                            <li>Giảm 20–40% chi phí điện</li>
                        </ul>
                    </div>
                </div>

            </div>
        </section>

        <hr class="energy-divider">

        {{-- ══ TARGET CUSTOMERS ══ --}}
        <section class="energy-section py-5" data-aos="fade-up">
            <div class="section-label">Đối tượng phục vụ</div>
            <h2 class="energy-section-title">Khách hàng mục tiêu</h2>
            <div class="row g-4 mt-2">
                <div class="col-md-6" data-aos="fade-right" data-aos-delay="50">
                    <div class="customer-card customer-card--enterprise">
                        <div class="customer-icon"><i class="fa-light fa-industry"></i></div>
                        <div>
                            <h5>Doanh nghiệp &amp; Công nghiệp</h5>
                            <ul class="customer-list">
                                <li><i class="fa-light fa-check"></i> Nhà máy sản xuất (FMCG, dệt may, điện tử)</li>
                                <li><i class="fa-light fa-check"></i> Khu công nghiệp (KCN) &amp; khu chế xuất</li>
                                <li><i class="fa-light fa-check"></i> Trung tâm thương mại &amp; văn phòng</li>
                                <li><i class="fa-light fa-check"></i> Trang trại &amp; nông nghiệp công nghệ cao</li>
                                <li><i class="fa-light fa-check"></i> Khách sạn, resort &amp; khu nghỉ dưỡng</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left" data-aos-delay="50">
                    <div class="customer-card customer-card--residential">
                        <div class="customer-icon"><i class="fa-light fa-house-chimney"></i></div>
                        <div>
                            <h5>Hộ gia đình &amp; Nhà ở</h5>
                            <ul class="customer-list">
                                <li><i class="fa-light fa-check"></i> Nhà phố &amp; biệt thự</li>
                                <li><i class="fa-light fa-check"></i> Chung cư tự quản &amp; hộ kinh doanh</li>
                                <li><i class="fa-light fa-check"></i> Trường học &amp; bệnh viện tư</li>
                                <li><i class="fa-light fa-check"></i> Khu dân cư mới phát triển</li>
                                <li><i class="fa-light fa-check"></i> Hộ có xe điện (kết hợp EV Charging)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="energy-divider">

        {{-- ══ PROCESS FLOW ══ --}}
        <section class="energy-section py-5" data-aos="fade-up">
            <div class="section-label">Quy trình triển khai</div>
            <h2 class="energy-section-title">7 bước triển khai dự án</h2>
            <p class="energy-section-desc">Quy trình chuẩn hoá, minh bạch — đảm bảo chất lượng và tiến độ tối ưu.</p>
            <div class="process-flow mt-4">
                <div class="process-step">
                    <div class="ps-num">1</div>
                    <div class="ps-icon"><i class="fa-light fa-magnifying-glass-location"></i></div>
                    <div class="ps-label">Khảo sát</div>
                    <div class="ps-desc">Khảo sát hiện trường, đánh giá kết cấu mái &amp; bức xạ mặt trời</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">2</div>
                    <div class="ps-icon"><i class="fa-light fa-comments"></i></div>
                    <div class="ps-label">Tư vấn</div>
                    <div class="ps-desc">Phân tích tiêu thụ điện, tư vấn giải pháp tối ưu</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">3</div>
                    <div class="ps-icon"><i class="fa-light fa-drafting-compass"></i></div>
                    <div class="ps-label">Thiết kế</div>
                    <div class="ps-desc">Thiết kế hệ thống bằng PVsyst, bản vẽ kỹ thuật chi tiết</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">4</div>
                    <div class="ps-icon"><i class="fa-light fa-file-invoice-dollar"></i></div>
                    <div class="ps-label">Báo giá</div>
                    <div class="ps-desc">Báo giá minh bạch, tính toán ROI và thời gian hoàn vốn</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">5</div>
                    <div class="ps-icon"><i class="fa-light fa-helmet-safety"></i></div>
                    <div class="ps-label">Thi công</div>
                    <div class="ps-desc">Thi công theo tiêu chuẩn an toàn điện, IEC 60364</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">6</div>
                    <div class="ps-icon"><i class="fa-light fa-clipboard-check"></i></div>
                    <div class="ps-label">Nghiệm thu</div>
                    <div class="ps-desc">Kiểm tra, đo lường, commissioning &amp; bàn giao hồ sơ</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">7</div>
                    <div class="ps-icon"><i class="fa-light fa-wrench"></i></div>
                    <div class="ps-label">O&amp;M</div>
                    <div class="ps-desc">Giám sát vận hành 24/7, bảo trì định kỳ &amp; hỗ trợ bảo hành</div>
                </div>
            </div>
        </section>

        <hr class="energy-divider">

        {{-- ══ FUTURE VISION ══ --}}
        <section class="energy-section py-5" data-aos="fade-up">
            <div class="section-label">Tầm nhìn tương lai</div>
            <h2 class="energy-section-title">Nhà máy năng lượng thông minh</h2>
            <p class="energy-section-desc">Mô hình tích hợp toàn diện — NLP Energy tiên phong kiến tạo hệ sinh thái năng lượng tự cung tự cấp cho doanh nghiệp Việt Nam.</p>
            <div class="vision-flow mt-4" data-aos="fade-up" data-aos-delay="80">
                <div class="vf-node vf-node--solar">
                    <i class="fa-light fa-solar-panel fa-2x mb-2"></i>
                    <div class="vf-label">Solar PV</div>
                    <div class="vf-sub">Sản xuất điện sạch</div>
                </div>
                <div class="vf-arrow">+</div>
                <div class="vf-node vf-node--bess">
                    <i class="fa-light fa-battery-full fa-2x mb-2"></i>
                    <div class="vf-label">BESS</div>
                    <div class="vf-sub">Lưu trữ năng lượng</div>
                </div>
                <div class="vf-arrow">+</div>
                <div class="vf-node vf-node--ems">
                    <i class="fa-light fa-chart-mixed fa-2x mb-2"></i>
                    <div class="vf-label">EMS</div>
                    <div class="vf-sub">Quản lý thông minh</div>
                </div>
                <div class="vf-arrow">+</div>
                <div class="vf-node vf-node--ev">
                    <i class="fa-light fa-charging-station fa-2x mb-2"></i>
                    <div class="vf-label">EV Charging</div>
                    <div class="vf-sub">Sạc xe điện xanh</div>
                </div>
                <div class="vf-arrow">+</div>
                <div class="vf-node vf-node--ai">
                    <i class="fa-light fa-brain-circuit fa-2x mb-2"></i>
                    <div class="vf-label">AI Optimise</div>
                    <div class="vf-sub">Tối ưu hoá AI</div>
                </div>
                <div class="vf-equal">=</div>
                <div class="vf-node vf-node--result">
                    <i class="fa-light fa-building-flag fa-2x mb-2"></i>
                    <div class="vf-label">Smart Energy</div>
                    <div class="vf-sub">Nhà máy năng lượng thông minh</div>
                </div>
            </div>
        </section>

        {{-- ══ CTA ══ --}}
        <section class="energy-cta-section py-5 mb-5" data-aos="fade-up">
            <div class="energy-cta-box">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h3>Nhận tư vấn miễn phí từ NLP Energy</h3>
                        <p>Đội ngũ kỹ sư giàu kinh nghiệm sẵn sàng khảo sát, tư vấn và báo giá hệ thống năng lượng mặt trời phù hợp nhất với nhu cầu của bạn.</p>
                        <div class="energy-cta-features">
                            <span><i class="fa-light fa-check-circle"></i> Khảo sát miễn phí</span>
                            <span><i class="fa-light fa-check-circle"></i> Báo giá trong 24h</span>
                            <span><i class="fa-light fa-check-circle"></i> Tính toán ROI chi tiết</span>
                            <span><i class="fa-light fa-check-circle"></i> Bảo hành 10 năm</span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="{{ route('page', 'contact') }}" class="energy-btn energy-btn--cta">
                            <i class="fa-light fa-phone"></i> Liên hệ tư vấn miễn phí
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>{{-- /.container --}}
</div>{{-- /.energy-page --}}
@endsection

@push('style')
<style>
/* ═══════════════════════════════════════
   NLP ENERGY PAGE
   Primary: #1a4731 | Accent: #2d7a4f | Light: #3aad6e
═══════════════════════════════════════ */
.energy-page { background: #fff; min-height: 80vh; }
.energy-divider { border: none; border-top: 1px solid #d8e8df; margin: 0; }
.section-label {
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: #3aad6e; margin-bottom: 6px;
}
.energy-section-title { font-size: 26px; font-weight: 700; color: #1a4731; margin-bottom: 8px; }
.energy-section-desc { color: #57606a; font-size: 15px; max-width: 680px; }

/* Hero */
.energy-eyebrow {
    display: inline-block; font-size: 12px; font-weight: 700;
    letter-spacing: 1.2px; text-transform: uppercase;
    color: #3aad6e; margin-bottom: 10px;
}
.energy-hero-title { font-size: 40px; font-weight: 800; color: #1a4731; margin-bottom: 4px; }
.energy-hero-subtitle { font-size: 17px; color: #2d7a4f; font-weight: 600; margin-bottom: 16px; }
.energy-hero-desc { font-size: 15px; color: #374151; line-height: 1.8; margin-bottom: 24px; }

.energy-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 24px; border-radius: 8px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    transition: all .2s;
}
.energy-btn--primary { background: #1a4731; color: #fff; border: 2px solid #1a4731; }
.energy-btn--primary:hover { background: #2d7a4f; border-color: #2d7a4f; color: #fff; }
.energy-btn--outline { background: transparent; color: #1a4731; border: 2px solid #1a4731; }
.energy-btn--outline:hover { background: #1a4731; color: #fff; }
.energy-btn--cta { background: #fff; color: #1a4731; border: 2px solid #fff; font-size: 15px; padding: 14px 28px; }
.energy-btn--cta:hover { background: #3aad6e; border-color: #3aad6e; color: #fff; }

/* Stats grid */
.energy-stats-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
}
.energy-stat {
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 12px; padding: 20px 16px; text-align: center;
    display: flex; flex-direction: column; align-items: center; gap: 4px;
}
.energy-stat i { font-size: 24px; color: #2d7a4f; margin-bottom: 6px; }
.estat-num { font-size: 24px; font-weight: 800; color: #1a4731; }
.estat-label { font-size: 12px; color: #57606a; font-weight: 500; text-align: center; }

/* Service cards */
.service-card {
    border: 1px solid #c3ddd0; border-radius: 12px;
    padding: 26px 22px; height: 100%; background: #fff;
    transition: transform .2s, box-shadow .2s;
}
.service-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(26,71,49,.1); }
.service-icon { font-size: 32px; color: #2d7a4f; margin-bottom: 14px; }
.service-title { font-size: 16px; font-weight: 700; color: #1a4731; margin-bottom: 10px; }
.service-desc { font-size: 13.5px; color: #57606a; line-height: 1.7; margin-bottom: 12px; }
.service-list {
    list-style: none; padding: 0; margin: 0;
    font-size: 12.5px; color: #374151;
}
.service-list li { padding: 3px 0; padding-left: 14px; position: relative; }
.service-list li::before {
    content: '•'; position: absolute; left: 0;
    color: #3aad6e; font-weight: 700;
}

/* Customer cards */
.customer-card {
    border: 1px solid #c3ddd0; border-radius: 12px;
    padding: 28px 24px; height: 100%;
    display: flex; align-items: flex-start; gap: 20px;
}
.customer-card--enterprise { background: #f0f7f4; }
.customer-card--residential { background: #fff; }
.customer-icon { font-size: 36px; color: #2d7a4f; flex-shrink: 0; margin-top: 2px; }
.customer-card h5 { font-size: 16px; font-weight: 700; color: #1a4731; margin-bottom: 12px; }
.customer-list { list-style: none; padding: 0; margin: 0; }
.customer-list li {
    font-size: 13.5px; color: #374151; padding: 4px 0;
    display: flex; align-items: center; gap: 8px;
}
.customer-list li i { color: #3aad6e; font-size: 13px; }

/* Process flow */
.process-flow {
    display: flex; flex-wrap: wrap; align-items: flex-start;
    gap: 8px; justify-content: center;
}
.process-step {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    width: 110px; text-align: center;
}
.ps-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: #1a4731; color: #fff;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.ps-icon { font-size: 26px; color: #2d7a4f; }
.ps-label { font-size: 13px; font-weight: 700; color: #1a4731; }
.ps-desc { font-size: 11px; color: #57606a; line-height: 1.5; }
.process-arrow {
    display: flex; align-items: center; padding-top: 30px;
    font-size: 14px; color: #3aad6e;
}

/* Vision flow */
.vision-flow {
    display: flex; flex-wrap: wrap; align-items: center;
    justify-content: center; gap: 12px;
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 16px; padding: 32px 24px;
}
.vf-node {
    display: flex; flex-direction: column; align-items: center;
    text-align: center; padding: 18px 14px; border-radius: 12px;
    min-width: 100px; border: 1px solid #c3ddd0;
}
.vf-node--solar  { background: #dff2e9; color: #2d7a4f; border-color: #2d7a4f; }
.vf-node--bess   { background: #d0eafb; color: #0550ae; border-color: #54aeff; }
.vf-node--ems    { background: #fdf3d0; color: #7d5a00; border-color: #e3a800; }
.vf-node--ev     { background: #ede9ff; color: #5b2bd0; border-color: #7c5cd8; }
.vf-node--ai     { background: #fff0f0; color: #b91c1c; border-color: #fca5a5; }
.vf-node--result { background: #1a4731; color: #fff; border-color: #1a4731; min-width: 120px; }
.vf-label { font-size: 13px; font-weight: 700; margin-bottom: 2px; }
.vf-sub { font-size: 11px; opacity: .8; }
.vf-arrow, .vf-equal {
    font-size: 22px; font-weight: 700; color: #3aad6e;
}
.vf-equal { font-size: 28px; }

/* CTA */
.energy-cta-box {
    background: linear-gradient(135deg, #1a4731 60%, #2d7a4f 100%);
    border-radius: 16px; padding: 44px 40px; color: #fff;
}
.energy-cta-box h3 { font-size: 24px; font-weight: 700; color: #fff; margin-bottom: 10px; }
.energy-cta-box p { font-size: 14.5px; color: #c3ddd0; margin-bottom: 18px; }
.energy-cta-features {
    display: flex; flex-wrap: wrap; gap: 14px;
    font-size: 13px; color: #c3ddd0;
}
.energy-cta-features i { color: #3aad6e; margin-right: 5px; }

@media (max-width: 767px) {
    .energy-hero-title { font-size: 28px; }
    .energy-stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .process-flow { gap: 4px; }
    .process-step { width: 80px; }
    .ps-desc { display: none; }
    .process-arrow { padding-top: 18px; }
    .vf-arrow, .vf-equal { font-size: 16px; }
}
</style>
@endpush
