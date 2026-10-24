/**
 * @nlp-labs-vietnam/investment-sdk
 *
 * HTTP client SDK cho NLP Investment Platform REST API
 * Backend: Laravel 11 + Sanctum (services/api/)
 *
 * Sử dụng:
 *   import { NLPInvestmentClient } from '@nlp-labs-vietnam/investment-sdk';
 *
 *   const client = new NLPInvestmentClient({ baseURL: 'https://nlpgroup.vn' });
 *   const { data } = await client.auth.login({ username: 'user', password: '...' });
 *   client.setToken(data.token);
 *   const props = await client.property.investmentList();
 */

import axios from 'axios';
import type { AxiosInstance } from 'axios';
import { AuthAPI } from './auth';
import { PropertyAPI } from './property';
import { DepositAPI } from './deposit';
import { PayoutAPI } from './payout';
import { ProfileAPI } from './profile';

export interface NLPClientConfig {
  /** URL gốc của Laravel app. Ví dụ: 'https://nlpgroup.vn' */
  baseURL: string;
  /** Sanctum Bearer token — đặt sau khi login() */
  token?: string;
  /** Timeout tính bằng ms. Mặc định: 30000 */
  timeout?: number;
}

export class NLPInvestmentClient {
  private http: AxiosInstance;

  public readonly auth: AuthAPI;
  public readonly property: PropertyAPI;
  public readonly deposit: DepositAPI;
  public readonly payout: PayoutAPI;
  public readonly profile: ProfileAPI;

  constructor(config: NLPClientConfig) {
    this.http = axios.create({
      baseURL: `${config.baseURL}/api`,
      timeout: config.timeout ?? 30_000,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...(config.token
          ? { Authorization: `Bearer ${config.token}` }
          : {}),
      },
    });

    this.auth     = new AuthAPI(this.http);
    this.property = new PropertyAPI(this.http);
    this.deposit  = new DepositAPI(this.http);
    this.payout   = new PayoutAPI(this.http);
    this.profile  = new ProfileAPI(this.http);
  }

  /**
   * Cập nhật Sanctum Bearer token sau khi login thành công.
   * Gọi ngay sau: const { data } = await client.auth.login(...)
   *               client.setToken(data.token);
   */
  setToken(token: string): void {
    this.http.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  }

  /** Xoá token (logout) */
  clearToken(): void {
    delete this.http.defaults.headers.common['Authorization'];
  }
}

// Re-export tất cả API classes và payload types
export { AuthAPI } from './auth';
export type {
  RegisterPayload,
  LoginPayload,
  AuthResponse,
  PasswordResetEmailPayload,
  PasswordResetCodePayload,
  PasswordResetPayload,
} from './auth';

export { PropertyAPI } from './property';
export type {
  PropertyListParams,
  InvestPayload,
  ShareStorePayload,
  MakeOfferPayload,
  DirectBuyPayload,
} from './property';

export { DepositAPI } from './deposit';
export type {
  PaymentRequestPayload,
  CardPaymentPayload,
  ManualPaymentPayload,
} from './deposit';

export { PayoutAPI } from './payout';
export { ProfileAPI } from './profile';
export type {
  UpdateProfilePayload,
  ChangePasswordPayload,
} from './profile';
