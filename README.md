# MVCini

A custom, lightweight MVC framework built with native PHP 8 and MySQL, adhering to PSR coding guidelines.
It uses strictly native PHP code to avoid external framework dependencies (except specifically requested libraries via Composer).

## Features

- **MVC Architecture:** Separation of concerns using Models, Views, and Controllers.
- **Routing & Front Controller:** Clean URLs using a single entry point (`public/index.php`) and a custom Router.
- **Database Wrapper:** Secure PDO-based database interaction featuring a fluent Query Builder for constructing SQL queries programmatically (`where()`, `join()`, `orderBy()`, etc.).
- **Security Mechanisms:**
  - CSRF Token generation and verification.
  - Encrypted cookies using OpenSSL (AES-256-CBC).
  - Session hijacking protection (validates IP and User-Agent fingerprint).
- **Internationalization (i18n):** Support for multiple languages (EN, ES, FR, NL) and an Admin UI for managing translations.
- **Modern UI:** Tailwind CSS is used on the demo home page for rapid UI prototyping.
- **Autoloading:** Native PSR-4 autoloader for core files and integration with Composer for third-party libraries.
- **Authentication System:** Boilerplate views and controllers for Login, Registration, Password Reset.
- **Built-in CMS Features:** Includes fully functional, translatable pages and administrative CRUD sections for:
  - FAQ
  - Knowledge Base
  - Downloads
  - Products
  - Jobs
  - About Us
- **Interactive Installer:** An automatic installer that sets up the database, imports schema, generates configuration, and securely deletes itself on first run.
- **Form Validation:** A robust `FormValidation` helper class to validate request data easily within controllers.
- **Pagination:** Integrated database pagination returning records and metadata, along with a Tailwind-compatible View helper.
- **Logging System:** Basic Logging system that writes messages to daily rotating files in a `storage/logs/` directory, supporting standard log levels (e.g., info, warning, error, debug).

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

3. **Installation (Recommended):**
   Start the local development server (or set up Apache/Nginx) and open the application in your browser. If it is your first time running it (i.e. `.env` doesn't exist), you will be redirected to an interactive installer that will automatically configure your database connection, import the default schema, and generate the `.env` configuration file.

4. **Manual Installation (Alternative):**
   - Import `database.sql` into your MySQL server to set up the default schema.
   - Copy the example environment file to create your own configuration:
     ```bash
     cp .env.example .env
     ```
   - Then edit `.env` and set up your database credentials and other settings like `APP_SECRET` and `DEFAULT_LANG`.

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

### Form Validation Example

You can use the `App\Core\FormValidation` helper class to easily validate POST or GET data within your controllers.

```php
use App\Core\FormValidation;

$data = $_POST;
$rules = [
    'username' => 'required|alphanumeric|min:3|max:20',
    'email'    => 'required|email',
    'password' => 'required|min:6',
    'password_confirm' => 'required|match:password',
    'age'      => 'numeric|min:18'
];

$validator = FormValidation::validate($data, $rules);

if ($validator->fails()) {
    // Pass errors to the view
    $errors = $validator->errors();
    // Or get a single error: $validator->first('username')
    $this->render('register', ['errors' => $errors]);
} else {
    // Validation passed, proceed with registration
}
```
