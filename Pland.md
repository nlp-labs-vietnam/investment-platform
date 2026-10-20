# SKILL · AGENT · PLAN — NLP Group Platform

> **NLP Group** · Kiến tạo hệ sinh thái đổi mới bền vững  
> Codebase: `nlp-labs-vietnam/investment-platform` · Branch: `main`  
> Stack: PHP 8.2 / Laravel · Node.js v22 LTS · MySQL 8.0 · Redis · Vue 2.7 LTS

---

## 1. STACK KỸ THUẬT (System Profile)

| Layer | Công nghệ | Phiên bản | Ghi chú |
|---|---|---|---|
| Backend | PHP / Laravel | PHP 8.2.33 / Laravel 9.x | Production — `APP_ENV=production` |
| Frontend runtime | **Node.js** | **v22.23.3 LTS** | `package.json` `@nlp-labs-vietnam/investment-ui` |
| Build tool | Vite + SCSS | Vite 4.x + Bootstrap 5.2 | `vite.config.js` |
| Frontend lib | **Vue.js** | **2.7.16 LTS** | Nâng từ 2.6.12 (EOL) — `assets/global/js/vue.min.js` |
| Database chính | **MySQL** | **8.0.46** (Ubuntu 24.04) | `DB_CONNECTION=mysql` · host `127.0.0.1:3306` |
| Database AWS | MySQL RDS | (AWS us-west-1) | `mysql_aws` connection — `config/database.php` |
| Cache | **Redis db1** | 6.x+ | `CACHE_DRIVER=redis` · `REDIS_CACHE_DB=1` |
| Queue | **Redis db0** | 6.x+ | `QUEUE_CONNECTION=redis` · `REDIS_DB=0` |
| Session | **Redis db2** | 6.x+ | `SESSION_DRIVER=redis` · `REDIS_SESSION_DB=2` |
| Mail | Failover SMTP | — | Chain: SMTP → MailerSend → log |
| Web server | Apache/Nginx | Ubuntu 24.04 | HTTPS enforced, HTTP→HTTPS 301 |
| Repository | **GitHub** | — | `git@github.com:nlp-labs-vietnam/investment-platform.git` |
| SSH Auth | `nlpgroup` key | — | `~/.ssh/nlpgroup` · `contact@nlpgroup.vn` |

---

## 2. DATABASE — Schema & Kết nối

### 2.1 Kết nối (`config/database.php`)

```php
// Local MySQL (production)
'mysql' => [
    'host'     => env('DB_HOST', '127.0.0.1'),
    'port'     => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE'),   // nlp03448_nlpgroup
    'charset'  => 'utf8mb4',
    'strict'   => false,
]

// AWS RDS backup
'mysql_aws' => [
    'host'     => env('DB_AWS_HOST'),   // lb-xxx.elb.us-west-1.amazonaws.com
    'port'     => env('DB_AWS_PORT', '9030'),
    'database' => env('DB_AWS_DATABASE'),
    'charset'  => 'utf8mb4',
    'strict'   => false,
]
```

### 2.2 Redis DB Separation (tránh key collision)

| Connection | DB index | Mục đích |
|---|---|---|
| `default` | db0 | Queue jobs |
| `cache` | db1 | Laravel Cache |
| `session` | db2 | Session store |

### 2.3 Bảng DB quan trọng

| Bảng | Mục đích |
|---|---|
| `pages` | Nội dung trang (slug, content, meta) |
| `page_details` | SEO chi tiết theo ngôn ngữ (VI/EN) |
| `manage_menus` | Menu header/footer (col `menu_items` TEXT) |
| `content_details` | Thông tin liên hệ, KPI, nội dung động |
| `contents` | Blog posts, media |
| `gateways` | Payment gateway (VND, MB Bank) |
| `currencies` | Tiền tệ (VND base) |

### 2.4 Backup DB

```bash
# Manual export
mysqldump -u USER -p DB_NAME > nlp03448_nlpgroup.sql

# Auto backup cron (02:30 AM daily)
30 2 * * * /var/www/investment-platform/backup-db.sh >> /var/log/nlpgroup-db-backup.log 2>&1
# Lưu tại: /var/backups/nlpgroup-db/ — giữ 7 bản
```

---

## 3. NODE.JS — Setup & Scripts

### 3.1 Phiên bản & Dependencies

```
Node.js  : v22.23.3 LTS
npm      : (bundled với Node 22)
```

**`package.json` scripts:**
```bash
npm run dev      # Vite dev server (HMR)
npm run build    # Vite production build → public/build/
```

**devDependencies:**
| Package | Version |
|---|---|
| `vite` | ^4.0.0 |
| `bootstrap` | ^5.2.3 |
| `sass` | ^1.56.1 |
| `laravel-vite-plugin` | ^0.7.5 |
| `@popperjs/core` | ^2.11.6 |
| `axios` | ^1.1.2 |

