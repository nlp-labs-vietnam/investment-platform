---
name: github-release
description: Use when the user wants to push code to GitHub, create a release tag, publish npm package, check sponsors, or interact with the GitHub API for the nlp-labs-vietnam/investment-platform repo.
---

# GitHub Release & Publish — NLP Labs Vietnam

Skill này hướng dẫn quy trình đầy đủ từ commit → tag → GitHub release → npm publish cho repo `nlp-labs-vietnam/investment-platform`.

## Thông tin cấu hình

| Biến | Nguồn | Giá trị |
|---|---|---|
| `GITHUB_TOKEN` | `.env` | Personal Access Token (PAT) |
| `GITHUB_REPO` | `.env` | `nlp-labs-vietnam/investment-platform` |
| `GITHUB_OWNER` | `.env` | `nlp-labs-vietnam` |
| `GITHUB_SPONSOR_URL` | `.env` | `https://github.com/sponsors/Dennis-nham/…` |

**Đọc token từ `.env`** — không bao giờ hardcode vào code hoặc SKILL.md.

---

## Bước 1 — Kiểm tra trạng thái repo

Chạy lệnh để xem git status và log mới nhất:

```bash
cd ./nlp-labs-vietnam
git status --short
git log --oneline -5
git tag | sort -V | tail -5
```

Dùng `execute_command` để thực thi và báo cáo kết quả.

---

## Bước 2 — Commit các thay đổi chưa commit

Nếu có file modified/untracked cần đưa vào release:

```bash
git add <files>
git commit -m "feat|fix|chore|docs: <mô tả ngắn>

<chi tiết nếu cần>"
```

Tuân theo **Conventional Commits**: `feat`, `fix`, `chore`, `docs`, `refactor`, `style`, `test`.

---

## Bước 3 — Tạo tag version mới

Xác định version tiếp theo theo **semver** (MAJOR.MINOR.PATCH):
- `PATCH` — bugfix, hotfix, chore
- `MINOR` — tính năng mới không breaking
- `MAJOR` — breaking change

```bash
# Xem tag hiện tại
git tag | sort -V | tail -3

# Tạo tag annotated
git tag -a v1.x.x -m "Release v1.x.x — <tóm tắt>"

# Push tag lên GitHub (trigger CI/CD publish-npm.yml)
git push origin v1.x.x
```

---

## Bước 4 — Tạo GitHub Release qua API

Sau khi tag đã push, tạo Release chính thức trên GitHub:

```bash
# Đọc token từ .env
GITHUB_TOKEN=$(grep '^GITHUB_TOKEN=' ./nlp-labs-vietnam/.env | cut -d'=' -f2)

curl -s -X POST \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github+json" \
  https://api.github.com/repos/nlp-labs-vietnam/investment-platform/releases \
  -d '{
    "tag_name": "v1.x.x",
    "name": "v1.x.x — <tên release>",
    "body": "## Thay đổi\n- feat: ...\n- fix: ...",
    "draft": false,
    "prerelease": false
  }'
```

Điền `tag_name`, `name`, `body` từ context thực tế trước khi chạy.

---

## Bước 5 — Kiểm tra GitHub Actions CI/CD

Sau khi push tag, GitHub Actions sẽ tự động chạy `.github/workflows/publish-npm.yml`:

```bash
GITHUB_TOKEN=$(grep '^GITHUB_TOKEN=' ./nlp-labs-vietnam/.env | cut -d'=' -f2)

# Liệt kê workflow runs mới nhất
curl -s \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github+json" \
  "https://api.github.com/repos/nlp-labs-vietnam/investment-platform/actions/runs?per_page=5" \
  | python3 -c "
import json,sys
data=json.load(sys.stdin)
for r in data.get('workflow_runs',[]):
    print(r['status'], r['conclusion'], r['name'], r['created_at'])
"
```

---

## Bước 6 — Push code lên GitHub

Đẩy toàn bộ commit chưa sync:

```bash
git push origin main
```

Nếu remote chưa được cấu hình với token (HTTPS):

```bash
GITHUB_TOKEN=$(grep '^GITHUB_TOKEN=' ./nlp-labs-vietnam/.env | cut -d'=' -f2)
git remote set-url origin "https://${GITHUB_TOKEN}@github.com/nlp-labs-vietnam/investment-platform.git"
git push origin main
```

---

## Bước 7 — Kiểm tra Sponsor (tùy chọn)

Xem trạng thái GitHub Sponsors cho tài khoản liên kết:

```bash
GITHUB_TOKEN=$(grep '^GITHUB_TOKEN=' ./nlp-labs-vietnam/.env | cut -d'=' -f2)
SPONSOR_URL=$(grep '^GITHUB_SPONSOR_URL=' ./nlp-labs-vietnam/.env | cut -d'=' -f2-)

echo "Sponsor URL: $SPONSOR_URL"

# Kiểm tra thông tin user qua API
curl -s \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github+json" \
  https://api.github.com/user \
  | python3 -c "import json,sys; u=json.load(sys.stdin); print('Login:', u.get('login'), '| Plan:', u.get('plan',{}).get('name','free'))"
```

---

## Bước 8 — Cập nhật .env.example

Sau khi thêm biến mới vào `.env`, đồng bộ key (không có value) vào `.env.example`:

```bash
# Kiểm tra các key có trong .env nhưng chưa có trong .env.example
comm -23 \
  <(grep -oP '^[A-Z_]+(?==)' ./nlp-labs-vietnam/.env | sort) \
  <(grep -oP '^[A-Z_]+(?==)' ./nlp-labs-vietnam/.env.example | sort)
```

Thêm các key còn thiếu vào `.env.example` với value rỗng.

---

## Lưu ý bảo mật

- **Không bao giờ** commit `.env` lên git (đã có trong `.gitignore`)
- **Không bao giờ** hardcode token vào source code hoặc workflow file
- Token trong `.env` chỉ dùng trên server production — dùng GitHub Secrets (`NPM_TOKEN`, `GH_TOKEN`) cho CI/CD
- Nếu token bị lộ trong chat/log → **revoke ngay** tại [github.com/settings/tokens](https://github.com/settings/tokens) và tạo token mới
