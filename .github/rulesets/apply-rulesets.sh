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

# ── Hàm apply ruleset ────────────────────────────────────────────────────────
apply_ruleset() {
  local file="$1"
  local name
  name=$(jq -r '.name' "$file")

  echo -e "\n${YELLOW}[→]${RESET} Xử lý ruleset: ${name}"

  # Kiểm tra ruleset đã tồn tại chưa
  existing_id=$(curl -sf \
    -H "Authorization: Bearer ${GITHUB_TOKEN}" \
    -H "Accept: application/vnd.github+json" \
    -H "X-GitHub-Api-Version: 2022-11-28" \
    "${API}" | jq -r --arg name "$name" '.[] | select(.name == $name) | .id' 2>/dev/null || true)

  if [[ -n "$existing_id" ]]; then
    # Cập nhật ruleset hiện có
    response=$(curl -sf -X PUT \
      -H "Authorization: Bearer ${GITHUB_TOKEN}" \
      -H "Accept: application/vnd.github+json" \
      -H "X-GitHub-Api-Version: 2022-11-28" \
      -H "Content-Type: application/json" \
      --data @"$file" \
      "${API}/${existing_id}")
    echo -e "${GREEN}[✔]${RESET} Đã cập nhật ruleset '${name}' (id: ${existing_id})"
  else
    # Tạo mới
    response=$(curl -sf -X POST \
      -H "Authorization: Bearer ${GITHUB_TOKEN}" \
      -H "Accept: application/vnd.github+json" \
      -H "X-GitHub-Api-Version: 2022-11-28" \
      -H "Content-Type: application/json" \
      --data @"$file" \
      "${API}")
    new_id=$(echo "$response" | jq -r '.id')
    echo -e "${GREEN}[✔]${RESET} Đã tạo ruleset '${name}' (id: ${new_id})"
  fi
}

# ── Apply từng ruleset ────────────────────────────────────────────────────────
echo -e "${GREEN}Applying GitHub Rulesets → ${REPO}${RESET}"

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
