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
| Nội dung [`web-NLP-GROUP.md`](web-NLP-GROUP.md) | ✅ Sơ đồ UI tổng quan đầy đủ |
| Route các trang hệ sinh thái | ✅ Đã định nghĩa |
| Agent skills (4 skills) | ✅ Đã tạo |
| Permission `storage/logs` | ✅ Đã sửa |
| Pages NLP ecosystem (`nlp-energy`, `nlp-ev-charging`, `nlp-technology`, `nlp-ai-digital`, `nlp-esg-carbon`, `ecosystem`) | ✅ Nội dung HTML đầy đủ, HTTP 200 |
| Trang `solutions` | ✅ Nội dung 6 giải pháp, HTTP 200 |
| SEO metadata toàn bộ 19 pages | ✅ Đã seed (meta_title, meta_description, og_description, keywords) |
| Navigation menu header | ✅ Dropdown Hệ Sinh Thái + Sản Phẩm NLP + Giải Pháp |
| Footer menu | ✅ useful_link + support_link đầy đủ — đã fix tên page khớp DB |
| Column `manage_menus.menu_items` | ✅ Đã alter VARCHAR(255) → TEXT |
| Artisan commands | ✅ `nlp:seed-pages`, `nlp:seed-seo`, `nlp:seed-products` |
| Cron job Laravel Scheduler | ✅ `* * * * *` → `/var/log/nlpgroup-scheduler.log` |
| Kernel.php schedule commands | ✅ Đã fix tên lệnh `crypto-currency` → đúng command name |
| Dropdown `Sản Phẩm NLP` links | ✅ Đã fix: `"Trạm Sạc Xe Điện"` / `"Thiết Bị Điện Mặt Trời"` / `"NLP AI & Digital"` |
| Footer `tuyen-dung` link | ✅ Đã fix: `"Tuyển Dụng"` → `/tuyen-dung` |
| Social links FB/TW/LI/IG | ✅ Đã fix: trỏ đúng page NLP Group |
| Route `/ecosystem` | ✅ Hoạt động HTTP 200 |
| Route `/tuyen-dung` | ✅ Hoạt động HTTP 200 |
| Route `/nlp-ev-products` | ✅ Hoạt động HTTP 200 |
| Route `/nlp-energy-products` | ✅ Hoạt động HTTP 200 |
| Route `/nlp-ai-digital` | ✅ Hoạt động HTTP 200 |
| DB export `nlp03448_nlpgroup.sql` | ✅ Xuất tại `/var/www/investment-platform/` (492 KB) |
| `APP_DEBUG=false` | ✅ Đã xác nhận production mode |
| Script `backup-db.sh` | ✅ Backup DB MySQL hàng ngày — cron `30 2 * * *` → `/var/log/nlpgroup-db-backup.log` |
| Schema markup JSON-LD | ✅ `partials/schema_jsonld.blade.php` — Organization + WebSite + 5 Service + BreadcrumbList |
| Sitemap XML | ✅ `/public/sitemap.xml` — 17 URLs + artisan `nlp:generate-sitemap` (weekly cron) |
| Robots.txt | ✅ `/public/robots.txt` — trỏ đến sitemap |
| Thông tin liên hệ | ✅ `content_details` lang VI+EN — địa chỉ, hotline +84 936 611 163, email info@nlpgroup.vn |
| 10 bài blog EV (id 1–10) | ✅ Nội dung đầy đủ 2000–3900 ký tự/bài (command `nlp:seed-content`) |
| Trang `/tuyen-dung` | ✅ 7 vị trí thực tế: Backend, AI/ML, Điện, BD Manager, Tư vấn Solar, ESG, Marketing |
| Ảnh 4 card hệ sinh thái | ✅ SVG placeholder 800×500 (`storage/app/public/contents/nlp-*-card.svg`) — DB đã cập nhật |
| Admin panel branding | ✅ NLP KPI banner + sidebar icons + accent màu xanh `#2d6a4f` |
| CS Hub B2B SOP | ✅ `public/chatbot-ai/nlp_b2b_cs_assistant.html` — 28 kịch bản, 7 nhóm, route `/cs-hub` |
| **Cache → Redis** | ✅ `CACHE_DRIVER=redis` (db1) · `QUEUE_CONNECTION=redis` (db0) · `SESSION_DRIVER=redis` (db2) |
| **Mail failover** | ✅ `MAIL_MAILER=failover` — chain: SMTP → MailerSend → log · `MAILERSEND_API_KEY` cần điền |
| **Vue 2 upgrade** | ✅ `vue.min.js` nâng từ 2.6.12 → **2.7.16 LTS** · Migration plan: `docs/VUE_MIGRATION_PLAN.md` |

