---
name: solar-energy-advisory
description: Tư vấn kỹ thuật và giải pháp điện mặt trời, BESS, EMS cho NLP Energy. Kích hoạt khi người dùng hỏi về điện mặt trời, công suất hệ thống, thời gian hoàn vốn, pin lưu trữ, EMS, EPC hoặc báo giá năng lượng mặt trời.
---

# Solar Energy Advisory — NLP Energy Agent Skill

## Mục đích / Purpose

Kỹ năng này giúp tư vấn kỹ thuật hệ thống điện mặt trời (Solar PV), pin lưu trữ năng lượng (BESS), và hệ thống quản lý năng lượng (EMS) cho khách hàng cá nhân, hộ kinh doanh và doanh nghiệp tại Việt Nam.

This skill provides technical advisory for solar PV systems, battery energy storage (BESS), and energy management systems (EMS) for residential, commercial, and industrial customers in Vietnam.

---

## 1. Thu thập thông tin đầu vào / Input Gathering

Trước khi tính toán, hãy hỏi khách hàng các thông tin sau:

**Bắt buộc:**
- **Tiêu thụ điện hàng tháng (kWh/tháng):** Lấy từ hóa đơn điện EVN, hoặc ước tính từ thiết bị sử dụng
- **Diện tích mái nhà (m²):** Mái bằng, mái nghiêng, hay mái tôn — có bị bóng che không?
- **Ngân sách dự kiến (VND):** Để xác định quy mô hệ thống phù hợp
- **Loại khách hàng:** Dân dụng (Residential) / Thương mại (Commercial) / Công nghiệp (Industrial)

**Tùy chọn:**
- Khu vực địa lý (miền Bắc / Trung / Nam) — ảnh hưởng đến số giờ nắng
- Hệ thống điện hiện tại: công tơ 1 chiều hay 2 chiều (net metering)?
- Mục tiêu: giảm hóa đơn điện / dự phòng điện / bán điện dư?

---

## 2. Công thức tính toán hệ thống / System Sizing Formulas

### 2.1 Công suất hệ thống (kWp)

```
Công suất (kWp) = Tiêu thụ tháng (kWh) / (Số giờ nắng đỉnh × 30 ngày × Hệ số hiệu suất 0.8)
```

**Ví dụ:** Gia đình dùng 500 kWh/tháng ở TP.HCM (4.5 giờ nắng đỉnh):
- kWp = 500 / (4.5 × 30 × 0.8) = 500 / 108 ≈ **4.6 kWp**

### 2.2 Sản lượng điện ước tính (kWh/tháng)

```
Sản lượng (kWh/tháng) = kWp × Số giờ nắng đỉnh × 30 × 0.8
```

### 2.3 CO₂ tiết kiệm hàng năm

```
CO₂ giảm (tCO₂/năm) = Sản lượng năm (kWh) × 0.4768 / 1000
```
Nguồn hệ số phát thải: Bộ Công Thương Việt Nam — MOIT Emission Factor 2022: **0.4768 tCO₂/MWh**

---

## 3. Số giờ nắng đỉnh theo vùng / Peak Sun Hours by Region

| Khu vực | Tỉnh/Thành tiêu biểu | Giờ nắng đỉnh (PSH) |
|---------|---------------------|----------------------|
| **Miền Bắc** | Hà Nội, Hải Phòng, Quảng Ninh | 3.5 – 4.0 h/ngày |
| **Miền Trung** | Đà Nẵng, Huế, Nha Trang, Bình Định | 4.5 – 5.0 h/ngày |
| **Miền Nam** | TP.HCM, Bình Dương, Đồng Nai, Cần Thơ | 4.5 – 5.5 h/ngày |
| **Tây Nguyên** | Đắk Lắk, Lâm Đồng, Gia Lai | 5.0 – 5.5 h/ngày |

**Lưu ý:** Sử dụng giá trị trung bình cả năm. Miền Bắc có mùa đông ít nắng nên tính toán thận trọng hơn.

---

## 4. Tính toán ROI và hoàn vốn / ROI Calculation

### 4.1 Chi phí đầu tư

| Loại hệ thống | Chi phí ước tính |
|---------------|-----------------|
| Dân dụng (3–10 kWp) | 14,000,000 – 16,000,000 VND/kWp |
| Thương mại (10–100 kWp) | 12,000,000 – 14,000,000 VND/kWp |
| Công nghiệp (>100 kWp) | 10,000,000 – 12,000,000 VND/kWp |

```
Tổng chi phí (VND) = Chi phí/kWp × Số kWp
```

### 4.2 Tiết kiệm hàng tháng

```
Tiết kiệm (VND/tháng) = Sản lượng tháng (kWh) × Giá điện bình quân (VND/kWh)
```

Giá điện tham chiếu (EVN 2024): ~2,800 – 3,500 VND/kWh (tùy bậc thang và loại khách hàng)

### 4.3 Thời gian hoàn vốn

```
Hoàn vốn (tháng) = Tổng chi phí / Tiết kiệm tháng
```

