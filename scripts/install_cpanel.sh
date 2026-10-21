#!/usr/bin/env bash
# =============================================================================
# install_cpanel.sh — Cài đặt NLP Group Platform trên cPanel / Cloud Hosting
#
# Dành cho môi trường:
#   • cPanel shared/VPS hosting (không cần root)
#   • Cloud Linux / AlmaLinux / CentOS (môi trường cPanel)
#   • Chạy bằng SSH terminal trong cPanel
#
# Yêu cầu trước khi chạy:
#   1. Tạo Database MySQL & user trong cPanel → MySQL Databases
#   2. Tạo subdomain / addon domain trỏ vào public_html/nlpgroup/public
#   3. Upload file nlpgroup-platform.zip vào home directory (~/)
#
# Cách dùng:
#   chmod +x install_cpanel.sh && bash install_cpanel.sh
#
# Tác giả: NLP Group <dev@nlpgroup.vn>
# Phiên bản: 2.0.0 (cPanel Edition)
# =============================================================================

set -euo pipefail

# ── Màu sắc terminal ──────────────────────────────────────────────────────────
RED='\033[0;31m';  GREEN='\033[0;32m';  YELLOW='\033[1;33m'
BLUE='\033[0;34m'; CYAN='\033[0;36m';   BOLD='\033[1m';  RESET='\033[0m'

log()     { echo -e "${GREEN}[✔]${RESET} $*"; }
info()    { echo -e "${BLUE}[→]${RESET} $*"; }
warn()    { echo -e "${YELLOW}[!]${RESET} $*"; }
error()   { echo -e "${RED}[✗]${RESET} $*" >&2; }
die()     { error "$*"; exit 1; }
step()    { echo -e "\n${BOLD}${CYAN}══════════════════════════════════════${RESET}"
            echo -e "${BOLD}${CYAN}  $*${RESET}"
            echo -e "${BOLD}${CYAN}══════════════════════════════════════${RESET}"; }
confirm() {
    read -r -p "$(echo -e "${YELLOW}[?]${RESET} $* [y/N] ")" reply
    [[ "${reply,,}" == "y" || "${reply,,}" == "yes" ]]
}

# ── Banner ─────────────────────────────────────────────────────────────────────
show_banner() {
    echo -e "${BOLD}${GREEN}"
    echo "  ╔═══════════════════════════════════════════════════════════════╗"
    echo "  ║         NLP GROUP PLATFORM — cPanel Installer v2.0           ║"
    echo "  ║         Kiến tạo hệ sinh thái đổi mới bền vững               ║"
    echo "  ╚═══════════════════════════════════════════════════════════════╝"
    echo -e "${RESET}"
}

