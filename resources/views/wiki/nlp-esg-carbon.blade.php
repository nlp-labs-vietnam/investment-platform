@extends(template().'layouts.app')
@section('title', 'NLP ESG & Carbon — Phát triển bền vững')

@section('content')

@include(template().'partials.banner')

<div class="esg-page">
    <div class="container">

        {{-- ══ INTRO ══ --}}
        <section class="esg-section py-5" data-aos="fade-up">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="esg-eyebrow">Thành viên NLP Group</span>
                    <h1 class="esg-hero-title">NLP ESG & Carbon</h1>
                    <p class="esg-hero-subtitle">Phát triển bền vững — Net Zero & Tín chỉ Carbon</p>
                    <p class="esg-hero-desc">
                        <strong>NLP ESG & Carbon</strong> là đơn vị thành viên NLP Group, chuyên tư vấn và triển
                        khai các giải pháp <strong>phát triển bền vững</strong>, đạt mục tiêu <strong>Net Zero</strong>,
                        phát triển <strong>tín chỉ carbon</strong> và xây dựng hệ thống <strong>MRV số</strong>
                        (Monitoring, Reporting, Verification) cho doanh nghiệp, tổ chức và dự án tại Việt Nam.
                        Chúng tôi kết nối khoa học khí hậu với hành động doanh nghiệp — biến cam kết ESG
                        thành giá trị kinh tế thực.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="#cta" class="esg-btn esg-btn--primary">
                            <i class="fa-light fa-leaf"></i> Đánh giá ESG miễn phí
                        </a>
                        <a href="#services" class="esg-btn esg-btn--outline">
                            <i class="fa-light fa-list-check"></i> Xem dịch vụ
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="esg-stats-grid">
                        <div class="esg-stat">
                            <i class="fa-light fa-earth-asia"></i>
                            <span class="estat-num">Net Zero</span>
                            <span class="estat-label">Cam kết phát thải ròng bằng 0</span>
                        </div>
                        <div class="esg-stat">
                            <i class="fa-light fa-certificate"></i>
                            <span class="estat-num">Carbon</span>
                            <span class="estat-label">Tín chỉ carbon quốc tế</span>
                        </div>
                        <div class="esg-stat">
                            <i class="fa-light fa-chart-mixed"></i>
                            <span class="estat-num">MRV</span>
                            <span class="estat-label">Giám sát & Xác minh số</span>
                        </div>
                        <div class="esg-stat">
                            <i class="fa-light fa-file-chart-column"></i>
                            <span class="estat-num">ESG</span>
                            <span class="estat-label">Báo cáo bền vững chuẩn quốc tế</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="esg-divider">

        {{-- ══ SERVICES ══ --}}
        <section class="esg-section py-5" id="services" data-aos="fade-up">
            <div class="section-label">Danh mục dịch vụ</div>
            <h2 class="esg-section-title">Dịch vụ ESG & Carbon</h2>
            <p class="esg-section-desc">Giải pháp toàn diện từ rừng đến carbon — hướng tới tương lai bền vững.</p>
            <div class="row g-4 mt-2">

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="esg-card">
                        <div class="esg-card-icon"><i class="fa-light fa-tree"></i></div>
                        <h4 class="esg-card-title">Phát triển rừng & Trồng rừng</h4>
                        <p class="esg-card-desc">Tư vấn và triển khai dự án trồng rừng, tái sinh rừng tự nhiên, quản lý rừng bền vững theo tiêu chuẩn REDD+ và VCS.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="esg-card">
                        <div class="esg-card-icon"><i class="fa-light fa-seedling"></i></div>
                        <h4 class="esg-card-title">Nông nghiệp bền vững & Công nghệ cao</h4>
                        <p class="esg-card-desc">Ứng dụng công nghệ cao vào nông nghiệp giảm phát thải: canh tác thông minh, quản lý nước, giảm sử dụng phân bón hóa học.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="esg-card">
                        <div class="esg-card-icon"><i class="fa-light fa-leaf"></i></div>
                        <h4 class="esg-card-title">Dự án tín chỉ carbon</h4>
                        <p class="esg-card-desc">Phát triển dự án carbon offset đủ điều kiện đăng ký trên thị trường carbon tự nguyện (VCS, Gold Standard) và tuân thủ.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="esg-card">
                        <div class="esg-card-icon"><i class="fa-light fa-chart-pie"></i></div>
                        <h4 class="esg-card-title">Đánh giá ESG doanh nghiệp</h4>
                        <p class="esg-card-desc">Kiểm kê khí nhà kính theo GHG Protocol, đánh giá hiệu suất ESG và lộ trình cải tiến Scope 1, 2, 3 toàn diện.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="esg-card">
                        <div class="esg-card-icon"><i class="fa-light fa-earth-asia"></i></div>
                        <h4 class="esg-card-title">Giải pháp Net Zero</h4>
                        <p class="esg-card-desc">Xây dựng lộ trình Net Zero 2050, kết hợp giảm phát thải tại nguồn (năng lượng tái tạo) và bù đắp carbon (rừng, tín chỉ).</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="esg-card">
                        <div class="esg-card-icon"><i class="fa-light fa-file-chart-column"></i></div>
                        <h4 class="esg-card-title">Báo cáo phát triển bền vững MRV</h4>
                        <p class="esg-card-desc">Xây dựng hệ thống MRV số hóa, lập báo cáo ESG theo chuẩn GRI, TCFD, CDP và yêu cầu của nhà đầu tư quốc tế.</p>
                    </div>
                </div>

            </div>
        </section>

        <hr class="esg-divider">

        {{-- ══ CO2 CALCULATION ══ --}}
        <section class="esg-section py-5" data-aos="fade-up">
            <div class="section-label">Tính toán CO₂</div>
            <h2 class="esg-section-title">Cách NLP tính lượng CO₂ giảm phát thải</h2>
            <p class="esg-section-desc">Phương pháp chuẩn hóa theo hệ số phát thải lưới điện Việt Nam — Bộ Công Thương 2022.</p>
            <div class="row g-4 mt-2">
                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="50">
                    <div class="co2-method-box">
                        <h5 class="co2-method-title"><i class="fa-light fa-solar-panel"></i> Điện mặt trời (Solar PV)</h5>
                        <p class="co2-method-desc">Mỗi kWh điện sản xuất từ hệ thống điện mặt trời thay thế trực tiếp cho điện từ lưới quốc gia, qua đó giảm lượng CO₂ phát thải tương ứng với hệ số phát thải của lưới.</p>
                        <div class="co2-formula-box">
                            <div class="co2-formula-label">Công thức tính</div>
                            <div class="co2-formula">CO₂ giảm = kWh × 0.4768 / 1000 <span class="co2-unit">(tCO₂)</span></div>
                            <div class="co2-example">
                                <strong>Ví dụ:</strong> Hệ thống 10 kWp sản xuất ~14,000 kWh/năm<br>
                                → CO₂ giảm = 14,000 × 0.4768 / 1000 = <strong>6.68 tCO₂/năm</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="50">
                    <div class="co2-method-box">
                        <h5 class="co2-method-title"><i class="fa-light fa-charging-station"></i> Trạm sạc xe điện (EV Charging)</h5>
                        <p class="co2-method-desc">Xe điện sạc từ lưới điện Việt Nam tạo ra phát thải gián tiếp. Khi trạm sạc được cấp điện từ năng lượng tái tạo, lượng CO₂ tránh phát thải được tính theo kWh sạc thực tế.</p>
                        <div class="co2-formula-box">
                            <div class="co2-formula-label">Công thức tính</div>
                            <div class="co2-formula">CO₂ tránh = kWh sạc × 0.4768 / 1000 <span class="co2-unit">(tCO₂)</span></div>
                            <div class="co2-example">
                                <strong>Ví dụ:</strong> Trạm sạc phục vụ 50 xe/ngày × 20 kWh<br>
                                = 1,000 kWh/ngày → <strong>173.8 tCO₂/năm</strong> tránh phát thải
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="co2-grid-factor-box">
                        <div class="cgf-icon"><i class="fa-light fa-info-circle"></i></div>
                        <div>
                            <strong>Hệ số phát thải lưới điện Việt Nam: 0.4768 tCO₂/MWh</strong><br>
                            <span>Nguồn: Bộ Công Thương Việt Nam (MOIT 2022) — Emission Factor for Vietnam National Grid. Cập nhật định kỳ theo thực trạng cơ cấu nguồn điện quốc gia.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="esg-divider">

        {{-- ══ SDG ALIGNMENT ══ --}}
        <section class="esg-section py-5" data-aos="fade-up">
            <div class="section-label">Mục tiêu phát triển bền vững</div>
            <h2 class="esg-section-title">Đóng góp cho Mục tiêu SDG của LHQ</h2>
            <p class="esg-section-desc">Các hoạt động của NLP ESG & Carbon trực tiếp đóng góp vào 5 SDG trọng tâm của Liên Hợp Quốc.</p>
            <div class="row g-4 mt-2">

                <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="50">
                    <div class="sdg-card sdg-7">
                        <div class="sdg-num">SDG 7</div>
                        <div class="sdg-title">Năng lượng sạch & giá rẻ</div>
                        <div class="sdg-desc">Thúc đẩy năng lượng tái tạo, tăng tỷ lệ điện sạch trong cơ cấu năng lượng quốc gia</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="sdg-card sdg-11">
                        <div class="sdg-num">SDG 11</div>
                        <div class="sdg-title">Thành phố bền vững</div>
                        <div class="sdg-desc">Hạ tầng giao thông xanh, trạm sạc EV và giải pháp năng lượng đô thị thông minh</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="150">
                    <div class="sdg-card sdg-13">
                        <div class="sdg-num">SDG 13</div>
                        <div class="sdg-title">Hành động vì khí hậu</div>
                        <div class="sdg-desc">Giảm phát thải CO₂ qua năng lượng tái tạo, Net Zero và tín chỉ carbon chất lượng cao</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="200">
                    <div class="sdg-card sdg-15">
                        <div class="sdg-num">SDG 15</div>
                        <div class="sdg-title">Tài nguyên trên cạn</div>
                        <div class="sdg-desc">Bảo vệ và phát triển rừng, đa dạng sinh học và quản lý đất bền vững</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="250">
                    <div class="sdg-card sdg-17">
                        <div class="sdg-num">SDG 17</div>
                        <div class="sdg-title">Đối tác toàn cầu</div>
                        <div class="sdg-desc">Hợp tác GIZ, UNDP, USAID để huy động nguồn lực tài chính khí hậu quốc tế</div>
                    </div>
                </div>

            </div>
        </section>

        <hr class="esg-divider">

        {{-- ══ PARTNERS ══ --}}
        <section class="esg-section py-5" data-aos="fade-up">
            <div class="section-label">Đối tác hợp tác</div>
            <h2 class="esg-section-title">Tổ chức hợp tác chiến lược</h2>
            <div class="row g-4 mt-2">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="partner-org-card">
                        <div class="poc-icon"><i class="fa-light fa-globe-europe"></i></div>
                        <h5 class="poc-name">GIZ</h5>
                        <p class="poc-desc">Deutsche Gesellschaft für Internationale Zusammenarbeit — hỗ trợ kỹ thuật và tài chính cho dự án khí hậu tại Việt Nam</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="partner-org-card">
                        <div class="poc-icon"><i class="fa-light fa-building-columns"></i></div>
                        <h5 class="poc-name">UNDP</h5>
                        <p class="poc-desc">United Nations Development Programme — thúc đẩy SDGs, hỗ trợ tài chính carbon và phát triển bền vững tại Việt Nam</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="partner-org-card">
                        <div class="poc-icon"><i class="fa-light fa-sun-plant-wilt"></i></div>
                        <h5 class="poc-name">USAID Climate Solutions</h5>
                        <p class="poc-desc">Hỗ trợ chuyển đổi năng lượng sạch, thích ứng biến đổi khí hậu và tăng cường năng lực quản trị ESG tại Đông Nam Á</p>
                    </div>
                </div>
            </div>
        </section>

        <hr class="esg-divider">

        {{-- ══ PROCESS ══ --}}
        <section class="esg-section py-5" data-aos="fade-up">
            <div class="section-label">Quy trình ESG</div>
            <h2 class="esg-section-title">5 bước triển khai ESG</h2>
            <p class="esg-section-desc">Quy trình chuẩn hóa theo tiêu chuẩn quốc tế — minh bạch, có thể đo lường và sẵn sàng xác minh.</p>
            <div class="esg-process-flow mt-4">
                <div class="esg-process-step" data-aos="fade-up" data-aos-delay="50">
                    <div class="eps-num">1</div>
                    <div class="eps-icon"><i class="fa-light fa-magnifying-glass-chart"></i></div>
                    <div class="eps-label">Assessment</div>
                    <div class="eps-desc">Đánh giá hiện trạng phát thải, rủi ro ESG và cơ hội cải thiện</div>
                </div>
                <div class="esg-process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="esg-process-step" data-aos="fade-up" data-aos-delay="100">
                    <div class="eps-num">2</div>
                    <div class="eps-icon"><i class="fa-light fa-map"></i></div>
                    <div class="eps-label">Strategy</div>
                    <div class="eps-desc">Xây dựng chiến lược Net Zero và lộ trình giảm phát thải theo mốc 2030, 2050</div>
                </div>
                <div class="esg-process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="esg-process-step" data-aos="fade-up" data-aos-delay="150">
                    <div class="eps-num">3</div>
                    <div class="eps-icon"><i class="fa-light fa-gears"></i></div>
                    <div class="eps-label">Implementation</div>
                    <div class="eps-desc">Triển khai giải pháp năng lượng tái tạo, hiệu quả năng lượng và carbon offset</div>
                </div>
                <div class="esg-process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="esg-process-step" data-aos="fade-up" data-aos-delay="200">
                    <div class="eps-num">4</div>
                    <div class="eps-icon"><i class="fa-light fa-chart-line"></i></div>
                    <div class="eps-label">MRV</div>
                    <div class="eps-desc">Giám sát, đo lường và xác minh dữ liệu phát thải theo chuẩn GHG Protocol</div>
                </div>
                <div class="esg-process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="esg-process-step" data-aos="fade-up" data-aos-delay="250">
                    <div class="eps-num">5</div>
                    <div class="eps-icon"><i class="fa-light fa-file-chart-column"></i></div>
                    <div class="eps-label">Reporting</div>
                    <div class="eps-desc">Lập báo cáo ESG, công bố thông tin và đăng ký tín chỉ carbon quốc tế</div>
                </div>
            </div>
        </section>

        {{-- ══ CTA ══ --}}
        <section class="esg-cta-section py-5 mb-5" id="cta" data-aos="fade-up">
            <div class="esg-cta-box">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h3>Đánh giá ESG miễn phí cho doanh nghiệp</h3>
                        <p>Chuyên gia NLP ESG & Carbon sẽ đánh giá hiện trạng phát thải, xác định tiềm năng tín chỉ carbon và đề xuất lộ trình Net Zero phù hợp với chiến lược doanh nghiệp của bạn.</p>
                        <div class="esg-cta-features">
                            <span><i class="fa-light fa-check-circle"></i> Đánh giá miễn phí</span>
                            <span><i class="fa-light fa-check-circle"></i> Kiểm kê GHG cơ bản</span>
                            <span><i class="fa-light fa-check-circle"></i> Lộ trình Net Zero</span>
                            <span><i class="fa-light fa-check-circle"></i> Tư vấn tín chỉ carbon</span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="{{ route('page', 'contact') }}" class="esg-btn esg-btn--cta">
                            <i class="fa-light fa-phone"></i> Liên hệ ngay
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>{{-- /.container --}}
</div>{{-- /.esg-page --}}
@endsection

