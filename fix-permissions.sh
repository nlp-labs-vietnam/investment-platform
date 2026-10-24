#!/usr/bin/env bash
# =============================================================================
# fix-permissions.sh — Investment Platform Permission Reset Script
# =============================================================================
# Phân quyền chuẩn cho môi trường production Laravel
#
# NOTE: Tính năng này đã được tích hợp vào install.sh v3.0.0+
#   Khuyến nghị dùng: sudo bash install.sh permissions [--dir PATH]
#   Script này vẫn hoạt động độc lập như một shortcut nhanh.
#
# Mô hình phân quyền:
#   Owner : www-data  (web server user)
#   Group : developers (dev team, CI/CD)
#   Other : r-- / r-x (không có write)
#
# Vai trò:
#   root        → chạy script này, quản lý hệ thống
#   www-data    → chạy PHP, đọc/ghi storage, bootstrap/cache
#   developers  → group chung cho dev team, CI/CD pipelines
#   other       → chỉ đọc (end-user qua nginx/apache)
#
# Cách dùng:
#   sudo bash fix-permissions.sh [/path/to/app]
#   sudo bash install.sh permissions [--dir /path/to/app]   ← khuyến nghị
# =============================================================================

set -euo pipefail

APP="${1:-/var/www/investment-platform}"
WEB_USER="www-data"
DEV_GROUP="developers"

# Kiểm tra quyền root
if [[ $EUID -ne 0 ]]; then
  echo "❌ Script này phải chạy với quyền root (sudo)" >&2
  exit 1
fi

# Kiểm tra thư mục tồn tại
if [[ ! -d "$APP" ]]; then
  echo "❌ Thư mục không tồn tại: $APP" >&2
  exit 1
fi

echo "=== Investment Platform: Fix Permissions ==="
echo "    App path  : $APP"
echo "    Web user  : $WEB_USER"
echo "    Dev group : $DEV_GROUP"
echo ""

# --- 1. Tạo group developers nếu chưa có ---
if ! getent group "$DEV_GROUP" > /dev/null 2>&1; then
  groupadd "$DEV_GROUP"
  echo "✓ Đã tạo group: $DEV_GROUP"
else
  echo "✓ Group đã tồn tại: $DEV_GROUP"
fi

# Thêm www-data vào group developers
usermod -aG "$DEV_GROUP" "$WEB_USER"
echo "✓ $WEB_USER đã thuộc group $DEV_GROUP"

# --- 2. Ownership toàn bộ project ---
echo ""
echo "[1/6] Set ownership → $WEB_USER:$DEV_GROUP ..."
chown -R "$WEB_USER:$DEV_GROUP" "$APP"
echo "      ✓ Done"

# --- 3. Reset quyền cơ bản (bỏ qua node_modules và .git để nhanh) ---
echo ""
echo "[2/6] Set quyền cơ bản: thư mục=755, tệp=644 ..."
# Thư mục
find "$APP" \
  -not \( -path "$APP/.git" -prune \) \
  -not \( -path "$APP/node_modules" -prune \) \
  -type d -exec chmod 755 {} \;
# Tệp
find "$APP" \
  -not \( -path "$APP/.git" -prune \) \
  -not \( -path "$APP/node_modules" -prune \) \
  -type f -exec chmod 644 {} \;
# .git: chỉ owner
chmod -R u+rwX,go-rwx "$APP/.git"
echo "      ✓ Done"

# --- 4. Thư mục & file writable bởi www-data + developers ---
echo ""
echo "[3/6] Set writable directories: storage, bootstrap/cache → 775/664 + setgid ..."
# storage/
find "$APP/storage" -type d -exec chmod 775 {} \;
find "$APP/storage" -type d -exec chmod g+s {} \;
find "$APP/storage" -type f -exec chmod 664 {} \;
# bootstrap/cache/
chmod 775 "$APP/bootstrap/cache"
chmod g+s "$APP/bootstrap/cache"
find "$APP/bootstrap/cache" -type f -exec chmod 664 {} \;
# exports/
[[ -d "$APP/exports" ]]       && { chmod 775 "$APP/exports";        chmod g+s "$APP/exports"; }
[[ -d "$APP/public/exports" ]] && { chmod 775 "$APP/public/exports"; chmod g+s "$APP/public/exports"; }
echo "      ✓ Done"

# --- 5. File nhạy cảm: chỉ owner+group đọc ---
echo ""
echo "[4/6] Set file nhạy cảm → 640 ..."
[[ -f "$APP/.env" ]]                  && chmod 640 "$APP/.env"
[[ -f "$APP/firebase-service.json" ]] && chmod 640 "$APP/firebase-service.json"
find "$APP" -maxdepth 1 -name "*.sql" -exec chmod 640 {} \;
# Backup tar.gz trong public/
find "$APP/public" -maxdepth 2 -name "*.tar.gz" -exec chmod 640 {} \;
find "$APP/public" -maxdepth 1 -type d -name "backup_*" -exec chmod 750 {} \;
echo "      ✓ Done"

# --- 6. File executable ---
echo ""
echo "[5/6] Set file executable → 750 ..."
[[ -f "$APP/artisan" ]]  && chmod 750 "$APP/artisan"
find "$APP" -maxdepth 1 -name "*.sh" -exec chmod 750 {} \;
echo "      ✓ Done"

# --- 7. Kiểm tra Laravel có thể ghi ---
echo ""
echo "[6/6] Kiểm tra write access của $WEB_USER ..."
TMPFILE="$APP/storage/logs/.permission_test_$$"
if sudo -u "$WEB_USER" touch "$TMPFILE" 2>/dev/null; then
  rm -f "$TMPFILE"
  echo "      ✓ $WEB_USER có thể ghi vào storage/logs/"
else
  echo "      ⚠ $WEB_USER KHÔNG thể ghi vào storage/logs/ — kiểm tra lại!" >&2
fi

echo ""
echo "=== Hoàn tất! Tóm tắt phân quyền ==="
echo ""
printf "%-50s %-10s %-15s %s\n" "Đường dẫn" "Quyền" "Owner:Group" "Ghi chú"
printf "%-50s %-10s %-15s %s\n" "--------------------------------------------------" "----------" "---------------" "---------"

dirs_to_check=(
  "$APP"
  "$APP/app"
  "$APP/config"
  "$APP/public"
  "$APP/storage"
  "$APP/storage/logs"
  "$APP/bootstrap/cache"
  "$APP/exports"
)
for d in "${dirs_to_check[@]}"; do
  [[ -e "$d" ]] && printf "%-50s %-10s %-15s %s\n" \
    "${d#$APP/}" \
    "$(stat -c '%A' "$d")" \
    "$(stat -c '%U:%G' "$d")" \
    "$(stat -c '%n' "$d" | xargs -I{} basename {})"
done

echo ""
files_to_check=(
  "$APP/.env"
  "$APP/artisan"
  "$APP/firebase-service.json"
)
for f in "${files_to_check[@]}"; do
  [[ -f "$f" ]] && printf "%-50s %-10s %-15s %s\n" \
    "${f#$APP/}" \
    "$(stat -c '%A' "$f")" \
    "$(stat -c '%U:%G' "$f")" \
    ""
done

echo ""
echo "✅ fix-permissions.sh hoàn thành!"