# ── Phát hiện PHP khả dụng trong cPanel ───────────────────────────────────────
detect_php() {
    step "Phát hiện phiên bản PHP"

    # cPanel thường cài PHP tại các đường dẫn chuẩn sau
    local PHP_CANDIDATES=(
        "$(command -v php 2>/dev/null || true)"
        "/usr/local/bin/php"
        "/opt/cpanel/ea-php82/root/usr/bin/php"
        "/opt/cpanel/ea-php81/root/usr/bin/php"
        "/opt/cpanel/ea-php80/root/usr/bin/php"
    )

    PHP_BIN=""
    for candidate in "${PHP_CANDIDATES[@]}"; do
        [[ -z "$candidate" ]] && continue
        [[ ! -x "$candidate" ]] && continue
        version=$("$candidate" -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;' 2>/dev/null || echo "0.0")
        major=$(echo "$version" | cut -d. -f1)
        minor=$(echo "$version" | cut -d. -f2)
        if [[ "$major" -gt 8 ]] || [[ "$major" -eq 8 && "$minor" -ge 1 ]]; then
            PHP_BIN="$candidate"
            log "Sử dụng PHP ${version} tại: ${PHP_BIN}"
            break
        fi
    done

    if [[ -z "$PHP_BIN" ]]; then
        die "Không tìm thấy PHP >= 8.1. Kiểm tra cPanel → MultiPHP Manager và bật PHP 8.1 hoặc 8.2."
    fi
}

# ── Phát hiện Composer ────────────────────────────────────────────────────────
detect_composer() {
    step "Phát hiện / Cài đặt Composer"

    COMPOSER_BIN=""

    # Kiểm tra composer toàn cục
    if command -v composer &>/dev/null; then
        ver=$(composer --version 2>/dev/null | grep -oP '\d+' | head -1 || echo "0")
        if [[ "$ver" -ge 2 ]]; then
            COMPOSER_BIN="composer"
            log "Đã dùng Composer $(composer --version | grep -oP '[\d.]+' | head -1) toàn cục"
            return
        fi
    fi

    # Cài Composer local vào home directory
    info "Tải Composer vào ~/composer.phar ..."
    EXPECTED="$($PHP_BIN -r 'copy("https://composer.github.io/installer.sig","php://stdout");' 2>/dev/null || echo '')"
    $PHP_BIN -r "copy('https://getcomposer.org/installer','composer-setup.php');" 2>/dev/null

    if [[ -n "$EXPECTED" ]]; then
        ACTUAL="$($PHP_BIN -r "echo hash_file('sha384','composer-setup.php');" 2>/dev/null)"
        if [[ "$EXPECTED" != "$ACTUAL" ]]; then
            rm -f composer-setup.php
            die "Checksum Composer không hợp lệ!"
        fi
    fi

    $PHP_BIN composer-setup.php --quiet --install-dir="${HOME}" --filename=composer.phar
    rm -f composer-setup.php
    COMPOSER_BIN="$PHP_BIN ${HOME}/composer.phar"
    log "Composer đã được cài vào ~/composer.phar"
}

# ── Thu thập cấu hình từ người dùng ──────────────────────────────────────────
collect_config() {
    step "Cấu hình cài đặt"

    echo -e "${YELLOW}Lưu ý:${RESET} Bạn cần tạo Database và User MySQL trước trong cPanel → MySQL Databases.\n"

    read -r -p "$(echo -e "${BLUE}[→]${RESET} Thư mục cài đặt (để trống = ~/public_html/nlpgroup): ")" INPUT_INSTALL_DIR
    INSTALL_DIR="${INPUT_INSTALL_DIR:-${HOME}/public_html/nlpgroup}"

    read -r -p "$(echo -e "${BLUE}[→]${RESET} Tên miền / subdomain [nlpgroup.yourdomain.com]: ")" INPUT_DOMAIN
    APP_DOMAIN="${INPUT_DOMAIN:-nlpgroup.yourdomain.com}"

    read -r -p "$(echo -e "${BLUE}[→]${RESET} Tên Database MySQL (đã tạo trong cPanel): ")" DB_DATABASE
    [[ -z "$DB_DATABASE" ]] && die "Tên database không được để trống."

    read -r -p "$(echo -e "${BLUE}[→]${RESET} Tên User MySQL (đã tạo trong cPanel): ")" DB_USERNAME
    [[ -z "$DB_USERNAME" ]] && die "Tên user database không được để trống."

    while true; do
        read -r -s -p "$(echo -e "${BLUE}[→]${RESET} Mật khẩu MySQL (ít nhất 8 ký tự): ")" DB_PASSWORD; echo
        [[ ${#DB_PASSWORD} -ge 8 ]] && break
        warn "Mật khẩu quá ngắn, vui lòng nhập lại."
    done

    read -r -p "$(echo -e "${BLUE}[→]${RESET} DB Host [localhost]: ")" INPUT_DB_HOST
    DB_HOST="${INPUT_DB_HOST:-localhost}"

    read -r -p "$(echo -e "${BLUE}[→]${RESET} Email quản trị [admin@nlpgroup.vn]: ")" INPUT_EMAIL
    ADMIN_EMAIL="${INPUT_EMAIL:-admin@nlpgroup.vn}"

    echo -e "\n${BOLD}Xác nhận cấu hình:${RESET}"
    echo -e "  Thư mục    : ${CYAN}${INSTALL_DIR}${RESET}"
    echo -e "  Domain     : ${CYAN}${APP_DOMAIN}${RESET}"
    echo -e "  Database   : ${CYAN}${DB_DATABASE}${RESET}"
    echo -e "  DB User    : ${CYAN}${DB_USERNAME}${RESET}"
    echo -e "  DB Host    : ${CYAN}${DB_HOST}${RESET}"
    echo -e "  Admin Email: ${CYAN}${ADMIN_EMAIL}${RESET}"
    echo ""
    confirm "Tiếp tục cài đặt?" || die "Hủy cài đặt."
}

# ── Giải nén mã nguồn ─────────────────────────────────────────────────────────
extract_source() {
    step "Giải nén mã nguồn"

    # Tìm file zip trong home directory
    local ZIP_FILE=""
    for candidate in \
        "${HOME}/nlpgroup-platform.zip" \
        "${HOME}/investment-platform.zip" \
        "${HOME}/nlpgroup.zip" \
        "$(ls "${HOME}"/*.zip 2>/dev/null | head -1 || true)"
    do
        [[ -f "$candidate" ]] && { ZIP_FILE="$candidate"; break; }
    done

    if [[ -z "$ZIP_FILE" ]]; then
        warn "Không tìm thấy file .zip tự động."
        read -r -p "$(echo -e "${BLUE}[→]${RESET} Nhập đường dẫn đầy đủ đến file zip: ")" ZIP_FILE
        [[ ! -f "$ZIP_FILE" ]] && die "File '${ZIP_FILE}' không tồn tại."
    fi

    info "Đang giải nén: ${ZIP_FILE} → ${INSTALL_DIR}"

    # Sao lưu nếu đã có .env
    if [[ -f "${INSTALL_DIR}/.env" ]]; then
        cp "${INSTALL_DIR}/.env" "/tmp/.env.backup.$(date +%Y%m%d_%H%M%S)"
        warn "Đã sao lưu .env cũ vào /tmp/"
    fi

    mkdir -p "$INSTALL_DIR"

    # Giải nén, bỏ qua thư mục wrapper nếu có
    local TMP_EXTRACT="/tmp/nlpgroup_extract_$$"
    mkdir -p "$TMP_EXTRACT"
    unzip -q "$ZIP_FILE" -d "$TMP_EXTRACT"

    # Kiểm tra xem zip có chứa 1 thư mục wrapper không
    local ENTRIES
    ENTRIES=$(ls -A "$TMP_EXTRACT" | wc -l)
    if [[ "$ENTRIES" -eq 1 ]]; then
        local WRAPPER
        WRAPPER=$(ls -A "$TMP_EXTRACT")
        if [[ -d "${TMP_EXTRACT}/${WRAPPER}" ]]; then
            info "Phát hiện thư mục wrapper '${WRAPPER}', bỏ qua..."
            cp -a "${TMP_EXTRACT}/${WRAPPER}/." "$INSTALL_DIR/"
        else
            cp -a "${TMP_EXTRACT}/." "$INSTALL_DIR/"
        fi
    else
        cp -a "${TMP_EXTRACT}/." "$INSTALL_DIR/"
    fi

    rm -rf "$TMP_EXTRACT"
    log "Mã nguồn đã giải nén vào ${INSTALL_DIR}"
}

# ── Tạo cấu trúc thư mục cần thiết ───────────────────────────────────────────
setup_directories() {
    step "Tạo cấu trúc thư mục"

    mkdir -p "${INSTALL_DIR}/storage/"{app/public,framework/{cache/data,sessions,views},logs}
    mkdir -p "${INSTALL_DIR}/bootstrap/cache"
    mkdir -p "${INSTALL_DIR}/public/uploads"

    # Quyền ghi cho web server (755 cho thư mục, không cần 777 trong cPanel)
    chmod -R 755 "${INSTALL_DIR}/storage"
    chmod -R 755 "${INSTALL_DIR}/bootstrap/cache"

    log "Cấu trúc thư mục đã sẵn sàng"
}

# ── Cấu hình file .env ────────────────────────────────────────────────────────
configure_env() {
    step "Cấu hình file .env"

    local ENV_FILE="${INSTALL_DIR}/.env"
    local ENV_EXAMPLE="${INSTALL_DIR}/.env.example"

    if [[ -f "$ENV_EXAMPLE" ]]; then
        cp "$ENV_EXAMPLE" "$ENV_FILE"
        log "Đã copy từ .env.example"
    else
        cat > "$ENV_FILE" <<'ENVEOF'
APP_NAME="NLP Group"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Ho_Chi_Minh

LOG_CHANNEL=daily
LOG_LEVEL=error

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

BROADCAST_DRIVER=log
FILESYSTEM_DISK=local

MAIL_MAILER=smtp
MAIL_PORT=587
MAIL_ENCRYPTION=tls
ENVEOF
        log "Đã tạo .env tối thiểu"
    fi

    # Tạo APP_KEY mới
    APP_KEY_VALUE=$($PHP_BIN -r "echo 'base64:'.base64_encode(random_bytes(32));")

    set_env() {
        local KEY="$1" VAL="$2"
        local ESCAPED
        ESCAPED=$(printf '%s\n' "$VAL" | sed 's/[&/\]/\\&/g')
        if grep -q "^${KEY}=" "$ENV_FILE" 2>/dev/null; then
            sed -i "s|^${KEY}=.*|${KEY}=\"${ESCAPED}\"|" "$ENV_FILE"
        else
            echo "${KEY}=\"${VAL}\"" >> "$ENV_FILE"
        fi
    }

    set_env "APP_KEY"       "$APP_KEY_VALUE"
    set_env "APP_ENV"       "production"
    set_env "APP_DEBUG"     "false"
    set_env "APP_URL"       "https://${APP_DOMAIN}"
    set_env "DB_CONNECTION" "mysql"
    set_env "DB_HOST"       "$DB_HOST"
    set_env "DB_PORT"       "3306"
    set_env "DB_DATABASE"   "$DB_DATABASE"
    set_env "DB_USERNAME"   "$DB_USERNAME"
    set_env "DB_PASSWORD"   "$DB_PASSWORD"

    chmod 640 "$ENV_FILE"
    log "File .env đã được cấu hình và bảo vệ (chmod 640)"
}

# ── Cài đặt PHP dependencies ──────────────────────────────────────────────────
composer_install() {
    step "Cài đặt PHP dependencies (Composer)"

    if [[ ! -f "${INSTALL_DIR}/composer.json" ]]; then
        warn "Không tìm thấy composer.json. Bỏ qua."
        return
    fi

    cd "$INSTALL_DIR"

    # cPanel thường giới hạn memory — đặt 512M
    COMPOSER_MEMORY_LIMIT=-1 $COMPOSER_BIN install \
        --no-dev \
        --optimize-autoloader \
        --no-interaction \
        --no-scripts \
        2>/dev/null || \
    COMPOSER_MEMORY_LIMIT=-1 $COMPOSER_BIN install \
        --no-dev \
        --optimize-autoloader \
        --no-interaction

    log "PHP dependencies đã cài đặt"
}

# ── Chạy artisan commands ─────────────────────────────────────────────────────
run_artisan() {
    step "Khởi tạo ứng dụng Laravel"

    cd "$INSTALL_DIR"
    local ART="$PHP_BIN artisan"

    # Key generate nếu chưa có
    if ! grep -q '^APP_KEY=base64:.\{20\}' .env 2>/dev/null; then
        $ART key:generate --force --no-interaction 2>/dev/null || true
        log "APP_KEY mới đã được tạo"
    fi

    # Storage link
    $ART storage:link --no-interaction 2>/dev/null || true
    log "storage:link đã thiết lập"

    # Database migration
    info "Kiểm tra kết nối database..."
    if $ART db:show --no-interaction 2>/dev/null; then
        $ART migrate --force --no-interaction
        log "Migration hoàn tất"

        if confirm "Chạy database seeder (dữ liệu mặc định ban đầu)?"; then
            $ART db:seed --no-interaction 2>/dev/null || warn "Seeder gặp lỗi, chạy thủ công sau: php artisan db:seed"
            log "Seeder hoàn tất"
        fi
    else
        warn "Không kết nối được database. Kiểm tra thông tin DB trong .env rồi chạy thủ công:"
        echo -e "  ${CYAN}php artisan migrate --force${RESET}"
    fi

    # Tối ưu cache
    $ART config:cache    --no-interaction 2>/dev/null || true
    $ART route:cache     --no-interaction 2>/dev/null || true
    $ART view:cache      --no-interaction 2>/dev/null || true
    $ART event:cache     --no-interaction 2>/dev/null || true
    log "Cache ứng dụng đã được tạo"
}

# ── Tạo file .htaccess cho public_html root ───────────────────────────────────
create_htaccess() {
    step "Cấu hình .htaccess"

    # cPanel thường serve từ public_html — tạo redirect vào /public
    local ROOT_HTACCESS="${INSTALL_DIR}/../.htaccess"
    local PUBLIC_DIR="${INSTALL_DIR}/public"

    # .htaccess tại public/ (Laravel chuẩn)
    if [[ ! -f "${PUBLIC_DIR}/.htaccess" ]]; then
        cat > "${PUBLIC_DIR}/.htaccess" <<'HTACCESS'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Bảo vệ file nhạy cảm
<FilesMatch "(\.env|\.git|composer\.(json|lock)|package\.json)">
    Require all denied
</FilesMatch>
HTACCESS
        log ".htaccess tại public/ đã được tạo"
    fi

    # Nếu domain trỏ vào thư mục cha của nlpgroup, tạo redirect
    if [[ -w "$(dirname "$INSTALL_DIR")" ]]; then
        local PARENT_HTACCESS
        PARENT_HTACCESS="$(dirname "$INSTALL_DIR")/.htaccess"
        if [[ ! -f "$PARENT_HTACCESS" ]]; then
            cat > "$PARENT_HTACCESS" <<PHTACCESS
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^$ nlpgroup/public/ [L]
    RewriteRule (.*) nlpgroup/public/\$1 [L]
</IfModule>
PHTACCESS
            log ".htaccess root đã được tạo (redirect sang public/)"
        fi
    fi
}

# ── Quyền file ────────────────────────────────────────────────────────────────
setup_permissions() {
    step "Thiết lập quyền file"

    find "$INSTALL_DIR" -type f -exec chmod 644 {} \; 2>/dev/null || true
    find "$INSTALL_DIR" -type d -exec chmod 755 {} \; 2>/dev/null || true

    chmod -R 755 "${INSTALL_DIR}/storage"
    chmod -R 755 "${INSTALL_DIR}/bootstrap/cache"
    chmod 640    "${INSTALL_DIR}/.env" 2>/dev/null || true
    chmod 755    "${INSTALL_DIR}/artisan" 2>/dev/null || true

    log "Quyền file đã được thiết lập"
}

# ── Tóm tắt hoàn tất ─────────────────────────────────────────────────────────
print_summary() {
    echo ""
    echo -e "${BOLD}${GREEN}"
    echo "╔══════════════════════════════════════════════════════════════════╗"
    echo "║        CÀI ĐẶT NLP GROUP PLATFORM TRÊN CPANEL HOÀN TẤT!        ║"
    echo "╚══════════════════════════════════════════════════════════════════╝"
    echo -e "${RESET}"

    echo -e "${BOLD}Thông tin truy cập:${RESET}"
    echo -e "  🌐 Website     : ${CYAN}https://${APP_DOMAIN}${RESET}"
    echo -e "  📁 Thư mục app : ${CYAN}${INSTALL_DIR}${RESET}"
    echo -e "  🌐 Document root: ${CYAN}${INSTALL_DIR}/public${RESET}"
    echo -e "  🗄  Database    : ${CYAN}${DB_DATABASE}${RESET} (user: ${DB_USERNAME})"
    echo ""

    echo -e "${BOLD}Bước tiếp theo trên cPanel:${RESET}"
    echo -e "  1. ${YELLOW}cPanel → Domains → Document Root → đặt thành:${RESET}"
    echo -e "     ${CYAN}${INSTALL_DIR}/public${RESET}"
    echo -e "  2. ${YELLOW}Hoặc cPanel → Subdomains → chỉnh Document Root${RESET}"
    echo -e "  3. ${YELLOW}Bật SSL miễn phí: cPanel → SSL/TLS → AutoSSL${RESET}"
    echo ""

    echo -e "${BOLD}Lệnh quản lý (SSH):${RESET}"
    echo -e "  ${YELLOW}cd ${INSTALL_DIR}${RESET}"
    echo -e "  ${YELLOW}php artisan list${RESET}                 # Liệt kê lệnh"
    echo -e "  ${YELLOW}php artisan optimize:clear${RESET}        # Xóa cache"
    echo -e "  ${YELLOW}php artisan migrate --force${RESET}       # Chạy migration"
    echo -e "  ${YELLOW}php artisan queue:work${RESET}            # Chạy queue worker"
    echo ""

    echo -e "${BOLD}Cron Job (cPanel → Cron Jobs):${RESET}"
    echo -e "  ${CYAN}* * * * * php ${INSTALL_DIR}/artisan schedule:run >> /dev/null 2>&1${RESET}"
    echo ""

    echo -e "${BOLD}Logs:${RESET}"
    echo -e "  ${YELLOW}tail -f ${INSTALL_DIR}/storage/logs/laravel.log${RESET}"
    echo ""

    echo -e "${GREEN}Cảm ơn bạn đã sử dụng NLP Group Platform!${RESET}"
    echo -e "${BLUE}Hỗ trợ kỹ thuật: dev@nlpgroup.vn | nlpgroup.vn${RESET}"
    echo ""
}

# ── MAIN ──────────────────────────────────────────────────────────────────────
main() {
    trap 'echo -e "\n${RED}[✗] Cài đặt bị gián đoạn tại dòng $LINENO. Kiểm tra log: /tmp/nlpgroup_cpanel_install.log${RESET}"; exit 1' ERR INT TERM

    LOG_FILE="/tmp/nlpgroup_cpanel_install_$(date +%Y%m%d_%H%M%S).log"

    show_banner
    detect_php
    detect_composer
    collect_config

    info "Log cài đặt được ghi vào: ${LOG_FILE}"
    exec > >(tee -a "$LOG_FILE") 2>&1

    extract_source
    setup_directories
    configure_env
    composer_install
    create_htaccess
    setup_permissions
    run_artisan

    print_summary
    log "Log đầy đủ: ${LOG_FILE}"
}

main "$@"
