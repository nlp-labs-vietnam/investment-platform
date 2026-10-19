# TỔNG QUAN SƠ ĐỒ UI — nlpgroup.vn
> Kiểm tra thực tế: https://nlpgroup.vn | Cập nhật theo giao diện đang chạy

---

## 1. LAYOUT TỔNG THỂ

```
┌─────────────────────────────────────────────────────────┐
│  HEADER (sticky)                                        │
│  [Logo NLP Group]  Nav Menu                [Đăng Nhập] │
│  Trang Chủ | Về NLP Group | Hệ Sinh Thái ▾ |           │
│  Sản Phẩm NLP ▾ | Giải Pháp ▾ | Tin Tức | Liên Hệ     │
├─────────────────────────────────────────────────────────┤
│  MAIN CONTENT (xem theo từng trang bên dưới)            │
├─────────────────────────────────────────────────────────┤
│  FOOTER                                                 │
│  Logo | Mô tả | Mạng xã hội (FB/TW/LI/IG)             │
│  Liên Kết Hữu Ích | Chính Sách Công Ty | Bản Tin       │
│  © 2026 NLP Group | Tiếng Việt | English               │
└─────────────────────────────────────────────────────────┘
```

---

## 2. HEADER — Navigation chi tiết

```
[Logo]
  ├── Trang Chủ                → /
  ├── Về NLP Group             → /about
  ├── Hệ Sinh Thái NLP ▾
  │     ├── NLP Energy         → /nlp-energy
  │     ├── NLP EV Charging    → /nlp-ev-charging
  │     ├── NLP Technology     → /nlp-technology
  │     ├── NLP AI & Digital   → /nlp-ai-digital
  │     └── NLP ESG & Carbon   → /nlp-esg-carbon
  ├── Sản Phẩm NLP ▾
  │     ├── nlp-ev-products    → /nlp-ev-products
  │     └── nlp-energy-products→ /nlp-energy-products
  ├── Giải Pháp ▾
  │     ├── NLP Energy         → /nlp-energy
  │     ├── NLP EV Charging    → /nlp-ev-charging
  │     ├── NLP Technology     → /nlp-technology
  │     ├── NLP AI & Digital   → /nlp-ai-digital
  │     └── NLP ESG & Carbon   → /nlp-esg-carbon
  ├── Tin Tức                  → /blog
  ├── Liên Hệ                  → /contact
  └── [Đăng Nhập]              → /login
```

---

## 3. TRANG CHỦ (/) — Các Section theo thứ tự

```
┌─────────────────────────────────────────────────────────┐
│  SECTION 1 — HERO BANNER                               │
│  Tiêu đề: "NLP GROUP"                                  │
│  Subtitle: "Kiến tạo hệ sinh thái đổi mới bền vững"   │
│  Ảnh minh họa (webp)                                   │
│  Stats: 150+ Dự án | 12MW Công suất | ₫8400 tấn CO₂   │
│  CTA: [Khám phá dự án →] → /property                  │
├─────────────────────────────────────────────────────────┤
│  SECTION 2 — TẠI SAO CHỌN NLP GROUP?                  │
│  Tiêu đề + mô tả tổng quan                             │
│  Card Grid (4 cột):                                     │
│  [NLP Energy] [NLP EV Charging] [NLP Technology] [NLP ESG & Carbon]
│  Mỗi card: ảnh + tên + mô tả ngắn + [Tìm hiểu thêm]  │
│  CTA: [Xem toàn bộ hệ sinh thái NLP] → /ecosystem     │
├─────────────────────────────────────────────────────────┤
│  SECTION 3 — BANNER SLOGAN                             │
│  "Công nghệ tạo giá trị. Năng lượng tạo tương lai."   │
│  "Bền vững tạo thịnh vượng — NLP Group"                │
├─────────────────────────────────────────────────────────┤
│  SECTION 4 — VỀ NLP GROUP (giới thiệu ngắn)           │
│  Badge: "về nlp group"                                  │
│  Tiêu đề + 5 lĩnh vực (Energy/EV/Tech/AI/ESG)         │
│  Mô tả + CTA: [Tìm hiểu thêm về NLP Group] → /about  │
├─────────────────────────────────────────────────────────┤
│  SECTION 5 — DỰ ÁN NỔI BẬT                            │
│  Badge: "Dự án nổi bật"                                 │
│  Tiêu đề: "Danh mục dự án đầu tư NLP Group"           │
│  Listing dự án (từ DB): mỗi item hiển thị:             │
│  - Vốn Khả Dụng | Số Tiền Đầu Tư | Hoàn Lợi Nhuận    │
│  - Chu Kỳ | Tổng Đợt | Thời Hạn | Phí Trễ | Kỳ Hạn  │
│  Modal đăng nhập nếu chưa auth                         │
│  CTA: [Xem Tất Cả Trạm Sạc] → /property               │
├─────────────────────────────────────────────────────────┤
│  SECTION 6 — TESTIMONIALS (Đối tác & Khách hàng)      │
│  Badge: "đối tác & khách hàng"                          │
│  Tiêu đề: "Họ nói gì về NLP Group?"                   │
│  Slider/carousel — 5 review cards:                     │
│  avatar + quote + tên + chức danh + thành phố          │
├─────────────────────────────────────────────────────────┤
│  SECTION 7 — DỰ ÁN MỚI NHẤT                           │
│  Badge: "Dự án mới nhất"                               │
│  Tiêu đề: "Cơ hội đầu tư đang mở"                     │
│  Grid dự án mới (tương tự Section 5)                   │
│  CTA: [Xem Tất Cả Trạm Sạc] → /property               │
├─────────────────────────────────────────────────────────┤
│  SECTION 8 — QUY TRÌNH TRIỂN KHAI                      │
│  Badge: "Quy trình triển khai dự án"                   │
│  Tiêu đề: "Bạn đầu tư. NLP Group lo phần còn lại."    │
│  4 bước dạng numbered card:                            │
│  1. Khảo sát & Tư vấn → 2. Thiết kế & Báo giá        │
│  3. Thi công & Lắp đặt → 4. Vận hành & Bảo trì       │
│  Map/Decorative image                                   │
├─────────────────────────────────────────────────────────┤
│  SECTION 9 — TIN TỨC MỚI NHẤT                         │
│  Badge: "tin tức & bài viết"                            │
│  Tiêu đề: "Cập nhật mới nhất từ NLP Group"            │
│  Grid 6 bài (2 hàng × 3 cột):                         │
│  thumbnail + tiêu đề + tác giả + ngày                  │
│  Link → /blog-details/{id}?slug                        │
└─────────────────────────────────────────────────────────┘
```

