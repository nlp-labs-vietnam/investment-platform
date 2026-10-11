@extends(template().'layouts.app')
@section('title', 'Về NLP Group')

@section('content')

@include(template().'partials.banner')

<div class="about-page">
    <div class="container">

        {{-- ══ HERO ══ --}}
        <section class="about-section about-hero py-5" data-aos="fade-up">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="about-eyebrow">NLP Group Vietnam</span>
                    <h1 class="about-hero-title">Công nghệ tạo giá trị.<br>Năng lượng tạo tương lai.</h1>
                    <p class="about-hero-desc">
                        <strong>NLP Group</strong> là tập đoàn công nghệ – năng lượng tiên phong tại Việt Nam, hoạt động trên nền tảng
                        tích hợp 5 lĩnh vực chiến lược: Năng lượng tái tạo, Hạ tầng sạc EV, Công nghệ phần mềm,
                        Trí tuệ nhân tạo &amp; Chuyển đổi số, và ESG &amp; Tín chỉ carbon. Với sứ mệnh kiến tạo hệ sinh thái
                        xanh bền vững, NLP Group đồng hành cùng doanh nghiệp và hộ gia đình trên hành trình
                        chuyển dịch năng lượng và số hoá toàn diện.
                    </p>
                    <div class="about-hero-stats">
                        <div class="hero-stat"><span class="stat-num">5+</span><span class="stat-label">Năm hoạt động</span></div>
                        <div class="hero-stat"><span class="stat-num">150+</span><span class="stat-label">Dự án triển khai</span></div>
                        <div class="hero-stat"><span class="stat-num">5</span><span class="stat-label">Công ty thành viên</span></div>
                        <div class="hero-stat"><span class="stat-num">200+</span><span class="stat-label">Đối tác tin cậy</span></div>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="about-hero-logo-wrap">
                        <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"
                             alt="NLP Group Logo" style="max-width:220px;">
                        <div class="about-hero-badges">
                            <span class="about-badge"><i class="fa-light fa-award"></i> ISO 9001</span>
                            <span class="about-badge"><i class="fa-light fa-leaf"></i> ISO 14001</span>
                            <span class="about-badge"><i class="fa-light fa-shield-check"></i> ISO 45001</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="about-divider">

        {{-- ══ CHAIRMAN'S MESSAGE ══ --}}
        <section class="about-section py-5" data-aos="fade-up">
            <div class="section-label">Thông điệp lãnh đạo</div>
            <h2 class="about-section-title">Thông điệp Chủ tịch</h2>
            <div class="chairman-card">
                <div class="chairman-quote-icon"><i class="fa-light fa-quote-left"></i></div>
                <blockquote class="chairman-quote">
                    Chúng tôi tin rằng <strong>công nghệ và năng lượng xanh</strong> không chỉ là xu hướng —
                    đó là con đường tất yếu để Việt Nam phát triển bền vững. NLP Group được xây dựng
                    với khát vọng trở thành cầu nối giữa công nghệ tiên tiến và nhu cầu thực tiễn của
                    doanh nghiệp, hộ gia đình và cộng đồng. Mỗi dự án chúng tôi thực hiện là một bước
                    đi cụ thể hướng đến mục tiêu <strong>Net Zero 2050</strong> của Việt Nam.
                </blockquote>
                <div class="chairman-info">
                    <div class="chairman-avatar"><i class="fa-light fa-user-tie fa-2x"></i></div>
                    <div>
                        <div class="chairman-name">Chủ tịch NLP Group</div>
                        <div class="chairman-title">Hội đồng quản trị — NLP Group Vietnam</div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="about-divider">

        {{-- ══ VISION & MISSION ══ --}}
        <section class="about-section py-5" data-aos="fade-up">
            <div class="section-label">Định hướng phát triển</div>
            <h2 class="about-section-title">Tầm nhìn &amp; Sứ mệnh</h2>
            <div class="row g-4 mt-2">
                <div class="col-md-6" data-aos="fade-right" data-aos-delay="100">
                    <div class="vm-card vm-card--vision">
                        <div class="vm-icon"><i class="fa-light fa-eye"></i></div>
                        <h3 class="vm-title">Tầm nhìn</h3>
                        <p class="vm-text">
                            Trở thành <strong>tập đoàn công nghệ – năng lượng hàng đầu Đông Nam Á</strong>,
                            dẫn dắt quá trình chuyển dịch năng lượng và số hoá tại Việt Nam với hệ sinh thái
                            tích hợp từ sản xuất điện sạch, lưu trữ năng lượng, hạ tầng sạc EV đến
                            quản trị carbon và trí tuệ nhân tạo.
                        </p>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="vm-card vm-card--mission">
                        <div class="vm-icon"><i class="fa-light fa-rocket"></i></div>
                        <h3 class="vm-title">Sứ mệnh</h3>
                        <p class="vm-text">
                            Cung cấp <strong>giải pháp năng lượng xanh và công nghệ thông minh</strong>
                            toàn diện, giúp doanh nghiệp và cộng đồng giảm chi phí năng lượng, nâng cao
                            năng lực cạnh tranh và thực hiện cam kết phát triển bền vững theo tiêu chuẩn
                            quốc tế ESG.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <hr class="about-divider">

        {{-- ══ CORE VALUES ══ --}}
        <section class="about-section py-5" data-aos="fade-up">
            <div class="section-label">Nền tảng văn hoá</div>
            <h2 class="about-section-title">Giá trị cốt lõi</h2>
            <div class="row g-4 mt-2">
                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="50">
                    <div class="value-card">
                        <div class="value-icon"><i class="fa-light fa-handshake-angle"></i></div>
                        <h4 class="value-title">Uy tín</h4>
                        <div class="value-subtitle">Integrity</div>
                        <p class="value-desc">Cam kết minh bạch, trung thực trong mọi giao dịch và quan hệ đối tác. Uy tín là tài sản quan trọng nhất của NLP Group.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="value-card">
                        <div class="value-icon"><i class="fa-light fa-lightbulb"></i></div>
                        <h4 class="value-title">Đổi mới</h4>
                        <div class="value-subtitle">Innovation</div>
                        <p class="value-desc">Không ngừng nghiên cứu, ứng dụng công nghệ tiên tiến để mang lại giải pháp sáng tạo, hiệu quả vượt trội cho khách hàng.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="150">
                    <div class="value-card">
                        <div class="value-icon"><i class="fa-light fa-seedling"></i></div>
                        <h4 class="value-title">Bền vững</h4>
                        <div class="value-subtitle">Sustainability</div>
                        <p class="value-desc">Gắn kết lợi ích kinh doanh với trách nhiệm môi trường và xã hội. Mọi quyết định đều hướng đến tương lai xanh hơn.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="value-card">
                        <div class="value-icon"><i class="fa-light fa-people-group"></i></div>
                        <h4 class="value-title">Hợp tác</h4>
                        <div class="value-subtitle">Partnership</div>
                        <p class="value-desc">Xây dựng hệ sinh thái đối tác bền chặt với triết lý cùng thắng, chia sẻ tri thức và cùng nhau tạo ra giá trị lâu dài.</p>
                    </div>
                </div>
            </div>
        </section>

        <hr class="about-divider">

        {{-- ══ BUSINESS PHILOSOPHY ══ --}}
        <section class="about-section py-5" data-aos="fade-up">
            <div class="section-label">Triết lý kinh doanh</div>
            <h2 class="about-section-title">Triết lý kinh doanh NLP Group</h2>
            <div class="philosophy-banner mt-4">
                <div class="row g-0">
                    <div class="col-md-4">
                        <div class="philosophy-pillar">
                            <i class="fa-light fa-microchip fa-2x mb-3"></i>
                            <h5>Công nghệ tạo giá trị</h5>
                            <p>Ứng dụng công nghệ số và AI để tối ưu hoá vận hành, giảm chi phí và tạo ra giá trị đo lường được cho khách hàng.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="philosophy-pillar philosophy-pillar--mid">
                            <i class="fa-light fa-solar-panel fa-2x mb-3"></i>
                            <h5>Năng lượng tạo tương lai</h5>
                            <p>Chuyển dịch từ năng lượng hoá thạch sang năng lượng tái tạo là hành trình NLP Group đồng hành cùng mỗi khách hàng.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="philosophy-pillar">
                            <i class="fa-light fa-globe fa-2x mb-3"></i>
                            <h5>Bền vững tạo thịnh vượng</h5>
                            <p>Phát triển bền vững không chỉ là trách nhiệm xã hội — đó là nền tảng cho thịnh vượng lâu dài của doanh nghiệp và cộng đồng.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="about-divider">

        {{-- ══ ORG CHART / SUBSIDIARIES ══ --}}
        <section class="about-section py-5" data-aos="fade-up">
            <div class="section-label">Cấu trúc tập đoàn</div>
            <h2 class="about-section-title">Cơ cấu tổ chức</h2>
            <p class="about-section-desc">NLP Group hoạt động thông qua 5 công ty thành viên chuyên biệt, cùng tạo nên hệ sinh thái năng lượng – công nghệ tích hợp.</p>
            <div class="org-chart mt-4">
                <div class="org-parent">
                    <i class="fa-light fa-building-columns"></i> NLP Group Vietnam — Tập đoàn
                </div>
                <div class="org-line"></div>
                <div class="row g-3 justify-content-center">
                    <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="50">
                        <div class="org-card">
                            <div class="org-icon"><i class="fa-light fa-solar-panel"></i></div>
                            <h5>NLP Energy</h5>
                            <p>Năng lượng tái tạo · Solar PV · BESS · EMS · EPC · O&amp;M</p>
                            <a href="{{ route('page', 'nlp-energy') }}" class="org-link">Xem chi tiết <i class="fa-light fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="org-card">
                            <div class="org-icon"><i class="fa-light fa-charging-station"></i></div>
                            <h5>NLP EV Charging</h5>
                            <p>Hạ tầng sạc xe điện · Trụ sạc AC/DC · CSMS · OCPP</p>
                            <a href="{{ route('page', 'nlp-ev-charging') }}" class="org-link">Xem chi tiết <i class="fa-light fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="150">
                        <div class="org-card">
                            <div class="org-icon"><i class="fa-light fa-laptop-code"></i></div>
                            <h5>NLP Technology</h5>
                            <p>Phần mềm doanh nghiệp · ERP · CRM · IoT · Cloud</p>
                            <a href="{{ route('page', 'nlp-technology') }}" class="org-link">Xem chi tiết <i class="fa-light fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="org-card">
                            <div class="org-icon"><i class="fa-light fa-brain-circuit"></i></div>
                            <h5>NLP AI &amp; Digital</h5>
                            <p>Trí tuệ nhân tạo · AI Agent · RAG/LLM · Computer Vision</p>
                            <a href="{{ route('page', 'nlp-ai-digital') }}" class="org-link">Xem chi tiết <i class="fa-light fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="250">
                        <div class="org-card">
                            <div class="org-icon"><i class="fa-light fa-leaf"></i></div>
                            <h5>NLP ESG &amp; Carbon</h5>
                            <p>ESG · MRV · Tín chỉ carbon · Net Zero · Phát triển bền vững</p>
                            <a href="{{ route('page', 'nlp-esg-carbon') }}" class="org-link">Xem chi tiết <i class="fa-light fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="about-divider">

        {{-- ══ CORPORATE CULTURE ══ --}}
        <section class="about-section py-5" data-aos="fade-up">
            <div class="section-label">Con người NLP</div>
            <h2 class="about-section-title">Văn hóa doanh nghiệp</h2>
            <div class="row g-4 mt-2">
                <div class="col-md-6" data-aos="fade-right" data-aos-delay="50">
                    <div class="culture-item">
                        <i class="fa-light fa-users-gear"></i>
                        <div>
                            <h5>Con người là trung tâm</h5>
                            <p>NLP Group đầu tư vào phát triển năng lực đội ngũ thông qua đào tạo liên tục, môi trường làm việc sáng tạo và chính sách đãi ngộ cạnh tranh.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left" data-aos-delay="50">
                    <div class="culture-item">
                        <i class="fa-light fa-chart-mixed-up-circle-dollar"></i>
                        <div>
                            <h5>Tư duy kết quả</h5>
                            <p>Văn hoá OKR — mỗi cá nhân và bộ phận đặt mục tiêu rõ ràng, đo lường kết quả minh bạch và liên tục cải tiến theo vòng lặp Agile.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-right" data-aos-delay="100">
                    <div class="culture-item">
                        <i class="fa-light fa-hands-holding-heart"></i>
                        <div>
                            <h5>Trách nhiệm xã hội</h5>
                            <p>NLP Group tích cực tham gia các chương trình cộng đồng về năng lượng sạch, giáo dục môi trường và hỗ trợ doanh nghiệp vừa và nhỏ chuyển đổi xanh.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="culture-item">
                        <i class="fa-light fa-network-wired"></i>
                        <div>
                            <h5>Hệ sinh thái mở</h5>
                            <p>NLP Group xây dựng mạng lưới đối tác mở, hợp tác với các trường đại học, viện nghiên cứu, startup công nghệ và tổ chức quốc tế trong lĩnh vực năng lượng &amp; ESG.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ CTA ══ --}}
        <section class="about-cta-section py-5 mb-5" data-aos="fade-up">
            <div class="about-cta-box text-center">
                <h3>Cùng NLP Group xây dựng tương lai xanh</h3>
                <p>Liên hệ với chúng tôi để được tư vấn giải pháp phù hợp với nhu cầu của bạn.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                    <a href="{{ route('page', 'contact') }}" class="about-btn about-btn--primary">
                        <i class="fa-light fa-paper-plane"></i> Liên hệ tư vấn
                    </a>
                    <a href="{{ route('page', 'wiki/ecosystem') }}" class="about-btn about-btn--outline">
                        <i class="fa-light fa-diagram-project"></i> Khám phá hệ sinh thái
                    </a>
                </div>
            </div>
        </section>

    </div>{{-- /.container --}}
