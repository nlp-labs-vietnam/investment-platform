import type { AxiosInstance, AxiosResponse } from 'axios';
import type { Deposit } from '@nlp-labs-vietnam/investment-types';

// ── Request payload types ─────────────────────────────────────────────────

export interface PaymentRequestPayload {
  gateway_code: string;
  currency: string;
  amount: number;
}

export interface CardPaymentPayload {
  gateway_code: string;
  currency: string;
  amount: number;
  card_number: string;
  expiry_month: string;
  expiry_year: string;
  cvv: string;
}

export interface ManualPaymentPayload {
  gateway_code: string;
  currency: string;
  amount: number;
  information: Record<string, unknown>;
}

// ── DepositAPI ────────────────────────────────────────────────────────────
// Nguồn: routes/api.php → App\Http\Controllers\Api\DepositController

export class DepositAPI {
  constructor(private readonly http: AxiosInstance) {}

  /** GET /api/add/fund — lấy danh sách gateway */
  addFund(): Promise<AxiosResponse<unknown>> {
    return this.http.get('/add/fund');
  }

  /** GET /api/deposit/check/amount */
  checkAmount(params: {
    gateway_code: string;
    currency: string;
    amount: number;
  }): Promise<AxiosResponse<unknown>> {
    return this.http.get('/deposit/check/amount', { params });
  }

  /** POST /api/payment/request — tạo payment request tự động */
  paymentRequest(
    data: PaymentRequestPayload,
  ): Promise<AxiosResponse<Deposit>> {
    return this.http.post<Deposit>('/payment/request', data);
  }

  /** POST /api/payment/done — xác nhận payment */
  paymentDone(data: Record<string, unknown>): Promise<AxiosResponse<unknown>> {
    return this.http.post('/payment/done', data);
  }

  /** POST /api/card/payment */
  cardPayment(data: CardPaymentPayload): Promise<AxiosResponse<Deposit>> {
    return this.http.post<Deposit>('/card/payment', data);
  }

  /** POST /api/manual/payment */
  manualPayment(
    data: ManualPaymentPayload,
  ): Promise<AxiosResponse<Deposit>> {
    return this.http.post<Deposit>('/manual/payment', data);
  }

  /** GET /api/payment/process/{trx_id} */
  paymentProcess(trxId: string): Promise<AxiosResponse<unknown>> {
    return this.http.get(`/payment/process/${trxId}`);
  }

  /** POST /api/add/fund/confirm/{trx_id} */
  addFundConfirm(
    trxId: string,
    data: Record<string, unknown>,
  ): Promise<AxiosResponse<unknown>> {
    return this.http.post(`/add/fund/confirm/${trxId}`, data);
  }
}
