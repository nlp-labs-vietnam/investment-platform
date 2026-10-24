import type { AxiosInstance, AxiosResponse } from 'axios';
import type {
  Property,
  Investment,
  PropertyShare,
  PropertyOffer,
} from '@nlp-labs-vietnam/investment-types';

// ── Request payload types ─────────────────────────────────────────────────

export interface PropertyListParams {
  page?: number;
  per_page?: number;
  search?: string;
  status?: 'active' | 'inactive' | 'pending' | 'expired';
  category?: string;
}

export interface InvestPayload {
  amount: number;
  shares?: number;
}

export interface ShareStorePayload {
  shares: number;
  per_share_price: number;
}

export interface MakeOfferPayload {
  shares: number;
  per_share_price: number;
}

export interface DirectBuyPayload {
  offer_id: number;
  shares: number;
}

// ── PropertyAPI ───────────────────────────────────────────────────────────
// Nguồn: routes/api.php → App\Http\Controllers\Api\PropertyController

export class PropertyAPI {
  constructor(private readonly http: AxiosInstance) {}

  /** GET /api/property */
  list(params?: PropertyListParams): Promise<AxiosResponse<Property[]>> {
    return this.http.get<Property[]>('/property', { params });
  }

  /** GET /api/investment/properties */
  investmentList(): Promise<AxiosResponse<Property[]>> {
    return this.http.get<Property[]>('/investment/properties');
  }

  /** GET /api/property-details/{id} */
  detail(id: number): Promise<AxiosResponse<Property>> {
    return this.http.get<Property>(`/property-details/${id}`);
  }

  /** GET /api/investor-profile/{id}/{username} */
  investorProfile(
    id: number,
    username: string,
  ): Promise<AxiosResponse<unknown>> {
    return this.http.get(`/investor-profile/${id}/${username}`);
  }

  /** POST /api/invest/property/{id} */
  invest(
    id: number,
    data: InvestPayload,
  ): Promise<AxiosResponse<Investment>> {
    return this.http.post<Investment>(`/invest/property/${id}`, data);
  }

  /** GET /api/invest/history */
  investHistory(): Promise<AxiosResponse<Investment[]>> {
    return this.http.get<Investment[]>('/invest/history');
  }

  /** GET /api/invest/history/details/{id} */
  investHistoryDetails(id: number): Promise<AxiosResponse<Investment>> {
    return this.http.get<Investment>(`/invest/history/details/${id}`);
  }

  /** POST /api/complete/due/payment/{id} */
  completeDuePayment(
    id: number,
    data: Record<string, unknown>,
  ): Promise<AxiosResponse<unknown>> {
    return this.http.post(`/complete/due/payment/${id}`, data);
  }

  /** GET /api/share/market */
  shareMarket(): Promise<AxiosResponse<PropertyShare[]>> {
    return this.http.get<PropertyShare[]>('/share/market');
  }

  /** GET /api/my/properties */
  myProperties(): Promise<AxiosResponse<Property[]>> {
    return this.http.get<Property[]>('/my/properties');
  }

  /** POST /api/property/share/store/{id} */
  shareStore(
    id: number,
    data: ShareStorePayload,
  ): Promise<AxiosResponse<PropertyShare>> {
    return this.http.post<PropertyShare>(`/property/share/store/${id}`, data);
  }

  /** GET /api/my/shared/properties */
  mySharedProperties(): Promise<AxiosResponse<PropertyShare[]>> {
    return this.http.get<PropertyShare[]>('/my/shared/properties');
  }

  /** POST /api/property/share/update/{id} */
  shareUpdate(
    id: number,
    data: Partial<ShareStorePayload>,
  ): Promise<AxiosResponse<PropertyShare>> {
    return this.http.post<PropertyShare>(`/property/share/update/${id}`, data);
  }

  /** DELETE /api/property/share/remove/{id} */
  shareRemove(id: number): Promise<AxiosResponse<void>> {
    return this.http.delete(`/property/share/remove/${id}`);
  }

  /** POST /api/property/make/offer/{id} */
  makeOffer(
    id: number,
    data: MakeOfferPayload,
  ): Promise<AxiosResponse<PropertyOffer>> {
    return this.http.post<PropertyOffer>(
      `/property/make/offer/${id}`,
      data,
    );
  }

  /** POST /api/direct/buy/share/{id} */
  directBuyShare(
    id: number,
    data: DirectBuyPayload,
  ): Promise<AxiosResponse<unknown>> {
    return this.http.post(`/direct/buy/share/${id}`, data);
  }

  /** GET /api/send/offer/properties */
  sendOfferProperties(): Promise<AxiosResponse<PropertyOffer[]>> {
    return this.http.get<PropertyOffer[]>('/send/offer/properties');
  }

  /** GET /api/receive/offer/properties */
  receiveOfferProperties(): Promise<AxiosResponse<PropertyOffer[]>> {
    return this.http.get<PropertyOffer[]>('/receive/offer/properties');
  }

  /** GET /api/offer/list/{id} */
  offerList(id: number): Promise<AxiosResponse<PropertyOffer[]>> {
    return this.http.get<PropertyOffer[]>(`/offer/list/${id}`);
  }

  /** POST /api/offer/accept/{id} */
  offerAccept(id: number): Promise<AxiosResponse<unknown>> {
    return this.http.post(`/offer/accept/${id}`);
  }

  /** POST /api/offer/reject/{id} */
  offerReject(id: number): Promise<AxiosResponse<unknown>> {
    return this.http.post(`/offer/reject/${id}`);
  }

  /** DELETE /api/offer/remove/{id} */
  offerRemove(id: number): Promise<AxiosResponse<void>> {
    return this.http.delete(`/offer/remove/${id}`);
  }

  /** DELETE /api/property/offer/remove/{id} */
  propertyOfferRemove(id: number): Promise<AxiosResponse<void>> {
    return this.http.delete(`/property/offer/remove/${id}`);
  }

  /** GET /api/offer/conversation/{id} */
  offerConversation(id: number): Promise<AxiosResponse<unknown>> {
    return this.http.get(`/offer/conversation/${id}`);
  }

  /** POST /api/offer/reply/message */
  offerReplyMessage(data: {
    offer_id: number;
    message: string;
  }): Promise<AxiosResponse<unknown>> {
    return this.http.post('/offer/reply/message', data);
  }

  /** POST /api/offer/payment/lock/{id} */
  paymentLock(
    id: number,
    data: Record<string, unknown>,
  ): Promise<AxiosResponse<unknown>> {
    return this.http.post(`/offer/payment/lock/${id}`, data);
  }

  /** POST /api/offer/payment/lock/update/{id} */
  paymentLockUpdate(
    id: number,
    data: Record<string, unknown>,
  ): Promise<AxiosResponse<unknown>> {
    return this.http.post(`/offer/payment/lock/update/${id}`, data);
  }

  /** GET /api/payment/lock/cancel/{id} */
  paymentLockCancel(id: number): Promise<AxiosResponse<unknown>> {
    return this.http.get(`/payment/lock/cancel/${id}`);
  }

  /** POST /api/payment/lock/confirm/{id} */
  paymentLockConfirm(
    id: number,
    data: Record<string, unknown>,
  ): Promise<AxiosResponse<unknown>> {
    return this.http.post(`/payment/lock/confirm/${id}`, data);
  }
}
