import type { AxiosInstance, AxiosResponse } from 'axios';
import type { User } from '@nlp-labs-vietnam/investment-types';

// ── Request payload types ─────────────────────────────────────────────────

export interface RegisterPayload {
  firstname: string;
  lastname: string;
  username: string;
  email: string;
  password: string;
  password_confirmation: string;
  referral_id?: string;
  country_code?: string;
  country?: string;
  phone_code?: string;
  phone?: string;
}

export interface LoginPayload {
  username: string;
  password: string;
}

export interface PasswordResetEmailPayload {
  email: string;
}

export interface PasswordResetCodePayload {
  code: string;
  email: string;
}

export interface PasswordResetPayload {
  email: string;
  code: string;
  password: string;
  password_confirmation: string;
}

export interface AuthResponse {
  token: string;
  user: User;
}

// ── AuthAPI ───────────────────────────────────────────────────────────────
// Nguồn: routes/api.php → App\Http\Controllers\Api\AuthController

export class AuthAPI {
  constructor(private readonly http: AxiosInstance) {}

  /** POST /api/register */
  register(data: RegisterPayload): Promise<AxiosResponse<AuthResponse>> {
    return this.http.post<AuthResponse>('/register', data);
  }

  /** POST /api/login */
  login(data: LoginPayload): Promise<AxiosResponse<AuthResponse>> {
    return this.http.post<AuthResponse>('/login', data);
  }

  /** POST /api/logout — requires Sanctum token */
  logout(): Promise<AxiosResponse<void>> {
    return this.http.post('/logout');
  }

  /** POST /api/reset/password/email */
  sendPasswordResetEmail(
    data: PasswordResetEmailPayload,
  ): Promise<AxiosResponse<unknown>> {
    return this.http.post('/reset/password/email', data);
  }

  /** POST /api/reset/password/code */
  verifyPasswordResetCode(
    data: PasswordResetCodePayload,
  ): Promise<AxiosResponse<unknown>> {
    return this.http.post('/reset/password/code', data);
  }

  /** POST /api/password/reset */
  resetPassword(data: PasswordResetPayload): Promise<AxiosResponse<unknown>> {
    return this.http.post('/password/reset', data);
  }
}
