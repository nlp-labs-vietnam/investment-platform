@extends(template().'layouts.app')
@section('title', 'Ủng hộ — Donate')

@push('style')
<style>
/* ── Donate Page ──────────────────────────────────────────── */
.donate-hero {
    background: linear-gradient(135deg, #1a4731 0%, #2d7a4f 60%, #3aad6e 100%);
    padding: 80px 0 60px;
    color: #fff;
    text-align: center;
}
.donate-hero .hero-icon {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: .9;
}
.donate-hero h1 {
    font-size: 2.4rem;
    font-weight: 700;
    margin-bottom: 12px;
}
.donate-hero p {
    font-size: 1.1rem;
    opacity: .85;
    max-width: 620px;
    margin: 0 auto;
}

/* ── Section wrapper ─────────────────────────────────────── */
.donate-section {
    padding: 60px 0;
    background: #f7f9f7;
}
.donate-section + .donate-section {
    background: #fff;
}

/* ── Tab nav ─────────────────────────────────────────────── */
.donate-tabs {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
}
.donate-tab-btn {
    padding: 10px 28px;
    border: 2px solid #2d7a4f;
    border-radius: 50px;
    background: transparent;
    color: #2d7a4f;
    font-weight: 600;
    font-size: .95rem;
    cursor: pointer;
    transition: all .2s;
}
.donate-tab-btn.active,
.donate-tab-btn:hover {
    background: #2d7a4f;
    color: #fff;
}

/* ── Bank card ───────────────────────────────────────────── */
.bank-card {
    background: #fff;
    border: 1px solid #e2e8e4;
    border-radius: 16px;
    padding: 32px 28px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    transition: box-shadow .2s;
}
.bank-card:hover {
    box-shadow: 0 6px 24px rgba(45,122,79,.15);
}
.bank-card .bank-name {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1a4731;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.bank-card .bank-name i {
    color: #2d7a4f;
}
.bank-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px dashed #e8f0eb;
    gap: 12px;
}
.bank-row:last-of-type { border-bottom: none; }
.bank-row .label {
    font-size: .82rem;
    color: #7a9487;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .04em;
    min-width: 110px;
}
.bank-row .value {
    font-size: 1rem;
    color: #1f2328;
    font-weight: 600;
    flex: 1;
    text-align: right;
    word-break: break-all;
}
.copy-btn {
    background: #eaf5ee;
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: .8rem;
    color: #2d7a4f;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
    white-space: nowrap;
}
.copy-btn:hover { background: #2d7a4f; color: #fff; }
.copy-btn.copied { background: #2d7a4f; color: #fff; }

/* ── Crypto card ─────────────────────────────────────────── */
.crypto-card {
    background: #fff;
    border: 1px solid #e2e8e4;
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.crypto-card .crypto-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1a4731;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.crypto-address {
    background: #f7f9f7;
    border: 1px solid #d8e8dd;
    border-radius: 10px;
    padding: 14px 16px;
    font-family: 'Courier New', monospace;
    font-size: .88rem;
    color: #2d4a36;
    word-break: break-all;
    margin-bottom: 12px;
}

/* ── Impact stats ────────────────────────────────────────── */
.impact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
    margin-top: 32px;
}
.impact-card {
    background: #fff;
    border-radius: 14px;
    padding: 28px 20px;
    text-align: center;
    border: 1px solid #e2e8e4;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}
.impact-card .impact-icon {
    font-size: 2.2rem;
    color: #2d7a4f;
    margin-bottom: 10px;
}
.impact-card .impact-number {
    font-size: 2rem;
    font-weight: 800;
    color: #1a4731;
}
.impact-card .impact-label {
    font-size: .88rem;
    color: #7a9487;
    margin-top: 4px;
}

/* ── FAQ ─────────────────────────────────────────────────── */
.donate-faq .faq-item {
    border: 1px solid #e2e8e4;
    border-radius: 12px;
    margin-bottom: 12px;
    overflow: hidden;
}
.donate-faq .faq-question {
    padding: 18px 22px;
    font-weight: 600;
    color: #1a4731;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    transition: background .2s;
}
.donate-faq .faq-question:hover { background: #f0f8f3; }
.donate-faq .faq-question i { transition: transform .3s; }
.donate-faq .faq-answer {
    padding: 0 22px;
    max-height: 0;
    overflow: hidden;
    transition: max-height .35s ease, padding .2s;
    background: #fafcfb;
    font-size: .95rem;
    color: #4a6657;
    line-height: 1.7;
}
.donate-faq .faq-item.open .faq-answer {
    max-height: 300px;
    padding: 16px 22px;
}
.donate-faq .faq-item.open .faq-question i {
    transform: rotate(180deg);
}

/* ── Section title ───────────────────────────────────────── */
.section-title-donate {
    text-align: center;
    margin-bottom: 40px;
}
.section-title-donate h2 {
    font-size: 1.9rem;
    font-weight: 700;
    color: #1a4731;
}
.section-title-donate p {
    color: #6b8a77;
    margin-top: 8px;
    font-size: 1rem;
}
.section-title-donate .divider {
    width: 50px;
    height: 3px;
    background: #2d7a4f;
    margin: 12px auto 0;
    border-radius: 2px;
}

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 576px) {
    .donate-hero h1 { font-size: 1.7rem; }
    .bank-row { flex-direction: column; align-items: flex-start; }
    .bank-row .value { text-align: left; }
}
</style>
@endpush

@section('content')

{{-- ══ HERO ══════════════════════════════════════════════════════════════ --}}
<section class="donate-hero">
    <div class="container">
        <div class="hero-icon"><i class="fa-light fa-heart-pulse"></i></div>
        <h1>@lang('Ủng hộ NLP Group')</h1>
        <p>@lang('Mỗi đóng góp của bạn giúp chúng tôi mang năng lượng sạch đến nhiều hộ gia đình và doanh nghiệp Việt Nam hơn.')</p>
    </div>
</section>

{{-- ══ IMPACT STATS ════════════════════════════════════════════════════ --}}
<section class="donate-section">
    <div class="container">
        <div class="section-title-donate">
            <h2>@lang('Tác động của bạn')</h2>
            <p>@lang('Những con số thực tế từ sứ mệnh của chúng tôi')</p>
            <div class="divider"></div>
        </div>
        <div class="impact-grid">
            <div class="impact-card" data-aos="fade-up">
                <div class="impact-icon"><i class="fa-light fa-solar-panel"></i></div>
                <div class="impact-number">150+</div>
                <div class="impact-label">@lang('Dự án solar hoàn thành')</div>
            </div>
            <div class="impact-card" data-aos="fade-up" data-aos-delay="100">
                <div class="impact-icon"><i class="fa-light fa-bolt"></i></div>
                <div class="impact-number">12 MW</div>
                <div class="impact-label">@lang('Công suất lắp đặt')</div>
            </div>
            <div class="impact-card" data-aos="fade-up" data-aos-delay="200">
                <div class="impact-icon"><i class="fa-light fa-leaf"></i></div>
                <div class="impact-number">8,400 T</div>
                <div class="impact-label">@lang('CO₂ tiết kiệm / năm')</div>
            </div>
            <div class="impact-card" data-aos="fade-up" data-aos-delay="300">
                <div class="impact-icon"><i class="fa-light fa-users"></i></div>
                <div class="impact-number">2,000+</div>
                <div class="impact-label">@lang('Hộ gia đình hưởng lợi')</div>
            </div>
        </div>
    </div>
</section>

{{-- ══ DONATE METHODS ══════════════════════════════════════════════════ --}}
<section class="donate-section">
    <div class="container">
        <div class="section-title-donate">
            <h2>@lang('Hình thức đóng góp')</h2>
            <p>@lang('Chọn phương thức phù hợp với bạn')</p>
            <div class="divider"></div>
        </div>

        {{-- Tab buttons --}}
        <div class="donate-tabs">
            <button class="donate-tab-btn active" data-tab="bank">
                <i class="fa-light fa-building-columns me-2"></i>@lang('Chuyển khoản ngân hàng')
            </button>
            <button class="donate-tab-btn" data-tab="crypto">
                <i class="fa-brands fa-bitcoin me-2"></i>@lang('Crypto / Web3')
            </button>
        </div>

        {{-- ── Bank Transfer ── --}}
        <div id="tab-bank" class="tab-pane">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @foreach($bankAccounts as $acc)
                    <div class="bank-card" data-aos="fade-up">
                        <div class="bank-name">
                            <i class="fa-light fa-building-columns"></i>
                            {{ $acc['bank'] }}
                        </div>

                        <div class="bank-row">
                            <span class="label">@lang('Số tài khoản')</span>
                            <span class="value" id="acc-{{ $loop->index }}">{{ $acc['number'] }}</span>
                            <button class="copy-btn" onclick="copyText('{{ $acc['number'] }}', this)">
                                <i class="fa-light fa-copy me-1"></i>@lang('Copy')
                            </button>
                        </div>
                        <div class="bank-row">
                            <span class="label">@lang('Chủ tài khoản')</span>
                            <span class="value">{{ $acc['name'] }}</span>
                        </div>
                        <div class="bank-row">
                            <span class="label">@lang('Chi nhánh')</span>
                            <span class="value">{{ $acc['branch'] }}</span>
                        </div>
                        <div class="bank-row">
                            <span class="label">@lang('Nội dung CK')</span>
                            <span class="value" id="note-{{ $loop->index }}">DONATE NLP GROUP [Tên của bạn]</span>
                            <button class="copy-btn" onclick="copyText('DONATE NLP GROUP', this)">
                                <i class="fa-light fa-copy me-1"></i>@lang('Copy')
                            </button>
                        </div>
                    </div>
                    @endforeach

                    {{-- VietQR hint --}}
                    <div class="text-center mt-2 mb-4" style="color:#7a9487; font-size:.9rem;">
                        <i class="fa-light fa-circle-info me-1"></i>
                        @lang('Bạn cũng có thể quét QR qua app ngân hàng bằng cách nhập số tài khoản trên VietQR.')
                        <a href="https://vietqr.io" target="_blank" class="ms-1" style="color:#2d7a4f;">VietQR.io</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Crypto ── --}}
        <div id="tab-crypto" class="tab-pane" style="display:none;">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @foreach($cryptoWallets as $wallet)
                    <div class="crypto-card" data-aos="fade-up">
                        <div class="crypto-name">
                            <i class="{{ $wallet['icon'] }} fa-lg"></i>
                            {{ $wallet['name'] }}
                        </div>
                        <div class="crypto-address" id="wallet-{{ $loop->index }}">{{ $wallet['address'] }}</div>
                        <button class="copy-btn" onclick="copyText('{{ $wallet['address'] }}', this)">
                            <i class="fa-light fa-copy me-1"></i>@lang('Sao chép địa chỉ ví')
                        </button>
                    </div>
                    @endforeach

                    <div class="text-center mt-2" style="color:#7a9487; font-size:.88rem;">
                        <i class="fa-light fa-triangle-exclamation me-1 text-warning"></i>
                        @lang('Chỉ gửi đúng loại token tương ứng với mạng lưới. NLP Group không chịu trách nhiệm nếu gửi sai mạng.')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ FAQ ═════════════════════════════════════════════════════════════ --}}
