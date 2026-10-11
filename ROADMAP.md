# ROADMAP — nlpgroup.vn

> **NLP Group** · Kiến tạo hệ sinh thái đổi mới bền vững  
> Năng lượng xanh • Xe điện • Công nghệ • Trí tuệ nhân tạo • Phát triển bền vững

---

## Trạng thái hiện tại

| Hạng mục | Tình trạng |
|---|---|
| Website `nlpgroup.vn` | ✅ Online (HTTP 200) |
| Laravel app & database | ✅ Hoạt động |
| Giao diện theme `green` | ✅ Đang chạy |
| Nội dung web-NLP-GROUP.md | ✅ Đầy đủ 7 phần |
| Route các trang hệ sinh thái | ✅ Đã định nghĩa |
| Agent skills (4 skills) | ✅ Đã tạo |
| Permission `storage/logs` | ✅ Đã sửa |
| Pages trong DB (slug có nội dung) | ⚠️ Mới có: `/`, `about`, `contact`, `solutions`, `faq`, `blog` |
| Pages NLP ecosystem trong DB | ❌ Chưa có: `nlp-energy`, `nlp-ev-charging`, `nlp-technology`, `nlp-esg-carbon`, `ecosystem` |
| SEO metadata đã chuẩn hóa | ✅ Có trong `Pland.md` — chờ import |

---

## GIAI ĐOẠN 1 — Nền tảng nội dung

**Mục tiêu:** Đưa đầy đủ nội dung 5 mảng hệ sinh thái lên website

### 1.1 Tạo Pages trong Admin cho các slug còn thiếu

Vào Admin → Pages → tạo mới cho từng slug, dùng nội dung từ [`web-NLP-GROUP.md`](web-NLP-GROUP.md):

| Slug | Tên trang | Nguồn nội dung |
|---|---|---|
| `nlp-energy` | NLP Energy | Phần 3 |
| `nlp-ev-charging` | NLP EV Charging | Phần 4 |
| `nlp-technology` | NLP Technology | Phần 5 |
| `nlp-esg-carbon` | NLP ESG & Carbon | Phần 7 |
| `ecosystem` | Hệ sinh thái NLP | Phần 1 + tổng hợp |

### 1.2 Import SEO metadata

Dùng bảng đã chuẩn hóa trong [`Pland.md`](Pland.md) để update các field:
- `meta_title`, `meta_description`, `og_description`, `meta_keywords`
- Các page đã có: `home`, `about`, `contact`, `solutions`, `faq`, `blog`, `privacy-policy`, `terms-and-conditions`

### 1.3 Hoàn thiện nội dung trang chủ

- [ ] Banner hero: "NLP GROUP — Kiến tạo hệ sinh thái đổi mới bền vững"
- [ ] Section Hệ sinh thái (5 lĩnh vực): Energy · EV Charging · Technology · AI · ESG
- [ ] Section Điểm khác biệt
- [ ] Section Dự án tiêu biểu
- [ ] Section Đối tác chiến lược
- [ ] CTA: Liên hệ / Tư vấn miễn phí

---

## GIAI ĐOẠN 2 — Hoàn thiện giao diện & UX

**Mục tiêu:** Giao diện phản ánh đúng thương hiệu NLP Group (Hiện đại – Công nghệ – Xanh – Bền vững)

### 2.1 Theme `green` — Kiểm tra & tinh chỉnh

- [ ] Màu sắc thương hiệu nhất quán (xanh lá chủ đạo)
- [ ] Typography rõ ràng, hiện đại
- [ ] Responsive mobile / tablet
- [ ] Tốc độ tải trang (Core Web Vitals)

### 2.2 Navigation menu

- [ ] Menu chính: Trang chủ · Về NLP Group · Hệ sinh thái · Giải pháp · Dự án · Đối tác · Tin tức · Liên hệ
- [ ] Dropdown Hệ sinh thái: 5 lĩnh vực
- [ ] Dropdown Giải pháp: 6 loại

### 2.3 Footer

- [ ] Logo + tagline NLP Group
- [ ] Links nhanh theo cấu trúc site
- [ ] Thông tin liên hệ: địa chỉ, hotline, email
- [ ] Social media links
- [ ] Bản quyền © NLP Group

### 2.4 Section feature.blade.php