### 3.2 Vue.js

- **Version**: 2.7.16 LTS (file `assets/global/js/vue.min.js`)
- **Backup cũ**: `assets/global/js/vue.min.js.v2.6.12.bak`
- **Migration plan**: `docs/VUE_MIGRATION_PLAN.md` (Vue 2 → Alpine.js/Vue 3)

---

## 4. GITHUB — Repository & Workflow

### 4.1 Remote & Authentication

```bash
# Remote URL (SSH)
git remote set-url origin git@github.com:nlp-labs-vietnam/investment-platform.git

# SSH config (~/.ssh/config)
Host github.com
  HostName github.com
  User git
  IdentityFile ~/.ssh/nlpgroup
  IdentitiesOnly yes

# Test kết nối
ssh -T git@github.com
# → Hi thekingbao! You've successfully authenticated...
```

### 4.2 Deploy Workflow

```bash
# 1. Stage changes
git add <files>

# 2. Commit
git commit -m "type(scope): mô tả thay đổi"

# 3. Push
git push origin main
```

**Commit convention:**
| Prefix | Dùng khi |
|---|---|
| `feat:` | Tính năng mới |
| `fix:` | Sửa bug |
| `docs:` | Tài liệu |
| `chore:` | Config, deps |
| `data:` | DB seed/export |
| `refactor:` | Tái cấu trúc code |

### 4.3 GitHub Actions (`.github/workflows/`)

- **deploy job**: Tự động deploy khi push `main`
- **npm-publish job**: Publish `@nlp-labs-vietnam/investment-ui` lên npmjs

---

## 5. AGENT SKILLS — Danh sách & Mô tả

### 5.1 Skills đã tạo (`agent-skills/`)

| Skill | File | Lĩnh vực |
|---|---|---|
| `solar-energy-advisory` | `agent-skills/solar-energy-advisory/SKILL.md` | Tư vấn điện mặt trời, EPC, BESS |
| `ev-charging-operations` | `agent-skills/ev-charging-operations/SKILL.md` | Vận hành trạm sạc EV, nhượng quyền |
| `esg-carbon-accounting` | `agent-skills/esg-carbon-accounting/SKILL.md` | ESG, tín chỉ carbon, Net Zero |
| `grant-pitching-partnerships` | `agent-skills/grant-pitching-partnerships/SKILL.md` | Pitch đối tác, gọi vốn, đầu tư |

### 5.2 Chatbot AI / CS Hub

| Tool | Path | Mô tả |
|---|---|---|
| B2B CS Hub | `public/chatbot-ai/nlp_b2b_cs_assistant.html` | 28 kịch bản, 7 nhóm — route `/cs-hub` |
| B2B CS Source | `public/chatbot-ai/nlp_b2b_cs_assistant.tsx` | React component source |

### 5.3 Skills cần tạo (tiếp theo)

| Skill | Mô tả | Ưu tiên |
|---|---|---|
| `nlp-crm-lead` | Xử lý lead form, phân loại khách hàng | 🟡 |
| `nlp-content-writer` | Viết blog EPC/Solar/ESG chuẩn SEO | 🟡 |
| `nlp-seo-audit` | Kiểm tra SEO, Core Web Vitals | 🟢 |
| `nlp-chatbot-widget` | Widget chatbot tích hợp website | 🔴 |

---

## 6. PLAN — Kế hoạch triển khai tiếp theo

### 🔴 Ưu tiên cao (Sprint hiện tại)

| # | Việc cần làm | File/Tool |
|---|---|---|
| 1 | Điền `MAILERSEND_API_KEY` thực tế | `.env` |
| 2 | Cập nhật `MAIL_HOST` SMTP thực (Zoho/Google Workspace) | `.env` |
| 3 | Test gửi email: `php artisan tinker` → `Mail::to(...)` | CLI |
| 4 | Upload ảnh thực tế thay 4 SVG placeholder card hệ sinh thái | Admin panel |
| 5 | Thiết lập auto-backup DB hàng ngày → S3/AWS RDS | `backup-db.sh` |

### 🟡 Ưu tiên trung bình (Sprint tiếp theo)

| # | Việc cần làm | Ghi chú |
|---|---|---|
| 6 | Blog: viết thêm bài cho Năng lượng, Công nghệ, AI, ESG | thumbnail thực tế |
| 7 | NLP AI Chatbot widget tích hợp website | `nlp-chatbot-widget` skill |
| 8 | Form tư vấn chuyên biệt theo lĩnh vực + email notification | `/contact` page |
| 9 | Log rotation (`daily` channel) | `config/logging.php` |
| 10 | Monitor uptime (UptimeRobot hoặc tương đương) | External service |

### 🟢 Ưu tiên thấp

