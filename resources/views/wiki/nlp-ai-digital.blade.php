@extends(template().'layouts.app')
@section('title', 'NLP AI & Digital — Trí tuệ nhân tạo')

@section('content')

@include(template().'partials.banner')

<div class="ai-page">
    <div class="container">

        {{-- ══ HERO ══ --}}
        <section class="ai-section py-5" data-aos="fade-up">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="ai-eyebrow">Thành viên NLP Group</span>
                    <h1 class="ai-hero-title">NLP AI & Digital</h1>
                    <p class="ai-hero-subtitle">Ứng dụng trí tuệ nhân tạo trong kỷ nguyên số</p>
                    <p class="ai-hero-desc">
                        <strong>NLP AI & Digital</strong> là đơn vị tiên phong trong NLP Group chuyên nghiên cứu
                        và ứng dụng <strong>trí tuệ nhân tạo</strong> vào các bài toán thực tiễn của ngành năng lượng,
                        ESG và doanh nghiệp. Với nền tảng <strong>LLM chuyên ngành</strong>, hệ thống
                        <strong>AI Agent</strong> và công nghệ <strong>RAG</strong>, NLP AI biến dữ liệu thành
                        trí tuệ hành động — giúp doanh nghiệp ra quyết định nhanh, chính xác và tự động hóa
                        các nghiệp vụ phức tạp.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="#cta" class="ai-btn ai-btn--primary">
                            <i class="fa-light fa-handshake"></i> Hợp tác R&D với NLP Labs
                        </a>
                        <a href="#ai-tech" class="ai-btn ai-btn--outline">
                            <i class="fa-light fa-microchip-ai"></i> Khám phá công nghệ
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="ai-stats-grid">
                        <div class="ai-stat">
                            <i class="fa-light fa-robot"></i>
                            <span class="astat-num">4+</span>
                            <span class="astat-label">AI Agent chuyên biệt</span>
                        </div>
                        <div class="ai-stat">
                            <i class="fa-light fa-brain"></i>
                            <span class="astat-num">LLM</span>
                            <span class="astat-label">Mô hình ngôn ngữ lớn</span>
                        </div>
                        <div class="ai-stat">
                            <i class="fa-light fa-database"></i>
                            <span class="astat-num">RAG</span>
                            <span class="astat-label">Retrieval Augmented Generation</span>
                        </div>
                        <div class="ai-stat">
                            <i class="fa-light fa-leaf"></i>
                            <span class="astat-num">ESG</span>
                            <span class="astat-label">Carbon & Net Zero AI</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="ai-divider">

        {{-- ══ AGENT SKILLS ══ --}}
        <section class="ai-section py-5" data-aos="fade-up">
            <div class="section-label">Agent Skill Modules</div>
            <h2 class="ai-section-title">4 AI Agent chuyên biệt của NLP</h2>
            <p class="ai-section-desc">Các AI Agent được huấn luyện chuyên sâu theo từng lĩnh vực, hoạt động tự động và hỗ trợ quyết định theo thời gian thực.</p>
            <div class="row g-4 mt-2">

                <div class="col-md-6" data-aos="fade-up" data-aos-delay="50">
                    <div class="agent-card">
                        <div class="agent-card-header">
                            <div class="agent-icon"><i class="fa-light fa-solar-panel"></i></div>
                            <div>
                                <div class="agent-tag">Solar Energy</div>
                                <h4 class="agent-title">Solar Energy Advisory</h4>
                            </div>
                        </div>
                        <p class="agent-desc">Tư vấn kỹ thuật điện mặt trời & BESS tự động — tính toán công suất, ROI, thời gian hoàn vốn và lựa chọn thiết bị tối ưu.</p>
                        <ul class="agent-features">
                            <li><i class="fa-light fa-check"></i> Tính toán kWp theo tiêu thụ thực tế</li>
                            <li><i class="fa-light fa-check"></i> Gợi ý BESS khi tự tiêu thụ &lt; 40%</li>
                            <li><i class="fa-light fa-check"></i> Báo cáo CO₂ tiết kiệm tự động</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="agent-card">
                        <div class="agent-card-header">
                            <div class="agent-icon"><i class="fa-light fa-charging-station"></i></div>
                            <div>
                                <div class="agent-tag">EV Charging</div>
                                <h4 class="agent-title">EV Charging Operations</h4>
                            </div>
                        </div>
                        <p class="agent-desc">Quy hoạch trạm sạc xe điện, tích hợp OCPP/CSMS và tư vấn mô hình nhượng quyền hợp tác mặt bằng tối ưu doanh thu.</p>
                        <ul class="agent-features">
                            <li><i class="fa-light fa-check"></i> Phân tích vị trí & mật độ xe EV</li>
                            <li><i class="fa-light fa-check"></i> Đề xuất số lượng AC/DC charger</li>
                            <li><i class="fa-light fa-check"></i> Mô hình chia sẻ doanh thu chi tiết</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6" data-aos="fade-up" data-aos-delay="150">
                    <div class="agent-card">
                        <div class="agent-card-header">
                            <div class="agent-icon"><i class="fa-light fa-leaf"></i></div>
                            <div>
                                <div class="agent-tag">ESG Carbon</div>
                                <h4 class="agent-title">ESG Carbon Accounting</h4>
                            </div>
                        </div>
                        <p class="agent-desc">Hệ thống MRV số, tính toán CO₂ theo chuẩn quốc tế và tự động hóa báo cáo ESG cho doanh nghiệp và nhà đầu tư.</p>
                        <ul class="agent-features">
                            <li><i class="fa-light fa-check"></i> Tính toán Scope 1, 2, 3 emissions</li>
                            <li><i class="fa-light fa-check"></i> MRV số theo chuẩn GHG Protocol</li>
                            <li><i class="fa-light fa-check"></i> Báo cáo ESG tự động, sẵn sàng audit</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="agent-card">
                        <div class="agent-card-header">
                            <div class="agent-icon"><i class="fa-light fa-file-contract"></i></div>
                            <div>
                                <div class="agent-tag">Grant & Partnership</div>
                                <h4 class="agent-title">Grant Pitching & Partnerships</h4>
                            </div>
                        </div>
                        <p class="agent-desc">Tự động hóa hồ sơ xin tài trợ quốc tế, tạo Pitch Deck thông minh và kết nối với mạng lưới đối tác chiến lược toàn cầu.</p>
                        <ul class="agent-features">
                            <li><i class="fa-light fa-check"></i> Phân tích tiêu chí tài trợ GIZ/UNDP</li>
                            <li><i class="fa-light fa-check"></i> Tự động tạo Executive Summary</li>
                            <li><i class="fa-light fa-check"></i> Kết nối Microsoft for Startups, AWS</li>
                        </ul>
                    </div>
                </div>

            </div>
        </section>

        <hr class="ai-divider">

        {{-- ══ AI TECHNOLOGIES ══ --}}
        <section class="ai-section py-5" id="ai-tech" data-aos="fade-up">
            <div class="section-label">Công nghệ AI</div>
            <h2 class="ai-section-title">8 công nghệ AI cốt lõi</h2>
            <p class="ai-section-desc">Nền tảng công nghệ đa tầng — từ mô hình ngôn ngữ đến thị giác máy và tự động hóa thông minh.</p>
            <div class="row g-4 mt-2">

                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="50">
                    <div class="ai-tech-card">
                        <div class="ai-tech-icon"><i class="fa-light fa-robot"></i></div>
                        <h4 class="ai-tech-title">AI Agent</h4>
                        <p class="ai-tech-desc">Tác nhân AI tự chủ, xử lý đa bước, tương tác công cụ và ra quyết định tự động theo mục tiêu.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="ai-tech-card">
                        <div class="ai-tech-icon"><i class="fa-light fa-brain"></i></div>
                        <h4 class="ai-tech-title">RAG / LLM Chuyên ngành</h4>
                        <p class="ai-tech-desc">Retrieval Augmented Generation với LLM fine-tuned cho năng lượng, ESG và thị trường Việt Nam.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="150">
                    <div class="ai-tech-card">
                        <div class="ai-tech-icon"><i class="fa-light fa-eye"></i></div>
                        <h4 class="ai-tech-title">Computer Vision</h4>
                        <p class="ai-tech-desc">Nhận dạng hình ảnh, phát hiện bất thường thiết bị, kiểm tra chất lượng tấm pin bằng AI.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="ai-tech-card">
                        <div class="ai-tech-icon"><i class="fa-light fa-chart-network"></i></div>
                        <h4 class="ai-tech-title">Machine Learning</h4>
                        <p class="ai-tech-desc">Dự báo sản lượng điện, tối ưu lịch sạc EV, phát hiện gian lận và phân khúc khách hàng.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="50">
                    <div class="ai-tech-card">
                        <div class="ai-tech-icon"><i class="fa-light fa-comments"></i></div>
                        <h4 class="ai-tech-title">AI Chatbot</h4>
                        <p class="ai-tech-desc">Chatbot đa kênh tích hợp Zalo, Website, Messenger — hỗ trợ khách hàng 24/7 tự động.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="ai-tech-card">
                        <div class="ai-tech-icon"><i class="fa-light fa-gears"></i></div>
                        <h4 class="ai-tech-title">AI Automation</h4>
                        <p class="ai-tech-desc">RPA thông minh, tự động hóa quy trình dựa trên AI — xử lý tài liệu, workflow phức tạp.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="150">
                    <div class="ai-tech-card">
                        <div class="ai-tech-icon"><i class="fa-light fa-chart-bar"></i></div>
                        <h4 class="ai-tech-title">Phân tích dữ liệu</h4>
                        <p class="ai-tech-desc">Dashboard BI, phân tích xu hướng, khai phá dữ liệu năng lượng và ESG theo thời gian thực.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="ai-tech-card">
                        <div class="ai-tech-icon"><i class="fa-light fa-language"></i></div>
                        <h4 class="ai-tech-title">NLP Processing</h4>
                        <p class="ai-tech-desc">Xử lý ngôn ngữ tự nhiên tiếng Việt — phân tích văn bản, trích xuất thông tin và phân loại tài liệu.</p>
                    </div>
                </div>

            </div>
        </section>

        <hr class="ai-divider">

        {{-- ══ RESEARCH DIRECTIONS ══ --}}
        <section class="ai-section py-5" data-aos="fade-up">
            <div class="section-label">Định hướng nghiên cứu</div>
            <h2 class="ai-section-title">NLP Labs Research</h2>
            <div class="row g-4 mt-2">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="research-card">
                        <div class="research-icon"><i class="fa-brands fa-github"></i></div>
                        <h5 class="research-title">Open Source AI</h5>
                        <p class="research-desc">Đóng góp mô hình mã nguồn mở cho cộng đồng AI Việt Nam — phát triển các công cụ NLP cho tiếng Việt.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="research-card">
                        <div class="research-icon"><i class="fa-light fa-solar-panel"></i></div>
                        <h5 class="research-title">RAG cho Năng lượng</h5>
                        <p class="research-desc">Xây dựng knowledge base và RAG pipeline chuyên biệt cho lĩnh vực năng lượng tái tạo, ESG và tín chỉ carbon.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="research-card">
                        <div class="research-icon"><i class="fa-light fa-flag"></i></div>
                        <h5 class="research-title">Mô hình tiếng Việt</h5>
                        <p class="research-desc">Fine-tuning LLM cho ngữ cảnh tiếng Việt và văn hóa kinh doanh Việt Nam — hiểu đặc thù pháp lý và ngành nghề địa phương.</p>
                    </div>
                </div>
            </div>
        </section>

        <hr class="ai-divider">

        {{-- ══ PARTNERSHIPS ══ --}}
        <section class="ai-section py-5" data-aos="fade-up">
            <div class="section-label">Hợp tác chiến lược</div>
            <h2 class="ai-section-title">Đối tác công nghệ mục tiêu</h2>
            <p class="ai-section-desc">NLP AI & Digital tích cực tìm kiếm và xây dựng quan hệ đối tác với các tổ chức công nghệ hàng đầu thế giới.</p>
            <div class="row g-3 mt-2">
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="50">
                    <div class="partner-card">
                        <i class="fa-brands fa-microsoft"></i>
                        <span>Microsoft for Startups</span>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="partner-card">
                        <i class="fa-brands fa-aws"></i>
                        <span>AWS Activate</span>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="150">
                    <div class="partner-card">
                        <i class="fa-brands fa-google"></i>
                        <span>Google Cloud for Startups</span>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="partner-card">
                        <i class="fa-brands fa-github"></i>
                        <span>GitHub Sponsors</span>
                    </div>
                </div>
            </div>
        </section>

        <hr class="ai-divider">

        {{-- ══ NLP LABS VIETNAM LINK ══ --}}
        <section class="ai-section py-5" data-aos="fade-up">
            <div class="nlp-labs-banner">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <div class="nlp-labs-icon"><i class="fa-light fa-flask"></i></div>
                        <h3>NLP Labs Vietnam</h3>
                        <p>Trung tâm nghiên cứu và phát triển công nghệ năng lượng của NLP Group — nơi các giải pháp AI & kỹ thuật được kiểm chứng và hoàn thiện trước khi triển khai thực tế.</p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="{{ route('wiki', 'nlp-labs-vietnam') }}" class="ai-btn ai-btn--labs">
                            <i class="fa-light fa-arrow-right"></i> Khám phá NLP Labs
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ CTA ══ --}}
        <section class="ai-cta-section py-5 mb-5" id="cta" data-aos="fade-up">
            <div class="ai-cta-box">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h3>Hợp tác R&D với NLP Labs</h3>
                        <p>Cùng xây dựng giải pháp AI chuyên biệt cho ngành năng lượng, ESG và thị trường Việt Nam. Chúng tôi tìm kiếm đối tác nghiên cứu, nhà đầu tư và khách hàng tiên phong.</p>
                        <div class="ai-cta-features">
                            <span><i class="fa-light fa-check-circle"></i> R&D chung</span>
                            <span><i class="fa-light fa-check-circle"></i> Pilot AI miễn phí</span>
                            <span><i class="fa-light fa-check-circle"></i> Co-develop sản phẩm</span>
                            <span><i class="fa-light fa-check-circle"></i> Chia sẻ IP</span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="{{ route('page', 'contact') }}" class="ai-btn ai-btn--cta">
                            <i class="fa-light fa-envelope"></i> Liên hệ hợp tác
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>{{-- /.container --}}
</div>{{-- /.ai-page --}}
@endsection

