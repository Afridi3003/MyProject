--------------------------------------# Silinex Global Services PHP Website

This project is a PHP website for Silinex Global Services with a Supabase/PostgreSQL database connection. The public website and `/admin` CMS both read from PostgreSQL by default, and the site falls back to the PHP arrays in `index.php` only if the database is not reachable.

## Project Files

- `index.php` - Main PHP website file and fallback content arrays.
- `style.css` - Website layout, responsive UI, theme, and graphics.
- `config.php` - MySQL/Supabase database connection credentials.
- `database.php` - PDO database connection and content loader.
- `router.php` - Clean URL router for the PHP development server.
- `admin.php` - Admin/CMS dashboard for managing website content.
- `admin.css` - Admin dashboard layout and responsive styling.
- `schema.sql` - Database, tables, and starter data.
- `schema_supabase.sql` - Supabase/PostgreSQL tables for the same website/admin data.
- `test-postgres-connection.php` - CLI test for confirming the PostgreSQL/Supabase connection.
- `start-postgres-site.ps1` - PowerShell template for starting the website with PostgreSQL environment variables.
- `assets/silinex-logo.jpeg` - Company logo used in the website.
- `uploads/` - Stores images and logos uploaded from the admin dashboard.

## Database Connection Details

Default database settings are stored in `config.php`. The current default is Supabase/PostgreSQL:

```php
DB_DRIVER = pgsql
DB_HOST = db.qdrlzqbbibvxrujnoung.supabase.co
DB_PORT = 5432
DB_NAME = postgres
DB_USER = postgres
DB_SSLMODE = require
```

The PostgreSQL password is configured in `config.php` and can also be overridden with the `DB_PASS` environment variable.

For optional local MariaDB testing, use:

```php
DB_DRIVER = mysql
DB_HOST = 127.0.0.1
DB_PORT = 3310
DB_NAME = silinex_global
DB_USER = root
DB_PASS = empty password
DB_CHARSET = utf8mb4
```

## Supabase Connection

Supabase uses PostgreSQL. Your Supabase login email, `shaikafridi619@gmail.com`, is only for signing in to the Supabase dashboard; the PHP website cannot connect with the email alone. The site needs your Supabase database host, database password, and project connection details.

1. Log in to Supabase with:

```text
shaikafridi619@gmail.com
```

2. Open your Supabase project.

3. Go to `Project Settings > Database`.

4. Copy these values:

```text
Host: db.qdrlzqbbibvxrujnoung.supabase.co
Port: 5432
Database name: postgres
User: postgres
Password: your Supabase database password
SSL mode: require
```

5. In Supabase, open `SQL Editor` and run the file:

```text
schema_supabase.sql
```

6. The project now uses Supabase/PostgreSQL by default, so you can start the site normally:

```powershell
php -S localhost:8000 router.php
```

You can still override the database with environment variables when needed:

```powershell
$env:DB_DRIVER="pgsql"
$env:DB_HOST="db.qdrlzqbbibvxrujnoung.supabase.co"
$env:DB_PORT="5432"
$env:DB_NAME="postgres"
$env:DB_USER="postgres"
$env:DB_PASS="your-supabase-database-password"
$env:DB_SSLMODE="require"
php -S localhost:8000 router.php
```

Or edit `start-postgres-site.ps1` with your real Supabase database password, then run:

```powershell
.\start-postgres-site.ps1
```

To test the PostgreSQL connection before starting the website:

```powershell
$env:DB_DRIVER="pgsql"
$env:DB_HOST="db.qdrlzqbbibvxrujnoung.supabase.co"
$env:DB_PORT="5432"
$env:DB_NAME="postgres"
$env:DB_USER="postgres"
$env:DB_PASS="your-supabase-database-password"
$env:DB_SSLMODE="require"
php .\test-postgres-connection.php
```

The Supabase API URL and anon key can also be stored for future browser/API features:

```powershell
$env:SUPABASE_URL="https://qdrlzqbbibvxrujnoung.supabase.co"
$env:SUPABASE_ANON_KEY="your-anon-public-key"
```

For PHP database reads and admin CMS writes, the important settings are `DB_DRIVER`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, and `DB_SSLMODE`.

7. Make sure your PHP installation has the PostgreSQL PDO extension enabled:

