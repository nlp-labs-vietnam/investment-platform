<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

/**
 * Seed SEO metadata (page_title, meta_title, meta_description, og_description,
 * meta_keywords, meta_robots) for the 6 NLP ecosystem pages.
 *
 * Usage:
 *   php artisan nlp:seed-seo            # skips pages that already have meta_title
 *   php artisan nlp:seed-seo --force    # overwrites all
 */
class SeedNlpSeo extends Command
{
    protected $signature   = 'nlp:seed-seo {--force : Overwrite existing SEO data}';
    protected $description = 'Seed SEO metadata for NLP ecosystem pages';

    private function seoData(): array
    {
        return [
            'nlp-energy' => [
                'page_title'       => 'NLP Energy – Giải pháp năng lượng xanh | NLP Group',
                'meta_title'       => 'NLP Energy – Điện mặt trời áp mái, BESS, EMS | NLP Group',
                'meta_description' => 'NLP Energy cung cấp giải pháp điện mặt trời áp mái, hệ thống lưu trữ BESS, quản lý năng lượng EMS và dịch vụ EPC, O&M toàn diện cho doanh nghiệp và hộ gia đình.',
                'og_description'   => 'Chuyển đổi năng lượng thông minh — điện mặt trời, BESS, EMS và EPC trọn gói từ NLP Energy.',
                'meta_keywords'    => ['điện mặt trời áp mái', 'năng lượng mặt trời', 'BESS lưu trữ điện', 'EMS quản lý năng lượng', 'EPC điện mặt trời', 'tổng thầu EPC', 'O&M hệ thống điện', 'NLP Energy'],
                'meta_robots'      => 'index,follow',
            ],
            'nlp-ev-charging' => [
                'page_title'       => 'NLP EV Charging – Trạm sạc xe điện thông minh | NLP Group',
                'meta_title'       => 'NLP EV Charging – Trụ sạc AC/DC, CSMS, Nhượng quyền trạm sạc',
                'meta_description' => 'NLP EV Charging phát triển hạ tầng trạm sạc xe điện thông minh: trụ sạc AC/DC, phần mềm CSMS, chuẩn OCPP, ứng dụng người dùng và mô hình nhượng quyền trạm sạc.',
                'og_description'   => 'Hạ tầng sạc xe điện thông minh — trụ sạc AC/DC, CSMS, app người dùng và nhượng quyền từ NLP EV Charging.',
                'meta_keywords'    => ['trụ sạc xe điện', 'trạm sạc EV', 'nhượng quyền trạm sạc', 'sạc xe điện AC DC', 'CSMS quản lý trạm sạc', 'OCPP', 'hạ tầng xe điện', 'NLP EV Charging'],
                'meta_robots'      => 'index,follow',
            ],
            'nlp-technology' => [
                'page_title'       => 'NLP Technology – Giải pháp phần mềm & Chuyển đổi số | NLP Group',
                'meta_title'       => 'NLP Technology – Phát triển phần mềm, ERP, CRM, IoT, AI',
                'meta_description' => 'NLP Technology cung cấp giải pháp phát triển phần mềm theo yêu cầu, website, mobile app, ERP, CRM, IoT, API, Cloud và tư vấn chuyển đổi số cho doanh nghiệp Việt Nam.',
                'og_description'   => 'Nền tảng công nghệ số toàn diện — phần mềm, ERP, CRM, IoT, Cloud và chuyển đổi số từ NLP Technology.',
                'meta_keywords'    => ['phần mềm quản lý doanh nghiệp', 'ERP CRM', 'chuyển đổi số', 'phát triển phần mềm', 'IoT platform', 'mobile app', 'website doanh nghiệp', 'NLP Technology'],
                'meta_robots'      => 'index,follow',
            ],
            'nlp-ai-digital' => [
                'page_title'       => 'NLP AI & Digital – Trí tuệ nhân tạo cho doanh nghiệp | NLP Group',
                'meta_title'       => 'NLP AI – AI Agent, Chatbot, Machine Learning, Computer Vision',
                'meta_description' => 'NLP AI & Digital nghiên cứu và triển khai AI Agent, chatbot tiếng Việt, Machine Learning, Computer Vision, phân tích dữ liệu và tự động hóa quy trình cho doanh nghiệp.',
                'og_description'   => 'Ứng dụng trí tuệ nhân tạo thực tiễn — AI Agent, chatbot, ML, Computer Vision và tự động hóa từ NLP AI & Digital.',
                'meta_keywords'    => ['AI agent doanh nghiệp', 'chatbot tiếng Việt', 'machine learning', 'computer vision', 'tự động hóa AI', 'phân tích dữ liệu', 'NLP AI Digital', 'trí tuệ nhân tạo'],
                'meta_robots'      => 'index,follow',
            ],
            'nlp-esg-carbon' => [
                'page_title'       => 'NLP ESG & Carbon – Phát triển bền vững & Tín chỉ carbon | NLP Group',
                'meta_title'       => 'NLP ESG – Tín chỉ carbon, Net Zero, Trồng rừng, Tư vấn ESG',
                'meta_description' => 'NLP ESG & Carbon cung cấp giải pháp tín chỉ carbon, trồng rừng bền vững, nông nghiệp carbon thấp, tư vấn ESG doanh nghiệp và lộ trình Net Zero tại Việt Nam.',
                'og_description'   => 'Phát triển bền vững toàn diện — tín chỉ carbon, trồng rừng, tư vấn ESG và lộ trình Net Zero từ NLP ESG & Carbon.',
                'meta_keywords'    => ['tín chỉ carbon', 'Net Zero', 'ESG doanh nghiệp', 'trồng rừng bền vững', 'nông nghiệp carbon thấp', 'MRV carbon', 'báo cáo bền vững', 'NLP ESG Carbon'],
                'meta_robots'      => 'index,follow',
            ],
            'ecosystem' => [
                'page_title'       => 'Hệ sinh thái NLP Group – Năng lượng xanh, Xe điện, Công nghệ, AI, ESG',
                'meta_title'       => 'Hệ sinh thái NLP Group – 5 lĩnh vực chiến lược tích hợp',
                'meta_description' => 'Hệ sinh thái NLP Group gồm 5 lĩnh vực: NLP Energy, NLP EV Charging, NLP Technology, NLP AI & Digital, NLP ESG & Carbon — giải pháp tích hợp xanh và số cho doanh nghiệp Việt Nam.',
                'og_description'   => 'Một đối tác — toàn bộ hệ sinh thái năng lượng xanh, xe điện, công nghệ, AI và ESG từ NLP Group.',
                'meta_keywords'    => ['hệ sinh thái NLP Group', 'năng lượng xanh công nghệ', 'xe điện Việt Nam', 'chuyển đổi số xanh', 'ESG Việt Nam', 'NLP Group', 'giải pháp tích hợp'],
                'meta_robots'      => 'index,follow',
            ],
        ];
    }

    public function handle(): int
    {
        foreach ($this->seoData() as $slug => $seo) {
            $page = Page::where('slug', $slug)->where('template_name', 'green')->first();

            if (! $page) {
                $this->warn("  skip  {$slug} (page not found)");
                continue;
            }

            if ($page->meta_title && ! $this->option('force')) {
                $this->line("  skip  {$slug} (already has meta_title; use --force to overwrite)");
                continue;
            }

            $page->update([
                'page_title'       => $seo['page_title'],
                'meta_title'       => $seo['meta_title'],
                'meta_description' => $seo['meta_description'],
                'og_description'   => $seo['og_description'],
                'meta_keywords'    => $seo['meta_keywords'],      // cast → JSON via model
                'meta_robots'      => $seo['meta_robots'],
            ]);

            $this->info("  ✓  {$slug}");
        }

        $this->info('Done.');
        return self::SUCCESS;
    }
}
