<?php

namespace App\Console\Commands;

use App\Models\Language;
use App\Models\Page;
use Illuminate\Console\Command;

class SeedNlpPages extends Command
{
    protected $signature   = 'nlp:seed-pages {--force : Overwrite existing content}';
    protected $description = 'Seed full HTML content for NLP ecosystem pages';

    /** HTML content keyed by page slug */
    private function pages(): array
    {
        return [
            'nlp-energy'      => $this->energyContent(),
            'nlp-ev-charging' => $this->evChargingContent(),
            'nlp-technology'  => $this->technologyContent(),
            'nlp-ai-digital'  => $this->aiDigitalContent(),
            'nlp-esg-carbon'  => $this->esgCarbonContent(),
            'ecosystem'       => $this->ecosystemContent(),
        ];
    }

    public function handle(): int
    {
        $langId = Language::where('default_status', 1)->value('id');

        if (! $langId) {
            $this->error('No default language found.');
            return self::FAILURE;
        }

        foreach ($this->pages() as $slug => $html) {
            $page = Page::where('slug', $slug)->where('template_name', 'green')->first();

            if (! $page) {
                $this->warn("Page slug='{$slug}' not found – skipping.");
                continue;
            }

            $detail = $page->manyDetails()->where('language_id', $langId)->first();

            if ($detail && ! $this->option('force')) {
                // Only update if content is still the placeholder
                if (! str_starts_with(trim($detail->content ?? ''), '[[')) {
                    $this->line("  skip  {$slug} (already has real content; use --force to overwrite)");
                    continue;
                }
            }

            $page->details()->updateOrCreate(
                ['language_id' => $langId],
                ['name' => $page->name ?? $slug, 'content' => $html, 'sections' => null]
            );

            $this->info("  ✓  {$slug}");
        }

        $this->info('Done.');
        return self::SUCCESS;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CONTENT METHODS
    // ─────────────────────────────────────────────────────────────────────────

    private function energyContent(): string
    {
        return <<<'HTML'
<div class="nlp-page-content">
  <section class="nlp-hero" style="background:linear-gradient(135deg,#1a3c2e 0%,#2d6a4f 100%);color:#fff;padding:80px 0 60px">
    <div class="container text-center">
      <span style="background:#52b788;color:#fff;padding:6px 18px;border-radius:20px;font-size:13px;letter-spacing:1px">NLP ENERGY</span>
      <h1 style="font-size:2.6rem;font-weight:700;margin:20px 0 16px">Giải pháp năng lượng xanh<br>Kiến tạo tương lai bền vững</h1>
      <p style="font-size:1.1rem;opacity:.9;max-width:620px;margin:0 auto 32px">Đồng hành cùng doanh nghiệp và hộ gia đình trong hành trình chuyển đổi sang năng lượng sạch, tiết kiệm và hiệu quả.</p>
      <a href="/contact" style="background:#52b788;color:#fff;padding:14px 36px;border-radius:8px;text-decoration:none;font-weight:600">Đặt lịch khảo sát miễn phí →</a>
    </div>
  </section>

  <section style="padding:60px 0;background:#f8fffe">
    <div class="container">
      <div class="text-center mb-5"><h2 style="color:#1a3c2e;font-weight:700">Các giải pháp của NLP Energy</h2></div>
      <div class="row g-4">
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:12px">☀️</div>
            <h4 style="color:#1a3c2e;font-weight:700">Điện mặt trời áp mái</h4>
            <p style="color:#555">Lắp đặt hệ thống điện mặt trời toàn diện cho nhà dân, nhà xưởng, khu công nghiệp và tòa nhà thương mại.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:12px">🔋</div>
            <h4 style="color:#1a3c2e;font-weight:700">Lưu trữ năng lượng (BESS)</h4>
            <p style="color:#555">Hệ thống pin lithium lưu trữ điện mặt trời, cấp điện dự phòng và tối ưu chi phí theo giờ cao điểm.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:12px">⚡</div>
            <h4 style="color:#1a3c2e;font-weight:700">Quản lý năng lượng (EMS)</h4>
            <p style="color:#555">Nền tảng giám sát, phân tích và tối ưu hóa toàn bộ hệ thống năng lượng theo thời gian thực.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:12px">🔧</div>
            <h4 style="color:#1a3c2e;font-weight:700">Thiết kế – Thi công (EPC)</h4>
            <p style="color:#555">Giải pháp trọn gói từ khảo sát, thiết kế kỹ thuật, thi công lắp đặt đến nghiệm thu bàn giao.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:12px">🛠️</div>
            <h4 style="color:#1a3c2e;font-weight:700">Vận hành & Bảo trì (O&M)</h4>
            <p style="color:#555">Duy trì hiệu suất hệ thống qua kiểm tra định kỳ, vệ sinh tấm pin, giám sát từ xa và xử lý sự cố.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:12px">📉</div>
            <h4 style="color:#1a3c2e;font-weight:700">Tiết kiệm năng lượng</h4>
            <p style="color:#555">Tư vấn và triển khai các giải pháp giảm tiêu thụ điện, tối ưu thiết bị và quy trình vận hành.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section style="padding:60px 0;background:#fff">
    <div class="container">
      <div class="text-center mb-5"><h2 style="color:#1a3c2e;font-weight:700">Quy trình triển khai dự án</h2></div>
      <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:12px">
        @foreach(['Khảo sát','Tư vấn giải pháp','Thiết kế kỹ thuật','Báo giá','Thi công lắp đặt','Nghiệm thu','Vận hành & Bảo trì'] as $i => $step)
        <div style="display:flex;align-items:center;gap:8px">
          <div style="background:#2d6a4f;color:#fff;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px">{{$i+1}}</div>
          <span style="color:#1a3c2e;font-weight:600">{{$step}}</span>
          @if($i < 6)<span style="color:#52b788;font-size:1.2rem">→</span>@endif
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <section style="padding:60px 0;background:linear-gradient(135deg,#1a3c2e 0%,#2d6a4f 100%);color:#fff;text-align:center">
    <div class="container">
      <h2 style="font-weight:700;margin-bottom:12px">Sẵn sàng chuyển đổi năng lượng?</h2>
      <p style="opacity:.9;margin-bottom:28px;font-size:1.05rem">Đội ngũ chuyên gia NLP Energy sẽ khảo sát và tư vấn giải pháp phù hợp nhất cho bạn — hoàn toàn miễn phí.</p>
      <a href="/contact" style="background:#52b788;color:#fff;padding:14px 36px;border-radius:8px;text-decoration:none;font-weight:600;margin-right:12px">Đặt lịch khảo sát</a>
      <a href="/solutions" style="background:transparent;border:2px solid #fff;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600">Xem giải pháp khác</a>
    </div>
  </section>
</div>
HTML;
    }

    private function evChargingContent(): string
    {
        return <<<'HTML'
<div class="nlp-page-content">
  <section class="nlp-hero" style="background:linear-gradient(135deg,#0d1b2a 0%,#1b4f72 100%);color:#fff;padding:80px 0 60px">
    <div class="container text-center">
      <span style="background:#2e86c1;color:#fff;padding:6px 18px;border-radius:20px;font-size:13px;letter-spacing:1px">NLP EV CHARGING</span>
      <h1 style="font-size:2.6rem;font-weight:700;margin:20px 0 16px">Kiến tạo hạ tầng giao thông điện hóa<br>Kết nối tương lai di chuyển xanh</h1>
      <p style="font-size:1.1rem;opacity:.9;max-width:620px;margin:0 auto 32px">Phát triển mạng lưới trạm sạc xe điện thông minh, đáp ứng nhu cầu của cá nhân, doanh nghiệp và đô thị hiện đại.</p>
      <a href="/contact" style="background:#2e86c1;color:#fff;padding:14px 36px;border-radius:8px;text-decoration:none;font-weight:600">Khảo sát địa điểm miễn phí →</a>
    </div>
  </section>

  <section style="padding:60px 0;background:#f0f7ff">
    <div class="container">
      <div class="text-center mb-5"><h2 style="color:#0d1b2a;font-weight:700">Các giải pháp sạc xe điện</h2></div>
      <div class="row g-4">
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:10px">🔌</div>
            <h5 style="color:#0d1b2a;font-weight:700">Trụ sạc AC</h5>
            <p style="color:#555;font-size:.95rem">Phù hợp nhà riêng, chung cư, văn phòng, khách sạn. Chi phí hợp lý, vận hành ổn định, sạc qua đêm.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:10px">⚡</div>
            <h5 style="color:#0d1b2a;font-weight:700">Trụ sạc DC</h5>
            <p style="color:#555;font-size:.95rem">Sạc nhanh 20–120kW+ cho trạm dừng nghỉ, cao tốc, trung tâm thương mại và đội xe thương mại.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:10px">🖥️</div>
            <h5 style="color:#0d1b2a;font-weight:700">Phần mềm CSMS</h5>
            <p style="color:#555;font-size:.95rem">Quản lý toàn bộ trạng thái trụ sạc, người dùng, doanh thu và vận hành từ một trung tâm điều khiển.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:10px">📱</div>
            <h5 style="color:#0d1b2a;font-weight:700">Ứng dụng người dùng</h5>
            <p style="color:#555;font-size:.95rem">Tìm trạm sạc, đặt chỗ, quét QR, thanh toán điện tử và theo dõi quá trình sạc mọi lúc mọi nơi.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:10px">🚚</div>
            <h5 style="color:#0d1b2a;font-weight:700">Giải pháp đội xe</h5>
            <p style="color:#555;font-size:.95rem">Tối ưu lịch sạc, theo dõi chi phí điện theo từng xe, báo cáo vận hành cho taxi, giao hàng, logistics.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,.07);height:100%">
            <div style="font-size:2rem;margin-bottom:10px">🤝</div>
            <h5 style="color:#0d1b2a;font-weight:700">Nhượng quyền trạm sạc</h5>
            <p style="color:#555;font-size:.95rem">Chủ địa điểm cung cấp mặt bằng — NLP EV cung cấp thiết bị, công nghệ, vận hành và hỗ trợ kỹ thuật.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section style="padding:60px 0;background:#fff">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h2 style="color:#0d1b2a;font-weight:700;margin-bottom:20px">Chuẩn kết nối OCPP</h2>
          <p style="color:#555;margin-bottom:16px">NLP EV Charging ứng dụng chuẩn mở OCPP (1.6J / 2.0.1) giúp kết nối nhiều loại trụ sạc, không phụ thuộc nhà sản xuất và dễ dàng mở rộng quy mô.</p>
          <ul style="color:#555;padding-left:20px">
            <li>Quản lý tập trung nhiều điểm sạc</li>
            <li>Phân quyền vận hành linh hoạt</li>
            <li>Theo dõi lỗi và xuất báo cáo tự động</li>
            <li>Tích hợp thanh toán số đa nền tảng</li>
          </ul>
        </div>
        <div class="col-md-6 text-center">
          <div style="background:#f0f7ff;border-radius:16px;padding:40px;font-size:3rem">⚡🔌📱🖥️</div>
        </div>
      </div>
    </div>
  </section>

  <section style="padding:60px 0;background:linear-gradient(135deg,#0d1b2a 0%,#1b4f72 100%);color:#fff;text-align:center">
    <div class="container">
      <h2 style="font-weight:700;margin-bottom:12px">Đăng ký nhượng quyền hoặc khảo sát địa điểm</h2>
      <p style="opacity:.9;margin-bottom:28px">Chúng tôi hỗ trợ bạn từ khảo sát, thiết kế hệ thống đến vận hành và mở rộng mạng lưới.</p>
      <a href="/contact" style="background:#2e86c1;color:#fff;padding:14px 36px;border-radius:8px;text-decoration:none;font-weight:600;margin-right:12px">Liên hệ tư vấn</a>
      <a href="/solutions" style="border:2px solid #fff;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600">Xem giải pháp khác</a>
    </div>
  </section>
</div>
HTML;
    }

