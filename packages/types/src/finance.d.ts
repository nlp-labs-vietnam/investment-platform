// ─────────────────────────────────────────────
// Nguồn: app/Models/Deposit.php, Payout.php, Transaction.php, Fund.php, Gateway.php
// ─────────────────────────────────────────────

export interface Deposit {
  id: number;
  user_id: number;
  payment_method_id: number;
  payment_method_currency: string;
  amount: number;
  percentage_charge: number;
  fixed_charge: number;
  payable_amount: number;
  base_currency_charge: number;
  payable_amount_in_base_currency: number;
  btc_amount: number | null;
  btc_wallet: string | null;
  payment_id: string | null;
  information: Record<string, unknown> | null;
  trx_id: string;
  /** 1=pending, 2=success, 3=cancelled, 4=rejected */
  status: 1 | 2 | 3 | 4;
  note: string | null;
  depositable_id: number | null;
  depositable_type: string | null;
  created_at: string;
  updated_at: string;
}

export interface Payout {
  id: number;
  user_id: number;
  payout_method_id: number;
  payout_currency_code: string;
  amount: number;
  charge: number;
  net_amount: number;
  amount_in_base_currency: number;
  charge_in_base_currency: number;
  net_amount_in_base_currency: number;
  response_id: string | null;
  last_error: string | null;
  information: Record<string, unknown> | null;
  meta_field: Record<string, unknown> | null;
  feedback: string | null;
  trx_id: string;
  /** 1=pending, 2=success, 3=rejected */
  status: 1 | 2 | 3;
  created_at: string;
}

export interface Transaction {
  id: number;
  user_id: number;
  transactional_id: number;
  transactional_type: string;
  amount: number;
  balance: number;
  charge: number;
  /** '+' = credit, '-' = debit */
  trx_type: '+' | '-';
  remarks: string;
  trx_id: string;
  created_at: string;
}

export interface Fund {
  id: number;
  user_id: number;
  amount: number;
  trx_id: string;
  status: 0 | 1;
  created_at: string;
}

export interface Gateway {
  id: number;
  name: string;
  code: string;
  alias: string;
  status: 0 | 1;
  min_amount: number;
  max_amount: number;
  percentage_charge: number;
  fixed_charge: number;
  currencies: Record<string, GatewayCurrency>;
}

export interface GatewayCurrency {
  currency: string;
  symbol: string;
  rate: number;
  min_amount: number;
  max_amount: number;
  percentage_charge: number;
  fixed_charge: number;
}

export interface MoneyTransfer {
  id: number;
  sender_id: number;
  receiver_id: number;
  amount: number;
  charge: number;
  trx_id: string;
  created_at: string;
}
