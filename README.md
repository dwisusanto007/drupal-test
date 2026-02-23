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
- Web server (PHP + Apache/Nginx)
- MySQL database
- Mailhog (for email testing)

### 4. Install Drupal

#### Option A: Using the Installer

1. Open your browser and navigate to:
   ```
   https://drupal-test.ddev.site
   ```

2. Follow the installation wizard:
   - Choose "Standard" profile
   - Select language (English)
   - Database settings:
     - Database name: `db`
     - Database username: `db`
     - Database password: `db`
   - Site settings:
     - Site name: `Drupal Test`
     - Site email: `admin@example.com`
     - Username: `admin`
     - Password: `admin` (change in production!)
     - Default country: Your country
     - Default timezone: Your timezone

#### Option B: Using Drush (Command Line)

```bash
ddev drush site:install standard --account-name=admin --account-pass=admin --db-url=mysql://db:db@db/db -y
```

### 5. Access the Site

- **URL**: https://drupal-test.ddev.site
- **Admin URL**: https://drupal-test.ddev.site/user/login

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

# Open Mailhog
ddev mailhog
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
ddev drush dis module_name
```

### Composer Commands

```bash
# Add a dependency
composer require drupal/module_name

# Update dependencies
composer update

# Install Drupal via composer
composer require drupal/core:^11 --no-update
```

## Project Structure

```
.
├── .ddev/              # DDEV configuration
├── web/                # Drupal web root
│   ├── core/           # Drupal core
│   ├── modules/        # Custom modules
│   ├── profiles/       # Installation profiles
│   ├── sites/         # Site configuration
│   └── themes/        # Custom themes
├── composer.json       # Composer dependencies
└── .gitignore         # Git ignore rules
```

## Troubleshooting

### Database Connection Issues

If you can't connect to the database, ensure DDEV is running:

```bash
ddev start
```

### SSL Certificate Issues

DDEV uses self-signed certificates. You may need to accept the certificate in your browser or run:

```bash
ddev auth ssh
```

### Port Conflicts

If port 80 or 443 is already in use, stop other services or use:

```bash
ddev config --port HTTPS_PORT=8443
```

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

## License

This project is licensed under the GPL v2 or later. See the [LICENSE.txt](LICENSE.txt) file for details.
