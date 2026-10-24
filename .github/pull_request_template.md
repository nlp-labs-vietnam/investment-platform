## Mô tả
<!-- Thay đổi này làm gì? Tại sao cần thiết? Closes #issue_number nếu có -->

## Loại thay đổi

- [ ] 🐛 `fix` — Bug fix
- [ ] ✨ `feat` — Tính năng mới
- [ ] 💥 `feat!` — Breaking change
- [ ] 📝 `docs` — Tài liệu
- [ ] ♻️ `refactor` — Refactor (không thêm tính năng / sửa lỗi)
- [ ] ⚡ `perf` — Cải thiện hiệu suất
- [ ] 🔧 `chore` — Build, dependencies, config
- [ ] 🚀 `ci` — CI/CD

## Checklist

### Code quality
- [ ] `pnpm lint` — không có lỗi ESLint
- [ ] `pnpm typecheck` — TypeScript không có lỗi
- [ ] `pnpm test` — tất cả tests pass
- [ ] `pnpm build` — build thành công
- [ ] `php artisan test` — PHPUnit/Pest tests pass

### Backend
- [ ] Migration đi kèm nếu thay đổi schema
- [ ] Không có N+1 query mới (dùng `->with()` khi cần)
- [ ] API response format giữ nguyên hoặc versioned

### Security
- [ ] Không log/expose credentials, PII, secrets
- [ ] Input validation đầy đủ (Form Requests)
- [ ] Authorization check (Policies / Gates)

### Documentation
- [ ] JSDoc / type annotations cập nhật nếu thay đổi public API
- [ ] CHANGELOG.md cập nhật (hoặc tự động qua conventional commits)

## Screenshots / Demo (nếu thay đổi UI)
<!-- Thêm before/after screenshots hoặc screen recording -->

## Notes cho reviewer
<!-- Bất kỳ thông tin nào cần reviewer chú ý -->
