/**
 * String utilities
 * Chuyển đổi từ app/Helpers/helpers.php
 *   stringToTitle() → stringToTitle()
 *   getTitle()      → getTitle()
 *   hex2rgba()      → hex2rgba()
 */

/**
 * Chuyển chuỗi có ký tự đặc biệt thành Title Case.
 * PHP gốc: stringToTitle($string)
 */
export function stringToTitle(str: string): string {
  return str
    .replace(/[^a-zA-Z0-9]+/g, ' ')
    .split(' ')
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
    .join(' ')
    .trim();
}

/**
 * Chuyển route/key thành tiêu đề hiển thị.
 * PHP gốc: getTitle($title)
 */
export function getTitle(title: string): string {
  if (title.toLowerCase() === 'sms') {
    return title.replace(/[^A-Za-z0-9]/g, ' ').toUpperCase();
  }
  return title
    .replace(/[^A-Za-z0-9]/g, ' ')
    .replace(/\b\w/g, (c) => c.toUpperCase());
}

/**
 * Chuyển mã hex thành chuỗi rgb/rgba.
 * PHP gốc: hex2rgba($color, $opacity)
 */
export function hex2rgba(color: string, opacity?: number): string {
  const defaultColor = 'rgb(0,0,0)';
  if (!color) return defaultColor;

  const hex = color.startsWith('#') ? color.slice(1) : color;

  let r: number, g: number, b: number;
  if (hex.length === 6) {
    r = parseInt(hex.slice(0, 2), 16);
    g = parseInt(hex.slice(2, 4), 16);
    b = parseInt(hex.slice(4, 6), 16);
  } else if (hex.length === 3) {
    r = parseInt(hex[0] + hex[0], 16);
    g = parseInt(hex[1] + hex[1], 16);
    b = parseInt(hex[2] + hex[2], 16);
  } else {
    return defaultColor;
  }

  if (opacity !== undefined) {
    const o = Math.abs(opacity) > 1 ? 1.0 : opacity;
    return `rgba(${r},${g},${b},${o})`;
  }
  return `rgb(${r},${g},${b})`;
}
