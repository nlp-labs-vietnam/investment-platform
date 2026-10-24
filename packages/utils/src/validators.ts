/**
 * Investment validators
 * Dùng trên frontend trước khi gọi API
 */

export interface ValidationResult {
  valid: boolean;
  message?: string;
}

/**
 * Validate số tiền đầu tư nằm trong khoảng cho phép.
 * Mirrors: InvestmentService@profitLossCalculate validation
 */
export function validateInvestAmount(
  amount: number,
  options: {
    fixedAmount?: number | null;
    minimumAmount?: number | null;
    maximumAmount?: number | null;
  },
): ValidationResult {
  if (amount <= 0) {
    return { valid: false, message: 'Số tiền đầu tư phải lớn hơn 0' };
  }
  if (options.fixedAmount != null) {
    if (amount !== options.fixedAmount) {
      return {
        valid: false,
        message: `Số tiền đầu tư cố định phải là ${options.fixedAmount}`,
      };
    }
    return { valid: true };
  }
  if (options.minimumAmount != null && amount < options.minimumAmount) {
    return {
      valid: false,
      message: `Số tiền tối thiểu là ${options.minimumAmount}`,
    };
  }
  if (options.maximumAmount != null && amount > options.maximumAmount) {
    return {
      valid: false,
      message: `Số tiền tối đa là ${options.maximumAmount}`,
    };
  }
  return { valid: true };
}

/**
 * Validate số lượng shares mua.
 */
export function validateShareCount(
  shares: number,
  availableShares: number,
): ValidationResult {
  if (!Number.isInteger(shares) || shares < 1) {
    return { valid: false, message: 'Số lượng cổ phần phải là số nguyên dương' };
  }
  if (shares > availableShares) {
    return {
      valid: false,
      message: `Chỉ còn ${availableShares} cổ phần khả dụng`,
    };
  }
  return { valid: true };
}
