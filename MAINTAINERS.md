# Danh sách Người bảo trì (MAINTAINERS.md)

> **@nlp-labs-vietnam** — NLP Labs Vietnam Open Source Organization  
> Cấu trúc quản lý lấy cảm hứng từ dự án mã nguồn mở cấp doanh nghiệp (EVerest, Linux Foundation).  
> Mọi thay đổi với tệp này cần được **Project Lead** phê duyệt.

---

## 👑 Project Lead / Core Maintainer

| GitHub Handle | Họ và tên | Vai trò |
|---|---|---|
| **[@Dennis-nham](https://github.com/Dennis-nham)** | NHAM QUOC HUAN | Project Lead · Authorized Signatory, NLP GROUP JSC |

**Trách nhiệm:**
- Quản lý kiến trúc hệ thống tổng thể của NLP Labs
- Đưa ra quyết định cuối cùng về công nghệ và roadmap phát triển
- Review và phê duyệt (Merge) các Pull Request ảnh hưởng đến lõi hệ thống
- Quản lý tất cả repository dưới `@nlp-labs-vietnam` (wildcard `*` trong `CODEOWNERS`)

---

## 🛠 Core Contributors & Module Owners

### 1. [@thekingbao](https://github.com/thekingbao) — Phạm Thế Bảo

**Vai trò:** Tech Lead · Infrastructure & UI/UX · Documentation

| Phụ trách | Đường dẫn |
|---|---|
| Frontend / UI components | `/frontend/`, `/packages/core/`, `/packages/react/`, `/packages/vue/` |
| Infrastructure & CI/CD | `/infrastructure/`, `.github/`, `turbo.json`, `pnpm-workspace.yaml` |
| Documentation & Wiki | `/docs/`, `/apps/docs/`, `*.md` |
| Design Tokens | `/packages/tokens/`, `/packages/icons/` |

**Trách nhiệm:**
- Xây dựng và duy trì kiến trúc Full-stack (Frontend/Backend)
- Quản lý hạ tầng máy chủ (Nginx, PM2, Monorepo CI/CD via Turborepo)
- Thiết kế UI/UX theo chuẩn Apple HIG (`ios-design-system`)
- Duy trì tài liệu chuẩn hóa và Wiki của tổ chức

---

### 2. [@nhamlapquoc](https://github.com/nhamlapquoc) — Nhâm Lập Quốc

**Vai trò:** Core NLP Engineer · AI/ML Maintainer

| Phụ trách | Đường dẫn |
|---|---|
| NLP core algorithms | `/core-nlp/`, `/models/` |
| Data pipeline | `/pipeline/`, `/data/` |
| AI microservices | `/services/nlp-*/`, `/services/sentiment/` |
| Research notebooks | `/research/`, `/notebooks/` |

**Trách nhiệm:**
- Nghiên cứu, phát triển và tối ưu hóa các module/mô hình NLP
- Viết và duy trì mã nguồn cốt lõi (Core algorithms, Transformer fine-tuning)
- Xây dựng Data Pipeline (Kafka ingestion → RabbitMQ routing → NLP workers)
- Đảm bảo chất lượng mã nguồn cho các tính năng AI

---

### 3. [@wikialisalabs](https://github.com/wikialisalabs) — Wikialisa Labs

**Vai trò:** Organization Admin · Satellite Resources · Package Ecosystem

| Phụ trách | Đường dẫn |
|---|---|
| Satellite packages | `/packages/wikialisa-labs/`, `/packages/blade/` |
| Parsers & Tooling | `/tools/`, `/parsers/` |
| Open educational resources | `/education/`, `/standards/` |
| Package registry | `.npmrc`, `package.json` (root) |

**Trách nhiệm:**
- Quản lý kho lưu trữ vệ tinh và cấu trúc gói (`@alisababivip/*` namespace)
- Quản lý các Parser (Tree-sitter), công cụ tự động hóa
- Duy trì các tiêu chuẩn giáo dục mở tích hợp vào dự án
- Quản lý `.npmrc` và phát hành gói lên npm registry

---

## 📋 Quy trình Review

```
PR mở
 └─→ Module owner review (trong vòng 48 giờ)
       └─→ @Dennis-nham final approval (với PRs ảnh hưởng kiến trúc)
             └─→ Merge vào `main`
```

- **Hotfix:** Có thể merge trực tiếp với 1 approval từ bất kỳ Core Contributor
- **Feature PR:** Cần ít nhất 1 approval từ Module Owner phụ trách + Project Lead
- **Architecture PR:** Bắt buộc Project Lead review

---

## 📜 Lịch sử

| Ngày | Thay đổi |
|---|---|
| 2023-10-25 | Khởi tạo NLP GROUP JSC — Authorized Signatory: NHAM QUOC HUAN |
| 2024 | Thành lập `@nlp-labs-vietnam` GitHub Organization |
| 2025 | Ra mắt `ios-design-system` (Turborepo monorepo, `@alisababivip/*`) |
| 2025 | Phân bổ vai trò MAINTAINERS theo chuẩn EVerest/Linux Foundation |
