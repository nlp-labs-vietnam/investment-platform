@extends(template().'layouts.app')
@section('title', 'NLP Technology — Nền tảng công nghệ số')

@section('content')

@include(template().'partials.banner')

<div class="tech-page">
    <div class="container">

        {{-- ══ INTRO ══ --}}
        <section class="tech-section py-5" data-aos="fade-up">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="tech-eyebrow">Thành viên NLP Group</span>
                    <h1 class="tech-hero-title">NLP Technology</h1>
                    <p class="tech-hero-subtitle">Phần mềm & Chuyển đổi số</p>
                    <p class="tech-hero-desc">
                        <strong>NLP Technology</strong> là đơn vị thành viên thuộc NLP Group, chuyên nghiên cứu,
                        phát triển và triển khai các giải pháp <strong>phần mềm theo yêu cầu</strong> và
                        <strong>chuyển đổi số</strong> toàn diện cho doanh nghiệp Việt Nam.
                        Hoạt động theo mô hình tích hợp, NLP Technology đồng hành cùng khách hàng từ giai đoạn
                        tư vấn chiến lược đến triển khai hệ thống và bảo trì vận hành, giúp tối ưu quy trình
                        và gia tăng năng lực cạnh tranh trong kỷ nguyên số.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="#cta" class="tech-btn tech-btn--primary">
                            <i class="fa-light fa-paper-plane"></i> Tư vấn chuyển đổi số miễn phí
                        </a>
                        <a href="#services" class="tech-btn tech-btn--outline">
                            <i class="fa-light fa-list-check"></i> Xem dịch vụ
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="tech-stats-grid">
                        <div class="tech-stat">
                            <i class="fa-light fa-code-branch"></i>
                            <span class="tstat-num">200+</span>
                            <span class="tstat-label">Dự án phần mềm</span>
                        </div>
                        <div class="tech-stat">
                            <i class="fa-light fa-building"></i>
                            <span class="tstat-num">80+</span>
                            <span class="tstat-label">Doanh nghiệp chuyển đổi số</span>
                        </div>
                        <div class="tech-stat">
                            <i class="fa-light fa-users"></i>
                            <span class="tstat-num">50+</span>
                            <span class="tstat-label">Kỹ sư & chuyên gia</span>
                        </div>
                        <div class="tech-stat">
                            <i class="fa-light fa-star"></i>
                            <span class="tstat-num">98%</span>
                            <span class="tstat-label">Khách hàng hài lòng</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="tech-divider">

        {{-- ══ SERVICES ══ --}}
        <section class="tech-section py-5" id="services" data-aos="fade-up">
            <div class="section-label">Danh mục dịch vụ</div>
            <h2 class="tech-section-title">Dịch vụ của NLP Technology</h2>
            <p class="tech-section-desc">Giải pháp công nghệ toàn diện — từ phần mềm tùy chỉnh đến nền tảng đám mây.</p>
            <div class="row g-4 mt-2">

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="tech-card">
                        <div class="tech-card-icon"><i class="fa-light fa-code"></i></div>
                        <h4 class="tech-card-title">Phần mềm theo yêu cầu</h4>
                        <p class="tech-card-desc">Phát triển phần mềm tùy chỉnh hoàn toàn theo nghiệp vụ đặc thù, quy trình và yêu cầu riêng của từng doanh nghiệp.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="tech-card">
                        <div class="tech-card-icon"><i class="fa-light fa-globe"></i></div>
                        <h4 class="tech-card-title">Website</h4>
                        <p class="tech-card-desc">Thiết kế và phát triển website doanh nghiệp, landing page, cổng thông tin, thương mại điện tử theo tiêu chuẩn hiện đại.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="tech-card">
                        <div class="tech-card-icon"><i class="fa-light fa-mobile-screen"></i></div>
                        <h4 class="tech-card-title">Ứng dụng Mobile</h4>
                        <p class="tech-card-desc">Phát triển ứng dụng iOS & Android cho nội bộ doanh nghiệp, khách hàng và quản lý vận hành hiện trường.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="tech-card">
                        <div class="tech-card-icon"><i class="fa-light fa-building"></i></div>
                        <h4 class="tech-card-title">ERP</h4>
                        <p class="tech-card-desc">Hệ thống hoạch định nguồn lực doanh nghiệp tích hợp tài chính, kho vận, nhân sự và sản xuất trên một nền tảng duy nhất.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="tech-card">
                        <div class="tech-card-icon"><i class="fa-light fa-users"></i></div>
                        <h4 class="tech-card-title">CRM</h4>
                        <p class="tech-card-desc">Quản lý quan hệ khách hàng, tự động hóa bán hàng, theo dõi pipeline và phân tích hành vi khách hàng thông minh.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="tech-card">
                        <div class="tech-card-icon"><i class="fa-light fa-microchip"></i></div>
                        <h4 class="tech-card-title">IoT Platform</h4>
                        <p class="tech-card-desc">Nền tảng IoT kết nối thiết bị, cảm biến và máy móc với hệ thống quản lý trung tâm, hỗ trợ giám sát và điều khiển từ xa.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="tech-card">
                        <div class="tech-card-icon"><i class="fa-light fa-plug"></i></div>
                        <h4 class="tech-card-title">API & Tích hợp</h4>
                        <p class="tech-card-desc">Xây dựng và tích hợp API RESTful/GraphQL kết nối các hệ thống legacy, bên thứ ba và nền tảng đám mây liền mạch.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="tech-card">
                        <div class="tech-card-icon"><i class="fa-light fa-cloud"></i></div>
                        <h4 class="tech-card-title">Cloud Platform</h4>
                        <p class="tech-card-desc">Tư vấn và triển khai hạ tầng đám mây (AWS, Azure, GCP), migration, DevOps, container hóa và tối ưu chi phí cloud.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="tech-card">
                        <div class="tech-card-icon"><i class="fa-light fa-server"></i></div>
                        <h4 class="tech-card-title">SaaS</h4>
                        <p class="tech-card-desc">Phát triển sản phẩm phần mềm dạng SaaS đa tenancy, hỗ trợ mô hình đăng ký, billing tự động và marketplace.</p>
                    </div>
                </div>

            </div>
        </section>

        <hr class="tech-divider">

        {{-- ══ DIGITAL TRANSFORMATION ROADMAP ══ --}}
        <section class="tech-section py-5" data-aos="fade-up">
            <div class="section-label">Lộ trình chuyển đổi số</div>
            <h2 class="tech-section-title">4 giai đoạn chuyển đổi số</h2>
            <p class="tech-section-desc">NLP Technology đồng hành cùng doanh nghiệp qua từng giai đoạn trên hành trình số hóa.</p>
            <div class="roadmap-flow mt-4">
                <div class="roadmap-phase roadmap-phase--1" data-aos="fade-up" data-aos-delay="50">
                    <div class="rp-num">1</div>
                    <div class="rp-icon"><i class="fa-light fa-file-digitial"></i><i class="fa-light fa-scanner-image"></i></div>
                    <div class="rp-title">Số hóa</div>
                    <div class="rp-desc">Chuyển đổi dữ liệu và quy trình thủ công sang dạng kỹ thuật số, lưu trữ tập trung</div>
                </div>
                <div class="roadmap-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="roadmap-phase roadmap-phase--2" data-aos="fade-up" data-aos-delay="100">
                    <div class="rp-num">2</div>
                    <div class="rp-icon"><i class="fa-light fa-robot"></i></div>
                    <div class="rp-title">Tự động hóa</div>
                    <div class="rp-desc">Tự động hóa quy trình nghiệp vụ (BPA/RPA), giảm thiểu thao tác thủ công</div>
                </div>
                <div class="roadmap-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="roadmap-phase roadmap-phase--3" data-aos="fade-up" data-aos-delay="150">
                    <div class="rp-num">3</div>
                    <div class="rp-icon"><i class="fa-light fa-brain"></i></div>
                    <div class="rp-title">Ứng dụng AI</div>
                    <div class="rp-desc">Tích hợp trí tuệ nhân tạo, phân tích dự đoán và hỗ trợ ra quyết định thông minh</div>
                </div>
                <div class="roadmap-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="roadmap-phase roadmap-phase--4" data-aos="fade-up" data-aos-delay="200">
                    <div class="rp-num">4</div>
                    <div class="rp-icon"><i class="fa-light fa-building-flag"></i></div>
                    <div class="rp-title">Doanh nghiệp thông minh</div>
                    <div class="rp-desc">Hệ sinh thái số hoàn chỉnh — dữ liệu, con người và công nghệ hội tụ tối ưu</div>
                </div>
            </div>
        </section>

        <hr class="tech-divider">

        {{-- ══ TECH ECOSYSTEM ══ --}}
        <section class="tech-section py-5" data-aos="fade-up">
            <div class="section-label">Hệ sinh thái công nghệ</div>
            <h2 class="tech-section-title">Công thức Doanh nghiệp số</h2>
            <p class="tech-section-desc">Ba trụ cột cốt lõi tạo nên nền tảng chuyển đổi số bền vững.</p>
            <div class="ecosystem-flow mt-4">
                <div class="eco-node eco-node--software">
                    <i class="fa-light fa-code fa-2x mb-2"></i>
                    <div class="eco-label">Phần mềm</div>
                    <div class="eco-sub">ERP · CRM · Web · App</div>
                </div>
                <div class="eco-op">+</div>
                <div class="eco-node eco-node--data">
                    <i class="fa-light fa-database fa-2x mb-2"></i>
                    <div class="eco-label">Dữ liệu</div>
                    <div class="eco-sub">Analytics · BI · AI</div>
                </div>
                <div class="eco-op">+</div>
                <div class="eco-node eco-node--connect">
                    <i class="fa-light fa-plug fa-2x mb-2"></i>
                    <div class="eco-label">Kết nối</div>
                    <div class="eco-sub">API · IoT · Cloud</div>
                </div>
                <div class="eco-op">=</div>
                <div class="eco-node eco-node--result">
                    <i class="fa-light fa-building-flag fa-2x mb-2"></i>
                    <div class="eco-label">Doanh nghiệp số</div>
                    <div class="eco-sub">Nhanh · Thông minh · Bền vững</div>
                </div>
            </div>
        </section>

        <hr class="tech-divider">

        {{-- ══ PROCESS ══ --}}
        <section class="tech-section py-5" data-aos="fade-up">
            <div class="section-label">Quy trình triển khai</div>
            <h2 class="tech-section-title">7 bước phát triển phần mềm</h2>
            <p class="tech-section-desc">Quy trình chuẩn hóa, minh bạch — đảm bảo chất lượng và đúng tiến độ.</p>
            <div class="process-flow mt-4">
                <div class="process-step">
                    <div class="ps-num">1</div>
                    <div class="ps-icon"><i class="fa-light fa-magnifying-glass"></i></div>
                    <div class="ps-label">Khảo sát</div>
                    <div class="ps-desc">Tìm hiểu nghiệp vụ, quy trình và mục tiêu doanh nghiệp</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">2</div>
                    <div class="ps-icon"><i class="fa-light fa-chart-gantt"></i></div>
                    <div class="ps-label">Phân tích</div>
                    <div class="ps-desc">Phân tích yêu cầu, lập tài liệu SRS và kế hoạch dự án</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">3</div>
                    <div class="ps-icon"><i class="fa-light fa-drafting-compass"></i></div>
                    <div class="ps-label">Thiết kế</div>
                    <div class="ps-desc">Thiết kế UI/UX, kiến trúc hệ thống và cơ sở dữ liệu</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">4</div>
                    <div class="ps-icon"><i class="fa-light fa-code"></i></div>
                    <div class="ps-label">Lập trình</div>
                    <div class="ps-desc">Phát triển theo sprint Agile, review code và CI/CD</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">5</div>
                    <div class="ps-icon"><i class="fa-light fa-vial"></i></div>
                    <div class="ps-label">Kiểm thử</div>
                    <div class="ps-desc">Unit test, integration test, UAT với người dùng thực</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">6</div>
                    <div class="ps-icon"><i class="fa-light fa-rocket"></i></div>
                    <div class="ps-label">Triển khai</div>
                    <div class="ps-desc">Deploy lên môi trường production, đào tạo người dùng</div>
                </div>
                <div class="process-arrow"><i class="fa-light fa-chevron-right"></i></div>
                <div class="process-step">
                    <div class="ps-num">7</div>
                    <div class="ps-icon"><i class="fa-light fa-wrench"></i></div>
                    <div class="ps-label">Bảo trì</div>
                    <div class="ps-desc">Hỗ trợ 24/7, cập nhật tính năng và bảo mật định kỳ</div>
                </div>
            </div>
        </section>

        <hr class="tech-divider">

        {{-- ══ INDUSTRIES ══ --}}
        <section class="tech-section py-5" data-aos="fade-up">
            <div class="section-label">Ngành nghề phục vụ</div>
            <h2 class="tech-section-title">Lĩnh vực chúng tôi chuyên sâu</h2>
            <div class="row g-3 mt-2">
                <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="50">
                    <div class="industry-card">
                        <i class="fa-light fa-store"></i>
                        <span>Thương mại</span>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="industry-card">
                        <i class="fa-light fa-industry"></i>
                        <span>Sản xuất</span>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="150">
                    <div class="industry-card">
                        <i class="fa-light fa-truck"></i>
                        <span>Logistics</span>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="200">
                    <div class="industry-card">
                        <i class="fa-light fa-bolt"></i>
                        <span>Năng lượng</span>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="250">
                    <div class="industry-card">
                        <i class="fa-light fa-landmark"></i>
                        <span>Tài chính</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ CTA ══ --}}
        <section class="tech-cta-section py-5 mb-5" id="cta" data-aos="fade-up">
            <div class="tech-cta-box">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h3>Tư vấn chuyển đổi số miễn phí</h3>
                        <p>Đội ngũ chuyên gia NLP Technology sẵn sàng phân tích hiện trạng và đề xuất lộ trình chuyển đổi số phù hợp với doanh nghiệp của bạn.</p>
                        <div class="tech-cta-features">
                            <span><i class="fa-light fa-check-circle"></i> Tư vấn miễn phí</span>
                            <span><i class="fa-light fa-check-circle"></i> Phân tích hiện trạng</span>
                            <span><i class="fa-light fa-check-circle"></i> Lộ trình chi tiết</span>
                            <span><i class="fa-light fa-check-circle"></i> ROI rõ ràng</span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="{{ route('page', 'contact') }}" class="tech-btn tech-btn--cta">
                            <i class="fa-light fa-phone"></i> Liên hệ ngay
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>{{-- /.container --}}
</div>{{-- /.tech-page --}}
@endsection