@push('style')
<style>
/* ═══════════════════════════════════════
   NLP ESG & CARBON PAGE
   Primary: #1a4731 | Mid: #2d7a4f | Accent: #3aad6e | Forest: #5c8a3c
═══════════════════════════════════════ */
.esg-page { background: #fff; min-height: 80vh; }
.esg-divider { border: none; border-top: 1px solid #d8e8df; margin: 0; }
.section-label {
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: #5c8a3c; margin-bottom: 6px;
}
.esg-section-title { font-size: 26px; font-weight: 700; color: #1a4731; margin-bottom: 8px; }
.esg-section-desc { color: #57606a; font-size: 15px; max-width: 680px; }

/* Hero */
.esg-eyebrow {
    display: inline-block; font-size: 12px; font-weight: 700;
    letter-spacing: 1.2px; text-transform: uppercase;
    color: #5c8a3c; margin-bottom: 10px;
}
.esg-hero-title { font-size: 40px; font-weight: 800; color: #1a4731; margin-bottom: 4px; }
.esg-hero-subtitle { font-size: 17px; color: #2d7a4f; font-weight: 600; margin-bottom: 16px; }
.esg-hero-desc { font-size: 15px; color: #374151; line-height: 1.8; margin-bottom: 24px; }

/* Buttons */
.esg-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 24px; border-radius: 8px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    transition: all .2s;
}
.esg-btn--primary { background: #1a4731; color: #fff; border: 2px solid #1a4731; }
.esg-btn--primary:hover { background: #2d7a4f; border-color: #2d7a4f; color: #fff; }
.esg-btn--outline { background: transparent; color: #1a4731; border: 2px solid #1a4731; }
.esg-btn--outline:hover { background: #1a4731; color: #fff; }
.esg-btn--cta { background: #fff; color: #1a4731; border: 2px solid #fff; font-size: 15px; padding: 14px 28px; }
.esg-btn--cta:hover { background: #3aad6e; border-color: #3aad6e; color: #fff; }

/* Stats */
.esg-stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.esg-stat {
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 12px; padding: 20px 16px; text-align: center;
    display: flex; flex-direction: column; align-items: center; gap: 4px;
}
.esg-stat i { font-size: 24px; color: #2d7a4f; margin-bottom: 6px; }
.estat-num { font-size: 20px; font-weight: 800; color: #1a4731; }
.estat-label { font-size: 12px; color: #57606a; font-weight: 500; text-align: center; }

/* Service cards */
.esg-card {
    border: 1px solid #c3ddd0; border-radius: 12px;
    padding: 26px 22px; height: 100%; background: #fff;
    transition: transform .2s, box-shadow .2s;
}
.esg-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(26,71,49,.1); }
.esg-card-icon { font-size: 32px; color: #5c8a3c; margin-bottom: 14px; }
.esg-card-title { font-size: 16px; font-weight: 700; color: #1a4731; margin-bottom: 10px; }
.esg-card-desc { font-size: 13.5px; color: #57606a; line-height: 1.7; margin: 0; }

/* CO2 Calculation */
.co2-method-box {
    border: 1px solid #c3ddd0; border-radius: 12px;
    padding: 24px 22px; height: 100%; background: #f9fdfb;
}
.co2-method-title { font-size: 16px; font-weight: 700; color: #1a4731; margin-bottom: 10px; }
.co2-method-title i { color: #5c8a3c; margin-right: 8px; }
.co2-method-desc { font-size: 13.5px; color: #57606a; line-height: 1.7; margin-bottom: 16px; }
.co2-formula-box {
    background: #1a4731; border-radius: 10px;
    padding: 18px 20px; color: #fff;
}
.co2-formula-label { font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #3aad6e; margin-bottom: 8px; }
.co2-formula { font-size: 16px; font-weight: 700; color: #fff; font-family: monospace; margin-bottom: 12px; }
.co2-unit { font-size: 13px; font-weight: 400; color: #3aad6e; }
.co2-example { font-size: 12.5px; color: #c3ddd0; line-height: 1.6; }
.co2-example strong { color: #3aad6e; }

.co2-grid-factor-box {
    display: flex; align-items: flex-start; gap: 14px;
    background: #dff2e9; border: 1px solid #2d7a4f;
    border-left: 4px solid #2d7a4f;
    border-radius: 8px; padding: 16px 20px;
    font-size: 13.5px; color: #1a4731;
}
.co2-grid-factor-box .cgf-icon { font-size: 18px; color: #2d7a4f; flex-shrink: 0; margin-top: 1px; }
.co2-grid-factor-box span { color: #57606a; font-size: 13px; display: block; margin-top: 4px; }

/* SDG cards */
.sdg-card {
    border-radius: 12px; padding: 18px 14px; text-align: center;
    height: 100%; border: 2px solid transparent;
    transition: transform .2s;
}
.sdg-card:hover { transform: translateY(-3px); }
.sdg-7  { background: #fef9c3; border-color: #eab308; }
.sdg-11 { background: #fef3c7; border-color: #f59e0b; }
.sdg-13 { background: #dcfce7; border-color: #16a34a; }
.sdg-15 { background: #d1fae5; border-color: #059669; }
.sdg-17 { background: #dbeafe; border-color: #2563eb; }
.sdg-num { font-size: 13px; font-weight: 800; margin-bottom: 6px; color: #1a4731; }
.sdg-title { font-size: 12.5px; font-weight: 700; color: #1a4731; margin-bottom: 6px; }
.sdg-desc { font-size: 11.5px; color: #57606a; line-height: 1.5; }

/* Partners */
.partner-org-card {
    border: 1px solid #c3ddd0; border-radius: 12px;
    padding: 22px 18px; height: 100%; background: #f0f7f4;
    text-align: center;
    transition: transform .2s;
}
.partner-org-card:hover { transform: translateY(-3px); }
.poc-icon { font-size: 30px; color: #2d7a4f; margin-bottom: 10px; }
.poc-name { font-size: 17px; font-weight: 700; color: #1a4731; margin-bottom: 8px; }
.poc-desc { font-size: 13px; color: #57606a; line-height: 1.6; margin: 0; }

/* Process */
.esg-process-flow {
    display: flex; flex-wrap: wrap; align-items: flex-start;
    gap: 8px; justify-content: center;
}
.esg-process-step {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    width: 130px; text-align: center;
}
.eps-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: #1a4731; color: #fff;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.eps-icon { font-size: 26px; color: #5c8a3c; }
.eps-label { font-size: 13px; font-weight: 700; color: #1a4731; }
.eps-desc { font-size: 11px; color: #57606a; line-height: 1.5; }
.esg-process-arrow {
    display: flex; align-items: center; padding-top: 30px;
    font-size: 14px; color: #3aad6e;
}

/* CTA */
.esg-cta-box {
    background: linear-gradient(135deg, #1a4731 60%, #5c8a3c 100%);
    border-radius: 16px; padding: 44px 40px; color: #fff;
}
.esg-cta-box h3 { font-size: 24px; font-weight: 700; color: #fff; margin-bottom: 10px; }
.esg-cta-box p { font-size: 14.5px; color: #c3ddd0; margin-bottom: 18px; }
.esg-cta-features {
    display: flex; flex-wrap: wrap; gap: 14px;
    font-size: 13px; color: #c3ddd0;
}
.esg-cta-features i { color: #3aad6e; margin-right: 5px; }

@media (max-width: 767px) {
    .esg-hero-title { font-size: 28px; }
    .esg-stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .esg-process-flow { gap: 4px; }
    .esg-process-step { width: 90px; }
    .eps-desc { display: none; }
    .esg-process-arrow { padding-top: 20px; }
}
</style>
@endpush