<section class="donate-section">
    <div class="container">
        <div class="section-title-donate">
            <h2>@lang('Câu hỏi thường gặp')</h2>
            <div class="divider"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="donate-faq">
                    @php
                    $faqs = [
                        ['q' => 'Tiền donate được dùng để làm gì?',
                         'a' => 'Toàn bộ đóng góp được dành cho các hoạt động nghiên cứu & phát triển năng lượng tái tạo, hỗ trợ cộng đồng nông thôn lắp đặt điện mặt trời, và vận hành nền tảng đầu tư mở NLPGroup.vn.'],
                        ['q' => 'Tôi có nhận được biên lai hay giấy xác nhận không?',
                         'a' => 'Sau khi chuyển khoản, vui lòng chụp màn hình giao dịch và gửi về email support@nlpgroup.vn kèm tên và số điện thoại. Chúng tôi sẽ gửi xác nhận trong vòng 24h.'],
                        ['q' => 'Số tiền tối thiểu để donate là bao nhiêu?',
                         'a' => 'Không có giới hạn tối thiểu. Mọi đóng góp dù nhỏ đều có ý nghĩa với chúng tôi.'],
                        ['q' => 'Tôi có thể donate định kỳ hàng tháng không?',
                         'a' => 'Hoàn toàn được. Bạn có thể đặt lệnh chuyển khoản tự động định kỳ qua app ngân hàng với nội dung DONATE NLP GROUP [Tên của bạn].'],
                        ['q' => 'Có thể donate bằng USD hoặc ngoại tệ khác không?',
                         'a' => 'Hiện tại chúng tôi nhận VND qua ngân hàng trong nước và USDT/ETH qua ví crypto. Nếu bạn ở nước ngoài, vui lòng liên hệ support@nlpgroup.vn để được hỗ trợ.'],
                    ];
                    @endphp

                    @foreach($faqs as $i => $faq)
                    <div class="faq-item" data-aos="fade-up" data-aos-delay="{{ $i * 60 }}">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            <span>{{ $faq['q'] }}</span>
                            <i class="fa-light fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">{{ $faq['a'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ CTA ═════════════════════════════════════════════════════════════ --}}
<section style="background: linear-gradient(135deg, #1a4731, #2d7a4f); padding: 60px 0; text-align:center; color:#fff;">
    <div class="container">
        <h2 style="font-size:1.9rem; font-weight:700; margin-bottom:12px;">@lang('Cùng nhau tạo nên sự khác biệt')</h2>
        <p style="opacity:.85; font-size:1rem; margin-bottom:28px; max-width:560px; margin-left:auto; margin-right:auto;">
            @lang('Chia sẻ trang này với bạn bè và gia đình để lan toả sứ mệnh năng lượng xanh.')
        </p>
        <div style="display:flex; gap:14px; justify-content:center; flex-wrap:wrap;">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
               target="_blank"
               style="background:#fff; color:#2d7a4f; padding:12px 28px; border-radius:50px; font-weight:700; text-decoration:none; font-size:.95rem;">
                <i class="fa-brands fa-facebook me-2"></i>Facebook
            </a>
            <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode('Ủng hộ NLP Group — năng lượng tái tạo Việt Nam') }}"
               target="_blank"
               style="background:rgba(255,255,255,.15); color:#fff; border:2px solid rgba(255,255,255,.5); padding:12px 28px; border-radius:50px; font-weight:700; text-decoration:none; font-size:.95rem;">
                <i class="fa-brands fa-telegram me-2"></i>Telegram
            </a>
        </div>
    </div>
</section>

@endsection

@push('script')
<script>
// ── Tab switching ──────────────────────────────────────────
document.querySelectorAll('.donate-tab-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.donate-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.style.display = 'none');
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab).style.display = 'block';
    });
});

// ── Copy to clipboard ──────────────────────────────────────
function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fa-light fa-check me-1"></i>Đã copy!';
        btn.classList.add('copied');
        setTimeout(() => { btn.innerHTML = original; btn.classList.remove('copied'); }, 2000);
    });
}

// ── FAQ accordion ──────────────────────────────────────────
function toggleFaq(el) {
    const item = el.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
}
</script>
@endpush
