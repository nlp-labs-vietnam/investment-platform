# Deploy Guide — GitHub Actions CI/CD

Hướng dẫn thiết lập toàn bộ pipeline CI/CD trên GitHub, không phụ thuộc phần cứng local.

---

## Kiến trúc pipeline

```
push → main
  │
  ├─► CI (ci.yml)
  │     ├─ frontend: pnpm install → lint → typecheck → test → build
  │     └─ backend:  composer → migrate (MySQL service) → phpunit → audit
  │
  ├─► Deploy (deploy.yml)
  │     ├─ build: pnpm build → upload artifact
  │     ├─ deploy: scp + ssh → atomic release swap
  │     └─ smoke: HTTP 200 check
  │
  └─► Security (security.yml)
        ├─ pnpm audit
        ├─ composer audit
        └─ CodeQL (JS + PHP)

push → v* tag
  ├─► Release (release.yml) — GitHub Release + tar.gz artifact
  └─► Publish npm (publish-npm.yml) — npm registry
```

---

## GitHub Secrets cần thiết

Vào: **GitHub repo → Settings → Secrets and variables → Actions → New repository secret**

### Bắt buộc cho Deploy

| Secret | Giá trị | Mô tả |
|--------|---------|-------|
| `DEPLOY_HOST` | `nlpgroup.vn` hoặc IP | Hostname / IP của VPS |
| `DEPLOY_USER` | `deploy` hoặc `www-data` | SSH user trên server |
| `DEPLOY_SSH_KEY` | Nội dung private key `~/.ssh/id_rsa` | SSH private key (không có passphrase) |
| `DEPLOY_PORT` | `22` | SSH port (có thể bỏ qua, mặc định 22) |
| `DEPLOY_PATH` | `/var/www/investment-platform` | Thư mục deploy trên server |

### Bắt buộc cho npm Publish

| Secret | Giá trị | Mô tả |
|--------|---------|-------|
| `NPM_TOKEN` | `npm_xxx...` | npm access token (Automation type) |

### Tùy chọn

| Secret | Giá trị | Mô tả |
|--------|---------|-------|
| `CODECOV_TOKEN` | `xxx` | Codecov upload token |

---

## Tạo SSH deploy key (chạy 1 lần trên VPS)

```bash
# Trên VPS — tạo key không passphrase
ssh-keygen -t ed25519 -C "github-deploy@nlpgroup.vn" -f ~/.ssh/github_deploy -N ""

# Thêm public key vào authorized_keys
cat ~/.ssh/github_deploy.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys

# In private key để copy vào GitHub Secret DEPLOY_SSH_KEY
cat ~/.ssh/github_deploy
```

---

## Cấu trúc thư mục deploy trên VPS

Pipeline dùng mô hình **atomic release swap** (giống Capistrano / Deployer):

```
/var/www/investment-platform/
├── current -> releases/20261021_150000/   ← symlink đến release hiện tại
├── releases/
│   ├── 20261021_150000/                  ← release mới nhất
│   ├── 20261020_120000/                  ← release trước
│   └── 20261019_090000/                  ← (tự động xóa sau 3 release)
└── shared/
    ├── .env                               ← KHÔNG commit vào git
    └── storage/                           ← uploads, logs, cache
```

### Tạo cấu trúc lần đầu trên VPS

```bash
DEPLOY_PATH=/var/www/investment-platform

mkdir -p "${DEPLOY_PATH}/releases"
mkdir -p "${DEPLOY_PATH}/shared/storage/"{app/public,framework/{cache/data,sessions,views},logs}

# Copy .env vào shared
cp "${DEPLOY_PATH}/current/.env" "${DEPLOY_PATH}/shared/.env" 2>/dev/null || \
  cp "${DEPLOY_PATH}/.env.example" "${DEPLOY_PATH}/shared/.env"

# Edit shared .env
nano "${DEPLOY_PATH}/shared/.env"

# Phân quyền
chown -R www-data:developers "${DEPLOY_PATH}/shared"
chmod -R 775 "${DEPLOY_PATH}/shared/storage"
```

---

## Thiết lập sudoers cho deploy user

Pipeline cần reload PHP-FPM và Nginx mà không cần password:

```bash
# Trên VPS, chạy: sudo visudo
# Thêm dòng sau (thay 'deploy' bằng DEPLOY_USER của bạn):

deploy ALL=(ALL) NOPASSWD: /bin/systemctl reload php8.2-fpm, /usr/sbin/nginx, /bin/systemctl reload nginx
```

---

## GitHub Environments (khuyến nghị)

Tạo 2 environments để kiểm soát deploy:

1. **GitHub repo → Settings → Environments → New environment**
2. Tạo `production` — thêm **Required reviewers** (ví dụ: @Dennis-nham)
3. Tạo `staging` — không cần reviewer
4. Thêm secrets riêng cho từng environment nếu cần host khác nhau

---

## Kích hoạt GitHub Pages

1. **GitHub repo → Settings → Pages**
2. **Source**: chọn `GitHub Actions` (không phải "Deploy from a branch")
3. Sau lần push tiếp theo, site sẽ live tại:
   > https://nlp-labs-vietnam.github.io/investment-platform

---

## Tổng hợp Secrets cần set

```
DEPLOY_HOST       = IP hoặc domain VPS
DEPLOY_USER       = tên user SSH
DEPLOY_SSH_KEY    = nội dung ~/.ssh/github_deploy (private key)
DEPLOY_PORT       = 22
DEPLOY_PATH       = /var/www/investment-platform
NPM_TOKEN         = npm_xxxxxxxxxxxx
```

Sau khi set xong, push một commit bất kỳ lên `main` để trigger toàn bộ pipeline.
