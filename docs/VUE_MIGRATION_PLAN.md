# Vue 2 → Vue 3 Migration Plan — NLP Group Platform

## Trạng thái hiện tại (tháng 10/2026)

| Hạng mục | Giá trị |
|---|---|
| Vue version (trước) | 2.6.12 (EOL) |
| Vue version (sau khi nâng cấp) | **2.7.16** (Vue 2 LTS cuối — Aug 2023) |
| Số `new Vue()` instance | 5 (green theme) + 2 (admin) + ~5 (light theme) |
| EOL Vue 2 | 31/12/2023 — không còn security fixes |

## Các Vue instance cần migrate

| File | Instance | Chức năng | Độ phức tạp |
|---|---|---|---|
| `green/layouts/app.blade.php` | `pushNotificationArea` | Push notification (Pusher) + read/mark | 🟡 Trung bình |
| `green/layouts/user.blade.php` | `pushNotificationArea` | Push notification (duplicate) | 🟡 Trung bình |
| `green/user/dashboard.blade.php` | `newApp` (firebase-app) | Firebase permission + skip | 🟢 Đơn giản |
| `green/user/property/offerConversation.blade.php` | `messenger` | Real-time chat (Pusher) | 🔴 Phức tạp |
| `green/property_details.blade.php` | `newApp` | Firebase foreground notification | 🟢 Đơn giản |
| `admin/layouts/app.blade.php` | `pushNotificationArea` | Admin push notification | 🟡 Trung bình |
| `admin/dashboard.blade.php` | `window.newApp` (firebase-app) | Firebase admin | 🟢 Đơn giản |

## Chiến lược đề xuất

### Option A: Migrate từng instance sang Alpine.js (ưu tiên)
- Alpine.js ~15 KB, cú pháp đơn giản, không cần build step
- Phù hợp cho các instance: firebase-app, pushNotificationArea (không quá phức tạp)
- **Thời gian ước tính:** 3–5 ngày dev

### Option B: Nâng cấp lên Vue 3 (Composition API)
- Cần refactor toàn bộ Options API → Composition API
- `createApp()` thay `new Vue()`
- Vue 3 không hỗ trợ `el` option trực tiếp trên instance
- **Thời gian ước tính:** 5–8 ngày dev + test

### Option C: Giữ Vue 2.7.16 — defer migration (hiện tại)
- Vue 2.7.16 = bản LTS ổn định nhất, không có CVE nghiêm trọng
- Rủi ro thấp trong 12–18 tháng tới
- **Quyết định:** Ưu tiên các việc business-critical trước

## Kế hoạch thực hiện (nếu chọn Option A — Alpine.js)

### Phase 1: Low-risk instances (1–2 ngày)
- [ ] `dashboard.blade.php` — firebase-app (chỉ set/get sessionStorage + permission)
- [ ] `property_details.blade.php` — firebase foreground notification

### Phase 2: Notification instances (2–3 ngày)
- [ ] `app.blade.php` (green) — pushNotificationArea (axios + Pusher)
- [ ] `user.blade.php` (green) — pushNotificationArea (identical code)
- [ ] `admin/layouts/app.blade.php` — admin push notification

### Phase 3: Complex instance (2–3 ngày, cần test kỹ)
- [ ] `offerConversation.blade.php` — real-time messenger (Pusher channels)

## Tham khảo

- Vue 2.7 Migration Guide: https://v2.vuejs.org/v2/guide/migration-vue-2-7.html
- Vue 2 → Vue 3 Migration Guide: https://v3-migration.vuejs.org/
- Alpine.js docs: https://alpinejs.dev/
- Vue 2 EOL announcement: https://vuejs.org/about/releases

## Ghi chú kỹ thuật

```js
// Vue 2 pattern hiện tại
new Vue({
  el: '#app',
  data: { items: [] },
  methods: { fetch() { axios.get(...) } }
})

// Alpine.js tương đương
// HTML: <div x-data="notificationData()">
function notificationData() {
  return {
    items: [],
    fetch() { axios.get(...).then(r => this.items = r.data) }
  }
}
```

---
*Cập nhật: tháng 10/2026 | Vue file: `assets/global/js/vue.min.js` v2.7.16*
