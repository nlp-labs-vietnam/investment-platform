---
name: ev-charging-operations
description: Tư vấn quy hoạch trạm sạc xe điện, tính toán chi phí AC/DC, mô hình hợp tác mặt bằng và tích hợp OCPP/CSMS. Kích hoạt khi người dùng hỏi về trạm sạc xe điện, trụ sạc AC, trụ sạc DC, nhượng quyền trạm sạc, OCPP, doanh thu trạm sạc.
---

# EV Charging Operations — NLP EV Charging Agent Skill

## Mục đích / Purpose

Kỹ năng này tư vấn quy hoạch trạm sạc xe điện (EV Charging Station), phân tích chi phí đầu tư AC/DC charger, mô hình hợp tác mặt bằng, tích hợp giao thức OCPP/CSMS và tính toán doanh thu cho nhà đầu tư và đối tác mặt bằng tại Việt Nam.

This skill advises on EV charging station planning, AC/DC charger investment cost analysis, site partnership models, OCPP/CSMS integration, and revenue calculation for investors and site partners in Vietnam.

---

## 1. Thu thập thông tin đầu vào / Input Gathering

Hỏi khách hàng các thông tin sau trước khi tư vấn:

**Bắt buộc:**
- **Loại địa điểm:** Chung cư / Tòa nhà văn phòng / Trung tâm thương mại / Bãi đỗ xe / Trạm dừng nghỉ cao tốc / Khách sạn / Khu công nghiệp
- **Diện tích bãi đỗ xe (m²) và số chỗ đỗ xe:** Để ước tính số lượng trụ sạc phù hợp
- **Công suất máy biến áp hiện có (kVA):** Quyết định số lượng và loại charger có thể lắp
- **Lưu lượng xe điện dự kiến (xe/ngày):** Để tính doanh thu tiềm năng
- **Vai trò của khách hàng:** Chủ mặt bằng / Nhà đầu tư / Đơn vị vận hành

**Tùy chọn:**
- Có muốn tích hợp ứng dụng quản lý/thanh toán không?
- Nguồn điện hiện tại: điện 1 pha hay 3 pha?
- Có kế hoạch lắp solar PV không (để sạc xanh)?

---

## 2. Phân loại và khuyến nghị trụ sạc / Charger Types

### 2.1 Sạc AC (Level 2 — Sạc chậm)

| Thông số | Chi tiết |
|----------|----------|
| **Công suất** | 7 kW – 22 kW (1 pha 7kW, 3 pha 11kW / 22kW) |
| **Thời gian sạc** | 4–8 giờ để sạc đầy xe điện phổ thông |
| **Chi phí thiết bị** | 15,000,000 – 40,000,000 VND/trụ |
| **Phù hợp** | Chung cư, bãi đỗ xe qua đêm, văn phòng, khách sạn |
| **Yêu cầu điện** | Tối thiểu 10 kVA/trụ (220V 1 pha) hoặc 15 kVA (3 pha) |
| **Chuẩn kết nối** | Type 2 (IEC 62196-2), CCS2, CHAdeMO (tùy model) |

**Khi nào dùng AC:**
- Khách hàng đỗ xe dài (>2 giờ) — chung cư, văn phòng, khách sạn
- Ngân sách đầu tư thấp
- Máy biến áp công suất nhỏ (<100 kVA)

### 2.2 Sạc DC (Level 3 — Sạc nhanh / Fast Charging)

| Thông số | Chi tiết |
|----------|----------|
| **Công suất** | 30 kW – 120 kW (và lên tới 350 kW cho highway) |
| **Thời gian sạc** | 20–60 phút sạc 80% |
| **Chi phí thiết bị** | 80,000,000 – 300,000,000 VND/trụ |
| **Phù hợp** | Trạm dừng nghỉ cao tốc, trung tâm thương mại, siêu thị, sân bay |
| **Yêu cầu điện** | 50–150 kVA/trụ (3 pha 380V) |
| **Chuẩn kết nối** | CCS2 + CHAdeMO + GB/T (multi-standard) |

**Khi nào dùng DC:**
- Địa điểm high-traffic — đậu xe ngắn (<1 giờ)
- Trạm sạc công cộng ven đường, cao tốc
- Cần thời gian sạc ngắn để phục vụ nhiều lượt xe/ngày

---

## 3. Mô hình doanh thu / Revenue Model

### 3.1 Giá sạc và margin

| Thông số | Giá trị |
|----------|---------|
| **Giá bán điện sạc** | 3,000 – 5,000 VND/kWh |
| **Giá mua điện lưới** | ~2,000 VND/kWh (trung bình thương mại/dịch vụ) |
| **Margin gộp** | ~1,000 – 3,000 VND/kWh |