---

## 4. CÁC TRANG CON — Route Map

```
/                           → Trang Chủ
/about                      → Về NLP Group
/property                   → Danh sách dự án / trạm sạc
/blog                       → Tin tức
/blog-details/{id}?{slug}   → Chi tiết bài viết
/contact                    → Liên hệ
/faq                        → Hỏi Đáp
/ecosystem                  → Hệ sinh thái tổng quan
/nlp-energy                 → NLP Energy
/nlp-ev-charging            → NLP EV Charging
/nlp-technology             → NLP Technology
/nlp-ai-digital             → NLP AI & Digital
/nlp-esg-carbon             → NLP ESG & Carbon
/nlp-ev-products            → Sản phẩm EV
/nlp-energy-products        → Sản phẩm Energy
/privacy-policy             → Chính Sách Bảo Mật
/terms-and-conditions       → Điều Khoản
/login                      → Đăng Nhập
/register                   → Đăng Ký
/language/vi                → Chuyển Tiếng Việt
/language/en                → Chuyển English
/tuyen-dung                 → Tuyển Dụng
```

---

## 5. KHU VỰC USER (đã đăng nhập) — /user/*

```
/user/dashboard                         → Dashboard tổng quan
/user/profile/my_profile                → Hồ sơ cá nhân
/user/profile/password-setting          → Đổi mật khẩu
/user/twofa                             → Xác thực 2 lớp
/user/verification-center/kyc          → KYC / Xác minh danh tính
/user/transactions/invest-log           → Lịch sử đầu tư
/user/transactions/invest-details/{id} → Chi tiết giao dịch
/user/payment/nlpgroup                  → Thanh toán NLP Group
/user/payment/vietqr                    → Thanh toán VietQR
/user/property                          → Quản lý bất động sản / dự án
/user/property/my-offer                 → Đề xuất của tôi
/user/property/my-share                 → Chia sẻ của tôi
/user/property/offer-list               → Danh sách đề xuất
/user/property/offer-conversation       → Hội thoại đề xuất
/user/wishlist                          → Danh sách yêu thích
/user/referral                          → Chương trình giới thiệu
/user/support-ticket/view               → Hỗ trợ / Ticket
```

---

## 6. FOOTER — Cấu trúc

```
┌──────────────┬──────────────────┬──────────────────┬─────────────┐
│ Logo NLP     │ Liên Kết         │ Chính Sách       │ Bản Tin     │
│ Mô tả công ty│ Hữu Ích          │ Công Ty          │ Email sub   │
│ MST:         │ - Trang Chủ      │ - Hệ Sinh Thái   │             │
│ 0316942991   │ - Về NLP Group   │ - NLP Energy     │             │
│ Vốn ĐL:      │ - Giải Pháp      │ - NLP EV Charging│             │
│ 1.172,5 tỷ  │ - Tin Tức        │ - nlp-ev-products│             │
│              │ - Hỏi Đáp       │ - nlp-energy-prod│             │
│ [FB][TW]     │ - Liên Hệ       │ - NLP Technology │             │
│ [LI][IG]     │ - tuyen-dung    │ - NLP AI & Digital│             │
│              │                  │ - NLP ESG & Carbon│            │
│              │                  │ - Bảo Mật        │             │
│              │                  │ - Điều Khoản     │             │
└──────────────┴──────────────────┴──────────────────┴─────────────┘
© 2026 NLP Group  |  Tiếng Việt  |  English
```

