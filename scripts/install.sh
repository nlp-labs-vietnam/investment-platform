#!/usr/bin/env bash
# =============================================================================
# install.sh — NLP Group Investment Platform — Unified Installer & CLI
# =============================================================================
#
# USAGE
#   sudo bash install.sh [COMMAND] [OPTIONS]
#
# COMMANDS
#   install          Full server install (detects OS, installs PHP/MySQL/Nginx)
#   deploy           Deploy/update app code only (no server software)
#   permissions      Fix file & directory permissions
#   cpanel           cPanel/shared-hosting install (no root required)
#   optimize         Run Laravel cache/optimize commands
#   check            System requirements check (dry-run, no changes)
#   help             Show this help message
#
# OPTIONS
#   --dir PATH       Override app install directory (default: /var/www/investment-platform)
#   --domain DOMAIN  Set domain name
#   --db-name NAME   Database name
#   --db-user USER   Database user
#   --db-pass PASS   Database password
#   --db-root PASS   MySQL root password
#   --email EMAIL    Admin email (for SSL)
#   --ssl            Enable Let's Encrypt SSL (install mode)
#   --no-color       Disable ANSI colors
#   --yes            Non-interactive: answer yes to all confirmations
#   --dry-run        Show what would be done, make no changes
#
# EXAMPLES
#   sudo bash install.sh install --domain nlpgroup.vn --ssl
#   sudo bash install.sh permissions --dir /var/www/investment-platform
#   bash install.sh cpanel --domain app.nlpgroup.vn
#   bash install.sh check
#
# Supports: Ubuntu 20-24 · Debian 11-12 · CentOS/RHEL/Rocky/AlmaLinux 8-9
#           macOS 12+ (Homebrew) · cPanel/CloudLinux
#
# Author  : NLP Group <dev@nlpgroup.vn>
# Version : 3.0.0
# =============================================================================

set -euo pipefail
IFS=$'\n\t'

# ──────────────────────────────────────────────────────────────────────────────
# 0. CONSTANTS & DEFAULTS
# ──────────────────────────────────────────────────────────────────────────────
readonly SCRIPT_VERSION="3.0.0"
readonly SCRIPT_NAME="$(basename "${BASH_SOURCE[0]}")"
readonly SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Software version requirements
readonly PHP_VERSION="8.2"
readonly PHP_MIN_MAJOR=8
readonly PHP_MIN_MINOR=1
readonly NODE_MIN=18
readonly COMPOSER_MIN=2

# Project identity
readonly REPO_OWNER="nlp-labs-vietnam"
readonly REPO_NAME="investment-platform"
readonly HOMEPAGE_URL="https://nlp-labs-vietnam.github.io/investment-platform"

# Default configuration (overridable via CLI flags or interactive prompts)
INSTALL_DIR="/var/www/investment-platform"
APP_DOMAIN=""
DB_DATABASE=""
DB_USERNAME=""
DB_PASSWORD=""
DB_ROOT_PASS=""
DB_HOST="127.0.0.1"
ADMIN_EMAIL=""
ENABLE_SSL=false
REPO_URL="https://github.com/${REPO_OWNER}/${REPO_NAME}.git"
NGINX_SITE="nlpgroup"

# Runtime state
USE_COLOR=true
YES_MODE=false
DRY_RUN=false
COMMAND=""
OS_ID="" OS_VERSION="" OS_NAME="" PKG_MGR="" PKG_FAMILY=""
PHP_BIN="" COMPOSER_BIN=""
LOG_FILE=""
IS_ROOT=false
IS_CPANEL=false

# ──────────────────────────────────────────────────────────────────────────────
# 1. TERMINAL OUTPUT
# ──────────────────────────────────────────────────────────────────────────────
_init_colors() {
    if [[ "$USE_COLOR" == true ]] && [[ -t 1 ]]; then
        RED='\033[0;31m';  GREEN='\033[0;32m';  YELLOW='\033[1;33m'
        BLUE='\033[0;34m'; CYAN='\033[0;36m';   BOLD='\033[1m';  RESET='\033[0m'
        DIM='\033[2m'
    else
        RED=''; GREEN=''; YELLOW=''; BLUE=''; CYAN=''; BOLD=''; RESET=''; DIM=''
    fi
}

log()     { echo -e "${GREEN}[✔]${RESET} $*"; }
info()    { echo -e "${BLUE}[→]${RESET} $*"; }
warn()    { echo -e "${YELLOW}[!]${RESET} $*"; }
error()   { echo -e "${RED}[✗]${RESET} $*" >&2; }
die()     { error "$*"; exit 1; }
dryrun()  { echo -e "${DIM}[dry-run]${RESET} $*"; }

step() {
    echo -e "\n${BOLD}${CYAN}══════════════════════════════════════════════${RESET}"
    echo -e "${BOLD}${CYAN}  $*${RESET}"
    echo -e "${BOLD}${CYAN}══════════════════════════════════════════════${RESET}"
}

confirm() {
    [[ "$YES_MODE" == true ]] && return 0
    local reply
    read -r -p "$(echo -e "${YELLOW}[?]${RESET} $* [y/N] ")" reply
    [[ "${reply,,}" == "y" || "${reply,,}" == "yes" ]]
}

run() {
    # Wrapper: print + execute (or just print in dry-run)
    if [[ "$DRY_RUN" == true ]]; then
        dryrun "$*"
    else
        eval "$@"
    fi
}

# ──────────────────────────────────────────────────────────────────────────────
# 2. ARGUMENT PARSING
# ──────────────────────────────────────────────────────────────────────────────
parse_args() {
    # First positional arg = command
    if [[ $# -gt 0 && ! "$1" =~ ^-- ]]; then
        COMMAND="$1"
        shift
    else
        COMMAND="help"
    fi

    while [[ $# -gt 0 ]]; do
        case "$1" in
            --dir)         INSTALL_DIR="$2";   shift 2 ;;
            --domain)      APP_DOMAIN="$2";    shift 2 ;;
            --db-name)     DB_DATABASE="$2";   shift 2 ;;
            --db-user)     DB_USERNAME="$2";   shift 2 ;;
            --db-pass)     DB_PASSWORD="$2";   shift 2 ;;
            --db-root)     DB_ROOT_PASS="$2";  shift 2 ;;
            --db-host)     DB_HOST="$2";       shift 2 ;;
            --email)       ADMIN_EMAIL="$2";   shift 2 ;;
            --ssl)         ENABLE_SSL=true;    shift ;;
            --no-color)    USE_COLOR=false;    shift ;;
            --yes|-y)      YES_MODE=true;      shift ;;
            --dry-run)     DRY_RUN=true;       shift ;;
            --version|-v)  echo "$SCRIPT_NAME v$SCRIPT_VERSION"; exit 0 ;;
            --help|-h)     COMMAND="help";     shift ;;
            -*)            warn "Unknown option: $1"; shift ;;
            *)             shift ;;
        esac
    done
}

