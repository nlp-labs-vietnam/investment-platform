import React, { useState, useEffect } from 'react';
import { 
    Bot, 
    Folder, 
    Edit3, 
    Settings, 
    MessageCircle, 
    Copy, 
    AlertTriangle, 
    CheckCircle, 
    MessageSquareDashed 
} from 'lucide-react';

// Dữ liệu kịch bản từ SOP
const sopData = [
    {
        group: "Nhóm: Đăng ký mở tài khoản",
        items: [
            { id: 'g0_1', title: "Yêu cầu thông tin đăng ký", text: "Hello! To register a new merchant account with TC-Gaming, please provide the following details:\n1. Company Name\n2. Target Market / Operating Countries\n3. Contact Person (Telegram/Skype)\n4. Expected launch date\n\nOnce we receive this information, our Business Development team will review and get back to you." },
            { id: 'g0_2', title: "Xác nhận & Chờ duyệt", text: "Thank you for providing the details. We have forwarded your registration request to our Business Development team for review. We will notify you of the result as soon as possible." },
            { id: 'g0_3', title: "Duyệt thành công & Cấp TK", text: "Congratulations! Your merchant account has been successfully created. Here are your details:\n\n- Merchant Code: {{merchant_code}}\n- Backend URL: {{backend_url}}\n- Admin Account: {{admin_account}}\n- Temporary Password: {{password}}\n\n⚠️ Security Reminder: Please log in to the backend and change your password immediately. Please save the credentials and delete this message afterward.", inputs: [{key: 'merchant_code', label: 'Merchant Code'}, {key: 'backend_url', label: 'Backend URL'}, {key: 'admin_account', label: 'Tên Account'}, {key: 'password', label: 'Mật khẩu tạm'}] },
            { id: 'g0_4', title: "Từ chối đăng ký", text: "Thank you for your interest in TC-Gaming. After careful consideration, we regret to inform you that we are unable to approve your registration request at this time. We wish you the best in your business endeavors." }
        ]
    },
    {
        group: "Nhóm 1: Chào hỏi & Điều hướng",
        items: [
            { id: 'g1_1', title: "Chào hỏi ban đầu", text: "Hello, I’m CS from TC-Gaming. How may I assist you today?" },
            { id: 'g1_2', title: "Hướng dẫn khách mới", text: "Welcome to TC-Gaming B2B Support Hub. To start using the system, please follow these steps:\n\n1. Verify your account via Telegram Login Widget.\n2. Select your preferred language: CN or EN.\n3. Use the Quick Actions menu for: Registration, Finance, Support, P2P Exchange & API Dashboard.\n\nIf you need assistance with backend login, API integration, whitelist IP, or finance, please let me know!" },
            { id: 'g1_3', title: "Yêu cầu cung cấp thông tin", text: "To assist you faster, please provide the following information:\n1. Merchant code\n2. Affected account / IP address\n3. Scope: BO, API, or both" }
        ]
    },
    {
        group: "Nhóm 2: Tài khoản & Mật khẩu",
        items: [
            { id: 'g2_1', title: "Hỏi tên TK cần xử lý", text: "Please provide the exact admin account that needs to be unlocked or requires a password reset." },
            { id: 'g2_2', title: "Báo khách chờ xử lý", text: "We’ve forwarded your request to the relevant department. We will reply once there is an update. (Tracking number: {{ticket}})", inputs: [{key: 'ticket', label: 'Mã Ticket (nếu có)'}] },
            { id: 'g2_3', title: "Trả kết quả Reset", text: "Hello, your request has been completed.\n\n- Admin account: {{account}}\n- New temporary password: {{password}}\n\n⚠️ Security Reminder: Please log in to the backend today to reactivate the account and change your password immediately. Otherwise, the system may auto-disable it. Please save the password and delete this message afterward.", inputs: [{key: 'account', label: 'Tên Account'}, {key: 'password', label: 'Mật khẩu mới'}] }
        ]
    },
    {
        group: "Nhóm 3: Quản lý IP Whitelist",
        items: [
            { id: 'g3_1', title: "Hỏi rõ BO hay API", text: "Please confirm whether this IP {{ip}} should be added to BO, API, or both?", inputs: [{key: 'ip', label: 'IP khách gửi'}] },
            { id: 'g3_2', title: "Đã thêm IP thành công", text: "The IP: {{ip}} has been successfully added to the {{scope}} whitelist. Please refresh and try again.", inputs: [{key: 'ip', label: 'IP đã thêm'}, {key: 'scope', label: 'BO / API / BO & API'}] },
            { id: 'g3_3', title: "BO xong, API chờ", text: "The BO whitelist update has been completed. Please review. The API whitelist is still being processed and we will notify you once done." },
            { id: 'g3_4', title: "API IP đầy (Max 20)", text: "The API whitelist IP limit is 20 entries, and your company has reached the maximum limit. Please provide the IP address(es) you would like to remove before we can add the new ones." },
            { id: 'g3_5', title: "Cập nhật IP (Xóa cũ, Thêm mới)", text: "The requested IP update has been completed.\n\n- Removed IP(s): {{old_ip}}\n- Added IP(s): {{new_ip}}\n- Scope: {{scope}}\n\nPlease refresh your system and check again.", inputs: [{key: 'old_ip', label: 'IP đã xóa'}, {key: 'new_ip', label: 'IP mới thêm'}, {key: 'scope', label: 'BO / API / BO & API'}] }
        ]
    },
    {
        group: "Nhóm 4: Kỹ thuật & Tài chính",
        items: [
            { id: 'g4_1', title: "Cấp thông tin tích hợp", text: "Hello, please find your technical information below:\n\n- Backend URL: {{backend}}\n- API URL: {{api}}\n- Merchant API Code: {{code}}\n- Callback URL: {{callback}}\n- Production Domain: {{prod_domain}}\n- Staging Domain: {{stag_domain}}\n\n(If you need password/key re-issuance, please submit a reset request).", inputs: [{key: 'backend', label: 'Backend URL'}, {key: 'api', label: 'API URL'}, {key: 'code', label: 'Merchant Code'}, {key: 'callback', label: 'Callback URL'}, {key: 'prod_domain', label: 'Prod Domain'}, {key: 'stag_domain', label: 'Staging Domain'}] },
            { id: 'g4_2', title: "Báo Final Code (Đã thanh toán)", text: "Hello, here is your finance status:\n\n- Payment Status: Completed at {{time}}\n- Final Code: {{final_code}}\n- Private Telegram Group: {{group}}", inputs: [{key: 'time', label: 'Thời gian'}, {key: 'final_code', label: 'Final Code'}, {key: 'group', label: 'Tên Group Telegram'}] },
            { id: 'g4_3', title: "Chưa thanh toán", text: "Hello, currently the payment has not been completed yet. Therefore, the Final Code has not been issued. Please complete the payment to proceed." }
        ]
    },
    {
        group: "Nhóm 5: Phản hồi nhanh",
        items: [
            { id: 'g5_1', title: "Bảo khách đợi", text: "Please wait a moment while I check this for you." },
            { id: 'g5_2', title: "Đã chuyển IT", text: "I have escalated this issue to our Tech team. I will get back to you as soon as I have an update." },
            { id: 'g5_3', title: "Xác nhận hoàn tất", text: "The issue has been resolved. Please check and let us know if you need further assistance." },
            { id: 'g5_4', title: "Từ chối khéo (Bảo mật)", text: "For security reasons, sensitive credentials can only be shared according to our authorization policy. Please use the registered admin account to request this." }
        ]
    }
];