- [ ] Kiểm tra 4 card hệ sinh thái hiển thị đúng hình ảnh
- [ ] Link "Tìm hiểu thêm" trỏ đúng route
- [ ] Nút "Xem toàn bộ hệ sinh thái NLP" → `/ecosystem`

---

## GIAI ĐOẠN 3 — Nội dung chuyên sâu từng mảng

**Mục tiêu:** Mỗi trang hệ sinh thái có nội dung đầy đủ, thuyết phục

### 3.1 Trang NLP Energy (`/nlp-energy`)

Dựa theo [`agent-skills/solar-energy-advisory/SKILL.md`](agent-skills/solar-energy-advisory/SKILL.md):
- [ ] Giới thiệu + Tầm nhìn
- [ ] 7 giải pháp: Áp mái · Nhà dân · Nhà xưởng · BESS · EMS · EPC · O&M
- [ ] Quy trình triển khai (7 bước)
- [ ] Giá trị khác biệt
- [ ] CTA: Đặt lịch khảo sát miễn phí

### 3.2 Trang NLP EV Charging (`/nlp-ev-charging`)

Dựa theo [`agent-skills/ev-charging-operations/SKILL.md`](agent-skills/ev-charging-operations/SKILL.md):
- [ ] Giới thiệu + Tầm nhìn
- [ ] 8 giải pháp: AC · DC · Trạm thông minh · CSMS · OCPP · App · Doanh nghiệp · Nhượng quyền
- [ ] Quy trình triển khai (8 bước)
- [ ] Mô hình hệ sinh thái
- [ ] CTA: Khảo sát địa điểm · Đăng ký nhượng quyền

### 3.3 Trang NLP Technology (`/nlp-technology`)

