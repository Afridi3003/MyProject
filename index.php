<?php
$earlyRequestPath = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '', '/');
if ($earlyRequestPath === 'admin') {
    require __DIR__ . '/admin.php';
    exit;
}

$services = [
    [
        'title' => 'Staffing',
        'text' => 'End-to-end technology staffing that connects skilled professionals with organizations ready to grow.',
        'icon' => '01',
        'image' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=900&q=80',
        'slug' => 'staffing',
        'details' => [
            'Contract, permanent, contract-to-hire, remote, and offshore staffing models.',
            'Screened technical professionals matched to project skills, culture, and delivery timelines.',
            'Support for hiring developers, cloud engineers, ERP consultants, QA teams, analysts, and support roles.'
        ]
    ],
    [
        'title' => 'Application Managed Services',
        'text' => 'Managed application support, monitoring, maintenance, and optimization for enterprise platforms.',
        'icon' => '02',
        'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=900&q=80',
        'slug' => 'application-managed-services',
        'details' => [
            'Continuous application monitoring, incident handling, maintenance, and performance tuning.',
            'Enhancement support for enterprise applications, integrations, reporting, and user workflows.',
            'Structured service management that helps internal teams reduce downtime and improve reliability.'
        ]
    ],
    [
        'title' => 'GRC Services',
        'text' => 'Governance, risk, and compliance services that build trust, resilience, and operational clarity.',
        'icon' => '03',
        'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=900&q=80',
        'slug' => 'grc-services',
        'details' => [
            'Governance frameworks, risk assessments, control mapping, audit readiness, and compliance tracking.',
            'Support for policies, process documentation, access reviews, and security governance operations.',
            'Practical reporting that gives leadership better visibility into risks, controls, and compliance status.'
        ]
    ],
    [
        'title' => 'Oracle Services',
        'text' => 'Oracle consulting, implementation, integrations, and cloud advisory for modern enterprise teams.',
        'icon' => '04',
        'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80',
        'slug' => 'oracle-services',
        'details' => [
            'Oracle Fusion ERP, HCM, SCM, CX, OIC, reporting, migration, and implementation support.',
            'Integration planning, configuration assistance, testing, issue resolution, and post-go-live support.',
            'Consulting for improving Oracle workflows, data movement, automation, and enterprise process alignment.'
        ]
    ],
    [
        'title' => 'Other Services',
        'text' => 'It is a commercial IT company service offering.',
        'icon' => '05',
        'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80',
        'slug' => 'silinex-dummy-services',
        'details' => [
            'Commercial IT service support for growing business teams.',
            'Flexible consulting and delivery assistance aligned with enterprise needs.',
            'Technology support designed to improve operations and reliability.'
        ]
    ]
];

