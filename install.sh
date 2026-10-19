#!/usr/bin/env bash
# =============================================================================
# install.sh — Cài đặt NLP Group Platform
# Hỗ trợ: Ubuntu 20.04/22.04/24.04 · Debian 11/12 · CentOS/RHEL 8/9
#
# Cách dùng:
#   chmod +x install.sh && sudo bash install.sh
#   hoặc chạy thẳng:
#   sudo bash -c "$(curl -fsSL https://nlpgroup.vn/install.sh)"
#
# Tác giả: NLP Group <dev@nlpgroup.vn>
# Phiên bản: 1.0.0
# =============================================================================

set -euo pipefail
IFS=$'\n\t'

# ── Màu sắc terminal ──────────────────────────────────────────────────────────
RED='\033[0;31m';  GREEN='\033[0;32m';  YELLOW='\033[1;33m'
BLUE='\033[0;34m'; CYAN='\033[0;36m';   BOLD='\033[1m';  RESET='\033[0m'

# ── Hàm tiện ích ──────────────────────────────────────────────────────────────
log()     { echo -e "${GREEN}[✔]${RESET} $*"; }
info()    { echo -e "${BLUE}[→]${RESET} $*"; }
warn()    { echo -e "${YELLOW}[!]${RESET} $*"; }
error()   { echo -e "${RED}[✗]${RESET} $*" >&2; }
die()     { error "$*"; exit 1; }
step()    { echo -e "\n${BOLD}${CYAN}══════════════════════════════════════${RESET}"; \
            echo -e "${BOLD}${CYAN}  $*${RESET}"; \
            echo -e "${BOLD}${CYAN}══════════════════════════════════════${RESET}"; }
confirm() {
    read -r -p "$(echo -e "${YELLOW}[?]${RESET} $* [y/N] ")" reply
    [[ "${reply,,}" == "y" || "${reply,,}" == "yes" ]]
}

# ── Kiểm tra quyền root ───────────────────────────────────────────────────────
check_root() {
    if [[ $EUID -ne 0 ]]; then
        die "Script này cần chạy với quyền root.\n   Thử lại: sudo bash install.sh"
    fi
}

# ── Nhận diện hệ điều hành ───────────────────────────────────────────────────
detect_os() {
    if [[ -f /etc/os-release ]]; then
        # shellcheck source=/dev/null
        source /etc/os-release
        OS_ID="${ID,,}"          # ubuntu | debian | centos | rhel | fedora | almalinux | rocky
        OS_VERSION="${VERSION_ID%%.*}"
        OS_NAME="${PRETTY_NAME}"
    elif [[ "$(uname)" == "Darwin" ]]; then
        OS_ID="darwin"
        OS_NAME="macOS $(sw_vers -productVersion)"
        OS_VERSION="$(sw_vers -productVersion | cut -d. -f1)"
    else
        die "Không nhận diện được hệ điều hành. Script hỗ trợ Ubuntu/Debian/CentOS/RHEL/macOS."
    fi

    # Phân nhóm họ package manager
    case "$OS_ID" in
        ubuntu|debian|linuxmint|pop)     PKG_MGR="apt"; PKG_FAMILY="debian" ;;
        centos|rhel|almalinux|rocky|ol)  PKG_MGR="dnf"; PKG_FAMILY="rhel"   ;;
        fedora)                          PKG_MGR="dnf"; PKG_FAMILY="rhel"   ;;
        darwin)                          PKG_MGR="brew"; PKG_FAMILY="darwin" ;;
        *)
            warn "Hệ điều hành '$OS_ID' chưa được kiểm tra đầy đủ. Tiếp tục thử..."
            if command -v apt-get &>/dev/null; then PKG_MGR="apt";  PKG_FAMILY="debian"
            elif command -v dnf  &>/dev/null; then PKG_MGR="dnf";  PKG_FAMILY="rhel"
            elif command -v yum  &>/dev/null; then PKG_MGR="yum";  PKG_FAMILY="rhel"
            else die "Không tìm được package manager (apt/dnf/yum/brew)."
            fi
            ;;
    esac

    log "Hệ điều hành: ${OS_NAME} | Package manager: ${PKG_MGR}"
}

# ── Phiên bản phần mềm yêu cầu ───────────────────────────────────────────────
PHP_VERSION="8.2"
PHP_MIN_MAJOR=8
PHP_MIN_MINOR=1
MYSQL_MIN="8.0"
COMPOSER_MIN="2"
NODE_MIN=18

