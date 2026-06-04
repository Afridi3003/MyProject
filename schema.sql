CREATE DATABASE IF NOT EXISTS silinex_global
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE silinex_global;

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) NOT NULL UNIQUE,
    title VARCHAR(180) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(20) NOT NULL,
    image VARCHAR(600) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS service_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_slug VARCHAR(120) NOT NULL,
    detail TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    INDEX (service_slug),
    UNIQUE KEY unique_service_detail_order (service_slug, sort_order)
);

CREATE TABLE IF NOT EXISTS service_pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_slug VARCHAR(120) NOT NULL UNIQUE,
    subtitle VARCHAR(255) NOT NULL,
    hero_image VARCHAR(600) NOT NULL,
    hero_alignment VARCHAR(30) NOT NULL DEFAULT 'center',
    focus TEXT NOT NULL,
    offer_json JSON NOT NULL,
    offer_image VARCHAR(600) NOT NULL DEFAULT '',
    offer_alignment VARCHAR(30) NOT NULL DEFAULT 'image-right',
    engagement_json JSON NOT NULL,
    why_json JSON NOT NULL,
    why_image VARCHAR(600) NOT NULL DEFAULT '',
    why_alignment VARCHAR(30) NOT NULL DEFAULT 'image-left'
);

CREATE TABLE IF NOT EXISTS industries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    industry_key VARCHAR(80) NOT NULL UNIQUE,
    tab VARCHAR(120) NOT NULL,
    tab_icon TEXT NOT NULL,
    category VARCHAR(180) NOT NULL,
    heading VARCHAR(220) NOT NULL,
    description TEXT NOT NULL,
    features_json JSON NOT NULL,
    image VARCHAR(600) NOT NULL,
    metrics_json JSON NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS technologies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(120) NOT NULL,
    name VARCHAR(160) NOT NULL,
    logo VARCHAR(600) NOT NULL,
    category_sort INT NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    UNIQUE KEY unique_technology_name (category, name)
);

CREATE TABLE IF NOT EXISTS partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    partner_type ENUM('featured', 'technology', 'alliance') NOT NULL DEFAULT 'featured',
    name VARCHAR(160) NOT NULL,
    logo VARCHAR(600) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    UNIQUE KEY unique_partner_name (partner_type, name)
);

CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote TEXT NOT NULL,
    person_name VARCHAR(160) NOT NULL,
    company VARCHAR(180) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    UNIQUE KEY unique_testimonial_person (person_name, company)
);

CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    UNIQUE KEY unique_question (question)
);

CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    published_date DATE NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    UNIQUE KEY unique_blog_title (title)
);

CREATE TABLE IF NOT EXISTS careers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    heading VARCHAR(180) NOT NULL,
    experience VARCHAR(80) NOT NULL,
    job_type VARCHAR(80) NOT NULL,
    city VARCHAR(120) NOT NULL,
    location VARCHAR(180) NOT NULL,
    short_content TEXT NOT NULL,
    content TEXT NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_career_heading (heading)
);

CREATE TABLE IF NOT EXISTS news_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    heading VARCHAR(180) NOT NULL UNIQUE,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(160) NOT NULL,
    email VARCHAR(180) NOT NULL,
    phone VARCHAR(80) DEFAULT '',
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin_content_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_key VARCHAR(80) NOT NULL,
    heading VARCHAR(180) NOT NULL,
    short_content TEXT NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(600) DEFAULT '',
    icon VARCHAR(120) DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (module_key)
);

CREATE TABLE IF NOT EXISTS cms_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_key VARCHAR(80) NOT NULL,
    action_type VARCHAR(40) NOT NULL,
    record_id INT NOT NULL DEFAULT 0,
    record_label VARCHAR(220) NOT NULL DEFAULT '',
    snapshot_json JSON NOT NULL,
    admin_user VARCHAR(120) NOT NULL DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (section_key)
);

