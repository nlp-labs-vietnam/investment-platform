// ─────────────────────────────────────────────
// @nlp-labs-vietnam/investment-types
// Nguồn: app/Models/Property.php, PropertySeo.php, Image.php, Address.php, Amenity.php
// ─────────────────────────────────────────────

export interface Property {
  id: number;
  name: string;
  slug: string;
  status: 'active' | 'inactive' | 'pending' | 'expired';
  minimum_amount: number | null;
  maximum_amount: number | null;
  fixed_amount: number | null;
  /** Computed appends: basicControl()->currency_symbol + fractionNumber() */
  investment_amount: string;
  amenity_id: number[];
  faq: PropertyFaq[] | null;
  total_share: number;
  available_share: number;
  per_share_price: number;
  return_type: 'profit' | 'loss' | null;
  profit_type: 'percentage' | 'fixed' | null;
  profit: number | null;
  return_time: number | null;
  return_time_unit: 'day' | 'week' | 'month' | 'year' | null;
  total_reviews: number;
  average_rating: number;
  start_date: string;
  expire_date: string;
  address_id: number | null;
  deleted_at: string | null;
  created_at: string;
  updated_at: string;
  // relations
  address?: Address;
  image?: Image[];
  seo?: PropertySeo;
  amenities?: Amenity[];
}

export interface PropertyFaq {
  question: string;
  answer: string;
}

export interface PropertySeo {
  id: number;
  property_id: number;
  meta_title: string;
  meta_description: string;
  og_description: string;
  keywords: string;
}

export interface Image {
  id: number;
  path: string;
  driver: string | null;
  sort_order: number;
  alt: string | null;
  caption: string | null;
  imageable_type: string;
  imageable_id: number;
}

export interface Address {
  id: number;
  address: string;
  city: string | null;
  state: string | null;
  country: string | null;
  zip: string | null;
  latitude: number | null;
  longitude: number | null;
}

export interface Amenity {
  id: number;
  name: string;
  icon: string | null;
}
