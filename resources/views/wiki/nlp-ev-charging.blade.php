@extends(template().'layouts.app')
@section('title', 'NLP EV Charging — Hạ tầng sạc xe điện')

@section('content')

@include(template().'partials.banner')

<div class="ev-page">
    <div class="container">

        {{-- ══ INTRO ══ --}}
        <section class="ev-section py-5" data-aos="fade-up">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="ev-eyebrow">Thành viên NLP Group</span>
                    <h1 class="ev-hero-title">NLP EV Charging</h1>
                    <p class="ev-hero-subtitle">Hạ tầng sạc xe điện thông minh — Kết nối tương lai xanh</p>
                    <p class="ev-hero-desc">
                        <strong>NLP EV Charging</strong> là đơn vị thành viên của NLP Group, chuyên xây dựng và vận hành
                        hạ tầng sạc xe điện thông minh tại Việt Nam. Với nền tảng công nghệ CSMS hiện đại và chuẩn
                        giao thức <strong>OCPP 1.6 / 2.0.1</strong>, NLP EV Charging cung cấp giải pháp trọn gói từ
                        trụ sạc AC cho hộ gia đình đến trụ sạc DC tốc độ cao cho trạm dừng nghỉ cao tốc,
                        đồng thời phát triển mô hình nhượng quyền trạm sạc giúp chủ địa điểm tạo doanh thu bền vững.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('page', 'contact') }}" class="ev-btn ev-btn--primary">
                            <i class="fa-light fa-handshake"></i> Hợp tác phát triển trạm sạc
                        </a>
                        <a href="#solutions" class="ev-btn ev-btn--outline">
                            <i class="fa-light fa-list-check"></i> Xem giải pháp
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="ev-info-card">
                        <div class="ev-info-header">
                            <i class="fa-light fa-charging-station fa-2x me-3"></i>
                            <div>
                                <div class="ev-info-title">NLP EV Charging Network</div>
                                <div class="ev-info-sub">Mạng lưới sạc thông minh toàn quốc</div>
                            </div>
                        </div>
                        <div class="ev-info-specs">
                            <div class="ev-spec-row"><span>Trụ sạc AC</span><span>7 kW — 22 kW</span></div>
                            <div class="ev-spec-row"><span>Trụ sạc DC</span><span>30 kW — 120 kW</span></div>
                            <div class="ev-spec-row"><span>Giao thức</span><span>OCPP 1.6 / 2.0.1</span></div>
                            <div class="ev-spec-row"><span>Quản lý</span><span>CSMS Cloud Platform</span></div>
                            <div class="ev-spec-row"><span>Thanh toán</span><span>App · QR · RFID</span></div>
                            <div class="ev-spec-row"><span>Tích hợp</span><span>Solar + BESS + EV</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="ev-divider">

        {{-- ══ SOLUTIONS ══ --}}
        <section class="ev-section py-5" id="solutions" data-aos="fade-up">
            <div class="section-label">Danh mục sản phẩm & dịch vụ</div>
            <h2 class="ev-section-title">Giải pháp NLP EV Charging</h2>
            <p class="ev-section-desc">Từ trụ sạc gia đình đến hạ tầng trạm sạc thương mại — giải pháp toàn diện cho mọi nhu cầu.</p>
            <div class="row g-4 mt-2">

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="ev-solution-card">
                        <div class="ev-sol-icon ev-sol-icon--ac">
                            <i class="fa-light fa-plug"></i>
                        </div>
                        <h4 class="ev-sol-title">Trụ sạc AC</h4>
                        <p class="ev-sol-desc">Giải pháp sạc tốc độ thường cho môi trường dân dụng và thương mại. Công suất 7–22 kW, phù hợp sạc qua đêm hoặc sạc trong giờ làm việc.</p>
                        <ul class="ev-sol-list">
                            <li>Nhà riêng &amp; chung cư</li>
                            <li>Văn phòng &amp; toà nhà</li>
                            <li>Bãi đỗ xe thương mại</li>
                            <li>Khách sạn &amp; resort</li>
                        </ul>
                        <div class="ev-sol-tags">
                            <span>7 kW</span><span>11 kW</span><span>22 kW</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="ev-solution-card">
                        <div class="ev-sol-icon ev-sol-icon--dc">
                            <i class="fa-light fa-bolt"></i>
                        </div>
                        <h4 class="ev-sol-title">Trụ sạc DC (Sạc nhanh)</h4>
                        <p class="ev-sol-desc">Trụ sạc nhanh DC cho trạm dừng nghỉ, trung tâm thương mại và khu công nghiệp. Sạc đầy trong 20–40 phút, hỗ trợ đa chuẩn CCS2, CHAdeMO, GB/T.</p>
                        <ul class="ev-sol-list">
                            <li>Trạm dừng nghỉ cao tốc</li>
                            <li>Trung tâm thương mại</li>
                            <li>Khu công nghiệp</li>
                            <li>Depot xe điện thương mại</li>
                        </ul>
                        <div class="ev-sol-tags">
                            <span>30 kW</span><span>60 kW</span><span>120 kW</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="ev-solution-card">
                        <div class="ev-sol-icon ev-sol-icon--csms">
                            <i class="fa-light fa-server"></i>
                        </div>
                        <h4 class="ev-sol-title">CSMS — Quản lý trạm sạc</h4>
                        <p class="ev-sol-desc">Nền tảng Charge Station Management System trên cloud, giám sát toàn bộ mạng lưới trạm sạc theo thời gian thực, quản lý thanh toán và báo cáo doanh thu.</p>
                        <ul class="ev-sol-list">
                            <li>Dashboard thời gian thực</li>
                            <li>Quản lý người dùng &amp; thẻ RFID</li>
                            <li>Báo cáo doanh thu tự động</li>
                            <li>Cảnh báo sự cố tức thì</li>
                        </ul>
                        <div class="ev-sol-tags">
                            <span>Cloud</span><span>SaaS</span><span>API</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="ev-solution-card">
                        <div class="ev-sol-icon ev-sol-icon--ocpp">
                            <i class="fa-light fa-network-wired"></i>
                        </div>
                        <h4 class="ev-sol-title">Chuẩn OCPP 1.6 / 2.0.1</h4>
                        <p class="ev-sol-desc">Hỗ trợ giao thức OCPP mở, đảm bảo khả năng tích hợp với mọi trụ sạc từ các thương hiệu khác nhau và kết nối vào mạng lưới sạc quốc gia / quốc tế.</p>
                        <ul class="ev-sol-list">
                            <li>OCPP 1.6 JSON / SOAP</li>
                            <li>OCPP 2.0.1 (ISO 15118)</li>
                            <li>Tích hợp Plug &amp; Charge</li>
                            <li>Smart Charging profile</li>
                        </ul>
                        <div class="ev-sol-tags">
                            <span>OCPP 1.6</span><span>OCPP 2.0.1</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="ev-solution-card">
                        <div class="ev-sol-icon ev-sol-icon--app">
                            <i class="fa-light fa-mobile-screen"></i>
                        </div>
                        <h4 class="ev-sol-title">Ứng dụng sạc xe điện</h4>
                        <p class="ev-sol-desc">Ứng dụng di động cho người dùng cuối: tìm trạm sạc gần nhất, theo dõi tiến trình sạc, quản lý ví điện tử và lịch sử giao dịch đơn giản, trực quan.</p>
                        <ul class="ev-sol-list">
                            <li>Tìm trạm sạc bản đồ</li>
                            <li>Đặt chỗ trạm sạc trước</li>
                            <li>Thanh toán QR / Ví điện tử</li>
                            <li>Theo dõi CO₂ tiết kiệm được</li>
                        </ul>
                        <div class="ev-sol-tags">
                            <span>iOS</span><span>Android</span><span>PWA</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="ev-solution-card">
                        <div class="ev-sol-icon ev-sol-icon--franchise">
                            <i class="fa-light fa-handshake"></i>
                        </div>
                        <h4 class="ev-sol-title">Nhượng quyền trạm sạc</h4>
                        <p class="ev-sol-desc">Mô hình đối tác đầu tư: chủ địa điểm cung cấp mặt bằng, NLP EV cung cấp thiết bị, lắp đặt và vận hành. Chia sẻ doanh thu minh bạch qua nền tảng CSMS.</p>
                        <ul class="ev-sol-list">
                            <li>Đầu tư ban đầu thấp</li>
                            <li>NLP vận hành &amp; bảo trì</li>
                            <li>Chia sẻ doanh thu %</li>
                            <li>Hỗ trợ marketing &amp; thương hiệu</li>
                        </ul>
                        <div class="ev-sol-tags">
                            <span>Nhượng quyền</span><span>Revenue share</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <hr class="ev-divider">

        {{-- ══ BUSINESS MODEL ══ --}}
        <section class="ev-section py-5" data-aos="fade-up">
            <div class="section-label">Mô hình kinh doanh</div>
            <h2 class="ev-section-title">Chủ địa điểm + NLP EV = Mạng lưới sạc</h2>
            <p class="ev-section-desc">Mô hình hợp tác linh hoạt — cùng xây dựng mạng lưới sạc xe điện phủ rộng toàn quốc.</p>
            <div class="biz-model mt-4">
                <div class="row g-0 align-items-stretch">
                    <div class="col-md-4">
                        <div class="biz-col biz-col--partner">
                            <div class="biz-icon"><i class="fa-light fa-building fa-2x"></i></div>
                            <h5>Chủ địa điểm</h5>
                            <ul class="biz-list">
                                <li>Cung cấp mặt bằng</li>
                                <li>Nguồn điện đầu vào</li>
                                <li>Giám sát an ninh</li>
                            </ul>
                            <div class="biz-value">Lợi ích: Thêm doanh thu, thu hút khách hàng</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="biz-col biz-col--nlp">
                            <div class="biz-icon"><i class="fa-light fa-charging-station fa-2x"></i></div>
                            <h5>NLP EV Charging</h5>
                            <ul class="biz-list">
                                <li>Thiết bị trụ sạc</li>
                                <li>Lắp đặt &amp; cấu hình</li>
                                <li>Nền tảng CSMS</li>
                                <li>Vận hành &amp; bảo trì</li>
                                <li>Thương hiệu &amp; marketing</li>
                            </ul>
                            <div class="biz-value">Lợi ích: Mở rộng mạng lưới nhanh chóng</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="biz-col biz-col--result">
                            <div class="biz-icon"><i class="fa-light fa-map-location-dot fa-2x"></i></div>
                            <h5>Kết quả</h5>
                            <ul class="biz-list">
                                <li>Mạng lưới sạc phủ rộng</li>
                                <li>Doanh thu chia sẻ minh bạch</li>
                                <li>Trải nghiệm người dùng tốt nhất</li>
                                <li>Hạ tầng EV xanh cho Việt Nam</li>
                            </ul>
                            <div class="biz-value">Mục tiêu: 500+ trạm sạc vào 2027</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="ev-divider">

        {{-- ══ PROCESS ══ --}}
        <section class="ev-section py-5" data-aos="fade-up">
            <div class="section-label">Quy trình triển khai</div>
            <h2 class="ev-section-title">8 bước triển khai trạm sạc</h2>
            <div class="ev-process-grid mt-4">
                <div class="ev-ps">
                    <div class="ev-ps-num">1</div>
                    <i class="fa-light fa-magnifying-glass-location ev-ps-icon"></i>
                    <div class="ev-ps-label">Khảo sát</div>
                    <div class="ev-ps-desc">Khảo sát địa điểm, đánh giá hạ tầng điện &amp; không gian lắp đặt</div>
                </div>
                <div class="ev-ps">
                    <div class="ev-ps-num">2</div>
                    <i class="fa-light fa-chart-bar ev-ps-icon"></i>
                    <div class="ev-ps-label">Phân tích</div>
                    <div class="ev-ps-desc">Phân tích nhu cầu sạc, lưu lượng xe, tính toán ROI cho chủ đầu tư</div>
                </div>
                <div class="ev-ps">
                    <div class="ev-ps-num">3</div>
                    <i class="fa-light fa-drafting-compass ev-ps-icon"></i>
                    <div class="ev-ps-label">Thiết kế</div>
                    <div class="ev-ps-desc">Thiết kế bố cục trạm sạc, hệ thống điện, kết nối mạng &amp; CSMS</div>
                </div>
                <div class="ev-ps">
                    <div class="ev-ps-num">4</div>
                    <i class="fa-light fa-box ev-ps-icon"></i>
                    <div class="ev-ps-label">Thiết bị</div>
                    <div class="ev-ps-desc">Cung cấp trụ sạc AC/DC chính hãng, tủ điện, cáp &amp; phụ kiện</div>
                </div>
                <div class="ev-ps">
                    <div class="ev-ps-num">5</div>
                    <i class="fa-light fa-helmet-safety ev-ps-icon"></i>
                    <div class="ev-ps-label">Lắp đặt</div>
                    <div class="ev-ps-desc">Thi công lắp đặt theo tiêu chuẩn IEC 61851, an toàn điện cấp 1</div>
                </div>
                <div class="ev-ps">
                    <div class="ev-ps-num">6</div>
                    <i class="fa-light fa-gears ev-ps-icon"></i>
                    <div class="ev-ps-label">Cấu hình</div>
                    <div class="ev-ps-desc">Cấu hình OCPP, kết nối CSMS, tích hợp thanh toán &amp; ứng dụng</div>
                </div>
                <div class="ev-ps">
                    <div class="ev-ps-num">7</div>
                    <i class="fa-light fa-play-circle ev-ps-icon"></i>
                    <div class="ev-ps-label">Vận hành</div>
                    <div class="ev-ps-desc">Khai trương trạm sạc, đào tạo nhân viên địa điểm, go-live</div>
                </div>
                <div class="ev-ps">
                    <div class="ev-ps-num">8</div>
                    <i class="fa-light fa-wrench ev-ps-icon"></i>
                    <div class="ev-ps-label">Bảo trì</div>
                    <div class="ev-ps-desc">Giám sát 24/7 qua CSMS, bảo trì định kỳ, hỗ trợ sự cố &lt; 4h</div>
                </div>
            </div>
        </section>

        <hr class="ev-divider">

        {{-- ══ INTEGRATION WITH NLP ENERGY ══ --}}
        <section class="ev-section py-5" data-aos="fade-up">
            <div class="section-label">Hệ sinh thái tích hợp</div>
            <h2 class="ev-section-title">Tích hợp với NLP Energy</h2>
            <p class="ev-section-desc">NLP EV Charging không hoạt động độc lập — đây là mắt xích quan trọng trong hệ sinh thái năng lượng xanh NLP Group.</p>
            <div class="integration-flow mt-4">
                <div class="int-node int-node--solar">
                    <i class="fa-light fa-solar-panel fa-2x mb-2"></i>
                    <div class="int-title">Solar PV</div>
                    <div class="int-sub">NLP Energy sản xuất điện sạch từ mặt trời</div>
                </div>
                <div class="int-arrow">
                    <i class="fa-light fa-arrow-right"></i>
                    <span>Điện sạch</span>
                </div>
                <div class="int-node int-node--bess">
                    <i class="fa-light fa-battery-full fa-2x mb-2"></i>
                    <div class="int-title">BESS</div>
                    <div class="int-sub">Lưu trữ năng lượng để sạc xe vào giờ cao điểm</div>
                </div>
                <div class="int-arrow">
                    <i class="fa-light fa-arrow-right"></i>
                    <span>Điện dự trữ</span>
                </div>
                <div class="int-node int-node--ev">
                    <i class="fa-light fa-charging-station fa-2x mb-2"></i>
                    <div class="int-title">Trạm sạc EV</div>
                    <div class="int-sub">NLP EV cung cấp điện xanh cho xe điện</div>
                </div>
                <div class="int-arrow">
                    <i class="fa-light fa-arrow-right"></i>
                    <span>Năng lượng xanh</span>
                </div>
                <div class="int-node int-node--vehicle">
                    <i class="fa-light fa-car-side fa-2x mb-2"></i>
                    <div class="int-title">Phương tiện xanh</div>
                    <div class="int-sub">100% điện từ năng lượng tái tạo</div>
                </div>
            </div>
            <div class="integration-note mt-4">
                <i class="fa-light fa-circle-info"></i>
                Khi tích hợp Solar + BESS + EV Charging, chi phí điện sạc giảm đến <strong>60%</strong> so với điện lưới thương mại,
                đồng thời toàn bộ điện sạc đến từ nguồn tái tạo — đạt tiêu chuẩn <strong>Green Charging</strong> quốc tế.
            </div>
        </section>

        {{-- ══ CTA ══ --}}
        <section class="ev-cta-section py-5 mb-5" data-aos="fade-up">
            <div class="ev-cta-box">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h3>Hợp tác phát triển trạm sạc với NLP EV</h3>
                        <p>Bạn có mặt bằng phù hợp? Hãy để NLP EV Charging biến địa điểm của bạn thành trạm sạc xe điện sinh lời. Chúng tôi đảm nhận toàn bộ — từ đầu tư thiết bị đến vận hành bảo trì.</p>
                        <div class="ev-cta-features">
                            <span><i class="fa-light fa-check-circle"></i> Không cần đầu tư thiết bị</span>
                            <span><i class="fa-light fa-check-circle"></i> NLP vận hành &amp; bảo trì</span>
                            <span><i class="fa-light fa-check-circle"></i> Chia sẻ doanh thu minh bạch</span>
                            <span><i class="fa-light fa-check-circle"></i> Hỗ trợ marketing thương hiệu</span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="{{ route('page', 'contact') }}" class="ev-btn ev-btn--cta">
                            <i class="fa-light fa-handshake"></i> Đăng ký hợp tác ngay
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>{{-- /.container --}}
</div>{{-- /.ev-page --}}
@endsection

