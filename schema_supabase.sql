-- Supabase / PostgreSQL schema for the Silinex Global Services website.
-- Run this in Supabase Dashboard > SQL Editor before switching DB_DRIVER to pgsql.

CREATE TABLE IF NOT EXISTS services (
    id SERIAL PRIMARY KEY,
    slug VARCHAR(120) NOT NULL UNIQUE,
    title VARCHAR(180) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(20) NOT NULL,
    image VARCHAR(600) NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS service_details (
    id SERIAL PRIMARY KEY,
    service_slug VARCHAR(120) NOT NULL,
    detail TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    UNIQUE (service_slug, sort_order)
);

CREATE TABLE IF NOT EXISTS service_pages (
    id SERIAL PRIMARY KEY,
    service_slug VARCHAR(120) NOT NULL UNIQUE,
    subtitle VARCHAR(255) NOT NULL,
    hero_image VARCHAR(600) NOT NULL,
    hero_alignment VARCHAR(30) NOT NULL DEFAULT 'center',
    focus TEXT NOT NULL,
    offer_json JSONB NOT NULL DEFAULT '[]'::jsonb,
    offer_image VARCHAR(600) NOT NULL DEFAULT '',
    offer_alignment VARCHAR(30) NOT NULL DEFAULT 'image-right',
    engagement_json JSONB NOT NULL DEFAULT '[]'::jsonb,
    why_json JSONB NOT NULL DEFAULT '[]'::jsonb,
    why_image VARCHAR(600) NOT NULL DEFAULT '',
    why_alignment VARCHAR(30) NOT NULL DEFAULT 'image-left'
);

CREATE TABLE IF NOT EXISTS industries (
    id SERIAL PRIMARY KEY,
    industry_key VARCHAR(80) NOT NULL UNIQUE,
    tab VARCHAR(120) NOT NULL,
    tab_icon TEXT NOT NULL,
    category VARCHAR(180) NOT NULL,
    heading VARCHAR(220) NOT NULL,
    description TEXT NOT NULL,
    features_json JSONB NOT NULL DEFAULT '[]'::jsonb,
    image VARCHAR(600) NOT NULL,
    metrics_json JSONB NOT NULL DEFAULT '[]'::jsonb,
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_active INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS technologies (
    id SERIAL PRIMARY KEY,
    category VARCHAR(120) NOT NULL,
    name VARCHAR(160) NOT NULL,
    logo VARCHAR(600) NOT NULL,
    category_sort INTEGER NOT NULL DEFAULT 0,
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_active INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS partners (
    id SERIAL PRIMARY KEY,
    partner_type VARCHAR(40) NOT NULL DEFAULT 'featured',
    name VARCHAR(160) NOT NULL,
    logo VARCHAR(600) NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_active INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS testimonials (
    id SERIAL PRIMARY KEY,
    quote TEXT NOT NULL,
    person_name VARCHAR(160) NOT NULL,
    company VARCHAR(180) NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_active INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS faqs (
    id SERIAL PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_active INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS blogs (
    id SERIAL PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    published_date DATE NOT NULL DEFAULT CURRENT_DATE,
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_active INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS careers (
    id SERIAL PRIMARY KEY,
    heading VARCHAR(180) NOT NULL,
    experience VARCHAR(80) NOT NULL,
    job_type VARCHAR(80) NOT NULL,
    city VARCHAR(120) NOT NULL,
    location VARCHAR(180) NOT NULL,
    short_content TEXT NOT NULL,
    content TEXT NOT NULL,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS news_categories (
    id SERIAL PRIMARY KEY,
    heading VARCHAR(180) NOT NULL UNIQUE,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS enquiries (
    id SERIAL PRIMARY KEY,
    name VARCHAR(160) NOT NULL,
    email VARCHAR(180) NOT NULL,
    phone VARCHAR(80) DEFAULT '',
    message TEXT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin_content_items (
    id SERIAL PRIMARY KEY,
    module_key VARCHAR(80) NOT NULL,
    heading VARCHAR(180) NOT NULL,
    short_content TEXT NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(600) DEFAULT '',
    icon VARCHAR(120) DEFAULT '',
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cms_versions (
    id SERIAL PRIMARY KEY,
    section_key VARCHAR(80) NOT NULL,
    action_type VARCHAR(40) NOT NULL,
    record_id INTEGER NOT NULL DEFAULT 0,
    record_label VARCHAR(220) NOT NULL DEFAULT '',
    snapshot_json JSONB NOT NULL DEFAULT '{}'::jsonb,
    admin_user VARCHAR(120) NOT NULL DEFAULT '',
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_service_details_slug ON service_details (service_slug);
CREATE INDEX IF NOT EXISTS idx_admin_content_module ON admin_content_items (module_key);
CREATE INDEX IF NOT EXISTS idx_cms_versions_section ON cms_versions (section_key);