```
Doanh thu ngày = Số trụ × kWh trung bình/trụ/ngày × Giá sạc (VND/kWh)
Chi phí điện   = Số trụ × kWh trung bình/trụ/ngày × Giá điện lưới
Lợi nhuận gộp  = Doanh thu ngày - Chi phí điện
```

**Ví dụ tính toán (5 trụ AC 7kW, 8h sạc/ngày mỗi trụ):**
- Sản lượng/ngày: 5 × 7 × 8 = 280 kWh
- Doanh thu: 280 × 4,000 = 1,120,000 VND/ngày
- Chi phí điện: 280 × 2,000 = 560,000 VND/ngày
- Lợi nhuận gộp: **560,000 VND/ngày ≈ 16,800,000 VND/tháng**

### 3.2 Mô hình chia sẻ doanh thu mặt bằng

| Bên | Tỷ lệ chia sẻ |
|-----|--------------|
| **Chủ mặt bằng (Landowner)** | 20% – 30% doanh thu thuần |
| **NLP EV (Tech & Ops)** | 70% – 80% doanh thu thuần |

**Điều khoản tiêu biểu:**
- Chủ mặt bằng cung cấp: diện tích, điện 3 pha, kết nối internet
- NLP EV đầu tư: thiết bị, lắp đặt, vận hành, bảo trì, ứng dụng thanh toán
- Hợp đồng: 3–5 năm với quyền ưu tiên gia hạn
- Thanh toán doanh thu: hàng tháng qua chuyển khoản

---

## 4. So sánh OCPP 1.6J vs 2.0.1

| Tiêu chí | OCPP 1.6J | OCPP 2.0.1 |
|----------|-----------|------------|
| **Phát hành** | 2015 | 2020 |
| **Giao thức** | WebSocket / JSON hoặc SOAP | WebSocket / JSON (bắt buộc) |
| **Bảo mật** | TLS tùy chọn | TLS/mTLS bắt buộc |
| **Smart Charging** | Profile cơ bản | Smart Charging nâng cao (ISO 15118) |
| **V2G Support** | Không | Hỗ trợ (V2G / Vehicle-to-Grid) |
| **Transaction** | Đơn giản | Transaction Event nâng cao |
| **Device Management** | Giới hạn | Device Model đầy đủ |
| **Firmware Update** | Cơ bản | Nâng cao (delta update) |
| **Tương thích phần cứng** | Rộng rãi (95% thiết bị) | Đang tăng (60-70% thiết bị mới) |
| **Khuyến nghị** | Thiết bị hiện tại, chi phí thấp | Dự án mới, tương lai |

**Khuyến nghị NLP EV:** Sử dụng OCPP 1.6J cho triển khai hiện tại, chuẩn bị nâng cấp lên 2.0.1 cho hệ thống V2G và smart grid sau 2025.

---

## 5. Tính năng CSMS (Central System Management Software)

CSMS (hay OCPP Backend) của NLP EV cung cấp:

**Quản lý thiết bị:**
- Dashboard giám sát trạng thái tất cả trụ sạc theo thời gian thực
- Cảnh báo sự cố tự động qua SMS/Email/Zalo
- Điều khiển từ xa: reboot, block/unblock connector, cập nhật firmware

**Quản lý người dùng & thanh toán:**
- Ứng dụng mobile (iOS/Android) cho người dùng cuối
- Thanh toán qua RFID card, QR code, ứng dụng di động
- Tích hợp cổng thanh toán: VNPay, MoMo, ZaloPay
- Hóa đơn điện tử tự động

**Quản lý năng lượng:**
- Load balancing — phân phối công suất thông minh giữa các trụ
- Lập lịch sạc theo giờ thấp điểm (off-peak scheduling)
- Báo cáo tiêu thụ điện chi tiết theo ngày/tháng/trụ

**Báo cáo & Phân tích:**
- Doanh thu theo trụ, theo địa điểm, theo thời gian
- Tỷ lệ sử dụng (utilization rate) và hiệu suất đầu tư
- Xuất báo cáo CSV/PDF cho chủ mặt bằng hàng tháng

---

## 6. Tính toán hoàn vốn đầu tư / Investment Payback

### Công thức

```
Thời gian hoàn vốn (tháng) = Tổng chi phí đầu tư (VND) / Lợi nhuận gộp tháng (VND)
```

### Bảng ước tính nhanh

