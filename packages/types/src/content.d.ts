// ─────────────────────────────────────────────
// Nguồn: app/Models/Page.php, Blog.php, BlogCategory.php, Content.php, ManageMenu.php
// ─────────────────────────────────────────────

export interface Page {
  id: number;
  name: string;
  slug: string;
  meta_title: string | null;
  meta_description: string | null;
  og_description: string | null;
  keywords: string | null;
  status: 0 | 1;
  created_at: string;
  details?: PageDetail[];
}

export interface PageDetail {
  id: number;
  page_id: number;
  language_id: number;
  content: string | null;
}

export interface Blog {
  id: number;
  blog_category_id: number;
  slug: string;
  image: string | null;
  image_driver: string | null;
  status: 0 | 1;
  created_at: string;
  updated_at: string;
  category?: BlogCategory;
  details?: BlogDetail[];
}

export interface BlogDetail {
  id: number;
  blog_id: number;
  language_id: number;
  title: string;
  description: string | null;
  meta_title: string | null;
  meta_description: string | null;
}

export interface BlogCategory {
  id: number;
  slug: string;
  status: 0 | 1;
}

export interface Content {
  id: number;
  name: string;
  theme: string;
  status: 0 | 1;
  details?: ContentDetail[];
}

export interface ContentDetail {
  id: number;
  content_id: number;
  language_id: number;
  key: string;
  value: string | null;
  data_type: string | null;
}

export interface ManageMenu {
  id: number;
  name: string;
  type: string;
  theme: string;
  menu_items: MenuItem[];
  status: 0 | 1;
}

export interface MenuItem {
  label: string;
  url: string;
  target: '_self' | '_blank';
  children?: MenuItem[];
}

export interface Language {
  id: number;
  name: string;
  code: string;
  default_status: 0 | 1;
  status: 0 | 1;
}