# ── Cấu hình deploy ───────────────────────────────────────────────────────────
INSTALL_DIR="/var/www/investment-platform"
APP_USER="www-data"
APP_GROUP="www-data"
NGINX_SITE="nlpgroup"
REPO_URL="https://github.com/nlpgroup/investment-platform.git"

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 0: Banner & Thu thập thông tin
# ─────────────────────────────────────────────────────────────────────────────
show_banner() {
    echo -e "${BOLD}${GREEN}"
    echo "   ███╗   ██╗██╗     ██████╗      ██████╗ ██████╗  ██████╗ ██╗   ██╗██████╗ "
    echo "   ████╗  ██║██║     ██╔══██╗    ██╔════╝ ██╔══██╗██╔═══██╗██║   ██║██╔══██╗"
    echo "   ██╔██╗ ██║██║     ██████╔╝    ██║  ███╗██████╔╝██║   ██║██║   ██║██████╔╝"
    echo "   ██║╚██╗██║██║     ██╔═══╝     ██║   ██║██╔══██╗██║   ██║██║   ██║██╔═══╝ "
    echo "   ██║ ╚████║███████╗██║         ╚██████╔╝██║  ██║╚██████╔╝╚██████╔╝██║     "
    echo "   ╚═╝  ╚═══╝╚══════╝╚═╝          ╚═════╝ ╚═╝  ╚═╝ ╚═════╝  ╚═════╝ ╚═╝     "
    echo -e "${RESET}"
    echo -e "   ${BOLD}NLP Group Platform — Cài đặt tự động${RESET}"
    echo -e "   Kiến tạo hệ sinh thái đổi mới bền vững\n"
}

