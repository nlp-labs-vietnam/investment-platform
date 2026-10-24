// ─────────────────────────────────────────────
// Nguồn: app/Models/Investment.php, PropertyShare.php, PropertyOffer.php, OfferLock.php
// ─────────────────────────────────────────────

export interface Investment {
  id: number;
  user_id: number;
  property_id: number;
  amount: number;
  shares: number;
  per_share_price: number;
  return_amount: number;
  profit: number;
  charge: number;
  trx_id: string;
  status: 'pending' | 'active' | 'completed' | 'rejected';
  /** Computed appends: belongs to auth user */
  invest: boolean;
  next_installment_date: string | null;
  next_installment_date_end: string | null;
  deleted_at: string | null;
  created_at: string;
  updated_at: string;
  // relations
  property?: import('./property.d').Property;
  propertyShare?: PropertyShare;
}

export interface PropertyShare {
  id: number;
  user_id: number;
  property_id: number;
  investment_id: number;
  shares: number;
  per_share_price: number;
  status: 0 | 1;
  created_at: string;
}

export interface PropertyOffer {
  id: number;
  sender_id: number;
  receiver_id: number;
  property_id: number;
  shares: number;
  per_share_price: number;
  total_amount: number;
  status: 'pending' | 'accepted' | 'rejected' | 'expired';
  created_at: string;
}

export interface OfferLock {
  id: number;
  offer_id: number;
  user_id: number;
  amount: number;
  status: 'locked' | 'confirmed' | 'cancelled';
  expires_at: string;
  created_at: string;
}

export interface OfferReply {
  id: number;
  offer_id: number;
  user_id: number;
  message: string;
  created_at: string;
}