---

## GIAI ĐOẠN 1 — Nền tảng nội dung ✅ HOÀN THÀNH

### 1.1 Pages trong DB ✅
| Slug | Tên trang | SEO | Content |
|---|---|---|---|
| `/` | Trang chủ | ✅ | ✅ |
| `about` | Về NLP Group | ✅ | ✅ |
| `nlp-energy` | NLP Energy | ✅ | ✅ |
| `nlp-ev-charging` | NLP EV Charging | ✅ | ✅ |
| `nlp-technology` | NLP Technology | ✅ | ✅ |
| `nlp-ai-digital` | NLP AI & Digital | ✅ | ✅ |
| `nlp-esg-carbon` | NLP ESG & Carbon | ✅ | ✅ |
| `ecosystem` | Hệ sinh thái NLP | ✅ | ✅ |
| `solutions` | Giải pháp | ✅ | ✅ |
| `nlp-ev-products` | Trạm Sạc Xe Điện | ✅ | ✅ |
| `nlp-energy-products` | Thiết Bị Điện Mặt Trời | ✅ | ✅ |
| `tuyen-dung` | Tuyển Dụng | ✅ | ✅ |
| `faq` | Hỏi Đáp | ✅ | ✅ |
| `blog` | Tin tức | ✅ | ✅ |
| `contact` | Liên Hệ | ✅ | ✅ |
| `privacy-policy` | Chính Sách Bảo Mật | ✅ | ✅ |
| `terms-and-conditions` | Điều Khoản | ✅ | ✅ |
| `property` | Dự án đầu tư | ✅ | ✅ |

### 1.2 SEO Metadata ✅
- Tất cả 19 pages có `meta_title`, `meta_description`, keywords

### 1.3 Trang chủ sections ✅
- [x] Hero banner + stats (150+ dự án, 12MW, 8400 tấn CO₂)
- [x] Section Hệ sinh thái (4 cards + CTA)
- [x] Section Giới thiệu NLP Group
- [x] Section Dự án nổi bật (từ DB)
- [x] Section Testimonials (5 reviews)
- [x] Section Dự án mới nhất
- [x] Section Quy trình 4 bước
- [x] Section Tin tức (6 bài mới nhất)

---

## GIAI ĐOẠN 2 — Hoàn thiện giao diện & UX

### 2.1 Theme `green` — Kiểm tra & tinh chỉnh

- [ ] Màu sắc thương hiệu nhất quán (xanh lá chủ đạo)
- [ ] Typography rõ ràng, hiện đại
- [ ] Responsive mobile / tablet
- [ ] Tốc độ tải trang (Core Web Vitals)

### 2.2 Navigation menu

- [x] Menu chính: Trang chủ · Về NLP Group · Hệ sinh thái · Sản Phẩm NLP · Giải pháp · Tin tức · Liên hệ
- [x] Dropdown Hệ sinh thái: 5 lĩnh vực
- [x] Dropdown Sản Phẩm NLP: Trạm Sạc · Điện Mặt Trời · NLP AI & Digital
- [x] Dropdown Giải pháp: 5 lĩnh vực liên kết tới ecosystem pages
- [x] Fix null-safe crash khi page name không tồn tại trong DB
- [x] Fix relative path trong menu items (dùng đúng `pages.name`)

### 2.3 Footer

- [x] Logo + tagline NLP Group
- [x] Links nhanh: Trang chủ, Về, Giải Pháp, Blog, Hỏi Đáp, Liên Hệ, Tuyển Dụng
- [x] Hệ Sinh Thái NLP + 5 đơn vị thành viên + sản phẩm
- [x] Social media links (FB/TW/LI/IG đã fix — trỏ đúng page NLP Group)
- [x] Bản quyền © NLP Group
- [x] Thông tin liên hệ: địa chỉ Mega Village Khang Điền, hotline +84 936 611 163, email info@nlpgroup.vn

