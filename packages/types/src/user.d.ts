// ─────────────────────────────────────────────
// Nguồn: app/Models/User.php, UserKyc.php, UserSocial.php, Rank.php, Referral.php
// ─────────────────────────────────────────────

export interface User {
  id: number;
  firstname: string;
  lastname: string;
  username: string;
  email: string;
  phone_code: string | null;
  phone: string | null;
  country_code: string | null;
  country: string | null;
  balance: number;
  image: string | null;
  image_driver: string | null;
  /** 1 = active, 0 = banned */
  status: 0 | 1;
  email_verified_at: string | null;
  kyc_verified_at: string | null;
  two_fa: 0 | 1;
  referral_id: number | null;
  language_id: number | null;
  deleted_at: string | null;
  created_at: string;
  updated_at: string;
  // relations
  kyc?: UserKyc;
  rank?: Rank;
  referrals?: Referral[];
}

export interface UserKyc {
  id: number;
  user_id: number;
  kyc_id: number;
  data: Record<string, string | number>;
  status: 'pending' | 'approved' | 'rejected';
  feedback: string | null;
  created_at: string;
  updated_at: string;
}

export interface UserSocial {
  id: number;
  user_id: number;
  provider: string;
  provider_id: string;
}

export interface Rank {
  id: number;
  name: string;
  minimum_invest: number;
  maximum_invest: number;
  commission: number;
  badge: string | null;
}

export interface Referral {
  id: number;
  user_id: number;
  referred_by: number;
  commission: number;
  created_at: string;
}

export interface ReferralBonus {
  id: number;
  user_id: number;
  referral_id: number;
  amount: number;
  trx_id: string;
  created_at: string;
}