@push('style')
<style>
/* ═══════════════════════════════════════
   NLP AI & DIGITAL PAGE
   Primary: #0f172a | Accent: #7c3aed | Light: #a78bfa
═══════════════════════════════════════ */
.ai-page { background: #fff; min-height: 80vh; }
.ai-divider { border: none; border-top: 1px solid #e0d9f7; margin: 0; }
.section-label {
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: #7c3aed; margin-bottom: 6px;
}
.ai-section-title { font-size: 26px; font-weight: 700; color: #1e1b4b; margin-bottom: 8px; }
.ai-section-desc { color: #57606a; font-size: 15px; max-width: 680px; }

/* Hero */
.ai-eyebrow {
    display: inline-block; font-size: 12px; font-weight: 700;
    letter-spacing: 1.2px; text-transform: uppercase;
    color: #7c3aed; margin-bottom: 10px;
}
.ai-hero-title { font-size: 40px; font-weight: 800; color: #1e1b4b; margin-bottom: 4px; }
.ai-hero-subtitle { font-size: 17px; color: #4c1d95; font-weight: 600; margin-bottom: 16px; }
.ai-hero-desc { font-size: 15px; color: #374151; line-height: 1.8; margin-bottom: 24px; }

/* Buttons */
.ai-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 24px; border-radius: 8px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    transition: all .2s;
}
.ai-btn--primary { background: #1e1b4b; color: #fff; border: 2px solid #1e1b4b; }
.ai-btn--primary:hover { background: #4c1d95; border-color: #4c1d95; color: #fff; }
.ai-btn--outline { background: transparent; color: #1e1b4b; border: 2px solid #1e1b4b; }
.ai-btn--outline:hover { background: #1e1b4b; color: #fff; }
.ai-btn--labs { background: #1a4731; color: #fff; border: 2px solid #1a4731; }
.ai-btn--labs:hover { background: #2d7a4f; border-color: #2d7a4f; color: #fff; }
.ai-btn--cta { background: #fff; color: #1e1b4b; border: 2px solid #fff; font-size: 15px; padding: 14px 28px; }
.ai-btn--cta:hover { background: #a78bfa; border-color: #a78bfa; color: #1e1b4b; }

/* Stats */
.ai-stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.ai-stat {
    background: #f5f3ff; border: 1px solid #ddd6fe;
    border-radius: 12px; padding: 20px 16px; text-align: center;
    display: flex; flex-direction: column; align-items: center; gap: 4px;
}
.ai-stat i { font-size: 24px; color: #7c3aed; margin-bottom: 6px; }
.astat-num { font-size: 22px; font-weight: 800; color: #1e1b4b; }
.astat-label { font-size: 12px; color: #57606a; font-weight: 500; text-align: center; }

/* Agent cards */
.agent-card {
    background: #1e1b4b; border: 1px solid #312e81;
    border-radius: 14px; padding: 24px 22px; height: 100%;
    color: #e0e7ff;
    transition: transform .2s, box-shadow .2s;
}
.agent-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(124,58,237,.2); }
.agent-card-header { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 14px; }
.agent-icon { font-size: 28px; color: #a78bfa; flex-shrink: 0; margin-top: 2px; }
.agent-tag {
    display: inline-block; background: #312e81; color: #a78bfa;
    font-size: 10px; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase; padding: 2px 8px; border-radius: 10px; margin-bottom: 4px;
}
.agent-title { font-size: 16px; font-weight: 700; color: #e0e7ff; margin: 0; }
.agent-desc { font-size: 13.5px; color: #c7d2fe; line-height: 1.7; margin-bottom: 14px; }
.agent-features { list-style: none; padding: 0; margin: 0; }
.agent-features li {
    font-size: 12.5px; color: #c7d2fe; padding: 4px 0;
    display: flex; align-items: center; gap: 8px;
}
.agent-features li i { color: #a78bfa; font-size: 12px; }

/* AI tech cards */
.ai-tech-card {
    border: 1px solid #ddd6fe; border-radius: 12px;
    padding: 22px 18px; height: 100%; background: #faf5ff;
    transition: transform .2s, box-shadow .2s;
}
.ai-tech-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(124,58,237,.12); }
.ai-tech-icon { font-size: 30px; color: #7c3aed; margin-bottom: 12px; }
.ai-tech-title { font-size: 15px; font-weight: 700; color: #1e1b4b; margin-bottom: 8px; }
.ai-tech-desc { font-size: 13px; color: #57606a; line-height: 1.6; margin: 0; }

/* Research cards */
.research-card {
    border: 1px solid #d8e8df; border-radius: 12px;
    padding: 22px 18px; height: 100%; background: #f0f7f4;
    transition: transform .2s;
}
.research-card:hover { transform: translateY(-3px); }
.research-icon { font-size: 28px; color: #2d7a4f; margin-bottom: 12px; }
.research-title { font-size: 15px; font-weight: 700; color: #1a4731; margin-bottom: 8px; }
.research-desc { font-size: 13px; color: #57606a; line-height: 1.6; margin: 0; }

/* Partner cards */
.partner-card {
    border: 1px solid #ddd6fe; border-radius: 10px;
    padding: 18px 12px; text-align: center;
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    background: #f5f3ff; transition: transform .2s, box-shadow .2s;
}
.partner-card:hover { transform: translateY(-3px); box-shadow: 0 4px 14px rgba(124,58,237,.1); }
.partner-card i { font-size: 26px; color: #7c3aed; }
.partner-card span { font-size: 12px; font-weight: 600; color: #1e1b4b; text-align: center; }

/* NLP Labs banner */
.nlp-labs-banner {
    background: linear-gradient(135deg, #1a4731 0%, #0f2d1e 100%);
    border-radius: 16px; padding: 32px 36px; color: #fff;
}
.nlp-labs-banner h3 { font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 10px; }
.nlp-labs-banner p { font-size: 14px; color: #c3ddd0; margin: 0; }
.nlp-labs-icon { font-size: 28px; color: #3aad6e; margin-bottom: 10px; }

/* CTA */
.ai-cta-box {
    background: linear-gradient(135deg, #1e1b4b 60%, #4c1d95 100%);
    border-radius: 16px; padding: 44px 40px; color: #fff;
}
.ai-cta-box h3 { font-size: 24px; font-weight: 700; color: #fff; margin-bottom: 10px; }
.ai-cta-box p { font-size: 14.5px; color: #c7d2fe; margin-bottom: 18px; }
.ai-cta-features {
    display: flex; flex-wrap: wrap; gap: 14px;
    font-size: 13px; color: #c7d2fe;
}
.ai-cta-features i { color: #a78bfa; margin-right: 5px; }

@media (max-width: 767px) {
    .ai-hero-title { font-size: 28px; }
    .ai-stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
}
</style>
@endpush
