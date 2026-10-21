#!/usr/bin/env bash
# =============================================================================
# apply-rulesets.sh — Áp dụng GitHub Branch/Tag Rulesets qua REST API
#
# Cách dùng:
#   export GITHUB_TOKEN=ghp_xxxxxxxxxxxx
#   bash .github/rulesets/apply-rulesets.sh
#
# Yêu cầu: curl, jq
# Tham khảo: https://docs.github.com/en/rest/repos/rules
# =============================================================================

set -euo pipefail

REPO="nlp-labs-vietnam/investment-platform"
API="https://api.github.com/repos/${REPO}/rulesets"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; RESET='\033[0m'

# ── Kiểm tra GITHUB_TOKEN ─────────────────────────────────────────────────────
if [[ -z "${GITHUB_TOKEN:-}" ]]; then
  echo -e "${RED}[✗]${RESET} Biến môi trường GITHUB_TOKEN chưa được đặt."
  echo -e "    Chạy: ${YELLOW}export GITHUB_TOKEN=ghp_xxxx${RESET}"
  exit 1
fi

# ── Kiểm tra jq ───────────────────────────────────────────────────────────────
if ! command -v jq &>/dev/null; then
  echo -e "${RED}[✗]${RESET} Cần cài jq: apt install jq / brew install jq"
  exit 1
fi

# ── Hàm curl wrapper với timeout & kiểm tra HTTP status ─────────────────────
gh_api() {
  local method="$1"; shift
  local url="$1";    shift
  local http_code body tmp
  tmp=$(mktemp)

  http_code=$(curl -s -o "$tmp" -w "%{http_code}" \
    --max-time 15 \
    -X "$method" \
    -H "Authorization: Bearer ${GITHUB_TOKEN}" \
    -H "Accept: application/vnd.github+json" \
    -H "X-GitHub-Api-Version: 2022-11-28" \
    "$@" \
    "$url")

  body=$(cat "$tmp"); rm -f "$tmp"

  if [[ "$http_code" -ge 400 ]]; then
    echo -e "${RED}[✗]${RESET} HTTP ${http_code} — $(echo "$body" | jq -r '.message // .error // .')" >&2
    return 1
  fi

  echo "$body"
}

# ── Hàm apply ruleset ────────────────────────────────────────────────────────
apply_ruleset() {
  local file="$1"
  local name
  name=$(jq -r '.name' "$file")

  echo -e "\n${YELLOW}[→]${RESET} Xử lý ruleset: ${name}"

  # Lấy danh sách rulesets hiện có
  existing_list=$(gh_api GET "${API}") || { echo -e "${RED}[✗]${RESET} Không lấy được danh sách rulesets"; return 1; }

  existing_id=$(echo "$existing_list" | jq -r --arg name "$name" '.[] | select(.name == $name) | .id')

  if [[ -n "$existing_id" ]]; then
    gh_api PUT "${API}/${existing_id}" \
      -H "Content-Type: application/json" \
      --data @"$file" > /dev/null
    echo -e "${GREEN}[✔]${RESET} Đã cập nhật ruleset '${name}' (id: ${existing_id})"
  else
    response=$(gh_api POST "${API}" \
      -H "Content-Type: application/json" \
      --data @"$file")
    new_id=$(echo "$response" | jq -r '.id')
    echo -e "${GREEN}[✔]${RESET} Đã tạo ruleset '${name}' (id: ${new_id})"
  fi
}

# ── Kiểm tra kết nối & quyền trước khi chạy ─────────────────────────────────
echo -e "${GREEN}Applying GitHub Rulesets → ${REPO}${RESET}"
echo -e "${YELLOW}[→]${RESET} Kiểm tra token..."

user=$(gh_api GET "https://api.github.com/user" | jq -r '.login') \
  || { echo -e "${RED}[✗]${RESET} Token không hợp lệ hoặc không có quyền."; exit 1; }

echo -e "${GREEN}[✔]${RESET} Xác thực thành công — user: ${user}"

# ── Apply từng ruleset ────────────────────────────────────────────────────────
for ruleset_file in \
  "${SCRIPT_DIR}/protect-main.json" \
  "${SCRIPT_DIR}/protect-develop.json" \
  "${SCRIPT_DIR}/protect-tags.json"; do
  if [[ -f "$ruleset_file" ]]; then
    apply_ruleset "$ruleset_file"
  else
    echo -e "${RED}[!]${RESET} File không tìm thấy: ${ruleset_file}"
  fi
done

echo -e "\n${GREEN}[✔] Hoàn tất! Xem tại:${RESET}"
echo -e "    https://github.com/${REPO}/settings/rules"