</div>{{-- /.about-page --}}
@endsection

@push('style')
<style>
/* ═══════════════════════════════════════
   ABOUT PAGE — NLP Group
   Primary: #1a4731 | Accent: #2d7a4f | Light: #3aad6e
═══════════════════════════════════════ */
.about-page { background: #fff; min-height: 80vh; }

/* Section utilities */
.about-divider { border: none; border-top: 1px solid #d8e8df; margin: 0; }
.section-label {
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: #3aad6e; margin-bottom: 6px;
}
.about-section-title {
    font-size: 26px; font-weight: 700; color: #1a4731;
    margin-bottom: 8px;
}
.about-section-desc { color: #57606a; font-size: 15px; max-width: 680px; }

/* ─── HERO ─── */
.about-eyebrow {
    display: inline-block; font-size: 12px; font-weight: 700;
    letter-spacing: 1.2px; text-transform: uppercase;
    color: #3aad6e; margin-bottom: 12px;
}
.about-hero-title {
    font-size: 36px; font-weight: 800; color: #1a4731;
    line-height: 1.25; margin-bottom: 16px;
}
.about-hero-desc { font-size: 15.5px; color: #374151; line-height: 1.8; margin-bottom: 28px; }
.about-hero-stats { display: flex; flex-wrap: wrap; gap: 16px; }
.hero-stat {
    display: flex; flex-direction: column; align-items: center;
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 10px; padding: 14px 22px; min-width: 100px;
}
.stat-num { font-size: 24px; font-weight: 800; color: #1a4731; }
.stat-label { font-size: 11.5px; color: #2d7a4f; font-weight: 600; margin-top: 2px; }
.about-hero-logo-wrap {
    background: #f0f7f4; border-radius: 16px;
    border: 1px solid #c3ddd0; padding: 32px 24px;
}
.about-hero-badges { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-top: 18px; }
.about-badge {
    font-size: 11px; font-weight: 700; padding: 4px 10px;
    border-radius: 12px; background: #1a4731; color: #fff;
}
.about-badge i { margin-right: 4px; }

/* ─── CHAIRMAN ─── */
.chairman-card {
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-left: 5px solid #2d7a4f;
    border-radius: 12px; padding: 32px 36px; margin-top: 24px;
    position: relative;
}
.chairman-quote-icon {
    font-size: 40px; color: #c3ddd0; margin-bottom: 16px;
    line-height: 1;
}
.chairman-quote {
    font-size: 16px; line-height: 1.85; color: #1f2328;
    border: none; padding: 0; margin-bottom: 24px;
    font-style: italic;
}
.chairman-info { display: flex; align-items: center; gap: 16px; }
.chairman-avatar {
    width: 52px; height: 52px; border-radius: 50%;
    background: #1a4731; color: #fff;
    display: flex; align-items: center; justify-content: center;
}
.chairman-name { font-weight: 700; font-size: 14px; color: #1a4731; }
.chairman-title { font-size: 12.5px; color: #57606a; }

/* ─── VISION / MISSION ─── */
.vm-card {
    border-radius: 12px; padding: 32px 28px; height: 100%;
    border: 1px solid #c3ddd0;
}
.vm-card--vision { background: #1a4731; }
.vm-card--mission { background: #f0f7f4; }
.vm-icon {
    font-size: 32px; margin-bottom: 16px;
}
.vm-card--vision .vm-icon { color: #3aad6e; }
.vm-card--mission .vm-icon { color: #2d7a4f; }
.vm-title {
    font-size: 20px; font-weight: 700; margin-bottom: 12px;
}
.vm-card--vision .vm-title { color: #fff; }
.vm-card--mission .vm-title { color: #1a4731; }
.vm-text { font-size: 14.5px; line-height: 1.75; }
.vm-card--vision .vm-text { color: #c3ddd0; }
.vm-card--mission .vm-text { color: #374151; }

/* ─── VALUE CARDS ─── */
.value-card {
    border: 1px solid #c3ddd0; border-radius: 12px;
    padding: 28px 22px; text-align: center; height: 100%;
    background: #fff; transition: transform .2s, box-shadow .2s;
}
.value-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(26,71,49,.12); }
.value-icon {
    font-size: 36px; color: #2d7a4f; margin-bottom: 14px;
}
.value-title { font-size: 18px; font-weight: 700; color: #1a4731; margin-bottom: 2px; }
.value-subtitle { font-size: 12px; color: #3aad6e; font-weight: 600; letter-spacing: .8px; margin-bottom: 12px; }
.value-desc { font-size: 13.5px; color: #57606a; line-height: 1.7; margin: 0; }

/* ─── PHILOSOPHY ─── */
.philosophy-banner {
    background: #1a4731; border-radius: 16px; overflow: hidden;
    color: #fff;
}
.philosophy-pillar {
    padding: 36px 28px; text-align: center;
    border-right: 1px solid rgba(255,255,255,.12);
}
.philosophy-pillar--mid { background: #2d7a4f; }
.philosophy-pillar:last-child { border-right: none; }
.philosophy-pillar i { color: #3aad6e; }
.philosophy-pillar h5 { font-size: 16px; font-weight: 700; color: #fff; margin: 0 0 10px; }
.philosophy-pillar p { font-size: 13.5px; color: #c3ddd0; line-height: 1.7; margin: 0; }

/* ─── ORG CHART ─── */
.org-parent {
    background: #1a4731; color: #fff; font-weight: 700; font-size: 15px;
    border-radius: 10px; padding: 14px 24px; text-align: center;
    display: inline-flex; align-items: center; gap: 10px;
    margin: 0 auto 4px; display: flex; justify-content: center;
}
.org-parent i { color: #3aad6e; }
.org-line {
    width: 2px; height: 28px; background: #c3ddd0;
    margin: 0 auto 16px;
}
.org-card {
    border: 1px solid #c3ddd0; border-radius: 12px;
    padding: 22px 18px; height: 100%; background: #f0f7f4;
    text-align: center; transition: transform .2s, box-shadow .2s;
}
.org-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(26,71,49,.1); }
.org-icon { font-size: 30px; color: #2d7a4f; margin-bottom: 10px; }
.org-card h5 { font-size: 15px; font-weight: 700; color: #1a4731; margin-bottom: 8px; }
.org-card p { font-size: 12.5px; color: #57606a; margin-bottom: 14px; line-height: 1.6; }
.org-link {
    font-size: 12.5px; font-weight: 600; color: #2d7a4f;
    text-decoration: none;
}
.org-link:hover { color: #1a4731; text-decoration: underline; }
.org-link i { margin-left: 4px; }

/* ─── CULTURE ─── */
.culture-item {
    display: flex; gap: 18px; align-items: flex-start;
    background: #f0f7f4; border: 1px solid #c3ddd0;
    border-radius: 10px; padding: 22px 20px; height: 100%;
}
.culture-item > i { font-size: 28px; color: #2d7a4f; flex-shrink: 0; margin-top: 2px; }
.culture-item h5 { font-size: 15px; font-weight: 700; color: #1a4731; margin-bottom: 6px; }
.culture-item p { font-size: 13.5px; color: #374151; line-height: 1.7; margin: 0; }

/* ─── CTA ─── */
.about-cta-box {
    background: linear-gradient(135deg, #1a4731 60%, #2d7a4f 100%);
    border-radius: 16px; padding: 56px 32px; color: #fff;
}
.about-cta-box h3 { font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 10px; }
.about-cta-box p { font-size: 15px; color: #c3ddd0; margin: 0; }
.about-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 26px; border-radius: 8px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    transition: all .2s;
}
.about-btn--primary { background: #3aad6e; color: #fff; border: 2px solid #3aad6e; }
.about-btn--primary:hover { background: #2d7a4f; border-color: #2d7a4f; color: #fff; }
.about-btn--outline { background: transparent; color: #fff; border: 2px solid rgba(255,255,255,.5); }
.about-btn--outline:hover { background: rgba(255,255,255,.1); border-color: #fff; color: #fff; }

@media (max-width: 767px) {
    .about-hero-title { font-size: 26px; }
    .philosophy-pillar { border-right: none; border-bottom: 1px solid rgba(255,255,255,.12); }
    .philosophy-pillar:last-child { border-bottom: none; }
}
</style>
@endpush