# ──────────────────────────────────────────────────────────────────────────────
# 3. AUTO-DETECT: OS / ENVIRONMENT
# ──────────────────────────────────────────────────────────────────────────────
detect_os() {
    # cPanel / CloudLinux fingerprint
    if [[ -f /usr/local/cpanel/cpanel || -d /usr/local/cpanel ]]; then
        IS_CPANEL=true
    fi

    if [[ -f /etc/os-release ]]; then
        # shellcheck source=/dev/null
        source /etc/os-release
        OS_ID="${ID,,}"
        OS_VERSION="${VERSION_ID%%.*}"
        OS_NAME="${PRETTY_NAME:-$ID $VERSION_ID}"
    elif [[ "$(uname -s)" == "Darwin" ]]; then
        OS_ID="darwin"
        OS_NAME="macOS $(sw_vers -productVersion 2>/dev/null || uname -r)"
        OS_VERSION="$(sw_vers -productVersion 2>/dev/null | cut -d. -f1 || echo '0')"
    elif [[ -f /etc/redhat-release ]]; then
        OS_ID="rhel"
        OS_NAME="$(cat /etc/redhat-release)"
        OS_VERSION="$(grep -oP '\d+' /etc/redhat-release | head -1)"
    else
        die "Cannot detect OS. Supported: Ubuntu/Debian/CentOS/RHEL/Rocky/AlmaLinux/macOS."
    fi

    # Map to package manager family
    case "$OS_ID" in
        ubuntu|debian|linuxmint|pop|elementary|kali)
            PKG_MGR="apt"; PKG_FAMILY="debian" ;;
        centos|rhel|almalinux|rocky|ol|cloudlinux)
            PKG_MGR="dnf"; PKG_FAMILY="rhel"
            # CentOS 7 / RHEL 7 still uses yum
            [[ "$OS_VERSION" == "7" ]] && PKG_MGR="yum"
            ;;
        fedora)
            PKG_MGR="dnf"; PKG_FAMILY="rhel" ;;
        darwin)
            PKG_MGR="brew"; PKG_FAMILY="darwin" ;;
        *)
            warn "Unknown OS '$OS_ID'. Trying to auto-detect package manager..."
            if   command -v apt-get &>/dev/null; then PKG_MGR="apt";  PKG_FAMILY="debian"
            elif command -v dnf     &>/dev/null; then PKG_MGR="dnf";  PKG_FAMILY="rhel"
            elif command -v yum     &>/dev/null; then PKG_MGR="yum";  PKG_FAMILY="rhel"
            else die "No supported package manager found (apt/dnf/yum/brew)."; fi
            ;;
    esac

    [[ $EUID -eq 0 ]] && IS_ROOT=true || IS_ROOT=false

    log "OS : ${OS_NAME}"
    log "Pkg: ${PKG_MGR} | Family: ${PKG_FAMILY} | Root: ${IS_ROOT} | cPanel: ${IS_CPANEL}"
}

# ──────────────────────────────────────────────────────────────────────────────
# 4. AUTO-DETECT: PHP
# ──────────────────────────────────────────────────────────────────────────────
detect_php() {
    local candidates=(
        "$(command -v "php${PHP_VERSION}" 2>/dev/null || true)"
        "$(command -v php 2>/dev/null || true)"
        "/usr/bin/php${PHP_VERSION}"
        "/usr/local/bin/php"
        # cPanel EasyApache paths
        "/opt/cpanel/ea-php82/root/usr/bin/php"
        "/opt/cpanel/ea-php81/root/usr/bin/php"
        "/opt/cpanel/ea-php80/root/usr/bin/php"
        # Homebrew macOS
        "/opt/homebrew/bin/php"
        "/usr/local/opt/php@${PHP_VERSION}/bin/php"
    )

    PHP_BIN=""
    for c in "${candidates[@]}"; do
        [[ -z "$c" || ! -x "$c" ]] && continue
        local ver
        ver=$("$c" -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;' 2>/dev/null || echo "0.0")
        local major minor
        major="${ver%%.*}"; minor="${ver##*.}"
        if [[ $major -gt $PHP_MIN_MAJOR ]] || \
           [[ $major -eq $PHP_MIN_MAJOR && $minor -ge $PHP_MIN_MINOR ]]; then
            PHP_BIN="$c"
            log "PHP ${ver} → ${PHP_BIN}"
            return 0
        fi
    done

    PHP_BIN=""
    return 1
}

# ──────────────────────────────────────────────────────────────────────────────
# 5. AUTO-DETECT: COMPOSER
# ──────────────────────────────────────────────────────────────────────────────
detect_composer() {
    local candidates=(
        "$(command -v composer 2>/dev/null || true)"
        "${HOME}/composer.phar"
        "/usr/local/bin/composer"
        "${INSTALL_DIR}/composer.phar"
    )

    COMPOSER_BIN=""
    for c in "${candidates[@]}"; do
        [[ -z "$c" ]] && continue
        # Handle .phar (needs php prefix)
        local bin="$c"
        [[ "$c" == *.phar ]] && [[ -n "$PHP_BIN" ]] && bin="$PHP_BIN $c"
        local ver
        ver=$(eval "$bin --version 2>/dev/null" | grep -oP '\d+' | head -1 || echo "0")
        if [[ "$ver" -ge $COMPOSER_MIN ]]; then
            COMPOSER_BIN="$bin"
            log "Composer v${ver} → ${c}"
            return 0
        fi
    done

    COMPOSER_BIN=""
    return 1
}

# ──────────────────────────────────────────────────────────────────────────────
# 6. INTERACTIVE CONFIG COLLECTION
# ──────────────────────────────────────────────────────────────────────────────
_ask() {
    # _ask VAR_NAME "Prompt text" "default_value"
    local var="$1" prompt="$2" default="${3:-}"
    if [[ -n "${!var:-}" ]]; then return; fi  # skip if already set via flag
    local reply
    if [[ -n "$default" ]]; then
        read -r -p "$(echo -e "${BLUE}[→]${RESET} ${prompt} [${default}]: ")" reply
        printf -v "$var" '%s' "${reply:-$default}"
    else
        read -r -p "$(echo -e "${BLUE}[→]${RESET} ${prompt}: ")" reply
        printf -v "$var" '%s' "$reply"
    fi
}

_ask_pass() {
    local var="$1" prompt="$2"
    if [[ -n "${!var:-}" ]]; then return; fi
    local pass
    while true; do
        read -r -s -p "$(echo -e "${BLUE}[→]${RESET} ${prompt}: ")" pass; echo
        [[ ${#pass} -ge 8 ]] && break
        warn "Password too short (min 8 characters). Try again."
    done
    printf -v "$var" '%s' "$pass"
}

collect_config() {
    step "Configuration"
    [[ "$YES_MODE" == true ]] && { _apply_config_defaults; return; }

    _ask APP_DOMAIN  "Domain name"            "nlpgroup.vn"
    _ask DB_DATABASE "MySQL database name"    "nlpgroup_db"
    _ask DB_USERNAME "MySQL user name"        "nlp_user"
    _ask_pass DB_PASSWORD "MySQL user password (min 8 chars)"
    _ask DB_ROOT_PASS "MySQL root password (leave blank to skip)" ""
    _ask DB_HOST     "MySQL host"             "127.0.0.1"
    _ask ADMIN_EMAIL "Admin email"            "admin@nlpgroup.vn"

    if [[ "$ENABLE_SSL" != "true" ]]; then
        confirm "Enable free SSL (Let's Encrypt) for ${APP_DOMAIN}?" && ENABLE_SSL=true
    fi

    echo ""
    echo -e "${BOLD}Summary:${RESET}"
    echo -e "  Install dir : ${CYAN}${INSTALL_DIR}${RESET}"
    echo -e "  Domain      : ${CYAN}${APP_DOMAIN}${RESET}"
    echo -e "  Database    : ${CYAN}${DB_DATABASE}${RESET}"
    echo -e "  DB User     : ${CYAN}${DB_USERNAME}${RESET}"
    echo -e "  DB Host     : ${CYAN}${DB_HOST}${RESET}"
    echo -e "  Admin email : ${CYAN}${ADMIN_EMAIL}${RESET}"
    echo -e "  SSL         : ${CYAN}$(${ENABLE_SSL} && echo 'Yes' || echo 'No')${RESET}"
    echo ""
    confirm "Continue with installation?" || die "Installation cancelled."
}

collect_config_cpanel() {
    step "cPanel Configuration"
    echo -e "${YELLOW}Note:${RESET} Create MySQL Database & User in cPanel → MySQL Databases first.\n"

    _ask INSTALL_DIR "Install directory"         "${HOME}/public_html/nlpgroup"
    _ask APP_DOMAIN  "Domain / subdomain"        "nlpgroup.yourdomain.com"
    _ask DB_DATABASE "MySQL database (cPanel)"   ""
    [[ -z "$DB_DATABASE" ]] && die "Database name is required."
    _ask DB_USERNAME "MySQL user (cPanel)"       ""
    [[ -z "$DB_USERNAME" ]] && die "Database user is required."
    _ask_pass DB_PASSWORD "MySQL password (min 8 chars)"
    _ask DB_HOST     "DB Host"                   "localhost"
    _ask ADMIN_EMAIL "Admin email"               "admin@nlpgroup.vn"

    echo ""
    echo -e "${BOLD}Summary:${RESET}"
    echo -e "  Install dir : ${CYAN}${INSTALL_DIR}${RESET}"
    echo -e "  Domain      : ${CYAN}${APP_DOMAIN}${RESET}"
    echo -e "  Database    : ${CYAN}${DB_DATABASE}${RESET}"
    echo -e "  Admin email : ${CYAN}${ADMIN_EMAIL}${RESET}"
    echo ""
    confirm "Continue?" || die "Installation cancelled."
}

_apply_config_defaults() {
    APP_DOMAIN="${APP_DOMAIN:-nlpgroup.vn}"
    DB_DATABASE="${DB_DATABASE:-nlpgroup_db}"
    DB_USERNAME="${DB_USERNAME:-nlp_user}"
    DB_PASSWORD="${DB_PASSWORD:-$(openssl rand -base64 16 | tr -d '/+=' | head -c 20)}"
    ADMIN_EMAIL="${ADMIN_EMAIL:-admin@nlpgroup.vn}"
    info "Using defaults (--yes mode). DB password auto-generated if not set."
}

# ──────────────────────────────────────────────────────────────────────────────
# 7. INSTALLATION STEPS
# ──────────────────────────────────────────────────────────────────────────────

# ── 7a. Base system packages ──────────────────────────────────────────────────
install_base_deps() {
    step "System packages"
    case "$PKG_FAMILY" in
        debian)
            export DEBIAN_FRONTEND=noninteractive
            run "apt-get update -qq"
            run "apt-get install -y -qq curl wget git unzip zip gnupg2 lsb-release \
                software-properties-common ca-certificates apt-transport-https \
                cron logrotate acl"
            ;;
        rhel)
            run "dnf update -y -q" 2>/dev/null || run "yum update -y -q"
            run "dnf install -y -q curl wget git unzip zip gnupg2 \
                ca-certificates cronie logrotate" 2>/dev/null || \
            run "yum install -y -q curl wget git unzip zip gnupg2 ca-certificates cronie logrotate"
            ;;
        darwin)
            if ! command -v brew &>/dev/null; then
                info "Installing Homebrew..."
                run "/bin/bash -c \"\$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)\""
            fi
            run "brew update -q"
            run "brew install git curl wget unzip"
            ;;
    esac
    log "Base packages installed"
}

# ── 7b. PHP ───────────────────────────────────────────────────────────────────
install_php() {
    step "PHP ${PHP_VERSION}"

    # Check if already satisfied
    if detect_php; then
        log "PHP already available, skipping install"
        return 0
    fi

    case "$PKG_FAMILY" in
        debian)
            if [[ "$OS_ID" == "ubuntu" ]]; then
                run "LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php 2>/dev/null || true"
            else
                run "curl -sSL https://packages.sury.org/php/apt.gpg \
                    | gpg --dearmor -o /usr/share/keyrings/sury-php.gpg 2>/dev/null || true"
                run "echo 'deb [signed-by=/usr/share/keyrings/sury-php.gpg] \
                    https://packages.sury.org/php/ \$(lsb_release -cs) main' \
                    > /etc/apt/sources.list.d/sury-php.list"
            fi
            run "apt-get update -qq"
            run "apt-get install -y -qq \
                php${PHP_VERSION} php${PHP_VERSION}-fpm php${PHP_VERSION}-cli \
                php${PHP_VERSION}-mysql php${PHP_VERSION}-mbstring php${PHP_VERSION}-xml \
                php${PHP_VERSION}-curl php${PHP_VERSION}-zip php${PHP_VERSION}-bcmath \
                php${PHP_VERSION}-gd php${PHP_VERSION}-intl php${PHP_VERSION}-redis \
                php${PHP_VERSION}-opcache php${PHP_VERSION}-tokenizer"
            ;;
        rhel)
            if [[ ! -f /etc/yum.repos.d/remi.repo ]]; then
                local remi_url="https://rpms.remirepo.net/enterprise/remi-release-${OS_VERSION}.rpm"
                run "dnf install -y -q '${remi_url}' 2>/dev/null || \
                    dnf install -y -q 'https://rpms.remirepo.net/fedora/remi-release-\$(rpm -E %fedora).rpm'"
            fi
            run "dnf module reset php -y -q 2>/dev/null || true"
            run "dnf module enable 'php:remi-${PHP_VERSION}' -y -q"
            run "dnf install -y -q php php-fpm php-cli php-mysqlnd \
                php-mbstring php-xml php-curl php-zip php-bcmath php-gd php-intl php-opcache"
            ;;
        darwin)
            run "brew install 'php@${PHP_VERSION}' 2>/dev/null || \
                brew upgrade 'php@${PHP_VERSION}' 2>/dev/null || true"
            run "brew link --overwrite --force 'php@${PHP_VERSION}' 2>/dev/null || true"
            ;;
    esac

    detect_php || die "PHP installation failed. Check logs."
    log "PHP installed: $("$PHP_BIN" -r 'echo PHP_VERSION;')"
}

