# Drupal 11 Test Project

A Drupal 11 installation configured with DDEV for local development.

## Requirements

- [Docker](https://www.docker.com/)
- [DDEV](https://ddev.com/)
- [Composer](https://getcomposer.org/)

## Quick Start

### 1. Clone the Repository

```bash
git clone https://github.com/dwisusanto007/drupal-test.git
cd drupal-test
```

### 2. Install Dependencies

If you don't have the vendor directory, install it with Composer:

```bash
composer install
```

### 3. Start DDEV

```bash
ddev start
```

This will start the following services:
- Web server (PHP 8.3 + Nginx-FPM)
- MariaDB 10.11 database
- Mailpit (for email testing)

### 4. Import Database

A pre-existing database dump is included at `database/drupal_dump.sql`. Import it to skip the Drupal installer:

```bash
ddev import-db --file=database/drupal_dump.sql
```

After importing, clear the cache:

```bash
ddev drush cr
```

Then access the site at:
- **URL**: https://drupal-test.ddev.site
- **Admin URL**: https://drupal-test.ddev.site/user/login

### 5. Install Drupal (Fresh Install — Skip if Using Dump)

#### Option A: Using the Installer

1. Open your browser and navigate to:
   ```
   https://drupal-test.ddev.site
   ```

2. Follow the installation wizard:
   - Choose "Standard" profile
   - Select language (English)
   - Database settings (pre-filled by DDEV):
     - Database name: `db`
     - Database username: `db`
     - Database password: `db`
   - Site settings:
     - Site name: `Drupal Test`
     - Site email: `admin@example.com`
     - Username: `admin`
     - Password: `admin` (change in production!)

#### Option B: Using Drush (Command Line)

```bash
ddev drush site:install standard --account-name=admin --account-pass=admin --db-url=mysql://db:db@db/db -y
```

### 6. Access the Site

- **URL**: https://drupal-test.ddev.site
- **Admin URL**: https://drupal-test.ddev.site/user/login

---

## Database

### Database Info

| Field    | Value          |
|----------|----------------|
| Engine   | MariaDB 10.11  |
| Database | `db`           |
| Username | `db`           |
| Password | `db`           |
| Host     | `db` (inside DDEV) / `127.0.0.1:32772` (host) |

### Dump File

| Field     | Value                          |
|-----------|--------------------------------|
| File      | `database/drupal_dump.sql`     |
| Format    | Plain SQL (MariaDB dump)       |
| Size      | ~8 MB                          |
| Tables    | 84 tables                      |
| Generated | MariaDB 10.11.10 via DDEV      |

#### Key Tables

| Table Group  | Tables |
|--------------|--------|
| Content      | `node`, `node_field_data`, `node__body`, `node__field_image`, `node__field_tags`, `node__field_category` |
| Taxonomy     | `taxonomy_term_data`, `taxonomy_term_field_data`, `taxonomy_index` |
| Events       | `node__field_event_category`, `node__field_event_date`, `node__field_event_description`, `node__field_event_image` |
| News         | `node__field_news_description`, `node__field_news_image`, `node__field_publish_date` |
| Users        | `users`, `users_field_data`, `user__roles`, `user__user_picture` |
| Files        | `file_managed`, `file_usage` |
| Comments     | `comment`, `comment_field_data`, `comment__comment_body` |
| Menus        | `menu_link_content`, `menu_link_content_data`, `menu_tree` |
| Config       | `config`, `key_value`, `key_value_expire` |
| Cache        | `cache_bootstrap`, `cache_config`, `cache_data`, `cache_default`, `cache_discovery`, `cache_entity`, `cache_render` |

### Import Database

```bash
ddev import-db --file=database/drupal_dump.sql
```

### Export / Re-dump Database

```bash
ddev export-db --gzip=false --file=database/drupal_dump.sql
```

Or with gzip compression:

```bash
ddev export-db --file=database/drupal_dump.sql.gz
```

Using Drush:

```bash
ddev drush sql:dump --result-file=database/drupal_dump.sql
```

---

## Common Commands

### DDEV Commands

```bash
# Start the project
ddev start

# Stop the project
ddev stop

# Restart the project
ddev restart

# View project status
ddev describe

# Open phpMyAdmin
ddev phpma

# Open Mailpit
ddev mailpit
```

### Drush Commands

```bash
# Clear cache
ddev drush cr

# List available commands
ddev drush list

# Enable a module
ddev drush en module_name

# Disable a module
ddev drush pm-uninstall module_name

# Check site status
ddev drush status
```

### Composer Commands

```bash
# Install dependencies
composer install

# Add a dependency
composer require drupal/module_name

# Update dependencies
composer update

# Add Drupal core
composer require drupal/core:^11 --no-update
```

---

## Project Structure

```
.
├── .ddev/              # DDEV configuration
│   └── config.yaml     # DDEV settings (MariaDB 10.11, PHP 8.3, Nginx)
├── database/           # Database dumps
│   └── drupal_dump.sql # Full database dump (MariaDB, plain SQL)
├── web/                # Drupal web root
│   ├── core/           # Drupal core
│   ├── modules/        # Contrib & custom modules
│   ├── profiles/       # Installation profiles
│   ├── sites/          # Site configuration
│   └── themes/         # Contrib & custom themes
├── composer.json        # Composer dependencies
├── composer.lock        # Locked dependency versions
└── .gitignore          # Git ignore rules
```

---

## Troubleshooting

### Database Connection Issues

If you can't connect to the database, ensure DDEV is running:

```bash
ddev start
```

Check the DB container status:

```bash
ddev describe
```

### SSL Certificate Issues

DDEV uses self-signed certificates. You may need to accept the certificate in your browser or run:

```bash
mkcert -install
ddev auth ssh
```

### Port Conflicts

If port 80 or 443 is already in use, stop other services or reconfigure:

```bash
ddev config --router-http-port=8080 --router-https-port=8443
ddev restart
```

### Import Database Fails

Ensure DDEV is running and the dump file exists:

```bash
ddev start
ls -lh database/drupal_dump.sql
ddev import-db --file=database/drupal_dump.sql
```

---

## Development

### Adding Custom Modules

Place custom modules in `web/modules/custom/`:

```bash
mkdir -p web/modules/custom/my_module
```

### Adding Custom Themes

Place custom themes in `web/themes/custom/`:

```bash
mkdir -p web/themes/custom/my_theme
```

### Resetting the Database

To reset back to the included dump:

```bash
ddev import-db --file=database/drupal_dump.sql
ddev drush cr
```

---

## License

This project is licensed under the GPL v2 or later. See the [LICENSE.txt](LICENSE.txt) file for details.
