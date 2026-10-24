/**
 * Date utilities
 * Chuyển đổi từ app/Helpers/helpers.php
 *   customDate()     → formatDate()
 *   customDateTime() → formatDateTime()
 *   dateTime()       → dateTime() (dùng format từ basicControl)
 */

/**
 * Định dạng ngày.
 * PHP gốc: customDate($date, $format = 'd M, Y')
 */
export function formatDate(
  date: string | Date,
  locale = 'vi-VN',
  options: Intl.DateTimeFormatOptions = {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  },
): string {
  return new Intl.DateTimeFormat(locale, options).format(new Date(date));
}

/**
 * Định dạng ngày giờ đầy đủ.
 * PHP gốc: customDateTime($date) → 'October 30, 2024 02:30 PM'
 */
export function formatDateTime(
  date: string | Date,
  locale = 'vi-VN',
): string {
  return new Intl.DateTimeFormat(locale, {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
  }).format(new Date(date));
}

/**
 * Kiểm tra ngày hết hạn.
 * Dùng cho Property.expire_date
 */
export function isExpired(date: string | Date): boolean {
  return new Date(date) < new Date();
}

/**
 * Tính số ngày còn lại đến ngày expire.
 * Trả về số âm nếu đã hết hạn.
 */
export function daysUntilExpiry(expireDate: string | Date): number {
  const now = new Date();
  const expire = new Date(expireDate);
  const diffMs = expire.getTime() - now.getTime();
  return Math.ceil(diffMs / (1000 * 60 * 60 * 24));
}

/**
 * Kiểm tra ngày trả góp còn trong hạn.
 * PHP gốc: Investment@getInstallmentDate()
 */
export function isInstallmentInRange(nextInstallmentDateEnd: string): boolean {
  return !isExpired(nextInstallmentDateEnd);
}