    private function technologyContent(): string
    {
        return <<<'HTML'
<div class="nlp-page-content">
  <section style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);color:#fff;padding:80px 0 60px">
    <div class="container text-center">
      <span style="background:#e94560;color:#fff;padding:6px 18px;border-radius:20px;font-size:13px;letter-spacing:1px">NLP TECHNOLOGY</span>
      <h1 style="font-size:2.6rem;font-weight:700;margin:20px 0 16px">Nền tảng công nghệ số<br>Kiến tạo giải pháp thông minh cho doanh nghiệp</h1>
      <p style="font-size:1.1rem;opacity:.9;max-width:620px;margin:0 auto 32px">Đồng hành cùng doanh nghiệp trong hành trình chuyển đổi số — từ phần mềm, mobile app đến ERP, CRM, IoT và AI.</p>
      <a href="/contact" style="background:#e94560;color:#fff;padding:14px 36px;border-radius:8px;text-decoration:none;font-weight:600">Tư vấn giải pháp →</a>
    </div>
  </section>

  <section style="padding:60px 0;background:#f5f5f5">
    <div class="container">
      <div class="text-center mb-5"><h2 style="color:#1a1a2e;font-weight:700">10 dịch vụ công nghệ</h2></div>
      <div class="row g-3">
        @foreach([
          ['💻','Phần mềm theo yêu cầu','Xây dựng hệ thống phần mềm được thiết kế riêng theo nhu cầu thực tế của từng doanh nghiệp.'],
          ['🌐','Phát triển Website','Website doanh nghiệp, thương mại điện tử, tích hợp CRM/ERP, quản lý nội dung chuyên nghiệp.'],
          ['📱','Mobile App','Ứng dụng iOS & Android — quản lý khách hàng, bán hàng, IoT, trạm sạc và dịch vụ số.'],
          ['🏭','Hệ thống ERP','Quản trị tập trung: tài chính, nhân sự, kho, mua hàng, bán hàng và báo cáo điều hành.'],
          ['🤝','Hệ thống CRM','Quản lý khách hàng, lịch sử giao dịch, tự động chăm sóc và phân tích hành vi khách hàng.'],
          ['🔗','Internet vạn vật (IoT)','Kết nối thiết bị, giám sát từ xa, điều khiển tự động cho nhà máy, năng lượng và xe điện.'],
          ['🔌','API & Tích hợp','Thiết kế API, đồng bộ dữ liệu và kết nối liền mạch các nền tảng phần mềm khác nhau.'],
          ['☁️','Cloud Platform','Cloud server, database, backup và monitoring — hạ tầng linh hoạt, bảo mật, tiết kiệm chi phí.'],
          ['📦','SaaS','Phần mềm thuê bao hàng tháng — sử dụng ngay, mở rộng dễ dàng, không cần đầu tư hạ tầng.'],
          ['🚀','Chuyển đổi số','Lộ trình số hóa dữ liệu → tự động hóa → ứng dụng AI → doanh nghiệp thông minh.'],
        ] as [$icon, $title, $desc])
        <div class="col-md-6 col-lg-4">
          <div style="background:#fff;border-radius:10px;padding:24px;display:flex;gap:16px;align-items:flex-start;height:100%">
            <div style="font-size:1.8rem;flex-shrink:0">{{$icon}}</div>
            <div><h6 style="color:#1a1a2e;font-weight:700;margin:0 0 6px">{{$title}}</h6><p style="color:#666;font-size:.9rem;margin:0">{{$desc}}</p></div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <section style="padding:60px 0;background:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#1a1a2e;font-weight:700;margin-bottom:40px">Quy trình phát triển giải pháp</h2>
      <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:8px">
        @foreach(['Khảo sát nhu cầu','Phân tích nghiệp vụ','Thiết kế giải pháp','Lập trình phát triển','Kiểm thử','Triển khai','Bảo trì & Nâng cấp'] as $i => $step)
        <div style="display:flex;align-items:center;gap:6px">
          <div style="background:#0f3460;color:#fff;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700">{{$i+1}}</div>
          <span style="color:#1a1a2e;font-size:.9rem;font-weight:600">{{$step}}</span>
          @if($i < 6)<span style="color:#e94560">→</span>@endif
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <section style="padding:60px 0;background:linear-gradient(135deg,#1a1a2e 0%,#0f3460 100%);color:#fff;text-align:center">
    <div class="container">
      <h2 style="font-weight:700;margin-bottom:12px">Bắt đầu chuyển đổi số ngay hôm nay</h2>
      <p style="opacity:.9;margin-bottom:28px">Đội ngũ chuyên gia NLP Technology sẽ tư vấn giải pháp phù hợp với mô hình và ngân sách của bạn.</p>
      <a href="/contact" style="background:#e94560;color:#fff;padding:14px 36px;border-radius:8px;text-decoration:none;font-weight:600;margin-right:12px">Tư vấn miễn phí</a>
      <a href="/solutions" style="border:2px solid #fff;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600">Xem giải pháp</a>
    </div>
  </section>
</div>
HTML;
    }

    private function aiDigitalContent(): string
    {
        return <<<'HTML'
<div class="nlp-page-content">
  <section style="background:linear-gradient(135deg,#0b0c10 0%,#1f2833 50%,#45a29e 100%);color:#fff;padding:80px 0 60px">
    <div class="container text-center">
      <span style="background:#45a29e;color:#fff;padding:6px 18px;border-radius:20px;font-size:13px;letter-spacing:1px">NLP AI & DIGITAL</span>
      <h1 style="font-size:2.6rem;font-weight:700;margin:20px 0 16px">Trí tuệ nhân tạo<br>Động lực chuyển đổi doanh nghiệp kỷ nguyên số</h1>
      <p style="font-size:1.1rem;opacity:.9;max-width:620px;margin:0 auto 32px">Biến dữ liệu thành trí tuệ — biến trí tuệ thành giá trị. Giúp doanh nghiệp vận hành thông minh hơn với AI.</p>
      <a href="/contact" style="background:#45a29e;color:#fff;padding:14px 36px;border-radius:8px;text-decoration:none;font-weight:600">Tư vấn AI cho doanh nghiệp →</a>
    </div>
  </section>

  <section style="padding:60px 0;background:#f9f9f9">
    <div class="container">
      <div class="text-center mb-5"><h2 style="color:#0b0c10;font-weight:700">Các giải pháp AI</h2></div>
      <div class="row g-4">
        <div class="col-md-6">
          <div style="background:#fff;border-radius:12px;padding:30px;box-shadow:0 2px 12px rgba(0,0,0,.07)">
            <h5 style="color:#0b0c10;font-weight:700">🤖 AI Agent – Trợ lý thông minh</h5>
            <p style="color:#555">Xây dựng "nhân sự số" có khả năng hiểu yêu cầu, phân tích dữ liệu, thực hiện nhiệm vụ và học hỏi theo thời gian. Ứng dụng: trợ lý điều hành, bán hàng, hỗ trợ nội bộ.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div style="background:#fff;border-radius:12px;padding:30px;box-shadow:0 2px 12px rgba(0,0,0,.07)">
            <h5 style="color:#0b0c10;font-weight:700">💬 AI Chatbot 24/7</h5>
            <p style="color:#555">Chatbot hiểu ngôn ngữ tự nhiên tiếng Việt, tư vấn sản phẩm, hỗ trợ kỹ thuật và thu thập thông tin khách hàng trên website, app, Facebook/Zalo.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div style="background:#fff;border-radius:12px;padding:30px;box-shadow:0 2px 12px rgba(0,0,0,.07)">
            <h5 style="color:#0b0c10;font-weight:700">📊 Machine Learning & Dự báo</h5>
            <p style="color:#555">Phân tích dữ liệu lịch sử, nhận biết xu hướng và dự đoán: sản lượng điện mặt trời, nhu cầu sạc EV, doanh số bán hàng, hành vi khách hàng.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div style="background:#fff;border-radius:12px;padding:30px;box-shadow:0 2px 12px rgba(0,0,0,.07)">
            <h5 style="color:#0b0c10;font-weight:700">👁️ Computer Vision</h5>
            <p style="color:#555">Nhận diện hình ảnh, phân tích video, phát hiện bất thường. Ứng dụng: kiểm tra chất lượng sản phẩm, giám sát an ninh, nhà máy thông minh.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div style="background:#fff;border-radius:12px;padding:30px;box-shadow:0 2px 12px rgba(0,0,0,.07)">
            <h5 style="color:#0b0c10;font-weight:700">📈 Phân tích dữ liệu thông minh</h5>
            <p style="color:#555">Thu thập, làm sạch, phân tích dữ liệu và trực quan hóa qua dashboard thông minh. Giúp lãnh đạo ra quyết định nhanh và chính xác hơn.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div style="background:#fff;border-radius:12px;padding:30px;box-shadow:0 2px 12px rgba(0,0,0,.07)">
            <h5 style="color:#0b0c10;font-weight:700">⚙️ AI Automation</h5>
            <p style="color:#555">Tự động hóa các công việc lặp lại trong kế toán, nhân sự, bán hàng, CSKH và quản lý vận hành. Giảm sai sót, tăng năng suất.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section style="padding:60px 0;background:linear-gradient(135deg,#0b0c10 0%,#45a29e 100%);color:#fff;text-align:center">
    <div class="container">
      <h2 style="font-weight:700;margin-bottom:12px">"Biến dữ liệu thành trí tuệ – Biến trí tuệ thành giá trị"</h2>
      <p style="opacity:.9;margin-bottom:28px">Hãy để NLP AI & Digital đồng hành cùng doanh nghiệp bạn trong kỷ nguyên trí tuệ nhân tạo.</p>
      <a href="/contact" style="background:#45a29e;color:#fff;padding:14px 36px;border-radius:8px;text-decoration:none;font-weight:600;margin-right:12px">Bắt đầu ngay</a>
      <a href="/ecosystem" style="border:2px solid #fff;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600">Xem hệ sinh thái</a>
    </div>
  </section>
</div>
HTML;
    }

    private function esgCarbonContent(): string
    {
        return <<<'HTML'
<div class="nlp-page-content">
  <section style="background:linear-gradient(135deg,#1b4332 0%,#2d6a4f 50%,#40916c 100%);color:#fff;padding:80px 0 60px">
    <div class="container text-center">
      <span style="background:#74c69d;color:#1b4332;padding:6px 18px;border-radius:20px;font-size:13px;font-weight:700;letter-spacing:1px">NLP ESG & CARBON</span>
      <h1 style="font-size:2.6rem;font-weight:700;margin:20px 0 16px">Kiến tạo giá trị xanh<br>Đồng hành cùng hành trình Net Zero</h1>
      <p style="font-size:1.1rem;opacity:.9;max-width:620px;margin:0 auto 32px">Giải pháp ESG toàn diện: trồng rừng, tín chỉ carbon, nông nghiệp bền vững và lộ trình Net Zero cho doanh nghiệp Việt Nam.</p>
      <a href="/contact" style="background:#74c69d;color:#1b4332;padding:14px 36px;border-radius:8px;text-decoration:none;font-weight:700">Đánh giá ESG miễn phí →</a>
    </div>
  </section>

  <section style="padding:60px 0;background:#f0faf4">
    <div class="container">
      <div class="text-center mb-5"><h2 style="color:#1b4332;font-weight:700">Các lĩnh vực hoạt động</h2></div>
      <div class="row g-4">
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;border-left:4px solid #40916c;height:100%">
            <h5 style="color:#1b4332;font-weight:700">🌳 Phát triển rừng bền vững</h5>
            <p style="color:#555;font-size:.95rem">Trồng rừng mới, phục hồi rừng suy thoái, quản lý rừng sản xuất bền vững và bảo tồn rừng ngập mặn — tạo nguồn tín chỉ carbon chất lượng cao.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;border-left:4px solid #40916c;height:100%">
            <h5 style="color:#1b4332;font-weight:700">🌾 Nông nghiệp bền vững</h5>
            <p style="color:#555;font-size:.95rem">Nông nghiệp công nghệ cao (IoT, AI, tự động hóa), canh tác carbon thấp, tối ưu phân bón và quản lý chất thải nông nghiệp.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;border-left:4px solid #40916c;height:100%">
            <h5 style="color:#1b4332;font-weight:700">💨 Dự án & Tín chỉ carbon</h5>
            <p style="color:#555;font-size:.95rem">Phát triển dự án giảm phát thải, đo lường hấp thụ carbon, quản lý dữ liệu MRV và kết nối thị trường tín chỉ carbon trong nước và quốc tế.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;border-left:4px solid #52b788;height:100%">
            <h5 style="color:#1b4332;font-weight:700">📊 Hệ thống MRV</h5>
            <p style="color:#555;font-size:.95rem">Đo lường (Measurement), Báo cáo (Reporting), Thẩm định (Verification) — đảm bảo tính minh bạch và uy tín của dự án carbon.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;border-left:4px solid #52b788;height:100%">
            <h5 style="color:#1b4332;font-weight:700">🏢 Tư vấn ESG doanh nghiệp</h5>
            <p style="color:#555;font-size:.95rem">Đánh giá hiện trạng E-S-G, xây dựng chính sách bền vững, lập báo cáo theo chuẩn GRI/TCFD và chuẩn bị cho tiêu chuẩn quốc tế.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:12px;padding:28px;border-left:4px solid #52b788;height:100%">
            <h5 style="color:#1b4332;font-weight:700">🎯 Lộ trình Net Zero</h5>
            <p style="color:#555;font-size:.95rem">6 bước: Đánh giá phát thải → Kế hoạch giảm phát thải → Chuyển đổi năng lượng sạch → Tối ưu vận hành → Bù trừ carbon → Đạt Net Zero.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section style="padding:60px 0;background:#fff">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h2 style="color:#1b4332;font-weight:700;margin-bottom:20px">Tại sao ESG quan trọng?</h2>
          <ul style="color:#555;line-height:2">
            <li>✅ Đáp ứng yêu cầu thuế carbon biên giới châu Âu (CBAM)</li>
            <li>✅ Tạo nguồn thu từ tín chỉ carbon quốc tế</li>
            <li>✅ Nâng cao hình ảnh thương hiệu xanh</li>
            <li>✅ Thu hút vốn đầu tư ESG</li>
            <li>✅ Chuẩn bị cho nền kinh tế carbon thấp</li>
            <li>✅ Tuân thủ tiêu chuẩn báo cáo quốc tế</li>
          </ul>
        </div>
        <div class="col-md-6 text-center">
          <div style="background:#f0faf4;border-radius:16px;padding:40px">
            <div style="font-size:2.5rem;margin-bottom:16px">🌳💨🌾📊</div>
            <p style="color:#1b4332;font-weight:700;font-size:1.1rem">"Bảo vệ môi trường hôm nay<br>Kiến tạo giá trị bền vững cho tương lai"</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section style="padding:60px 0;background:linear-gradient(135deg,#1b4332 0%,#40916c 100%);color:#fff;text-align:center">
    <div class="container">
      <h2 style="font-weight:700;margin-bottom:12px">Bắt đầu hành trình Net Zero của bạn</h2>
      <p style="opacity:.9;margin-bottom:28px">Chuyên gia NLP ESG & Carbon sẽ đánh giá hiện trạng và đề xuất lộ trình phù hợp — miễn phí.</p>
      <a href="/contact" style="background:#74c69d;color:#1b4332;padding:14px 36px;border-radius:8px;text-decoration:none;font-weight:700;margin-right:12px">Đánh giá ESG miễn phí</a>
      <a href="/solutions" style="border:2px solid #fff;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600">Xem giải pháp</a>
    </div>
  </section>
</div>
HTML;
    }

    private function ecosystemContent(): string
    {
        return <<<'HTML'
<div class="nlp-page-content">
  <section style="background:linear-gradient(135deg,#0d2137 0%,#1a3c2e 50%,#2d3561 100%);color:#fff;padding:80px 0 60px">
    <div class="container text-center">
      <span style="background:#52b788;color:#fff;padding:6px 18px;border-radius:20px;font-size:13px;letter-spacing:1px">HỆ SINH THÁI NLP GROUP</span>
      <h1 style="font-size:2.6rem;font-weight:700;margin:20px 0 16px">Kiến tạo hệ sinh thái đổi mới bền vững<br>Năng lượng xanh • Xe điện • Công nghệ • AI • ESG</h1>
      <p style="font-size:1.1rem;opacity:.9;max-width:680px;margin:0 auto">NLP Group kết hợp sức mạnh của 5 lĩnh vực chiến lược để tạo ra những giải pháp toàn diện cho doanh nghiệp và cộng đồng.</p>
    </div>
  </section>

  <section style="padding:70px 0;background:#f8f9fa">
    <div class="container">
      <div class="text-center mb-5"><h2 style="color:#0d2137;font-weight:700">5 lĩnh vực chiến lược</h2></div>
      <div class="row g-4 justify-content-center">
        <div class="col-md-4">
          <div style="background:#fff;border-radius:16px;padding:36px 28px;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,.08);height:100%;border-top:4px solid #2d6a4f">
            <div style="font-size:3rem;margin-bottom:16px">☀️</div>
            <h4 style="color:#2d6a4f;font-weight:700">NLP Energy</h4>
            <p style="color:#555;margin-bottom:20px">Điện mặt trời áp mái, BESS lưu trữ điện, EMS quản lý năng lượng và dịch vụ EPC, O&M toàn diện.</p>
            <a href="/nlp-energy" style="background:#2d6a4f;color:#fff;padding:10px 24px;border-radius:6px;text-decoration:none;font-weight:600;font-size:.9rem">Tìm hiểu thêm →</a>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:16px;padding:36px 28px;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,.08);height:100%;border-top:4px solid #1b4f72">
            <div style="font-size:3rem;margin-bottom:16px">🔋</div>
            <h4 style="color:#1b4f72;font-weight:700">NLP EV Charging</h4>
            <p style="color:#555;margin-bottom:20px">Trụ sạc AC/DC, phần mềm CSMS, ứng dụng người dùng, mô hình nhượng quyền trạm sạc thông minh.</p>
            <a href="/nlp-ev-charging" style="background:#1b4f72;color:#fff;padding:10px 24px;border-radius:6px;text-decoration:none;font-weight:600;font-size:.9rem">Tìm hiểu thêm →</a>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:16px;padding:36px 28px;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,.08);height:100%;border-top:4px solid #e94560">
            <div style="font-size:3rem;margin-bottom:16px">💻</div>
            <h4 style="color:#e94560;font-weight:700">NLP Technology</h4>
            <p style="color:#555;margin-bottom:20px">Phần mềm theo yêu cầu, website, mobile app, ERP, CRM, IoT, API, Cloud và chuyển đổi số doanh nghiệp.</p>
            <a href="/nlp-technology" style="background:#e94560;color:#fff;padding:10px 24px;border-radius:6px;text-decoration:none;font-weight:600;font-size:.9rem">Tìm hiểu thêm →</a>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:16px;padding:36px 28px;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,.08);height:100%;border-top:4px solid #45a29e">
            <div style="font-size:3rem;margin-bottom:16px">🤖</div>
            <h4 style="color:#45a29e;font-weight:700">NLP AI & Digital</h4>
            <p style="color:#555;margin-bottom:20px">AI Agent, chatbot, Machine Learning, Computer Vision, phân tích dữ liệu và tự động hóa quy trình doanh nghiệp.</p>
            <a href="/nlp-ai-digital" style="background:#45a29e;color:#fff;padding:10px 24px;border-radius:6px;text-decoration:none;font-weight:600;font-size:.9rem">Tìm hiểu thêm →</a>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#fff;border-radius:16px;padding:36px 28px;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,.08);height:100%;border-top:4px solid #40916c">
            <div style="font-size:3rem;margin-bottom:16px">🌱</div>
            <h4 style="color:#40916c;font-weight:700">NLP ESG & Carbon</h4>
            <p style="color:#555;margin-bottom:20px">Trồng rừng, tín chỉ carbon, nông nghiệp bền vững, tư vấn ESG và lộ trình Net Zero cho doanh nghiệp.</p>
            <a href="/nlp-esg-carbon" style="background:#40916c;color:#fff;padding:10px 24px;border-radius:6px;text-decoration:none;font-weight:600;font-size:.9rem">Tìm hiểu thêm →</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section style="padding:60px 0;background:#fff">
    <div class="container">
      <div class="text-center mb-5"><h2 style="color:#0d2137;font-weight:700">Sức mạnh của hệ sinh thái tích hợp</h2></div>
      <div class="row g-4">
        <div class="col-md-4 text-center">
          <div style="background:#f0faf4;border-radius:12px;padding:32px">
            <div style="font-size:2rem;margin-bottom:12px">🔗</div>
            <h5 style="color:#1a3c2e;font-weight:700">Kết nối toàn diện</h5>
            <p style="color:#555;font-size:.95rem">5 lĩnh vực bổ trợ lẫn nhau tạo giải pháp toàn diện mà không đơn vị nào đơn lẻ có thể cung cấp.</p>
          </div>
        </div>
        <div class="col-md-4 text-center">
          <div style="background:#f0f7ff;border-radius:12px;padding:32px">
            <div style="font-size:2rem;margin-bottom:12px">📊</div>
            <h5 style="color:#0d2137;font-weight:700">Dữ liệu chung</h5>
            <p style="color:#555;font-size:.95rem">Dữ liệu năng lượng, giao thông và môi trường được tích hợp để tạo ra insight và tối ưu hóa vận hành.</p>
          </div>
        </div>
        <div class="col-md-4 text-center">
          <div style="background:#fff8f0;border-radius:12px;padding:32px">
            <div style="font-size:2rem;margin-bottom:12px">💡</div>
            <h5 style="color:#b45309;font-weight:700">Một đối tác</h5>
            <p style="color:#555;font-size:.95rem">Thay vì làm việc với nhiều nhà cung cấp khác nhau, bạn chỉ cần một đối tác — NLP Group.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section style="padding:60px 0;background:linear-gradient(135deg,#0d2137 0%,#1a3c2e 100%);color:#fff;text-align:center">
    <div class="container">
      <h2 style="font-weight:700;margin-bottom:12px">NLP Group – Một đối tác, toàn bộ hệ sinh thái xanh và số</h2>
      <p style="opacity:.9;margin-bottom:28px;font-size:1.05rem">Hãy để chúng tôi đồng hành cùng bạn trong hành trình chuyển đổi xanh và chuyển đổi số.</p>
      <a href="/contact" style="background:#52b788;color:#fff;padding:14px 36px;border-radius:8px;text-decoration:none;font-weight:600;margin-right:12px">Liên hệ tư vấn</a>
      <a href="/solutions" style="border:2px solid #fff;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600">Xem giải pháp</a>
    </div>
  </section>
</div>
HTML;
    }
}
