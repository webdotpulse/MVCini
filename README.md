# MVCini

A custom, lightweight MVC framework built with native PHP 8 and MySQL, adhering to PSR coding guidelines.
It uses strictly native PHP code to avoid external framework dependencies (except specifically requested libraries via Composer).

## Features

- **MVC Architecture:** Separation of concerns using Models, Views, and Controllers.
- **Routing & Front Controller:** Clean URLs using a single entry point (`public/index.php`) and a custom Router.
- **Database Wrapper:** Secure PDO-based database interaction.
- **Security Mechanisms:**
  - CSRF Token generation and verification.
  - Encrypted cookies using OpenSSL (AES-256-CBC).
  - Session hijacking protection (validates IP and User-Agent fingerprint).
- **Internationalization (i18n):** Support for multiple languages (e.g., EN, ES).
- **Autoloading:** Native PSR-4 autoloader for core files and integration with Composer for third-party libraries.
- **Authentication System:** Boilerplate views and controllers for Login, Registration, Password Reset.

## Manual

### Requirements

- PHP 8.0+
- MySQL/MariaDB
- Composer (optional, for third-party dependencies)
- Apache (with mod_rewrite) or Nginx

### Installation

1. **Clone the repository:**
   ```bash
   git clone <repository_url>
   cd <repository_directory>
   ```

2. **Install Composer dependencies:**
   ```bash
   composer install
   ```

3. **Database Setup:**
   Import `database.sql` into your MySQL server to set up the default schema.

4. **Configuration:**
   Create a configuration file (e.g., `config/config.php`) setting up your database credentials and other settings like `app_secret` and `default_lang`.

### Running the Application

#### Local Development Server
The local development server should use the `public/` directory as the document root, as it acts as the front controller.

Run the following command from the root of the project:
```bash
php -S localhost:8000 -t public/
```
Then open `http://localhost:8000` in your browser.

#### Apache Setup
You can host the project on an Apache server. An `.htaccess` file in the root directory will automatically forward requests to the `public/` folder. Ensure that `mod_rewrite` is enabled.