collect_config() {
    step "Cấu hình cài đặt"

    # Domain
    read -r -p "$(echo -e "${BLUE}[→]${RESET} Tên miền website [nlpgroup.vn]: ")" INPUT_DOMAIN
    APP_DOMAIN="${INPUT_DOMAIN:-nlpgroup.vn}"

    # DB
    read -r -p "$(echo -e "${BLUE}[→]${RESET} Tên database MySQL [nlpgroup_db]: ")" INPUT_DB
    DB_DATABASE="${INPUT_DB:-nlpgroup_db}"

    read -r -p "$(echo -e "${BLUE}[→]${RESET} Tên user MySQL [nlp_user]: ")" INPUT_DB_USER
    DB_USERNAME="${INPUT_DB_USER:-nlp_user}"

    # Mật khẩu (ẩn input)
    while true; do
        read -r -s -p "$(echo -e "${BLUE}[→]${RESET} Mật khẩu MySQL (ít nhất 8 ký tự): ")" DB_PASSWORD; echo
        [[ ${#DB_PASSWORD} -ge 8 ]] && break
        warn "Mật khẩu quá ngắn, vui lòng nhập lại."
    done

    read -r -p "$(echo -e "${BLUE}[→]${RESET} Mật khẩu root MySQL (để trống nếu không dùng): ")" DB_ROOT_PASS; echo

    # Email
    read -r -p "$(echo -e "${BLUE}[→]${RESET} Email quản trị [admin@nlpgroup.vn]: ")" INPUT_EMAIL
    ADMIN_EMAIL="${INPUT_EMAIL:-admin@nlpgroup.vn}"

    # SSL
    if confirm "Cài đặt SSL miễn phí (Let's Encrypt) cho ${APP_DOMAIN}?"; then
        ENABLE_SSL=true
    else
        ENABLE_SSL=false
    fi

    echo ""
    echo -e "${BOLD}Xác nhận cấu hình:${RESET}"
    echo -e "  Domain     : ${CYAN}${APP_DOMAIN}${RESET}"
    echo -e "  Database   : ${CYAN}${DB_DATABASE}${RESET}"
    echo -e "  DB User    : ${CYAN}${DB_USERNAME}${RESET}"
    echo -e "  Admin Email: ${CYAN}${ADMIN_EMAIL}${RESET}"
    echo -e "  SSL        : ${CYAN}$(${ENABLE_SSL} && echo 'Có' || echo 'Không')${RESET}"
    echo ""
    confirm "Tiếp tục cài đặt?" || die "Hủy cài đặt theo yêu cầu người dùng."
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 1: Cập nhật hệ thống & Cài đặt phụ thuộc cơ bản
# ─────────────────────────────────────────────────────────────────────────────
install_base_deps() {
    step "Cập nhật hệ thống & cài đặt gói cơ bản"

    case "$PKG_FAMILY" in
        debian)
            export DEBIAN_FRONTEND=noninteractive
            apt-get update -qq
            apt-get install -y -qq \
                curl wget git unzip zip gnupg2 lsb-release \
                software-properties-common ca-certificates apt-transport-https \
                cron logrotate ufw fail2ban
            log "Đã cài gói cơ bản (Debian/Ubuntu)"
            ;;
        rhel)
            dnf update -y -q
            dnf install -y -q \
                curl wget git unzip zip gnupg2 \
                ca-certificates epel-release \
                cronie logrotate firewalld
            log "Đã cài gói cơ bản (RHEL/CentOS)"
            ;;
        darwin)
            if ! command -v brew &>/dev/null; then
                info "Cài đặt Homebrew..."
                /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
            fi
            brew update -q
            brew install git curl wget unzip
            log "Đã cài gói cơ bản (macOS)"
            ;;
    esac
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 2: Cài đặt PHP 8.2
# ─────────────────────────────────────────────────────────────────────────────
install_php() {
    step "Cài đặt PHP ${PHP_VERSION}"

    # Kiểm tra PHP đã có chưa
    if command -v php &>/dev/null; then
        CURRENT_PHP=$(php -r 'echo PHP_VERSION;' 2>/dev/null)
        CURRENT_MAJOR=$(echo "$CURRENT_PHP" | cut -d. -f1)
        CURRENT_MINOR=$(echo "$CURRENT_PHP" | cut -d. -f2)
        if [[ $CURRENT_MAJOR -gt $PHP_MIN_MAJOR ]] || \
           [[ $CURRENT_MAJOR -eq $PHP_MIN_MAJOR && $CURRENT_MINOR -ge $PHP_MIN_MINOR ]]; then
            log "PHP ${CURRENT_PHP} đã có sẵn, bỏ qua cài đặt."
            return
        fi
        warn "PHP ${CURRENT_PHP} quá cũ. Cần PHP >= ${PHP_MIN_MAJOR}.${PHP_MIN_MINOR}"
    fi

    case "$PKG_FAMILY" in
        debian)
            # Thêm PPA Ondřej Surý (nguồn PHP chính thức cho Ubuntu/Debian)
            if [[ "$OS_ID" == "ubuntu" ]]; then
                LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php 2>/dev/null || true
            else
                # Debian: dùng packages.sury.org
                curl -sSL https://packages.sury.org/php/apt.gpg \
                    | gpg --dearmor -o /usr/share/keyrings/sury-php.gpg 2>/dev/null || true
                echo "deb [signed-by=/usr/share/keyrings/sury-php.gpg] https://packages.sury.org/php/ $(lsb_release -cs) main" \
                    > /etc/apt/sources.list.d/sury-php.list
            fi
            apt-get update -qq
            apt-get install -y -qq \
                "php${PHP_VERSION}" \
                "php${PHP_VERSION}-fpm" \
                "php${PHP_VERSION}-cli" \
                "php${PHP_VERSION}-mysql" \
                "php${PHP_VERSION}-mbstring" \
                "php${PHP_VERSION}-xml" \
                "php${PHP_VERSION}-curl" \
                "php${PHP_VERSION}-zip" \
                "php${PHP_VERSION}-bcmath" \
                "php${PHP_VERSION}-gd" \
                "php${PHP_VERSION}-intl" \
                "php${PHP_VERSION}-redis" \
                "php${PHP_VERSION}-opcache"
            ;;
        rhel)
            # Thêm Remi repository
            if [[ ! -f /etc/yum.repos.d/remi.repo ]]; then
                dnf install -y -q "https://rpms.remirepo.net/enterprise/remi-release-${OS_VERSION}.rpm" || \
                dnf install -y -q "https://rpms.remirepo.net/fedora/remi-release-$(rpm -E %fedora).rpm"
            fi
            dnf module reset php -y -q 2>/dev/null || true
            dnf module enable "php:remi-${PHP_VERSION}" -y -q
            dnf install -y -q \
                php php-fpm php-cli php-mysqlnd \
                php-mbstring php-xml php-curl php-zip \
                php-bcmath php-gd php-intl php-opcache
            ;;
        darwin)
            brew install "php@${PHP_VERSION}" || brew upgrade "php@${PHP_VERSION}" || true
            brew link --overwrite --force "php@${PHP_VERSION}" 2>/dev/null || true
            ;;
    esac

    PHP_BIN=$(command -v "php${PHP_VERSION}" || command -v php)
    INSTALLED_PHP=$("$PHP_BIN" -r 'echo PHP_VERSION;')
    log "PHP ${INSTALLED_PHP} đã cài đặt thành công"
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 3: Cài đặt MySQL 8
# ─────────────────────────────────────────────────────────────────────────────
install_mysql() {
    step "Cài đặt MySQL 8"

    if command -v mysql &>/dev/null; then
        CURRENT_MYSQL=$(mysql --version 2>/dev/null | grep -oP '\d+\.\d+' | head -1)
        log "MySQL ${CURRENT_MYSQL} đã có sẵn, bỏ qua cài đặt."
        setup_mysql_db
        return
    fi

    case "$PKG_FAMILY" in
        debian)
            # MySQL Apt Repository
            MYSQL_APT_PKG="mysql-apt-config_0.8.32-1_all.deb"
            wget -q -O /tmp/mysql-apt-config.deb \
                "https://dev.mysql.com/get/${MYSQL_APT_PKG}" 2>/dev/null || true

            if [[ -f /tmp/mysql-apt-config.deb ]]; then
                DEBIAN_FRONTEND=noninteractive dpkg -i /tmp/mysql-apt-config.deb 2>/dev/null || true
                apt-get update -qq
            fi

            # Preset mật khẩu root MySQL trước khi cài
            if [[ -n "${DB_ROOT_PASS:-}" ]]; then
                debconf-set-selections <<< "mysql-server mysql-server/root_password password ${DB_ROOT_PASS}"
                debconf-set-selections <<< "mysql-server mysql-server/root_password_again password ${DB_ROOT_PASS}"
            fi

            apt-get install -y -qq mysql-server mysql-client
            ;;
        rhel)
            dnf install -y -q mysql-server mysql
            systemctl enable mysqld
            systemctl start mysqld

            # Lấy mật khẩu tạm thời từ log
            if [[ -n "${DB_ROOT_PASS:-}" ]]; then
                TEMP_ROOT_PASS=$(grep 'temporary password' /var/log/mysqld.log 2>/dev/null | tail -1 | awk '{print $NF}') || true
                if [[ -n "${TEMP_ROOT_PASS:-}" ]]; then
                    mysql -u root -p"${TEMP_ROOT_PASS}" \
                        --connect-expired-password \
                        -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '${DB_ROOT_PASS}';" 2>/dev/null || true
                fi
            fi
            ;;
        darwin)
            brew install mysql@8.0 || brew upgrade mysql@8.0 || true
            brew services start mysql@8.0
            ;;
    esac

    # Khởi động MySQL
    if [[ "$PKG_FAMILY" != "darwin" ]]; then
        systemctl enable mysql 2>/dev/null || systemctl enable mysqld 2>/dev/null || true
        systemctl start  mysql 2>/dev/null || systemctl start  mysqld 2>/dev/null || true
    fi

    log "MySQL đã cài đặt thành công"
    setup_mysql_db
}

