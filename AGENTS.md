# AI Agent Guidelines

## General instructions

- This project is a custom, lightweight MVC framework built with native PHP 8 and MySQL, adhering to PSR coding guidelines.
- The local development server should use the `public/` directory as the document root (e.g., `php -S localhost:8000 -t public/`), as it acts as the front controller.
- The project utilizes a custom native PSR-4 autoloader for core application files alongside Composer for managing external third-party libraries.
- Use strictly native PHP code to avoid external framework dependencies, except for specifically requested libraries.
- All database interactions must exclusively use PDO.

## Documentation Management

- **CRITICAL:** Every time a new feature is added, or an existing feature is modified or removed, you **must** update `README.md` automatically to reflect these changes.
- Maintain the "Features" overview and "Manual" instructions in `README.md` to ensure they are always up-to-date and accurately describe the current state of the application.
- **CRITICAL:** The interactive installer (`public/install.php`) must be updated in case breaking changes are made (e.g. database schema changes, new configuration options).
