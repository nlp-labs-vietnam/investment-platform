/**
 * Currency utilities
 * Chuyển đổi từ app/Helpers/helpers.php
 *   fractionNumber() → fractionNumber()
 *   getAmount()      → getAmount()
 *   basicControl()->currency_symbol → truyền vào tham số symbol
 */

/**
 * Định dạng số thập phân với dấu phân cách hàng nghìn.
 * PHP gốc: fractionNumber($amount) trong helpers.php
 */
export function fractionNumber(amount: number, decimals = 2): string {
  return amount
    .toFixed(decimals)
    .replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/**
 * Chia amount cho 10^8 (satoshi-style storage).
 * PHP gốc: getAmount($amount, $decimal) trong helpers.php
 */
export function getAmount(amount: number, decimal = 8): number {
  return amount / Math.pow(10, decimal);
}

/**
 * Định dạng tiền tệ VND theo chuẩn Intl.
 */
export function formatVND(amount: number): string {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
    minimumFractionDigits: 0,
  }).format(amount);
}

/**
 * Tạo chuỗi hiển thị khoảng đầu tư.
 * PHP gốc: Property@getInvestmentAmountAttribute()
 *   basicControl()->currency_symbol . fractionNumber(getAmount($min)) . ' - ' . ...
 */
export function investmentRange(
  min: number,
  max: number,
  symbol = '₫',
): string {
  return `${symbol}${fractionNumber(getAmount(min))} - ${symbol}${fractionNumber(getAmount(max))}`;
}

/**
 * Tạo chuỗi hiển thị khi fixed_amount không null.
 */
export function fixedInvestmentLabel(
  fixedAmount: number,
  symbol = '₫',
): string {
  return `${symbol}${fractionNumber(getAmount(fixedAmount))}`;
}