```text
extension=pdo_pgsql
extension=pgsql
```

If PHP does not have `pdo_pgsql` enabled, Supabase/PostgreSQL connection will fail even if the credentials are correct.

## PostgreSQL Data Sync

The current Supabase/PostgreSQL database has been seeded with the same project CMS/site records used by the MariaDB starter database:

| Table | Rows |
| --- | ---: |
| `services` | 5 |
| `service_details` | 15 |
| `service_pages` | 5 |
| `industries` | 6 |
| `technologies` | 29 |
| `partners` | 12 |
| `testimonials` | 3 |
| `faqs` | 4 |
| `blogs` | 3 |
| `careers` | 3 |
| `news_categories` | 5 |
| `enquiries` | 3 |
| `admin_content_items` | 7 |

After this sync, `/admin` reads from PostgreSQL because `config.php` defaults to `DB_DRIVER = pgsql`.

For this project, MariaDB runs from the project-specific data folder `mysql-data` on port `3310`. This avoids conflicts with the Windows `MySQL80` service on port `3306` and the stuck XAMPP process that previously affected port `3307`.

```text
Username: root
Password: blank / empty
Database: silinex_global
Host: 127.0.0.1
Port: 3310
```

If your MySQL password is different, update this line in `config.php`:

```php
define('DB_PASS', getenv('DB_PASS') ?: '');
```

Example with password `root123`:

```php
define('DB_PASS', getenv('DB_PASS') ?: 'root123');
```

## Tables Created

The `schema.sql` file creates these tables:

| Table | Purpose |
| --- | --- |
| `services` | Stores service cards such as Staffing, Application Managed Services, GRC Services, Oracle Services, and Silinex Dummy Services. |
| `service_details` | Stores bullet points/details for each service. |
| `service_pages` | Stores detailed service page content including subtitle, hero image, focus text, offer list, engagement list, and why-choose list. |
| `industries` | Stores industry tabs and industry panel content. |
| `technologies` | Stores technology categories and technology logos. |
| `partners` | Stores featured partners, technology partners, and strategic alliances. |
| `testimonials` | Stores client testimonial cards. |
| `faqs` | Stores FAQ questions and answers. |
| `blogs` | Stores blog/insight titles and dates. |
| `careers` | Stores admin-managed career/job postings. |
| `news_categories` | Stores admin-managed news category names. |
| `enquiries` | Stores contact/enquiry submissions for the admin dashboard. |
| `admin_content_items` | Stores CMS-managed content for Slider, Home Industry, News, Technology Category, Why Work, Life, and Values. |
| `cms_versions` | Stores CMS version-history snapshots when admin records are created, updated, or deleted. |

## CMS Versions

The admin dashboard includes `Admin > CMS Versions` for version history. Whenever supported admin records are saved or deleted, the CMS stores a JSON snapshot with:

- Section key, such as `services`, `partners`, or `blogs`.
- Action type: `created`, `updated`, or `deleted`.
- Record label and record id.
- Admin user and timestamp.
- Snapshot JSON of the saved or deleted content.

This makes it easier to audit what changed in the CMS over time. Version logging is intentionally non-blocking, so normal CMS saving continues even if a version snapshot cannot be written.

## Admin Image Uploads

The admin dashboard uses file uploads for image-based sections instead of asking for image URLs.

Upload fields are available in:

- Services
- Slider
- Home Industry
- News
- Technology Category
- Technologies
- Why Work
- Life
- Values
- Partner Management

Uploaded files are saved in the project folder:

```text
uploads/
```

The database stores the saved local path, for example:

```text
/uploads/20260529144500-a1b2c3d4-service-photo.webp
```

When editing an existing record, the current image stays unchanged unless a new file is selected. Supported image formats are JPG, JPEG, PNG, WEBP, GIF, and SVG.

The Services editor also manages each service detail page. From `Admin > Services > Edit`, you can update:

- Detail page subtitle and focus text.
- Hero, offer, and why-choose images.
- What We Offer, Engagement Models, and Why Choose list content.
- Hero text alignment.
- Offer and Why Choose text/image alignment.

## Fixed MySQL Port Conflict

The MySQL log showed this error:

```text
Can't start server: Bind on TCP/IP port
Do you already have another mysqld server running on port: 3306 ?
```