| Quy mô | Đầu tư | Lợi nhuận gộp/tháng | Hoàn vốn |
|--------|--------|---------------------|----------|
| 3 trụ AC 7kW | ~90M VND | ~5–8M VND | 12–18 tháng |
| 5 trụ AC 22kW | ~200M VND | ~15–20M VND | 10–14 tháng |
| 2 trụ DC 60kW | ~500M VND | ~25–40M VND | 13–20 tháng |
| 10 trụ AC + 2 DC | ~1 tỷ VND | ~60–80M VND | 13–17 tháng |

**Yếu tố ảnh hưởng đến hoàn vốn:**
- Tỷ lệ sử dụng (utilization) — mục tiêu >40% để có doanh thu tốt
- Số giờ cao điểm xe điện đến sạc
- Giá điện khu vực
- Chi phí lắp đặt đường dây (nếu cần nâng cấp hạ tầng điện)

---

## 7. Đánh giá địa điểm / Site Assessment Criteria

NLP EV đánh giá địa điểm theo các tiêu chí:

**✅ Địa điểm lý tưởng:**
- Bãi đỗ xe có >50 chỗ, tỷ lệ lấp đầy >70%
- Máy biến áp ≥ 100 kVA, có thể nâng cấp
- Gần khu dân cư, văn phòng mật độ cao
- Chủ mặt bằng cam kết hợp đồng ≥ 3 năm
- Internet cố định ổn định (4G backup)

**⚠️ Cần xem xét thêm:**
- Công suất máy biến áp < 50 kVA (cần đầu tư thêm)
- Địa điểm mới chưa có lưu lượng EV (cần marketing)
- Hợp đồng mặt bằng ngắn hạn < 2 năm

---

## 8. Định dạng đầu ra / Output Format

```
═══════════════════════════════════════
⚡ BÁO CÁO TƯ VẤN TRẠM SẠC XE ĐIỆN
═══════════════════════════════════════
Địa điểm: [Loại địa điểm]
Diện tích: [m²] | Công suất MBA: [kVA]

🔌 CẤU HÌNH ĐỀ XUẤT
  Số trụ AC: [X] trụ × [kW] = [tổng kW]
  Số trụ DC: [X] trụ × [kW] = [tổng kW]
  Tổng công suất cần: [Y] kVA

💰 ĐẦU TƯ
  Chi phí thiết bị: ~[VND]
  Chi phí lắp đặt: ~[VND]
  Tổng đầu tư: ~[VND]

📈 DOANH THU DỰ KIẾN
  Sản lượng ngày (lý thuyết): [kWh]
  Tỷ lệ sử dụng ước tính: [%]
  Doanh thu gộp/tháng: ~[VND]
  Lợi nhuận/tháng (sau điện): ~[VND]
  Thời gian hoàn vốn: ~[X] tháng

🤝 MÔ HÌNH HỢP TÁC
  Chủ mặt bằng: [X]% doanh thu thuần
  NLP EV (vận hành): [Y]%
  
📡 CÔNG NGHỆ
  Giao thức: OCPP [1.6J / 2.0.1]
  CSMS: NLP EV Cloud Platform
  Thanh toán: App + RFID + QR

📞 LIÊN HỆ NLP EV CHARGING
  Website: nlpgroup.vn
  Đăng ký khảo sát địa điểm miễn phí
═══════════════════════════════════════
```

---

## 9. Lưu ý quan trọng / Important Notes

- Số liệu chi phí thiết bị là ước tính — phụ thuộc vào hãng, model và thời điểm
- Công suất máy biến áp phải đủ dự phòng: tổng tải charger ≤ 70% công suất MBA
- Cần giấy phép hoạt động điện lực để kinh doanh dịch vụ sạc EV tại Việt Nam
- Quy định về giá bán điện sạc EV theo Thông tư Bộ Công Thương — cần cập nhật định kỳ
- Liên hệ **NLP EV Charging** tại **nlpgroup.vn** để được khảo sát địa điểm miễn phí và nhận báo giá chính thức

---

## 10. Từ khóa kích hoạt / Trigger Keywords

Kỹ năng này được kích hoạt khi người dùng nhắc đến:
`trạm sạc xe điện`, `trụ sạc`, `EV charging`, `sạc xe điện`, `trạm sạc AC`, `trạm sạc DC`, `sạc nhanh DC`, `sạc chậm AC`, `OCPP`, `CSMS`, `nhượng quyền trạm sạc`, `hợp tác mặt bằng trạm sạc`, `doanh thu trạm sạc`, `đầu tư trạm sạc EV`, `quy hoạch trạm sạc`, `kWh sạc`, `xe điện VinFast`, `VinFast EV charging`, `trạm sạc công cộng`
