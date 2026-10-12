<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedNlpBlog extends Command
{
    protected $signature = 'nlp:seed-blog {--force : Xóa và tạo lại toàn bộ dữ liệu}';
    protected $description = 'Seed 6 blog categories và 10 bài viết NLP Group vào database';

    public function handle()
    {
        $force = $this->option('force');
        $langId = 21; // Tiếng Việt

        // ----------------------------------------------------------------
        // 1. BLOG CATEGORIES
        // ----------------------------------------------------------------
        $categories = [
            ['name' => 'Tin NLP Group',  'slug' => 'tin-nlp-group'],
            ['name' => 'Tin Năng Lượng', 'slug' => 'tin-nang-luong'],
            ['name' => 'Tin Xe Điện',    'slug' => 'tin-xe-dien'],
            ['name' => 'Tin Công Nghệ',  'slug' => 'tin-cong-nghe'],
            ['name' => 'Tin AI',         'slug' => 'tin-ai'],
            ['name' => 'Tin ESG',        'slug' => 'tin-esg'],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $existing = DB::table('blog_categories')->where('slug', $cat['slug'])->first();
            if ($existing && !$force) {
                $catIds[$cat['slug']] = $existing->id;
                $this->line("  [skip] Category: {$cat['name']}");
                continue;
            }
            if ($existing && $force) {
                DB::table('blog_categories')->where('slug', $cat['slug'])->update([
                    'name' => $cat['name'],
                    'updated_at' => now(),
                ]);
                $catIds[$cat['slug']] = $existing->id;
                $this->info("  [update] Category: {$cat['name']}");
            } else {
                $id = DB::table('blog_categories')->insertGetId([
                    'name'       => $cat['name'],
                    'slug'       => $cat['slug'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $catIds[$cat['slug']] = $id;
                $this->info("  [create] Category: {$cat['name']} (id={$id})");
            }
        }

        // ----------------------------------------------------------------
        // 2. BLOG POSTS
        // ----------------------------------------------------------------
        $posts = $this->getBlogPosts($catIds);

        foreach ($posts as $post) {
            $existing = DB::table('blog_details')
                ->where('slug', $post['slug'])
                ->where('language_id', $langId)
                ->first();

            if ($existing && !$force) {
                $this->line("  [skip] Blog: {$post['title']}");
                continue;
            }

            // Upsert blogs table
            $blogData = [
                'category_id'           => $post['category_id'],
                'blog_image'            => $post['image'],
                'blog_image_driver'     => 'url',
                'breadcrumb_status'     => '1',
                'breadcrumb_image'      => $post['image'],
                'breadcrumb_image_driver' => 'url',
                'status'                => 1,
                'page_title'            => $post['title'],
                'meta_title'            => $post['meta_title'],
                'meta_keywords'         => $post['meta_keywords'],
                'meta_description'      => $post['meta_description'],
                'meta_image'            => $post['image'],
                'meta_image_driver'     => 'url',
                'updated_at'            => now(),
            ];

            if ($existing && $force) {
                $blogId = DB::table('blog_details')
                    ->where('slug', $post['slug'])
                    ->where('language_id', $langId)
                    ->value('blog_id');

                DB::table('blogs')->where('id', $blogId)->update($blogData);
                DB::table('blog_details')->where('blog_id', $blogId)->where('language_id', $langId)->update([
                    'title'       => $post['title'],
                    'slug'        => $post['slug'],
                    'description' => $post['content'],
                    'updated_at'  => now(),
                ]);
                $this->info("  [update] Blog: {$post['title']}");
            } else {
                $blogData['created_at'] = now();
                $blogId = DB::table('blogs')->insertGetId($blogData);
                DB::table('blog_details')->insert([
                    'blog_id'     => $blogId,
                    'language_id' => $langId,
                    'title'       => $post['title'],
                    'slug'        => $post['slug'],
                    'description' => $post['content'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $this->info("  [create] Blog: {$post['title']} (id={$blogId})");
            }
        }

        $this->newLine();
        $this->info('✅ NLP Blog seed hoàn tất!');
        $this->info('   Categories: ' . count($categories));
        $this->info('   Blog posts: ' . count($posts));
    }

    // ====================================================================
    // DATA: 10 bài viết NLP Group
    // ====================================================================
    private function getBlogPosts(array $catIds): array
    {
        return [

            // -----------------------------------------------------------
            // BÀI 1: Điện mặt trời áp mái nhà xưởng
            // -----------------------------------------------------------
            [
                'category_id'     => $catIds['tin-nang-luong'],
                'title'           => 'Điện mặt trời áp mái nhà xưởng — Xu hướng tất yếu cho doanh nghiệp sản xuất 2024–2025',
                'slug'            => 'dien-mat-troi-ap-mai-nha-xuong-xu-huong-2024-2025',
                'meta_title'      => 'Điện mặt trời áp mái nhà xưởng 2024–2025 | NLP Energy',
                'meta_keywords'   => 'điện mặt trời áp mái nhà xưởng, điện mặt trời công nghiệp, tiết kiệm chi phí điện nhà máy',
                'meta_description'=> 'Điện mặt trời áp mái nhà xưởng — giải pháp chiến lược giúp doanh nghiệp chủ động năng lượng, tiết kiệm 700 triệu đến 1 tỷ đồng chi phí điện hàng năm.',
                'image'           => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?w=1200&q=80',
                'content'         => $this->post1(),
            ],

            // -----------------------------------------------------------
            // BÀI 2: Pin lưu trữ hộ gia đình
            // -----------------------------------------------------------
            [
                'category_id'     => $catIds['tin-nang-luong'],
                'title'           => 'Hộ gia đình nào nên lắp pin lưu trữ năng lượng (BESS)?',
                'slug'            => 'ho-gia-dinh-nen-lap-pin-luu-tru-nang-luong-bess',
                'meta_title'      => 'Có nên lắp pin lưu trữ năng lượng BESS cho nhà ở? | NLP Energy',
                'meta_keywords'   => 'pin lưu trữ năng lượng, BESS hộ gia đình, điện mặt trời pin lưu trữ',
                'meta_description'=> 'Pin lưu trữ năng lượng BESS đang trở thành lựa chọn hấp dẫn cho hộ gia đình có điện mặt trời. Phân tích chi phí và hiệu quả từ NLP Energy.',
                'image'           => 'https://images.unsplash.com/photo-1545208773-56d9e461b0d9?w=1200&q=80',
                'content'         => $this->post2(),
            ],

            // -----------------------------------------------------------
            // BÀI 3: Thị trường xe điện VN 2024–2025
            // -----------------------------------------------------------
            [
                'category_id'     => $catIds['tin-xe-dien'],
                'title'           => 'Thị trường xe điện Việt Nam 2024–2025: Tăng trưởng bứt phá và cơ hội hạ tầng sạc',
                'slug'            => 'thi-truong-xe-dien-viet-nam-2024-2025-tang-truong-co-hoi',
                'meta_title'      => 'Thị trường xe điện Việt Nam 2024–2025 | NLP EV Charging',
                'meta_keywords'   => 'xe điện Việt Nam 2024, thị trường xe điện, VinFast xe điện, trạm sạc xe điện',
                'meta_description'=> 'VinFast bán hơn 23.600 ôtô điện tháng 9 — tăng trưởng bứt phá tạo cơ hội vàng cho nhà đầu tư hạ tầng sạc xe điện tại Việt Nam.',
                'image'           => 'https://images.unsplash.com/photo-1620219365994-7cb0db80c6f8?w=1200&q=80',
                'content'         => $this->post3(),
            ],

            // -----------------------------------------------------------
            // BÀI 4: Trụ sạc AC vs DC
            // -----------------------------------------------------------
            [
                'category_id'     => $catIds['tin-xe-dien'],
                'title'           => 'Trụ sạc AC hay DC — Lựa chọn nào phù hợp cho dự án trạm sạc của bạn?',
                'slug'            => 'tru-sac-ac-hay-dc-lua-chon-phu-hop-cho-du-an-tram-sac',
                'meta_title'      => 'So sánh trụ sạc AC và DC xe điện | NLP EV Charging',
                'meta_keywords'   => 'trụ sạc AC DC xe điện, so sánh trụ sạc, lắp đặt trạm sạc',
                'meta_description'=> 'AC hay DC? Phân tích chi tiết ưu nhược điểm, chi phí đầu tư và gợi ý lựa chọn phù hợp với từng mô hình kinh doanh trạm sạc xe điện.',
                'image'           => 'https://images.unsplash.com/photo-1647427060118-4af2dab06b08?w=1200&q=80',
                'content'         => $this->post4(),
            ],

            // -----------------------------------------------------------
            // BÀI 5: Chuyển đổi số 4 giai đoạn
            // -----------------------------------------------------------
            [
                'category_id'     => $catIds['tin-cong-nghe'],
                'title'           => 'Chuyển đổi số doanh nghiệp — 4 giai đoạn cần đi đúng thứ tự',
                'slug'            => 'chuyen-doi-so-doanh-nghiep-4-giai-doan-dung-thu-tu',
                'meta_title'      => 'Chuyển đổi số doanh nghiệp 4 giai đoạn | NLP Technology',
                'meta_keywords'   => 'chuyển đổi số doanh nghiệp, digital transformation, ERP CRM doanh nghiệp vừa nhỏ',
                'meta_description'=> 'Nhiều doanh nghiệp Việt thất bại khi chuyển đổi số vì không đi đúng 4 giai đoạn căn bản. NLP Technology phân tích lộ trình tối ưu.',
                'image'           => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&q=80',
                'content'         => $this->post5(),
            ],

            // -----------------------------------------------------------
            // BÀI 6: AI Agent
            // -----------------------------------------------------------
            [
                'category_id'     => $catIds['tin-ai'],
                'title'           => 'AI Agent — "Nhân sự số" sắp thay đổi cách doanh nghiệp vận hành',
                'slug'            => 'ai-agent-nhan-su-so-thay-doi-doanh-nghiep-van-hanh',
                'meta_title'      => 'AI Agent và tự động hóa doanh nghiệp | NLP AI & Digital',
                'meta_keywords'   => 'AI agent doanh nghiệp, trợ lý AI, tự động hóa AI, AI doanh nghiệp Việt Nam',
                'meta_description'=> 'AI Agent không chỉ là chatbot — đây là hệ thống có thể suy nghĩ và thực hiện chuỗi công việc phức tạp tự động. NLP AI giải thích ứng dụng thực tế.',
                'image'           => 'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=1200&q=80',
                'content'         => $this->post6(),
            ],

            // -----------------------------------------------------------
            // BÀI 7: CBAM thuế carbon EU
            // -----------------------------------------------------------
            [
                'category_id'     => $catIds['tin-esg'],
                'title'           => 'CBAM — Thuế carbon biên giới châu Âu và điều doanh nghiệp Việt cần biết ngay',
                'slug'            => 'cbam-thue-carbon-bien-gioi-chau-au-doanh-nghiep-viet-can-biet',
                'meta_title'      => 'CBAM thuế carbon EU và tác động doanh nghiệp Việt Nam | NLP ESG',
                'meta_keywords'   => 'CBAM thuế carbon châu Âu, ESG doanh nghiệp xuất khẩu, carbon border adjustment',
                'meta_description'=> 'Từ 2026, doanh nghiệp xuất khẩu sang EU phải báo cáo carbon trong sản phẩm. Đến 2034 phải trả tiền cho mỗi tấn CO₂ — chuẩn bị từ hôm nay.',
                'image'           => 'https://images.unsplash.com/photo-1497435334941-8c899a9f42d7?w=1200&q=80',
                'content'         => $this->post7(),
            ],

            // -----------------------------------------------------------
            // BÀI 8: Tín chỉ carbon
            // -----------------------------------------------------------
            [
                'category_id'     => $catIds['tin-esg'],
                'title'           => 'Tín chỉ carbon là gì? Cơ hội nào cho Việt Nam trên thị trường quốc tế?',
                'slug'            => 'tin-chi-carbon-la-gi-co-hoi-cho-viet-nam',
                'meta_title'      => 'Tín chỉ carbon là gì? Cơ hội Việt Nam | NLP ESG Carbon',
                'meta_keywords'   => 'tín chỉ carbon Việt Nam, carbon credit, thị trường carbon, Net Zero',
                'meta_description'=> '1 hecta rừng hấp thụ 5–10 tấn CO₂/năm, mỗi tấn bán được 5–20 USD. Việt Nam đã thu 51 triệu USD từ tín chỉ carbon — cơ hội nào cho bạn?',
                'image'           => 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=1200&q=80',
                'content'         => $this->post8(),
            ],

            // -----------------------------------------------------------
            // BÀI 9: NLP Group CSMS AI
            // -----------------------------------------------------------
            [
                'category_id'     => $catIds['tin-nlp-group'],
                'title'           => 'NLP Group ra mắt nền tảng quản lý trạm sạc tích hợp AI (CSMS)',
                'slug'            => 'nlp-group-ra-mat-nen-tang-quan-ly-tram-sac-ai-csms',
                'meta_title'      => 'NLP Group ra mắt CSMS AI — quản lý trạm sạc thông minh | nlpgroup.vn',
                'meta_keywords'   => 'NLP Group trạm sạc AI, CSMS thông minh, quản lý trạm sạc xe điện',
                'meta_description'=> 'NLP Group ra mắt Charging Station Management System tích hợp AI — quản lý hàng nghìn trụ sạc từ một giao diện, chuẩn OCPP 2.0.1.',
                'image'           => 'https://images.unsplash.com/photo-1601972599720-36938d4ecd31?w=1200&q=80',
                'content'         => $this->post9(),
            ],

            // -----------------------------------------------------------
            // BÀI 10: NLP Group tín chỉ carbon Tây Nguyên
            // -----------------------------------------------------------
            [
                'category_id'     => $catIds['tin-nlp-group'],
                'title'           => 'NLP Group ký kết hợp tác phát triển dự án tín chỉ carbon tại Tây Nguyên',
                'slug'            => 'nlp-group-ky-ket-hop-tac-du-an-tin-chi-carbon-tay-nguyen',
                'meta_title'      => 'NLP Group phát triển tín chỉ carbon tại Tây Nguyên | nlpgroup.vn',
                'meta_keywords'   => 'NLP Group tín chỉ carbon, dự án rừng Tây Nguyên, carbon credit Việt Nam',
                'meta_description'=> 'NLP ESG & Carbon ký kết hợp tác phát triển dự án trồng rừng và tín chỉ carbon tại Tây Nguyên theo chuẩn VCS quốc tế.',
                'image'           => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=1200&q=80',
                'content'         => $this->post10(),
            ],
        ];
    }

    // ====================================================================
    // HTML CONTENT CHO TỪNG BÀI VIẾT
    // ====================================================================

    private function post1(): string
    {
        return '<p>Theo số liệu từ Tập đoàn Điện lực Việt Nam (EVN), nhu cầu điện trong sản xuất công nghiệp chiếm hơn 50% tổng tiêu thụ điện quốc gia. Với xu hướng giá điện ngày càng tăng và yêu cầu sản xuất xanh từ các đối tác quốc tế, nhiều nhà máy, khu công nghiệp tại Việt Nam đã và đang đẩy mạnh đầu tư vào hệ thống điện mặt trời áp mái.</p>

<h2>Lợi ích kinh tế rõ ràng</h2>
<p>Một hệ thống điện mặt trời áp mái công suất 500 kWp lắp đặt cho nhà xưởng có thể:</p>
<ul>
<li>Cung cấp 550.000–650.000 kWh điện mỗi năm</li>
<li>Tiết kiệm từ 700 triệu đến 1 tỷ đồng chi phí điện hàng năm</li>
<li>Hoàn vốn trong 5–7 năm, vận hành hiệu quả trên 25 năm</li>
<li>Giảm phát thải khoảng 400–500 tấn CO₂ mỗi năm</li>
</ul>

<h2>Kết hợp với BESS và EMS</h2>
<p>Xu hướng mới nhất là tích hợp hệ thống lưu trữ năng lượng (BESS) và phần mềm quản lý năng lượng (EMS) để:</p>
<ul>
<li>Lưu trữ điện dư vào ban ngày, dùng vào giờ cao điểm ban đêm</li>
<li>Tránh phụ thuộc hoàn toàn vào lưới điện quốc gia</li>
<li>Tối ưu chi phí điện theo thời gian biểu giá</li>
<li>Giám sát hiệu suất theo thời gian thực qua nền tảng IoT</li>
</ul>

<h2>Tiêu chuẩn và chính sách</h2>
<p>Theo Nghị định 135/2024/NĐ-CP, các dự án điện mặt trời mái nhà tự sản, tự tiêu được khuyến khích phát triển với thủ tục đơn giản hóa. Doanh nghiệp có thể đăng ký lắp đặt mà không cần phức tạp về giấy phép nếu hệ thống phục vụ nhu cầu nội bộ.</p>

<h2>NLP Energy đồng hành</h2>
<p>NLP Energy cung cấp giải pháp điện mặt trời trọn gói cho nhà xưởng bao gồm khảo sát, tư vấn, thiết kế, thi công, lắp đặt và bảo trì dài hạn — giúp doanh nghiệp tối ưu đầu tư từ ngày đầu tiên.</p>';
    }

    private function post2(): string
    {
        return '<p>Pin lưu trữ năng lượng (BESS) đang trở thành lựa chọn hấp dẫn cho hộ gia đình có điện mặt trời, giúp chủ động nguồn điện 24/7. Nhưng không phải gia đình nào cũng cần đầu tư ngay.</p>

<h2>Nên lắp pin lưu trữ nếu:</h2>
<ul>
<li>Đã có hệ thống điện mặt trời áp mái</li>
<li>Khu vực thường xuyên mất điện hoặc điện không ổn định</li>
<li>Sử dụng điện nhiều vào ban đêm (18:00–22:00)</li>
<li>Muốn độc lập hoàn toàn khỏi lưới điện quốc gia</li>
<li>Có xe điện và muốn sạc từ điện mặt trời nhà mình</li>
</ul>

<h2>Chưa cần lắp ngay nếu:</h2>
<ul>
<li>Mới lắp điện mặt trời và chưa theo dõi đủ dữ liệu tiêu thụ</li>
<li>Chi phí đầu tư pin chưa phù hợp với khả năng tài chính</li>
<li>Khu vực điện lưới ổn định, ít mất điện</li>
</ul>

<h2>Chi phí và hiệu quả</h2>
<p>Một bộ pin lưu trữ lithium dung lượng 5–10 kWh cho hộ gia đình hiện có giá từ 30–80 triệu đồng, thời gian hoàn vốn 7–10 năm và tuổi thọ pin từ 10–15 năm tùy chất lượng.</p>

<h2>Lời khuyên từ NLP Energy</h2>
<p>Trước khi quyết định đầu tư pin lưu trữ, hãy để NLP Energy phân tích dữ liệu tiêu thụ điện thực tế của gia đình bạn và đề xuất giải pháp tối ưu nhất về chi phí–lợi ích. Liên hệ tư vấn miễn phí ngay hôm nay.</p>';
    }

    private function post3(): string
    {
        return '<p>Thị trường xe điện Việt Nam đang bước vào giai đoạn tăng trưởng bứt phá. Theo VnExpress, VinFast đã bán hơn 23.600 ôtô điện trong tháng 9 — đây là con số kỷ lục trong lịch sử thương hiệu xe điện Việt Nam.</p>

<h2>Xu hướng rõ ràng</h2>
<ul>
<li>Xe điện VinFast VF 3 (từ 276 triệu đồng) đang thu hút lượng lớn người dùng chuyển từ xe máy lên ôtô</li>
<li>Ưu đãi thuế nhập khẩu linh kiện xe điện đang giúp giảm giá thành đáng kể</li>
<li>Nhiều thương hiệu quốc tế (BYD, Hyundai, KIA) tăng cường đưa xe điện vào Việt Nam</li>
<li>Chính phủ cam kết đến 2030 có 30% xe buýt điện tại các thành phố lớn</li>
</ul>

<h2>Bài toán hạ tầng sạc</h2>
<p>Sự tăng trưởng nhanh của xe điện đặt ra yêu cầu cấp bách về hạ tầng sạc:</p>
<ul>
<li>Hiện tại Việt Nam có khoảng 150.000+ điểm sạc xe máy điện và 3.000+ trụ sạc ôtô điện</li>
<li>Nhu cầu dự kiến tăng gấp 5–10 lần đến năm 2030</li>
<li>Khoảng cách giữa cung và cầu tạo ra cơ hội đầu tư rất lớn</li>
</ul>

<h2>Cơ hội cho chủ đầu tư</h2>
<p>Với mỗi 10 trụ sạc AC 7kW, doanh thu từ phí dịch vụ có thể đạt 15–30 triệu đồng/tháng. Mô hình nhượng quyền trạm sạc đang được nhiều nhà đầu tư quan tâm như một kênh tạo thu nhập thụ động dài hạn.</p>

<h2>NLP EV Charging đồng hành</h2>
<p>NLP EV Charging cung cấp giải pháp hạ tầng sạc toàn diện — từ thiết bị, phần mềm quản lý, đến hỗ trợ vận hành và nhượng quyền — giúp nhà đầu tư khởi động nhanh và vận hành hiệu quả.</p>';
    }

    private function post4(): string
    {
        return '<p>Khi đầu tư trạm sạc xe điện, câu hỏi đầu tiên luôn là: AC hay DC? Sự lựa chọn này ảnh hưởng trực tiếp đến chi phí đầu tư, đối tượng khách hàng và mô hình kinh doanh của bạn.</p>

<h2>Trụ sạc AC (Alternating Current)</h2>
<p>Trụ sạc AC sử dụng dòng điện xoay chiều từ lưới điện, được chuyển đổi thành điện một chiều ngay trong xe.</p>
<p><strong>Ưu điểm:</strong></p>
<ul>
<li>Chi phí đầu tư thấp: 8–30 triệu đồng/trụ (7–22 kW)</li>
<li>Lắp đặt đơn giản, không cần hạ tầng điện đặc biệt</li>
<li>Phù hợp với bãi đỗ xe, nhà riêng, chung cư, văn phòng</li>
<li>Tiêu thụ điện vào giờ thấp điểm — chi phí điện thấp hơn</li>
</ul>
<p><strong>Nhược điểm:</strong></p>
<ul>
<li>Thời gian sạc dài: 4–8 giờ cho một lần sạc đầy</li>
<li>Không phù hợp cho người cần sạc nhanh, di chuyển liên tục</li>
</ul>

<h2>Trụ sạc DC (Direct Current)</h2>
<p>Trụ sạc DC chuyển đổi điện ngay tại trạm, đưa trực tiếp điện một chiều vào pin xe.</p>
<p><strong>Ưu điểm:</strong></p>
<ul>
<li>Sạc cực nhanh: 20–80 phút cho 80% dung lượng (50–300 kW)</li>
<li>Phù hợp cho trạm dừng nghỉ, cao tốc, trung tâm thương mại</li>
<li>Tạo ra trải nghiệm khách hàng tốt hơn</li>
</ul>
<p><strong>Nhược điểm:</strong></p>
<ul>
<li>Chi phí đầu tư cao hơn: 100–500 triệu đồng/trụ tùy công suất</li>
<li>Yêu cầu hạ tầng điện mạnh, đôi khi cần nâng cấp trạm biến áp</li>
</ul>

<h2>Gợi ý lựa chọn theo địa điểm</h2>
<table>
<thead><tr><th>Địa điểm</th><th>Khuyến nghị</th><th>Chi phí/trụ</th></tr></thead>
<tbody>
<tr><td>Nhà riêng, chung cư</td><td>AC 7 kW</td><td>8–12 triệu</td></tr>
<tr><td>Văn phòng, khách sạn</td><td>AC 11–22 kW</td><td>15–30 triệu</td></tr>
<tr><td>Trung tâm thương mại</td><td>AC 22 kW + DC 50 kW</td><td>30–150 triệu</td></tr>
<tr><td>Trạm dừng nghỉ cao tốc</td><td>DC 120–180 kW</td><td>300–500 triệu</td></tr>
<tr><td>Đội xe taxi/logistics</td><td>DC 60–120 kW</td><td>150–350 triệu</td></tr>
</tbody>
</table>';
    }

    private function post5(): string
    {
        return '<p>Nhiều doanh nghiệp Việt đang đầu tư vào công nghệ nhưng không đạt được kỳ vọng. Lý do thường đến từ việc không đi đúng thứ tự 4 giai đoạn chuyển đổi số căn bản.</p>

<h2>Giai đoạn 1: Số hóa dữ liệu (Digitization)</h2>
<p>Đây là bước đầu tiên và không thể bỏ qua. Chuyển toàn bộ thông tin từ giấy tờ, bảng tính Excel sang hệ thống số tập trung:</p>
<ul>
<li>Số hóa hồ sơ khách hàng</li>
<li>Số hóa kho hàng, tồn kho</li>
<li>Số hóa quy trình tuyển dụng, nhân sự</li>
<li>Số hóa báo cáo tài chính</li>
</ul>

<h2>Giai đoạn 2: Tự động hóa quy trình (Process Automation)</h2>
<p>Sau khi có dữ liệu số, tự động hóa các công việc lặp lại:</p>
<ul>
<li>Tự động gửi email chào hàng, báo giá</li>
<li>Tự động tạo hóa đơn và nhắc nhở công nợ</li>
<li>Tự động phân phối lead cho sales team</li>
<li>Workflow phê duyệt nội bộ không cần giấy tờ</li>
</ul>

<h2>Giai đoạn 3: Kết nối dữ liệu (Data Integration)</h2>
<p>Các hệ thống riêng lẻ (CRM, ERP, kế toán, kho) cần được kết nối qua API:</p>
<ul>
<li>Đơn hàng CRM → tự động cập nhật kho ERP</li>
<li>Thanh toán kế toán → cập nhật trạng thái đơn hàng</li>
<li>Dữ liệu bán hàng → báo cáo điều hành tự động</li>
</ul>

<h2>Giai đoạn 4: Ứng dụng AI và Phân tích dữ liệu</h2>
<p>Khi đã có đủ dữ liệu sạch và quy trình số, đây là lúc AI tạo ra giá trị thực sự:</p>
<ul>
<li>AI dự báo doanh số, tồn kho</li>
<li>AI phân tích hành vi khách hàng</li>
<li>Chatbot AI hỗ trợ bán hàng và CSKH</li>
<li>Dashboard thông minh hỗ trợ ra quyết định</li>
</ul>

<h2>Sai lầm phổ biến</h2>
<p>Nhiều doanh nghiệp muốn "nhảy cóc" thẳng sang Giai đoạn 4 mà bỏ qua Giai đoạn 1 và 2. Kết quả là AI không có đủ dữ liệu để học và dự báo chính xác. NLP Technology đồng hành cùng doanh nghiệp từ bước đầu tiên đến hệ sinh thái số hoàn chỉnh.</p>';
    }

    private function post6(): string
    {
        return '<p>AI Agent không chỉ là chatbot trả lời câu hỏi — đây là hệ thống có thể suy nghĩ, lên kế hoạch và thực hiện chuỗi công việc phức tạp tự động. Năm 2025–2026 được dự báo là thời điểm AI Agent bùng nổ trong doanh nghiệp.</p>

<h2>AI Agent là gì?</h2>
<p>Khác với chatbot truyền thống chỉ trả lời câu hỏi đơn lẻ, AI Agent có khả năng:</p>
<ul>
<li>Hiểu mục tiêu phức tạp như "Tìm 10 khách hàng tiềm năng trong tuần này"</li>
<li>Chia nhỏ mục tiêu thành các bước thực hiện cụ thể</li>
<li>Tự động thực hiện từng bước, kết nối với các công cụ khác nhau</li>
<li>Học hỏi và cải thiện theo thời gian từ phản hồi thực tế</li>
</ul>

<h2>Ứng dụng thực tế trong doanh nghiệp</h2>
<h3>Trợ lý bán hàng AI</h3>
<ul>
<li>Tự động tìm kiếm và phân loại lead từ nhiều nguồn</li>
<li>Gửi email chào hàng cá nhân hóa theo từng khách</li>
<li>Nhắc nhở follow-up tự động theo pipeline</li>
<li>Tổng hợp báo cáo bán hàng hàng tuần</li>
</ul>

<h3>Trợ lý CSKH AI</h3>
<ul>
<li>Giải quyết 70–80% câu hỏi thường gặp tự động</li>
<li>Phân loại và ưu tiên ticket hỗ trợ theo mức độ</li>
<li>Escalate tự động sang nhân viên khi cần thiết</li>
<li>Phân tích sentiment và cảm xúc khách hàng</li>
</ul>

<h2>AI trong hệ sinh thái năng lượng NLP</h2>
<p>Tại NLP Group, AI được ứng dụng để:</p>
<ul>
<li>Dự báo sản lượng điện mặt trời theo thời tiết và mùa vụ</li>
<li>Tối ưu lịch sạc xe điện theo giờ điện thấp điểm</li>
<li>Phân tích dữ liệu carbon và đề xuất giải pháp giảm phát thải</li>
<li>Giám sát thiết bị năng lượng từ xa bằng cảm biến IoT</li>
</ul>

<h2>Bắt đầu từ đâu?</h2>
<p>Doanh nghiệp nên bắt đầu với một use case cụ thể, đo lường kết quả, sau đó mở rộng dần — thay vì triển khai AI rộng rãi ngay lập tức. NLP AI & Digital sẵn sàng tư vấn lộ trình AI phù hợp với đặc thù ngành và quy mô doanh nghiệp của bạn.</p>';
    }

    private function post7(): string
    {
        return '<p>Từ năm 2026, doanh nghiệp xuất khẩu sang EU sẽ phải báo cáo lượng carbon trong sản phẩm. Đến 2034, họ sẽ phải trả tiền cho mỗi tấn CO₂ phát thải — đây là cơ chế CBAM mà Việt Nam cần chuẩn bị ngay.</p>

<h2>CBAM là gì?</h2>
<p>Carbon Border Adjustment Mechanism (CBAM) là cơ chế điều chỉnh carbon biên giới của EU, áp dụng cho hàng nhập khẩu vào EU từ các lĩnh vực:</p>
<ul>
<li>Thép và nhôm</li>
<li>Xi măng</li>
<li>Phân bón</li>
<li>Điện và hydro</li>
</ul>

<h2>Lộ trình áp dụng</h2>
<ul>
<li><strong>2023–2025:</strong> Giai đoạn chuyển tiếp — doanh nghiệp bắt buộc báo cáo phát thải nhưng chưa phải nộp tiền</li>
<li><strong>2026–2034:</strong> Phí CBAM tăng dần theo tỷ lệ ETS (hệ thống giao dịch phát thải EU)</li>
<li><strong>2034:</strong> CBAM áp dụng đầy đủ 100%</li>
</ul>

<h2>Tác động đến Việt Nam</h2>
<p>Việt Nam là một trong những nước xuất khẩu lớn sang EU, đặc biệt ngành thép, nhôm và điện tử. Doanh nghiệp không chuẩn bị trước có nguy cơ:</p>
<ul>
<li>Mất lợi thế cạnh tranh về giá với đối thủ đã có chứng chỉ xanh</li>
<li>Bị từ chối nhập hàng do thiếu hồ sơ carbon đầy đủ</li>
<li>Phải trả phí carbon bổ sung, tăng chi phí xuất khẩu 10–30%</li>
</ul>

<h2>Giải pháp chuẩn bị</h2>
<ol>
<li><strong>Đo lường phát thải:</strong> Kiểm kê toàn bộ khí nhà kính theo chuẩn ISO 14064 hoặc GHG Protocol</li>
<li><strong>Giảm phát thải tại nguồn:</strong> Lắp điện mặt trời, tối ưu năng lượng sản xuất</li>
<li><strong>Báo cáo ESG chuẩn:</strong> Lập báo cáo theo GRI, TCFD để minh bạch hóa</li>
<li><strong>Mua tín chỉ carbon:</strong> Bù đắp lượng phát thải còn lại qua thị trường carbon</li>
</ol>
<p>NLP ESG & Carbon đồng hành cùng doanh nghiệp trong toàn bộ hành trình ESG — từ đo lường đến báo cáo và tín chỉ carbon.</p>';
    }

    private function post8(): string
    {
        return '<p>Một hecta rừng hấp thụ khoảng 5–10 tấn CO₂ mỗi năm — và mỗi tấn CO₂ đó có thể bán được 5–20 USD trên thị trường carbon quốc tế. Đây là nguồn thu mới mà Việt Nam đang dần khai thác.</p>

<h2>Tín chỉ carbon là gì?</h2>
<p>Một tín chỉ carbon (carbon credit) đại diện cho quyền phát thải 1 tấn CO₂ tương đương. Nó được tạo ra từ các hoạt động:</p>
<ul>
<li>Trồng rừng và bảo vệ rừng tự nhiên</li>
<li>Phát triển năng lượng tái tạo (điện gió, điện mặt trời)</li>
<li>Giảm phát thải trong sản xuất nông nghiệp</li>
<li>Phục hồi hệ sinh thái ngập mặn, rừng ngập mặn</li>
</ul>

<h2>Hai loại thị trường carbon</h2>
<p><strong>Thị trường bắt buộc:</strong> Được quy định bởi chính phủ — doanh nghiệp buộc phải mua tín chỉ nếu phát thải vượt hạn ngạch (EU ETS, California Cap-and-Trade).</p>
<p><strong>Thị trường tự nguyện (VCM):</strong> Doanh nghiệp tự nguyện mua tín chỉ để đạt mục tiêu Net Zero hoặc nâng cao ESG. Đây là thị trường đang tăng trưởng rất nhanh trên toàn cầu.</p>

<h2>Tiềm năng của Việt Nam</h2>
<p>Với hơn 14,7 triệu hecta rừng (tỷ lệ che phủ 42%), Việt Nam có tiềm năng tạo ra hàng chục triệu tín chỉ carbon mỗi năm. Năm 2023, Việt Nam đã bán thí điểm 10,3 triệu tín chỉ carbon rừng cho Ngân hàng Thế giới, thu về hơn 51 triệu USD.</p>

<h2>Quy trình tham gia thị trường carbon</h2>
<ol>
<li>Xác định dự án tiềm năng (rừng, nông nghiệp, năng lượng tái tạo)</li>
<li>Đo lường lượng carbon giảm/hấp thụ theo chuẩn quốc tế (VCS, Gold Standard)</li>
<li>Đăng ký xác minh độc lập (MRV — Measurement, Reporting, Verification)</li>
<li>Phát hành và đăng ký tín chỉ trên sàn quốc tế</li>
<li>Giao dịch qua broker hoặc trực tiếp với người mua</li>
</ol>
<p>NLP ESG & Carbon đồng hành cùng doanh nghiệp và chủ rừng trong toàn bộ quy trình này — từ đánh giá tiềm năng đến giao dịch tín chỉ carbon đầu tiên.</p>';
    }

    private function post9(): string
    {
        return '<p>NLP Group vừa ra mắt nền tảng Charging Station Management System (CSMS) tích hợp AI, đánh dấu bước tiến quan trọng trong chiến lược phát triển hệ sinh thái xe điện thông minh tại Việt Nam.</p>

<h2>Tính năng nổi bật của CSMS NLP</h2>
<ul>
<li>Quản lý hàng nghìn trụ sạc từ một giao diện duy nhất</li>
<li>AI dự báo nhu cầu sạc và tối ưu phân phối điện theo thời gian thực</li>
<li>Tích hợp thanh toán đa nền tảng: ví điện tử, ngân hàng, QR code</li>
<li>Dashboard thời gian thực về doanh thu, uptime và lỗi thiết bị</li>
<li>Chuẩn OCPP 2.0.1 — kết nối với thiết bị của bất kỳ nhà sản xuất nào</li>
<li>Cảnh báo sự cố tự động qua SMS, email và app</li>
</ul>

<h2>Kiến trúc hệ sinh thái tích hợp</h2>
<p>Đây là một phần trong chiến lược tích hợp dài hạn của NLP Group, kết nối:</p>
<ul>
<li><strong>NLP Energy</strong> — điện mặt trời cung cấp năng lượng xanh cho trạm sạc</li>
<li><strong>NLP EV Charging</strong> — hạ tầng trụ sạc và vận hành</li>
<li><strong>NLP AI & Digital</strong> — nền tảng CSMS thông minh</li>
</ul>
<p>Ba mảng kết nối thành một hệ sinh thái xanh khép kín, tối ưu chi phí vận hành và mang lại trải nghiệm người dùng vượt trội.</p>

<h2>Lợi ích cho chủ đầu tư trạm sạc</h2>
<ul>
<li>Giảm chi phí vận hành lên đến 30% nhờ tự động hóa và AI</li>
<li>Tăng uptime trạm sạc từ 85% lên 95%+ nhờ phát hiện lỗi sớm</li>
<li>Doanh thu minh bạch, báo cáo tự động hàng ngày/tuần/tháng</li>
<li>Mở rộng quy mô dễ dàng — thêm trụ mới chỉ mất vài phút cấu hình</li>
</ul>';
    }

    private function post10(): string
    {
        return '<p>NLP ESG & Carbon vừa ký kết thỏa thuận hợp tác với các đối tác địa phương để phát triển dự án trồng rừng và tín chỉ carbon tại khu vực Tây Nguyên — một trong những vùng có tiềm năng carbon lớn nhất cả nước.</p>

<h2>Nội dung dự án</h2>
<ul>
<li>Trồng mới và phục hồi hàng nghìn hecta rừng tại Tây Nguyên</li>
<li>Triển khai hệ thống MRV (đo lường – báo cáo – thẩm định) theo chuẩn VCS</li>
<li>Tạo sinh kế bền vững cho cộng đồng địa phương và đồng bào dân tộc</li>
<li>Phát hành tín chỉ carbon đạt tiêu chuẩn quốc tế Verified Carbon Standard</li>
</ul>

<h2>Công nghệ ứng dụng</h2>
<p>NLP Group ứng dụng bộ công nghệ tích hợp để đảm bảo tính minh bạch:</p>
<ul>
<li><strong>Ảnh vệ tinh:</strong> Giám sát diện tích rừng và biến động thực vật theo thời gian thực</li>
<li><strong>IoT cảm biến:</strong> Đo độ ẩm, nhiệt độ đất, chỉ số sinh trưởng của cây</li>
<li><strong>AI phân tích:</strong> Dự báo lượng carbon hấp thụ và tối ưu kế hoạch trồng rừng</li>
<li><strong>Blockchain:</strong> Ghi nhận và truy xuất nguồn gốc từng tín chỉ carbon</li>
</ul>

<h2>Ý nghĩa chiến lược</h2>
<p>Đây là bước đầu tiên trong chiến lược xây dựng nền tảng quản lý carbon thông minh của NLP Group. Mục tiêu đến 2027 là phát hành 500.000 tín chỉ carbon/năm, đưa Việt Nam lên bản đồ thị trường carbon tự nguyện quốc tế.</p>

<p>Dự án không chỉ tạo ra giá trị kinh tế mà còn góp phần bảo vệ đa dạng sinh học, chống biến đổi khí hậu và phát triển kinh tế xanh bền vững cho vùng Tây Nguyên.</p>';
    }
}
