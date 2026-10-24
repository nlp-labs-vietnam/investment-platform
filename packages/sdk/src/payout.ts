import type { AxiosInstance, AxiosResponse } from 'axios';
import type { Payout } from '@nlp-labs-vietnam/investment-types';

// ── PayoutAPI ─────────────────────────────────────────────────────────────
// Nguồn: routes/api.php → App\Http\Controllers\Api\PayoutController

export class PayoutAPI {
  constructor(private readonly http: AxiosInstance) {}

  /** GET /api/payout — lấy payout methods */
  getPayout(): Promise<AxiosResponse<unknown>> {
    return this.http.get('/payout');
  }

  /** GET /api/payout/check/amount */
  checkAmount(params: {
    payout_method_id: number;
    amount: number;
  }): Promise<AxiosResponse<unknown>> {
    return this.http.get('/payout/check/amount', { params });
  }

  /** POST /api/payout/get/bank/list */
  getBankList(data: Record<string, unknown>): Promise<AxiosResponse<unknown>> {
    return this.http.post('/payout/get/bank/list', data);
  }

  /** POST /api/payout/get/bank/from */
  getBankFrom(data: Record<string, unknown>): Promise<AxiosResponse<unknown>> {
    return this.http.post('/payout/get/bank/from', data);
  }

  /** POST /api/payout/paystack/submit */
  paystackSubmit(data: Record<string, unknown>): Promise<AxiosResponse<Payout>> {
    return this.http.post<Payout>('/payout/paystack/submit', data);
  }

  /** POST /api/payout/flutterwave/submit */
  flutterwaveSubmit(
    data: Record<string, unknown>,
  ): Promise<AxiosResponse<Payout>> {
    return this.http.post<Payout>('/payout/flutterwave/submit', data);
  }

  /** POST /api/payout/submit/confirm */
  submit(data: Record<string, unknown>): Promise<AxiosResponse<Payout>> {
    return this.http.post<Payout>('/payout/submit/confirm', data);
  }
}