That happened because Windows service `MySQL80` was already listening on `3306`. A later XAMPP MariaDB process also became stuck and did not listen correctly on `3307`. To keep this project reliable, the website now uses its own MariaDB data directory at `mysql-data` and runs on port `3310`.

Files/settings changed:

- `mysql-data\my.ini`
- `[client] port=3310`
- `[mysqld] port=3310`
- Website config: `config.php` uses `DB_PORT = 3310`
- PHP config: `C:\php\php.ini`
- Enabled extensions: `pdo_mysql` and `mysqli`
- PHP config backup created: `C:\php\php.ini.codex-backup`

To start the project database:

```powershell
.\start-silinex-db.ps1
```

To confirm MariaDB is running manually:

```powershell
C:\xampp\mysql\bin\mysqladmin.exe -h 127.0.0.1 -u root -P 3310 ping
```

Expected output:

```text
mysqld is alive
```

## How To Create And Connect The Database

1. Start the project MariaDB server:

```powershell
.\start-silinex-db.ps1
```

2. Open a terminal inside this project folder:

```powershell
cd C:\Users\afrid\Documents\Codex\2026-05-18\https-www-silinexglobal-com-the-above
```

3. Import the database:

```powershell
mysql -h 127.0.0.1 -u root -P 3310 < schema.sql
```

The project database uses the local `root` user with a blank password.

If you use XAMPP and `mysql` is not recognized, run it with the full path:

```powershell
------------------------------------------------------------ < schema.sql
```

4. Confirm that `config.php` matches your MySQL details:

```php
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3310');
define('DB_NAME', getenv('DB_NAME') ?: 'silinex_global');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');
```

5. Run the PHP website with the router file:

```powershell
php -S localhost:8000 router.php
```

The `router.php` file is required for clean URLs like `/grc-services` and `/partners`.

If the PHP server was already running before the database connection changes, stop it with `Ctrl+C` and start it again so PHP reloads `php.ini`.

6. Open the admin dashboard:

```text
http://localhost:8000/admin
```

The admin dashboard includes Dashboard, Slider, Home Industry, Services, Careers, News Category, News, Technology Category, Technologies, Why Work, Life, Values, Partner Management, Enquiry, and Web Appearance sections.

Admin login credentials are read from environment variables:

```text
ADMIN_USERNAME
ADMIN_PASSWORD
```

If `ADMIN_USERNAME` is not set, the local default username is `Afridi_03`. Set `ADMIN_PASSWORD` before starting the PHP server.

Use the `Logout` button in the admin top bar to close the admin session.

6. Open the website:

```text
http://localhost:8000
```

## Clean URL Format

The website no longer needs `index.php` in public links.

| Page | Clean URL |
| --- | --- |
| Home | `http://localhost:8000` |
| Staffing | `http://localhost:8000/staffing` |
| Application Managed Services | `http://localhost:8000/application-managed-services` |
| GRC Services | `http://localhost:8000/grc-services` |
| Oracle Services | `http://localhost:8000/oracle-services` |
| Partners & Alliances | `http://localhost:8000/partners` |

The router also supports `/service/service-slug` style URLs, but the website navigation uses the shorter service-name format.

## How The Website Uses The Database

`index.php` includes the database layer here:

```php
require_once __DIR__ . '/database.php';
```

Then it calls:

```php
$siteData = loadSilinexSiteData([...]);
```

The database loader reads rows from MySQL and replaces the fallback arrays for:

- Services
- Service details
- Service pages
- Industries
- Technologies
- Partners
- Testimonials
- FAQs
- Blogs

If MySQL is not connected, the website still works using the fallback arrays already present in `index.php`.

## Testing The PHP Files

Run these commands to check for PHP syntax errors:

```powershell
php -l index.php
php -l config.php
php -l database.php
```

Expected result:

```text
No syntax errors detected
```

## Important Notes

- Do not put CSS variables or CSS code inside `index.php`; CSS belongs in `style.css`.
- Import `schema.sql` again whenever you want to recreate or seed the database.
- `schema.sql` uses `CREATE TABLE IF NOT EXISTS`, so it is safe to run again.
- Some seed inserts use `INSERT IGNORE` or `ON DUPLICATE KEY UPDATE` to reduce duplicate data.
