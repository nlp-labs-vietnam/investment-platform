# NLP Group Investment Platform

> **Laravel 11 · PHP 8.2 · MySQL · Vite · Bootstrap 5**  
> Nền tảng đầu tư năng lượng xanh & bất động sản — [@nlp-labs-vietnam](https://github.com/nlp-labs-vietnam)

[![CI](https://github.com/nlp-labs-vietnam/investment-platform/actions/workflows/ci.yml/badge.svg)](https://github.com/nlp-labs-vietnam/investment-platform/actions/workflows/ci.yml)
[![License](https://img.shields.io/badge/license-UNLICENSED-red.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel)](https://laravel.com)

---

## Yêu cầu

| Công cụ | Phiên bản tối thiểu |
|---------|---------------------|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 20+ |
| npm | 10+ |
| MySQL | 8.0+ |

---

## Cài đặt

### 1. Clone repo

```bash
git clone https://github.com/nlp-labs-vietnam/investment-platform.git
cd investment-platform
```

### 2. Cài PHP dependencies

```bash
composer install
```

### 3. Cài Node dependencies & build assets

```bash
npm install
npm run build
```

### 4. Cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

Chỉnh sửa `.env` — cập nhật các giá trị sau:

```ini
APP_URL=https://your-domain.com

DB_HOST=127.0.0.1
DB_DATABASE=investment_platform
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 5. Tạo database & chạy migration

```bash
php artisan migrate
php artisan db:seed          # Tuỳ chọn: dữ liệu mẫu
php artisan storage:link
```

### 6. Khởi động server (development)

```bash
php artisan serve
npm run dev     # Chạy song song trong terminal khác
```

Truy cập: **http://localhost:8000**

---

## Lệnh thường dùng

```bash
# Xoá cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Cache cho production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Chạy queue worker
php artisan queue:work

# Chạy tests
php artisan test
php artisan test --coverage
```

---

## Cấu trúc thư mục

```
app/                ← Application code (Controllers, Models, Services)
bootstrap/          ← Framework bootstrap & cache
config/             ← Configuration files
database/           ← Migrations, Factories, Seeders
deploy/             ← Infrastructure config (Nginx)
docs/               ← Tài liệu kỹ thuật & hướng dẫn deploy
packages/           ← Shared TypeScript packages (sdk, types, utils)
public/             ← Web root — assets build output
resources/          ← Views (Blade), SCSS, JS source
routes/             ← Route definitions (web, admin, api)
scripts/            ← Shell scripts (install, backup, deploy)
storage/            ← Logs, cache, uploads, backups
tests/              ← PHPUnit test suites (Unit & Feature)
```

---

## Kiểm thử

```bash
# Chạy toàn bộ test suite
php artisan test

# Chạy với coverage report
php artisan test --coverage --min=60

# Chạy riêng Unit hoặc Feature
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
```

---

## Deploy

Xem hướng dẫn chi tiết tại [`docs/deploy_guide.html`](docs/deploy_guide.html):

- **VPS / Server (root)** — Nginx + PHP-FPM + MySQL
- **VPS / Server (sudo user)** — Tương tự, dùng `sudo`
- **Shared Hosting / cPanel** — Apache + `.htaccess`

Script cài đặt tự động:

```bash
sudo bash scripts/install.sh
```

---

## Tài liệu

| Tài liệu | Mô tả |
|----------|-------|
| [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md) | Kiến trúc hệ thống & monorepo |
| [`docs/ROADMAP.md`](docs/ROADMAP.md) | Lộ trình phát triển |
| [`docs/deploy_guide.html`](docs/deploy_guide.html) | Hướng dẫn deploy đầy đủ |
| [`CONTRIBUTING.md`](CONTRIBUTING.md) | Quy trình đóng góp |
| [`CHANGELOG.md`](CHANGELOG.md) | Lịch sử thay đổi |

---

## Bảo mật

Nếu phát hiện lỗ hổng bảo mật, vui lòng **không** tạo public issue.  
Liên hệ trực tiếp: **dev@nlpgroup.vn**

---

## Giấy phép

Proprietary — © 2025 [NLP Group JSC](https://nlpgroup.vn). All rights reserved.