| # | Việc cần làm | Ghi chú |
|---|---|---|
| 11 | Image optimization (WebP, lazy load) | `public/assets/` |
| 12 | CDN cho static assets | Cloudflare / DigitalOcean Spaces |
| 13 | Vue 2 → Alpine.js/Vue 3 migration | `docs/VUE_MIGRATION_PLAN.md` |
| 14 | Core Web Vitals audit: LCP < 2.5s, FID < 100ms, CLS < 0.1 | PageSpeed Insights |
| 15 | Rate limiting form liên hệ | Laravel Throttle middleware |

---

## 7. SEO — Metadata đã chuẩn hóa (NLP Group)

### Bảng pages đã seed

| id | slug | page_title | meta_title |
|---|---|---|---|
| 1 | `/` | Trang Chủ | NLP Group \| Tổng Thầu EPC & Nhà Phân Phối Điện Mặt Trời Uy Tín |
| 4 | `contact` | Liên Hệ | Liên Hệ NLP Group \| Tư Vấn Kỹ Thuật & Báo Giá Điện Mặt Trời |
| 6 | `privacy-policy` | Chính Sách Bảo Mật | Chính Sách Bảo Mật \| NLP Group - Cam Kết Bảo Vệ Dữ Liệu |
| 7 | `terms-and-conditions` | Điều Khoản & Điều Kiện | Điều Khoản Sử Dụng & Dịch Vụ \| NLP Group |
| 19 | `about` | Về Chúng Tôi | Giới Thiệu NLP Group \| Đơn Vị Tổng Thầu EPC & Phân Phối Năng Lượng Uy Tín |
| 20 | `solutions` | Giải Pháp & Sản Phẩm | Thiết Bị & Giải Pháp EPC Điện Mặt Trời \| NLP Group |
| 21 | `faq` | Câu Hỏi Thường Gặp | Hỏi Đáp Về Điện Mặt Trời & Dịch Vụ EPC \| NLP Group |
| 22 | `blog` | Tin Tức & Kiến Thức | Bản Tin Năng Lượng Mặt Trời & Kiến Thức Kỹ Thuật \| NLP Group |

> **Lưu ý:** ID 18, 23, 24, 25 đã lược bỏ (trùng lặp). Slug `property` (ID 20) → `solutions`.

---

## 8. ARTISAN COMMANDS — Tham chiếu nhanh

```bash
# Seed pages + SEO
php artisan nlp:seed-pages
php artisan nlp:seed-seo
php artisan nlp:seed-products

# Seed blog content + tuyển dụng
php artisan nlp:seed-content
php artisan nlp:seed-content --force

# Generate sitemap
php artisan nlp:generate-sitemap

# Cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Queue
php artisan queue:work --queue=default
php artisan queue:restart
```

---

## 9. CRON JOBS — Production

```bash
# Laravel Scheduler (chạy mỗi phút)
* * * * * /usr/bin/php /var/www/investment-platform/artisan schedule:run >> /var/log/nlpgroup-scheduler.log 2>&1

# DB Backup (02:30 AM hàng ngày)
30 2 * * * /var/www/investment-platform/backup-db.sh >> /var/log/nlpgroup-db-backup.log 2>&1
```

**Schedule tasks trong `Kernel.php`:**
- `cache:warm` · `cache:prune`
- `UpdatePropertyJob`
- `payout-currency:update` · `payout-crypto-currency-update-cron`
- `nlp:generate-sitemap` (weekly Chủ nhật 04:00)
- `model:prune` · `badge:cron` · `main:cron`

---

## 10. LIÊN KẾT THAM CHIẾU

| Tài liệu | Mô tả |
|---|---|
| [`ROADMAP.md`](ROADMAP.md) | Roadmap đầy đủ theo giai đoạn |
| [`web-NLP-GROUP.md`](web-NLP-GROUP.md) | Sơ đồ UI tổng quan website NLP Group |
| [`docs/VUE_MIGRATION_PLAN.md`](docs/VUE_MIGRATION_PLAN.md) | Kế hoạch migrate Vue 2 → Alpine.js/Vue 3 |
| [`nlp03448_nlpgroup.sql`](nlp03448_nlpgroup.sql) | DB export mới nhất (492 KB) |
| [`config/database.php`](config/database.php) | DB connections: mysql local + mysql_aws + Redis |
| [`config/mail.php`](config/mail.php) | Mail failover chain |
| [`backup-db.sh`](backup-db.sh) | Script backup DB MySQL hàng ngày |
| [`app/Console/Kernel.php`](app/Console/Kernel.php) | Laravel Scheduler — schedule tasks |
| [`routes/web.php`](routes/web.php) | Toàn bộ routes Laravel |
| [`public/chatbot-ai/nlp_b2b_cs_assistant.html`](public/chatbot-ai/nlp_b2b_cs_assistant.html) | B2B CS Hub SOP |
| [`agent-skills/`](agent-skills/) | 4 Agent Skills: solar / EV / ESG / partnerships |
