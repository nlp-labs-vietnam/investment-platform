# 🤝 Contributing to NLP Labs Vietnam

> Cảm ơn bạn đã quan tâm đến việc đóng góp cho **@nlp-labs-vietnam**!  
> Tài liệu này áp dụng cho tất cả repository trong tổ chức.

---

## 🗺 Mục lục

1. [Quy tắc ứng xử](#-quy-tắc-ứng-xử)
2. [Cách bắt đầu](#-cách-bắt-đầu)
3. [Quy trình đóng góp](#-quy-trình-đóng-góp)
4. [Commit Convention](#-commit-convention)
5. [Pull Request checklist](#-pull-request-checklist)
6. [Phân quyền review](#-phân-quyền-review-codeowners)
7. [Báo cáo lỗi](#-báo-cáo-lỗi)
8. [Đề xuất tính năng](#-đề-xuất-tính-năng)

---

## 📋 Quy tắc ứng xử

Tất cả thành viên và người đóng góp phải tuân thủ [CODE_OF_CONDUCT.md](./CODE_OF_CONDUCT.md).  
Tóm tắt: **tôn trọng, xây dựng, không phân biệt**.

---

## 🚀 Cách bắt đầu

### Yêu cầu môi trường

```bash
node  --version   # ≥ 20
pnpm  --version   # ≥ 9
git   --version   # ≥ 2.38
```

### Clone & cài đặt

```bash
# Fork repo trên GitHub, sau đó clone fork của bạn
git clone https://github.com/<your-username>/<repo-name>.git
cd <repo-name>

# Cài dependencies
pnpm install

# Build toàn bộ workspace
pnpm build

# Chạy dev mode
pnpm dev
```

### Cấu trúc workspace

```
packages/   ← Shared libraries — đóng góp vào đây nếu thêm logic tái sử dụng
apps/       ← Deployable apps — UI, docs, showcase
services/   ← Backend microservices (investment-platform)
```

---

## 🔄 Quy trình đóng góp

```
1. Fork repo
      ↓
2. Tạo branch từ main
   git checkout -b feat/ten-tinh-nang
      ↓
3. Viết code + tests
      ↓
4. Commit theo Conventional Commits
      ↓
5. Push lên fork
   git push origin feat/ten-tinh-nang
      ↓
6. Mở Pull Request → main
      ↓
7. Module owner review (≤ 48h)
      ↓
8. @Dennis-nham final approval (nếu ảnh hưởng kiến trúc)
      ↓
9. Squash & Merge
```

### Đặt tên branch

| Loại | Pattern | Ví dụ |
|---|---|---|
| Tính năng mới | `feat/<mô-tả>` | `feat/solar-rag-pipeline` |
| Sửa lỗi | `fix/<mô-tả>` | `fix/tokenizer-unicode-crash` |
| Tài liệu | `docs/<mô-tả>` | `docs/update-architecture-diagram` |
| Refactor | `refactor/<mô-tả>` | `refactor/kafka-consumer-retry` |
| Hotfix | `hotfix/<mô-tả>` | `hotfix/api-key-leak` |
| Release | `release/<version>` | `release/v1.2.0` |

---

## ✍️ Commit Convention

Dự án dùng **Conventional Commits** — changelog và versioning được tạo tự động.

### Cú pháp

```
<type>(<scope>): <mô tả ngắn>

[body tùy chọn]

[footer tùy chọn: BREAKING CHANGE, Closes #issue]
```

### Types

| Type | Khi nào dùng |
|---|---|
| `feat` | Thêm tính năng mới |
| `fix` | Sửa lỗi |
| `docs` | Thay đổi tài liệu |
| `style` | Formatting, không thay đổi logic |
| `refactor` | Refactor code, không thêm tính năng / sửa lỗi |
| `perf` | Cải thiện hiệu suất |
| `test` | Thêm hoặc sửa tests |
| `chore` | Build process, dependency updates |
| `ci` | Thay đổi CI/CD config |

### Scopes (theo repo)

| Repo | Scopes thường dùng |
|---|---|
| `ios-design-system` | `tokens`, `core`, `react`, `vue`, `blade`, `icons`, `docs` |
| `investment-platform` | `ingestion`, `sentiment`, `ner`, `orchestrator`, `gateway`, `dashboard`, `sdk` |
| `core-nlp` | `models`, `pipeline`, `research`, `vi-tokenizer` |
| `wikialisa-labs` | `tree-sitter`, `standards`, `tools` |

### Ví dụ commit hợp lệ

```bash
feat(tokens): add iOS 17 dynamic color palette
fix(sentiment): handle null response from RabbitMQ worker
docs(architecture): update data flow diagram
refactor(ingestion): migrate Kafka consumer to async iterator
perf(vi-tokenizer): reduce syllable split latency by 40%
chore(deps): bump pnpm to 9.5.0
ci: add CodeQL security scan to workflow

# Breaking change:
feat(sdk)!: rename SentimentScore to SignalScore

BREAKING CHANGE: SentimentScore interface has been renamed to
SignalScore. Update all import statements accordingly.
```

---

## ✅ Pull Request Checklist

Trước khi mở PR, kiểm tra:

```
Code quality:
[ ] pnpm lint        — không có lỗi ESLint / Stylelint
[ ] pnpm typecheck   — TypeScript không có lỗi
[ ] pnpm test        — tất cả tests pass
[ ] pnpm build       — build thành công

Documentation:
[ ] Cập nhật JSDoc / type annotations nếu thay đổi public API
[ ] Cập nhật CHANGELOG.md (hoặc để tự động qua conventional commits)
[ ] Thêm / cập nhật docs trong apps/docs nếu cần

PR description:
[ ] Tiêu đề theo Conventional Commits format
[ ] Mô tả rõ vấn đề đang giải quyết (link issue nếu có)
[ ] Screenshots / screen recordings nếu thay đổi UI
[ ] Đánh dấu là Draft nếu chưa sẵn sàng review
```

### Template PR description

```markdown
## Mô tả
<!-- Thay đổi này làm gì? Tại sao cần thiết? -->

## Loại thay đổi
- [ ] 🐛 Bug fix
- [ ] ✨ New feature
- [ ] 💥 Breaking change
- [ ] 📝 Documentation
- [ ] ♻️ Refactor
- [ ] ⚡ Performance

## Liên quan
<!-- Closes #issue_number -->

## Screenshots (nếu có UI)

## Checklist
- [ ] Tests pass (`pnpm test`)
- [ ] Lint pass (`pnpm lint`)
- [ ] Build pass (`pnpm build`)
```

---

## 👥 Phân quyền review (CODEOWNERS)

Pull Request sẽ tự động assign reviewer theo [`.github/CODEOWNERS`](../.profile/CODEOWNERS):

| Phạm vi | Reviewer chính | Reviewer cuối |
|---|---|---|
| Toàn bộ (`*`) | — | @Dennis-nham |
| `/packages/tokens/`, `/packages/core/`, `/packages/react/`, `/packages/vue/` | @thekingbao | @Dennis-nham |
| `/infrastructure/`, `.github/`, `*.md` | @thekingbao | @Dennis-nham |
| `/core-nlp/`, `/models/`, `/pipeline/`, `/services/nlp-*/` | @nhamlapquoc | @Dennis-nham |
| `/packages/wikialisa-labs/`, `/tools/`, `/parsers/` | @wikialisalabs | @Dennis-nham |

**SLA review:** Module owner phản hồi trong **48 giờ**. Nếu quá hạn, tag `@Dennis-nham` để escalate.

---

## 🐛 Báo cáo lỗi

1. Kiểm tra [Issues](https://github.com/nlp-labs-vietnam) — lỗi đã được báo cáo chưa?
2. Nếu chưa, mở **New Issue** → chọn template **Bug Report**
3. Điền đầy đủ:
   - Môi trường: OS, Node version, pnpm version, browser (nếu UI)
   - Bước tái hiện
   - Hành vi mong đợi vs thực tế
   - Logs / screenshots

---

## 💡 Đề xuất tính năng

1. Mở **New Issue** → chọn template **Feature Request**
2. Mô tả:
   - Bài toán đang gặp phải
   - Giải pháp đề xuất
   - Alternatives đã xem xét
3. Maintainer sẽ label và triage trong vòng **1 tuần**

---

## 📬 Liên hệ

- **GitHub Discussions:** Câu hỏi kỹ thuật, ý tưởng
- **Issues:** Bug reports, feature requests
- **Email:** enterprise@nlpgroup.vn (hợp tác doanh nghiệp)
- **Project Lead:** [@Dennis-nham](https://github.com/Dennis-nham)

---

<div align="center">
<sub>NLP Group · Kiến tạo hệ sinh thái đổi mới bền vững · Cảm ơn vì mọi đóng góp 💚</sub>
</div>