# ── 7c. MySQL ─────────────────────────────────────────────────────────────────
install_mysql() {
    step "MySQL 8"

    if command -v mysql &>/dev/null; then
        log "MySQL already installed: $(mysql --version 2>/dev/null | grep -oP '[\d.]+' | head -1)"
        _setup_mysql_db
        return 0
    fi

    case "$PKG_FAMILY" in
        debian)
            local deb="/tmp/mysql-apt-config.deb"
            run "wget -q -O '${deb}' 'https://dev.mysql.com/get/mysql-apt-config_0.8.32-1_all.deb' 2>/dev/null || true"
            if [[ -f "$deb" ]]; then
                run "DEBIAN_FRONTEND=noninteractive dpkg -i '${deb}' 2>/dev/null || true"
                run "apt-get update -qq"
            fi
            if [[ -n "${DB_ROOT_PASS:-}" ]]; then
                run "debconf-set-selections <<< 'mysql-server mysql-server/root_password password ${DB_ROOT_PASS}'"
                run "debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password ${DB_ROOT_PASS}'"
            fi
            run "apt-get install -y -qq mysql-server mysql-client"
            ;;
        rhel)
            run "dnf install -y -q mysql-server mysql"
            run "systemctl enable mysqld && systemctl start mysqld"
            if [[ -n "${DB_ROOT_PASS:-}" ]]; then
                local tmp_pass
                tmp_pass=$(grep 'temporary password' /var/log/mysqld.log 2>/dev/null | tail -1 | awk '{print $NF}' || true)
                if [[ -n "${tmp_pass:-}" ]]; then
                    run "mysql -u root -p'${tmp_pass}' --connect-expired-password \
                        -e \"ALTER USER 'root'@'localhost' IDENTIFIED BY '${DB_ROOT_PASS}';\" 2>/dev/null || true"
                fi
            fi
            ;;
        darwin)
            run "brew install mysql@8.0 2>/dev/null || brew upgrade mysql@8.0 || true"
            run "brew services start mysql@8.0"
            ;;
    esac

    if [[ "$PKG_FAMILY" != "darwin" ]]; then
        run "systemctl enable mysql 2>/dev/null || systemctl enable mysqld 2>/dev/null || true"
        run "systemctl start  mysql 2>/dev/null || systemctl start  mysqld 2>/dev/null || true"
    fi

    log "MySQL installed"
    _setup_mysql_db
}

