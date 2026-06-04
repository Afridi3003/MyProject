$env:DB_DRIVER = "pgsql"
$env:DB_HOST = "db.qdrlzqbbibvxrujnoung.supabase.co"
$env:DB_PORT = "5432"
$env:DB_NAME = "postgres"
$env:DB_USER = "postgres"
$env:DB_PASS = "your-supabase-database-password"
$env:DB_SSLMODE = "require"
$env:SUPABASE_URL = "https://qdrlzqbbibvxrujnoung.supabase.co"
$env:ADMIN_USERNAME = "Afridi_03"
$env:ADMIN_PASSWORD = "your-admin-password"

php -S localhost:8000 router.php
