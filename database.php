<?php
require_once __DIR__ . '/config.php';

function silinexDb(): ?PDO
{
    static $pdo = null;
    static $attempted = false;

    if ($attempted) {
        return $pdo;
    }

    $attempted = true;
    if (DB_DRIVER === 'pgsql') {
        $dsn = 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';sslmode=' . DB_SSLMODE;
    } else {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    }

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (Throwable $error) {
        $pdo = null;
    }

    return $pdo;
}

function silinexFetchAll(string $sql, array $params = []): array
{
    $pdo = silinexDb();

    if (!$pdo) {
        return [];
    }

    try {
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    } catch (Throwable $error) {
        return [];
    }
}

function silinexJsonList(?string $json): array
{
    if (!$json) {
        return [];
    }

    $decoded = json_decode($json, true);
    return is_array($decoded) ? $decoded : [];
}

function loadSilinexSiteData(array $fallback): array
{
    $data = $fallback;

    $services = silinexFetchAll(
        'SELECT slug, title, description, icon, image FROM services WHERE is_active = 1 ORDER BY sort_order, id'
    );
    if ($services) {
        $data['services'] = array_map(function (array $service): array {
            $details = silinexFetchAll(
                'SELECT detail FROM service_details WHERE service_slug = ? ORDER BY sort_order, id',
                [$service['slug']]
            );

            return [
                'title' => $service['title'],
                'text' => $service['description'],
                'icon' => $service['icon'],
                'image' => $service['image'],
                'slug' => $service['slug'],
                'details' => array_column($details, 'detail'),
            ];
        }, $services);
    }

    $servicePages = silinexFetchAll(
        'SELECT service_slug, subtitle, hero_image, hero_alignment, focus, offer_json, offer_image, offer_alignment, engagement_json, why_json, why_image, why_alignment FROM service_pages ORDER BY id'
    );
    if ($servicePages) {
        $data['servicePages'] = [];
        foreach ($servicePages as $page) {
            $data['servicePages'][$page['service_slug']] = [
                'subtitle' => $page['subtitle'],
                'heroImage' => $page['hero_image'],
                'heroAlignment' => $page['hero_alignment'] ?: 'center',
                'focus' => $page['focus'],
                'offer' => silinexJsonList($page['offer_json']),
                'offerImage' => $page['offer_image'] ?: '',
                'offerAlignment' => $page['offer_alignment'] ?: 'image-right',
                'engagement' => silinexJsonList($page['engagement_json']),
                'why' => silinexJsonList($page['why_json']),
                'whyImage' => $page['why_image'] ?: '',
                'whyAlignment' => $page['why_alignment'] ?: 'image-left',
            ];
        }
    }

    $industries = silinexFetchAll(
        'SELECT industry_key, tab, tab_icon, category, heading, description, features_json, image, metrics_json FROM industries WHERE is_active = 1 ORDER BY sort_order, id'
    );
    if ($industries) {
        $fallbackById = [];
        foreach ($data['industries'] as $fallbackIndustry) {
            if (isset($fallbackIndustry['id'])) {
                $fallbackById[$fallbackIndustry['id']] = $fallbackIndustry;
            }
        }

        $data['industries'] = array_map(function (array $industry) use ($fallbackById): array {
            $fallbackIndustry = $fallbackById[$industry['industry_key']] ?? [];
            $features = silinexJsonList($industry['features_json']);
            $metrics = silinexJsonList($industry['metrics_json']);

            return [
                'id' => $industry['industry_key'],
                'tab' => $industry['tab'],
                'tab_icon' => $industry['tab_icon'] ?: ($fallbackIndustry['tab_icon'] ?? ''),
                'category' => $industry['category'],
                'heading' => $industry['heading'],
                'description' => $industry['description'],
                'features' => $features ?: ($fallbackIndustry['features'] ?? []),
                'image' => $industry['image'],
                'metrics' => $metrics ?: ($fallbackIndustry['metrics'] ?? []),
            ];
        }, $industries);
    }

    $technologyRows = silinexFetchAll(
        'SELECT category, name, logo FROM technologies WHERE is_active = 1 ORDER BY category_sort, sort_order, id'
    );
    if ($technologyRows) {
        $data['technologyGroups'] = [];
        foreach ($technologyRows as $technology) {
            $data['technologyGroups'][$technology['category']][] = [
                'name' => $technology['name'],
                'logo' => $technology['logo'],
            ];
        }
    }

    foreach (['featured' => 'partners', 'technology' => 'technologyPartners', 'alliance' => 'strategicAlliances'] as $type => $key) {
        $partners = silinexFetchAll(
            'SELECT name, logo FROM partners WHERE partner_type = ? AND is_active = 1 ORDER BY sort_order, id',
            [$type]
        );
        if ($partners) {
            $data[$key] = $partners;
        }
    }

    $testimonials = silinexFetchAll(
        'SELECT quote, person_name, company FROM testimonials WHERE is_active = 1 ORDER BY sort_order, id'
    );
    if ($testimonials) {
        $data['testimonials'] = array_map(function (array $testimonial): array {
            return [
                'quote' => $testimonial['quote'],
                'name' => $testimonial['person_name'],
                'company' => $testimonial['company'],
            ];
        }, $testimonials);
    }

    $faqs = silinexFetchAll(
        'SELECT question, answer FROM faqs WHERE is_active = 1 ORDER BY sort_order, id'
    );
    if ($faqs) {
        $data['faqs'] = [];
        foreach ($faqs as $faq) {
            $data['faqs'][$faq['question']] = $faq['answer'];
        }
    }

    $blogs = silinexFetchAll(
        'SELECT title, published_date FROM blogs WHERE is_active = 1 ORDER BY sort_order, published_date DESC, id'
    );
    if ($blogs) {
        $data['blogs'] = array_map(function (array $blog): array {
            return [
                'title' => $blog['title'],
                'date' => date('d/m/Y', strtotime($blog['published_date'])),
            ];
        }, $blogs);
    }

    return $data;
}