### 2.4 Section feature.blade.php

- [x] Link "Tìm hiểu thêm" trỏ đúng route (nlp-energy, nlp-ev-charging, nlp-technology, nlp-esg-carbon)
- [x] Nút "Xem toàn bộ hệ sinh thái NLP" → `/ecosystem`
- [x] Ảnh 4 card hệ sinh thái — SVG placeholder đã seed (`nlp-energy/ev-charging/technology/esg-carbon-card.svg`). Upload ảnh thực tế khi có để thay thế.

---

## GIAI ĐOẠN 3 — Nội dung chuyên sâu từng mảng

**Mục tiêu:** Mỗi trang hệ sinh thái có nội dung đầy đủ, thuyết phục nhà đầu tư & khách hàng

### 3.1 Trang NLP Energy (`/nlp-energy`) — có nội dung cơ bản
- [x] HTML content đã seed
- [ ] Bổ sung ảnh thực tế dự án (Solar rooftop photos)
- [ ] CTA: "Đặt lịch khảo sát miễn phí" → form liên hệ

### 3.2 Trang NLP EV Charging (`/nlp-ev-charging`) — có nội dung cơ bản
- [x] HTML content đã seed
- [ ] Bổ sung ảnh trạm sạc thực tế
- [ ] CTA: "Khảo sát địa điểm · Đăng ký nhượng quyền"

### 3.3 Trang NLP Technology (`/nlp-technology`) — có nội dung cơ bản
- [x] HTML content đã seed
- [ ] Portfolio dự án thực tế
- [ ] CTA: "Tư vấn giải pháp"

### 3.4 Trang NLP ESG & Carbon (`/nlp-esg-carbon`) — có nội dung cơ bản
- [x] HTML content đã seed
- [ ] Dự án tín chỉ carbon cụ thể
- [ ] CTA: "Đánh giá ESG miễn phí"

### 3.5 Trang Sản phẩm EV (`/nlp-ev-products`) ✅
- [x] Thông số kỹ thuật AC 7kW / 11kW / 22kW
- [x] Thông số kỹ thuật DC 30kW / 60kW / 120kW / 360kW
- [x] Bảng so sánh đầy đủ

### 3.6 Trang Sản phẩm Energy (`/nlp-energy-products`) ✅
- [x] Tấm pin Mono PERC 400Wp / TOPCon 550Wp
- [x] Inverter On-Grid 5kW / Hybrid 10kW
- [x] Hệ thống BESS 10kWh / 50kWh

### 3.7 Trang Hệ sinh thái (`/ecosystem`) ✅
- [x] Nội dung HTML đã seed, HTTP 200

### 3.8 Trang Tuyển Dụng (`/tuyen-dung`) ✅
- [x] Page & route hoạt động
- [x] Danh sách 7 vị trí tuyển dụng thực tế (seed qua `nlp:seed-content`)

---

## GIAI ĐOẠN 4 — Tính năng & Hệ thống

### 4.1 Blog & Tin tức

- [ ] Phân loại bài viết theo 6 chuyên mục (NLP Group · Năng lượng · Xe điện · Công nghệ · AI · ESG)
- [ ] Import bài viết đầu tiên cho từng chuyên mục (hiện có 6 bài placeholder ngày 13/10/2026)
- [ ] SEO cho từng bài (thumbnail thực tế thay cho default.png)

### 4.2 Thư viện media

- [ ] Upload hình ảnh dự án thực tế (Solar rooftop, EV stations)
- [ ] Video giới thiệu hệ sinh thái
- [ ] Hồ sơ năng lực PDF (download)
- [ ] Catalogue từng lĩnh vực

### 4.3 Form Liên hệ / Tư vấn

- [ ] Form tư vấn chuyên biệt theo từng lĩnh vực
- [ ] Email notification khi có lead mới
- [ ] CRM integration (NLP CRM)

### 4.4 NLP AI Chatbot (tích hợp)

- [ ] Chatbot tư vấn điện mặt trời (dùng `solar-energy-advisory` skill)
- [ ] Chatbot tư vấn trạm sạc EV
- [ ] Tích hợp vào website qua widget

---

