import React, { useState } from 'react';
import {
    Bot,
    Folder,
    Edit3,
    Settings,
    MessageCircle,
    Copy,
    AlertTriangle,
    CheckCircle,
    MessageSquareDashed,
    Zap,
    Sun,
    Leaf,
    Cpu,
    Building2,
    PhoneCall,
} from 'lucide-react';

// ============================================================
// SOP Data — NLP Group B2B Customer Support
// Cập nhật: 2026-10 | Áp dụng cho: CS / BD / Technical Team
// ============================================================
const sopData = [
    // ── Nhóm 0: Tiếp nhận & Phân loại ──────────────────────
    {
        group: "Nhóm 0: Tiếp nhận & Phân loại",
        icon: "PhoneCall",
        items: [
            {
                id: 'g0_1',
                title: "Chào hỏi ban đầu (Inbound)",
                text: "Xin chào! Tôi là {{cs_name}} từ NLP Group.\nCảm ơn anh/chị đã liên hệ với chúng tôi. Anh/chị có thể cho tôi biết nhu cầu hiện tại để tôi hỗ trợ tốt nhất?\n\n📌 NLP Group cung cấp:\n• Hệ thống điện mặt trời áp mái (50 kW – 5 MW)\n• Trạm sạc EV (AC 7–22 kW / DC 30–480 kW)\n• Giải pháp AI & Chuyển đổi số\n• Tư vấn ESG & Tín chỉ carbon\n\nAnh/chị muốn tìm hiểu về lĩnh vực nào?",
                inputs: [{ key: 'cs_name', label: 'Tên CS / BD phụ trách' }],
            },
            {
                id: 'g0_2',
                title: "Phân loại & Chuyển hướng",
                text: "Cảm ơn anh/chị {{client_name}} đã chia sẻ!\n\nDựa trên nhu cầu, tôi sẽ kết nối anh/chị với chuyên gia phụ trách:\n• 🔋 Điện mặt trời / BESS → Team NLP Energy\n• ⚡ Trạm sạc EV / Nhượng quyền → Team NLP EV Charging\n• 🤖 Phần mềm / AI → Team NLP Technology\n• 🌿 ESG / Carbon → Team NLP ESG\n\nAnh/chị vui lòng chờ {{wait_time}} phút, chuyên gia sẽ liên hệ ngay.",
                inputs: [
                    { key: 'client_name', label: 'Tên khách hàng / Công ty' },
                    { key: 'wait_time', label: 'Thời gian chờ (phút)' },
                ],
            },
            {
                id: 'g0_3',
                title: "Thu thập thông tin cơ bản",
                text: "Để tư vấn chính xác nhất, anh/chị vui lòng cung cấp:\n\n1. Tên công ty / tổ chức\n2. Ngành nghề kinh doanh chính\n3. Quy mô: số nhân viên / diện tích mặt bằng\n4. Địa chỉ triển khai dự án (tỉnh/thành phố)\n5. Ngân sách dự kiến (nếu có)\n6. Thời gian mong muốn triển khai\n\nThông tin này sẽ giúp đội ngũ của chúng tôi chuẩn bị đề xuất phù hợp nhất.",
            },
            {
                id: 'g0_4',
                title: "Xác nhận lịch tư vấn",
                text: "Tôi đã ghi nhận thông tin của anh/chị {{client_name}} — {{company}}.\n\n📅 Lịch tư vấn đã đặt:\n• Thời gian: {{meeting_time}}\n• Hình thức: {{meeting_type}}\n• Chuyên gia phụ trách: {{expert_name}}\n\nAnh/chị sẽ nhận email xác nhận trong vòng 30 phút.\nNếu cần điều chỉnh, vui lòng phản hồi lại tin nhắn này.\n\nTrân trọng,\nNLP Group",
                inputs: [
                    { key: 'client_name', label: 'Tên khách hàng' },
                    { key: 'company', label: 'Tên công ty' },
                    { key: 'meeting_time', label: 'Thời gian họp (VD: 10:00 Thứ 3, 22/10)' },
                    { key: 'meeting_type', label: 'Hình thức (Online Zoom / Tại văn phòng NLP / Khảo sát thực địa)' },
                    { key: 'expert_name', label: 'Tên chuyên gia' },
                ],
            },
        ],
    },

    // ── Nhóm 1: Tư vấn Điện Mặt Trời ───────────────────────
    {
        group: "Nhóm 1: Điện Mặt Trời (NLP Energy)",
        icon: "Sun",
        items: [
            {
                id: 'g1_1',
                title: "Giới thiệu giải pháp Solar",
                text: "NLP Energy cung cấp giải pháp điện mặt trời toàn diện:\n\n☀️ ĐIỆN MẶT TRỜI ÁP MÁI\n• Quy mô: 50 kW – 5 MW\n• Mô hình: Tự đầu tư (sở hữu hệ thống) hoặc PPA (0 đồng đầu tư, trả tiền điện)\n• Tiêu chuẩn: Tấm pin PERC / TOPCon, Inverter SMA / Huawei / Sungrow\n• Bảo hành: Tấm pin 25 năm, Inverter 10 năm\n\n🔋 HỆ THỐNG BESS (Lưu trữ điện)\n• Lưu trữ điện ban ngày dùng ban đêm\n• Cắt giảm tiền điện giờ cao điểm tới 40%\n• Tích hợp với hệ thống Solar hoặc lưới điện EVN\n\nAnh/chị muốn tìm hiểu mô hình nào phù hợp nhất?",
            },
            {
                id: 'g1_2',
                title: "Thông tin khảo sát mái nhà",
                text: "Để lập báo giá chính xác cho hệ thống Solar, đội kỹ thuật NLP Energy cần khảo sát:\n\n📋 THÔNG TIN CẦN CHUẨN BỊ:\n1. Hóa đơn điện 3 tháng gần nhất (để phân tích tiêu thụ)\n2. Sơ đồ mặt bằng mái / diện tích mái trống (m²)\n3. Loại kết cấu mái: tôn / bê tông / ngói\n4. Ảnh mái chụp từ nhiều góc\n5. Hóa đơn điện gần nhất (để xác định công tơ 1 pha / 3 pha)\n\nĐội kỹ thuật sẽ đến khảo sát miễn phí tại: {{address}}.\nLịch khảo sát dự kiến: {{survey_date}}.",
                inputs: [
                    { key: 'address', label: 'Địa chỉ khảo sát' },
                    { key: 'survey_date', label: 'Ngày khảo sát dự kiến' },
                ],
            },
            {
                id: 'g1_3',
                title: "Báo giá sơ bộ Solar",
                text: "Dựa trên thông tin khảo sát, NLP Energy đề xuất sơ bộ:\n\n📊 THÔNG SỐ HỆ THỐNG:\n• Công suất lắp đặt: {{capacity}} kWp\n• Sản lượng điện ước tính: {{output}} kWh/năm\n• Tiết kiệm điện/năm: {{saving}} VNĐ\n• Mức giảm CO₂: {{co2}} tấn/năm\n\n💰 CHI PHÍ ĐẦU TƯ:\n• Tổng đầu tư ước tính: {{investment}} VNĐ\n• Thời gian hoàn vốn: {{payback}} năm\n• IRR: {{irr}}%\n\n📄 Báo giá chi tiết sẽ được gửi qua email {{email}} trong vòng 24 giờ.",
                inputs: [
                    { key: 'capacity', label: 'Công suất (kWp)' },
                    { key: 'output', label: 'Sản lượng kWh/năm' },
                    { key: 'saving', label: 'Tiết kiệm VNĐ/năm' },
                    { key: 'co2', label: 'CO₂ giảm (tấn/năm)' },
                    { key: 'investment', label: 'Tổng đầu tư (VNĐ)' },
                    { key: 'payback', label: 'Thời gian hoàn vốn (năm)' },
                    { key: 'irr', label: 'IRR (%)' },
                    { key: 'email', label: 'Email khách hàng' },
                ],
            },
            {
                id: 'g1_4',
                title: "Giải thích mô hình PPA",
                text: "MÔ HÌNH PPA (Power Purchase Agreement) — ĐẦU TƯ 0 ĐỒNG:\n\n✅ Cách hoạt động:\n• NLP Energy đầu tư 100% chi phí lắp đặt\n• Khách hàng chỉ trả tiền điện theo lượng điện tiêu thụ từ hệ thống Solar\n• Giá điện PPA thường thấp hơn 10–20% so với giá EVN\n• Thời hạn hợp đồng: 10–20 năm\n\n✅ Lợi ích khách hàng:\n• Không cần vốn đầu tư ban đầu\n• Tiết kiệm chi phí điện ngay lập tức\n• Không phải lo bảo trì, vận hành\n• Quyền mua lại hệ thống sau {{buyout_year}} năm với giá ưu đãi\n\n❓ Anh/chị có muốn nhận hợp đồng PPA mẫu để tham khảo?",
                inputs: [{ key: 'buyout_year', label: 'Năm có thể mua lại' }],
            },
        ],
    },

    // ── Nhóm 2: Trạm Sạc EV ─────────────────────────────────
    {
        group: "Nhóm 2: Trạm Sạc EV (NLP EV Charging)",
        icon: "Zap",
        items: [
            {
                id: 'g2_1',
                title: "Giới thiệu giải pháp EV Charging",
                text: "NLP EV Charging cung cấp giải pháp trạm sạc xe điện toàn diện:\n\n⚡ DÒNG SẠC AC (Sạc chậm):\n• 7 kW — Hộ gia đình, văn phòng\n• 11 kW — Tòa nhà, khách sạn\n• 22 kW — TTTM, bãi đậu xe công cộng\n\n⚡ DÒNG SẠC DC (Sạc nhanh):\n• 30–60 kW — Trạm dừng nhỏ, đại lý xe\n• 120–360 kW — Trạm dừng chân cao tốc\n• 480 kW (T480Q) — Siêu trạm, fleet charging\n\n🔧 Hỗ trợ chuẩn: CCS2, GB/T, Type 2\n📱 App quản lý + CSMS OCPP 2.0.1\n🛡️ Bảo hành phần cứng 3 năm, NOC 24/7\n\nAnh/chị đang cần triển khai tại địa điểm nào?",
            },
            {
                id: 'g2_2',
                title: "Khảo sát địa điểm EV",
                text: "Để tư vấn giải pháp phù hợp, NLP EV Charging cần biết:\n\n📋 THÔNG TIN ĐỊA ĐIỂM:\n1. Loại địa điểm: {{location_type}}\n2. Địa chỉ: {{address}}\n3. Diện tích bãi đậu xe (m²)\n4. Số lượng xe EV dự kiến sạc mỗi ngày\n5. Nguồn điện hiện có: tổng công suất tủ điện (kW)\n6. Mục tiêu: thu phí sạc / miễn phí cho nhân viên / nhượng quyền\n\nĐội kỹ thuật NLP sẽ khảo sát điện miễn phí và tư vấn phương án tối ưu.",
                inputs: [
                    { key: 'location_type', label: 'Loại địa điểm (TTTM / Khách sạn / KCN / Cao tốc / Văn phòng)' },
                    { key: 'address', label: 'Địa chỉ' },
                ],
            },
            {
                id: 'g2_3',
                title: "Giới thiệu mô hình nhượng quyền",
                text: "MÔ HÌNH NHƯỢNG QUYỀN TRẠM SẠC NLP — ĐẦU TƯ 0 ĐỒNG:\n\n✅ Cách hoạt động:\n• NLP đầu tư 100%: thiết bị, lắp đặt, CSMS, app\n• Đối tác cung cấp: mặt bằng + điện\n• Doanh thu chia theo tỷ lệ thỏa thuận\n\n✅ Hỗ trợ từ NLP:\n• Quản lý vận hành từ xa qua CSMS\n• Bảo trì định kỳ + NOC 24/7\n• Marketing & branding trạm sạc\n• Báo cáo doanh thu tự động hàng tháng\n\n💰 ROI bắt đầu từ tháng 18\n\nAnh/chị có mặt bằng tại {{location}} không? Tôi có thể gửi hồ sơ nhượng quyền chi tiết.",
                inputs: [{ key: 'location', label: 'Tỉnh / Thành phố / Địa điểm cụ thể' }],
            },
            {
                id: 'g2_4',
                title: "Báo giá trụ sạc",
                text: "Dựa trên nhu cầu, NLP EV Charging đề xuất:\n\n📦 ĐỀ XUẤT THIẾT BỊ:\n• Loại trụ sạc: {{charger_type}}\n• Số lượng: {{quantity}} trụ\n• Tổng công suất: {{total_kw}} kW\n\n💰 CHI PHÍ ƯỚC TÍNH:\n• Thiết bị: {{equipment_cost}} VNĐ\n• Thi công & lắp đặt: {{install_cost}} VNĐ\n• Hạ tầng điện: {{electrical_cost}} VNĐ\n• Tổng đầu tư: {{total_cost}} VNĐ\n\n⏱️ Thời gian triển khai: {{timeline}}\n📄 Hợp đồng & báo giá chi tiết gửi qua email {{email}}.",
                inputs: [
                    { key: 'charger_type', label: 'Loại trụ (VD: DC 60kW Grasen VP10/02)' },
                    { key: 'quantity', label: 'Số lượng trụ' },
                    { key: 'total_kw', label: 'Tổng công suất (kW)' },
                    { key: 'equipment_cost', label: 'Chi phí thiết bị (VNĐ)' },
                    { key: 'install_cost', label: 'Chi phí lắp đặt (VNĐ)' },
                    { key: 'electrical_cost', label: 'Hạ tầng điện (VNĐ)' },
                    { key: 'total_cost', label: 'Tổng đầu tư (VNĐ)' },
                    { key: 'timeline', label: 'Thời gian triển khai' },
                    { key: 'email', label: 'Email khách hàng' },
                ],
            },
        ],
    },

    // ── Nhóm 3: Báo giá & Hợp đồng ─────────────────────────
    {
        group: "Nhóm 3: Báo giá & Hợp đồng",
        icon: "Building2",
        items: [
            {
                id: 'g3_1',
                title: "Gửi báo giá chính thức",
                text: "Kính gửi Anh/Chị {{client_name}} — {{company}},\n\nNLP Group xin gửi báo giá chính thức cho dự án {{project_name}}:\n\n📄 Tài liệu đính kèm:\n• Báo giá số: {{quote_number}}\n• Ngày lập: {{quote_date}}\n• Hiệu lực: 30 ngày kể từ ngày lập\n\n💡 Điểm nổi bật đề xuất:\n• {{highlight_1}}\n• {{highlight_2}}\n\nNếu anh/chị có câu hỏi về báo giá, vui lòng liên hệ trực tiếp qua:\n📞 {{contact_phone}} | ✉️ info@nlpgroup.vn\n\nTrân trọng,\n{{cs_name}} | NLP Group",
                inputs: [
                    { key: 'client_name', label: 'Tên khách hàng' },
                    { key: 'company', label: 'Tên công ty' },
                    { key: 'project_name', label: 'Tên dự án' },
                    { key: 'quote_number', label: 'Số báo giá (VD: NLP-2026-0123)' },
                    { key: 'quote_date', label: 'Ngày lập báo giá' },
                    { key: 'highlight_1', label: 'Điểm nổi bật 1' },
                    { key: 'highlight_2', label: 'Điểm nổi bật 2' },
                    { key: 'contact_phone', label: 'Số điện thoại CS' },
                    { key: 'cs_name', label: 'Tên CS phụ trách' },
                ],
            },
            {
                id: 'g3_2',
                title: "Follow-up báo giá (Sau 3–5 ngày)",
                text: "Kính anh/chị {{client_name}},\n\nTôi là {{cs_name}} từ NLP Group, muốn hỏi thăm anh/chị đã có cơ hội xem qua báo giá {{quote_number}} gửi ngày {{quote_date}} chưa?\n\nNếu anh/chị có bất kỳ thắc mắc về:\n• Thông số kỹ thuật\n• Điều khoản thanh toán\n• Điều chỉnh phạm vi công việc\n\nNLP Group sẵn sàng hỗ trợ và điều chỉnh phù hợp.\n\nAnh/chị dự kiến khi nào có thể phản hồi để chúng tôi sắp xếp bước tiếp theo?",
                inputs: [
                    { key: 'client_name', label: 'Tên khách hàng' },
                    { key: 'cs_name', label: 'Tên CS' },
                    { key: 'quote_number', label: 'Số báo giá' },
                    { key: 'quote_date', label: 'Ngày gửi báo giá' },
                ],
            },
            {
                id: 'g3_3',
                title: "Xác nhận ký hợp đồng",
                text: "Chào anh/chị {{client_name}},\n\nNLP Group xin xác nhận đã nhận được hợp đồng {{contract_number}} đã ký từ {{company}}.\n\n✅ Các bước tiếp theo:\n1. NLP ký và đóng dấu phản hồi trong vòng 2 ngày làm việc\n2. Xuất hóa đơn đặt cọc ({{deposit_percent}}% = {{deposit_amount}} VNĐ)\n3. Lên lịch khảo sát kỹ thuật chi tiết: {{survey_date}}\n4. Bắt đầu thi công dự kiến: {{start_date}}\n\n📞 Đầu mối dự án từ NLP: {{pm_name}} — {{pm_phone}}\n\nCảm ơn anh/chị đã tin tưởng NLP Group!",
                inputs: [
                    { key: 'client_name', label: 'Tên khách hàng' },
                    { key: 'contract_number', label: 'Số hợp đồng' },
                    { key: 'company', label: 'Tên công ty' },
                    { key: 'deposit_percent', label: 'Tỷ lệ đặt cọc (%)' },
                    { key: 'deposit_amount', label: 'Số tiền đặt cọc (VNĐ)' },
                    { key: 'survey_date', label: 'Ngày khảo sát kỹ thuật' },
                    { key: 'start_date', label: 'Ngày khởi công dự kiến' },
                    { key: 'pm_name', label: 'Tên Project Manager NLP' },
                    { key: 'pm_phone', label: 'SĐT Project Manager' },
                ],
            },
        ],
    },

    // ── Nhóm 4: Kỹ thuật & Hậu mãi ─────────────────────────
    {
        group: "Nhóm 4: Kỹ thuật & Hậu mãi",
        icon: "Cpu",
        items: [
            {
                id: 'g4_1',
                title: "Tiếp nhận sự cố kỹ thuật",
                text: "Cảm ơn anh/chị đã báo cáo. Tôi đã ghi nhận sự cố:\n\n🔧 THÔNG TIN SỰ CỐ:\n• Dự án: {{project_name}}\n• Mô tả lỗi: {{issue_description}}\n• Thời điểm xảy ra: {{issue_time}}\n• Mức độ: {{severity}}\n\n📌 Mã ticket hỗ trợ: {{ticket_id}}\n\nĐội kỹ thuật NLP sẽ phản hồi trong vòng {{response_time}} giờ.\nNếu là sự cố khẩn cấp, vui lòng gọi hotline: +84 936 611 163.",
                inputs: [
                    { key: 'project_name', label: 'Tên dự án / Địa điểm' },
                    { key: 'issue_description', label: 'Mô tả sự cố ngắn gọn' },
                    { key: 'issue_time', label: 'Thời điểm xảy ra' },
                    { key: 'severity', label: 'Mức độ (Khẩn cấp / Trung bình / Thấp)' },
                    { key: 'ticket_id', label: 'Mã Ticket (VD: NLP-TK-20261020-001)' },
                    { key: 'response_time', label: 'Thời gian phản hồi (4 / 8 / 24)' },
                ],
            },
            {
                id: 'g4_2',
                title: "Cập nhật tiến độ xử lý",
                text: "Kính anh/chị {{client_name}},\n\nCập nhật mới nhất về ticket {{ticket_id}}:\n\n📊 TRẠNG THÁI: {{status}}\n\n🔍 Ghi chú kỹ thuật:\n{{tech_note}}\n\nBước tiếp theo: {{next_step}}\nDự kiến hoàn tất: {{eta}}\n\nNếu cần thêm thông tin, vui lòng phản hồi hoặc gọi {{tech_phone}}.",
                inputs: [
                    { key: 'client_name', label: 'Tên khách hàng' },
                    { key: 'ticket_id', label: 'Mã Ticket' },
                    { key: 'status', label: 'Trạng thái (Đang xử lý / Chờ phụ tùng / Đã xử lý)' },
                    { key: 'tech_note', label: 'Ghi chú kỹ thuật' },
                    { key: 'next_step', label: 'Bước tiếp theo' },
                    { key: 'eta', label: 'Thời gian hoàn tất dự kiến' },
                    { key: 'tech_phone', label: 'SĐT kỹ thuật viên' },
                ],
            },
            {
                id: 'g4_3',
                title: "Xác nhận bảo hành",
                text: "THÔNG TIN BẢO HÀNH DỰ ÁN:\n\n📋 Dự án: {{project_name}}\n🏢 Khách hàng: {{company}}\n📅 Ngày nghiệm thu: {{commission_date}}\n\n🛡️ CHÍNH SÁCH BẢO HÀNH:\n• Tấm pin quang điện: 25 năm (hiệu suất)\n• Inverter / Bộ sạc: 10 năm ({{brand}})\n• Công trình thi công: 2 năm\n• Bảo trì định kỳ: {{maintenance_schedule}}\n\n📞 Liên hệ bảo hành: +84 936 611 163 | warranty@nlpgroup.vn\n\n⚠️ Bảo hành không áp dụng cho hư hỏng do thiên tai, sử dụng sai mục đích hoặc tự ý can thiệp thiết bị.",
                inputs: [
                    { key: 'project_name', label: 'Tên dự án' },
                    { key: 'company', label: 'Tên công ty' },
                    { key: 'commission_date', label: 'Ngày nghiệm thu' },
                    { key: 'brand', label: 'Hãng Inverter / Charger' },
                    { key: 'maintenance_schedule', label: 'Lịch bảo trì (VD: 6 tháng/lần)' },
                ],
            },
            {
                id: 'g4_4',
                title: "Hướng dẫn app / CSMS",
                text: "HƯỚNG DẪN SỬ DỤNG HỆ THỐNG QUẢN LÝ:\n\n📱 APP NLP ENERGY MONITOR (Solar):\n• Tải về: App Store / Google Play — tìm 'NLP Energy'\n• Đăng nhập: Tài khoản được cấp sau nghiệm thu\n• Tính năng: Xem sản lượng real-time, lịch sử, cảnh báo lỗi\n\n💻 CSMS — EV CHARGING MANAGEMENT:\n• URL: {{csms_url}}\n• Tài khoản: {{csms_account}}\n• Mật khẩu tạm: {{csms_password}}\n\n⚠️ Vui lòng đổi mật khẩu ngay sau lần đăng nhập đầu tiên.\n\nHướng dẫn sử dụng chi tiết (PDF) đã được gửi kèm email.",
                inputs: [
                    { key: 'csms_url', label: 'URL hệ thống CSMS' },
                    { key: 'csms_account', label: 'Tài khoản CSMS' },
                    { key: 'csms_password', label: 'Mật khẩu tạm' },
                ],
            },
        ],
    },

    // ── Nhóm 5: ESG & Đối tác chiến lược ────────────────────
    {
        group: "Nhóm 5: ESG / Carbon & Đối tác",
        icon: "Leaf",
        items: [
            {
                id: 'g5_1',
                title: "Giới thiệu dịch vụ ESG",
                text: "NLP ESG & Carbon cung cấp dịch vụ phát triển bền vững:\n\n🌿 DỊCH VỤ ESG:\n• Kiểm kê phát thải khí nhà kính (GHG Inventory) theo ISO 14064\n• Lập báo cáo ESG theo GRI Standards / TCFD\n• Xây dựng lộ trình Net Zero 2030–2050\n• Tư vấn tuân thủ CBAM (thuế carbon biên giới EU)\n\n🌱 TÍN CHỈ CARBON:\n• Phát triển dự án tín chỉ carbon (VCS / Gold Standard)\n• Môi giới mua bán tín chỉ carbon quốc tế\n• Đăng ký trên thị trường carbon Việt Nam\n\nDoanh nghiệp của anh/chị đang ở giai đoạn nào trong hành trình ESG?",
            },
            {
                id: 'g5_2',
                title: "Đề xuất hợp tác chiến lược",
                text: "Kính gửi {{partner_name}} — {{partner_company}},\n\nNLP Group trân trọng đề xuất hợp tác chiến lược trong lĩnh vực {{partnership_area}}:\n\n🤝 MÔ HÌNH HỢP TÁC:\n• {{model_description}}\n\n💡 GIÁ TRỊ MANG LẠI:\n• Cho {{partner_company}}: {{value_for_partner}}\n• Cho NLP Group: {{value_for_nlp}}\n\n📋 BƯỚC TIẾP THEO:\n1. Ký kết MOU (thời gian dự kiến: {{mou_date}})\n2. Họp kick-off lên kế hoạch chi tiết\n3. Triển khai thí điểm tại {{pilot_location}}\n\nAnh/chị vui lòng xem xét và phản hồi. NLP Group rất mong được hợp tác!",
                inputs: [
                    { key: 'partner_name', label: 'Tên đại diện đối tác' },
                    { key: 'partner_company', label: 'Tên công ty đối tác' },
                    { key: 'partnership_area', label: 'Lĩnh vực hợp tác' },
                    { key: 'model_description', label: 'Mô tả mô hình hợp tác' },
                    { key: 'value_for_partner', label: 'Giá trị cho đối tác' },
                    { key: 'value_for_nlp', label: 'Giá trị cho NLP Group' },
                    { key: 'mou_date', label: 'Ngày dự kiến ký MOU' },
                    { key: 'pilot_location', label: 'Địa điểm thí điểm' },
                ],
            },
            {
                id: 'g5_3',
                title: "Báo cáo carbon footprint",
                text: "KẾT QUẢ KIỂM KÊ PHÁT THẢI — {{company}} ({{year}}):\n\n📊 TỔNG PHÁT THẢI: {{total_emission}} tấn CO₂e\n• Scope 1 (Trực tiếp): {{scope1}} tấn CO₂e\n• Scope 2 (Điện): {{scope2}} tấn CO₂e\n• Scope 3 (Chuỗi cung ứng): {{scope3}} tấn CO₂e\n\n🎯 MỤC TIÊU GIẢM PHÁT THẢI:\n• Năm {{target_year}}: giảm {{target_percent}}% so với năm gốc\n\n💡 GIẢI PHÁP NLP ĐỀ XUẤT:\n1. Lắp đặt Solar {{solar_kw}} kWp → giảm {{solar_reduction}} tấn CO₂e/năm\n2. Mua tín chỉ carbon bù đắp phần còn lại: {{offset_tons}} tấn\n\nBáo cáo đầy đủ (PDF) đã được gửi qua email.",
                inputs: [
                    { key: 'company', label: 'Tên công ty' },
                    { key: 'year', label: 'Năm kiểm kê' },
                    { key: 'total_emission', label: 'Tổng phát thải (tấn CO₂e)' },
                    { key: 'scope1', label: 'Scope 1' },
                    { key: 'scope2', label: 'Scope 2' },
                    { key: 'scope3', label: 'Scope 3' },
                    { key: 'target_year', label: 'Năm mục tiêu' },
                    { key: 'target_percent', label: 'Mục tiêu giảm (%)' },
                    { key: 'solar_kw', label: 'Solar đề xuất (kWp)' },
                    { key: 'solar_reduction', label: 'CO₂e giảm từ Solar' },
                    { key: 'offset_tons', label: 'Tấn cần mua tín chỉ' },
                ],
            },
        ],
    },

    // ── Nhóm 6: Phản hồi nhanh & Xử lý phàn nàn ────────────
    {
        group: "Nhóm 6: Phản hồi nhanh & Phàn nàn",
        icon: "MessageCircle",
        items: [
            {
                id: 'g6_1',
                title: "Báo khách chờ xử lý",
                text: "Cảm ơn anh/chị đã phản hồi. Tôi đang kiểm tra thông tin và sẽ trả lời trong vòng {{wait_time}}.\n\nMã tham chiếu: {{ref_code}}",
                inputs: [
                    { key: 'wait_time', label: 'Thời gian (VD: 30 phút / 2 giờ)' },
                    { key: 'ref_code', label: 'Mã tham chiếu (nếu có)' },
                ],
            },
            {
                id: 'g6_2',
                title: "Chuyển lên cấp trên (Escalate)",
                text: "Tôi đã ghi nhận và chuyển vấn đề của anh/chị lên {{escalate_to}} để được hỗ trợ tốt nhất.\n\nAnh/chị sẽ nhận được phản hồi trong vòng {{eta}}.\nMã theo dõi: {{ticket_id}}",
                inputs: [
                    { key: 'escalate_to', label: 'Bộ phận / Người tiếp nhận' },
                    { key: 'eta', label: 'Thời gian phản hồi' },
                    { key: 'ticket_id', label: 'Mã Ticket' },
                ],
            },
            {
                id: 'g6_3',
                title: "Xử lý phàn nàn (Empathy)",
                text: "Thưa anh/chị {{client_name}},\n\nTôi thành thật xin lỗi vì trải nghiệm không như kỳ vọng của anh/chị.\n\nNLP Group đã ghi nhận phản hồi và cam kết:\n• Điều tra nguyên nhân trong vòng {{investigate_time}}\n• Phản hồi giải pháp cụ thể cho anh/chị\n• Có biện pháp phòng ngừa tái diễn\n\nĐây là ưu tiên hàng đầu của chúng tôi. Tôi sẽ cập nhật trực tiếp cho anh/chị.",
                inputs: [
                    { key: 'client_name', label: 'Tên khách hàng' },
                    { key: 'investigate_time', label: 'Thời gian điều tra (VD: 24 giờ)' },
                ],
            },
            {
                id: 'g6_4',
                title: "Từ chối khéo (Ngoài phạm vi)",
                text: "Cảm ơn anh/chị đã tin tưởng liên hệ NLP Group. Tuy nhiên, yêu cầu này hiện nằm ngoài phạm vi dịch vụ/thẩm quyền của bộ phận chúng tôi.\n\nAnh/chị vui lòng liên hệ:\n• {{correct_department}} — {{correct_contact}}\n\nHoặc gửi yêu cầu qua email: info@nlpgroup.vn để được điều phối đúng bộ phận.",
                inputs: [
                    { key: 'correct_department', label: 'Bộ phận đúng' },
                    { key: 'correct_contact', label: 'Liên hệ bộ phận đó' },
                ],
            },
            {
                id: 'g6_5',
                title: "Xác nhận hoàn tất & Đánh giá",
                text: "Kính anh/chị {{client_name}},\n\nVấn đề {{issue_summary}} đã được xử lý hoàn tất.\n\nAnh/chị vui lòng kiểm tra và xác nhận kết quả. Nếu chưa đáp ứng kỳ vọng, đội ngũ NLP Group luôn sẵn sàng hỗ trợ tiếp.\n\n⭐ Nếu hài lòng, anh/chị vui lòng dành 1 phút đánh giá dịch vụ tại:\n{{feedback_link}\n\nTrân trọng cảm ơn anh/chị đã đồng hành cùng NLP Group!",
                inputs: [
                    { key: 'client_name', label: 'Tên khách hàng' },
                    { key: 'issue_summary', label: 'Tóm tắt vấn đề đã xử lý' },
                    { key: 'feedback_link', label: 'Link đánh giá / Google Review' },
                ],
            },
        ],
    },
];

