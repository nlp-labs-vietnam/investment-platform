<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WikiController extends Controller
{
    public function nlpLabsVietnam()
    {
        $pageSeo = [
            'page_title'       => 'NLP Labs Vietnam — Wiki',
            'meta_title'       => 'NLP Labs Vietnam | Trung tâm Nghiên cứu & Phát triển Năng lượng Mặt trời',
            'meta_keywords'    => 'NLP Labs, NLP Group, nghiên cứu năng lượng mặt trời, R&D solar Vietnam, quang điện Việt Nam',
            'meta_description' => 'Tổng quan về NLP Labs Vietnam — bộ phận nghiên cứu và phát triển công nghệ quang điện của NLP Group, hoạt động tại Việt Nam.',
            'og_description'   => 'NLP Labs Vietnam là đơn vị R&D của NLP Group, chuyên nghiên cứu ứng dụng công nghệ điện mặt trời và năng lượng tái tạo tại Việt Nam.',
            'meta_robots'      => 'index, follow',
            'meta_image'       => null,
        ];

        return view('wiki.nlp-labs-vietnam', compact('pageSeo'));
    }

    public function about()
    {
        $pageSeo = [
            'page_title'       => 'Về NLP Group',
            'meta_title'       => 'Về NLP Group | Kiến tạo hệ sinh thái đổi mới bền vững',
            'meta_keywords'    => 'NLP Group, về chúng tôi, giới thiệu, tầm nhìn, sứ mệnh, giá trị cốt lõi',
            'meta_description' => 'NLP Group là doanh nghiệp phát triển hệ sinh thái công nghệ và năng lượng xanh tại Việt Nam.',
            'og_description'   => 'Tìm hiểu về NLP Group — tầm nhìn, sứ mệnh, giá trị cốt lõi và triết lý kinh doanh.',
            'meta_robots'      => 'index, follow',
            'meta_image'       => null,
        ];
        return view('wiki.about', compact('pageSeo'));
    }

    public function ecosystem()
    {
        $pageSeo = [
            'page_title'       => 'Hệ sinh thái NLP Group',
            'meta_title'       => 'Hệ sinh thái NLP Group | 5 lĩnh vực chiến lược',
            'meta_keywords'    => 'NLP Group, hệ sinh thái, NLP Energy, NLP EV Charging, NLP Technology, NLP AI, NLP ESG',
            'meta_description' => 'Khám phá 5 lĩnh vực chiến lược của NLP Group: Năng lượng, EV Charging, Công nghệ, AI, ESG & Carbon.',
            'og_description'   => '5 trụ cột kinh doanh của NLP Group hướng tới tương lai xanh và bền vững.',
            'meta_robots'      => 'index, follow',
            'meta_image'       => null,
        ];
        return view('wiki.ecosystem', compact('pageSeo'));
    }

    public function nlpEnergy()
    {
        $pageSeo = [
            'page_title'       => 'NLP Energy — Năng lượng xanh',
            'meta_title'       => 'NLP Energy | Điện mặt trời, BESS, EMS, EPC, O&M',
            'meta_keywords'    => 'NLP Energy, điện mặt trời, BESS, EMS, EPC, O&M, năng lượng tái tạo, solar Vietnam',
            'meta_description' => 'NLP Energy cung cấp giải pháp điện mặt trời áp mái, lưu trữ năng lượng BESS, quản lý EMS và tổng thầu EPC tại Việt Nam.',
            'og_description'   => 'Giải pháp năng lượng xanh toàn diện: Solar + BESS + EMS + EPC + O&M.',
            'meta_robots'      => 'index, follow',
            'meta_image'       => null,
        ];
        return view('wiki.nlp-energy', compact('pageSeo'));
    }

    public function nlpEvCharging()
    {
        $pageSeo = [
            'page_title'       => 'NLP EV Charging — Hạ tầng sạc xe điện',
            'meta_title'       => 'NLP EV Charging | Trụ sạc AC/DC, CSMS, OCPP, nhượng quyền trạm sạc',
            'meta_keywords'    => 'NLP EV Charging, trạm sạc xe điện, trụ sạc AC, trụ sạc DC, OCPP, CSMS, nhượng quyền',
            'meta_description' => 'NLP EV Charging phát triển hạ tầng sạc xe điện thông minh: Trụ sạc AC/DC, CSMS, OCPP, ứng dụng sạc và mô hình nhượng quyền.',
            'og_description'   => 'Kiến tạo hạ tầng giao thông điện hóa — kết nối tương lai di chuyển xanh.',
            'meta_robots'      => 'index, follow',
            'meta_image'       => null,
        ];
        return view('wiki.nlp-ev-charging', compact('pageSeo'));
    }

    public function nlpTechnology()
    {
        $pageSeo = [
            'page_title'       => 'NLP Technology — Nền tảng công nghệ số',
            'meta_title'       => 'NLP Technology | Phần mềm, Website, App, ERP, CRM, IoT, Cloud',
            'meta_keywords'    => 'NLP Technology, phần mềm, website, mobile app, ERP, CRM, IoT, cloud, chuyển đổi số',
            'meta_description' => 'NLP Technology cung cấp giải pháp phần mềm, website, ứng dụng di động, ERP, CRM, IoT và chuyển đổi số doanh nghiệp.',
            'og_description'   => 'Nền tảng công nghệ số — kiến tạo giải pháp thông minh cho doanh nghiệp hiện đại.',
            'meta_robots'      => 'index, follow',
            'meta_image'       => null,
        ];
        return view('wiki.nlp-technology', compact('pageSeo'));
    }

    public function nlpAiDigital()
    {
        $pageSeo = [
            'page_title'       => 'NLP AI & Digital — Trí tuệ nhân tạo',
            'meta_title'       => 'NLP AI & Digital | AI Agent, RAG, LLM, Computer Vision, Automation',
            'meta_keywords'    => 'NLP AI, AI Agent, RAG, LLM, trí tuệ nhân tạo, machine learning, computer vision, NLP Labs Vietnam',
            'meta_description' => 'NLP AI & Digital nghiên cứu và triển khai AI Agent, RAG/LLM chuyên ngành năng lượng, Computer Vision và tự động hóa doanh nghiệp.',
            'og_description'   => 'Ứng dụng trí tuệ nhân tạo trong kỷ nguyên số — biến dữ liệu thành giá trị.',
            'meta_robots'      => 'index, follow',
            'meta_image'       => null,
        ];
        return view('wiki.nlp-ai-digital', compact('pageSeo'));
    }

    public function nlpEsgCarbon()
    {
        $pageSeo = [
            'page_title'       => 'NLP ESG & Carbon — Phát triển bền vững',
            'meta_title'       => 'NLP ESG & Carbon | MRV, Tín chỉ carbon, Net Zero, Trồng rừng',
            'meta_keywords'    => 'NLP ESG, carbon, tín chỉ carbon, Net Zero, MRV, trồng rừng, nông nghiệp bền vững',
            'meta_description' => 'NLP ESG & Carbon phát triển giải pháp MRV số, quản lý tín chỉ carbon, trồng rừng bền vững và lộ trình Net Zero.',
            'og_description'   => 'Đồng hành cùng mục tiêu phát triển bền vững — Net Zero cho doanh nghiệp Việt Nam.',
            'meta_robots'      => 'index, follow',
            'meta_image'       => null,
        ];
        return view('wiki.nlp-esg-carbon', compact('pageSeo'));
    }
}
