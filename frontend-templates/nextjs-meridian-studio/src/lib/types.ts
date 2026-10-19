export type AineAsset = {
  uuid: string;
  url: string;
  thumbnail_url?: string;
  original_url?: string;
  filename?: string;
  metadata?: { alt_text?: string | null; width?: number; height?: number };
};

export type ContentEntry<T extends Record<string, unknown> = Record<string, unknown>> = {
  uuid: string;
  locale: string;
  published_at: string | null;
  fields: T;
};

export type SiteSettingsFields = {
  "site-name"?: string;
  tagline?: string;
  "site-url"?: string;
  "seo-title-template"?: string;
  "default-meta-description"?: string;
  "default-og-image"?: AineAsset[] | AineAsset | null;
  "contact-email"?: string;
  "contact-phone"?: string;
  address?: string;
  navigation?: { label?: string; url?: string }[];
  "social-links"?: { platform?: string; url?: string }[];
  "footer-tagline"?: string;
};

export type HomeHeroFields = {
  eyebrow?: string;
  headline?: string;
  subheadline?: string;
  "primary-cta-label"?: string;
  "primary-cta-url"?: string;
  "secondary-cta-label"?: string;
  "secondary-cta-url"?: string;
  "background-image"?: AineAsset[] | AineAsset | null;
  "stat-1-label"?: string;
  "stat-1-value"?: string;
  "stat-2-label"?: string;
  "stat-2-value"?: string;
  "stat-3-label"?: string;
  "stat-3-value"?: string;
};

export type ServiceDeliverable = {
  label?: string;
};

export type ServiceFields = {
  title?: string;
  description?: string;
  details?: string;
  deliverables?: ServiceDeliverable[];
  icon?: string;
  "sort-order"?: string | number;
};

export type CaseStudyResult = {
  value?: string;
  label?: string;
};

export type CaseStudyFields = {
  title?: string;
  slug?: string;
  excerpt?: string;
  challenge?: string;
  approach?: string;
  outcome?: string;
  results?: CaseStudyResult[];
  body?: string;
  "featured-image"?: AineAsset[] | AineAsset | null;
  gallery?: AineAsset[] | AineAsset | null;
  client?: string;
  industry?: string;
  year?: string;
  "meta-title"?: string;
  "meta-description"?: string;
  featured?: boolean;
  services?: ContentEntry<ServiceFields>[];
};

export type TeamMemberFields = {
  name?: string;
  role?: string;
  bio?: string;
  photo?: AineAsset[] | AineAsset | null;
  "linkedin-url"?: string;
  "twitter-url"?: string;
  "sort-order"?: string | number;
};

export type TestimonialFields = {
  quote?: string;
  "author-name"?: string;
  "author-role"?: string;
  company?: string;
  photo?: AineAsset[] | AineAsset | null;
  "sort-order"?: string | number;
};

export type InsightFields = {
  title?: string;
  slug?: string;
  excerpt?: string;
  category?: string;
  body?: string;
  "featured-image"?: AineAsset[] | AineAsset | null;
  "meta-title"?: string;
  "meta-description"?: string;
  author?: ContentEntry<TeamMemberFields> | null;
};

export type FaqFields = {
  question?: string;
  answer?: string;
  "sort-order"?: string | number;
};

export type ContactPageFields = {
  heading?: string;
  intro?: string;
  "office-hours"?: string;
  "form-submit-label"?: string;
  "success-message"?: string;
};
