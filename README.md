# NLP Group — Investment Platform

> Nền tảng đầu tư năng lượng tái tạo và bất động sản phân đoạn (Fractional Real Estate & Solar EPC Investment Platform) được xây dựng trên **Laravel 11**.

---

## ✨ Tính năng chính

- **Đầu tư phân đoạn** — mua cổ phần dự án năng lượng mặt trời / bất động sản theo từng phần nhỏ
- **37+ cổng thanh toán** — Stripe, Razorpay, PayPal, Mollie, Flutterwave, Coingate, …
- **KYC & 2FA** — xác minh danh tính và bảo mật 2 lớp
- **Hệ thống giới thiệu & xếp hạng** — hoa hồng đa cấp, bảng xếp hạng nhà đầu tư
- **Thông báo đa kênh** — Email, SMS (Twilio/Vonage/Plivo/Infobip), Push (Firebase), In-app
- **Đa ngôn ngữ** — Tiếng Việt, Tiếng Anh, dễ mở rộng
- **Admin dashboard** — quản lý toàn diện người dùng, tài sản, tài chính, nội dung
- **REST API** — Sanctum token auth, dành cho mobile app (iOS/Android)

---

## 🛠 Yêu cầu hệ thống

| Thành phần | Phiên bản |
|---|---|
| PHP | >= 8.1 |
| Laravel | 11.x |
| MySQL | >= 8.0 |
| Node.js | >= 18.x |
| Composer | >= 2.x |

---

## 🚀 Cài đặt

```bash
# 1. Clone repo
git clone https://github.com/nlp-labs-vietnam/investment-platform.git
cd investment-platform

# 2. Cài PHP dependencies
composer install --no-dev --optimize-autoloader

# 3. Cài Node dependencies & build assets
npm install
npm run build

# 4. Cấu hình môi trường
cp .env.example .env
php artisan key:generate

# 5. Migrate & seed database
php artisan migrate --seed

# 6. Tạo symlink storage
php artisan storage:link
```

---

## ⚙️ Cấu hình môi trường

Sao chép `.env.example` thành `.env` và điền các giá trị:

```dotenv
APP_NAME="NLP Group"
APP_URL=https://nlpgroup.vn
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=investment_platform
DB_USERNAME=root
DB_PASSWORD=

# Cổng thanh toán (ví dụ Stripe)
STRIPE_KEY=pk_live_xxx
STRIPE_SECRET=sk_live_xxx

# Firebase Push Notifications
FIREBASE_SERVER_KEY=xxx

# SMS (Twilio)
TWILIO_SID=xxx
TWILIO_TOKEN=xxx
TWILIO_FROM=+1xxx
```

---

## 📂 Cấu trúc thư mục

```
├── app/
│   ├── Http/Controllers/    # Admin, API, User, Auth controllers
│   ├── Models/              # 51 Eloquent models
│   ├── Services/            # Business logic (37+ payment gateways, SMS, translate)
│   └── Helpers/             # Global helper functions
├── database/
│   ├── migrations/          # 66 migration files
│   └── seeders/             # 15 seeders
├── resources/
│   ├── js/                  # Frontend JS (Vite/ES modules)
│   ├── sass/                # SCSS styles (Bootstrap 5)
│   └── views/               # Blade templates (Admin + Frontend themes)
├── routes/
│   ├── web.php              # Web routes
│   ├── api.php              # REST API routes (Sanctum)
│   └── admin.php            # Admin panel routes
└── public/
    └── exports/             # Public downloadable files (IPA, APK, …)
```

---

## 📱 Mobile App (iOS / Android)

File IPA (iOS) có thể tải xuống tại:

```
https://nlpgroup.vn/exports/MB-Bank_Thường_ios.ipa
```

---

## 🧪 Chạy tests

```bash
php artisan test
# hoặc
./vendor/bin/phpunit
```

---

## 🤝 Đóng góp

1. Fork repo này
2. Tạo branch: `git checkout -b feature/ten-tinh-nang`
3. Commit: `git commit -m "feat: mô tả thay đổi"`
4. Push: `git push origin feature/ten-tinh-nang`
5. Tạo Pull Request

---

## 📄 Giấy phép

© 2024–2026 [NLP Group](https://nlpgroup.vn). All rights reserved.
