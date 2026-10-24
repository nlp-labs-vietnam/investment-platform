#!/usr/bin/env bash
# =============================================================================
# backup.sh — Đóng gói mã nguồn NLP Group Platform thành file ZIP
#             sẵn sàng upload lên cPanel / Cloud Hosting
#
# Kết quả: ~/nlpgroup-platform-YYYYMMDD_HHMMSS.zip
#
# Cách dùng:
#   chmod +x backup.sh && bash backup.sh
#
# Tác giả: NLP Group <dev@nlpgroup.vn>
# Phiên bản: 1.0.0
# =============================================================================

set -euo pipefail

# ── Màu sắc ───────────────────────────────────────────────────────────────────
RED='\033[0;31m';  GREEN='\033[0;32m';  YELLOW='\033[1;33m'
BLUE='\033[0;34m'; CYAN='\033[0;36m';   BOLD='\033[1m';  RESET='\033[0m'

log()  { echo -e "${GREEN}[✔]${RESET} $*"; }
info() { echo -e "${BLUE}[→]${RESET} $*"; }
warn() { echo -e "${YELLOW}[!]${RESET} $*"; }
die()  { echo -e "${RED}[✗]${RESET} $*" >&2; exit 1; }
step() { echo -e "\n${BOLD}${CYAN}══════════════════════════════════════${RESET}"
         echo -e "${BOLD}${CYAN}  $*${RESET}"
         echo -e "${BOLD}${CYAN}══════════════════════════════════════${RESET}"; }

# ── Cấu hình ──────────────────────────────────────────────────────────────────
SOURCE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TIMESTAMP="$(date +%Y%m%d_%H%M%S)"
OUTPUT_NAME="nlpgroup-platform-${TIMESTAMP}.zip"
OUTPUT_DIR="${HOME}"
OUTPUT_FILE="${OUTPUT_DIR}/${OUTPUT_NAME}"

# Các thư mục/file bị loại trừ khỏi backup
EXCLUDE_PATTERNS=(
    # Dependencies (sẽ cài lại qua composer/npm)
    "node_modules"
    "vendor"

    # Git & CI
    ".git"
    ".github"
    ".gitignore"

    # Build artifacts
    "public/hot"
    "public/build"

    # Logs & cache (không cần thiết)
    "storage/logs/*.log"
    "storage/framework/cache/data/*"
    "storage/framework/sessions/*"
    "storage/framework/views/*"
    "bootstrap/cache/*.php"

    # File nhạy cảm môi trường
    ".env"

    # Editor & IDE
    ".vscode"
    ".idea"
    "*.DS_Store"
    "Thumbs.db"

    # Backup script output
    "*.zip"
    "*.tar.gz"

    # Công cụ phát triển nội bộ
    ".snapshots"
    ".bob"
    "nodesource_setup.sh"
    "exports"
)

# ── Kiểm tra công cụ zip ──────────────────────────────────────────────────────
check_deps() {
    if ! command -v zip &>/dev/null; then
        die "Lệnh 'zip' chưa được cài. Cài đặt: sudo apt install zip  hoặc  sudo yum install zip"
    fi
}

# ── Xây dựng tham số exclude ──────────────────────────────────────────────────
build_exclude_args() {
    EXCLUDE_ARGS=()
    for pattern in "${EXCLUDE_PATTERNS[@]}"; do
        EXCLUDE_ARGS+=("--exclude=${SOURCE_DIR}/${pattern}")
        EXCLUDE_ARGS+=("--exclude=*/${pattern}/*")
        EXCLUDE_ARGS+=("--exclude=*/${pattern}")
    done
}