setup_mysql_db() {
    step "Tạo database và user MySQL"

    local MYSQL_CMD="mysql"
    if [[ -n "${DB_ROOT_PASS:-}" ]]; then
        MYSQL_CMD="mysql -uroot -p${DB_ROOT_PASS}"
    fi

    # Kiểm tra kết nối MySQL
    if ! $MYSQL_CMD -e "SELECT 1;" &>/dev/null; then
        warn "Không kết nối được MySQL với thông tin đã cung cấp."
        warn "Hãy tạo database thủ công:"
        echo -e "  ${CYAN}CREATE DATABASE \`${DB_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;${RESET}"
        echo -e "  ${CYAN}CREATE USER '${DB_USERNAME}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';${RESET}"
        echo -e "  ${CYAN}GRANT ALL PRIVILEGES ON \`${DB_DATABASE}\`.* TO '${DB_USERNAME}'@'localhost';${RESET}"
        echo -e "  ${CYAN}FLUSH PRIVILEGES;${RESET}"
        return
    fi

    $MYSQL_CMD <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE}\`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS '${DB_USERNAME}'@'localhost'
    IDENTIFIED BY '${DB_PASSWORD}';

GRANT ALL PRIVILEGES ON \`${DB_DATABASE}\`.*
    TO '${DB_USERNAME}'@'localhost';

FLUSH PRIVILEGES;
SQL

    log "Database '${DB_DATABASE}' và user '${DB_USERNAME}' đã được tạo"
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 4: Cài đặt Nginx
# ─────────────────────────────────────────────────────────────────────────────
install_nginx() {
    step "Cài đặt Nginx"

    if command -v nginx &>/dev/null; then
        log "Nginx $(nginx -v 2>&1 | grep -oP '[\d.]+') đã có sẵn"
    else
        case "$PKG_FAMILY" in
            debian) apt-get install -y -qq nginx ;;
            rhel)   dnf install -y -q nginx; systemctl enable nginx ;;
            darwin) brew install nginx ;;
        esac
        log "Nginx đã cài đặt thành công"
    fi

    # Tạo cấu hình Nginx cho NLP Group
    configure_nginx

    if [[ "$PKG_FAMILY" != "darwin" ]]; then
        systemctl enable nginx
        systemctl restart nginx
    fi
}

configure_nginx() {
    local NGINX_CONF_DIR
    case "$PKG_FAMILY" in
        debian) NGINX_CONF_DIR="/etc/nginx/sites-available" ;;
        rhel)   NGINX_CONF_DIR="/etc/nginx/conf.d" ;;
        darwin) NGINX_CONF_DIR="$(brew --prefix)/etc/nginx/servers" ;;
    esac

    mkdir -p "$NGINX_CONF_DIR"

    # Tạo PHP socket path
    local PHP_SOCK
    case "$PKG_FAMILY" in
        debian) PHP_SOCK="/run/php/php${PHP_VERSION}-fpm.sock" ;;
        rhel)   PHP_SOCK="/run/php-fpm/www.sock" ;;
        darwin) PHP_SOCK="/tmp/php-fpm.sock" ;;
    esac

    cat > "${NGINX_CONF_DIR}/${NGINX_SITE}" <<NGINX
# NLP Group Platform — Nginx Virtual Host
# Tạo tự động bởi install.sh

server {
    listen 80;
    listen [::]:80;
    server_name ${APP_DOMAIN} www.${APP_DOMAIN};

    root ${INSTALL_DIR}/public;
    index index.php index.html;

    charset utf-8;

    # Bảo mật: Ẩn thông tin phiên bản
    server_tokens off;

    # Giới hạn kích thước upload
    client_max_body_size 64M;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN"           always;
    add_header X-XSS-Protection "1; mode=block"       always;
    add_header X-Content-Type-Options "nosniff"        always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Laravel routing
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:${PHP_SOCK};
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 120;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
    }

    # Cache static assets 1 năm
    location ~* \.(css|js|jpg|jpeg|gif|png|ico|svg|woff|woff2|ttf|eot|webp)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Chặn truy cập file ẩn
    location ~ /\.(?!well-known) {
        deny all;
    }

    # Chặn truy cập thư mục nhạy cảm
    location ~ ^/(storage|bootstrap/cache|\.env) {
        deny all;
    }

    access_log /var/log/nginx/${NGINX_SITE}-access.log;
    error_log  /var/log/nginx/${NGINX_SITE}-error.log;
}
NGINX

    # Enable site (Debian/Ubuntu)
    if [[ "$PKG_FAMILY" == "debian" ]]; then
        ln -sf "${NGINX_CONF_DIR}/${NGINX_SITE}" "/etc/nginx/sites-enabled/${NGINX_SITE}"
        # Tắt site default
        rm -f /etc/nginx/sites-enabled/default 2>/dev/null || true
    fi

    # Kiểm tra cú pháp Nginx
    nginx -t && log "Cấu hình Nginx hợp lệ" || die "Cấu hình Nginx có lỗi. Kiểm tra: nginx -t"
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 5: Cài đặt Composer 2
# ─────────────────────────────────────────────────────────────────────────────
install_composer() {
    step "Cài đặt Composer ${COMPOSER_MIN}.x"

    if command -v composer &>/dev/null; then
        CURRENT_COMPOSER=$(composer --version 2>/dev/null | grep -oP '\d+' | head -1)
        if [[ "$CURRENT_COMPOSER" -ge "$COMPOSER_MIN" ]]; then
            log "Composer ${CURRENT_COMPOSER}.x đã có sẵn"
            return
        fi
        warn "Composer quá cũ, cập nhật lên v2..."
    fi

    # Tải và cài Composer chính thức
    EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"
    if [[ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]]; then
        rm composer-setup.php
        die "Checksum Composer không hợp lệ. Có thể bị tấn công man-in-the-middle!"
    fi

    php composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer
    rm composer-setup.php
    log "Composer $(composer --version | grep -oP '[\d.]+' | head -1) đã cài đặt"
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 6: Cài đặt Node.js (tùy chọn, cho frontend build)
# ─────────────────────────────────────────────────────────────────────────────
install_nodejs() {
    step "Cài đặt Node.js ${NODE_MIN}+"

    if command -v node &>/dev/null; then
        CURRENT_NODE=$(node --version | grep -oP '\d+' | head -1)
        if [[ "$CURRENT_NODE" -ge "$NODE_MIN" ]]; then
            log "Node.js v${CURRENT_NODE} đã có sẵn"
            return
        fi
    fi

    case "$PKG_FAMILY" in
        debian)
            curl -fsSL "https://deb.nodesource.com/setup_${NODE_MIN}.x" | bash - 2>/dev/null
            apt-get install -y -qq nodejs
            ;;
        rhel)
            curl -fsSL "https://rpm.nodesource.com/setup_${NODE_MIN}.x" | bash - 2>/dev/null
            dnf install -y -q nodejs
            ;;
        darwin)
            brew install node@"${NODE_MIN}" || brew upgrade node || true
            ;;
    esac

    log "Node.js $(node --version) đã cài đặt"
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 7: Deploy mã nguồn NLP Group
# ─────────────────────────────────────────────────────────────────────────────
deploy_app() {
    step "Deploy mã nguồn NLP Group vào ${INSTALL_DIR}"

    # Sao lưu nếu đã tồn tại
    if [[ -d "$INSTALL_DIR" && -f "${INSTALL_DIR}/.env" ]]; then
        BACKUP_DIR="${INSTALL_DIR}_backup_$(date +%Y%m%d_%H%M%S)"
        warn "Thư mục đã tồn tại. Sao lưu vào ${BACKUP_DIR}..."
        cp -a "$INSTALL_DIR" "$BACKUP_DIR"
        log "Đã sao lưu vào ${BACKUP_DIR}"
    fi

    # Clone hoặc pull repository
    if [[ ! -d "$INSTALL_DIR/.git" ]]; then
        # Tạo thư mục và clone
        mkdir -p "$(dirname "$INSTALL_DIR")"

        if confirm "Clone từ Git repository (${REPO_URL})?"; then
            git clone --depth=1 "$REPO_URL" "$INSTALL_DIR" 2>/dev/null || {
                warn "Không thể clone từ Git. Tạo cấu trúc thư mục thủ công..."
                mkdir -p "${INSTALL_DIR}/"{public,storage,bootstrap/cache}
            }
        else
            info "Bỏ qua clone. Cài đặt .env và quyền cho thư mục hiện có."
        fi
    else
        info "Repository đã tồn tại, thực hiện git pull..."
        git -C "$INSTALL_DIR" pull --rebase 2>/dev/null || warn "git pull thất bại, tiếp tục..."
    fi

    # Đảm bảo các thư mục cần thiết tồn tại
    mkdir -p "${INSTALL_DIR}/storage/"{app/public,framework/{cache/data,sessions,views},logs}
    mkdir -p "${INSTALL_DIR}/bootstrap/cache"

    log "Mã nguồn đã được deploy"
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 8: Tạo file .env
# ─────────────────────────────────────────────────────────────────────────────
configure_env() {
    step "Cấu hình file .env"

    local ENV_FILE="${INSTALL_DIR}/.env"
    local ENV_EXAMPLE="${INSTALL_DIR}/.env.example"

    if [[ -f "$ENV_EXAMPLE" ]]; then
        cp "$ENV_EXAMPLE" "$ENV_FILE"
    else
        # Tạo .env tối thiểu nếu không có .env.example
        cat > "$ENV_FILE" <<'ENVEOF'
APP_NAME="NLP Group"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Ho_Chi_Minh

LOG_CHANNEL=stack
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
    fi

    # Ghi đè biến từ cấu hình người dùng
    APP_KEY_VALUE=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")

    set_env() {
        local KEY="$1" VAL="$2"
        if grep -q "^${KEY}=" "$ENV_FILE" 2>/dev/null; then
            # Escape ký tự đặc biệt trong sed
            VAL_ESCAPED=$(printf '%s\n' "$VAL" | sed 's/[\/&]/\\&/g')
            sed -i "s|^${KEY}=.*|${KEY}=\"${VAL_ESCAPED}\"|" "$ENV_FILE"
        else
            echo "${KEY}=\"${VAL}\"" >> "$ENV_FILE"
        fi
    }

    set_env "APP_KEY"      "$APP_KEY_VALUE"
    set_env "APP_URL"      "http://${APP_DOMAIN}"
    set_env "DB_CONNECTION" "mysql"
    set_env "DB_HOST"       "127.0.0.1"
    set_env "DB_PORT"       "3306"
    set_env "DB_DATABASE"   "$DB_DATABASE"
    set_env "DB_USERNAME"   "$DB_USERNAME"
    set_env "DB_PASSWORD"   "$DB_PASSWORD"

    chmod 640 "$ENV_FILE"
    log "File .env đã được cấu hình"
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 9: Cài đặt PHP dependencies (Composer install)
# ─────────────────────────────────────────────────────────────────────────────
composer_install() {
    step "Cài đặt PHP dependencies"

    if [[ ! -f "${INSTALL_DIR}/composer.json" ]]; then
        warn "Không tìm thấy composer.json. Bỏ qua bước này."
        return
    fi

    cd "$INSTALL_DIR"
    composer install \
        --no-dev \
        --optimize-autoloader \
        --no-interaction \
        --quiet \
        2>/dev/null || composer install --no-dev --optimize-autoloader --no-interaction
    log "PHP dependencies đã được cài đặt"
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 10: Chạy migration và seeder
# ─────────────────────────────────────────────────────────────────────────────
run_migrations() {
    step "Chạy database migration"

    cd "$INSTALL_DIR"

    # Kiểm tra kết nối DB trước
    if ! php artisan db:show --no-interaction 2>/dev/null; then
        warn "Không kết nối được database. Bỏ qua migration."
        warn "Chạy thủ công sau: php artisan migrate --seed"
        return
    fi

    php artisan migrate --force --no-interaction
    log "Migration hoàn tất"

    if confirm "Chạy database seeder (dữ liệu mẫu ban đầu)?"; then
        php artisan db:seed --no-interaction || warn "Seeder gặp lỗi, kiểm tra lại thủ công."
        log "Seeder hoàn tất"
    fi
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 11: Thiết lập quyền file và thư mục
# ─────────────────────────────────────────────────────────────────────────────
setup_permissions() {
    step "Thiết lập quyền file"

    # Thư mục cần ghi bởi web server
    local WRITABLE_DIRS=(
        "storage"
        "bootstrap/cache"
        "public/uploads"
    )

    for dir in "${WRITABLE_DIRS[@]}"; do
        local FULL_PATH="${INSTALL_DIR}/${dir}"
        mkdir -p "$FULL_PATH"
        chown -R "${APP_USER}:${APP_GROUP}" "$FULL_PATH"
        chmod -R 775 "$FULL_PATH"
    done

    # Quyền chủ sở hữu tổng thể
    chown -R "${APP_USER}:${APP_GROUP}" "$INSTALL_DIR"
    find "$INSTALL_DIR" -type f -exec chmod 644 {} \;
    find "$INSTALL_DIR" -type d -exec chmod 755 {} \;

    # Bảo vệ file nhạy cảm
    chmod 640 "${INSTALL_DIR}/.env" 2>/dev/null || true

    # Thư mục storage và cache cần 775
    chmod -R 775 "${INSTALL_DIR}/storage"
    chmod -R 775 "${INSTALL_DIR}/bootstrap/cache"

    # Artisan và scripts cần executable
    chmod +x "${INSTALL_DIR}/artisan" 2>/dev/null || true

    log "Quyền file đã được thiết lập"
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 12: Tối ưu ứng dụng Laravel
# ─────────────────────────────────────────────────────────────────────────────
optimize_app() {
    step "Tối ưu ứng dụng Laravel"

    cd "$INSTALL_DIR"

    php artisan config:cache    --no-interaction 2>/dev/null || true
    php artisan route:cache     --no-interaction 2>/dev/null || true
    php artisan view:cache      --no-interaction 2>/dev/null || true
    php artisan event:cache     --no-interaction 2>/dev/null || true
    php artisan storage:link    --no-interaction 2>/dev/null || true

    log "Ứng dụng đã được tối ưu (config/route/view cache)"
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 13: Cài đặt SSL (Let's Encrypt — tùy chọn)
# ─────────────────────────────────────────────────────────────────────────────
install_ssl() {
    [[ "${ENABLE_SSL:-false}" != "true" ]] && return

    step "Cài đặt SSL — Let's Encrypt (Certbot)"

    case "$PKG_FAMILY" in
        debian)
            apt-get install -y -qq certbot python3-certbot-nginx
            certbot --nginx \
                --non-interactive \
                --agree-tos \
                --email "${ADMIN_EMAIL}" \
                --domains "${APP_DOMAIN},www.${APP_DOMAIN}" \
                --redirect 2>/dev/null && log "SSL đã được cài đặt cho ${APP_DOMAIN}" \
                || warn "Certbot thất bại. Hãy chạy thủ công: certbot --nginx -d ${APP_DOMAIN}"
            ;;
        rhel)
            dnf install -y -q certbot python3-certbot-nginx
            certbot --nginx --non-interactive --agree-tos \
                --email "${ADMIN_EMAIL}" \
                --domains "${APP_DOMAIN}" 2>/dev/null || \
                warn "Certbot thất bại. Hãy chạy thủ công."
            ;;
        darwin)
            warn "Certbot trên macOS: dùng 'brew install certbot' và cấu hình thủ công."
            ;;
    esac

    # Tự động gia hạn SSL hàng ngày
    if command -v crontab &>/dev/null; then
        (crontab -l 2>/dev/null; echo "0 3 * * * certbot renew --quiet --no-self-upgrade") | \
            crontab - 2>/dev/null || true
        log "Cron gia hạn SSL đã được thiết lập (3:00 AM hàng ngày)"
    fi
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 14: Cấu hình Firewall cơ bản
# ─────────────────────────────────────────────────────────────────────────────
configure_firewall() {
    step "Cấu hình Firewall"

    if command -v ufw &>/dev/null; then
        ufw --force reset 2>/dev/null || true
        ufw default deny incoming
        ufw default allow outgoing
        ufw allow ssh        comment 'SSH'
        ufw allow 80/tcp     comment 'HTTP'
        ufw allow 443/tcp    comment 'HTTPS'
        ufw --force enable
        log "UFW Firewall đã bật (SSH + HTTP + HTTPS)"

    elif command -v firewall-cmd &>/dev/null; then
        systemctl enable --now firewalld 2>/dev/null || true
        firewall-cmd --permanent --add-service=ssh
        firewall-cmd --permanent --add-service=http
        firewall-cmd --permanent --add-service=https
        firewall-cmd --reload
        log "Firewalld đã bật (SSH + HTTP + HTTPS)"

    else
        warn "Không tìm thấy ufw/firewalld. Cấu hình firewall thủ công."
    fi
}

# ─────────────────────────────────────────────────────────────────────────────
# BƯỚC 15: Khởi động lại các dịch vụ
# ─────────────────────────────────────────────────────────────────────────────
restart_services() {
    step "Khởi động lại các dịch vụ"

    if [[ "$PKG_FAMILY" != "darwin" ]]; then
        local SERVICES=("nginx")

        # PHP-FPM service name khác nhau theo distro
        for svc in "php${PHP_VERSION}-fpm" "php-fpm" "phpfpm"; do
            if systemctl list-unit-files "${svc}.service" &>/dev/null; then
                SERVICES+=("$svc")
                break
            fi
        done

        for svc in "${SERVICES[@]}"; do
            systemctl enable "$svc"  2>/dev/null || true
            systemctl restart "$svc" 2>/dev/null && log "Dịch vụ ${svc} đã khởi động lại" \
                                                  || warn "Không thể khởi động lại ${svc}"
        done
    else
        brew services restart nginx 2>/dev/null || true
        brew services restart "php@${PHP_VERSION}" 2>/dev/null || true
        log "Dịch vụ Nginx và PHP-FPM đã khởi động lại (macOS)"
    fi
}

# ─────────────────────────────────────────────────────────────────────────────
# HOÀN TẤT: In tóm tắt cài đặt
# ─────────────────────────────────────────────────────────────────────────────
print_summary() {
    local PROTO="http"
    [[ "${ENABLE_SSL:-false}" == "true" ]] && PROTO="https"

    echo ""
    echo -e "${BOLD}${GREEN}"
    echo "╔══════════════════════════════════════════════════════════════╗"
    echo "║           CÀI ĐẶT NLP GROUP PLATFORM HOÀN TẤT!             ║"
    echo "╚══════════════════════════════════════════════════════════════╝"
    echo -e "${RESET}"

    echo -e "${BOLD}Thông tin truy cập:${RESET}"
    echo -e "  🌐 Website     : ${CYAN}${PROTO}://${APP_DOMAIN}${RESET}"
    echo -e "  📁 Thư mục     : ${CYAN}${INSTALL_DIR}${RESET}"
    echo -e "  🗄  Database    : ${CYAN}${DB_DATABASE}${RESET} (user: ${DB_USERNAME})"
    echo ""

    echo -e "${BOLD}Lệnh quản lý:${RESET}"
    echo -e "  ${YELLOW}cd ${INSTALL_DIR}${RESET}"
    echo -e "  ${YELLOW}php artisan list${RESET}                  # Liệt kê tất cả lệnh"
    echo -e "  ${YELLOW}php artisan optimize:clear${RESET}         # Xóa cache"
    echo -e "  ${YELLOW}php artisan queue:work${RESET}             # Chạy queue worker"
    echo ""

    echo -e "${BOLD}Logs:${RESET}"
    echo -e "  ${YELLOW}tail -f ${INSTALL_DIR}/storage/logs/laravel.log${RESET}"
    echo -e "  ${YELLOW}tail -f /var/log/nginx/${NGINX_SITE}-error.log${RESET}"
    echo ""

    if [[ "${ENABLE_SSL:-false}" != "true" ]]; then
        echo -e "${YELLOW}[!] SSL chưa được cài. Để cài SSL sau:${RESET}"
        echo -e "  ${CYAN}certbot --nginx -d ${APP_DOMAIN} --email ${ADMIN_EMAIL}${RESET}"
        echo ""
    fi

    echo -e "${GREEN}Cảm ơn bạn đã sử dụng NLP Group Platform!${RESET}"
    echo -e "${BLUE}Hỗ trợ kỹ thuật: dev@nlpgroup.vn | nlpgroup.vn${RESET}"
    echo ""
}

# ─────────────────────────────────────────────────────────────────────────────
# MAIN — Luồng thực thi chính
# ─────────────────────────────────────────────────────────────────────────────
main() {
    # Bắt lỗi và dọn dẹp khi thoát bất thường
    trap 'echo -e "\n${RED}[✗] Cài đặt bị gián đoạn tại dòng $LINENO.${RESET}"; exit 1' ERR INT TERM

    show_banner
    check_root
    detect_os

    # Thu thập cấu hình từ người dùng
    collect_config

    # Ghi log toàn bộ quá trình cài đặt
    LOG_FILE="/tmp/nlpgroup_install_$(date +%Y%m%d_%H%M%S).log"
    info "Log cài đặt được ghi vào: ${LOG_FILE}"
    exec > >(tee -a "$LOG_FILE") 2>&1

    # Các bước cài đặt theo thứ tự
    install_base_deps
    install_php
    install_mysql
    install_nodejs
    install_nginx
    install_composer
    deploy_app
    configure_env
    composer_install
    run_migrations
    setup_permissions
    optimize_app
    configure_firewall
    install_ssl
    restart_services

    print_summary

    log "Log đầy đủ: ${LOG_FILE}"
}

main "$@"