@push('style')
<style>
/* ═══════════════════════════════════════
   NLP EV CHARGING PAGE
   Primary: #1a4731 | Accent: #2d7a4f | Light: #3aad6e
═══════════════════════════════════════ */
.ev-page { background: #fff; min-height: 80vh; }
.ev-divider { border: none; border-top: 1px solid #d8e8df; margin: 0; }
.section-label {
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: #3aad6e; margin-bottom: 6px;
}
.ev-section-title { font-size: 26px; font-weight: 700; color: #1a4731; margin-bottom: 8px; }
.ev-section-desc { color: #57606a; font-size: 15px; max-width: 680px; }

/* Hero */
.ev-eyebrow {
    display: inline-block; font-size: 12px; font-weight: 700;
    letter-spacing: 1.2px; text-transform: uppercase;
    color: #3aad6e; margin-bottom: 10px;
}
.ev-hero-title { font-size: 40px; font-weight: 800; color: #1a4731; margin-bottom: 4px; }
.ev-hero-subtitle { font-size: 17px; color: #2d7a4f; font-weight: 600; margin-bottom: 16px; }
.ev-hero-desc { font-size: 15px; color: #374151; line-height: 1.8; margin-bottom: 24px; }

.ev-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 24px; border-radius: 8px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    transition: all .2s;
}
.ev-btn--primary { background: #1a4731; color: #fff; border: 2px solid #1a4731; }
.ev-btn--primary:hover { background: #2d7a4f; border-color: #2d7a4f; color: #fff; }
.ev-btn--outline { background: transparent; color: #1a4731; border: 2px solid #1a4731; }
.ev-btn--outline:hover { background: #1a4731; color: #fff; }
.ev-btn--cta { background: #fff; color: #1a4731; border: 2px solid #fff; font-size: 15px; padding: 14px 28px; }
.ev-btn--cta:hover { background: #3aad6e; border-color: #3aad6e; color: #fff; }

/* Info card */
.ev-info-card {
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 14px; overflow: hidden;
}
.ev-info-header {
    background: #1a4731; color: #fff;
    padding: 20px 22px; display: flex; align-items: center;
}
.ev-info-header i { color: #3aad6e; flex-shrink: 0; }
.ev-info-title { font-size: 15px; font-weight: 700; }
.ev-info-sub { font-size: 12px; color: #c3ddd0; margin-top: 2px; }
.ev-info-specs { padding: 16px 22px; }
.ev-spec-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 8px 0; border-bottom: 1px solid #d8e8df;
    font-size: 13.5px;
}
.ev-spec-row:last-child { border-bottom: none; }
.ev-spec-row span:first-child { color: #57606a; font-weight: 500; }
.ev-spec-row span:last-child { color: #1a4731; font-weight: 700; }

/* Solution cards */
.ev-solution-card {
    border: 1px solid #c3ddd0; border-radius: 12px;
    padding: 26px 22px; height: 100%; background: #fff;
    transition: transform .2s, box-shadow .2s;
}
.ev-solution-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(26,71,49,.1); }
.ev-sol-icon {
    font-size: 32px; margin-bottom: 14px;
    width: 60px; height: 60px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
}
.ev-sol-icon--ac       { background: #dff2e9; color: #2d7a4f; }
.ev-sol-icon--dc       { background: #d0eafb; color: #0550ae; }
.ev-sol-icon--csms     { background: #fdf3d0; color: #7d5a00; }
.ev-sol-icon--ocpp     { background: #ede9ff; color: #5b2bd0; }
.ev-sol-icon--app      { background: #fff0f0; color: #b91c1c; }
.ev-sol-icon--franchise { background: #dff2e9; color: #1a4731; }
.ev-sol-title { font-size: 16px; font-weight: 700; color: #1a4731; margin-bottom: 10px; }
.ev-sol-desc { font-size: 13.5px; color: #57606a; line-height: 1.7; margin-bottom: 12px; }
.ev-sol-list {
    list-style: none; padding: 0; margin: 0 0 14px;
    font-size: 12.5px; color: #374151;
}
.ev-sol-list li { padding: 3px 0 3px 14px; position: relative; }
.ev-sol-list li::before { content: '•'; position: absolute; left: 0; color: #3aad6e; font-weight: 700; }
.ev-sol-tags { display: flex; flex-wrap: wrap; gap: 6px; }
.ev-sol-tags span {
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 10px; padding: 2px 9px;
    font-size: 11.5px; font-weight: 600; color: #2d7a4f;
}

/* Business model */
.biz-model { border-radius: 14px; overflow: hidden; border: 1px solid #c3ddd0; }
.biz-col {
    padding: 30px 24px; height: 100%;
}
.biz-col--partner { background: #f0f7f4; border-right: 1px solid #c3ddd0; }
.biz-col--nlp { background: #1a4731; color: #fff; border-right: 1px solid rgba(255,255,255,.15); }
.biz-col--result { background: #2d7a4f; color: #fff; }
.biz-icon { font-size: 32px; margin-bottom: 12px; }
.biz-col--partner .biz-icon { color: #2d7a4f; }
.biz-col--nlp .biz-icon { color: #3aad6e; }
.biz-col--result .biz-icon { color: #c3ddd0; }
.biz-col h5 { font-size: 16px; font-weight: 700; margin-bottom: 12px; }
.biz-col--partner h5 { color: #1a4731; }
.biz-col--nlp h5 { color: #fff; }
.biz-col--result h5 { color: #fff; }
.biz-list { list-style: none; padding: 0; margin: 0 0 16px; }
.biz-list li { padding: 4px 0; font-size: 13px; }
.biz-col--partner .biz-list li { color: #374151; }
.biz-list li::before { content: '✓ '; }
.biz-col--nlp .biz-list li { color: #c3ddd0; }
.biz-col--result .biz-list li { color: #c3ddd0; }
.biz-value {
    font-size: 12px; font-weight: 700; padding: 8px 12px;
    border-radius: 6px; margin-top: 8px;
}
.biz-col--partner .biz-value { background: #c3ddd0; color: #1a4731; }
.biz-col--nlp .biz-value { background: rgba(58,173,110,.25); color: #3aad6e; }
.biz-col--result .biz-value { background: rgba(255,255,255,.15); color: #fff; }

/* Process grid */
.ev-process-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
.ev-ps {
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 10px; padding: 18px 14px; text-align: center;
    position: relative;
}
.ev-ps-num {
    position: absolute; top: -10px; left: 50%; transform: translateX(-50%);
    width: 24px; height: 24px; border-radius: 50%;
    background: #1a4731; color: #fff;
    font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.ev-ps-icon { font-size: 26px; color: #2d7a4f; margin: 8px 0 6px; }
.ev-ps-label { font-size: 13px; font-weight: 700; color: #1a4731; margin-bottom: 6px; }
.ev-ps-desc { font-size: 11.5px; color: #57606a; line-height: 1.5; }

/* Integration flow */
.integration-flow {
    display: flex; flex-wrap: wrap; align-items: center;
    justify-content: center; gap: 8px;
}
.int-node {
    display: flex; flex-direction: column; align-items: center;
    text-align: center; padding: 18px 16px; border-radius: 12px;
    min-width: 130px; border: 1px solid;
}
.int-node--solar  { background: #dff2e9; color: #2d7a4f; border-color: #2d7a4f; }
.int-node--bess   { background: #d0eafb; color: #0550ae; border-color: #54aeff; }
.int-node--ev     { background: #1a4731; color: #fff; border-color: #1a4731; }
.int-node--vehicle { background: #3aad6e; color: #fff; border-color: #3aad6e; }
.int-title { font-size: 13px; font-weight: 700; margin-bottom: 4px; }
.int-sub { font-size: 11px; opacity: .8; line-height: 1.4; max-width: 120px; }
.int-arrow {
    display: flex; flex-direction: column; align-items: center; gap: 4px;
    color: #3aad6e; font-size: 18px;
}
.int-arrow span { font-size: 10px; color: #57606a; font-weight: 600; white-space: nowrap; }
.integration-note {
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-left: 4px solid #3aad6e;
    border-radius: 8px; padding: 14px 18px;
    font-size: 13.5px; color: #374151;
    display: flex; gap: 10px; align-items: flex-start;
}
.integration-note i { color: #3aad6e; font-size: 16px; flex-shrink: 0; margin-top: 2px; }

/* CTA */
.ev-cta-box {
    background: linear-gradient(135deg, #1a4731 60%, #2d7a4f 100%);
    border-radius: 16px; padding: 44px 40px; color: #fff;
}
.ev-cta-box h3 { font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 10px; }
.ev-cta-box p { font-size: 14px; color: #c3ddd0; margin-bottom: 18px; }
.ev-cta-features {
    display: flex; flex-wrap: wrap; gap: 14px;
    font-size: 13px; color: #c3ddd0;
}
.ev-cta-features i { color: #3aad6e; margin-right: 5px; }

@media (max-width: 991px) {
    .ev-process-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 767px) {
    .ev-hero-title { font-size: 28px; }
    .ev-process-grid { grid-template-columns: repeat(2, 1fr); }
    .int-arrow { display: none; }
    .biz-col--partner { border-right: none; border-bottom: 1px solid #c3ddd0; }
    .biz-col--nlp { border-right: none; border-bottom: 1px solid rgba(255,255,255,.15); }
}
</style>
@endpush