INSERT INTO services (slug, title, description, icon, image, sort_order) VALUES
('staffing', 'Staffing', 'End-to-end technology staffing that connects skilled professionals with organizations ready to grow.', '01', 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=900&q=80', 1),
('application-managed-services', 'Application Managed Services', 'Managed application support, monitoring, maintenance, and optimization for enterprise platforms.', '02', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=900&q=80', 2),
('grc-services', 'GRC Services', 'Governance, risk, and compliance services that build trust, resilience, and operational clarity.', '03', 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=900&q=80', 3),
('oracle-services', 'Oracle Services', 'Oracle consulting, implementation, integrations, and cloud advisory for modern enterprise teams.', '04', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80', 4),
('silinex-dummy-services', 'Silinex Dummy Services', 'It is a commercial IT company service offering.', '05', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80', 5)
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), icon = VALUES(icon), image = VALUES(image), sort_order = VALUES(sort_order);

INSERT IGNORE INTO service_details (service_slug, detail, sort_order) VALUES
('staffing', 'Contract, permanent, contract-to-hire, remote, and offshore staffing models.', 1),
('staffing', 'Screened technical professionals matched to project skills, culture, and delivery timelines.', 2),
('staffing', 'Support for developers, cloud engineers, ERP consultants, QA teams, analysts, and support roles.', 3),
('application-managed-services', 'Continuous application monitoring, incident handling, maintenance, and performance tuning.', 1),
('application-managed-services', 'Enhancement support for enterprise applications, integrations, reporting, and user workflows.', 2),
('application-managed-services', 'Structured service management that helps internal teams reduce downtime and improve reliability.', 3),
('grc-services', 'Governance frameworks, risk assessments, control mapping, audit readiness, and compliance tracking.', 1),
('grc-services', 'Support for policies, process documentation, access reviews, and security governance operations.', 2),
('grc-services', 'Reporting that gives leadership visibility into risks, controls, and compliance status.', 3),
('oracle-services', 'Oracle Fusion ERP, HCM, SCM, CX, OIC, reporting, migration, and implementation support.', 1),
('oracle-services', 'Integration planning, configuration assistance, testing, issue resolution, and post-go-live support.', 2),
('oracle-services', 'Consulting for improving Oracle workflows, data movement, automation, and process alignment.', 3),
('silinex-dummy-services', 'Commercial IT service support for growing business teams.', 1),
('silinex-dummy-services', 'Flexible consulting and delivery assistance aligned with enterprise needs.', 2),
('silinex-dummy-services', 'Technology support designed to improve operations and reliability.', 3);

INSERT INTO service_pages (service_slug, subtitle, hero_image, focus, offer_json, engagement_json, why_json) VALUES
('staffing', 'Flexible hiring models for dependable technology delivery.', 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1400&q=80', 'Build the right team faster with screened technical talent, flexible engagement models, and hiring support that aligns skills with business goals.', JSON_ARRAY('Permanent, contract, contract-to-hire, remote, and offshore staffing support.', 'Candidate screening for developers, cloud engineers, QA teams, ERP consultants, analysts, and support roles.', 'Fast shortlisting, interview coordination, onboarding support, and resource replacement assistance.'), JSON_ARRAY('Dedicated resource model', 'Project-based hiring', 'Remote and offshore augmentation', 'Contract-to-hire support'), JSON_ARRAY('Faster hiring cycles with targeted screening and role matching.', 'Reduced internal recruitment load while keeping quality high.', 'Access to specialized IT and enterprise technology professionals.')),
('application-managed-services', 'Focus on growth. Leave operations to us.', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1400&q=80', 'Our Application Managed Services framework enables you to focus on business outcomes while we take care of your IT environment with support, enhancements, scalability, and transparency.', JSON_ARRAY('Proactive monitoring, incident management, performance optimization, and issue resolution.', 'Cloud migration and optimization support for scalable, cost-efficient platforms.', 'Enhancements, customizations, integrations, and reporting improvements aligned with business needs.'), JSON_ARRAY('24/7 global support', 'SLA-driven delivery model', 'Onsite / offshore / hybrid support', 'Continuous improvement roadmap'), JSON_ARRAY('Operational stability so your applications run smoothly while your staff focuses on growth.', 'End-to-end ownership from maintenance to integration and optimization.', 'Continuous optimization across cloud, application, and workflow performance.')),
('grc-services', 'Governance, risk, and compliance built for clarity.', 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1400&q=80', 'Strengthen business resilience with governance frameworks, risk visibility, control documentation, audit readiness, and practical compliance operations.', JSON_ARRAY('Risk assessments, control mapping, compliance tracking, and governance reporting.', 'Policy documentation, access reviews, audit support, and process standardization.', 'Leadership dashboards that provide visibility into risks, controls, ownership, and remediation.'), JSON_ARRAY('GRC advisory', 'Audit readiness support', 'Control testing assistance', 'Ongoing compliance operations'), JSON_ARRAY('Clearer accountability across risk owners, process owners, and leadership.', 'Reduced audit friction through better documentation and control evidence.', 'Practical compliance processes that support business speed instead of slowing it down.')),
('oracle-services', 'Oracle consulting for modern enterprise operations.', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1400&q=80', 'Plan, implement, integrate, and support Oracle cloud and enterprise solutions across ERP, HCM, SCM, CX, OIC, reporting, and process automation.', JSON_ARRAY('Oracle Fusion ERP, HCM, SCM, CX, OIC, reporting, migration, and implementation support.', 'Configuration, integration planning, data movement, testing, issue resolution, and post-go-live assistance.', 'Workflow improvement and enterprise process alignment for better adoption and operational value.'), JSON_ARRAY('Implementation consulting', 'Oracle managed support', 'Integration and reporting support', 'Post-go-live optimization'), JSON_ARRAY('Deep enterprise process understanding across Oracle business functions.', 'Support from planning through launch, stabilization, and improvement.', 'Better alignment between Oracle systems, integrations, users, and reporting needs.')),
('silinex-dummy-services', 'Commercial IT service support for growing teams.', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1400&q=80', 'A flexible commercial IT service offering for organizations that need consulting, technology support, and delivery assistance.', JSON_ARRAY('Commercial IT service support for growing business teams.', 'Flexible consulting and delivery assistance aligned with enterprise needs.', 'Technology support designed to improve operations and reliability.'), JSON_ARRAY('Consulting support', 'Managed assistance', 'Remote delivery', 'Business technology support'), JSON_ARRAY('Simple service model for fast-moving teams.', 'Practical support across common IT needs.', 'Reliable assistance for operations and delivery.'))
ON DUPLICATE KEY UPDATE subtitle = VALUES(subtitle), hero_image = VALUES(hero_image), focus = VALUES(focus), offer_json = VALUES(offer_json), engagement_json = VALUES(engagement_json), why_json = VALUES(why_json);

INSERT INTO industries (industry_key, tab, tab_icon, category, heading, description, features_json, image, metrics_json, sort_order) VALUES
('proptech', 'PropTech', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M3 7v14M21 7v14M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16M9 9h6M9 13h6M9 17h6"/></svg>', 'PROPTECH (REAL ESTATE)', 'Smart Solutions for Modern Real Estate', 'We deliver smart digital platforms that streamline property management, tenant engagement, and real-time asset performance for real estate businesses.', JSON_ARRAY(), 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80', JSON_ARRAY(), 1),
('retail', 'Retail', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>', 'RETAIL & E-COMMERCE', 'Next-Gen Retail & Omnichannel Systems', 'Technology and data systems that improve operations, customer experience, and help your retail brand scale across channels.', JSON_ARRAY(), 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?auto=format&fit=crop&w=800&q=80', JSON_ARRAY(), 2),
('healthcare', 'Healthcare', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>', 'HEALTHCARE & LIFE SCIENCES', 'Secure, Compliant Healthcare Technology', 'Secure, compliant, and reliable systems that optimize clinical workflows, protect patient data, and support digital health innovation.', JSON_ARRAY(), 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80', JSON_ARRAY(), 3),
('energy', 'Energy', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>', 'ENERGY & UTILITIES', 'Digital Energy Solutions & IoT Analytics', 'Modern digital infrastructure, telemetry, and analytics for dependable grid operations and renewable energy transitions.', JSON_ARRAY(), 'https://images.unsplash.com/photo-1466611653911-95081537e5b7?auto=format&fit=crop&w=800&q=80', JSON_ARRAY(), 4),
('enterprise', 'Enterprise', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>', 'ENTERPRISE & TECH', 'Scalable Foundations for Growing Enterprises', 'Reliable, secure, and performant systems designed to empower teams, automate processes, and scale operations smoothly.', JSON_ARRAY(), 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80', JSON_ARRAY(), 5),
('edtech', 'EdTech', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>', 'EDTECH & LEARNING', 'Advanced Digital Learning Platforms', 'Modern LMS integrations, curriculum delivery platforms, and expert professionals that help educational brands scale and grow.', JSON_ARRAY(), 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&w=800&q=80', JSON_ARRAY(), 6)
ON DUPLICATE KEY UPDATE tab = VALUES(tab), category = VALUES(category), heading = VALUES(heading), description = VALUES(description), image = VALUES(image), sort_order = VALUES(sort_order);

INSERT IGNORE INTO technologies (category, name, logo, category_sort, sort_order) VALUES
('Web Platform', 'PHP', 'https://cdn.simpleicons.org/php/777BB4', 1, 1),
('Web Platform', 'JavaScript', 'https://cdn.simpleicons.org/javascript/F7DF1E', 1, 2),
('Web Platform', 'Swift', 'https://cdn.simpleicons.org/swift/F05138', 1, 3),
('Web Platform', 'TypeScript', 'https://cdn.simpleicons.org/typescript/3178C6', 1, 4),
('Web Platform', 'Python', 'https://cdn.simpleicons.org/python/3776AB', 1, 5),
('Web Platform', 'Java', 'https://cdn.simpleicons.org/openjdk/ED8B00', 1, 6),
('Web Platform', 'Ruby', 'https://cdn.simpleicons.org/ruby/CC342D', 1, 7),
('Web Platform', 'C++', 'https://cdn.simpleicons.org/cplusplus/00599C', 1, 8),
('Web Platform', 'React JS', 'https://cdn.simpleicons.org/react/61DAFB', 1, 9),
('Web Platform', 'Laravel', 'https://cdn.simpleicons.org/laravel/FF2D20', 1, 10),
('Database', 'PostgreSQL', 'https://cdn.simpleicons.org/postgresql/4169E1', 2, 1),
('Database', 'Oracle', 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128', 2, 2),
('Database', 'MySQL', 'https://cdn.simpleicons.org/mysql/4479A1', 2, 3),
('Database', 'MongoDB', 'https://cdn.simpleicons.org/mongodb/47A248', 2, 4),
('Cloud & DevOps', 'Kubernetes', 'https://cdn.simpleicons.org/kubernetes/326CE5', 3, 1),
('Cloud & DevOps', 'Docker', 'https://cdn.simpleicons.org/docker/2496ED', 3, 2),
('Cloud & DevOps', 'GitHub', 'https://cdn.simpleicons.org/github/181717', 3, 3),
('Oracle', 'Oracle Fusion ERP', 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128', 4, 1),
('Oracle', 'Oracle HCM Cloud', 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128', 4, 2),
('Oracle', 'Oracle SCM Cloud', 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128', 4, 3),
('Oracle', 'Oracle CX Cloud', 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128', 4, 4),
('Oracle', 'Oracle OIC', 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128', 4, 5),
('Integration', 'Informatica', 'https://www.google.com/s2/favicons?domain=informatica.com&sz=128', 5, 1),
('Integration', 'TIBCO', 'https://www.google.com/s2/favicons?domain=tibco.com&sz=128', 5, 2),
('Integration', 'SnapLogic', 'https://www.google.com/s2/favicons?domain=snaplogic.com&sz=128', 5, 3),
('Integration', 'Zapier', 'https://www.google.com/s2/favicons?domain=zapier.com&sz=128', 5, 4),
('Integration', 'Boomi', 'https://www.google.com/s2/favicons?domain=boomi.com&sz=128', 5, 5),
('Integration', 'Workato', 'https://www.google.com/s2/favicons?domain=workato.com&sz=128', 5, 6),
('Integration', 'MuleSoft', 'https://www.google.com/s2/favicons?domain=mulesoft.com&sz=128', 5, 7);

INSERT IGNORE INTO partners (partner_type, name, logo, sort_order) VALUES
('featured', 'Oracle', 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128', 1),
('featured', 'Zoho', 'https://www.google.com/s2/favicons?domain=zoho.com&sz=128', 2),
('featured', 'AWS', 'https://www.google.com/s2/favicons?domain=aws.amazon.com&sz=128', 3),
('featured', 'Microsoft', 'https://www.google.com/s2/favicons?domain=microsoft.com&sz=128', 4),
('featured', 'Salesforce', 'https://www.google.com/s2/favicons?domain=salesforce.com&sz=128', 5),
('featured', 'ServiceNow', 'https://www.google.com/s2/favicons?domain=servicenow.com&sz=128', 6),
('technology', 'Zoho', 'https://www.google.com/s2/favicons?domain=zoho.com&sz=128', 1),
('technology', 'Microsoft', 'https://www.google.com/s2/favicons?domain=microsoft.com&sz=128', 2),
('technology', 'AWS', 'https://www.google.com/s2/favicons?domain=aws.amazon.com&sz=128', 3),
('technology', 'Oracle Fusion Cloud', 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128', 4),
('alliance', 'Raveesh', 'https://dummyimage.com/180x100/ffffff/08233f&text=RAVEESH', 1),
('alliance', 'DHP Properties', 'https://dummyimage.com/180x100/ffffff/0e7c66&text=DHP+Properties', 2);

INSERT IGNORE INTO testimonials (quote, person_name, company, sort_order) VALUES
('Silinex Global Services delivered exceptional results for our digital transformation journey. Their technical expertise helped us scale with confidence.', 'Compliance Manager', 'Financial Services Firm', 1),
('The team provided highly skilled professionals who matched our project requirements quickly and reliably.', 'HR Director', 'Global Services Company', 2),
('Their managed services approach improved system performance and gave our internal teams room to focus on core work.', 'Operations Head', 'Retail Organization', 3);

INSERT IGNORE INTO faqs (question, answer, sort_order) VALUES
('What services does Silinex Global Services provide?', 'IT staffing, application managed services, GRC services, Oracle consulting, Zoho consulting, and digital transformation support.', 1),
('Which industries do you serve?', 'Real estate, retail, healthcare, pharma, energy, fintech, manufacturing, education, and enterprise technology.', 2),
('Do you provide offshore and remote staffing services?', 'Yes. Flexible hiring models include contract, permanent, contract-to-hire, remote, and offshore resource augmentation.', 3),
('What is Application Managed Services?', 'AMS is a support model where enterprise applications are monitored, maintained, optimized, and improved continuously.', 4);

INSERT IGNORE INTO blogs (title, published_date, sort_order) VALUES
('Leadership Skills Needed in the AI Era', '2026-05-04', 1),
('AI Implementation Challenges and Solutions', '2026-04-23', 2),
('Common Pitfalls in Digital Transformation', '2026-04-13', 3);

INSERT IGNORE INTO careers (heading, experience, job_type, city, location, short_content, content) VALUES
('IT Recruiter', '2-4 Years', 'Full Time', 'Hyderabad', 'Hyderabad, India', 'Hiring technology recruiters for staffing delivery.', 'Manage sourcing, screening, coordination, and candidate pipeline ownership.'),
('Java Developer', '6-8 Years', 'Full Time', 'Hyderabad', 'Hyderabad', 'Enterprise Java application development role.', 'Build and maintain enterprise Java applications, APIs, integrations, and platform features.'),
('Oracle DBA', '7+ Years', 'Full Time', 'Bengaluru', 'Bengaluru', 'Oracle database administration and support.', 'Support Oracle database operations, backups, tuning, access control, and availability.');

INSERT IGNORE INTO news_categories (heading) VALUES
('Company Announcements'),
('Partnerships & Alliances'),
('Product / Solution Launches'),
('Awards & Recognition'),
('Oracle Services');

INSERT IGNORE INTO enquiries (name, email, phone, message) VALUES
('Bodanapu Bhanu', 'bbhanusree59@gmail.com', '+91 90000 00000', 'Interested in staffing and managed services.'),
('Prakash', 'bhrpropertiesllp@gmail.com', '+91 90000 00001', 'Need support for IT consulting and application services.'),
('Roshani', 'contact@example.com', '+91 90000 00002', 'Please share details about Oracle services.');

INSERT INTO admin_content_items (module_key, heading, short_content, content, image, icon, sort_order)
SELECT * FROM (
    SELECT 'slider', 'Empowering Digital Growth', 'Main homepage hero message.', 'Promote Silinex IT staffing, managed services, GRC, Oracle, and digital transformation capabilities.', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80', 'Hero', 1
    UNION ALL SELECT 'home-industry', 'Enterprise Technology', 'Industry block for the homepage.', 'Showcase Silinex capabilities for enterprise and technology-led organizations.', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=900&q=80', 'Industry', 1
    UNION ALL SELECT 'news', 'Silinex Expands Managed Services', 'Company update for latest news.', 'Silinex continues to expand managed services and enterprise support offerings for growing clients.', '', 'News', 1
    UNION ALL SELECT 'technology-category', 'Cloud & DevOps', 'Technology category for platform capability.', 'Cloud, deployment, automation, monitoring, and DevOps technology expertise.', '', 'Tech', 1
    UNION ALL SELECT 'why-work', 'Collaborative Growth Culture', 'Reason to work with Silinex.', 'Silinex supports learning, ownership, mentoring, and growth across technology careers.', '', 'Work', 1
    UNION ALL SELECT 'life', 'Life at Silinex', 'People-first workplace content.', 'A collaborative environment built around client impact, technical growth, and shared success.', '', 'Life', 1
    UNION ALL SELECT 'values', 'Trust and Delivery', 'Core company value.', 'We value accountability, transparency, dependable delivery, and long-term partnerships.', '', 'Value', 1
) seed
WHERE NOT EXISTS (SELECT 1 FROM admin_content_items LIMIT 1);