const checklist = [
    "Đã xác nhận đúng Merchant Code chưa?",
    "Với lỗi IP, đã hỏi rõ thêm vào BO hay API chưa?",
    "Lỗi API IP: đã check xem họ bị quá 20 IP chưa?",
    "Cấp Pass/Key mới: đã dán câu 'Security Reminder' chưa?",
    "Khi báo Done: đã bảo khách 'Please refresh and try again' chưa?"
];

export default function App() {
    const [selectedItem, setSelectedItem] = useState(null);
    const [formData, setFormData] = useState({});
    const [showToast, setShowToast] = useState(false);

    const handleSelectItem = (item) => {
        setSelectedItem(item);
        // Reset form data when selecting a new template
        const initialData = {};
        if (item.inputs) {
            item.inputs.forEach(input => initialData[input.key] = "");
        }
        setFormData(initialData);
    };

    const handleInputChange = (key, value) => {
        setFormData(prev => ({ ...prev, [key]: value }));
    };

    const getGeneratedText = () => {
        if (!selectedItem) return "";
        let text = selectedItem.text;
        if (selectedItem.inputs) {
            selectedItem.inputs.forEach(input => {
                const val = formData[input.key] || `[${input.label.toUpperCase()}]`;
                text = text.replace(new RegExp(`{{${input.key}}}`, 'g'), val);
            });
        }
        return text;
    };

    const copyToClipboard = () => {
        const text = getGeneratedText();
        
        // Sử dụng document.execCommand('copy') thay vì navigator.clipboard
        // để khắc phục lỗi phân quyền (permissions policy) trong iFrame
        const textArea = document.createElement("textarea");
        textArea.value = text;
        
        // Ẩn textarea khỏi màn hình
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            setShowToast(true);
            setTimeout(() => setShowToast(false), 2000);
        } catch (err) {
            console.error('Không thể copy nội dung: ', err);
        } finally {
            textArea.remove();
        }
    };

    return (
        <div className="flex h-screen overflow-hidden bg-gray-50 font-sans">
            <style>{`
                .custom-scrollbar::-webkit-scrollbar { width: 6px; }
                .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
            `}</style>
            
            {/* TOAST NOTIFICATION */}
            {showToast && (
                <div className="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center z-50 transition-opacity">
                    <CheckCircle className="w-5 h-5 mr-2" />
                    Đã copy vào Clipboard!
                </div>
            )}

            {/* SIDEBAR - DANH MỤC */}
            <div className="w-1/3 bg-white border-r border-gray-200 flex flex-col h-full shadow-sm">
                <div className="p-4 bg-blue-700 text-white shadow-md">
                    <h1 className="text-xl font-bold flex items-center">
                        <Bot className="mr-2" />
                        TC-Gaming CS Bot
                    </h1>
                    <p className="text-sm text-blue-100 mt-1">Hỗ trợ giao tiếp chuẩn SOP</p>
                </div>
                
                <div className="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-6">
                    {sopData.map((group, idx) => (
                        <div key={idx} className="mb-4">
                            <h2 className="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center">
                                <Folder className="w-4 h-4 mr-1" /> {group.group}
                            </h2>
                            <div className="space-y-2">
                                {group.items.map(item => (
                                    <button 
                                        key={item.id}
                                        onClick={() => handleSelectItem(item)}
                                        className={`w-full text-left px-3 py-2 rounded-md text-sm transition-colors border ${
                                            selectedItem?.id === item.id 
                                            ? 'bg-blue-50 border-blue-300 text-blue-700 font-medium' 
                                            : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300'
                                        }`}
                                    >
                                        {item.title}
                                    </button>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            {/* MAIN CONTENT - KHU VỰC CHỈNH SỬA & PREVIEW */}
            <div className="w-2/3 flex flex-col h-full">
                
                {/* Khu vực tạo tin nhắn */}
                <div className="flex-1 p-6 overflow-y-auto custom-scrollbar">
                    {!selectedItem ? (
                        <div className="h-full flex flex-col items-center justify-center text-gray-400">
                            <MessageSquareDashed className="w-16 h-16 mb-4 text-gray-300" />
                            <p className="text-lg">Chọn một tình huống bên trái để bắt đầu</p>
                        </div>
                    ) : (
                        <div className="max-w-3xl mx-auto">
                            <h2 className="text-2xl font-bold text-gray-800 mb-6 border-b pb-2 flex items-center">
                                <Edit3 className="mr-2 text-blue-600" />
                                {selectedItem.title}
                            </h2>

                            {/* Form điền biến số */}
                            {selectedItem.inputs && selectedItem.inputs.length > 0 && (
                                <div className="bg-white p-5 rounded-xl shadow-sm border border-gray-200 mb-6">
                                    <h3 className="text-sm font-semibold text-gray-700 mb-4 uppercase flex items-center">
                                        <Settings className="w-4 h-4 mr-1" /> Điền thông tin
                                    </h3>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {selectedItem.inputs.map(input => (
                                            <div key={input.key}>
                                                <label className="block text-xs font-medium text-gray-600 mb-1">{input.label}</label>
                                                <input 
                                                    type="text" 
                                                    value={formData[input.key] || ''}
                                                    onChange={(e) => handleInputChange(input.key, e.target.value)}
                                                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                                    placeholder={`Nhập ${input.label.toLowerCase()}...`}
                                                />
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {/* Kết quả Output */}
                            <div className="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                                <div className="flex justify-between items-center mb-3">
                                    <h3 className="text-sm font-semibold text-gray-700 uppercase flex items-center">
                                        <MessageCircle className="w-4 h-4 mr-1" /> Nội dung gửi cho khách (Tiếng Anh)
                                    </h3>
                                </div>
                                <div className="bg-gray-50 border border-gray-200 rounded-lg p-4 relative group">
                                    <pre className="whitespace-pre-wrap font-sans text-gray-800 text-[15px] leading-relaxed">
                                        {getGeneratedText()}
                                    </pre>
                                </div>
                                
                                <div className="mt-4 flex justify-end">
                                    <button 
                                        onClick={copyToClipboard}
                                        className="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm"
                                    >
                                        <Copy className="w-4 h-4 mr-2" />
                                        Copy tin nhắn
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}
                </div>

                {/* CHECKLIST FOOTER */}
                <div className="bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                    <div className="max-w-4xl mx-auto flex items-start">
                        <div className="bg-red-100 text-red-600 p-2 rounded-lg mr-4 flex-shrink-0">
                            <AlertTriangle className="w-5 h-5" />
                        </div>
                        <div>
                            <h4 className="text-sm font-bold text-gray-800 mb-2 uppercase">CS Checklist trước khi gửi tin:</h4>
                            <div className="flex flex-wrap gap-x-6 gap-y-2">
                                {checklist.map((item, idx) => (
                                    <label key={idx} className="flex items-center text-xs text-gray-600 cursor-pointer hover:text-gray-900">
                                        <input type="checkbox" className="mr-2 w-3.5 h-3.5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" />
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