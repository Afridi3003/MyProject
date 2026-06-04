<?php
session_start();
require_once __DIR__ . '/database.php';

define('ADMIN_USERNAME', getenv('ADMIN_USERNAME') ?: 'Afridi_03');
define('ADMIN_PASSWORD', getenv('ADMIN_PASSWORD') ?: '');

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: /admin');
    exit;
}

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['admin_login'] ?? '') === '1') {
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if (hash_equals(ADMIN_USERNAME, $username) && hash_equals(ADMIN_PASSWORD, $password)) {
        $_SESSION['silinex_admin_authenticated'] = true;
        $_SESSION['silinex_admin_user'] = ADMIN_USERNAME;
        header('Location: /admin');
        exit;
    }

    $_SESSION['silinex_admin_login_error'] = 'Invalid username or password.';
    header('Location: /admin');
    exit;
}

if (empty($_SESSION['silinex_admin_authenticated'])) {
    if (!empty($_SESSION['silinex_admin_login_error'])) {
        $loginError = $_SESSION['silinex_admin_login_error'];
        unset($_SESSION['silinex_admin_login_error']);
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Silinex Admin Login</title>
        <link rel="stylesheet" href="/admin.css?v=20260525-search">
    </head>
    <body class="login-body">
        <main class="login-shell">
            <section class="login-panel" aria-labelledby="admin-login-title">
                <div class="login-brand">
                    <img src="/assets/silinex-logo.jpeg" alt="Silinex Global Services">
                    <p>Secure CMS Access</p>
                    <h1 id="admin-login-title">Admin Login</h1>
                </div>
                <?php if ($loginError): ?>
                    <div class="login-error"><?php echo htmlspecialchars($loginError); ?></div>
                <?php endif; ?>
                <form method="post" class="login-form">
                    <input type="hidden" name="admin_login" value="1">
                    <label>
                        Username
                        <input type="text" name="username" autocomplete="username" required autofocus>
                    </label>
                    <label>
                        Password
                        <input type="password" name="password" autocomplete="current-password" required>
                    </label>
                    <button type="submit">Login Securely</button>
                </form>
            </section>
        </main>
    </body>
    </html>
    <?php
    exit;
}

$pdo = silinexDb();

function adminQuery(string $sql, array $params = []): array
{
    global $pdo;
    if (!$pdo) {
        return [];
    }

    $statement = $pdo->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
}

function adminExecute(string $sql, array $params = []): bool
{
    global $pdo;
    if (!$pdo) {
        return false;
    }

    $statement = $pdo->prepare($sql);
    return $statement->execute($params);
}

function adminTryExecute(string $sql, array $params = []): bool
{
    try {
        return adminExecute($sql, $params);
    } catch (Throwable $error) {
        return false;
    }
}

function adminColumnExists(string $table, string $column): bool
{
    if (DB_DRIVER === 'pgsql') {
        $rows = adminQuery(
            'SELECT column_name FROM information_schema.columns WHERE table_schema = ? AND table_name = ? AND column_name = ? LIMIT 1',
            ['public', $table, $column]
        );
    } else {
        $rows = adminQuery(
            'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? LIMIT 1',
            [DB_NAME, $table, $column]
        );
    }

    return !empty($rows);
}

function adminTextLines(string $text): array
{
    $lines = preg_split('/\R+/', trim($text));
    $lines = array_map('trim', $lines ?: []);
    return array_values(array_filter($lines, static fn(string $line): bool => $line !== ''));
}

function adminJsonLines(string $text): string
{
    return json_encode(adminTextLines($text), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

function adminJsonTextarea(?string $json): string
{
    $decoded = json_decode((string)$json, true);
    return is_array($decoded) ? implode("\n", array_map('strval', $decoded)) : '';
}

function adminEnsureVersionTable(): void
{
    if (DB_DRIVER === 'pgsql') {
        adminTryExecute("CREATE TABLE IF NOT EXISTS cms_versions (
            id SERIAL PRIMARY KEY,
            section_key VARCHAR(80) NOT NULL,
            action_type VARCHAR(40) NOT NULL,
            record_id INTEGER NOT NULL DEFAULT 0,
            record_label VARCHAR(220) NOT NULL DEFAULT '',
            snapshot_json JSONB NOT NULL DEFAULT '{}'::jsonb,
            admin_user VARCHAR(120) NOT NULL DEFAULT '',
            created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
        )");
        adminTryExecute('CREATE INDEX IF NOT EXISTS idx_cms_versions_section ON cms_versions (section_key)');
        return;
    }

    adminTryExecute("CREATE TABLE IF NOT EXISTS cms_versions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        section_key VARCHAR(80) NOT NULL,
        action_type VARCHAR(40) NOT NULL,
        record_id INT NOT NULL DEFAULT 0,
        record_label VARCHAR(220) NOT NULL DEFAULT '',
        snapshot_json JSON NOT NULL,
        admin_user VARCHAR(120) NOT NULL DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (section_key)
    )");
}

function adminLogVersion(string $sectionKey, string $actionType, int $recordId, string $recordLabel, array $snapshot): void
{
    $snapshotJson = json_encode($snapshot, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $adminUser = $_SESSION['silinex_admin_user'] ?? ADMIN_USERNAME;

    try {
        if (DB_DRIVER === 'pgsql') {
            adminExecute(
                'INSERT INTO cms_versions (section_key, action_type, record_id, record_label, snapshot_json, admin_user) VALUES (?, ?, ?, ?, ?::jsonb, ?)',
                [$sectionKey, $actionType, $recordId, $recordLabel, $snapshotJson, $adminUser]
            );
            return;
        }

        adminExecute(
            'INSERT INTO cms_versions (section_key, action_type, record_id, record_label, snapshot_json, admin_user) VALUES (?, ?, ?, ?, ?, ?)',
            [$sectionKey, $actionType, $recordId, $recordLabel, $snapshotJson, $adminUser]
        );
    } catch (Throwable $error) {
        // Version logging must never block the main CMS save flow.
    }
}

function adminLastRecordId(string $table): int
{
    global $pdo;
    if (!$pdo) {
        return 0;
    }

    try {
        if (DB_DRIVER === 'pgsql') {
            return (int)$pdo->query("SELECT currval(pg_get_serial_sequence('$table', 'id'))")->fetchColumn();
        }

        return (int)$pdo->lastInsertId();
    } catch (Throwable $error) {
        return 0;
    }
}

function adminVersionCleanValue(mixed $value): mixed
{
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        return $value;
    }

    return $value;
}

function adminVersionChangedFields(array $before, array $after): array
{
    $changed = [];
    $keys = array_unique(array_merge(array_keys($before), array_keys($after)));

    foreach ($keys as $key) {
        $beforeValue = adminVersionCleanValue($before[$key] ?? null);
        $afterValue = adminVersionCleanValue($after[$key] ?? null);
        if ($beforeValue !== $afterValue) {
            $changed[] = $key;
        }
    }

    return $changed;
}

function adminVersionChangeLevel(array $changedFields): string
{
    $majorFields = ['title', 'heading', 'description', 'short_content', 'content', 'focus', 'offer_json', 'engagement_json', 'why_json', 'image', 'hero_image', 'offer_image', 'why_image'];
    foreach ($changedFields as $field) {
        if (in_array($field, $majorFields, true) || str_contains((string)$field, 'content') || str_contains((string)$field, 'image')) {
            return 'major';
        }
    }

    return count($changedFields) > 2 ? 'major' : 'minor';
}

function adminVersionSnapshot(string $actionType, array $before, array $after): array
{
    $changedFields = $actionType === 'updated' ? adminVersionChangedFields($before, $after) : [];

    if ($actionType === 'updated') {
        return [
            'change_level' => adminVersionChangeLevel($changedFields),
            'changed_fields' => $changedFields,
            'before_change' => $before,
            'after_change' => $after,
        ];
    }

    if ($actionType === 'deleted') {
        return [
            'change_level' => 'major',
            'deleted_content' => $before,
        ];
    }

    return [
        'change_level' => 'major',
        'created_content' => $after,
    ];
}

function adminHumanLabel(string $key): string
{
    return ucwords(str_replace('_', ' ', str_replace('-', ' ', $key)));
}

function adminRenderVersionFields(array $data, int $depth = 0): string
{
    if (!$data) {
        return '<p class="version-empty-content">No content data stored.</p>';
    }

    $html = '<dl class="version-content-list depth-' . $depth . '">';
    foreach ($data as $key => $value) {
        $value = adminVersionCleanValue($value);
        if ($value === null || $value === '') {
            continue;
        }

        $html .= '<dt>' . htmlspecialchars(adminHumanLabel((string)$key)) . '</dt><dd>';
        if (is_array($value)) {
            if (array_is_list($value)) {
                $html .= '<ul>';
                foreach ($value as $item) {
                    $html .= '<li>' . (is_array($item) ? adminRenderVersionFields($item, $depth + 1) : htmlspecialchars((string)$item)) . '</li>';
                }
                $html .= '</ul>';
            } else {
                $html .= adminRenderVersionFields($value, $depth + 1);
            }
        } else {
            $html .= nl2br(htmlspecialchars((string)$value));
        }
        $html .= '</dd>';
    }
    $html .= '</dl>';

    return $html;
}

function adminRenderVersionSnapshot(array $snapshot): string
{
    $html = '<div class="version-content-view">';

    if (!empty($snapshot['changed_fields']) && is_array($snapshot['changed_fields'])) {
        $html .= '<p class="version-changed-fields">Changed: ' . htmlspecialchars(implode(', ', array_map('adminHumanLabel', $snapshot['changed_fields']))) . '</p>';
    }

    if (isset($snapshot['before_change']) || isset($snapshot['after_change'])) {
        $html .= '<div class="version-compare-grid">';
        $html .= '<section><h4>Before changes</h4>' . adminRenderVersionFields((array)($snapshot['before_change'] ?? [])) . '</section>';
        $html .= '<section><h4>After changes</h4>' . adminRenderVersionFields((array)($snapshot['after_change'] ?? [])) . '</section>';
        $html .= '</div>';
    } elseif (isset($snapshot['deleted_content'])) {
        $html .= '<section><h4>Deleted content</h4>' . adminRenderVersionFields((array)$snapshot['deleted_content']) . '</section>';
    } elseif (isset($snapshot['created_content'])) {
        $html .= '<section><h4>Created content</h4>' . adminRenderVersionFields((array)$snapshot['created_content']) . '</section>';
    } else {
        $html .= '<section><h4>Stored content</h4>' . adminRenderVersionFields($snapshot) . '</section>';
    }

    $html .= '</div>';
    return $html;
}

function adminVersionRedirect(string $query = ''): void
{
    header('Location: /admin?section=versions' . ($query ? '&' . $query : ''));
    exit;
}

function adminUploadedPath(string $field, string $existing = ''): string
{
    if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return $existing;
    }

    if (($_FILES[$field]['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return $existing;
    }

    $originalName = (string)($_FILES[$field]['name'] ?? '');
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];

    if (!in_array($extension, $allowedExtensions, true)) {
        return $existing;
    }

    $uploadDir = __DIR__ . '/uploads';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        return $existing;
    }

    $safeName = preg_replace('/[^A-Za-z0-9._-]/', '-', pathinfo($originalName, PATHINFO_FILENAME));
    $safeName = trim((string)$safeName, '-_.') ?: 'silinex-image';
    $fileName = date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '-' . $safeName . '.' . $extension;
    $targetPath = $uploadDir . '/' . $fileName;

    if (!is_uploaded_file((string)$_FILES[$field]['tmp_name']) || !move_uploaded_file((string)$_FILES[$field]['tmp_name'], $targetPath)) {
        return $existing;
    }

    return '/uploads/' . $fileName;
}

function adminEnsureTables(): void
{
    if (DB_DRIVER === 'pgsql') {
        return;
    }

    adminExecute("CREATE TABLE IF NOT EXISTS admin_content_items (
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
    )");

    adminExecute("CREATE TABLE IF NOT EXISTS careers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        heading VARCHAR(180) NOT NULL,
        experience VARCHAR(80) NOT NULL,
        job_type VARCHAR(80) NOT NULL,
        city VARCHAR(120) NOT NULL,
        location VARCHAR(180) NOT NULL,
        short_content TEXT NOT NULL,
        content TEXT NOT NULL,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    adminExecute("CREATE TABLE IF NOT EXISTS news_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        heading VARCHAR(180) NOT NULL UNIQUE,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    adminExecute("CREATE TABLE IF NOT EXISTS enquiries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(160) NOT NULL,
        email VARCHAR(180) NOT NULL,
        phone VARCHAR(80) DEFAULT '',
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    if (!adminQuery('SELECT id FROM careers LIMIT 1')) {
        adminExecute("INSERT INTO careers (heading, experience, job_type, city, location, short_content, content) VALUES
            ('IT Recruiter', '2-4 Years', 'Full Time', 'Hyderabad', 'Hyderabad, India', 'Hiring technology recruiters for staffing delivery.', 'Manage sourcing, screening, coordination, and candidate pipeline ownership.'),
            ('Java Developer', '6-8 Years', 'Full Time', 'Hyderabad', 'Hyderabad', 'Enterprise Java application development role.', 'Build and maintain enterprise Java applications, APIs, integrations, and platform features.'),
            ('Oracle DBA', '7+ Years', 'Full Time', 'Bengaluru', 'Bengaluru', 'Oracle database administration and support.', 'Support Oracle database operations, backups, tuning, access control, and availability.')");
    }

    if (!adminQuery('SELECT id FROM news_categories LIMIT 1')) {
        adminExecute("INSERT IGNORE INTO news_categories (heading) VALUES
            ('Company Announcements'),
            ('Partnerships & Alliances'),
            ('Product / Solution Launches'),
            ('Awards & Recognition'),
            ('Oracle Services')");
    }

    if (!adminQuery('SELECT id FROM enquiries LIMIT 1')) {
        adminExecute("INSERT INTO enquiries (name, email, phone, message) VALUES
            ('Bodanapu Bhanu', 'bbhanusree59@gmail.com', '+91 90000 00000', 'Interested in staffing and managed services.'),
            ('Prakash', 'bhrpropertiesllp@gmail.com', '+91 90000 00001', 'Need support for IT consulting and application services.'),
            ('Roshani', 'contact@example.com', '+91 90000 00002', 'Please share details about Oracle services.')");
    }

    if (!adminQuery('SELECT id FROM admin_content_items LIMIT 1')) {
        adminExecute("INSERT INTO admin_content_items (module_key, heading, short_content, content, image, icon, sort_order) VALUES
            ('slider', 'Empowering Digital Growth', 'Main homepage hero message.', 'Promote Silinex IT staffing, managed services, GRC, Oracle, and digital transformation capabilities.', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80', 'Hero', 1),
            ('home-industry', 'Enterprise Technology', 'Industry block for the homepage.', 'Showcase Silinex capabilities for enterprise and technology-led organizations.', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=900&q=80', 'Industry', 1),
            ('news', 'Silinex Expands Managed Services', 'Company update for latest news.', 'Silinex continues to expand managed services and enterprise support offerings for growing clients.', '', 'News', 1),
            ('technology-category', 'Cloud & DevOps', 'Technology category for platform capability.', 'Cloud, deployment, automation, monitoring, and DevOps technology expertise.', '', 'Tech', 1),
            ('why-work', 'Collaborative Growth Culture', 'Reason to work with Silinex.', 'Silinex supports learning, ownership, mentoring, and growth across technology careers.', '', 'Work', 1),
            ('life', 'Life at Silinex', 'People-first workplace content.', 'A collaborative environment built around client impact, technical growth, and shared success.', '', 'Life', 1),
            ('values', 'Trust and Delivery', 'Core company value.', 'We value accountability, transparency, dependable delivery, and long-term partnerships.', '', 'Value', 1)");
    }

    $servicePageColumns = [
        'offer_image' => "ALTER TABLE service_pages ADD COLUMN offer_image VARCHAR(600) NOT NULL DEFAULT '' AFTER offer_json",
        'why_image' => "ALTER TABLE service_pages ADD COLUMN why_image VARCHAR(600) NOT NULL DEFAULT '' AFTER why_json",
        'hero_alignment' => "ALTER TABLE service_pages ADD COLUMN hero_alignment VARCHAR(30) NOT NULL DEFAULT 'center' AFTER hero_image",
        'offer_alignment' => "ALTER TABLE service_pages ADD COLUMN offer_alignment VARCHAR(30) NOT NULL DEFAULT 'image-right' AFTER offer_image",
        'why_alignment' => "ALTER TABLE service_pages ADD COLUMN why_alignment VARCHAR(30) NOT NULL DEFAULT 'image-left' AFTER why_image",
    ];

    foreach ($servicePageColumns as $column => $sql) {
        if (!adminColumnExists('service_pages', $column)) {
            adminTryExecute($sql);
        }
    }
}

if ($pdo) {
    adminEnsureTables();
    adminEnsureVersionTable();
}

$section = $_GET['section'] ?? 'dashboard';
$mode = $_GET['mode'] ?? 'view';
$id = (int)($_GET['id'] ?? 0);
$notice = '';

$contentModules = [
    'slider' => 'Slider',
    'home-industry' => 'Home Industry',
    'news' => 'News',
    'technology-category' => 'Technology Category',
    'why-work' => 'Why Work',
    'life' => 'Life',
    'values' => 'Values',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $postedSection = $_POST['section'] ?? '';
    $postedId = (int)($_POST['id'] ?? 0);

    if ($postedSection === 'versions') {
        $versionAction = $_POST['version_action'] ?? '';

        if ($versionAction === 'delete' && $postedId) {
            adminExecute('DELETE FROM cms_versions WHERE id = ?', [$postedId]);
            adminVersionRedirect('version_deleted=1');
        }

        if ($versionAction === 'clear') {
            adminExecute('DELETE FROM cms_versions');
            adminVersionRedirect('versions_cleared=1');
        }
    }

    if ($postedSection === 'content-item') {
        $moduleKey = $_POST['module_key'] ?? '';
        if (isset($contentModules[$moduleKey])) {
            $beforeRecord = $postedId ? (adminQuery('SELECT * FROM admin_content_items WHERE id = ? AND module_key = ?', [$postedId, $moduleKey])[0] ?? []) : [];
            $fields = [
                $moduleKey,
                trim($_POST['heading'] ?? ''),
                trim($_POST['short_content'] ?? ''),
                trim($_POST['content'] ?? ''),
                adminUploadedPath('image_file', trim($_POST['current_image'] ?? '')),
                trim($_POST['icon'] ?? ''),
                (int)($_POST['sort_order'] ?? 0),
                isset($_POST['is_active']) ? 1 : 0,
            ];

            if ($postedId) {
                adminExecute('UPDATE admin_content_items SET module_key = ?, heading = ?, short_content = ?, content = ?, image = ?, icon = ?, sort_order = ?, is_active = ? WHERE id = ?', [...$fields, $postedId]);
            } else {
                adminExecute('INSERT INTO admin_content_items (module_key, heading, short_content, content, image, icon, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', $fields);
                $postedId = adminLastRecordId('admin_content_items');
            }
            $actionType = $beforeRecord ? 'updated' : 'created';
            $afterRecord = [
                'module_key' => $moduleKey,
                'heading' => trim($_POST['heading'] ?? ''),
                'short_content' => trim($_POST['short_content'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'image' => $fields[4],
                'icon' => trim($_POST['icon'] ?? ''),
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
            ];
            adminLogVersion($moduleKey, $actionType, $postedId, trim($_POST['heading'] ?? ''), adminVersionSnapshot($actionType, $beforeRecord, $afterRecord));
            header('Location: /admin?section=' . urlencode($moduleKey) . '&saved=1');
            exit;
        }
    }

    if ($postedSection === 'services') {
        $originalSlug = trim($_POST['original_slug'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? '01');
        $image = adminUploadedPath('image_file', trim($_POST['current_image'] ?? ''));
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $beforeService = $postedId ? (adminQuery('SELECT * FROM services WHERE id = ?', [$postedId])[0] ?? []) : [];
        $beforePage = $beforeService ? (adminQuery('SELECT * FROM service_pages WHERE service_slug = ?', [$beforeService['slug']])[0] ?? []) : [];
        $beforeRecord = $beforeService ? ['service' => $beforeService, 'detail_page' => $beforePage] : [];

        if ($postedId) {
            adminExecute('UPDATE services SET slug = ?, title = ?, description = ?, icon = ?, image = ?, sort_order = ?, is_active = ? WHERE id = ?', [$slug, $title, $description, $icon, $image, $sortOrder, $isActive, $postedId]);
        } else {
            adminExecute('INSERT INTO services (slug, title, description, icon, image, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)', [$slug, $title, $description, $icon, $image, $sortOrder, $isActive]);
            $postedId = adminLastRecordId('services');
        }

        if ($originalSlug && $originalSlug !== $slug) {
            adminExecute('UPDATE service_details SET service_slug = ? WHERE service_slug = ?', [$slug, $originalSlug]);
            adminExecute('UPDATE service_pages SET service_slug = ? WHERE service_slug = ?', [$slug, $originalSlug]);
        }

        $allowedHeroAlignments = ['left', 'center', 'right'];
        $allowedBlockAlignments = ['image-left', 'image-right'];
        $heroAlignment = $_POST['hero_alignment'] ?? 'center';
        $offerAlignment = $_POST['offer_alignment'] ?? 'image-right';
        $whyAlignment = $_POST['why_alignment'] ?? 'image-left';
        $heroAlignment = in_array($heroAlignment, $allowedHeroAlignments, true) ? $heroAlignment : 'center';
        $offerAlignment = in_array($offerAlignment, $allowedBlockAlignments, true) ? $offerAlignment : 'image-right';
        $whyAlignment = in_array($whyAlignment, $allowedBlockAlignments, true) ? $whyAlignment : 'image-left';

        $heroImage = adminUploadedPath('hero_image_file', trim($_POST['current_hero_image'] ?? '')) ?: $image;
        $offerImage = adminUploadedPath('offer_image_file', trim($_POST['current_offer_image'] ?? '')) ?: $image;
        $whyImage = adminUploadedPath('why_image_file', trim($_POST['current_why_image'] ?? '')) ?: 'https://images.unsplash.com/photo-1639322537228-f710d846310a?auto=format&fit=crop&w=1000&q=80';
        $subtitle = trim($_POST['detail_subtitle'] ?? '') ?: $description;
        $focus = trim($_POST['detail_focus'] ?? '') ?: $description;
        $offerJson = adminJsonLines($_POST['offer_items'] ?? '');
        $engagementJson = adminJsonLines($_POST['engagement_items'] ?? '');
        $whyJson = adminJsonLines($_POST['why_items'] ?? '');

        if ($offerJson === '[]') {
            $offerJson = json_encode([$description], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        if ($engagementJson === '[]') {
            $engagementJson = json_encode(['Consulting support', 'Managed assistance'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        if ($whyJson === '[]') {
            $whyJson = json_encode(['Enterprise-ready delivery aligned with business goals.'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $servicePageParams = [$slug, $subtitle, $heroImage, $heroAlignment, $focus, $offerJson, $offerImage, $offerAlignment, $engagementJson, $whyJson, $whyImage, $whyAlignment];
        if (DB_DRIVER === 'pgsql') {
            adminExecute(
                'INSERT INTO service_pages (service_slug, subtitle, hero_image, hero_alignment, focus, offer_json, offer_image, offer_alignment, engagement_json, why_json, why_image, why_alignment)
                 VALUES (?, ?, ?, ?, ?, ?::jsonb, ?, ?, ?::jsonb, ?::jsonb, ?, ?)
                 ON CONFLICT (service_slug) DO UPDATE SET subtitle = EXCLUDED.subtitle, hero_image = EXCLUDED.hero_image, hero_alignment = EXCLUDED.hero_alignment, focus = EXCLUDED.focus, offer_json = EXCLUDED.offer_json, offer_image = EXCLUDED.offer_image, offer_alignment = EXCLUDED.offer_alignment, engagement_json = EXCLUDED.engagement_json, why_json = EXCLUDED.why_json, why_image = EXCLUDED.why_image, why_alignment = EXCLUDED.why_alignment',
                $servicePageParams
            );
        } else {
            adminExecute(
                'INSERT INTO service_pages (service_slug, subtitle, hero_image, hero_alignment, focus, offer_json, offer_image, offer_alignment, engagement_json, why_json, why_image, why_alignment)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE subtitle = VALUES(subtitle), hero_image = VALUES(hero_image), hero_alignment = VALUES(hero_alignment), focus = VALUES(focus), offer_json = VALUES(offer_json), offer_image = VALUES(offer_image), offer_alignment = VALUES(offer_alignment), engagement_json = VALUES(engagement_json), why_json = VALUES(why_json), why_image = VALUES(why_image), why_alignment = VALUES(why_alignment)',
                $servicePageParams
            );
        }

        $actionType = $beforeRecord ? 'updated' : 'created';
        $afterRecord = [
            'service' => [
                'slug' => $slug,
                'title' => $title,
                'description' => $description,
                'icon' => $icon,
                'image' => $image,
                'sort_order' => $sortOrder,
                'is_active' => $isActive,
            ],
            'detail_page' => [
                'subtitle' => $subtitle,
                'hero_image' => $heroImage,
                'hero_alignment' => $heroAlignment,
                'focus' => $focus,
                'offer' => adminTextLines($_POST['offer_items'] ?? ''),
                'offer_image' => $offerImage,
                'offer_alignment' => $offerAlignment,
                'engagement' => adminTextLines($_POST['engagement_items'] ?? ''),
                'why' => adminTextLines($_POST['why_items'] ?? ''),
                'why_image' => $whyImage,
                'why_alignment' => $whyAlignment,
            ],
        ];
        adminLogVersion('services', $actionType, $postedId, $title, adminVersionSnapshot($actionType, $beforeRecord, $afterRecord));

        header('Location: /admin?section=services&saved=1');
        exit;
    }

    if ($postedSection === 'careers') {
        $beforeRecord = $postedId ? (adminQuery('SELECT * FROM careers WHERE id = ?', [$postedId])[0] ?? []) : [];
        $fields = [
            trim($_POST['heading'] ?? ''),
            trim($_POST['experience'] ?? ''),
            trim($_POST['job_type'] ?? ''),
            trim($_POST['city'] ?? ''),
            trim($_POST['location'] ?? ''),
            trim($_POST['short_content'] ?? ''),
            trim($_POST['content'] ?? ''),
            isset($_POST['is_active']) ? 1 : 0,
        ];

        if ($postedId) {
            adminExecute('UPDATE careers SET heading = ?, experience = ?, job_type = ?, city = ?, location = ?, short_content = ?, content = ?, is_active = ? WHERE id = ?', [...$fields, $postedId]);
        } else {
            adminExecute('INSERT INTO careers (heading, experience, job_type, city, location, short_content, content, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', $fields);
            $postedId = adminLastRecordId('careers');
        }
        $actionType = $beforeRecord ? 'updated' : 'created';
        $afterRecord = [
            'heading' => $fields[0],
            'experience' => $fields[1],
            'job_type' => $fields[2],
            'city' => $fields[3],
            'location' => $fields[4],
            'short_content' => $fields[5],
            'content' => $fields[6],
            'is_active' => $fields[7],
        ];
        adminLogVersion('careers', $actionType, $postedId, trim($_POST['heading'] ?? ''), adminVersionSnapshot($actionType, $beforeRecord, $afterRecord));
        header('Location: /admin?section=careers&saved=1');
        exit;
    }

    if ($postedSection === 'news-categories') {
        $beforeRecord = $postedId ? (adminQuery('SELECT * FROM news_categories WHERE id = ?', [$postedId])[0] ?? []) : [];
        $heading = trim($_POST['heading'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        if ($postedId) {
            adminExecute('UPDATE news_categories SET heading = ?, is_active = ? WHERE id = ?', [$heading, $isActive, $postedId]);
        } else {
            adminExecute('INSERT INTO news_categories (heading, is_active) VALUES (?, ?)', [$heading, $isActive]);
            $postedId = adminLastRecordId('news_categories');
        }
        $actionType = $beforeRecord ? 'updated' : 'created';
        $afterRecord = [
            'heading' => $heading,
            'is_active' => $isActive,
        ];
        adminLogVersion('news-categories', $actionType, $postedId, $heading, adminVersionSnapshot($actionType, $beforeRecord, $afterRecord));
        header('Location: /admin?section=news-categories&saved=1');
        exit;
    }

    if ($postedSection === 'partners') {
        $beforeRecord = $postedId ? (adminQuery('SELECT * FROM partners WHERE id = ?', [$postedId])[0] ?? []) : [];
        $partnerType = $_POST['partner_type'] ?? 'featured';
        $name = trim($_POST['name'] ?? '');
        $logo = adminUploadedPath('logo_file', trim($_POST['current_logo'] ?? ''));
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        if ($postedId) {
            adminExecute('UPDATE partners SET partner_type = ?, name = ?, logo = ?, sort_order = ?, is_active = ? WHERE id = ?', [$partnerType, $name, $logo, $sortOrder, $isActive, $postedId]);
        } else {
            adminExecute('INSERT INTO partners (partner_type, name, logo, sort_order, is_active) VALUES (?, ?, ?, ?, ?)', [$partnerType, $name, $logo, $sortOrder, $isActive]);
            $postedId = adminLastRecordId('partners');
        }
        $actionType = $beforeRecord ? 'updated' : 'created';
        $afterRecord = [
            'partner_type' => $partnerType,
            'name' => $name,
            'logo' => $logo,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ];
        adminLogVersion('partners', $actionType, $postedId, $name, adminVersionSnapshot($actionType, $beforeRecord, $afterRecord));
        header('Location: /admin?section=partners&saved=1');
        exit;
    }

    if ($postedSection === 'technologies') {
        $beforeRecord = $postedId ? (adminQuery('SELECT * FROM technologies WHERE id = ?', [$postedId])[0] ?? []) : [];
        $category = trim($_POST['category'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $logo = adminUploadedPath('logo_file', trim($_POST['current_logo'] ?? ''));
        $categorySort = (int)($_POST['category_sort'] ?? 0);
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($postedId) {
            adminExecute('UPDATE technologies SET category = ?, name = ?, logo = ?, category_sort = ?, sort_order = ?, is_active = ? WHERE id = ?', [$category, $name, $logo, $categorySort, $sortOrder, $isActive, $postedId]);
        } else {
            adminExecute('INSERT INTO technologies (category, name, logo, category_sort, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?)', [$category, $name, $logo, $categorySort, $sortOrder, $isActive]);
            $postedId = adminLastRecordId('technologies');
        }
        $actionType = $beforeRecord ? 'updated' : 'created';
        $afterRecord = [
            'category' => $category,
            'name' => $name,
            'logo' => $logo,
            'category_sort' => $categorySort,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ];
        adminLogVersion('technologies', $actionType, $postedId, $name, adminVersionSnapshot($actionType, $beforeRecord, $afterRecord));
        header('Location: /admin?section=technologies&saved=1');
        exit;
    }

    if ($postedSection === 'testimonials') {
        $beforeRecord = $postedId ? (adminQuery('SELECT * FROM testimonials WHERE id = ?', [$postedId])[0] ?? []) : [];
        $quote = trim($_POST['quote'] ?? '');
        $personName = trim($_POST['person_name'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($postedId) {
            adminExecute('UPDATE testimonials SET quote = ?, person_name = ?, company = ?, sort_order = ?, is_active = ? WHERE id = ?', [$quote, $personName, $company, $sortOrder, $isActive, $postedId]);
        } else {
            adminExecute('INSERT INTO testimonials (quote, person_name, company, sort_order, is_active) VALUES (?, ?, ?, ?, ?)', [$quote, $personName, $company, $sortOrder, $isActive]);
            $postedId = adminLastRecordId('testimonials');
        }
        $actionType = $beforeRecord ? 'updated' : 'created';
        $afterRecord = [
            'quote' => $quote,
            'person_name' => $personName,
            'company' => $company,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ];
        adminLogVersion('testimonials', $actionType, $postedId, $personName, adminVersionSnapshot($actionType, $beforeRecord, $afterRecord));
        header('Location: /admin?section=testimonials&saved=1');
        exit;
    }

    if ($postedSection === 'blogs') {
        $beforeRecord = $postedId ? (adminQuery('SELECT * FROM blogs WHERE id = ?', [$postedId])[0] ?? []) : [];
        $title = trim($_POST['title'] ?? '');
        $publishedDate = trim($_POST['published_date'] ?? date('Y-m-d'));
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($postedId) {
            adminExecute('UPDATE blogs SET title = ?, published_date = ?, sort_order = ?, is_active = ? WHERE id = ?', [$title, $publishedDate, $sortOrder, $isActive, $postedId]);
        } else {
            adminExecute('INSERT INTO blogs (title, published_date, sort_order, is_active) VALUES (?, ?, ?, ?)', [$title, $publishedDate, $sortOrder, $isActive]);
            $postedId = adminLastRecordId('blogs');
        }
        $actionType = $beforeRecord ? 'updated' : 'created';
        $afterRecord = [
            'title' => $title,
            'published_date' => $publishedDate,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ];
        adminLogVersion('blogs', $actionType, $postedId, $title, adminVersionSnapshot($actionType, $beforeRecord, $afterRecord));
        header('Location: /admin?section=blogs&saved=1');
        exit;
    }
}

if ($mode === 'delete' && $id && $pdo) {
    if (isset($contentModules[$section])) {
        $deletedRecord = adminQuery('SELECT * FROM admin_content_items WHERE id = ? AND module_key = ?', [$id, $section])[0] ?? [];
        adminExecute('DELETE FROM admin_content_items WHERE id = ? AND module_key = ?', [$id, $section]);
        if ($deletedRecord) {
            adminLogVersion($section, 'deleted', $id, $deletedRecord['heading'] ?? '', adminVersionSnapshot('deleted', $deletedRecord, []));
        }
        header('Location: /admin?section=' . urlencode($section) . '&deleted=1');
        exit;
    }

    $deleteMap = [
        'services' => 'services',
        'careers' => 'careers',
        'news-categories' => 'news_categories',
        'partners' => 'partners',
        'technologies' => 'technologies',
        'testimonials' => 'testimonials',
        'blogs' => 'blogs',
        'enquiries' => 'enquiries',
    ];
    if (isset($deleteMap[$section])) {
        $deletedRecord = adminQuery('SELECT * FROM ' . $deleteMap[$section] . ' WHERE id = ?', [$id])[0] ?? [];
        adminExecute('DELETE FROM ' . $deleteMap[$section] . ' WHERE id = ?', [$id]);
        if ($deletedRecord) {
            $label = $deletedRecord['title'] ?? $deletedRecord['heading'] ?? $deletedRecord['name'] ?? $deletedRecord['person_name'] ?? ('Record #' . $id);
            adminLogVersion($section, 'deleted', $id, (string)$label, adminVersionSnapshot('deleted', $deletedRecord, []));
        }
        header('Location: /admin?section=' . urlencode($section) . '&deleted=1');
        exit;
    }
}

if (isset($_GET['saved'])) {
    $notice = 'Content saved successfully.';
} elseif (isset($_GET['deleted'])) {
    $notice = 'Record deleted successfully.';
} elseif (isset($_GET['version_deleted'])) {
    $notice = 'Version deleted successfully.';
} elseif (isset($_GET['versions_cleared'])) {
    $notice = 'All CMS versions cleared successfully.';
}

$counts = [
    'services' => (int)(adminQuery('SELECT COUNT(*) total FROM services')[0]['total'] ?? 0),
    'partners' => (int)(adminQuery('SELECT COUNT(*) total FROM partners')[0]['total'] ?? 0),
    'careers' => (int)(adminQuery('SELECT COUNT(*) total FROM careers')[0]['total'] ?? 0),
    'testimonials' => (int)(adminQuery('SELECT COUNT(*) total FROM testimonials')[0]['total'] ?? 0),
    'blogs' => (int)(adminQuery('SELECT COUNT(*) total FROM blogs')[0]['total'] ?? 0),
    'enquiries' => (int)(adminQuery('SELECT COUNT(*) total FROM enquiries')[0]['total'] ?? 0),
    'versions' => (int)(adminQuery('SELECT COUNT(*) total FROM cms_versions')[0]['total'] ?? 0),
];

$nav = [
    'dashboard' => ['Dashboard', '▦'],
    'services' => ['Services', '□'],
    'careers' => ['Careers', '▣'],
    'news-categories' => ['News Category', '▤'],
    'partners' => ['Partner Management', '◇'],
    'technologies' => ['Technologies', '⚙'],
    'enquiries' => ['Enquiry', '✉'],
    'appearance' => ['Web Appearance', '◌'],
];

$nav = [
    'dashboard' => ['Dashboard', '▦'],
    'slider' => ['Slider', '▧'],
    'home-industry' => ['Home Industry', '▣'],
    'services' => ['Services', '□'],
    'careers' => ['Careers', '▤'],
    'news-categories' => ['News Category', '▥'],
    'news' => ['News', '▨'],
    'technology-category' => ['Technology Category', '▦'],
    'technologies' => ['Technologies', '⚙'],
    'why-work' => ['Why Work', '✦'],
    'life' => ['Life', '♡'],
    'values' => ['Values', '$'],
    'testimonials' => ['Testimonials', '☉'],
    'blogs' => ['Blogs', '▤'],
    'partners' => ['Partner Management', '◇'],
    'enquiries' => ['Enquiry', '✉'],
    'versions' => ['CMS Versions', 'v'],
    'appearance' => ['Web Appearance', '◌'],
];

function adminActive(string $key, string $section): string
{
    return $key === $section ? 'is-active' : '';
}

function adminStatus(int $active): string
{
    return $active ? '<span class="status active">Active</span>' : '<span class="status inactive">Hidden</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Silinex Admin Panel</title>
    <link rel="stylesheet" href="/admin.css?v=20260525-search">
</head>
<body>
    <aside class="admin-sidebar">
        <a class="admin-logo" href="/admin">
            <img src="/assets/silinex-logo.jpeg" alt="Silinex Global Services">
        </a>
        <label class="admin-search">
            <span>Search</span>
            <input type="search" placeholder="Search" data-admin-global-search>
        </label>
        <nav class="admin-nav" aria-label="Admin navigation">
            <?php foreach ($nav as $key => [$label, $icon]): ?>
                <a class="<?php echo adminActive($key, $section); ?>" href="/admin?section=<?php echo urlencode($key); ?>">
                    <span><?php echo htmlspecialchars($icon); ?></span>
                    <?php echo htmlspecialchars($label); ?>
                    <b>›</b>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <main class="admin-shell">
        <header class="admin-topbar">
            <strong data-clock><?php echo date('D M d Y H:i:s'); ?> GMT+0530 (India Standard Time)</strong>
            <div class="topbar-actions">
                <div class="admin-avatar" aria-label="Admin profile">SG</div>
                <a class="logout-link" href="/admin?logout=1">Logout</a>
            </div>
        </header>

        <?php if (!$pdo): ?>
            <section class="admin-alert error">Database connection failed. Check your database server and config.php settings, then reload this page.</section>
        <?php endif; ?>

        <?php if ($notice): ?>
            <section class="admin-alert"><?php echo htmlspecialchars($notice); ?></section>
        <?php endif; ?>

        <?php if ($section === 'dashboard'): ?>
            <section class="admin-page-head">
                <div>
                    <p>Control Center</p>
                    <h1>Dashboard</h1>
                </div>
                <a class="admin-button" href="/" target="_blank">View Website</a>
            </section>

            <section class="metric-grid">
                <?php
                $metricCards = [
                    ['Total Services', $counts['services'], '▦', 'navy', 'services'],
                    ['Total Partners', $counts['partners'], '◇', 'blue', 'partners'],
                    ['Total Job Posting', $counts['careers'], '▣', 'cyan', 'careers'],
                    ['Total Testimonials', $counts['testimonials'], '◌', 'slate', 'testimonials'],
                    ['Total Blogs', $counts['blogs'], '▤', 'ink', 'blogs'],
                    ['Total Enquiries', $counts['enquiries'], '✉', 'sky', 'enquiries'],
                ];
                foreach ($metricCards as [$label, $count, $icon, $tone, $targetSection]):
                ?>
                    <a class="metric-card <?php echo $tone; ?>" href="/admin?section=<?php echo urlencode($targetSection); ?>" aria-label="Open <?php echo htmlspecialchars($label); ?>">
                        <span><?php echo htmlspecialchars($icon); ?></span>
                        <strong><?php echo $count; ?></strong>
                        <p><?php echo htmlspecialchars($label); ?></p>
                    </a>
                <?php endforeach; ?>
            </section>

            <section class="admin-card">
                <div class="table-head">
                    <h2>Recent Enquiries</h2>
                    <div class="table-tools"><button>Copy</button><button>Excel</button><button>PDF</button></div>
                </div>
                <?php $rows = adminQuery('SELECT * FROM enquiries ORDER BY id DESC LIMIT 8'); ?>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Serial</th><th>Name</th><th>Email</th><th>Phone</th><th>Message</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $index => $row): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php elseif ($section === 'versions'): ?>
            <?php
            $versionSections = adminQuery("SELECT section_key, COUNT(*) total FROM cms_versions WHERE action_type <> 'test' GROUP BY section_key ORDER BY section_key");
            $versionActions = adminQuery("SELECT action_type, COUNT(*) total FROM cms_versions WHERE action_type <> 'test' GROUP BY action_type ORDER BY action_type");
            $versionFilterSection = trim($_GET['version_section'] ?? '');
            $versionFilterAction = trim($_GET['version_action'] ?? '');
            $versionSearch = trim($_GET['version_search'] ?? '');
            $versionWhere = [];
            $versionParams = [];

            if ($versionFilterSection !== '') {
                $versionWhere[] = 'section_key = ?';
                $versionParams[] = $versionFilterSection;
            }

            if ($versionFilterAction !== '') {
                $versionWhere[] = 'action_type = ?';
                $versionParams[] = $versionFilterAction;
            } else {
                $versionWhere[] = 'action_type <> ?';
                $versionParams[] = 'test';
            }

            if ($versionSearch !== '') {
                $versionWhere[] = '(record_label LIKE ? OR admin_user LIKE ? OR section_key LIKE ? OR action_type LIKE ?)';
                $searchTerm = '%' . $versionSearch . '%';
                array_push($versionParams, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
            }

            $versionWhereSql = $versionWhere ? ' WHERE ' . implode(' AND ', $versionWhere) : '';
            $versionRows = adminQuery('SELECT * FROM cms_versions' . $versionWhereSql . ' ORDER BY created_at DESC, id DESC LIMIT 200', $versionParams);
            $versionLatest = adminQuery("SELECT * FROM cms_versions WHERE action_type <> 'test' ORDER BY created_at DESC, id DESC LIMIT 1")[0] ?? null;
            $versionTotal = (int)(adminQuery("SELECT COUNT(*) total FROM cms_versions WHERE action_type <> 'test'")[0]['total'] ?? 0);
            $versionShown = count($versionRows);
            ?>
            <section class="admin-page-head">
                <div>
                    <p>Version History</p>
                    <h1>CMS Versions</h1>
                </div>
            </section>

            <section class="admin-card version-tools">
                <div class="table-head">
                    <div>
                        <h2>Version Storage</h2>
                        <p class="table-subtitle">CMS changes are saved as readable content snapshots.</p>
                    </div>
                    <form class="inline-danger-form" method="post" onsubmit="return confirm('Clear all CMS versions?');">
                        <input type="hidden" name="section" value="versions">
                        <input type="hidden" name="version_action" value="clear">
                        <button class="danger-button" type="submit" <?php echo $versionTotal ? '' : 'disabled'; ?>>Clear All</button>
                    </form>
                </div>

                <div class="version-compact-status">
                    <span class="status <?php echo $versionTotal ? 'active' : 'inactive'; ?>">
                        <?php echo $versionTotal ? 'Versioning Active' : 'No Versions Stored'; ?>
                    </span>
                    <strong><?php echo $versionTotal; ?> snapshots</strong>
                    <p>Edits, new records, and deletes are stored for review.</p>
                    <?php if ($versionLatest): ?>
                        <small>Latest: <?php echo htmlspecialchars((string)$versionLatest['record_label']); ?></small>
                    <?php endif; ?>
                </div>
            </section>

            <section class="admin-card version-history">
                <div class="table-head">
                    <div>
                        <h2>Recent CMS Snapshots</h2>
                        <p class="table-subtitle">Showing <?php echo $versionShown; ?> of <?php echo $versionTotal; ?> stored versions.</p>
                    </div>
                    <form class="version-filter-form" method="get">
                        <input type="hidden" name="section" value="versions">
                        <select name="version_section">
                            <option value="">All sections</option>
                            <?php foreach ($versionSections as $versionSection): ?>
                                <option value="<?php echo htmlspecialchars((string)$versionSection['section_key']); ?>" <?php echo $versionFilterSection === (string)$versionSection['section_key'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars((string)$versionSection['section_key']); ?> (<?php echo (int)$versionSection['total']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="version_action">
                            <option value="">All actions</option>
                            <?php foreach ($versionActions as $versionActionRow): ?>
                                <option value="<?php echo htmlspecialchars((string)$versionActionRow['action_type']); ?>" <?php echo $versionFilterAction === (string)$versionActionRow['action_type'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars((string)$versionActionRow['action_type']); ?> (<?php echo (int)$versionActionRow['total']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="search" name="version_search" placeholder="Search versions" value="<?php echo htmlspecialchars($versionSearch); ?>">
                        <button type="submit">Filter</button>
                        <a href="/admin?section=versions">Reset</a>
                    </form>
                </div>
                <div class="version-list">
                    <?php foreach ($versionRows as $version): ?>
                        <?php
                        $decodedSnapshot = json_decode((string)$version['snapshot_json'], true);
                        $decodedSnapshot = is_array($decodedSnapshot) ? $decodedSnapshot : ['stored_content' => (string)$version['snapshot_json']];
                        ?>
                        <article class="version-card">
                            <div class="version-meta">
                                <span><?php echo htmlspecialchars(strtoupper((string)$version['action_type'])); ?></span>
                                <strong><?php echo htmlspecialchars((string)($version['record_label'] ?: 'Untitled record')); ?></strong>
                                <small>
                                    <?php echo htmlspecialchars((string)$version['section_key']); ?>
                                    - Record #<?php echo (int)$version['record_id']; ?>
                                    - <?php echo htmlspecialchars((string)$version['created_at']); ?>
                                    - <?php echo htmlspecialchars((string)($version['admin_user'] ?? 'Admin')); ?>
                                </small>
                            </div>
                            <form class="version-delete-form" method="post" onsubmit="return confirm('Delete this CMS version?');">
                                <input type="hidden" name="section" value="versions">
                                <input type="hidden" name="version_action" value="delete">
                                <input type="hidden" name="id" value="<?php echo (int)$version['id']; ?>">
                                <button class="danger-button" type="submit">Delete</button>
                            </form>
                            <details>
                                <summary>View content data</summary>
                                <?php echo adminRenderVersionSnapshot($decodedSnapshot); ?>
                            </details>
                        </article>
                    <?php endforeach; ?>
                    <?php if (!$versionRows): ?>
                        <p class="empty-note">No CMS versions yet. Save or delete content to create the first version.</p>
                    <?php endif; ?>
                </div>
            </section>
        <?php elseif ($section === 'services'): ?>
            <?php
            $editing = $mode === 'edit' && $id ? (adminQuery('SELECT * FROM services WHERE id = ?', [$id])[0] ?? null) : null;
            $servicePage = $editing ? (adminQuery('SELECT * FROM service_pages WHERE service_slug = ?', [$editing['slug']])[0] ?? []) : [];
            if ($mode === 'add' || $editing):
            ?>
                <section class="admin-page-head"><h1><?php echo $editing ? 'Edit Services' : 'Add Services'; ?></h1></section>
                <form class="admin-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="section" value="services">
                    <input type="hidden" name="id" value="<?php echo (int)($editing['id'] ?? 0); ?>">
                    <input type="hidden" name="original_slug" value="<?php echo htmlspecialchars($editing['slug'] ?? ''); ?>">
                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($editing['image'] ?? ''); ?>">
                    <input type="hidden" name="current_hero_image" value="<?php echo htmlspecialchars($servicePage['hero_image'] ?? ''); ?>">
                    <input type="hidden" name="current_offer_image" value="<?php echo htmlspecialchars($servicePage['offer_image'] ?? ''); ?>">
                    <input type="hidden" name="current_why_image" value="<?php echo htmlspecialchars($servicePage['why_image'] ?? ''); ?>">
                    <label>Heading* <input name="title" required value="<?php echo htmlspecialchars($editing['title'] ?? ''); ?>"></label>
                    <label>Slug* <input name="slug" required value="<?php echo htmlspecialchars($editing['slug'] ?? ''); ?>"></label>
                    <label>Short Content* <textarea name="description" required maxlength="500"><?php echo htmlspecialchars($editing['description'] ?? ''); ?></textarea></label>
                    <label>
                        Photo*
                        <input type="file" name="image_file" accept="image/*" <?php echo empty($editing['image']) ? 'required' : ''; ?>>
                        <span class="file-help">Upload JPG, PNG, WEBP, GIF, or SVG.</span>
                    </label>
                    <label>Icon / Number* <input name="icon" required value="<?php echo htmlspecialchars($editing['icon'] ?? '01'); ?>"></label>
                    <?php if (!empty($editing['image'])): ?>
                        <div class="upload-preview">
                            <span>Current image</span>
                            <img src="<?php echo htmlspecialchars($editing['image']); ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <label>Sort Order <input type="number" name="sort_order" value="<?php echo htmlspecialchars((string)($editing['sort_order'] ?? 0)); ?>"></label>
                    <label class="check"><input type="checkbox" name="is_active" <?php echo (int)($editing['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label>
                    <div class="form-section-title full">
                        <span>Service Detail Page</span>
                        <p>Manage the detail-page content, section photos, and text/image alignment from here.</p>
                    </div>
                    <label>Detail Subtitle <input name="detail_subtitle" value="<?php echo htmlspecialchars($servicePage['subtitle'] ?? ''); ?>"></label>
                    <label>
                        Hero Alignment
                        <select name="hero_alignment">
                            <?php foreach (['left' => 'Left aligned', 'center' => 'Centered', 'right' => 'Right aligned'] as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo (($servicePage['hero_alignment'] ?? 'center') === $value) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="full">Focus Content <textarea name="detail_focus"><?php echo htmlspecialchars($servicePage['focus'] ?? ''); ?></textarea></label>
                    <label>
                        Hero Image
                        <input type="file" name="hero_image_file" accept="image/*">
                        <span class="file-help">Shown at the top of the service detail page.</span>
                    </label>
                    <?php if (!empty($servicePage['hero_image'])): ?>
                        <div class="upload-preview">
                            <span>Current hero image</span>
                            <img src="<?php echo htmlspecialchars($servicePage['hero_image']); ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <label class="full">What We Offer <textarea name="offer_items" rows="6"><?php echo htmlspecialchars(adminJsonTextarea($servicePage['offer_json'] ?? '')); ?></textarea></label>
                    <label>
                        Offer Photo
                        <input type="file" name="offer_image_file" accept="image/*">
                        <span class="file-help">Visual used beside the What We Offer section.</span>
                    </label>
                    <label>
                        Offer Section Alignment
                        <select name="offer_alignment">
                            <?php foreach (['image-right' => 'Text left, image right', 'image-left' => 'Image left, text right'] as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo (($servicePage['offer_alignment'] ?? 'image-right') === $value) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <?php if (!empty($servicePage['offer_image'])): ?>
                        <div class="upload-preview">
                            <span>Current offer photo</span>
                            <img src="<?php echo htmlspecialchars($servicePage['offer_image']); ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <label class="full">Engagement Models <textarea name="engagement_items" rows="5"><?php echo htmlspecialchars(adminJsonTextarea($servicePage['engagement_json'] ?? '')); ?></textarea></label>
                    <label class="full">Why Choose <textarea name="why_items" rows="6"><?php echo htmlspecialchars(adminJsonTextarea($servicePage['why_json'] ?? '')); ?></textarea></label>
                    <label>
                        Why Choose Photo
                        <input type="file" name="why_image_file" accept="image/*">
                        <span class="file-help">Visual used beside the Why Choose section.</span>
                    </label>
                    <label>
                        Why Choose Alignment
                        <select name="why_alignment">
                            <?php foreach (['image-left' => 'Image left, text right', 'image-right' => 'Text left, image right'] as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo (($servicePage['why_alignment'] ?? 'image-left') === $value) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <?php if (!empty($servicePage['why_image'])): ?>
                        <div class="upload-preview">
                            <span>Current why choose photo</span>
                            <img src="<?php echo htmlspecialchars($servicePage['why_image']); ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <button class="admin-button" type="submit">Submit</button>
                </form>
            <?php else: ?>
                <section class="admin-page-head"><h1>View Services</h1><a class="admin-button" href="/admin?section=services&mode=add">Add New</a></section>
                <?php $rows = adminQuery('SELECT * FROM services ORDER BY sort_order, id'); include __DIR__ . '/admin_table_services.php'; ?>
            <?php endif; ?>
        <?php elseif ($section === 'careers'): ?>
            <?php
            $editing = $mode === 'edit' && $id ? (adminQuery('SELECT * FROM careers WHERE id = ?', [$id])[0] ?? null) : null;
            if ($mode === 'add' || $editing):
            ?>
                <section class="admin-page-head"><h1><?php echo $editing ? 'Edit Career' : 'Add Career'; ?></h1></section>
                <form class="admin-form" method="post">
                    <input type="hidden" name="section" value="careers">
                    <input type="hidden" name="id" value="<?php echo (int)($editing['id'] ?? 0); ?>">
                    <label>Heading* <input name="heading" required value="<?php echo htmlspecialchars($editing['heading'] ?? ''); ?>"></label>
                    <label>Experience* <input name="experience" required value="<?php echo htmlspecialchars($editing['experience'] ?? ''); ?>"></label>
                    <label>Job Type* <select name="job_type"><option>Full Time</option><option>Contract</option><option>Remote</option><option>Hybrid</option></select></label>
                    <label>Select City* <select name="city"><option>Hyderabad</option><option>Mumbai</option><option>Delhi</option><option>Bengaluru</option><option>Chennai</option><option>Kolkata</option></select></label>
                    <label>Location* <input name="location" required value="<?php echo htmlspecialchars($editing['location'] ?? ''); ?>"></label>
                    <label>Short Content* <textarea name="short_content" required><?php echo htmlspecialchars($editing['short_content'] ?? ''); ?></textarea></label>
                    <label class="full">Content* <textarea name="content" required><?php echo htmlspecialchars($editing['content'] ?? ''); ?></textarea></label>
                    <label class="check"><input type="checkbox" name="is_active" <?php echo (int)($editing['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label>
                    <button class="admin-button" type="submit">Submit</button>
                </form>
            <?php else: ?>
                <section class="admin-page-head"><h1>View Careers</h1><a class="admin-button" href="/admin?section=careers&mode=add">Add New</a></section>
                <?php $rows = adminQuery('SELECT * FROM careers ORDER BY id DESC'); include __DIR__ . '/admin_table_careers.php'; ?>
            <?php endif; ?>
        <?php elseif ($section === 'news-categories'): ?>
            <?php
            $editing = $mode === 'edit' && $id ? (adminQuery('SELECT * FROM news_categories WHERE id = ?', [$id])[0] ?? null) : null;
            if ($mode === 'add' || $editing):
            ?>
                <section class="admin-page-head"><h1><?php echo $editing ? 'Edit News Category' : 'Add News Category'; ?></h1></section>
                <form class="admin-form compact" method="post">
                    <input type="hidden" name="section" value="news-categories">
                    <input type="hidden" name="id" value="<?php echo (int)($editing['id'] ?? 0); ?>">
                    <label>Heading* <input name="heading" required value="<?php echo htmlspecialchars($editing['heading'] ?? ''); ?>"></label>
                    <label class="check"><input type="checkbox" name="is_active" <?php echo (int)($editing['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label>
                    <button class="admin-button" type="submit">Submit</button>
                </form>
            <?php else: ?>
                <section class="admin-page-head"><h1>View News Category</h1><a class="admin-button" href="/admin?section=news-categories&mode=add">Add New</a></section>
                <?php $rows = adminQuery('SELECT * FROM news_categories ORDER BY id'); include __DIR__ . '/admin_table_news_categories.php'; ?>
            <?php endif; ?>
        <?php elseif (isset($contentModules[$section])): ?>
            <?php
            $moduleLabel = $contentModules[$section];
            $editing = $mode === 'edit' && $id ? (adminQuery('SELECT * FROM admin_content_items WHERE id = ? AND module_key = ?', [$id, $section])[0] ?? null) : null;
            if ($mode === 'add' || $editing):
            ?>
                <section class="admin-page-head"><h1><?php echo $editing ? 'Edit ' . htmlspecialchars($moduleLabel) : 'Add ' . htmlspecialchars($moduleLabel); ?></h1></section>
                <form class="admin-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="section" value="content-item">
                    <input type="hidden" name="module_key" value="<?php echo htmlspecialchars($section); ?>">
                    <input type="hidden" name="id" value="<?php echo (int)($editing['id'] ?? 0); ?>">
                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($editing['image'] ?? ''); ?>">
                    <label>Heading* <input name="heading" required value="<?php echo htmlspecialchars($editing['heading'] ?? ''); ?>"></label>
                    <label>Icon / Label <input name="icon" value="<?php echo htmlspecialchars($editing['icon'] ?? ''); ?>"></label>
                    <label>Short Content* <textarea name="short_content" required><?php echo htmlspecialchars($editing['short_content'] ?? ''); ?></textarea></label>
                    <label>
                        Image
                        <input type="file" name="image_file" accept="image/*">
                        <span class="file-help">Choose an image to replace the current section image.</span>
                    </label>
                    <?php if (!empty($editing['image'])): ?>
                        <div class="upload-preview">
                            <span>Current image</span>
                            <img src="<?php echo htmlspecialchars($editing['image']); ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <label class="full">Content* <textarea name="content" required><?php echo htmlspecialchars($editing['content'] ?? ''); ?></textarea></label>
                    <label>Sort Order <input type="number" name="sort_order" value="<?php echo htmlspecialchars((string)($editing['sort_order'] ?? 0)); ?>"></label>
                    <label class="check"><input type="checkbox" name="is_active" <?php echo (int)($editing['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label>
                    <button class="admin-button" type="submit">Submit</button>
                </form>
            <?php else: ?>
                <section class="admin-page-head"><h1>View <?php echo htmlspecialchars($moduleLabel); ?></h1><a class="admin-button" href="/admin?section=<?php echo urlencode($section); ?>&mode=add">Add New</a></section>
                <?php $rows = adminQuery('SELECT * FROM admin_content_items WHERE module_key = ? ORDER BY sort_order, id', [$section]); include __DIR__ . '/admin_table_content_items.php'; ?>
            <?php endif; ?>
        <?php elseif ($section === 'partners'): ?>
            <?php
            $editing = $mode === 'edit' && $id ? (adminQuery('SELECT * FROM partners WHERE id = ?', [$id])[0] ?? null) : null;
            if ($mode === 'add' || $editing):
            ?>
                <section class="admin-page-head"><h1><?php echo $editing ? 'Edit Partner' : 'Add Partner'; ?></h1></section>
                <form class="admin-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="section" value="partners">
                    <input type="hidden" name="id" value="<?php echo (int)($editing['id'] ?? 0); ?>">
                    <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($editing['logo'] ?? ''); ?>">
                    <label>Partner Type* <select name="partner_type"><option value="featured">Featured</option><option value="technology">Technology</option><option value="alliance">Alliance</option></select></label>
                    <label>Name* <input name="name" required value="<?php echo htmlspecialchars($editing['name'] ?? ''); ?>"></label>
                    <label>
                        Logo*
                        <input type="file" name="logo_file" accept="image/*" <?php echo empty($editing['logo']) ? 'required' : ''; ?>>
                        <span class="file-help">Upload the partner logo as an image file.</span>
                    </label>
                    <?php if (!empty($editing['logo'])): ?>
                        <div class="upload-preview logo-preview">
                            <span>Current logo</span>
                            <img src="<?php echo htmlspecialchars($editing['logo']); ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <label>Sort Order <input type="number" name="sort_order" value="<?php echo htmlspecialchars((string)($editing['sort_order'] ?? 0)); ?>"></label>
                    <label class="check"><input type="checkbox" name="is_active" <?php echo (int)($editing['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label>
                    <button class="admin-button" type="submit">Submit</button>
                </form>
            <?php else: ?>
                <section class="admin-page-head"><h1>Partner Management</h1><a class="admin-button" href="/admin?section=partners&mode=add">Add New</a></section>
                <?php $rows = adminQuery('SELECT * FROM partners ORDER BY partner_type, sort_order, id'); include __DIR__ . '/admin_table_partners.php'; ?>
            <?php endif; ?>
        <?php elseif ($section === 'technologies'): ?>
            <?php
            $editing = $mode === 'edit' && $id ? (adminQuery('SELECT * FROM technologies WHERE id = ?', [$id])[0] ?? null) : null;
            if ($mode === 'add' || $editing):
            ?>
                <section class="admin-page-head"><h1><?php echo $editing ? 'Edit Technology' : 'Add Technology'; ?></h1></section>
                <form class="admin-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="section" value="technologies">
                    <input type="hidden" name="id" value="<?php echo (int)($editing['id'] ?? 0); ?>">
                    <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($editing['logo'] ?? ''); ?>">
                    <label>Category* <input name="category" required value="<?php echo htmlspecialchars($editing['category'] ?? ''); ?>"></label>
                    <label>Name* <input name="name" required value="<?php echo htmlspecialchars($editing['name'] ?? ''); ?>"></label>
                    <label>
                        Logo*
                        <input type="file" name="logo_file" accept="image/*" <?php echo empty($editing['logo']) ? 'required' : ''; ?>>
                        <span class="file-help">Upload the technology logo as an image file.</span>
                    </label>
                    <?php if (!empty($editing['logo'])): ?>
                        <div class="upload-preview logo-preview">
                            <span>Current logo</span>
                            <img src="<?php echo htmlspecialchars($editing['logo']); ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <label>Category Sort <input type="number" name="category_sort" value="<?php echo htmlspecialchars((string)($editing['category_sort'] ?? 0)); ?>"></label>
                    <label>Sort Order <input type="number" name="sort_order" value="<?php echo htmlspecialchars((string)($editing['sort_order'] ?? 0)); ?>"></label>
                    <label class="check"><input type="checkbox" name="is_active" <?php echo (int)($editing['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label>
                    <button class="admin-button" type="submit">Submit</button>
                </form>
            <?php else: ?>
                <section class="admin-page-head"><h1>Technologies</h1><a class="admin-button" href="/admin?section=technologies&mode=add">Add New</a></section>
                <?php $rows = adminQuery('SELECT * FROM technologies ORDER BY category_sort, sort_order, id'); include __DIR__ . '/admin_table_technologies.php'; ?>
            <?php endif; ?>
        <?php elseif ($section === 'enquiries'): ?>
            <section class="admin-page-head"><h1>Enquiry</h1></section>
            <?php $rows = adminQuery('SELECT * FROM enquiries ORDER BY id DESC'); include __DIR__ . '/admin_table_enquiries.php'; ?>
        <?php elseif ($section === 'testimonials'): ?>
            <?php
            $editing = $mode === 'edit' && $id ? (adminQuery('SELECT * FROM testimonials WHERE id = ?', [$id])[0] ?? null) : null;
            if ($mode === 'add' || $editing):
            ?>
                <section class="admin-page-head"><h1><?php echo $editing ? 'Edit Testimonial' : 'Add Testimonial'; ?></h1></section>
                <form class="admin-form" method="post">
                    <input type="hidden" name="section" value="testimonials">
                    <input type="hidden" name="id" value="<?php echo (int)($editing['id'] ?? 0); ?>">
                    <label class="full">Quote* <textarea name="quote" required><?php echo htmlspecialchars($editing['quote'] ?? ''); ?></textarea></label>
                    <label>Person Name* <input name="person_name" required value="<?php echo htmlspecialchars($editing['person_name'] ?? ''); ?>"></label>
                    <label>Company* <input name="company" required value="<?php echo htmlspecialchars($editing['company'] ?? ''); ?>"></label>
                    <label>Sort Order <input type="number" name="sort_order" value="<?php echo htmlspecialchars((string)($editing['sort_order'] ?? 0)); ?>"></label>
                    <label class="check"><input type="checkbox" name="is_active" <?php echo (int)($editing['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label>
                    <button class="admin-button" type="submit">Submit</button>
                </form>
            <?php else: ?>
                <section class="admin-page-head"><h1>View Testimonials</h1><a class="admin-button" href="/admin?section=testimonials&mode=add">Add New</a></section>
                <?php $rows = adminQuery('SELECT * FROM testimonials ORDER BY sort_order, id'); include __DIR__ . '/admin_table_testimonials.php'; ?>
            <?php endif; ?>
        <?php elseif ($section === 'blogs'): ?>
            <?php
            $editing = $mode === 'edit' && $id ? (adminQuery('SELECT * FROM blogs WHERE id = ?', [$id])[0] ?? null) : null;
            if ($mode === 'add' || $editing):
            ?>
                <section class="admin-page-head"><h1><?php echo $editing ? 'Edit Blog' : 'Add Blog'; ?></h1></section>
                <form class="admin-form" method="post">
                    <input type="hidden" name="section" value="blogs">
                    <input type="hidden" name="id" value="<?php echo (int)($editing['id'] ?? 0); ?>">
                    <label class="full">Title* <input name="title" required value="<?php echo htmlspecialchars($editing['title'] ?? ''); ?>"></label>
                    <label>Published Date* <input type="date" name="published_date" required value="<?php echo htmlspecialchars($editing['published_date'] ?? date('Y-m-d')); ?>"></label>
                    <label>Sort Order <input type="number" name="sort_order" value="<?php echo htmlspecialchars((string)($editing['sort_order'] ?? 0)); ?>"></label>
                    <label class="check"><input type="checkbox" name="is_active" <?php echo (int)($editing['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label>
                    <button class="admin-button" type="submit">Submit</button>
                </form>
            <?php else: ?>
                <section class="admin-page-head"><h1>View Blogs</h1><a class="admin-button" href="/admin?section=blogs&mode=add">Add New</a></section>
                <?php $rows = adminQuery('SELECT * FROM blogs ORDER BY sort_order, published_date DESC, id'); include __DIR__ . '/admin_table_blogs.php'; ?>
            <?php endif; ?>
        <?php else: ?>
            <section class="admin-page-head"><h1>Web Appearance</h1></section>
            <section class="admin-card appearance-card">
                <h2>Silinex Brand System</h2>
                <p>This admin page follows the same navy, cyan, silver, and white brand direction used by the public website.</p>
                <div class="swatches"><span></span><span></span><span></span><span></span></div>
            </section>
        <?php endif; ?>

        <footer class="admin-footer">Copyright 2026. All Rights Reserved.</footer>
    </main>

    <script>
        const clock = document.querySelector('[data-clock]');
        setInterval(() => {
            if (clock) clock.textContent = new Date().toString();
        }, 1000);

        const normalize = (value) => value.toLowerCase().trim();

        document.querySelectorAll('.table-head input[type="search"]').forEach((input) => {
            input.addEventListener('input', () => {
                const card = input.closest('.admin-card');
                const rows = card ? card.querySelectorAll('tbody tr') : [];
                const query = normalize(input.value);
                let visibleCount = 0;

                rows.forEach((row) => {
                    const hidden = query !== '' && !normalize(row.textContent).includes(query);
                    row.hidden = hidden;
                    if (!hidden) visibleCount++;
                });

                if (card) {
                    card.classList.toggle('has-no-results', rows.length > 0 && visibleCount === 0);
                }
            });
        });

        const globalSearch = document.querySelector('[data-admin-global-search]');
        if (globalSearch) {
            globalSearch.addEventListener('input', () => {
                const query = normalize(globalSearch.value);

                document.querySelectorAll('.admin-nav a').forEach((link) => {
                    link.hidden = query !== '' && !normalize(link.textContent).includes(query);
                });

                document.querySelectorAll('.metric-card, .admin-card tbody tr').forEach((item) => {
                    item.hidden = query !== '' && !normalize(item.textContent).includes(query);
                });

                document.querySelectorAll('.admin-card').forEach((card) => {
                    const rows = card.querySelectorAll('tbody tr');
                    if (!rows.length) return;
                    const visibleRows = Array.from(rows).filter((row) => !row.hidden);
                    card.classList.toggle('has-no-results', query !== '' && visibleRows.length === 0);
                });
            });
        }

        function tableToMatrix(table) {
            const rows = Array.from(table.querySelectorAll('tr')).filter((row) => !row.hidden);
            return rows.map((row) => Array.from(row.children)
                .filter((cell) => !cell.hidden)
                .map((cell) => cell.innerText.replace(/\s+/g, ' ').trim()));
        }

        function downloadFile(filename, content, mimeType) {
            const blob = new Blob([content], { type: mimeType });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(url);
        }

        function currentTable(button) {
            const card = button.closest('.admin-card');
            return card ? card.querySelector('table') : null;
        }

        document.querySelectorAll('.table-tools button').forEach((button) => {
            button.type = 'button';
            button.addEventListener('click', async () => {
                const action = button.textContent.trim().toLowerCase();
                const table = currentTable(button);
                if (!table) return;

                const matrix = tableToMatrix(table);
                const title = document.querySelector('.admin-page-head h1, .table-head h2')?.textContent.trim() || 'admin-table';
                const safeTitle = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'admin-table';

                if (action === 'copy') {
                    const text = matrix.map((row) => row.join('\t')).join('\n');
                    await navigator.clipboard.writeText(text);
                    button.textContent = 'Copied';
                    window.setTimeout(() => button.textContent = 'Copy', 1200);
                    return;
                }

                if (action === 'excel') {
                    const csv = matrix.map((row) => row.map((cell) => `"${cell.replace(/"/g, '""')}"`).join(',')).join('\n');
                    downloadFile(`${safeTitle}.csv`, csv, 'text/csv;charset=utf-8');
                    return;
                }

                if (action === 'pdf') {
                    const printWindow = window.open('', '_blank', 'width=1100,height=760');
                    if (!printWindow) return;
                    const rows = matrix.map((row, index) => {
                        const tag = index === 0 ? 'th' : 'td';
                        return `<tr>${row.map((cell) => `<${tag}>${cell}</${tag}>`).join('')}</tr>`;
                    }).join('');
                    printWindow.document.write(`<!doctype html><html><head><title>${title}</title><style>
                        body{font-family:Arial,sans-serif;margin:24px;color:#111827}
                        h1{font-size:24px;margin:0 0 18px}
                        table{width:100%;border-collapse:collapse}
                        th,td{border:1px solid #dce6f0;padding:10px;text-align:left;font-size:13px}
                        th{background:#eef4fa}
                    </style></head><body><h1>${title}</h1><table>${rows}</table><script>window.onload=()=>window.print();<\/script></body></html>`);
                    printWindow.document.close();
                    return;
                }

                if (action.startsWith('column visibility')) {
                    const existing = button.parentElement.querySelector('.column-menu');
                    if (existing) {
                        existing.remove();
                        return;
                    }

                    document.querySelectorAll('.column-menu').forEach((menu) => menu.remove());
                    const menu = document.createElement('div');
                    menu.className = 'column-menu';
                    Array.from(table.querySelectorAll('thead th')).forEach((heading, index) => {
                        const label = document.createElement('label');
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.checked = !heading.hidden;
                        checkbox.addEventListener('change', () => {
                            table.querySelectorAll('tr').forEach((row) => {
                                if (row.children[index]) row.children[index].hidden = !checkbox.checked;
                            });
                        });
                        label.append(checkbox, document.createTextNode(heading.innerText.trim()));
                        menu.appendChild(label);
                    });
                    button.parentElement.appendChild(menu);
                }
            });
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('.table-tools')) {
                document.querySelectorAll('.column-menu').forEach((menu) => menu.remove());
            }
        });
    </script>
</body>
</html>
