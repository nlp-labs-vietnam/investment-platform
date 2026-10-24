#!/usr/bin/env bash
# =============================================================================
# backup-db.sh — Backup database MySQL NLP Group Platform
#                Chạy tự động qua cron, lưu file .sql.gz
#
# Lưu trữ: /var/backups/nlpgroup-db/
# Giữ lại: 7 bản gần nhất (tự xóa bản cũ hơn)
#
# Cách dùng thủ công:
#   bash backup-db.sh
#
# Cách thêm vào cron (chạy lúc 2:30 AM hàng ngày):
#   30 2 * * * ./nlp-labs-vietnam/backup-db.sh >> /var/log/nlpgroup-db-backup.log 2>&1
#
# Tác giả: NLP Group <dev@nlpgroup.vn>
# =============================================================================

set -euo pipefail

# ── Cấu hình ──────────────────────────────────────────────────────────────────
APP_DIR="./nlp-labs-vietnam"
BACKUP_DIR="/var/backups/nlpgroup-db"
KEEP_DAYS=7
TIMESTAMP="$(date +%Y%m%d_%H%M%S)"
LOG_PREFIX="[$(date '+%Y-%m-%d %H:%M:%S')]"

# ── Đọc thông tin DB từ .env ───────────────────────────────────────────────────
ENV_FILE="${APP_DIR}/.env"
if [[ ! -f "$ENV_FILE" ]]; then
    echo "${LOG_PREFIX} [✗] Không tìm thấy .env tại ${ENV_FILE}" >&2
    exit 1
fi

get_env() {
    grep -m1 "^${1}=" "$ENV_FILE" | cut -d'=' -f2- | tr -d '"' | tr -d "'"
}

DB_HOST="$(get_env DB_HOST)"
DB_PORT="$(get_env DB_PORT)"
DB_DATABASE="$(get_env DB_DATABASE)"
DB_USERNAME="$(get_env DB_USERNAME)"
DB_PASSWORD="$(get_env DB_PASSWORD)"

DB_PORT="${DB_PORT:-3306}"

if [[ -z "$DB_DATABASE" || -z "$DB_USERNAME" ]]; then
    echo "${LOG_PREFIX} [✗] Thiếu thông tin DB trong .env (DB_DATABASE, DB_USERNAME)" >&2
    exit 1
fi

# ── Kiểm tra mysqldump ────────────────────────────────────────────────────────
if ! command -v mysqldump &>/dev/null; then
    echo "${LOG_PREFIX} [✗] Lệnh 'mysqldump' chưa được cài đặt." >&2
    exit 1
fi

# ── Tạo thư mục backup ────────────────────────────────────────────────────────
mkdir -p "$BACKUP_DIR"

BACKUP_FILE="${BACKUP_DIR}/nlpgroup-db-${TIMESTAMP}.sql.gz"

# ── Thực hiện backup ──────────────────────────────────────────────────────────
echo "${LOG_PREFIX} [→] Bắt đầu backup database: ${DB_DATABASE}@${DB_HOST}:${DB_PORT}"

MYSQL_PWD="$DB_PASSWORD" mysqldump \
    --host="${DB_HOST}" \
    --port="${DB_PORT}" \
    --user="${DB_USERNAME}" \
    --single-transaction \
    --routines \
    --triggers \
    --add-drop-table \
    "${DB_DATABASE}" \
    | gzip -9 > "$BACKUP_FILE"

SIZE=$(du -sh "$BACKUP_FILE" | cut -f1)
echo "${LOG_PREFIX} [✔] Đã lưu: ${BACKUP_FILE} (${SIZE})"

# ── Xóa backup cũ hơn KEEP_DAYS ngày ─────────────────────────────────────────
DELETED=$(find "$BACKUP_DIR" -name "nlpgroup-db-*.sql.gz" -mtime +${KEEP_DAYS} -print -delete | wc -l)
if [[ "$DELETED" -gt 0 ]]; then
    echo "${LOG_PREFIX} [→] Đã xóa ${DELETED} bản backup cũ (>${KEEP_DAYS} ngày)"
fi

# ── Liệt kê các bản hiện có ───────────────────────────────────────────────────
COUNT=$(find "$BACKUP_DIR" -name "nlpgroup-db-*.sql.gz" | wc -l)
echo "${LOG_PREFIX} [✔] Tổng số bản backup hiện có: ${COUNT}"
echo "${LOG_PREFIX} [✔] Backup hoàn tất."
