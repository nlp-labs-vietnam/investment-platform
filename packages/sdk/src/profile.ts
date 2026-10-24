import type { AxiosInstance, AxiosResponse } from 'axios';
import type { User, UserKyc } from '@nlp-labs-vietnam/investment-types';

// ── Request payload types ─────────────────────────────────────────────────

export interface UpdateProfilePayload {
  firstname?: string;
  lastname?: string;
  phone_code?: string;
  phone?: string;
  country_code?: string;
  country?: string;
  image?: File | string;
}

export interface ChangePasswordPayload {
  current_password: string;
  password: string;
  password_confirmation: string;
}

// ── ProfileAPI ────────────────────────────────────────────────────────────
// Nguồn: routes/api.php → App\Http\Controllers\Api\ProfileController

export class ProfileAPI {
  constructor(private readonly http: AxiosInstance) {}

  /** GET /api/user/profile */
  getProfile(): Promise<AxiosResponse<User>> {
    return this.http.get<User>('/user/profile');
  }

  /** POST /api/update/user/profile */
  updateProfile(data: UpdateProfilePayload): Promise<AxiosResponse<User>> {
    return this.http.post<User>('/update/user/profile', data);
  }

  /** POST /api/change/password */
  changePassword(
    data: ChangePasswordPayload,
  ): Promise<AxiosResponse<unknown>> {
    return this.http.post('/change/password', data);
  }

  /** POST /api/delete-account */
  deleteAccount(data: { password: string }): Promise<AxiosResponse<unknown>> {
    return this.http.post('/delete-account', data);
  }

  /** GET /api/kycs — lấy form KYC */
  kycs(): Promise<AxiosResponse<unknown>> {
    return this.http.get('/kycs');
  }

  /** POST /api/kyc-submit */
  kycSubmit(data: Record<string, unknown>): Promise<AxiosResponse<UserKyc>> {
    return this.http.post<UserKyc>('/kyc-submit', data);
  }
}