## GIAI ĐOẠN 5 — SEO & Hiệu suất

### 5.1 On-page SEO

- [x] SEO metadata toàn bộ 19 pages (meta_title, meta_description, keywords)
- [x] Schema markup (Organization + WebSite + 5 Service + BreadcrumbList JSON-LD)
- [x] Sitemap XML tự động (`nlp:generate-sitemap` — cron weekly Chủ nhật 04:00)
- [x] Robots.txt (trỏ đến `https://nlpgroup.vn/sitemap.xml`)
- [ ] Fix thumbnail bài blog (hiện dùng `default.png`)

### 5.2 Từ khóa trọng tâm

| Lĩnh vực | Từ khóa chính |
|---|---|
| NLP Energy | điện mặt trời áp mái, tổng thầu EPC, BESS lưu trữ điện |
| NLP EV Charging | trụ sạc xe điện, trạm sạc EV, nhượng quyền trạm sạc |
| NLP Technology | phần mềm quản lý năng lượng, chuyển đổi số |
| NLP AI | AI agent doanh nghiệp, tự động hóa AI |
| NLP ESG | tín chỉ carbon, Net Zero, ESG doanh nghiệp |

### 5.3 Hiệu suất kỹ thuật

- [ ] Image optimization (WebP, lazy load)
- [ ] CDN cho assets tĩnh
- [ ] HTTPS + HTTP/2 (hiện đang redirect 301 HTTP→HTTPS ✅)
- [ ] Core Web Vitals: LCP < 2.5s, FID < 100ms, CLS < 0.1

---

## GIAI ĐOẠN 6 — Vận hành & Bảo trì

### 6.1 Bảo trì định kỳ

- [x] Cron job: `* * * * * /usr/bin/php /var/www/investment-platform/artisan schedule:run`
- [x] Log: `/var/log/nlpgroup-scheduler.log`
- [x] Schedule tasks: cache:warm · cache:prune · main:cron · badge:cron · UpdatePropertyJob · model:prune · payout-currency:update · payout-crypto-currency-update-cron
- [x] Backup DB tự động hàng ngày (`backup-db.sh` — cron `30 2 * * *`, lưu tại `/var/backups/nlpgroup-db/`, giữ 7 bản)
- [ ] Log rotation (Laravel log channel `daily`)
- [ ] Monitor uptime (UptimeRobot hoặc tương đương)

### 6.2 Bảo mật

- [x] Xác nhận `APP_ENV=production`, `APP_DEBUG=false` trong `.env`
- [x] CSRF protection đã bật (Laravel default ✅)
- [ ] Rate limiting trên form liên hệ
- [ ] Cập nhật dependency định kỳ (`composer update`)

### 6.5 Performance & Drivers ✅

- [x] Cache driver: `file` → **Redis db1** (`CACHE_DRIVER=redis`)
- [x] Queue driver: `database` → **Redis db0** (`QUEUE_CONNECTION=redis`)
- [x] Session driver: `file` → **Redis db2** (`SESSION_DRIVER=redis`, connection `session`)
- [x] Redis DB separation: db0=default · db1=cache · db2=session (tránh key collision)
- [x] Vue 2: `2.6.12` (EOL) → **`2.7.16` LTS** (`assets/global/js/vue.min.js`)
- [ ] Vue 2 → Alpine.js/Vue 3 migration (xem `docs/VUE_MIGRATION_PLAN.md`)

### 6.6 Email ⚠️ Cần hoàn thiện

- [x] `MAIL_MAILER=failover` — chain: SMTP → MailerSend → log
- [x] `config/mail.php` failover chain đã tinh gọn
- [ ] **[ACTION REQUIRED]** Điền `MAILERSEND_API_KEY` tại https://app.mailersend.com/api-tokens
- [ ] **[ACTION REQUIRED]** Cập nhật `MAIL_HOST` thành SMTP server thực tế (Zoho/Google Workspace/AWS SES)
- [ ] Test gửi email thực tế: `php artisan tinker` → `Mail::to('test@nlpgroup.vn')->send(...)`

### 6.3 Storage permissions (đã fix)

```bash
# Chạy lại nếu bị reset sau deploy
chown -R www-data:www-data /var/www/investment-platform/storage/
chmod -R 775 /var/www/investment-platform/storage/
chmod -R 775 /var/www/investment-platform/bootstrap/cache/
```