Dựa theo Phần 5 — [`web-NLP-GROUP.md`](web-NLP-GROUP.md#phần-5):
- [ ] 10 dịch vụ: Phần mềm · Website · Mobile · ERP · CRM · IoT · API · Cloud · SaaS · Chuyển đổi số
- [ ] Quy trình 7 bước
- [ ] CTA: Tư vấn giải pháp

### 3.4 Trang NLP ESG & Carbon (`/nlp-esg-carbon`)

Dựa theo [`agent-skills/esg-carbon-accounting/SKILL.md`](agent-skills/esg-carbon-accounting/SKILL.md):
- [ ] 7 lĩnh vực: Rừng · Nông nghiệp · Carbon Project · Carbon Credit · MRV · ESG · Net Zero
- [ ] Lộ trình Net Zero 6 bước
- [ ] CTA: Đánh giá ESG miễn phí

### 3.5 Trang Hệ sinh thái (`/ecosystem`)

- [ ] Sơ đồ tổng thể NLP Group (5 đơn vị thành viên)
- [ ] Cách 5 mảng kết nối với nhau
- [ ] Giá trị tích hợp toàn diện

---

## GIAI ĐOẠN 4 — Tính năng & Hệ thống

**Mục tiêu:** Bổ sung các tính năng quan trọng còn thiếu

### 4.1 Blog & Tin tức

- [ ] Phân loại bài viết theo 6 chuyên mục (NLP Group · Năng lượng · Xe điện · Công nghệ · AI · ESG)
- [ ] Import bài viết đầu tiên cho từng chuyên mục
- [ ] SEO cho từng bài

### 4.2 Thư viện media

- [ ] Upload hình ảnh dự án thực tế
- [ ] Video giới thiệu hệ sinh thái
- [ ] Hồ sơ năng lực PDF (download)
- [ ] Catalogue từng lĩnh vực

### 4.3 Tuyển dụng

- [ ] Trang `/tuyen-dung` với danh sách vị trí đang tuyển

### 4.4 Form Liên hệ / Tư vấn

- [ ] Form tư vấn chuyên biệt theo từng lĩnh vực
- [ ] Email notification khi có lead mới
- [ ] CRM integration (NLP CRM)

### 4.5 NLP AI Chatbot (tích hợp)

- [ ] Chatbot tư vấn điện mặt trời (dùng `solar-energy-advisory` skill)
- [ ] Chatbot tư vấn trạm sạc EV
- [ ] Tích hợp vào website qua widget

---

## GIAI ĐOẠN 5 — SEO & Hiệu suất

**Mục tiêu:** Đạt thứ hạng tốt trên Google cho các từ khóa trọng tâm

### 5.1 On-page SEO

- [ ] Import toàn bộ metadata từ [`Pland.md`](Pland.md) vào DB
- [ ] Tạo SEO metadata cho 5 trang hệ sinh thái mới
- [ ] Schema markup (Organization, LocalBusiness, Service)
- [ ] Sitemap XML tự động
- [ ] Robots.txt

### 5.2 Từ khóa trọng tâm

| Lĩnh vực | Từ khóa chính |
|---|---|
| NLP Energy | điện mặt trời áp mái, tổng thầu EPC, BESS lưu trữ điện |
| NLP EV Charging | trụ sạc xe điện, trạm sạc EV, nhượng quyền trạm sạc |
| NLP Technology | phần mềm quản lý năng lượng, chuyển đổi số |
| NLP AI | AI agent doanh nghiệp, tự động hóa AI |
| NLP ESG | tín chỉ carbon, Net Zero, ESG doanh nghiệp |

### 5.3 Hiệu suất kỹ thuật

- [ ] Cấu hình cache (Laravel Cache, Redis)
- [ ] Image optimization (WebP, lazy load)
- [ ] CDN cho assets tĩnh
- [ ] HTTPS + HTTP/2
- [ ] Core Web Vitals: LCP < 2.5s, FID < 100ms, CLS < 0.1

---

## GIAI ĐOẠN 6 — Vận hành & Bảo trì

### 6.1 Bảo trì định kỳ

- [ ] Backup DB tự động hàng ngày
- [ ] Cron job: `php artisan schedule:run`
- [ ] Log rotation (Laravel log channel `daily`)
- [ ] Monitor uptime (UptimeRobot hoặc tương đương)

### 6.2 Bảo mật

- [ ] `APP_ENV=production`, `APP_DEBUG=false` trong `.env`
- [ ] CSRF protection đã bật (Laravel default)
- [ ] Rate limiting trên form liên hệ
- [ ] Cập nhật dependency định kỳ (`composer update`)

### 6.3 Storage permissions (đã fix)

```bash
# Chạy lại nếu bị reset sau deploy
chown -R www-data:www-data /var/www/investment-platform/storage/
chmod -R 775 /var/www/investment-platform/storage/
chmod -R 775 /var/www/investment-platform/bootstrap/cache/
```

---

## Checklist nhanh — việc cần làm ngay

- [ ] **[Admin]** Tạo 5 pages mới trong Admin: `nlp-energy`, `nlp-ev-charging`, `nlp-technology`, `nlp-esg-carbon`, `ecosystem`
- [ ] **[Admin]** Import SEO metadata từ `Pland.md` cho 8 pages hiện có
- [ ] **[Dev]** Xác nhận các section template `green` hiển thị đúng với content mới
- [ ] **[Content]** Viết nội dung cho từng page hệ sinh thái (dùng `web-NLP-GROUP.md` làm nguồn)
- [ ] **[Dev]** Thêm route `nlp-ai-digital` nếu cần trang riêng cho NLP AI & Digital

---

## Liên kết tham chiếu

| Tài liệu | Mô tả |
|---|---|
| [`web-NLP-GROUP.md`](web-NLP-GROUP.md) | Toàn bộ nội dung website NLP Group (7 phần) |
| [`Pland.md`](Pland.md) | Bảng SEO metadata đã chuẩn hóa |
| [`agent-skills/solar-energy-advisory/`](agent-skills/solar-energy-advisory/SKILL.md) | Skill tư vấn năng lượng mặt trời |
| [`agent-skills/ev-charging-operations/`](agent-skills/ev-charging-operations/SKILL.md) | Skill vận hành trạm sạc EV |
| [`agent-skills/esg-carbon-accounting/`](agent-skills/esg-carbon-accounting/SKILL.md) | Skill ESG & tín chỉ carbon |
| [`agent-skills/grant-pitching-partnerships/`](agent-skills/grant-pitching-partnerships/SKILL.md) | Skill pitch đối tác & đầu tư |
| [`routes/web.php`](routes/web.php) | Định nghĩa routes Laravel |
| [`resources/views/themes/green/`](resources/views/themes/green/) | Theme giao diện hiện tại |