---

## 7. KIỂM TRA THỰC TẾ — Trạng thái các tính năng

| Trang / Tính năng | Trạng thái | Ghi chú |
|---|---|---|
| Trang chủ `/` | ✅ Hoạt động | Hero, sections đầy đủ |
| Nav dropdown Hệ Sinh Thái | ✅ Hoạt động | 5 sub-items |
| Section dự án nổi bật | ✅ Hoạt động | Lấy từ DB, modal đăng nhập |
| Testimonials slider | ✅ Hoạt động | 5 reviews |
| Section tin tức | ✅ Hoạt động | 6 bài mới nhất |
| `/property` | ✅ Hoạt động | Danh sách trạm sạc/dự án |
| `/blog` | ✅ Hoạt động | Listing bài viết |
| `/login` & `/register` | ✅ Hoạt động | Auth pages |
| Dropdown `Sản Phẩm NLP` | ✅ Đã fix | `"Trạm Sạc Xe Điện"` → `/nlp-ev-products`, `"Thiết Bị Điện Mặt Trời"` → `/nlp-energy-products` |
| Footer `tuyen-dung` | ✅ Đã fix | `"Tuyển Dụng"` → `/tuyen-dung` (route + page khớp) |
| `/ecosystem` | ✅ Hoạt động | Route + page `slug=ecosystem` đã có, `FrontendController@page` |
| Social links FB/TW/LI/IG | ✅ Đã fix | FB: `/nlpgroup.vn` · TW: `/nlpgroup_vn` · LI: `/company/nlp-group-vn` · IG: `/nlpgroup.vn` |
| NLP AI & Digital | ✅ Đã fix | Đã thêm vào dropdown `Sản Phẩm NLP` trong header menu |

---

## 8. THỐNG KÊ TRANG CHỦ

| Chỉ số | Giá trị |
|---|---|
| Số sections trang chủ | 9 |
| Số mục menu chính | 6 (+ nút đăng nhập) |
| Số sub-menu items | 10 |
| Số bài tin tức hiển thị | 6 |
| Số review hiển thị | 5 |
| Số bước quy trình | 4 |
| Ngôn ngữ hỗ trợ | 2 (VI / EN) |
| MST | 0316942991 |
| Vốn điều lệ | 1.172,5 tỷ VNĐ |

---

## 9. CẤU TRÚC ĐẦY ĐỦ WEBSITE (site map)

```
nlpgroup.vn
│
├── / (Trang Chủ)
│   ├── Hero Banner + Stats
│   ├── Hệ Sinh Thái (4 cards)
│   ├── Giới thiệu công ty
│   ├── Dự án nổi bật (DB)
│   ├── Testimonials
│   ├── Dự án mới nhất (DB)
│   ├── Quy trình 4 bước
│   └── Tin tức mới (6 bài)
│
├── /about (Về NLP Group)
│   ├── Giới thiệu
│   ├── Thông điệp Chủ tịch
│   ├── Tầm nhìn / Sứ mệnh
│   ├── Giá trị cốt lõi
│   ├── Cơ cấu tổ chức
│   ├── Hồ sơ năng lực
│   └── Văn hóa doanh nghiệp
│
├── /ecosystem (Hệ Sinh Thái)
│
├── Hệ Sinh Thái NLP (5 pages)
│   ├── /nlp-energy
│   ├── /nlp-ev-charging
│   ├── /nlp-technology
│   ├── /nlp-ai-digital
│   └── /nlp-esg-carbon
│
├── Sản Phẩm (2 pages)
│   ├── /nlp-ev-products
│   └── /nlp-energy-products
│
├── /property (Dự án đầu tư)
│   └── /property/{id} (Chi tiết)
│
├── /blog (Tin tức)
│   └── /blog-details/{id}?{slug}
│
├── /contact
├── /faq
├── /privacy-policy
├── /terms-and-conditions
├── /tuyen-dung
│
└── Khu vực User (auth required)
    ├── /login  /register
    ├── /user/dashboard
    ├── /user/profile/*
    ├── /user/transactions/*
    ├── /user/payment/*
    ├── /user/property/*
    ├── /user/wishlist
    ├── /user/referral
    ├── /user/support-ticket/*
    ├── /user/twofa
    └── /user/verification-center/*
```

---

*Kiểm tra thực tế tại: https://nlpgroup.vn — Ngôn ngữ: VI/EN*
*App API: https://api.nlpgroup.vn*