**Kỳ vọng:**
- Miền Nam / Trung: **4 – 6 năm** (48–72 tháng)
- Miền Bắc: **6 – 8 năm** (72–96 tháng)

---

## 5. Khuyến nghị BESS (Pin lưu trữ) / BESS Recommendation

**Khi nào nên dùng BESS:**
- Tỷ lệ tự tiêu thụ (self-consumption) < 40% — điện dư nhiều ban ngày nhưng không dùng được
- Lưới điện không ổn định, hay mất điện (vùng nông thôn, khu công nghiệp có phụ tải không đều)
- Doanh nghiệp muốn độc lập hoàn toàn hoặc dự phòng điện quan trọng (nhà máy, bệnh viện, data center)
- Muốn tối ưu giờ cao điểm / thấp điểm (time-of-use arbitrage)

**Công thức chọn dung lượng BESS:**
```
BESS (kWh) = Nhu cầu dự phòng (kWh) × Số giờ cần dự phòng / Hiệu suất pin (0.9)
```

**Loại pin khuyến nghị:** LiFePO₄ (Lithium Iron Phosphate)
- Tuổi thọ: > 6,000 chu kỳ (khoảng 15–20 năm)
- An toàn cháy nổ cao
- Phù hợp khí hậu nhiệt đới Việt Nam

---

## 6. Quy trình EPC / EPC Process Steps

1. **Khảo sát hiện trường** — Kiểm tra mái, đo bức xạ, đánh giá kết cấu
2. **Thiết kế kỹ thuật** — Phần mềm PVsyst, bản vẽ AutoCAD, single line diagram
3. **Báo giá & Hợp đồng** — Chi tiết vật tư, tiến độ, điều khoản bảo hành
4. **Cung cấp thiết bị** — Tấm pin Tier 1, inverter Sungrow/Huawei, mounting, cáp
5. **Thi công lắp đặt** — Theo tiêu chuẩn IEC 60364, TCVN về điện mặt trời
6. **Commissioning & Nghiệm thu** — Đo thông số, test tải, bàn giao hồ sơ
7. **Đăng ký đấu nối EVN** — Hỗ trợ thủ tục đăng ký đấu nối lưới nếu cần
8. **O&M sau lắp đặt** — Giám sát cloud 24/7, bảo trì định kỳ

---

## 7. Định dạng đầu ra / Output Format

Khi đã có đủ thông tin, trả lời theo mẫu sau:

```
═══════════════════════════════════════
📊 BÁO CÁO TƯ VẤN HỆ THỐNG ĐIỆN MẶT TRỜI
═══════════════════════════════════════
Khách hàng: [Loại hình]
Khu vực: [Tỉnh/Thành]

🔆 THÔNG SỐ HỆ THỐNG
  Công suất đề xuất: [X] kWp
  Sản lượng tháng: ~[Y] kWh/tháng
  Sản lượng năm:   ~[Z] kWh/năm

💰 TÀI CHÍNH
  Đầu tư ước tính: [VND]
  Tiết kiệm/tháng: [VND]
  Thời gian hoàn vốn: [X] tháng (~[Y] năm)

🌱 MÔI TRƯỜNG
  CO₂ tiết kiệm/năm: [X] tCO₂
  Tương đương trồng: [Y] cây xanh

🔋 KHUYẾN NGHỊ BESS
  [Có / Không cần — lý do]
  [Dung lượng nếu cần: X kWh]

⚙️ THIẾT BỊ ĐỀ XUẤT
  Tấm pin: [Hãng, công nghệ, công suất/tấm]
  Inverter: [Hãng, model]
  Mounting: [Loại]

📞 LIÊN HỆ NLP ENERGY
  Website: nlpgroup.vn
  Để được khảo sát & báo giá chính xác: Liên hệ tư vấn miễn phí
═══════════════════════════════════════
```

---

## 8. Lưu ý quan trọng / Important Notes

- Mọi tính toán là **ước tính sơ bộ** — số liệu chính xác cần khảo sát thực tế
- Thông số tấm pin và inverter thay đổi theo thị trường — cần xác nhận với NLP Energy
- Chính sách mua bán điện dư (net metering) phụ thuộc quy định EVN hiện hành
- Hệ thống BESS không được đấu nối bán điện lên lưới ở một số địa phương — cần xác nhận
- Liên hệ **nlpgroup.vn** để được tư vấn chuyên sâu và báo giá chính thức từ đội ngũ kỹ sư NLP Energy

---

## 9. Từ khóa kích hoạt / Trigger Keywords

Kỹ năng này được kích hoạt khi người dùng nhắc đến:
`điện mặt trời`, `solar`, `pin năng lượng mặt trời`, `tấm pin`, `inverter`, `kWp`, `BESS`, `lưu trữ điện`, `EMS`, `EPC solar`, `thời gian hoàn vốn điện mặt trời`, `tư vấn lắp điện mặt trời`, `báo giá điện mặt trời`, `công suất hệ thống solar`, `ROI điện mặt trời`, `off-grid`, `on-grid`, `hybrid solar`