# ── Đóng gói ──────────────────────────────────────────────────────────────────
create_zip() {
    step "Đóng gói mã nguồn → ${OUTPUT_FILE}"

    # Đảm bảo thư mục output tồn tại
    mkdir -p "$OUTPUT_DIR"

    info "Nguồn: ${SOURCE_DIR}"
    info "Loại trừ: node_modules, vendor, .git, logs, cache, .env ..."
    echo ""

    # Chạy từ thư mục cha để zip lưu đường dẫn tương đối
    local PARENT_DIR
    PARENT_DIR="$(dirname "$SOURCE_DIR")"
    local BASE_NAME
    BASE_NAME="$(basename "$SOURCE_DIR")"

    cd "$PARENT_DIR"

    # Xây dựng lệnh zip với tất cả exclusions
    local ZIP_CMD=(
        zip -r9 "$OUTPUT_FILE"
        "$BASE_NAME"
        -x "${BASE_NAME}/node_modules/*"
        -x "${BASE_NAME}/vendor/*"
        -x "${BASE_NAME}/.git/*"
        -x "${BASE_NAME}/.github/*"
        -x "${BASE_NAME}/.gitignore"
        -x "${BASE_NAME}/.env"
        -x "${BASE_NAME}/public/hot"
        -x "${BASE_NAME}/public/build/*"
        -x "${BASE_NAME}/storage/logs/*.log"
        -x "${BASE_NAME}/storage/framework/cache/data/*"
        -x "${BASE_NAME}/storage/framework/sessions/*"
        -x "${BASE_NAME}/storage/framework/views/*"
        -x "${BASE_NAME}/bootstrap/cache/*.php"
        -x "${BASE_NAME}/.vscode/*"
        -x "${BASE_NAME}/.idea/*"
        -x "${BASE_NAME}/.snapshots/*"
        -x "${BASE_NAME}/.bob/*"
        -x "${BASE_NAME}/*.zip"
        -x "${BASE_NAME}/*.tar.gz"
        -x "${BASE_NAME}/exports/*"
        -x "${BASE_NAME}/nodesource_setup.sh"
        -x "*.DS_Store"
        -x "*Thumbs.db"
    )

    "${ZIP_CMD[@]}"

    log "Đã tạo: ${OUTPUT_FILE}"
}

# ── Thống kê kết quả ──────────────────────────────────────────────────────────
print_stats() {
    local SIZE_BYTES SIZE_HUMAN FILE_COUNT

    SIZE_BYTES=$(stat -c%s "$OUTPUT_FILE" 2>/dev/null || stat -f%z "$OUTPUT_FILE" 2>/dev/null || echo 0)
    FILE_COUNT=$(unzip -l "$OUTPUT_FILE" 2>/dev/null | tail -1 | grep -oP '^\s*\d+' | tr -d ' ' || echo "?")

    if   [[ $SIZE_BYTES -ge $((1024*1024*1024)) ]]; then
        SIZE_HUMAN="$(awk "BEGIN{printf \"%.1f GB\", $SIZE_BYTES/1073741824}")"
    elif [[ $SIZE_BYTES -ge $((1024*1024)) ]]; then
        SIZE_HUMAN="$(awk "BEGIN{printf \"%.1f MB\", $SIZE_BYTES/1048576}")"
    else
        SIZE_HUMAN="$(awk "BEGIN{printf \"%.0f KB\", $SIZE_BYTES/1024}")"
    fi

    echo ""
    echo -e "${BOLD}${GREEN}══════════════════════════════════════════${RESET}"
    echo -e "${BOLD}${GREEN}  BACKUP HOÀN TẤT${RESET}"
    echo -e "${BOLD}${GREEN}══════════════════════════════════════════${RESET}"
    echo ""
    echo -e "  📦 File    : ${CYAN}${OUTPUT_FILE}${RESET}"
    echo -e "  📏 Dung lượng: ${CYAN}${SIZE_HUMAN}${RESET}"
    echo -e "  📄 Tổng file : ${CYAN}${FILE_COUNT} files${RESET}"
    echo ""

    echo -e "${BOLD}Hướng dẫn upload lên cPanel:${RESET}"
    echo -e "  1. ${YELLOW}Đăng nhập cPanel → File Manager${RESET}"
    echo -e "     Hoặc dùng FTP: upload ${OUTPUT_NAME} vào ~/home directory"
    echo -e "  2. ${YELLOW}Giải nén trong cPanel → Extract${RESET}"
    echo -e "     Hoặc qua SSH: ${CYAN}unzip ${OUTPUT_NAME}${RESET}"
    echo -e "  3. ${YELLOW}Chạy script cài đặt:${RESET}"
    echo -e "     ${CYAN}bash install_cpanel.sh${RESET}"
    echo ""

    echo -e "${GREEN}Cảm ơn bạn đã sử dụng NLP Group Platform!${RESET}"
    echo -e "${BLUE}Hỗ trợ: dev@nlpgroup.vn | nlpgroup.vn${RESET}"
    echo ""
}

# ── MAIN ──────────────────────────────────────────────────────────────────────
main() {
    echo -e "${BOLD}${CYAN}"
    echo "  ╔════════════════════════════════════════════════════╗"
    echo "  ║     NLP Group Platform — Backup & Package Tool    ║"
    echo "  ╚════════════════════════════════════════════════════╝"
    echo -e "${RESET}"

    check_deps
    create_zip
    print_stats
}

main "$@"