// ── Checklist trước khi gửi ──────────────────────────────────
const checklist = [
    "Đã xưng hô đúng anh/chị + tên khách chưa?",
    "Báo giá: đã điền đủ số hợp đồng, ngày, tên dự án chưa?",
    "Ticket kỹ thuật: đã tạo mã ticket trong hệ thống chưa?",
    "Cấp tài khoản CSMS: đã dặn đổi mật khẩu ngay chưa?",
    "Escalate: đã thông báo nội bộ cho người tiếp nhận chưa?",
    "Cuối tin nhắn: có ký tên + NLP Group chưa?",
];

// ── Icon map ──────────────────────────────────────────────────
const GroupIcon = ({ name }: { name: string }) => {
    const props = { className: "w-4 h-4 mr-1 flex-shrink-0", strokeWidth: 2 };
    switch (name) {
        case 'Sun':         return <Sun {...props} />;
        case 'Zap':         return <Zap {...props} />;
        case 'Leaf':        return <Leaf {...props} />;
        case 'Cpu':         return <Cpu {...props} />;
        case 'Building2':   return <Building2 {...props} />;
        case 'PhoneCall':   return <PhoneCall {...props} />;
        default:            return <Folder {...props} />;
    }
};

// ── Main Component ───────────────────────────────────────────
export default function NLPGroupCSBot() {
    const [selectedItem, setSelectedItem] = useState<any>(null);
    const [formData, setFormData] = useState<Record<string, string>>({});
    const [showToast, setShowToast] = useState(false);

    const handleSelectItem = (item: any) => {
        setSelectedItem(item);
        const initial: Record<string, string> = {};
        if (item.inputs) item.inputs.forEach((inp: any) => (initial[inp.key] = ''));
        setFormData(initial);
    };

    const handleInputChange = (key: string, value: string) => {
        setFormData(prev => ({ ...prev, [key]: value }));
    };

    const getGeneratedText = () => {
        if (!selectedItem) return '';
        let text: string = selectedItem.text;
        if (selectedItem.inputs) {
            selectedItem.inputs.forEach((inp: any) => {
                const val = formData[inp.key] || `[${inp.label.toUpperCase()}]`;
                text = text.replace(new RegExp(`{{${inp.key}}}`, 'g'), val);
            });
        }
        return text;
    };

    const copyToClipboard = () => {
        const text = getGeneratedText();
        const el = document.createElement('textarea');
        el.value = text;
        el.style.position = 'fixed';
        el.style.left = '-999999px';
        document.body.appendChild(el);
        el.focus();
        el.select();
        try {
            document.execCommand('copy');
            setShowToast(true);
            setTimeout(() => setShowToast(false), 2000);
        } catch (e) {
            console.error('Copy failed:', e);
        } finally {
            el.remove();
        }
    };

    return (
        <div className="flex h-screen overflow-hidden font-sans" style={{ background: '#f7f8fa' }}>
            <style>{`
                .nlp-scrollbar::-webkit-scrollbar { width: 5px; }
                .nlp-scrollbar::-webkit-scrollbar-track { background: transparent; }
                .nlp-scrollbar::-webkit-scrollbar-thumb { background: #d1fae5; border-radius: 20px; }
                .nlp-btn-active  { background: #f0fdf4; border-color: #6ee7b7; color: #1a3c2e; font-weight: 600; }
                .nlp-btn-default { background: #fff; border-color: #e5e7eb; color: #374151; }
                .nlp-btn-default:hover { background: #f9fafb; border-color: #d1fae5; }
            `}</style>

            {/* TOAST */}
            {showToast && (
                <div className="fixed top-4 right-4 px-5 py-3 rounded-lg shadow-lg flex items-center z-50"
                     style={{ background: '#2d6a4f', color: '#fff' }}>
                    <CheckCircle className="w-4 h-4 mr-2" />
                    Đã sao chép vào clipboard!
                </div>
            )}

            {/* ── SIDEBAR ── */}
            <div className="w-80 flex flex-col h-full border-r" style={{ background: '#fff', borderColor: '#e5e7eb' }}>
                {/* Header */}
                <div className="p-4 flex-shrink-0" style={{ background: 'linear-gradient(135deg,#1a3c2e 0%,#2d6a4f 100%)' }}>
                    <h1 className="text-lg font-bold flex items-center" style={{ color: '#fff' }}>
                        <Bot className="mr-2 w-5 h-5" style={{ color: '#6ee7b7' }} />
                        NLP Group CS Hub
                    </h1>
                    <p className="text-xs mt-0.5" style={{ color: 'rgba(255,255,255,0.7)' }}>
                        B2B Customer Support — SOP v1.0
                    </p>
                </div>

                {/* Groups */}
                <div className="flex-1 overflow-y-auto nlp-scrollbar p-3 space-y-4">
                    {sopData.map((group, idx) => (
                        <div key={idx}>
                            <h2 className="text-xs font-bold uppercase tracking-wider mb-2 flex items-center"
                                style={{ color: '#2d6a4f' }}>
                                <GroupIcon name={group.icon} />
                                {group.group}
                            </h2>
                            <div className="space-y-1">
                                {group.items.map(item => (
                                    <button
                                        key={item.id}
                                        onClick={() => handleSelectItem(item)}
                                        className={`w-full text-left px-3 py-2 rounded-lg text-sm border transition-all ${selectedItem?.id === item.id ? 'nlp-btn-active' : 'nlp-btn-default'}`}
                                    >
                                        {item.title}
                                    </button>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>

                {/* Sidebar footer */}
                <div className="p-3 flex-shrink-0 text-center" style={{ borderTop: '1px solid #e5e7eb' }}>
                    <span className="text-xs" style={{ color: '#9ca3af' }}>
                        nlpgroup.vn · info@nlpgroup.vn
                    </span>
                </div>
            </div>

            {/* ── MAIN PANEL ── */}
            <div className="flex-1 flex flex-col h-full overflow-hidden">
                {/* Content */}
                <div className="flex-1 p-6 overflow-y-auto nlp-scrollbar">
                    {!selectedItem ? (
                        <div className="h-full flex flex-col items-center justify-center" style={{ color: '#9ca3af' }}>
                            <MessageSquareDashed className="w-16 h-16 mb-4" style={{ color: '#d1fae5' }} />
                            <p className="text-lg font-medium" style={{ color: '#6b7280' }}>
                                Chọn kịch bản hỗ trợ bên trái
                            </p>
                            <p className="text-sm mt-1" style={{ color: '#9ca3af' }}>
                                {sopData.reduce((n, g) => n + g.items.length, 0)} kịch bản · {sopData.length} nhóm
                            </p>
                        </div>
                    ) : (
                        <div className="max-w-2xl mx-auto">
                            {/* Title */}
                            <h2 className="text-xl font-bold mb-5 pb-3 flex items-center"
                                style={{ color: '#1a3c2e', borderBottom: '2px solid #d1fae5' }}>
                                <Edit3 className="mr-2 w-5 h-5" style={{ color: '#2d6a4f' }} />
                                {selectedItem.title}
                            </h2>

                            {/* Form inputs */}
                            {selectedItem.inputs && selectedItem.inputs.length > 0 && (
                                <div className="rounded-xl p-5 mb-5" style={{ background: '#fff', border: '1px solid #e5e7eb' }}>
                                    <h3 className="text-xs font-semibold uppercase mb-4 flex items-center"
                                        style={{ color: '#6b7280' }}>
                                        <Settings className="w-4 h-4 mr-1" /> Điền biến số
                                    </h3>
                                    <div className="grid grid-cols-1 gap-3" style={{ gridTemplateColumns: selectedItem.inputs.length > 2 ? 'repeat(2,1fr)' : '1fr' }}>
                                        {selectedItem.inputs.map((inp: any) => (
                                            <div key={inp.key}>
                                                <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>
                                                    {inp.label}
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData[inp.key] || ''}
                                                    onChange={e => handleInputChange(inp.key, e.target.value)}
                                                    placeholder={`Nhập ${inp.label.toLowerCase()}…`}
                                                    className="w-full px-3 py-2 rounded-lg text-sm outline-none transition-all"
                                                    style={{ border: '1px solid #d1d5db', background: '#fafafa' }}
                                                    onFocus={e => { e.target.style.borderColor = '#6ee7b7'; e.target.style.boxShadow = '0 0 0 3px rgba(110,231,183,0.2)'; }}
                                                    onBlur={e => { e.target.style.borderColor = '#d1d5db'; e.target.style.boxShadow = 'none'; }}
                                                />
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {/* Output preview */}
                            <div className="rounded-xl p-5" style={{ background: '#fff', border: '1px solid #e5e7eb' }}>
                                <div className="flex justify-between items-center mb-3">
                                    <h3 className="text-xs font-semibold uppercase flex items-center" style={{ color: '#6b7280' }}>
                                        <MessageCircle className="w-4 h-4 mr-1" /> Nội dung gửi khách
                                    </h3>
                                </div>
                                <div className="rounded-lg p-4" style={{ background: '#f9fafb', border: '1px solid #e5e7eb' }}>
                                    <pre className="whitespace-pre-wrap text-sm leading-relaxed" style={{ fontFamily: 'inherit', color: '#1f2328' }}>
                                        {getGeneratedText()}
                                    </pre>
                                </div>
                                <div className="mt-4 flex justify-end">
                                    <button
                                        onClick={copyToClipboard}
                                        className="flex items-center px-5 py-2.5 rounded-lg font-medium text-sm transition-all"
                                        style={{ background: '#2d6a4f', color: '#fff' }}
                                        onMouseEnter={e => ((e.target as HTMLElement).style.background = '#1a3c2e')}
                                        onMouseLeave={e => ((e.target as HTMLElement).style.background = '#2d6a4f')}
                                    >
                                        <Copy className="w-4 h-4 mr-2" />
                                        Copy tin nhắn
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}
                </div>

                {/* ── CHECKLIST FOOTER ── */}
                <div className="flex-shrink-0 p-4" style={{ background: '#fff', borderTop: '1px solid #e5e7eb' }}>
                    <div className="max-w-4xl mx-auto flex items-start gap-3">
                        <div className="p-2 rounded-lg flex-shrink-0" style={{ background: '#fef3c7' }}>
                            <AlertTriangle className="w-4 h-4" style={{ color: '#d97706' }} />
                        </div>
                        <div>
                            <h4 className="text-xs font-bold uppercase mb-2" style={{ color: '#374151' }}>
                                CS Checklist trước khi gửi
                            </h4>
                            <div className="flex flex-wrap gap-x-5 gap-y-1.5">
                                {checklist.map((item, i) => (
                                    <label key={i} className="flex items-center text-xs cursor-pointer" style={{ color: '#6b7280' }}>
                                        <input type="checkbox" className="mr-1.5 rounded" style={{ accentColor: '#2d6a4f' }} />
                                        {item}
                                    </label>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