### 6.4 Database

- [x] Backup thủ công: `nlp03448_nlpgroup.sql` (492 KB — 2,096 dòng)
- [x] Connection `mysql_aws` đã cấu hình trong `config/database.php`
- [ ] Thiết lập auto-backup hàng ngày → S3 hoặc AWS RDS

---

## Checklist việc cần làm tiếp theo

### 🔴 Ưu tiên cao
- [x] **[Content]** Upload ảnh thực tế cho 4 card hệ sinh thái — SVG placeholder đã seed; thay bằng ảnh thực khi có
- [x] **[Content]** Cập nhật thông tin liên hệ trong Content admin — đã update qua DB
- [x] **[Dev]** Gắn `backup-db.sh` vào crontab (backup DB hàng ngày — 02:30 AM)
- [x] **[Dev]** Xác nhận `APP_DEBUG=false` trong `.env` production

### 🟡 Ưu tiên trung bình
- [x] **[Content]** 10 bài blog EV (id 1–10) nội dung đầy đủ 2000–3900 ký tự (`nlp:seed-content`)
- [ ] **[Content]** Viết thêm bài blog cho chuyên mục Năng lượng, Công nghệ, AI, ESG (thumbnail thực tế)
- [x] **[Dev]** Schema markup JSON-LD cho Organization + 5 Service pages (`schema_jsonld.blade.php`)
- [x] **[Dev]** Sitemap XML (`/sitemap.xml`) + Robots.txt (`/robots.txt`)
- [x] **[Content]** Danh sách 7 vị trí tuyển dụng thực tế tại `/tuyen-dung` (`nlp:seed-content --force`)

### 🟢 Ưu tiên thấp
- [ ] **[Content]** Upload ảnh thực tế thay thế 4 SVG placeholder card hệ sinh thái
- [ ] **[Dev]** NLP AI Chatbot widget tích hợp vào website
- [ ] **[Dev]** CDN cho assets tĩnh
- [ ] **[Dev]** Monitor uptime tự động

---

## Liên kết tham chiếu

| Tài liệu | Mô tả |
|---|---|
| [`web-NLP-GROUP.md`](web-NLP-GROUP.md) | Sơ đồ UI tổng quan website NLP Group |
| [`nlp03448_nlpgroup.sql`](nlp03448_nlpgroup.sql) | DB export mới nhất (492 KB) |
| [`app/Console/Kernel.php`](app/Console/Kernel.php) | Laravel Scheduler — schedule tasks |
| [`config/database.php`](config/database.php) | DB connections (mysql local + mysql_aws) |
| [`backup-db.sh`](backup-db.sh) | Script backup DB MySQL — chạy hàng ngày 02:30 AM |
| [`app/Console/Commands/GenerateSitemap.php`](app/Console/Commands/GenerateSitemap.php) | Artisan `nlp:generate-sitemap` |
| [`app/Console/Commands/SeedNlpContent.php`](app/Console/Commands/SeedNlpContent.php) | Artisan `nlp:seed-content` — blog + tuyen-dung |
| [`resources/views/themes/green/partials/schema_jsonld.blade.php`](resources/views/themes/green/partials/schema_jsonld.blade.php) | Schema.org JSON-LD partial |
| [`storage/app/public/contents/`](storage/app/public/contents/) | SVG placeholder 4 card hệ sinh thái |
| [`agent-skills/solar-energy-advisory/`](agent-skills/solar-energy-advisory/SKILL.md) | Skill tư vấn năng lượng mặt trời |
| [`agent-skills/ev-charging-operations/`](agent-skills/ev-charging-operations/SKILL.md) | Skill vận hành trạm sạc EV |
| [`agent-skills/esg-carbon-accounting/`](agent-skills/esg-carbon-accounting/SKILL.md) | Skill ESG & tín chỉ carbon |
| [`agent-skills/grant-pitching-partnerships/`](agent-skills/grant-pitching-partnerships/SKILL.md) | Skill pitch đối tác & đầu tư |
| [`routes/web.php`](routes/web.php) | Định nghĩa routes Laravel |
| [`resources/views/themes/green/`](resources/views/themes/green/) | Theme giao diện hiện tại |
