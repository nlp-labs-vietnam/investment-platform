{{-- ============================================================
     Schema.org JSON-LD — NLP Group
     Tự động chèn:
       1. Organization (mọi trang)
       2. WebSite + SearchAction (mọi trang)
       3. Service (các trang hệ sinh thái)
     ============================================================ --}}

@php
    $siteUrl  = rtrim(config('app.url'), '/');
    $siteName = basicControl()->site_title ?? 'NLP Group';

    // Ánh xạ slug → thông tin Service
    $serviceMap = [
        'nlp-energy' => [
            'name'        => 'NLP Energy — Điện Mặt Trời & Lưu Trữ Điện',
            'description' => 'Tổng thầu EPC hệ thống điện mặt trời áp mái, khu công nghiệp và hệ thống BESS lưu trữ điện quy mô lớn.',
            'category'    => 'Renewable Energy',
            'url'         => $siteUrl . '/nlp-energy',
        ],
        'nlp-ev-charging' => [
            'name'        => 'NLP EV Charging — Trạm Sạc Xe Điện',
            'description' => 'Đầu tư, lắp đặt và vận hành mạng lưới trạm sạc xe điện AC/DC. Nhượng quyền trạm sạc EV toàn quốc.',
            'category'    => 'Electric Vehicle Charging',
            'url'         => $siteUrl . '/nlp-ev-charging',
        ],
        'nlp-technology' => [
            'name'        => 'NLP Technology — Phần Mềm & Chuyển Đổi Số',
            'description' => 'Phát triển phần mềm quản lý năng lượng, hệ thống IoT giám sát và giải pháp chuyển đổi số doanh nghiệp.',
            'category'    => 'Technology',
            'url'         => $siteUrl . '/nlp-technology',
        ],
        'nlp-ai-digital' => [
            'name'        => 'NLP AI & Digital — Trí Tuệ Nhân Tạo',
            'description' => 'Xây dựng AI agent doanh nghiệp, tự động hóa quy trình và triển khai giải pháp AI thực tiễn.',
            'category'    => 'Artificial Intelligence',
            'url'         => $siteUrl . '/nlp-ai-digital',
        ],
        'nlp-esg-carbon' => [
            'name'        => 'NLP ESG & Carbon — Tín Chỉ Carbon & Phát Triển Bền Vững',
            'description' => 'Tư vấn ESG, kiểm kê phát thải, mua bán tín chỉ carbon và lộ trình Net Zero cho doanh nghiệp.',
            'category'    => 'ESG & Sustainability',
            'url'         => $siteUrl . '/nlp-esg-carbon',
        ],
    ];

    $currentSlug = request()->route('slug') ?? ltrim(request()->path(), '/');
    $isHomePage  = (request()->path() === '/') || (request()->path() === '');
@endphp

{{-- 1. Organization --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "@id": "{{ $siteUrl }}/#organization",
  "name": "NLP Group",
  "alternateName": "Công ty CP Tập đoàn NLP",
  "url": "{{ $siteUrl }}",
  "logo": {
    "@type": "ImageObject",
    "url": "{{ $siteUrl }}/{{ ltrim(getFile(basicControl()->logo_driver ?? '', basicControl()->logo ?? ''), '/') }}"
  },
  "description": "NLP Group — Kiến tạo hệ sinh thái đổi mới bền vững: Năng lượng xanh, Xe điện, Công nghệ, AI và Phát triển bền vững.",
  "foundingDate": "2020",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "VN"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "customer service",
    "email": "info@nlpgroup.vn",
    "url": "{{ $siteUrl }}/contact"
  },
  "sameAs": [
    "https://www.facebook.com/nlpgroup.vn",
    "https://www.linkedin.com/company/nlpgroup-vn"
  ]
}
</script>

{{-- 2. WebSite + SearchAction --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "@id": "{{ $siteUrl }}/#website",
  "url": "{{ $siteUrl }}",
  "name": "{{ $siteName }}",
  "description": "NLP Group — Hệ sinh thái đổi mới bền vững tại Việt Nam",
  "publisher": {
    "@id": "{{ $siteUrl }}/#organization"
  },
  "potentialAction": {
    "@type": "SearchAction",
    "target": {
      "@type": "EntryPoint",
      "urlTemplate": "{{ $siteUrl }}/blog?q={search_term_string}"
    },
    "query-input": "required name=search_term_string"
  },
  "inLanguage": "vi-VN"
}
</script>

{{-- 3. Service (chỉ render trên các trang hệ sinh thái) --}}
@if(isset($serviceMap[$currentSlug]))
@php $svc = $serviceMap[$currentSlug]; @endphp
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "serviceType": "{{ $svc['category'] }}",
  "name": "{{ $svc['name'] }}",
  "description": "{{ $svc['description'] }}",
  "url": "{{ $svc['url'] }}",
  "provider": {
    "@id": "{{ $siteUrl }}/#organization"
  },
  "areaServed": {
    "@type": "Country",
    "name": "Vietnam"
  }
}
</script>
@endif

{{-- 4. BreadcrumbList (tất cả trang con) --}}
@if(!$isHomePage)
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Trang chủ",
      "item": "{{ $siteUrl }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "{{ isset($pageSeo['meta_title']) ? $pageSeo['meta_title'] : ucwords(str_replace(['-','_'], ' ', $currentSlug)) }}",
      "item": "{{ $siteUrl }}/{{ $currentSlug }}"
    }
  ]
}
</script>
@endif