$industries = [
    [
        'id' => 'proptech',
        'tab' => 'PropTech',
        'tab_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M3 7v14M21 7v14M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16M9 9h6M9 13h6M9 17h6"/></svg>',
        'category' => 'PROPTECH (REAL ESTATE)',
        'heading' => 'Smart Solutions for Modern Real Estate',
        'description' => 'We deliver smart digital platforms that streamline property management, tenant engagement, and real-time asset performance for real estate businesses.',
        'features' => [
            ['title' => 'Property Management', 'text' => 'Streamline operations and reduce costs', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>'],
            ['title' => 'Real-time Insights', 'text' => 'Make data-driven decisions faster', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>'],
            ['title' => 'Tenant Engagement', 'text' => 'Enhance communication and satisfaction', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>'],
            ['title' => 'Secure & Scalable', 'text' => 'Enterprise-grade security for your data', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>']
        ],
        'image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80',
        'metrics' => [
            ['title' => 'Occupancy Rate', 'value' => '92%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>'],
            ['title' => 'Maintenance', 'value' => 'On Track', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>'],
            ['title' => 'Energy Usage', 'value' => '-18%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>']
        ]
    ],
    [
        'id' => 'retail',
        'tab' => 'Retail',
        'tab_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>',
        'category' => 'RETAIL & E-COMMERCE',
        'heading' => 'Next-Gen Retail & Omnichannel Systems',
        'description' => 'Technology and data systems that improve operations, customer experience, and help your retail brand scale across channels.',
        'features' => [
            ['title' => 'Inventory Automation', 'text' => 'Real-time stock tracking and order sync', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>'],
            ['title' => 'Customer Analytics', 'text' => 'Understand behavior and buy patterns', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83M22 12A10 10 0 0 0 12 2v10z"/></svg>'],
            ['title' => 'Omnichannel POS', 'text' => 'Unified sales platforms across stores', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>'],
            ['title' => 'Smart Loyalty Programs', 'text' => 'Keep customers returning with tailored offers', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>']
        ],
        'image' => 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?auto=format&fit=crop&w=800&q=80',
        'metrics' => [
            ['title' => 'Cart Abandonment', 'value' => '-24%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>'],
            ['title' => 'Conversion Rate', 'value' => '+4.8%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>'],
            ['title' => 'Order Accuracy', 'value' => '99.9%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>']
        ]
    ],
    [
        'id' => 'healthcare',
        'tab' => 'Healthcare',
        'tab_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        'category' => 'HEALTHCARE & LIFE SCIENCES',
        'heading' => 'Secure, Compliant Healthcare Technology',
        'description' => 'Secure, compliant, and reliable systems that optimize clinical workflows, protect patient data, and support digital health innovation.',
        'features' => [
            ['title' => 'HIPAA Compliance', 'text' => 'Strict data security and privacy measures', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>'],
            ['title' => 'EHR Integrations', 'text' => 'Seamless patient data access for providers', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>'],
            ['title' => 'Telehealth Solutions', 'text' => 'High-quality virtual consultation software', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>'],
            ['title' => 'Clinical Workflows', 'text' => 'Reduce administrative tasks for doctors', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>']
        ],
        'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80',
        'metrics' => [
            ['title' => 'Wait Times', 'value' => '-35%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'],
            ['title' => 'Data Accuracy', 'value' => '99.9%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>'],
            ['title' => 'Compliance', 'value' => 'Certified', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>']
        ]
    ],
    [
        'id' => 'energy',
        'tab' => 'Energy',
        'tab_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
        'category' => 'ENERGY & UTILITIES',
        'heading' => 'Digital Energy Solutions & IoT Analytics',
        'description' => 'Modern digital infrastructure, telemetry, and analytics for dependable grid operations and renewable energy transitions.',
        'features' => [
            ['title' => 'Grid Telemetry', 'text' => 'Monitor distribution networks in real-time', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>'],
            ['title' => 'Predictive Maintenance', 'text' => 'Fix grid assets before failures occur', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>'],
            ['title' => 'IoT Smart Sensors', 'text' => 'Aggregate sensor data at millisecond speeds', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 6v6l4 2"/></svg>'],
            ['title' => 'Renewable Analytics', 'text' => 'Optimize solar and wind energy output', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>']
        ],
        'image' => 'https://images.unsplash.com/photo-1466611653911-95081537e5b7?auto=format&fit=crop&w=800&q=80',
        'metrics' => [
            ['title' => 'Outage Rate', 'value' => '-40%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>'],
            ['title' => 'Resource Waste', 'value' => '-15%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>'],
            ['title' => 'Renewable Mix', 'value' => '42%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>']
        ]
    ],
    [
        'id' => 'enterprise',
        'tab' => 'Enterprise',
        'tab_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
        'category' => 'ENTERPRISE & TECH',
        'heading' => 'Scalable Foundations for Growing Enterprises',
        'description' => 'Reliable, secure, and performant systems designed to empower teams, automate processes, and scale operations smoothly.',
        'features' => [
            ['title' => 'Cloud Migration', 'text' => 'Transition infrastructure to AWS or Azure safely', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>'],
            ['title' => 'Workflow Automation', 'text' => 'Streamline repetitive daily operations', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>'],
            ['title' => 'Custom ERP Modules', 'text' => 'Oracle Fusion and custom ERP integrations', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>'],
            ['title' => 'High Availability', 'text' => '99.99% uptime configurations for core apps', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>']
        ],
        'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
        'metrics' => [
            ['title' => 'Deployment Cost', 'value' => '-30%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>'],
            ['title' => 'System Uptime', 'value' => '99.99%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'],
            ['title' => 'Load Times', 'value' => '-45%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>']
        ]
    ],
    [
        'id' => 'edtech',
        'tab' => 'EdTech',
        'tab_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>',
        'category' => 'EDTECH & LEARNING',
        'heading' => 'Advanced Digital Learning Platforms',
        'description' => 'Modern LMS integrations, curriculum delivery platforms, and expert professionals that help educational brands scale and grow.',
        'features' => [
            ['title' => 'LMS Implementations', 'text' => 'Setup, configure, and maintain Moodle & Canvas', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/></svg>'],
            ['title' => 'Interactive Learning', 'text' => 'Engage students with live quizzes and chats', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>'],
            ['title' => 'Virtual Classrooms', 'text' => 'High-bandwidth streaming & collaborative whiteboard', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>'],
            ['title' => 'Scalable Cloud Hosting', 'text' => 'Keep platforms speedy during test spikes', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg>']
        ],
        'image' => 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&w=800&q=80',
        'metrics' => [
            ['title' => 'Student Engagement', 'value' => '+55%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>'],
            ['title' => 'Grade Averages', 'value' => '+12%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>'],
            ['title' => 'Content Delivery', 'value' => '99.99%', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>']
        ]
    ]
];

$technologies = [
    'PHP', 'JavaScript', 'Swift', 'TypeScript', 'Python', 'Java', 'Ruby', 'C++',
    'React JS', 'Laravel', 'PostgreSQL', 'Oracle', 'MySQL', 'MongoDB',
    'Kubernetes', 'Docker', 'GitHub', 'Oracle Fusion ERP', 'Oracle HCM Cloud',
    'Oracle SCM Cloud', 'Oracle CX Cloud', 'Oracle OIC', 'Informatica', 'MuleSoft'
];

$technologyGroups = [
    'Web Platform' => [
        ['name' => 'PHP', 'logo' => 'https://cdn.simpleicons.org/php/777BB4'],
        ['name' => 'JavaScript', 'logo' => 'https://cdn.simpleicons.org/javascript/F7DF1E'],
        ['name' => 'Swift', 'logo' => 'https://cdn.simpleicons.org/swift/F05138'],
        ['name' => 'TypeScript', 'logo' => 'https://cdn.simpleicons.org/typescript/3178C6'],
        ['name' => 'Python', 'logo' => 'https://cdn.simpleicons.org/python/3776AB'],
        ['name' => 'Java', 'logo' => 'https://cdn.simpleicons.org/openjdk/ED8B00'],
        ['name' => 'Ruby', 'logo' => 'https://cdn.simpleicons.org/ruby/CC342D'],
        ['name' => 'C++', 'logo' => 'https://cdn.simpleicons.org/cplusplus/00599C'],
        ['name' => 'React JS', 'logo' => 'https://cdn.simpleicons.org/react/61DAFB'],
        ['name' => 'Laravel', 'logo' => 'https://cdn.simpleicons.org/laravel/FF2D20'],
    ],
    'Database' => [
        ['name' => 'PostgreSQL', 'logo' => 'https://cdn.simpleicons.org/postgresql/4169E1'],
        ['name' => 'Oracle', 'logo' => 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128'],
        ['name' => 'MySQL', 'logo' => 'https://cdn.simpleicons.org/mysql/4479A1'],
        ['name' => 'MongoDB', 'logo' => 'https://cdn.simpleicons.org/mongodb/47A248'],
    ],
    'Cloud & DevOps' => [
        ['name' => 'Kubernetes', 'logo' => 'https://cdn.simpleicons.org/kubernetes/326CE5'],
        ['name' => 'Docker', 'logo' => 'https://cdn.simpleicons.org/docker/2496ED'],
        ['name' => 'GitHub', 'logo' => 'https://cdn.simpleicons.org/github/181717'],
    ],
    'Oracle' => [
        ['name' => 'Oracle Fusion ERP', 'logo' => 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128'],
        ['name' => 'Oracle', 'logo' => 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128'],
        ['name' => 'Oracle HCM Cloud', 'logo' => 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128'],
        ['name' => 'Oracle SCM Cloud', 'logo' => 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128'],
        ['name' => 'Oracle CX Cloud', 'logo' => 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128'],
        ['name' => 'Oracle PPM Cloud', 'logo' => 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128'],
        ['name' => 'Oracle OIC', 'logo' => 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128'],
    ],
    'Integration' => [
        ['name' => 'Informatica', 'logo' => 'https://www.google.com/s2/favicons?domain=informatica.com&sz=128'],
        ['name' => 'TIBCO', 'logo' => 'https://www.google.com/s2/favicons?domain=tibco.com&sz=128'],
        ['name' => 'SnapLogic', 'logo' => 'https://www.google.com/s2/favicons?domain=snaplogic.com&sz=128'],
        ['name' => 'Zapier', 'logo' => 'https://www.google.com/s2/favicons?domain=zapier.com&sz=128'],
        ['name' => 'Boomi', 'logo' => 'https://www.google.com/s2/favicons?domain=boomi.com&sz=128'],
        ['name' => 'Workato', 'logo' => 'https://www.google.com/s2/favicons?domain=workato.com&sz=128'],
        ['name' => 'MuleSoft', 'logo' => 'https://www.google.com/s2/favicons?domain=mulesoft.com&sz=128'],
    ],
];

$partners = [
    ['name' => 'Oracle', 'logo' => 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128'],
    ['name' => 'Zoho', 'logo' => 'https://www.google.com/s2/favicons?domain=zoho.com&sz=128'],
    ['name' => 'AWS', 'logo' => 'https://www.google.com/s2/favicons?domain=aws.amazon.com&sz=128'],
    ['name' => 'Microsoft', 'logo' => 'https://www.google.com/s2/favicons?domain=microsoft.com&sz=128'],
    ['name' => 'Salesforce', 'logo' => 'https://www.google.com/s2/favicons?domain=salesforce.com&sz=128'],
    ['name' => 'ServiceNow', 'logo' => 'https://www.google.com/s2/favicons?domain=servicenow.com&sz=128'],
];

$technologyPartners = [
    ['name' => 'Zoho', 'logo' => 'https://www.google.com/s2/favicons?domain=zoho.com&sz=128'],
    ['name' => 'Microsoft', 'logo' => 'https://www.google.com/s2/favicons?domain=microsoft.com&sz=128'],
    ['name' => 'AWS', 'logo' => 'https://www.google.com/s2/favicons?domain=aws.amazon.com&sz=128'],
    ['name' => 'Oracle Fusion Cloud', 'logo' => 'https://www.google.com/s2/favicons?domain=oracle.com&sz=128'],
];

$strategicAlliances = [
    ['name' => 'Raveesh', 'logo' => 'https://dummyimage.com/180x100/ffffff/08233f&text=RAVEESH'],
    ['name' => 'DHP Properties', 'logo' => 'https://dummyimage.com/180x100/ffffff/0e7c66&text=DHP+Properties'],
];

$testimonials = [
    [
        'quote' => 'Silinex Global Services delivered exceptional results for our digital transformation journey. Their technical expertise helped us scale with confidence.',
        'name' => 'Compliance Manager',
        'company' => 'Financial Services Firm'
    ],
    [
        'quote' => 'The team provided highly skilled professionals who matched our project requirements quickly and reliably.',
        'name' => 'HR Director',
        'company' => 'Global Services Company'
    ],
    [
        'quote' => 'Their managed services approach improved system performance and gave our internal teams room to focus on core work.',
        'name' => 'Operations Head',
        'company' => 'Retail Organization'
    ],
];

$faqs = [
    'What services does Silinex Global Services provide?' => 'IT staffing, application managed services, GRC services, Oracle consulting, Zoho consulting, and digital transformation support.',
    'Which industries do you serve?' => 'Real estate, retail, healthcare, pharma, energy, fintech, manufacturing, education, and enterprise technology.',
    'Do you provide offshore and remote staffing services?' => 'Yes. Flexible hiring models include contract, permanent, contract-to-hire, remote, and offshore resource augmentation.',
    'What is Application Managed Services?' => 'AMS is a support model where enterprise applications are monitored, maintained, optimized, and improved continuously.',
    'whats the vision for next 5 years?'=>'To be a leading global provider of technology solutions and services, empowering businesses to thrive in the digital age through innovation, expertise, and exceptional customer service.'
];

$blogs = [
    ['title' => 'Leadership Skills Needed in the AI Era', 'date' => '04/05/2026'],
    ['title' => 'AI Implementation Challenges and Solutions', 'date' => '23/04/2026'],
    ['title' => 'Common Pitfalls in Digital Transformation', 'date' => '13/04/2026'],
];

$servicePages = [
    'staffing' => [
        'subtitle' => 'Flexible hiring models for dependable technology delivery.',
        'heroImage' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1400&q=80',
        'focus' => 'Build the right team faster with screened technical talent, flexible engagement models, and hiring support that aligns skills with business goals.',
        'offer' => [
            'Permanent, contract, contract-to-hire, remote, and offshore staffing support.',
            'Candidate screening for developers, cloud engineers, QA teams, ERP consultants, analysts, and support roles.',
            'Fast shortlisting, interview coordination, onboarding support, and resource replacement assistance.'
        ],
        'engagement' => ['Dedicated resource model', 'Project-based hiring', 'Remote and offshore augmentation', 'Contract-to-hire support'],
        'why' => [
            'Faster hiring cycles with targeted screening and role matching.',
            'Reduced internal recruitment load while keeping quality high.',
            'Access to specialized IT and enterprise technology professionals.'
        ]
    ],
    'application-managed-services' => [
        'subtitle' => 'Focus on growth. Leave operations to us.',
        'heroImage' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1400&q=80',
        'focus' => 'Our Application Managed Services framework enables you to focus on business outcomes while we take care of your IT environment with support, enhancements, scalability, and transparency.',
        'offer' => [
            'Proactive monitoring, incident management, performance optimization, and issue resolution.',
            'Cloud migration and optimization support for scalable, cost-efficient platforms.',
            'Enhancements, customizations, integrations, and reporting improvements aligned with business needs.'
        ],
        'engagement' => ['24/7 global support', 'SLA-driven delivery model', 'Onsite / offshore / hybrid support', 'Continuous improvement roadmap'],
        'why' => [
            'Operational stability so your applications run smoothly while your staff focuses on growth.',
            'End-to-end ownership from maintenance to integration and optimization.',
            'Continuous optimization across cloud, application, and workflow performance.'
        ]
    ],
    'grc-services' => [
        'subtitle' => 'Governance, risk, and compliance built for clarity.',
        'heroImage' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1400&q=80',
        'focus' => 'Strengthen business resilience with governance frameworks, risk visibility, control documentation, audit readiness, and practical compliance operations.',
        'offer' => [
            'Risk assessments, control mapping, compliance tracking, and governance reporting.',
            'Policy documentation, access reviews, audit support, and process standardization.',
            'Leadership dashboards that provide visibility into risks, controls, ownership, and remediation.'
        ],
        'engagement' => ['GRC advisory', 'Audit readiness support', 'Control testing assistance', 'Ongoing compliance operations'],
        'why' => [
            'Clearer accountability across risk owners, process owners, and leadership.',
            'Reduced audit friction through better documentation and control evidence.',
            'Practical compliance processes that support business speed instead of slowing it down.'
        ]
    ],
    'oracle-services' => [
        'subtitle' => 'Oracle consulting for modern enterprise operations.',
        'heroImage' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1400&q=80',
        'focus' => 'Plan, implement, integrate, and support Oracle cloud and enterprise solutions across ERP, HCM, SCM, CX, OIC, reporting, and process automation.',
        'offer' => [
            'Oracle Fusion ERP, HCM, SCM, CX, OIC, reporting, migration, and implementation support.',
            'Configuration, integration planning, data movement, testing, issue resolution, and post-go-live assistance.',
            'Workflow improvement and enterprise process alignment for better adoption and operational value.'
        ],
        'engagement' => ['Implementation consulting', 'Oracle managed support', 'Integration and reporting support', 'Post-go-live optimization'],
        'why' => [
            'Deep enterprise process understanding across Oracle business functions.',
            'Support from planning through launch, stabilization, and improvement.',
            'Better alignment between Oracle systems, integrations, users, and reporting needs.'
        ]
    ],
    'silinex-dummy-services' => [
        'subtitle' => 'Commercial IT service support for growing teams.',
        'heroImage' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1400&q=80',
        'focus' => 'A flexible commercial IT service offering for organizations that need consulting, technology support, and delivery assistance.',
        'offer' => [
            'Commercial IT service support for growing business teams.',
            'Flexible consulting and delivery assistance aligned with enterprise needs.',
            'Technology support designed to improve operations and reliability.'
        ],
        'engagement' => ['Consulting support', 'Managed assistance', 'Remote delivery', 'Business technology support'],
        'why' => [
            'Simple service model for fast-moving teams.',
            'Practical support across common IT needs.',
            'Reliable assistance for operations and delivery.'
        ]
    ],
];

require_once __DIR__ . '/database.php';

$siteData = loadSilinexSiteData([
    'services' => $services,
    'industries' => $industries,
    'technologyGroups' => $technologyGroups,
    'partners' => $partners,
    'technologyPartners' => $technologyPartners,
    'strategicAlliances' => $strategicAlliances,
    'testimonials' => $testimonials,
    'faqs' => $faqs,
    'blogs' => $blogs,
    'servicePages' => $servicePages,
]);

$services = $siteData['services'];
$industries = $siteData['industries'];
$technologyGroups = $siteData['technologyGroups'];
$partners = $siteData['partners'];
$technologyPartners = $siteData['technologyPartners'];
$strategicAlliances = $siteData['strategicAlliances'];
$testimonials = $siteData['testimonials'];
$faqs = $siteData['faqs'];
$blogs = $siteData['blogs'];
$servicePages = $siteData['servicePages'];

$requestPath = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '', '/');
if ($requestPath !== '' && $requestPath !== 'index.php') {
    if ($requestPath === 'partners') {
        $_GET['page'] = 'partners';
    } elseif (preg_match('#^service/([^/]+)$#', $requestPath, $matches)) {
        $_GET['service'] = urldecode($matches[1]);
    } else {
        foreach ($services as $service) {
            if (($service['slug'] ?? '') === $requestPath) {
                $_GET['service'] = $requestPath;
                break;
            }
        }
    }
}

$selectedService = null;
$selectedServicePage = null;
$selectedPage = $_GET['page'] ?? null;
if (isset($_GET['service'])) {
    foreach ($services as $service) {
        if ($service['slug'] === $_GET['service']) {
            $selectedService = $service;
            $selectedServicePage = $servicePages[$service['slug']] ?? null;
            break;
        }
    }
}

$serviceHeroAlignment = 'center';
$serviceOfferBlockClass = 'service-detail-block';
$serviceWhyBlockClass = 'service-detail-block reverse';
$serviceOfferImage = '';
$serviceWhyImage = 'https://images.unsplash.com/photo-1639322537228-f710d846310a?auto=format&fit=crop&w=1000&q=80';
if ($selectedService && $selectedServicePage) {
    $serviceHeroAlignment = in_array(($selectedServicePage['heroAlignment'] ?? 'center'), ['left', 'center', 'right'], true) ? $selectedServicePage['heroAlignment'] : 'center';
    $serviceOfferBlockClass = (($selectedServicePage['offerAlignment'] ?? 'image-right') === 'image-left') ? 'service-detail-block reverse' : 'service-detail-block';
    $serviceWhyBlockClass = (($selectedServicePage['whyAlignment'] ?? 'image-left') === 'image-right') ? 'service-detail-block' : 'service-detail-block reverse';
    $serviceOfferImage = $selectedServicePage['offerImage'] ?: ($selectedService['image'] ?? '');
    $serviceWhyImage = $selectedServicePage['whyImage'] ?: $serviceWhyImage;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Silinex Global Services | IT Staffing & Managed Services</title>
    <link rel="stylesheet" href="/style.css?v=20260604-nexora-float">
</head>
<body>
   <script>
  (function (w, d, src, widgetKey, apiBase) {
    w.VoiceAgent = w.VoiceAgent || {};
    w.VoiceAgent.q = w.VoiceAgent.q || [];
    w.VoiceAgent.q.push(function () {
      w.VoiceAgent.init({ widgetKey: widgetKey, apiBase: apiBase });
    });
    if (!d.querySelector('script[data-nexora-widget]')) {
      var s = d.createElement('script');
      s.src = src;
      s.async = true;
      s.dataset.nexoraWidget = 'true';
      (d.head || d.body || d.documentElement).appendChild(s);
    }
  })(window, document, "https://nexora-admin-eeog.onrender.com/widget/embed.js", "va_widget_live_oDljKf2Jriij9ucOsxX9mkd2gcrd1x3E77TBbcWN", "https://nexora-api-ngnu.onrender.com/api/v1");
</script>
    <?php if (!$selectedService && !$selectedServicePage): ?>
    <div class="site-loader" data-site-loader hidden>
        <div class="loader-mark">
            <img src="/assets/silinex-logo.jpeg" alt="Silinex Global Services">
        </div>
    </div>
    <?php endif; ?>

    <header class="primary-header">
        <a class="primary-brand" href="/#home" aria-label="Silinex Global Services home">
            <img src="/assets/silinex-logo.jpeg" alt="Silinex Global Services">
        </a>
        <nav class="primary-nav" aria-label="Main navigation">
            <a class="is-active" href="/#home">Home</a>
            <div class="nav-dropdown">
                <button type="button">Services⌄</button>
                <div class="dropdown-menu">
                    <?php foreach ($services as $service): ?>
                        <a href="/<?php echo rawurlencode($service['slug']); ?>"><?php echo htmlspecialchars($service['title']); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="nav-dropdown">
                <button type="button">Customer Success⌄</button>
                <div class="dropdown-menu">
                    <a href="/partners">Partners & Alliances</a>
                </div>
            </div>
            <div class="nav-dropdown">
                <button type="button">About⌄</button>
                <div class="dropdown-menu">
                    <a href="#about">Who We Are</a>
                    <a href="#services">Our Services</a>
                    <a href="#contact">Get In Touch</a>
                </div>
            </div>
            <a href="/#technologies">Careers</a>
            <a href="/#contact">Contact Us</a>
        </nav>
        <a class="primary-cta" href="/#contact">Get to Know ↗</a>
    </header>

    <header class="site-header">
        <a class="brand" href="/#home" aria-label="Silinex Global Services home">
            <img class="brand-logo" src="/assets/silinex-logo.jpeg" alt="Silinex Global Services">
        </a>
        <nav class="nav-links" aria-label="Primary navigation">
            <a href="/#services">Services</a>
            <a href="/#about">Who We Are</a>
            <a href="/#industries">Industries</a>
            <a href="/#technologies">Technologies</a>
            <a href="/#contact">Contact</a>
        </nav>
        <a class="header-cta" href="/#contact">Get to Know</a>
    </header>

    <?php if ($selectedService && $selectedServicePage): ?>
    <div class="service-loader" data-service-loader hidden>
        <div class="loader-mark">
            <img src="/assets/silinex-logo.jpeg" alt="Silinex Global Services">
        </div>
    </div>

    <main class="service-detail-page" data-service-page>
        <section class="service-detail-hero align-<?php echo htmlspecialchars($serviceHeroAlignment); ?>">
            <p class="eyebrow">Service Details</p>
            <h1><?php echo htmlspecialchars($selectedService['title']); ?></h1>
            <p><?php echo htmlspecialchars($selectedServicePage['subtitle']); ?></p>
        </section>

        <section class="service-detail-content">
            <img class="service-detail-hero-image" src="<?php echo htmlspecialchars($selectedServicePage['heroImage']); ?>" alt="<?php echo htmlspecialchars($selectedService['title']); ?>">

            <article class="service-detail-intro">
                <h2><?php echo htmlspecialchars($selectedService['title']); ?></h2>
                <p><strong><?php echo htmlspecialchars($selectedServicePage['focus']); ?></strong></p>
            </article>

            <section class="<?php echo htmlspecialchars($serviceOfferBlockClass); ?>">
                <div>
                    <p class="eyebrow">What We Offer</p>
                    <h2>Our Capabilities</h2>
                    <ul class="detail-list">
                        <?php foreach ($selectedServicePage['offer'] as $item): ?>
                            <li><?php echo htmlspecialchars($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <h3>Engagement Models</h3>
                    <ul class="detail-list compact">
                        <?php foreach ($selectedServicePage['engagement'] as $item): ?>
                            <li><?php echo htmlspecialchars($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <img src="<?php echo htmlspecialchars($serviceOfferImage); ?>" alt="<?php echo htmlspecialchars($selectedService['title']); ?> capability visual">
            </section>

            <section class="<?php echo htmlspecialchars($serviceWhyBlockClass); ?>">
                <img src="<?php echo htmlspecialchars($serviceWhyImage); ?>" alt="Why Silinex technology visual">
                <div>
                    <p class="eyebrow">Why Choose Silinex</p>
                    <h2>Built for Stability, Speed, and Support</h2>
                    <ul class="detail-list">
                        <?php foreach ($selectedServicePage['why'] as $item): ?>
                            <li><?php echo htmlspecialchars($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a class="button primary" href="/#contact">Talk to Us</a>
                </div>
            </section>
        </section>
    </main>
    <?php elseif ($selectedPage === 'partners'): ?>
    <main class="customer-success-page">
        <section class="customer-hero">
            <p class="eyebrow">Partners & Alliances</p>
            <h1>Customer Success</h1>
            <p>We help organizations redefine possibilities through intelligent technology and people-centric transformation. Here is how Silinex is driving measurable success across industries.</p>
        </section>

        <section class="success-partners-section">
            <div class="section-heading centered-heading">
                <h2>Technology Partners</h2>
                <p>We partner with trusted technology platforms to deliver scalable and secure solutions for our clients.</p>
            </div>
            <div class="success-logo-grid">
                <?php foreach ($technologyPartners as $partner): ?>
                    <article>
                        <img src="<?php echo htmlspecialchars($partner['logo']); ?>" alt="<?php echo htmlspecialchars($partner['name']); ?> logo">
                        <span><?php echo htmlspecialchars($partner['name']); ?></span>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="success-partners-section alliance-section">
            <div class="section-heading centered-heading">
                <h2>Strategic Alliances</h2>
                <p>Together with our partners, we deliver smarter, faster, and scalable technology solutions.</p>
            </div>
            <div class="success-logo-grid compact">
                <?php foreach ($strategicAlliances as $partner): ?>
                    <article>
                        <img src="<?php echo htmlspecialchars($partner['logo']); ?>" alt="<?php echo htmlspecialchars($partner['name']); ?> logo">
                        <span><?php echo htmlspecialchars($partner['name']); ?></span>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="success-network">
            <div>
                <p class="eyebrow">Collaborate With Us</p>
                <h2>Join Our Success Network</h2>
                <p>Ready to become a Silinex success story? Let us collaborate to unlock new levels of efficiency, innovation, and global growth.</p>
                <div class="hero-actions">
                    <a class="button primary" href="/#contact">Partner With Us</a>
                    <a class="button secondary" href="/application-managed-services">View Services</a>
                </div>
            </div>
        </section>
    </main>
    <?php else: ?>

    <main id="home">
        <section class="hero">
            <div class="hero-copy">
                <p class="eyebrow">Welcome to Silinex Global Services</p>
                <h1>Empowering Digital Growth with Intelligent IT and Staffing Solutions</h1>
                <p class="hero-text">World-class IT consulting, staffing, and technology solutions powered by innovation, trust, and over 100 years of combined industry leadership.</p>
                <div class="hero-actions">
                    <button class="button primary" type="button" data-open-services>Explore Services</button>
                    <a class="button secondary" href="#about">Learn More</a>
                </div>
            </div>
            <div class="hero-visual" aria-label="Digital growth illustration">
                <div class="orbit orbit-one"></div>
                <div class="orbit orbit-two"></div>
                <div class="metric-card metric-main">
                    <span>100+</span>
                    <small>Years combined leadership</small>
                </div>
                <div class="metric-card metric-top">
                    <span>24/7</span>
                    <small>Managed support</small>
                </div>
                <div class="metric-card metric-bottom">
                    <span>Global</span>
                    <small>Talent network</small>
                </div>
            </div>
        </section>

        <section class="partners section-band" aria-labelledby="partners-title">
            <p class="eyebrow" id="partners-title">Featured Partners</p>
            <div class="partner-strip">
                <div class="partner-track">
                    <?php for ($loop = 0; $loop < 2; $loop++): ?>
                        <?php foreach ($partners as $partner): ?>
                            <span class="partner-card">
                                <img src="<?php echo htmlspecialchars($partner['logo']); ?>" alt="<?php echo htmlspecialchars($partner['name']); ?> logo">
                                <strong><?php echo htmlspecialchars($partner['name']); ?></strong>
                            </span>
                        <?php endforeach; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </section>

        <section class="section" id="services">
            <div class="section-heading">
                <p class="eyebrow">Our Services</p>
                <h2>Empowering Businesses Through Technology and Talent</h2>
                <p>Comprehensive IT consulting and staffing solutions designed to help enterprises grow faster, operate smarter, and innovate continuously.</p>
            </div>
            <div class="service-grid">
                <?php foreach (array_slice($services, 0, 3) as $service): ?>
                    <article class="service-card">
                        <img class="service-image" src="<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>">
                        <div class="service-content">
                            <span class="service-number"><?php echo htmlspecialchars($service['icon']); ?></span>
                            <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                            <p><?php echo htmlspecialchars($service['text']); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="about section-band" id="about">
            <div class="about-media">
                <span class="stat">45+</span>
                <p>Projects shaped by focused technology delivery</p>
            </div>
            <div class="about-copy">
                <p class="eyebrow">Who We Are</p>
                <h2>Empowering Growth Through Technology and Talent</h2>
                <p>Silinex is founded on decades of expertise in IT, staffing, and digital transformation. The leadership team brings proven success across energy, healthcare, technology, and enterprise sectors.</p>
                <div class="mini-stats">
                    <span><strong>3x</strong> Faster hiring cycles</span>
                    <span><strong>6</strong> Core industries</span>
                    <span><strong>4</strong> Service pillars</span>
                </div>
            </div>
        </section>

        <section class="section industries-section" id="industries">
            <div class="section-heading centered-heading">
                <p class="eyebrow">Powering Progress Across Industries</p>
                <h2>Our <span>Industries</span></h2>
            </div>
            <div class="industries-tabs-container">
                <div class="industries-tab-list" role="tablist" aria-label="Industries">
                    <?php foreach ($industries as $index => $industry): ?>
                        <button type="button" role="tab" aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-controls="panel-<?php echo $industry['id']; ?>" id="tab-<?php echo $industry['id']; ?>" data-industry-tab="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'is-active' : ''; ?>">
                            <?php echo $industry['tab_icon']; ?>
                            <?php echo htmlspecialchars($industry['tab']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="industries-tab-panels">
                    <?php foreach ($industries as $index => $industry): ?>
                        <div id="panel-<?php echo $industry['id']; ?>" role="tabpanel" aria-labelledby="tab-<?php echo $industry['id']; ?>" data-industry-panel="<?php echo $index; ?>" class="industry-panel <?php echo $index === 0 ? 'is-active' : ''; ?>" <?php echo $index !== 0 ? 'hidden' : ''; ?>>
                            
                            <div class="industry-panel-content">
                                <p class="industry-category"><?php echo htmlspecialchars($industry['category']); ?></p>
                                <h3><?php echo htmlspecialchars($industry['heading']); ?></h3>
                                <p class="industry-description"><?php echo htmlspecialchars($industry['description']); ?></p>
                                
                                <div class="industry-features">
                                    <?php foreach ($industry['features'] as $feature): ?>
                                        <div class="industry-feature">
                                            <div class="feature-icon"><?php echo $feature['icon']; ?></div>
                                            <div class="feature-text">
                                                <h4><?php echo htmlspecialchars($feature['title']); ?></h4>
                                                <p><?php echo htmlspecialchars($feature['text']); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <a href="/#contact" class="button primary">Learn More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px; margin-left: 8px;"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
                            </div>
                            
                            <div class="industry-panel-visual">
                                <img src="<?php echo htmlspecialchars($industry['image']); ?>" alt="<?php echo htmlspecialchars($industry['heading']); ?>">
                                <?php if (isset($industry['metrics'])): ?>
                                    <?php foreach ($industry['metrics'] as $metricIndex => $metric): ?>
                                        <div class="metric-float metric-<?php echo $metricIndex + 1; ?>">
                                            <div class="metric-icon"><?php echo $metric['icon']; ?></div>
                                            <div>
                                                <span class="metric-title"><?php echo htmlspecialchars($metric['title']); ?></span>
                                                <span class="metric-value"><?php echo htmlspecialchars($metric['value']); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="section technology-section" id="technologies">
            <div class="section-heading centered-heading">
                <p class="eyebrow">Our Technologies</p>
                <h2>We Use <span>Technologies</span></h2>
            </div>
            <div class="technology-tabs" aria-label="Technology categories">
                <?php $groupIndex = 0; ?>
                <?php foreach ($technologyGroups as $groupName => $items): ?>
                    <button class="<?php echo $groupIndex === 0 ? 'is-active' : ''; ?>" type="button" data-tech-tab="<?php echo htmlspecialchars($groupIndex); ?>"><?php echo htmlspecialchars($groupName); ?></button>
                    <?php $groupIndex++; ?>
                <?php endforeach; ?>
            </div>
            <div class="technology-grid">
                <?php $groupIndex = 0; ?>
                <?php foreach ($technologyGroups as $items): ?>
                    <div class="technology-group <?php echo $groupIndex === 0 ? 'is-active' : ''; ?>" data-tech-panel="<?php echo htmlspecialchars($groupIndex); ?>">
                    <?php foreach ($items as $tech): ?>
                        <article class="technology-item">
                            <img src="<?php echo htmlspecialchars($tech['logo']); ?>" alt="<?php echo htmlspecialchars($tech['name']); ?> logo">
                            <span><?php echo htmlspecialchars($tech['name']); ?></span>
                        </article>
                    <?php endforeach; ?>
                    </div>
                    <?php $groupIndex++; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section-band testimonials">
            <div class="section-heading">
                <p class="eyebrow">Client Testimonials</p>
                <h2>Built on Delivery, Trust, and Momentum</h2>
            </div>
            <div class="testimonial-grid">
                <?php foreach ($testimonials as $testimonial): ?>
                    <figure>
                        <blockquote><?php echo htmlspecialchars($testimonial['quote']); ?></blockquote>
                        <figcaption>
                            <strong><?php echo htmlspecialchars($testimonial['name']); ?></strong>
                            <span><?php echo htmlspecialchars($testimonial['company']); ?></span>
                        </figcaption>
                    </figure>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section faq">
            <div class="section-heading">
                <p class="eyebrow">F.A.Q.</p>
                <h2>Need Support?</h2>
            </div>
            <div class="faq-list">
                <?php foreach ($faqs as $question => $answer): ?>
                    <details>
                        <summary><?php echo htmlspecialchars($question); ?></summary>
                        <p><?php echo htmlspecialchars($answer); ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section blogs">
            <div class="section-heading">
                <p class="eyebrow">Our Blogs</p>
                <h2>Latest Insights</h2>
            </div>
            <div class="blog-grid">
                <?php foreach ($blogs as $blog): ?>
                    <article>
                        <span>By Admin</span>
                        <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                        <time><?php echo htmlspecialchars($blog['date']); ?></time>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="contact-panel" id="contact">
            <div>
                <p class="eyebrow">Ready to Start?</p>
                <h2>Build your next technology team or managed service plan.</h2>
            </div>
            <div class="contact-actions">
                <a class="button primary whatsapp-link" href="https://wa.me/916281484150" target="_blank" rel="noopener">
                    <svg viewBox="0 0 32 32" aria-hidden="true">
                        <path d="M16.03 3.5A12.36 12.36 0 0 0 5.5 22.36L4 28.5l6.3-1.47A12.35 12.35 0 1 0 16.03 3.5Zm0 22.63a10.14 10.14 0 0 1-5.17-1.42l-.37-.22-3.74.87.9-3.62-.24-.38a10.12 10.12 0 1 1 8.62 4.77Zm5.57-7.58c-.3-.15-1.8-.89-2.08-.99-.28-.1-.48-.15-.68.15-.2.3-.78.99-.96 1.19-.18.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.49-.9-.8-1.5-1.78-1.67-2.08-.18-.3-.02-.47.13-.62.14-.14.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.37-.03-.52-.07-.15-.68-1.65-.93-2.25-.24-.58-.49-.5-.68-.51h-.58c-.2 0-.52.07-.8.37-.28.3-1.05 1.02-1.05 2.5 0 1.47 1.08 2.9 1.23 3.1.15.2 2.12 3.24 5.14 4.54.72.31 1.28.5 1.72.64.72.23 1.38.2 1.9.12.58-.09 1.8-.73 2.05-1.44.25-.7.25-1.31.18-1.44-.08-.13-.28-.2-.58-.35Z"/>
                    </svg>
                    WhatsApp Us
                </a>
                <a class="button secondary" href="mailto:info@silinexglobal.com">Email Us</a>
            </div>
        </section>
    </main>

    <div class="services-modal" data-services-modal aria-hidden="true">
        <div class="modal-backdrop" data-close-services></div>
        <section class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="services-modal-title">
            <div class="modal-header">
                <div>
                    <p class="eyebrow">Our Services</p>
                    <h2 id="services-modal-title">Explore All Services</h2>
                </div>
                <button class="modal-close" type="button" data-close-services aria-label="Close services popup">×</button>
            </div>
            <section class="modal-details" aria-labelledby="modal-details-title">
                <div class="modal-details-heading">
                    <p class="eyebrow">Service Details</p>
                    <h3 id="modal-details-title">Choose a service to read more</h3>
                </div>
                <div class="modal-detail-links">
                    <?php foreach ($services as $service): ?>
                        <a href="/<?php echo rawurlencode($service['slug']); ?>"><?php echo htmlspecialchars($service['title']); ?></a>
                    <?php endforeach; ?>
                </div>
                <div class="modal-detail-list">
                    <?php foreach ($services as $service): ?>
                        <article id="modal-<?php echo htmlspecialchars($service['slug']); ?>" class="modal-detail-card">
                            <span><?php echo htmlspecialchars($service['icon']); ?></span>
                            <div>
                                <h4><?php echo htmlspecialchars($service['title']); ?></h4>
                                <p><?php echo htmlspecialchars($service['text']); ?></p>
                                <ul>
                                    <?php foreach ($service['details'] as $detail): ?>
                                        <li><?php echo htmlspecialchars($detail); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <footer class="modal-footer">
                <div class="modal-footer-brand">
                    <img src="/assets/silinex-logo.jpeg" alt="Silinex Global Services">
                    <p>Technology, staffing, Oracle, and compliance solutions for teams ready to scale.</p>
                </div>
                <div class="modal-footer-services">
                    <?php foreach ($services as $service): ?>
                        <span><?php echo htmlspecialchars($service['title']); ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="modal-footer-contact">
                <a href="mailto:info@silinexglobal.com">info@silinexglobal.com</a>
                <a href="https://wa.me/916281484150" target="_blank" rel="noopener">WhatsApp: +91 62814 84150</a>
                </div>
            </footer>
        </section>
    </div>
    <?php endif; ?>

    <footer class="site-footer">
        <div class="footer-service-strip" aria-label="Featured services">
            <?php foreach ($services as $service): ?>
                <a href="/<?php echo rawurlencode($service['slug']); ?>">
                    <span>✓</span>
                    <?php echo htmlspecialchars($service['title']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="footer-main">
            <div class="footer-about">
                <a class="footer-brand" href="/#home">
                    <img class="footer-logo" src="/assets/silinex-logo.jpeg" alt="Silinex Global Services">
                </a>
                <p>Silinex Global Services is a next-generation IT consulting and staffing company delivering technology, Oracle, and compliance solutions for growing businesses.</p>
            </div>
            <div class="footer-column">
                <h3>Quick Links</h3>
                <a href="/#home">Home</a>
                <a href="/#services">Services</a>
                <a href="/#about">About Us</a>
                <a href="/#industries">Industries</a>
                <a href="/#technologies">Technologies</a>
                <a href="/#contact">Contact Us</a>
            </div>
            <div class="footer-column">
                <h3>Our Services</h3>
                <?php foreach ($services as $service): ?>
                    <a href="/<?php echo rawurlencode($service['slug']); ?>"><?php echo htmlspecialchars($service['title']); ?></a>
                <?php endforeach; ?>
            </div>
            <div class="footer-contact">
                <h3>Get In Touch</h3>
                <div class="contact-method">
                    <span class="contact-icon">✉</span>
                    <div>
                        <small>Email Us</small>
                        <a href="mailto:info@silinexglobal.com">info@silinexglobal.com</a>
                    </div>
                </div>
                <div class="contact-method">
                    <span class="contact-icon">☎</span>
                    <div>
                        <small>WhatsApp</small>
                        <a href="https://wa.me/916281484150" target="_blank" rel="noopener">+91 62814 84150</a>
                    </div>
                </div>
                <div class="social-links" aria-label="Social media links">
                    <a href="#" aria-label="Facebook">f</a>
                    <a href="#" aria-label="Twitter">t</a>
                    <a href="#" aria-label="LinkedIn">in</a>
                    <a href="#" aria-label="YouTube">▶</a>
                    <a href="#" aria-label="Instagram">◎</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Copyright © 2026 Silinex Global Services. All rights reserved.</p>
            <a href="/#home">Back to top</a>
        </div>
    </footer>
    <button class="back-to-top" type="button" data-back-to-top aria-label="Back to top">↑</button>
    <a class="whatsapp-float" href="https://wa.me/916281484150" target="_blank" rel="noopener" aria-label="Chat with Silinex on WhatsApp">
        <svg viewBox="0 0 32 32" aria-hidden="true">
            <path d="M16.03 3.5A12.36 12.36 0 0 0 5.5 22.36L4 28.5l6.3-1.47A12.35 12.35 0 1 0 16.03 3.5Zm0 22.63a10.14 10.14 0 0 1-5.17-1.42l-.37-.22-3.74.87.9-3.62-.24-.38a10.12 10.12 0 1 1 8.62 4.77Zm5.57-7.58c-.3-.15-1.8-.89-2.08-.99-.28-.1-.48-.15-.68.15-.2.3-.78.99-.96 1.19-.18.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.49-.9-.8-1.5-1.78-1.67-2.08-.18-.3-.02-.47.13-.62.14-.14.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.37-.03-.52-.07-.15-.68-1.65-.93-2.25-.24-.58-.49-.5-.68-.51h-.58c-.2 0-.52.07-.8.37-.28.3-1.05 1.02-1.05 2.5 0 1.47 1.08 2.9 1.23 3.1.15.2 2.12 3.24 5.14 4.54.72.31 1.28.5 1.72.64.72.23 1.38.2 1.9.12.58-.09 1.8-.73 2.05-1.44.25-.7.25-1.31.18-1.44-.08-.13-.28-.2-.58-.35Z"/>
        </svg>
    </a>
    <button class="nexora-float" type="button" data-nexora-open aria-label="Open Silinex assistant">
        <span class="nexora-float-ring" aria-hidden="true"></span>
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 4.25c-4.28 0-7.75 3.08-7.75 6.88 0 2.2 1.16 4.15 2.98 5.41l-.56 2.72 3.12-1.56c.7.2 1.44.31 2.21.31 4.28 0 7.75-3.08 7.75-6.88S16.28 4.25 12 4.25Zm0 1.7c3.34 0 6.05 2.32 6.05 5.18S15.34 16.31 12 16.31c-.72 0-1.41-.11-2.04-.34l-.34-.12-1.03.51.18-.9-.5-.33c-1.45-.96-2.32-2.45-2.32-4 0-2.86 2.71-5.18 6.05-5.18Z"/>
        </svg>
    </button>
    <script>
        const servicesModal = document.querySelector('[data-services-modal]');
        const openServicesButton = document.querySelector('[data-open-services]');
        const closeServicesButtons = document.querySelectorAll('[data-close-services]');

        function openServicesModal() {
            servicesModal.classList.add('is-open');
            servicesModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
        }

        function closeServicesModal() {
            servicesModal.classList.remove('is-open');
            servicesModal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
            openServicesButton.focus();
        }

        if (openServicesButton && servicesModal) {
            openServicesButton.addEventListener('click', openServicesModal);
            closeServicesButtons.forEach((button) => button.addEventListener('click', closeServicesModal));
        }

        document.addEventListener('keydown', (event) => {
            if (servicesModal && event.key === 'Escape' && servicesModal.classList.contains('is-open')) {
                closeServicesModal();
            }
        });

        const backToTopButton = document.querySelector('[data-back-to-top]');
        if (backToTopButton) {
            backToTopButton.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        const nexoraButton = document.querySelector('[data-nexora-open]');
        if (nexoraButton) {
            nexoraButton.addEventListener('click', () => {
                const voiceAgent = window.VoiceAgent;

                if (voiceAgent && typeof voiceAgent.open === 'function') {
                    voiceAgent.open();
                    return;
                }

                if (voiceAgent && typeof voiceAgent.toggle === 'function') {
                    voiceAgent.toggle();
                    return;
                }

                if (voiceAgent && Array.isArray(voiceAgent.q)) {
                    voiceAgent.q.push(() => {
                        if (typeof window.VoiceAgent.open === 'function') {
                            window.VoiceAgent.open();
                        } else if (typeof window.VoiceAgent.toggle === 'function') {
                            window.VoiceAgent.toggle();
                        }
                    });
                }

                nexoraButton.classList.add('is-loading');
                window.setTimeout(() => nexoraButton.classList.remove('is-loading'), 1200);
            });
        }

        const industryTabs = document.querySelectorAll('[data-industry-tab]');
        const industryPanels = document.querySelectorAll('[data-industry-panel]');

        if (industryTabs.length && industryPanels.length) {
            industryTabs.forEach((tabButton) => {
                tabButton.addEventListener('click', () => {
                    const targetIndex = tabButton.getAttribute('data-industry-tab');

                    industryTabs.forEach((button) => {
                        button.classList.remove('is-active');
                        button.setAttribute('aria-selected', 'false');
                    });
                    industryPanels.forEach((panel) => {
                        panel.classList.remove('is-active');
                        panel.hidden = true;
                    });

                    tabButton.classList.add('is-active');
                    tabButton.setAttribute('aria-selected', 'true');
                    
                    const activePanel = document.querySelector(`[data-industry-panel="${targetIndex}"]`);
                    if (activePanel) {
                        activePanel.classList.add('is-active');
                        activePanel.hidden = false;
                    }
                });
            });
        }

        const techTabs = document.querySelectorAll('[data-tech-tab]');
        const techPanels = document.querySelectorAll('[data-tech-panel]');

        if (techTabs.length && techPanels.length) {
            techTabs.forEach((tabButton) => {
                tabButton.addEventListener('click', () => {
                    const targetPanel = tabButton.getAttribute('data-tech-tab');

                    techTabs.forEach((button) => button.classList.remove('is-active'));
                    techPanels.forEach((panel) => panel.classList.remove('is-active'));

                    tabButton.classList.add('is-active');
                    document.querySelector(`[data-tech-panel="${targetPanel}"]`).classList.add('is-active');
                });
            });
        }

        const serviceLoader = document.querySelector('[data-service-loader]');
        const servicePage = document.querySelector('[data-service-page]');
        const siteLoader = document.querySelector('[data-site-loader]');
        const shouldSkipLoader = sessionStorage.getItem('skipLoader') === 'true';

        if (shouldSkipLoader) {
            sessionStorage.removeItem('skipLoader');
        }

        document.querySelectorAll('a[href^="/#"], a[href^="#"]').forEach((link) => {
            link.addEventListener('click', () => {
                sessionStorage.setItem('skipLoader', 'true');
            });
        });

        if (serviceLoader && servicePage) {
            document.body.classList.add('service-is-loading');
            serviceLoader.hidden = false;

            if (shouldSkipLoader) {
                serviceLoader.classList.add('is-hidden');
                servicePage.classList.add('is-ready');
                document.body.classList.remove('service-is-loading');
            } else {
                window.setTimeout(() => {
                    serviceLoader.classList.add('is-hidden');
                    servicePage.classList.add('is-ready');
                    document.body.classList.remove('service-is-loading');
                }, 900);
            }
        }

        if (siteLoader) {
            if (shouldSkipLoader) {
                siteLoader.classList.add('is-hidden');
            } else {
                document.body.classList.add('service-is-loading');
                siteLoader.hidden = false;
                window.setTimeout(() => {
                    siteLoader.classList.add('is-hidden');
                    document.body.classList.remove('service-is-loading');
                }, 900);
            }
        }

        const siteHeader = document.querySelector('.site-header');

        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;

            if (currentScrollY > 120) {
                siteHeader.classList.add('is-visible');
            } else {
                siteHeader.classList.remove('is-visible');
            }
        }, { passive: true });
    </script>
</body>
</html>
