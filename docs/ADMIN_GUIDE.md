# HƯỚNG DẪN QUẢN TRỊ VIÊN — NLP Group Investment Platform

> **Admin URL:** https://nlpgroup.vn/admin  
> **Email:** admin@nlpgroup.vn  
> **Hỗ trợ kỹ thuật:** dev@nlpgroup.vn  
> **Phiên bản:** 1.0 | Cập nhật: 10/2026

---

## MỤC LỤC

1. [Đăng nhập Admin](#1-đăng-nhập-admin)
2. [Dashboard tổng quan](#2-dashboard-tổng-quan)
3. [Quản lý dự án (Properties)](#3-quản-lý-dự-án-properties)
4. [Quản lý đầu tư](#4-quản-lý-đầu-tư)
5. [Quản lý người dùng](#5-quản-lý-người-dùng)
6. [Quản lý nạp tiền (Deposit)](#6-quản-lý-nạp-tiền-deposit)
7. [Quản lý rút tiền (Payout)](#7-quản-lý-rút-tiền-payout)
8. [Quản lý lịch trả lợi nhuận](#8-quản-lý-lịch-trả-lợi-nhuận)
9. [Quản lý nội dung website](#9-quản-lý-nội-dung-website)
10. [Cấu hình cơ bản](#10-cấu-hình-cơ-bản)
11. [Quản lý Blog & Tin tức](#11-quản-lý-blog--tin-tức)
12. [KYC — Xác minh danh tính](#12-kyc--xác-minh-danh-tính)
13. [Hỗ trợ & Ticket](#13-hỗ-trợ--ticket)
14. [Cài đặt email & SMS](#14-cài-đặt-email--sms)
15. [Cài đặt cổng thanh toán](#15-cài-đặt-cổng-thanh-toán)
16. [Quản lý menu & footer](#16-quản-lý-menu--footer)
17. [Bảo mật & Vận hành](#17-bảo-mật--vận-hành)
18. [Xử lý sự cố thường gặp](#18-xử-lý-sự-cố-thường-gặp)

---

## 1. Đăng nhập Admin

**URL:** https://nlpgroup.vn/admin

| Thông tin | Giá trị |
|---|---|
| Email | admin@nlpgroup.vn |
| Username | nlpadmin |
| URL Admin | https://nlpgroup.vn/admin |

> ⚠️ **Quan trọng:** Không chia sẻ thông tin đăng nhập admin. Bật 2FA ngay sau lần đăng nhập đầu tiên.

---

## 2. Dashboard tổng quan

**URL:** https://nlpgroup.vn/admin/dashboard

Màn hình dashboard hiển thị:

| Chỉ số | Mô tả |
|---|---|
| **Tổng người dùng** | Số tài khoản đã đăng ký |
| **Tổng nạp tiền** | Tổng tiền nạp (VNĐ) |
| **Tổng đầu tư** | Tổng vốn đang đầu tư |
| **Tổng lợi nhuận đã trả** | Tổng tiền đã hoàn cho nhà đầu tư |
| **Đầu tư đang chạy** | Số hợp đồng đầu tư đang hoạt động |
| **Đầu tư đến hạn** | Số hợp đồng cần xử lý thanh toán |
| **KYC chờ duyệt** | Số hồ sơ xác minh đang chờ |
| **Ticket hỗ trợ mở** | Số ticket chưa giải quyết |

### Biểu đồ thống kê:
- Biểu đồ nạp/rút theo tháng
- Biểu đồ người dùng mới
- Thống kê đầu tư theo giai đoạn

---

## 3. Quản lý dự án (Properties)

**URL:** https://nlpgroup.vn/admin/properties

### 3.1 Tạo dự án mới

1. Click **"Create Property"**
2. Điền thông tin:

| Trường | Mô tả | Ví dụ |
|---|---|---|
| **Tên dự án** | Tên hiển thị | "Trạm Sạc EV Quận 1 - HCM" |
| **Loại** | Loại dự án | EV Charging / Solar / ESG |
| **Giá trị dự án** | Tổng giá trị (VNĐ) | 500,000,000 |
| **Số tiền đầu tư tối thiểu** | Min per lần đầu tư | 1,000,000 |
| **Số tiền đầu tư tối đa** | Max per lần đầu tư | 100,000,000 |
| **Lợi nhuận (%)** | % lợi nhuận / kỳ | 2.5 |
| **Chu kỳ trả lợi nhuận** | Tần suất trả | Hàng tháng |
| **Tổng số kỳ** | Số lần trả | 12 |
| **Kỳ hạn đầu tư** | Thời gian khoá vốn | 12 tháng |
| **Phí trả trễ hạn** | % phí phạt | 0.5 |
| **Địa chỉ** | Vị trí dự án | Q.1, TP.HCM |
| **Mô tả** | Nội dung chi tiết | HTML content |
| **Hình ảnh** | Ảnh dự án | Upload JPG/PNG |
| **Trạng thái** | Active / Inactive | Active |

3. Click **"Save"**

### 3.2 Chỉnh sửa / Vô hiệu hoá dự án
- Danh sách → Click **"Edit"** để sửa
- Chuyển trạng thái **Inactive** để ẩn khỏi trang người dùng

### 3.3 Xem danh sách yêu thích
**URL:** https://nlpgroup.vn/admin/property/wishlist  
Danh sách người dùng đã lưu từng dự án.

---

## 4. Quản lý đầu tư

### 4.1 Đầu tư đang chạy
**URL:** https://nlpgroup.vn/admin/running-investments

- Xem toàn bộ hợp đồng đang hoạt động
- Tìm kiếm theo: tên dự án, người dùng, ngày
- Xem chi tiết từng hợp đồng

### 4.2 Đầu tư đến hạn trả lợi nhuận
**URL:** https://nlpgroup.vn/admin/due-investments

> ⚡ **Ưu tiên xử lý hàng ngày** — Danh sách hợp đồng đến kỳ cần trả lợi nhuận.

1. Kiểm tra danh sách mỗi ngày
2. Xác nhận trả lợi nhuận thủ công (nếu chưa tự động)
3. Hệ thống cộng vào số dư tài khoản người dùng

### 4.3 Đầu tư đã đáo hạn
**URL:** https://nlpgroup.vn/admin/expired-investments

- Hợp đồng đã hoàn thành toàn bộ kỳ hạn
- Kiểm tra vốn gốc đã được hoàn trả chưa

### 4.4 Đầu tư đã hoàn thành
**URL:** https://nlpgroup.vn/admin/completed-investments

Lịch sử tất cả đầu tư đã kết thúc.

### 4.5 Lịch trả lợi nhuận
**URL:** https://nlpgroup.vn/admin/profit-schedule

Toàn bộ lịch trả lợi nhuận theo từng ngày — dùng để kiểm tra và đối soát.

---

## 5. Quản lý người dùng

**URL:** https://nlpgroup.vn/admin/users

### 5.1 Danh sách người dùng
- Xem, tìm kiếm theo email / username / trạng thái
- Xem chi tiết: số dư, lịch sử đầu tư, KYC status

### 5.2 Tạo người dùng thủ công
**URL:** https://nlpgroup.vn/admin/users/add  
Tạo tài khoản thay mặt khách hàng (VD: nhập dữ liệu cũ).

### 5.3 Chỉnh sửa người dùng
1. Danh sách → Click **"Edit"** → sửa thông tin
2. Có thể: đổi email, cập nhật số dư thủ công, đặt lại mật khẩu

### 5.4 Khoá / Mở khoá tài khoản
- Trong trang chi tiết user → Click **"Block Profile"** để khoá
- Tài khoản bị khoá không thể đăng nhập / đầu tư

### 5.5 Xem lịch sử người dùng
- **Đầu tư:** `/admin/user/investments/{id}`
- **Nạp tiền:** `/admin/user/payment/{id}`
- **Rút tiền:** `/admin/user/payout/{id}`
- **Giao dịch:** `/admin/user/transaction/{id}`
- **Hoa hồng:** `/admin/user/commissions/{id}`

---

## 6. Quản lý nạp tiền (Deposit)

**URL:** https://nlpgroup.vn/admin/deposit/manual

### Xử lý nạp tiền thủ công (MB Bank / Bank Transfer):

1. Vào **Deposit → Manual Deposit**
2. Kiểm tra danh sách lệnh đang chờ
3. Đối chiếu với sao kê ngân hàng
4. Nếu hợp lệ → Click **"Approve"** → tiền vào tài khoản user
5. Nếu không hợp lệ → Click **"Reject"** → điền lý do từ chối

> ⏰ **Cam kết xử lý trong 1–4 giờ làm việc**

### Cổng thanh toán tự động (VietQR):
- Tự động xác nhận khi hệ thống nhận callback từ ngân hàng
- Kiểm tra log lỗi tại: `storage/logs/laravel.log`

---

## 7. Quản lý rút tiền (Payout)

**URL:** https://nlpgroup.vn/admin/payout/pending

### Quy trình xử lý:

1. **Kiểm tra yêu cầu rút tiền** đang chờ xử lý
2. Xác minh thông tin tài khoản ngân hàng người dùng
3. Thực hiện chuyển khoản thực tế
4. Quay lại admin → **Approve** lệnh rút (kèm mã giao dịch ngân hàng)

### Xem lịch sử rút tiền:
**URL:** https://nlpgroup.vn/admin/payout/request

---

## 8. Quản lý lịch trả lợi nhuận

**URL:** https://nlpgroup.vn/admin/profit-schedule

Hệ thống tự động trả lợi nhuận qua **Laravel Scheduler** (cron job chạy mỗi phút). Tuy nhiên admin cần:

1. Kiểm tra hàng ngày tại **Due Investments**
2. Đảm bảo cron job đang chạy:
   ```bash
   tail -20 /var/log/nlpgroup-scheduler.log
   ```
3. Nếu lợi nhuận chưa tự động trả → xử lý thủ công tại Due Investments

---

## 9. Quản lý nội dung website

### 9.1 Quản lý Pages (Trang tĩnh)
**URL:** https://nlpgroup.vn/admin/pages

Tạo và chỉnh sửa nội dung các trang:

| Slug | Trang |
|---|---|
| `/` | Trang chủ |
| `about` | Về NLP Group |
| `nlp-energy` | NLP Energy |
| `nlp-ev-charging` | NLP EV Charging |
| `nlp-technology` | NLP Technology |
| `nlp-ai-digital` | NLP AI & Digital |
| `nlp-esg-carbon` | NLP ESG & Carbon |
| `ecosystem` | Hệ sinh thái |
| `nlp-ev-products` | Sản phẩm EV |
| `nlp-energy-products` | Sản phẩm Energy |
| `tuyen-dung` | Tuyển Dụng |
| `solutions` | Giải Pháp |
| `faq` | Hỏi Đáp |
| `contact` | Liên Hệ |

**Cách tạo/sửa page:**
1. Click **"Create Page"** → điền Tên, Slug, Meta SEO
2. Trong tab **Content** → soạn nội dung bằng HTML editor
3. Trong tab **SEO** → điền `meta_title`, `meta_description`, `keywords`
4. **Save**

### 9.2 Quản lý Sections trang chủ
**URL:** https://nlpgroup.vn/admin/content

Chỉnh sửa từng section:
- **Hero** — tiêu đề, subtitle, ảnh nền, CTA button
- **Feature** — 4 card hệ sinh thái (ảnh + tiêu đề + mô tả)
- **About** — mô tả giới thiệu NLP Group
- **Statistics** — số liệu thống kê (150+ dự án, 12MW...)
- **Testimonial** — 5 đánh giá khách hàng
- **Social** — link mạng xã hội (FB/TW/LI/IG)
- **Contact** — thông tin liên hệ, địa chỉ, hotline, email

> ⚡ **Quan trọng:** Cập nhật thông tin liên hệ thực tế (địa chỉ, hotline, email) tại **Content → Contact**

### 9.3 Quản lý Menu Header & Footer
**URL:** https://nlpgroup.vn/admin/manage-menu

**Cấu trúc menu header hiện tại:**
```
Trang Chủ | Về NLP Group | Hệ Sinh Thái ▾ | Sản Phẩm NLP ▾ | Giải Pháp ▾ | Tin Tức | Liên Hệ
```

> ⚠️ Khi thêm item vào menu, phải dùng đúng **tên page** (trường `name` trong bảng pages),  
> không dùng slug. Ví dụ: `"Trạm Sạc Xe Điện"` thay vì `"nlp-ev-products"`.

**Cấu trúc menu footer:**
- Liên Kết Hữu Ích: home, about, solution, Blog, faq, contact, Tuyển Dụng
- Chính Sách Công Ty: Hệ Sinh Thái NLP, 5 đơn vị, Sản phẩm, privacy policy, terms

### 9.4 Quản lý Amenities (Tiện ích dự án)
**URL:** https://nlpgroup.vn/admin/amenities

Tạo các tag tiện ích gắn vào dự án (VD: OCPP 2.0.1, Bảo hành 24 tháng, Sạc nhanh DC...).

---

## 10. Cấu hình cơ bản

**URL:** https://nlpgroup.vn/admin/basic-control

### Các cài đặt quan trọng:

| Cài đặt | Vị trí | Giá trị hiện tại |
|---|---|---|
| **Tên website** | Basic Control → Site Title | NLP Group |
| **Đơn vị tiền tệ** | Basic Control → Currency | VNĐ (₫) |
| **Đăng ký mở** | Basic Control → Registration | Bật |
| **Bảo trì** | Basic Control → Maintenance | Tắt |
| **APP_DEBUG** | `.env` | ⚠️ Cần đặt = `false` |
| **Theme** | Basic Control → Theme | green |

> ⚠️ **Cần làm ngay:** Đặt `APP_DEBUG=false` trong file `.env` tại server:
> ```bash
> nano ./nlp-labs-vietnam/.env
> # Sửa: APP_DEBUG=false
> php artisan config:clear
> ```

### Cài đặt tỷ giá tiền tệ:
**URL:** https://nlpgroup.vn/admin/currency-exchange-rate  
Cập nhật tỷ giá thủ công hoặc bật tự động qua API.

---

## 11. Quản lý Blog & Tin tức

**URL:** https://nlpgroup.vn/admin/blogs

### Tạo bài viết mới:
1. Click **"Create Blog"**
2. Điền:
   - **Tiêu đề** — tiêu đề bài viết
   - **Danh mục** — chọn từ danh sách (tạo tại `/admin/blog-category`)
   - **Ảnh thumbnail** — upload ảnh đại diện (khuyến nghị 1200×630px)
   - **Nội dung** — soạn bằng editor
   - **Meta SEO** — title, description, keywords
3. **Publish** để đăng ngay hoặc **Draft** để lưu nháp

### Danh mục blog:
**URL:** https://nlpgroup.vn/admin/blog-category  
Tạo 6 danh mục: NLP Group · Năng lượng · Xe điện · Công nghệ · AI · ESG

> ⚡ **Việc cần làm:** Thay thế 6 bài blog placeholder (ảnh `default.png`, ngày 13/10/2026) bằng bài viết thực tế.

---

## 12. KYC — Xác minh danh tính

**URL:** https://nlpgroup.vn/admin/user/kyc-list

### Quy trình duyệt KYC:

1. Vào **KYC List** → tab **"Pending"**
2. Click vào hồ sơ cần xem xét
3. Kiểm tra:
   - Ảnh CCCD/Hộ chiếu (mặt trước + mặt sau)
   - Ảnh selfie cầm giấy tờ
   - Thông tin khớp với tài khoản
4. Quyết định:
   - ✅ **Approve** — xác minh thành công
   - ❌ **Reject** — từ chối, điền lý do để gửi lại cho user

### Trạng thái KYC:
- **Pending** — chờ duyệt
- **Approved** — đã xác minh
- **Rejected** — bị từ chối

---

## 13. Hỗ trợ & Ticket

**URL:** https://nlpgroup.vn/admin/tickets

### Xử lý ticket:
1. Vào **Ticket List** → lọc theo trạng thái **"Open"**
2. Click vào ticket → đọc nội dung
3. Phản hồi trực tiếp trong ticket
4. Đổi trạng thái → **"Closed"** khi đã giải quyết

### Mức độ ưu tiên:
- 🔴 Khẩn cấp: tài khoản bị khoá, tiền không vào, lỗi hệ thống
- 🟡 Thông thường: câu hỏi về đầu tư, KYC, rút tiền
- 🟢 Thấp: góp ý, câu hỏi chung

### Gửi email hàng loạt:
**URL:** https://nlpgroup.vn/admin/send-email  
Gửi thông báo đến toàn bộ người dùng (VD: ra mắt dự án mới, bảo trì hệ thống).

---

## 14. Cài đặt email & SMS

### Email:
**URL:** https://nlpgroup.vn/admin/email-control

Cấu hình SMTP để gửi email:

| Trường | Giá trị |
|---|---|
| Mailer | smtp |
| Host | smtp.gmail.com (hoặc SMTP của bạn) |
| Port | 587 |
| From Address | noreply@nlpgroup.vn |
| From Name | NLP Group |

> ⚠️ Hiện tại `MAIL_MAILER=log` — email chỉ ghi vào log, **không gửi thực tế**. Cần cấu hình SMTP thật.

### Template email:
**URL:** https://nlpgroup.vn/admin/email-templates  
Chỉnh sửa nội dung các email tự động: đăng ký, xác nhận đầu tư, trả lợi nhuận...

### SMS:
**URL:** https://nlpgroup.vn/admin/sms-controls  
Cấu hình SMS gateway nếu cần thông báo qua tin nhắn.

---

## 15. Cài đặt cổng thanh toán

**URL:** https://nlpgroup.vn/admin/payment-methods

### Cổng thanh toán đang hoạt động:

| Cổng | Trạng thái | Ghi chú |
|---|---|---|
| **NLP Group (MB Bank)** | ✅ Bật | Xử lý thủ công |
| **VietQR (Auto)** | ✅ Bật | Tự động qua API |
| **Bank Transfer** | ✅ Bật | Thủ công |
| Paypal, Stripe, v.v. | ❌ Tắt | Chưa cấu hình |

### Kích hoạt cổng thanh toán:
1. Click vào tên cổng → **Edit**
2. Điền API Key / Secret từ nhà cung cấp
3. Chuyển trạng thái → **Active**
4. Test thanh toán trước khi mở cho user

---

## 16. Quản lý menu & footer

> Xem chi tiết tại [Mục 9.3](#93-quản-lý-menu-header--footer)

### Lưu ý quan trọng khi chỉnh menu:
- Sau khi sửa menu trong Admin, **xoá cache** ngay:
  ```bash
  php artisan cache:clear
  ```
- Nếu link menu bị lỗi 404, kiểm tra `pages.name` trong DB có khớp với menu item không.

---

## 17. Bảo mật & Vận hành

### 17.1 Cron Job (Scheduler)
```
* * * * * /usr/bin/php ./nlp-labs-vietnam/artisan schedule:run >> /var/log/nlpgroup-scheduler.log 2>&1
```
**Kiểm tra log:**
```bash
tail -50 /var/log/nlpgroup-scheduler.log
```

### 17.2 Laravel Schedule tasks đang chạy:
| Task | Tần suất |
|---|---|
| `cache:warm` | Mỗi 30 phút |
| `main:cron` — xử lý đầu tư | Hàng ngày 0:00 |
| `badge:cron` — cập nhật rank | Hàng ngày 0:00 |
| `UpdatePropertyJob` | Hàng ngày 0:00 |
| `model:prune` — dọn dữ liệu cũ | Hàng ngày |
| `payout-currency:update` | Mỗi phút |

### 17.3 Backup Database
```bash
# Backup thủ công
mysqldump -u invest_user -pinvest_pass_2025 -h 127.0.0.1 investment_platform 2>/dev/null \
  > ./nlp-labs-vietnam/backup_$(date +%Y%m%d_%H%M%S).sql

# Backup tự động hàng ngày (thêm vào crontab)
0 2 * * * /bin/bash ./nlp-labs-vietnam/backup.sh >> /var/log/nlpgroup-backup.log 2>&1
```

### 17.4 Xem log lỗi ứng dụng
```bash
# Log Laravel realtime
tail -f ./nlp-labs-vietnam/storage/logs/laravel.log

# 50 dòng cuối
tail -50 ./nlp-labs-vietnam/storage/logs/laravel.log | grep -E "ERROR|CRITICAL"
```

### 17.5 Lệnh quản lý thường dùng
```bash
cd ./nlp-labs-vietnam

# Xoá toàn bộ cache
php artisan optimize:clear

# Cache lại config + routes (production)
php artisan config:cache && php artisan route:cache && php artisan view:cache

# Xem trạng thái ứng dụng
php artisan about

# Kiểm tra schedule
php artisan schedule:list

# Chạy migration mới
php artisan migrate --force

# Fix quyền storage
chown -R www-data:www-data storage/ bootstrap/cache/
chmod -R 775 storage/ bootstrap/cache/
```

### 17.6 Checklist bảo mật:
- [ ] `APP_DEBUG=false` trong `.env`
- [ ] `APP_ENV=production` trong `.env`
- [ ] Đổi mật khẩu admin mặc định
- [ ] Bật 2FA cho tài khoản admin
- [ ] SMTP thật thay vì `MAIL_MAILER=log`
- [ ] Backup DB tự động hàng ngày
- [ ] SSL đang hoạt động (HTTPS redirect 301 ✅)

---

## 18. Xử lý sự cố thường gặp

### ❌ Lỗi 500 Internal Server Error
```bash
tail -50 ./nlp-labs-vietnam/storage/logs/laravel.log
php artisan config:clear && php artisan cache:clear
```

### ❌ Trang trắng
```bash
# Kiểm tra APP_DEBUG
grep APP_DEBUG ./nlp-labs-vietnam/.env

# Bật debug tạm thời để xem lỗi
# APP_DEBUG=true (nhớ tắt lại sau khi fix)
```

### ❌ Schedule không chạy / lợi nhuận không tự trả
```bash
# Kiểm tra cron đang chạy
crontab -l | grep investment

# Kiểm tra log
tail -20 /var/log/nlpgroup-scheduler.log

# Chạy thủ công
php artisan schedule:run
php artisan main:cron
```

### ❌ Menu bị lỗi 404 sau khi sửa
```bash
php artisan cache:clear
# Kiểm tra pages.name trong DB khớp với menu_items
mysql -u invest_user -p investment_platform -e "SELECT name, slug FROM pages WHERE template_name='green';"
```

### ❌ Email không gửi được
```bash
# Kiểm tra cấu hình mail
grep MAIL ./nlp-labs-vietnam/.env

# Test gửi mail
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@test.com')->subject('Test'));
```

### ❌ Hình ảnh upload không hiển thị
```bash
# Tạo lại symlink storage
php artisan storage:link

# Kiểm tra quyền
ls -la ./nlp-labs-vietnam/storage/app/public/
```

### ❌ Lỗi "No application encryption key"
```bash
php artisan key:generate --force
php artisan config:clear
```

---

## THÔNG TIN KỸ THUẬT HỆ THỐNG

| Thông số | Giá trị |
|---|---|
| Framework | Laravel (PHP 8.2) |
| Database | MySQL 8.0 / MariaDB |
| Theme | green |
| Admin prefix | `/admin` |
| PHP path | `/usr/bin/php` |
| App path | `./nlp-labs-vietnam` |
| Log scheduler | `/var/log/nlpgroup-scheduler.log` |
| DB name | `investment_platform` |
| DB user | `invest_user` |
| DB AWS host | `lb-74243077-b2b179f24683edd1.elb.us-west-1.amazonaws.com:9030` |

---

*© 2026 NLP Group — nlpgroup.vn | dev@nlpgroup.vn*