@push('style')
<style>
/* ═══════════════════════════════════════
   NLP TECHNOLOGY PAGE
   Primary: #1a4731 | Accent: #2d7a4f | Light: #3aad6e
═══════════════════════════════════════ */
.tech-page { background: #fff; min-height: 80vh; }
.tech-divider { border: none; border-top: 1px solid #d8e8df; margin: 0; }
.section-label {
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: #3aad6e; margin-bottom: 6px;
}
.tech-section-title { font-size: 26px; font-weight: 700; color: #1a4731; margin-bottom: 8px; }
.tech-section-desc { color: #57606a; font-size: 15px; max-width: 680px; }

/* Hero */
.tech-eyebrow {
    display: inline-block; font-size: 12px; font-weight: 700;
    letter-spacing: 1.2px; text-transform: uppercase;
    color: #3aad6e; margin-bottom: 10px;
}
.tech-hero-title { font-size: 40px; font-weight: 800; color: #1a4731; margin-bottom: 4px; }
.tech-hero-subtitle { font-size: 17px; color: #2d7a4f; font-weight: 600; margin-bottom: 16px; }
.tech-hero-desc { font-size: 15px; color: #374151; line-height: 1.8; margin-bottom: 24px; }

/* Buttons */
.tech-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 24px; border-radius: 8px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    transition: all .2s;
}
.tech-btn--primary { background: #1a4731; color: #fff; border: 2px solid #1a4731; }
.tech-btn--primary:hover { background: #2d7a4f; border-color: #2d7a4f; color: #fff; }
.tech-btn--outline { background: transparent; color: #1a4731; border: 2px solid #1a4731; }
.tech-btn--outline:hover { background: #1a4731; color: #fff; }
.tech-btn--cta { background: #fff; color: #1a4731; border: 2px solid #fff; font-size: 15px; padding: 14px 28px; }
.tech-btn--cta:hover { background: #3aad6e; border-color: #3aad6e; color: #fff; }

/* Stats grid */
.tech-stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.tech-stat {
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 12px; padding: 20px 16px; text-align: center;
    display: flex; flex-direction: column; align-items: center; gap: 4px;
}
.tech-stat i { font-size: 24px; color: #2d7a4f; margin-bottom: 6px; }
.tstat-num { font-size: 24px; font-weight: 800; color: #1a4731; }
.tstat-label { font-size: 12px; color: #57606a; font-weight: 500; text-align: center; }

/* Service cards */
.tech-card {
    border: 1px solid #c3ddd0; border-radius: 12px;
    padding: 26px 22px; height: 100%; background: #fff;
    transition: transform .2s, box-shadow .2s;
}
.tech-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(26,71,49,.1); }
.tech-card-icon { font-size: 32px; color: #2d7a4f; margin-bottom: 14px; }
.tech-card-title { font-size: 16px; font-weight: 700; color: #1a4731; margin-bottom: 10px; }
.tech-card-desc { font-size: 13.5px; color: #57606a; line-height: 1.7; margin: 0; }

/* Roadmap */
.roadmap-flow {
    display: flex; flex-wrap: wrap; align-items: flex-start;
    gap: 8px; justify-content: center;
}
.roadmap-phase {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    width: 160px; text-align: center;
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 12px; padding: 20px 14px;
}
.roadmap-phase--1 { border-top: 3px solid #3aad6e; }
.roadmap-phase--2 { border-top: 3px solid #2d7a4f; }
.roadmap-phase--3 { border-top: 3px solid #1a6b42; }
.roadmap-phase--4 { border-top: 3px solid #1a4731; }
.rp-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: #1a4731; color: #fff;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.rp-icon { font-size: 26px; color: #2d7a4f; }
.rp-title { font-size: 13px; font-weight: 700; color: #1a4731; }
.rp-desc { font-size: 11.5px; color: #57606a; line-height: 1.5; }
.roadmap-arrow {
    display: flex; align-items: center; padding-top: 46px;
    font-size: 14px; color: #3aad6e;
}

/* Ecosystem */
.ecosystem-flow {
    display: flex; flex-wrap: wrap; align-items: center;
    justify-content: center; gap: 12px;
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 16px; padding: 32px 24px;
}
.eco-node {
    display: flex; flex-direction: column; align-items: center;
    text-align: center; padding: 20px 18px; border-radius: 12px;
    min-width: 120px; border: 1px solid #c3ddd0;
}
.eco-node--software { background: #dff2e9; color: #2d7a4f; border-color: #2d7a4f; }
.eco-node--data     { background: #d0eafb; color: #0550ae; border-color: #54aeff; }
.eco-node--connect  { background: #fdf3d0; color: #7d5a00; border-color: #e3a800; }
.eco-node--result   { background: #1a4731; color: #fff; border-color: #1a4731; min-width: 140px; }
.eco-label { font-size: 13px; font-weight: 700; margin-bottom: 2px; }
.eco-sub { font-size: 11px; opacity: .8; }
.eco-op { font-size: 24px; font-weight: 700; color: #3aad6e; }

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

/* Industries */
.industry-card {
    border: 1px solid #c3ddd0; border-radius: 10px;
    padding: 18px 10px; text-align: center;
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    background: #f0f7f4; transition: transform .2s, box-shadow .2s;
}
.industry-card:hover { transform: translateY(-3px); box-shadow: 0 4px 14px rgba(26,71,49,.1); }
.industry-card i { font-size: 28px; color: #2d7a4f; }
.industry-card span { font-size: 13px; font-weight: 600; color: #1a4731; }

/* CTA */
.tech-cta-box {
    background: linear-gradient(135deg, #1a4731 60%, #2d7a4f 100%);
    border-radius: 16px; padding: 44px 40px; color: #fff;
}
.tech-cta-box h3 { font-size: 24px; font-weight: 700; color: #fff; margin-bottom: 10px; }
.tech-cta-box p { font-size: 14.5px; color: #c3ddd0; margin-bottom: 18px; }
.tech-cta-features {
    display: flex; flex-wrap: wrap; gap: 14px;
    font-size: 13px; color: #c3ddd0;
}
.tech-cta-features i { color: #3aad6e; margin-right: 5px; }

@media (max-width: 767px) {
    .tech-hero-title { font-size: 28px; }
    .tech-stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .process-flow { gap: 4px; }
    .process-step { width: 80px; }
    .ps-desc { display: none; }
    .process-arrow { padding-top: 18px; }
    .roadmap-flow { gap: 4px; }
    .roadmap-phase { width: 130px; }
    .roadmap-arrow { padding-top: 50px; }
    .eco-op { font-size: 18px; }
}
</style>
@endpush
