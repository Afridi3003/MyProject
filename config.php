<?php
// Database settings for the Silinex website.
// Use DB_DRIVER=mysql for local MariaDB/MySQL.
// Use DB_DRIVER=pgsql for Supabase PostgreSQL.
// You can override every value with environment variables.
if (!defined('DB_DRIVER')) {
    define('DB_DRIVER', getenv('DB_DRIVER') ?: 'pgsql');
}

if (!defined('DB_HOST')) {
    define('DB_HOST', getenv('DB_HOST') ?: 'db.qdrlzqbbibvxrujnoung.supabase.co');
}

if (!defined('DB_PORT')) {
    define('DB_PORT', getenv('DB_PORT') ?: '5432');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', getenv('DB_NAME') ?: 'postgres');
}

if (!defined('DB_USER')) {
    define('DB_USER', getenv('DB_USER') ?: 'postgres');
}

if (!defined('DB_PASS')) {
    define('DB_PASS', getenv('DB_PASS') ?: '');
}

if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');
}

if (!defined('DB_SSLMODE')) {
    define('DB_SSLMODE', getenv('DB_SSLMODE') ?: 'require');
}

if (!defined('SUPABASE_URL')) {
    define('SUPABASE_URL', getenv('SUPABASE_URL') ?: 'https://qdrlzqbbibvxrujnoung.supabase.co');
}

if (!defined('SUPABASE_ANON_KEY')) {
    define('SUPABASE_ANON_KEY', getenv('SUPABASE_ANON_KEY') ?: '');
}