_setup_mysql_db() {
    step "Create database & user"

    local mysql_cmd="mysql"
    [[ -n "${DB_ROOT_PASS:-}" ]] && mysql_cmd="mysql -uroot -p${DB_ROOT_PASS}"

    if ! eval "$mysql_cmd -e 'SELECT 1;'" &>/dev/null; then
        warn "Cannot connect to MySQL. Create database manually:"
        echo -e "  ${CYAN}CREATE DATABASE \`${DB_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;${RESET}"
        echo -e "  ${CYAN}CREATE USER '${DB_USERNAME}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';${RESET}"
        echo -e "  ${CYAN}GRANT ALL PRIVILEGES ON \`${DB_DATABASE}\`.* TO '${DB_USERNAME}'@'localhost';${RESET}"
        echo -e "  ${CYAN}FLUSH PRIVILEGES;${RESET}"
        return 0
    fi

    run "eval \"$mysql_cmd\" <<SQL
CREATE DATABASE IF NOT EXISTS \\\`${DB_DATABASE}\\\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USERNAME}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON \\\`${DB_DATABASE}\\\`.* TO '${DB_USERNAME}'@'localhost';
FLUSH PRIVILEGES;
SQL"
    log "Database '${DB_DATABASE}' and user '${DB_USERNAME}' created"
}

# ── 7d. Nginx ─────────────────────────────────────────────────────────────────
install_nginx() {
    step "Nginx"

    if ! command -v nginx &>/dev/null; then
        case "$PKG_FAMILY" in
            debian) run "apt-get install -y -qq nginx" ;;
            rhel)   run "dnf install -y -q nginx && systemctl enable nginx" ;;
            darwin) run "brew install nginx" ;;
        esac
        log "Nginx installed"
    else
        log "Nginx already installed"
    fi

    _configure_nginx

    if [[ "$PKG_FAMILY" != "darwin" ]]; then
        run "systemctl enable nginx && systemctl restart nginx"
    fi
}

_configure_nginx() {
    local conf_dir sock
    case "$PKG_FAMILY" in
        debian)
            conf_dir="/etc/nginx/sites-available"
            sock="/run/php/php${PHP_VERSION}-fpm.sock"
            ;;
        rhel)
            conf_dir="/etc/nginx/conf.d"
            sock="/run/php-fpm/www.sock"
            ;;
        darwin)
            conf_dir="$(brew --prefix 2>/dev/null)/etc/nginx/servers"
            sock="/tmp/php-fpm.sock"
            ;;
        *)
            conf_dir="/etc/nginx/conf.d"
            sock="/run/php-fpm/www.sock"
            ;;
    esac

    run "mkdir -p '${conf_dir}'"

    if [[ "$DRY_RUN" != true ]]; then
        cat > "${conf_dir}/${NGINX_SITE}" <<NGINX
# NLP Group Platform — Nginx Virtual Host
# Auto-generated by install.sh v${SCRIPT_VERSION}

server {
    listen 80;
    listen [::]:80;
    server_name ${APP_DOMAIN} www.${APP_DOMAIN};

    root ${INSTALL_DIR}/public;
    index index.php index.html;

    charset utf-8;
    server_tokens off;
    client_max_body_size 64M;

    # Security headers
    add_header X-Frame-Options           "SAMEORIGIN"                          always;
    add_header X-XSS-Protection          "1; mode=block"                       always;
    add_header X-Content-Type-Options    "nosniff"                             always;
    add_header Referrer-Policy           "strict-origin-when-cross-origin"     always;
    add_header Permissions-Policy        "geolocation=(), microphone=()"       always;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        fastcgi_pass unix:${sock};
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 120;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
    }

    location ~* \.(css|js|jpg|jpeg|gif|png|ico|svg|woff|woff2|ttf|eot|webp|avif)\$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Block dotfiles (except .well-known for ACME challenges)
    location ~ /\.(?!well-known) { deny all; }

    # Block direct access to sensitive paths
    location ~* ^/(storage|bootstrap/cache|vendor)/ { deny all; }
    location = /.env { deny all; }

    access_log /var/log/nginx/${NGINX_SITE}-access.log;
    error_log  /var/log/nginx/${NGINX_SITE}-error.log;
}
NGINX
    fi

    if [[ "$PKG_FAMILY" == "debian" ]]; then
        run "ln -sf '${conf_dir}/${NGINX_SITE}' '/etc/nginx/sites-enabled/${NGINX_SITE}'"
        run "rm -f /etc/nginx/sites-enabled/default 2>/dev/null || true"
    fi

    run "nginx -t" && log "Nginx config valid" || die "Nginx config invalid. Run: nginx -t"
}

# ── 7e. Composer ──────────────────────────────────────────────────────────────
install_composer() {
    step "Composer ${COMPOSER_MIN}.x"

    if detect_composer; then
        log "Composer already available"
        return 0
    fi

    info "Downloading Composer..."
    local php="${PHP_BIN:-php}"

    # Verify installer checksum
    local expected actual
    expected=$("$php" -r 'copy("https://composer.github.io/installer.sig","php://stdout");' 2>/dev/null || true)
    "$php" -r "copy('https://getcomposer.org/installer','composer-setup.php');"

    if [[ -n "$expected" ]]; then
        actual=$("$php" -r "echo hash_file('sha384','composer-setup.php');")
        if [[ "$expected" != "$actual" ]]; then
            rm -f composer-setup.php
            die "Composer installer checksum INVALID. Possible MITM attack!"
        fi
    fi

    local install_dir="/usr/local/bin"
    [[ "$IS_ROOT" != true ]] && install_dir="${HOME}"

    run "'${php}' composer-setup.php --quiet --install-dir='${install_dir}' --filename=composer"
    run "rm -f composer-setup.php"

    detect_composer || die "Composer installation failed."
    log "Composer installed"
}

# ── 7f. Node.js ───────────────────────────────────────────────────────────────
install_nodejs() {
    step "Node.js ${NODE_MIN}+"

    if command -v node &>/dev/null; then
        local cur
        cur=$(node --version 2>/dev/null | grep -oP '\d+' | head -1 || echo "0")
        if [[ "$cur" -ge $NODE_MIN ]]; then
            log "Node.js v${cur} already installed"
            return 0
        fi
        warn "Node.js v${cur} is too old (need ${NODE_MIN}+)"
    fi

    case "$PKG_FAMILY" in
        debian)
            run "curl -fsSL 'https://deb.nodesource.com/setup_${NODE_MIN}.x' | bash - 2>/dev/null"
            run "apt-get install -y -qq nodejs"
            ;;
        rhel)
            run "curl -fsSL 'https://rpm.nodesource.com/setup_${NODE_MIN}.x' | bash - 2>/dev/null"
            run "dnf install -y -q nodejs 2>/dev/null || yum install -y -q nodejs"
            ;;
        darwin)
            run "brew install node@${NODE_MIN} 2>/dev/null || brew upgrade node || true"
            ;;
    esac

    log "Node.js $(node --version 2>/dev/null) installed"
}

# ── 7g. Deploy application code ───────────────────────────────────────────────
deploy_app() {
    step "Deploy application → ${INSTALL_DIR}"

    # Backup .env if exists
    if [[ -f "${INSTALL_DIR}/.env" ]]; then
        local bak="/tmp/.env.backup.$(date +%Y%m%d_%H%M%S)"
        run "cp '${INSTALL_DIR}/.env' '${bak}'"
        warn ".env backed up to ${bak}"
    fi

    if [[ ! -d "${INSTALL_DIR}/.git" ]]; then
        run "mkdir -p '$(dirname "${INSTALL_DIR}")'"
        if confirm "Clone from Git (${REPO_URL})?"; then
            run "git clone --depth=1 '${REPO_URL}' '${INSTALL_DIR}'" || {
                warn "Clone failed. Creating directory structure manually..."
                run "mkdir -p '${INSTALL_DIR}/'{public,storage,bootstrap/cache}"
            }
        else
            info "Skipping clone. Configuring existing directory."
        fi
    else
        info "Git repository exists, pulling latest..."
        run "git -C '${INSTALL_DIR}' fetch --quiet"
        run "git -C '${INSTALL_DIR}' pull --rebase 2>/dev/null || true"
    fi

    # Ensure required directories
    run "mkdir -p '${INSTALL_DIR}/storage/'{app/public,framework/{cache/data,sessions,views},logs}"
    run "mkdir -p '${INSTALL_DIR}/bootstrap/cache'"
    run "mkdir -p '${INSTALL_DIR}/public/uploads'"
    run "mkdir -p '${INSTALL_DIR}/exports'"

    log "Application code deployed"
}

# ── 7h. Configure .env ────────────────────────────────────────────────────────
configure_env() {
    step "Configure .env"

    local env_file="${INSTALL_DIR}/.env"
    local env_example="${INSTALL_DIR}/.env.example"
    local php="${PHP_BIN:-php}"

    # Generate APP_KEY
    local app_key
    app_key=$("$php" -r "echo 'base64:'.base64_encode(random_bytes(32));" 2>/dev/null || \
              openssl rand -base64 32 | tr -d '\n' | sed 's/^/base64:/')

    if [[ -f "$env_example" && ! -f "$env_file" ]]; then
        run "cp '${env_example}' '${env_file}'"
        log "Copied from .env.example"
    elif [[ ! -f "$env_file" ]]; then
        if [[ "$DRY_RUN" != true ]]; then
            cat > "$env_file" <<ENVEOF
APP_NAME="NLP Group"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Ho_Chi_Minh
LOG_CHANNEL=daily
LOG_LEVEL=error
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
BROADCAST_DRIVER=log
FILESYSTEM_DISK=local
MAIL_MAILER=smtp
MAIL_PORT=587
MAIL_ENCRYPTION=tls
ENVEOF
        fi
        log "Created minimal .env"
    fi

    # Helper: set or append a key=value in .env
    _set_env() {
        local key="$1" val="$2"
        [[ "$DRY_RUN" == true ]] && { dryrun "  ${key}=\"${val}\""; return; }
        local esc; esc=$(printf '%s\n' "$val" | sed 's/[\/&]/\\&/g')
        if grep -q "^${key}=" "$env_file" 2>/dev/null; then
            sed -i "s|^${key}=.*|${key}=\"${esc}\"|" "$env_file"
        else
            echo "${key}=\"${val}\"" >> "$env_file"
        fi
    }

    _set_env "APP_KEY"       "$app_key"
    _set_env "APP_ENV"       "production"
    _set_env "APP_DEBUG"     "false"
    _set_env "APP_URL"       "http${ENABLE_SSL:+s}://${APP_DOMAIN}"
    _set_env "DB_CONNECTION" "mysql"
    _set_env "DB_HOST"       "${DB_HOST}"
    _set_env "DB_PORT"       "3306"
    _set_env "DB_DATABASE"   "${DB_DATABASE}"
    _set_env "DB_USERNAME"   "${DB_USERNAME}"
    _set_env "DB_PASSWORD"   "${DB_PASSWORD}"

    run "chmod 640 '${env_file}' 2>/dev/null || true"
    log ".env configured and secured (640)"
}

# ── 7i. Composer install ──────────────────────────────────────────────────────
composer_install() {
    step "PHP dependencies (Composer)"

    [[ ! -f "${INSTALL_DIR}/composer.json" ]] && { warn "composer.json not found, skipping"; return; }

    run "cd '${INSTALL_DIR}' && COMPOSER_MEMORY_LIMIT=-1 ${COMPOSER_BIN} install \
        --no-dev --optimize-autoloader --no-interaction --quiet 2>/dev/null || \
        COMPOSER_MEMORY_LIMIT=-1 ${COMPOSER_BIN} install \
        --no-dev --optimize-autoloader --no-interaction"
    log "PHP dependencies installed"
}

# ── 7j. Migrations ────────────────────────────────────────────────────────────
run_migrations() {
    step "Database migrations"

    local php="${PHP_BIN:-php}"
    cd "$INSTALL_DIR"

    if ! "$php" artisan db:show --no-interaction &>/dev/null; then
        warn "Cannot connect to database. Run manually: php artisan migrate --seed"
        return 0
    fi

    run "'${php}' artisan migrate --force --no-interaction"
    log "Migrations complete"

    if confirm "Run database seeders (initial data)?"; then
        run "'${php}' artisan db:seed --no-interaction 2>/dev/null || true"
        log "Seeders complete"
    fi
}

# ── 7k. Fix permissions ───────────────────────────────────────────────────────
setup_permissions() {
    step "File & directory permissions"

    local app="$INSTALL_DIR"
    local web_user dev_group

    # Auto-detect web user based on environment
    if [[ "$IS_CPANEL" == true ]]; then
        # cPanel: current user owns files, no shared group needed
        web_user="$(whoami)"
        dev_group="$(id -gn)"
    elif [[ "$PKG_FAMILY" == "darwin" ]]; then
        web_user="$(whoami)"
        dev_group="staff"
    else
        web_user="www-data"
        dev_group="developers"
        # Create group if missing (root only)
        if [[ "$IS_ROOT" == true ]]; then
            if ! getent group "$dev_group" &>/dev/null; then
                run "groupadd '${dev_group}'"
                log "Group '${dev_group}' created"
            fi
            run "usermod -aG '${dev_group}' '${web_user}' 2>/dev/null || true"
            # Add current deployer user if different from root
            if [[ -n "${SUDO_USER:-}" && "$SUDO_USER" != "root" ]]; then
                run "usermod -aG '${dev_group}' '${SUDO_USER}' 2>/dev/null || true"
                log "${SUDO_USER} added to group ${dev_group}"
            fi
        fi
    fi

    info "Owner: ${web_user}:${dev_group} | App: ${app}"

    # 1. Ownership
    if [[ "$IS_ROOT" == true || "$IS_CPANEL" == true ]]; then
        run "chown -R '${web_user}:${dev_group}' '${app}' 2>/dev/null || \
             chown -R '${web_user}' '${app}'"
    fi

    # 2. Baseline permissions (skip .git and node_modules for speed)
    run "find '${app}' \
        -not \( -path '${app}/.git' -prune \) \
        -not \( -path '${app}/node_modules' -prune \) \
        -type d -exec chmod 755 {} +"
    run "find '${app}' \
        -not \( -path '${app}/.git' -prune \) \
        -not \( -path '${app}/node_modules' -prune \) \
        -type f -exec chmod 644 {} +"

    # .git: owner-only
    [[ -d "${app}/.git" ]] && run "chmod -R u+rwX,go-rwx '${app}/.git'"

    # 3. Writable directories (setgid on Linux so new files inherit group)
    local writable_dirs=("storage" "bootstrap/cache" "exports" "public/exports" "public/uploads")
    for d in "${writable_dirs[@]}"; do
        local full="${app}/${d}"
        [[ ! -d "$full" ]] && continue
        run "find '${full}' -type d -exec chmod 775 {} +"
        run "find '${full}' -type f -exec chmod 664 {} +"
        if [[ "$PKG_FAMILY" != "darwin" ]]; then
            run "find '${full}' -type d -exec chmod g+s {} +"
        fi
    done

    # 4. Sensitive files → 640
    local sensitive_files=(".env" "firebase-service.json")
    for f in "${sensitive_files[@]}"; do
        [[ -f "${app}/${f}" ]] && run "chmod 640 '${app}/${f}'"
    done
    run "find '${app}' -maxdepth 1 -name '*.sql' -exec chmod 640 {} +"
    run "find '${app}/public' -maxdepth 2 -name '*.tar.gz' -exec chmod 640 {} + 2>/dev/null || true"
    run "find '${app}/public' -maxdepth 1 -type d -name 'backup_*' -exec chmod 750 {} + 2>/dev/null || true"

    # 5. Executable scripts
    run "chmod 750 '${app}/artisan' 2>/dev/null || true"
    run "find '${app}' -maxdepth 1 -name '*.sh' -exec chmod 750 {} +"

    # 6. Verify write access
    if [[ "$DRY_RUN" != true ]]; then
        local test_file="${app}/storage/logs/.perm_test_$$"
        if [[ "$IS_CPANEL" == true ]] || [[ "$(whoami)" == "$web_user" ]]; then
            touch "$test_file" 2>/dev/null && rm -f "$test_file" \
                && log "Write test: OK (${web_user})" \
                || warn "Write test FAILED for ${web_user} on storage/logs/"
        elif [[ "$IS_ROOT" == true ]]; then
            sudo -u "$web_user" touch "$test_file" 2>/dev/null && rm -f "$test_file" \
                && log "Write test: OK (${web_user})" \
                || warn "Write test FAILED for ${web_user} on storage/logs/"
        fi
    fi

    log "Permissions applied"
}

# ── 7l. Laravel optimize ──────────────────────────────────────────────────────
optimize_app() {
    step "Laravel optimizations"

    local php="${PHP_BIN:-php}"
    cd "$INSTALL_DIR"

    run "'${php}' artisan config:cache    --no-interaction 2>/dev/null || true"
    run "'${php}' artisan route:cache     --no-interaction 2>/dev/null || true"
    run "'${php}' artisan view:cache      --no-interaction 2>/dev/null || true"
    run "'${php}' artisan event:cache     --no-interaction 2>/dev/null || true"
    run "'${php}' artisan storage:link    --no-interaction 2>/dev/null || true"

    log "Laravel cache built (config/route/view/event)"
}

# ── 7m. SSL ───────────────────────────────────────────────────────────────────
install_ssl() {
    [[ "$ENABLE_SSL" != "true" ]] && return 0
    step "SSL — Let's Encrypt"

    case "$PKG_FAMILY" in
        debian)
            run "apt-get install -y -qq certbot python3-certbot-nginx"
            run "certbot --nginx --non-interactive --agree-tos \
                --email '${ADMIN_EMAIL}' \
                --domains '${APP_DOMAIN},www.${APP_DOMAIN}' \
                --redirect 2>/dev/null" \
                && log "SSL installed for ${APP_DOMAIN}" \
                || warn "Certbot failed. Run manually: certbot --nginx -d ${APP_DOMAIN}"
            ;;
        rhel)
            run "dnf install -y -q certbot python3-certbot-nginx 2>/dev/null || \
                yum install -y -q certbot python3-certbot-nginx"
            run "certbot --nginx --non-interactive --agree-tos \
                --email '${ADMIN_EMAIL}' --domains '${APP_DOMAIN}' 2>/dev/null || true"
            ;;
        darwin)
            warn "On macOS, install certbot manually: brew install certbot"
            ;;
    esac

    # Auto-renew cron
    if command -v crontab &>/dev/null; then
        run "(crontab -l 2>/dev/null; echo '0 3 * * * certbot renew --quiet --no-self-upgrade') | crontab -"
        log "SSL auto-renew cron set (3:00 AM daily)"
    fi
}

# ── 7n. Firewall ──────────────────────────────────────────────────────────────
configure_firewall() {
    step "Firewall"

    if command -v ufw &>/dev/null; then
        run "ufw --force reset 2>/dev/null || true"
        run "ufw default deny incoming"
        run "ufw default allow outgoing"
        run "ufw allow ssh     comment 'SSH'"
        run "ufw allow 80/tcp  comment 'HTTP'"
        run "ufw allow 443/tcp comment 'HTTPS'"
        run "ufw --force enable"
        log "UFW: SSH + HTTP + HTTPS allowed"
    elif command -v firewall-cmd &>/dev/null; then
        run "systemctl enable --now firewalld 2>/dev/null || true"
        run "firewall-cmd --permanent --add-service=ssh"
        run "firewall-cmd --permanent --add-service=http"
        run "firewall-cmd --permanent --add-service=https"
        run "firewall-cmd --reload"
        log "Firewalld: SSH + HTTP + HTTPS allowed"
    else
        warn "No firewall detected (ufw/firewalld). Configure manually."
    fi
}

# ── 7o. cPanel-specific: htaccess ────────────────────────────────────────────
create_htaccess() {
    step ".htaccess (Apache/cPanel)"

    local public_dir="${INSTALL_DIR}/public"

    if [[ ! -f "${public_dir}/.htaccess" ]]; then
        if [[ "$DRY_RUN" != true ]]; then
            mkdir -p "$public_dir"
            cat > "${public_dir}/.htaccess" <<'HTACCESS'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

<IfModule mod_headers.c>
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

<FilesMatch "(\.env|\.git|composer\.(json|lock)|package\.json)">
    Require all denied
</FilesMatch>
HTACCESS
        fi
        log ".htaccess created at public/"
    fi

    # Root-level redirect (cPanel: domain → public_html/nlpgroup/public)
    local parent_dir
    parent_dir="$(dirname "$INSTALL_DIR")"
    if [[ -w "$parent_dir" && ! -f "${parent_dir}/.htaccess" ]]; then
        local app_dir_name
        app_dir_name="$(basename "$INSTALL_DIR")"
        if [[ "$DRY_RUN" != true ]]; then
            cat > "${parent_dir}/.htaccess" <<PHTACCESS
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^$ ${app_dir_name}/public/ [L]
    RewriteRule (.*) ${app_dir_name}/public/\$1 [L]
</IfModule>
PHTACCESS
        fi
        log "Root .htaccess created (redirect → ${app_dir_name}/public/)"
    fi
}

# ── 7p. Restart services ──────────────────────────────────────────────────────
restart_services() {
    step "Restart services"
    [[ "$PKG_FAMILY" == "darwin" ]] && {
        run "brew services restart nginx 2>/dev/null || true"
        run "brew services restart 'php@${PHP_VERSION}' 2>/dev/null || true"
        log "Services restarted (macOS Homebrew)"
        return 0
    }

    local services=("nginx")
    # Find the correct PHP-FPM service name
    for svc in "php${PHP_VERSION}-fpm" "php-fpm" "phpfpm"; do
        if systemctl list-unit-files "${svc}.service" &>/dev/null 2>&1; then
            services+=("$svc"); break
        fi
    done

    for svc in "${services[@]}"; do
        run "systemctl enable '${svc}' 2>/dev/null || true"
        run "systemctl restart '${svc}' 2>/dev/null" \
            && log "${svc} restarted" \
            || warn "Could not restart ${svc}"
    done
}

# ── 7q. Extract source from zip (cPanel) ─────────────────────────────────────
extract_source_cpanel() {
    step "Extract source code"

    local zip_file=""
    local candidates=(
        "${HOME}/nlpgroup-platform.zip"
        "${HOME}/investment-platform.zip"
        "${HOME}/nlpgroup.zip"
    )
    # Also pick up any *.zip in home
    local found
    found=$(ls "${HOME}"/*.zip 2>/dev/null | head -1 || true)
    [[ -n "$found" ]] && candidates+=("$found")

    for c in "${candidates[@]}"; do
        [[ -f "$c" ]] && { zip_file="$c"; break; }
    done

    if [[ -z "$zip_file" ]]; then
        warn "No .zip file found automatically."
        _ask zip_file "Full path to zip file" ""
        [[ ! -f "$zip_file" ]] && die "File not found: ${zip_file}"
    fi

    info "Extracting: ${zip_file} → ${INSTALL_DIR}"
    run "mkdir -p '${INSTALL_DIR}'"

    local tmp="/tmp/nlpgroup_extract_$$"
    run "mkdir -p '${tmp}'"
    run "unzip -q '${zip_file}' -d '${tmp}'"

    # Strip single wrapper directory if present
    local count
    count=$(ls -A "$tmp" 2>/dev/null | wc -l)
    if [[ "$count" -eq 1 ]]; then
        local wrapper; wrapper=$(ls -A "$tmp")
        if [[ -d "${tmp}/${wrapper}" ]]; then
            run "cp -a '${tmp}/${wrapper}/.' '${INSTALL_DIR}/'"
        else
            run "cp -a '${tmp}/.' '${INSTALL_DIR}/'"
        fi
    else
        run "cp -a '${tmp}/.' '${INSTALL_DIR}/'"
    fi

    run "rm -rf '${tmp}'"
    log "Source extracted to ${INSTALL_DIR}"
}

# ──────────────────────────────────────────────────────────────────────────────
# 8. SYSTEM REQUIREMENTS CHECK
# ──────────────────────────────────────────────────────────────────────────────
cmd_check() {
    step "System Requirements Check"

    local ok=true

    _chk() {
        local label="$1" check_cmd="$2" fix_hint="${3:-}"
        if eval "$check_cmd" &>/dev/null; then
            local ver; ver=$(eval "$check_cmd" 2>/dev/null | head -1 || true)
            echo -e "  ${GREEN}✔${RESET} ${label}: ${DIM}${ver}${RESET}"
        else
            echo -e "  ${RED}✗${RESET} ${label} — NOT FOUND"
            [[ -n "$fix_hint" ]] && echo -e "    ${YELLOW}→ ${fix_hint}${RESET}"
            ok=false
        fi
    }

    _chk "PHP >= ${PHP_MIN_MAJOR}.${PHP_MIN_MINOR}" \
        "php -r 'exit(PHP_MAJOR_VERSION >= ${PHP_MIN_MAJOR} && PHP_MINOR_VERSION >= ${PHP_MIN_MINOR} ? 0 : 1);'" \
        "Run: sudo bash ${SCRIPT_NAME} install"

    _chk "PHP-FPM" \
        "command -v php-fpm || ls /etc/php/*/fpm 2>/dev/null || brew services list 2>/dev/null | grep php" \
        "Install php${PHP_VERSION}-fpm"

    _chk "Composer 2.x" \
        "composer --version | grep -q 'Composer version 2'" \
        "Run: sudo bash ${SCRIPT_NAME} install"

    _chk "MySQL / MariaDB" \
        "command -v mysql && mysql --version" \
        "Run: sudo bash ${SCRIPT_NAME} install"

    _chk "Nginx or Apache" \
        "command -v nginx || command -v apache2 || command -v httpd" \
        "Run: sudo bash ${SCRIPT_NAME} install"

    _chk "Node.js >= ${NODE_MIN}" \
        "node --version | grep -oP '\d+' | head -1 | xargs -I{} test {} -ge ${NODE_MIN}" \
        "Run: sudo bash ${SCRIPT_NAME} install"

    _chk "git" "command -v git && git --version" "apt install git"
    _chk "curl" "command -v curl && curl --version" "apt install curl"
    _chk "unzip" "command -v unzip && unzip -v" "apt install unzip"

    echo ""
    if [[ "$ok" == true ]]; then
        log "All requirements satisfied ✓"
    else
        warn "Some requirements are missing. Run: sudo bash ${SCRIPT_NAME} install"
    fi

    # Check app directory
    if [[ -d "$INSTALL_DIR" ]]; then
        echo ""
        echo -e "${BOLD}App directory: ${INSTALL_DIR}${RESET}"
        local php="${PHP_BIN:-php}"
        if [[ -f "${INSTALL_DIR}/artisan" ]]; then
            "$php" "${INSTALL_DIR}/artisan" --version 2>/dev/null || true
        fi
        echo ""
        echo -e "${BOLD}Directory permissions:${RESET}"
        for p in . storage bootstrap/cache public .env; do
            local fp="${INSTALL_DIR}/${p}"
            [[ -e "$fp" ]] && \
                printf "  %-30s %s  %s\n" "$p" \
                    "$(stat -c '%A' "$fp" 2>/dev/null || stat -f '%Sp' "$fp" 2>/dev/null || echo '?')" \
                    "$(stat -c '%U:%G' "$fp" 2>/dev/null || stat -f '%Su:%Sg' "$fp" 2>/dev/null || echo '?')"
        done
    fi
}

# ──────────────────────────────────────────────────────────────────────────────
# 9. SUMMARY PRINTER
# ──────────────────────────────────────────────────────────────────────────────
print_summary() {
    local proto="http"
    [[ "$ENABLE_SSL" == "true" ]] && proto="https"
    local php="${PHP_BIN:-php}"

    echo ""
    echo -e "${BOLD}${GREEN}"
    echo "╔══════════════════════════════════════════════════════════════╗"
    echo "║          NLP GROUP PLATFORM — SETUP COMPLETE!               ║"
    echo "╚══════════════════════════════════════════════════════════════╝"
    echo -e "${RESET}"
    echo -e "${BOLD}Access:${RESET}"
    echo -e "  Website    : ${CYAN}${proto}://${APP_DOMAIN:-localhost}${RESET}"
    echo -e "  Directory  : ${CYAN}${INSTALL_DIR}${RESET}"
    [[ -n "${DB_DATABASE:-}" ]] && \
    echo -e "  Database   : ${CYAN}${DB_DATABASE}${RESET} (user: ${DB_USERNAME:-?})"
    echo ""
    echo -e "${BOLD}Management commands:${RESET}"
    echo -e "  ${YELLOW}cd ${INSTALL_DIR}${RESET}"
    echo -e "  ${YELLOW}${php} artisan list${RESET}                  # all commands"
    echo -e "  ${YELLOW}${php} artisan optimize:clear${RESET}         # clear cache"
    echo -e "  ${YELLOW}${php} artisan queue:work${RESET}             # queue worker"
    echo -e "  ${YELLOW}${php} artisan schedule:run${RESET}           # run scheduler"
    echo ""
    echo -e "${BOLD}Cron job (add to crontab):${RESET}"
    echo -e "  ${CYAN}* * * * * ${php} ${INSTALL_DIR}/artisan schedule:run >> /dev/null 2>&1${RESET}"
    echo ""
    echo -e "${BOLD}Logs:${RESET}"
    echo -e "  ${YELLOW}tail -f ${INSTALL_DIR}/storage/logs/laravel.log${RESET}"
    [[ "$PKG_FAMILY" != "darwin" && "$IS_CPANEL" != "true" ]] && \
    echo -e "  ${YELLOW}tail -f /var/log/nginx/${NGINX_SITE}-error.log${RESET}"
    echo ""
    if [[ "$ENABLE_SSL" != "true" && "$IS_CPANEL" != "true" ]]; then
        echo -e "${YELLOW}[!] SSL not configured. To enable:${RESET}"
        echo -e "    ${CYAN}certbot --nginx -d ${APP_DOMAIN:-yourdomain.com} --email ${ADMIN_EMAIL:-you@example.com}${RESET}"
        echo ""
    fi
    if [[ "$IS_CPANEL" == "true" ]]; then
        echo -e "${BOLD}cPanel next steps:${RESET}"
        echo -e "  1. ${YELLOW}cPanel → Domains → Document Root → set to:${RESET}"
        echo -e "     ${CYAN}${INSTALL_DIR}/public${RESET}"
        echo -e "  2. ${YELLOW}cPanel → SSL/TLS → AutoSSL${RESET}"
        echo ""
    fi
    [[ -n "${LOG_FILE:-}" ]] && echo -e "  Full log: ${DIM}${LOG_FILE}${RESET}"
    echo ""
    echo -e "${GREEN}Thank you for using NLP Group Platform!${RESET}"
    echo -e "${BLUE}Docs   : ${CYAN}${HOMEPAGE_URL}${RESET}"
    echo -e "${BLUE}Repo   : ${CYAN}https://github.com/${REPO_OWNER}/${REPO_NAME}${RESET}"
    echo -e "${BLUE}Support: ${CYAN}dev@nlpgroup.vn${RESET}"
    echo ""
}

# ──────────────────────────────────────────────────────────────────────────────
# 10. BANNER
# ──────────────────────────────────────────────────────────────────────────────
show_banner() {
    echo -e "${BOLD}${GREEN}"
    cat <<'BANNER'
   ███╗   ██╗██╗     ██████╗      ██████╗ ██████╗  ██████╗ ██╗   ██╗██████╗
   ████╗  ██║██║     ██╔══██╗    ██╔════╝ ██╔══██╗██╔═══██╗██║   ██║██╔══██╗
   ██╔██╗ ██║██║     ██████╔╝    ██║  ███╗██████╔╝██║   ██║██║   ██║██████╔╝
   ██║╚██╗██║██║     ██╔═══╝     ██║   ██║██╔══██╗██║   ██║██║   ██║██╔═══╝
   ██║ ╚████║███████╗██║         ╚██████╔╝██║  ██║╚██████╔╝╚██████╔╝██║
   ╚═╝  ╚═══╝╚══════╝╚═╝          ╚═════╝ ╚═╝  ╚═╝ ╚═════╝  ╚═════╝ ╚═╝
BANNER
    echo -e "${RESET}"
    echo -e "   ${BOLD}NLP Group Platform ${SCRIPT_VERSION} — Unified Installer & CLI${RESET}"
    echo -e "   ${DIM}${OS_NAME:-} | ${PKG_MGR:-} | Root: ${IS_ROOT} | cPanel: ${IS_CPANEL}${RESET}"
    echo ""
}

# ──────────────────────────────────────────────────────────────────────────────
# 11. HELP
# ──────────────────────────────────────────────────────────────────────────────
show_help() {
    echo -e "${BOLD}${CYAN}NLP Group Platform — install.sh v${SCRIPT_VERSION}${RESET}"
    echo ""
    echo -e "${BOLD}USAGE${RESET}"
    echo "  bash ${SCRIPT_NAME} <command> [options]"
    echo ""
    echo -e "${BOLD}COMMANDS${RESET}"
    printf "  ${GREEN}%-14s${RESET} %s\n" "install"     "Full server install (PHP, MySQL, Nginx, app)"
    printf "  ${GREEN}%-14s${RESET} %s\n" "deploy"      "Deploy/update app code only"
    printf "  ${GREEN}%-14s${RESET} %s\n" "permissions" "Fix file & directory permissions"
    printf "  ${GREEN}%-14s${RESET} %s\n" "cpanel"      "cPanel/shared-hosting install (no root needed)"
    printf "  ${GREEN}%-14s${RESET} %s\n" "optimize"    "Run Laravel cache & optimize commands"
    printf "  ${GREEN}%-14s${RESET} %s\n" "check"       "System requirements check (read-only)"
    printf "  ${GREEN}%-14s${RESET} %s\n" "help"        "Show this help"
    echo ""
    echo -e "${BOLD}OPTIONS${RESET}"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--dir PATH"    "App install directory (default: /var/www/investment-platform)"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--domain NAME" "Domain name"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--db-name NAME" "Database name"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--db-user USER" "Database user"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--db-pass PASS" "Database password"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--db-root PASS" "MySQL root password"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--email EMAIL"  "Admin email (SSL)"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--ssl"          "Enable Let's Encrypt SSL"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--yes / -y"     "Non-interactive (accept all defaults)"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--dry-run"      "Show commands without executing"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--no-color"     "Disable colored output"
    printf "  ${YELLOW}%-20s${RESET} %s\n" "--version"      "Show version"
    echo ""
    echo -e "${BOLD}EXAMPLES${RESET}"
    echo -e "  ${CYAN}sudo bash install.sh install --domain nlpgroup.vn --ssl${RESET}"
    echo -e "  ${CYAN}sudo bash install.sh permissions${RESET}"
    echo -e "  ${CYAN}sudo bash install.sh deploy --dir /var/www/myapp${RESET}"
    echo -e "  ${CYAN}bash install.sh cpanel --domain app.nlpgroup.vn${RESET}"
    echo -e "  ${CYAN}bash install.sh check${RESET}"
    echo -e "  ${CYAN}bash install.sh optimize${RESET}"
    echo ""
    echo -e "  ${DIM}# One-liner remote install:${RESET}"
    echo -e "  ${CYAN}sudo bash -c \"\$(curl -fsSL https://raw.githubusercontent.com/nlp-labs-vietnam/investment-platform/main/scripts/install.sh)\" -- install --domain nlpgroup.vn --ssl${RESET}"
    echo ""
    echo -e "${BOLD}LINKS${RESET}"
    echo -e "  Docs    : ${CYAN}${HOMEPAGE_URL}${RESET}"
    echo -e "  Repo    : ${CYAN}https://github.com/${REPO_OWNER}/${REPO_NAME}${RESET}"
    echo -e "  Support : ${CYAN}dev@nlpgroup.vn${RESET}"
    echo ""
}

# ──────────────────────────────────────────────────────────────────────────────
# 12. COMMAND DISPATCH
# ──────────────────────────────────────────────────────────────────────────────
cmd_install() {
    [[ "$IS_ROOT" != true ]] && die "Command 'install' requires root (sudo)."

    LOG_FILE="/tmp/nlpgroup_install_$(date +%Y%m%d_%H%M%S).log"
    info "Install log: ${LOG_FILE}"
    exec > >(tee -a "$LOG_FILE") 2>&1

    show_banner
    collect_config

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
    log "Full log: ${LOG_FILE}"
}

cmd_deploy() {
    LOG_FILE="/tmp/nlpgroup_deploy_$(date +%Y%m%d_%H%M%S).log"
    info "Deploy log: ${LOG_FILE}"
    exec > >(tee -a "$LOG_FILE") 2>&1

    detect_php  || warn "PHP not found — some steps may be skipped"
    detect_composer || warn "Composer not found — skipping PHP deps"

    _ask APP_DOMAIN  "Domain name"          "nlpgroup.vn"
    _ask DB_DATABASE "MySQL database name"  "nlpgroup_db"
    _ask DB_USERNAME "MySQL user"           "nlp_user"
    _ask_pass DB_PASSWORD "MySQL password"
    _ask DB_HOST     "MySQL host"           "127.0.0.1"

    deploy_app
    configure_env
    [[ -n "$COMPOSER_BIN" ]] && composer_install
    [[ -n "$PHP_BIN" ]] && run_migrations
    setup_permissions
    [[ -n "$PHP_BIN" ]] && optimize_app

    print_summary
    log "Deploy complete. Log: ${LOG_FILE}"
}

cmd_permissions() {
    INSTALL_DIR="${INSTALL_DIR:-/var/www/investment-platform}"
    [[ ! -d "$INSTALL_DIR" ]] && die "Directory not found: ${INSTALL_DIR}"
    setup_permissions
    log "Permissions fixed for: ${INSTALL_DIR}"
}

cmd_optimize() {
    detect_php || die "PHP not found. Install PHP ${PHP_MIN_MAJOR}.${PHP_MIN_MINOR}+ first."
    [[ ! -f "${INSTALL_DIR}/artisan" ]] && die "artisan not found in ${INSTALL_DIR}"
    optimize_app
}

cmd_cpanel() {
    LOG_FILE="/tmp/nlpgroup_cpanel_$(date +%Y%m%d_%H%M%S).log"
    info "cPanel install log: ${LOG_FILE}"

    show_banner
    detect_php  || die "PHP >= ${PHP_MIN_MAJOR}.${PHP_MIN_MINOR} not found. Enable in cPanel → MultiPHP Manager."
    detect_composer || install_composer

    IS_CPANEL=true
    collect_config_cpanel

    exec > >(tee -a "$LOG_FILE") 2>&1

    extract_source_cpanel
    _setup_dirs_cpanel
    configure_env
    composer_install
    create_htaccess
    setup_permissions
    run_migrations
    optimize_app

    print_summary
    log "cPanel install complete. Log: ${LOG_FILE}"
}

_setup_dirs_cpanel() {
    step "Create directory structure"
    run "mkdir -p '${INSTALL_DIR}/storage/'{app/public,framework/{cache/data,sessions,views},logs}"
    run "mkdir -p '${INSTALL_DIR}/bootstrap/cache'"
    run "mkdir -p '${INSTALL_DIR}/public/uploads'"
    run "mkdir -p '${INSTALL_DIR}/exports'"
    run "chmod -R 755 '${INSTALL_DIR}/storage' '${INSTALL_DIR}/bootstrap/cache'"
    log "Directories ready"
}

# ──────────────────────────────────────────────────────────────────────────────
# 13. MAIN ENTRY POINT
# ──────────────────────────────────────────────────────────────────────────────
main() {
    parse_args "$@"
    _init_colors

    # Trap for unexpected errors
    trap 'error "Aborted at line ${LINENO}. Command: ${BASH_COMMAND}"; exit 1' ERR
    trap 'warn "Interrupted."; exit 130' INT TERM

    # Detect OS for all commands except help/check (which are read-only)
    case "$COMMAND" in
        help|--help|-h|"")
            show_help; exit 0 ;;
        check)
            detect_os 2>/dev/null || true
            detect_php 2>/dev/null || true
            cmd_check; exit 0 ;;
    esac

    detect_os

    case "$COMMAND" in
        install)     cmd_install ;;
        deploy)      cmd_deploy ;;
        permissions) cmd_permissions ;;
        optimize)    cmd_optimize ;;
        cpanel)      cmd_cpanel ;;
        *)
            error "Unknown command: '${COMMAND}'"
            echo ""
            show_help
            exit 1
            ;;
    esac
}

main "$@"
