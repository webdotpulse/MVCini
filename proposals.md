# MVCini Improvement Proposals

This document outlines several proposals for improving the MVCini framework. Each proposal includes a description of the feature and a prompt that can be used to request its implementation by an AI assistant.

## 1. Middleware Support for Routing
**Description:** Currently, the Router directly instantiates controllers and calls methods. Adding middleware support would allow filtering HTTP requests entering the application (e.g., authentication checks, logging, CORS) before they reach the controller.
**Prompt:** `Implement a Middleware system for the Router. Middleware should be able to intercept requests before they reach the controller. Add an interface for Middleware and update the Router to accept and process an array of middleware classes for specific routes or globally.`

## 2. Dependency Injection (DI) Container
**Description:** The Router currently uses `new $controllerClass()` to instantiate controllers. Implementing a DI container would allow for auto-wiring dependencies into controllers and other classes via their constructors, improving testability and separation of concerns.
**Prompt:** `Create a lightweight Dependency Injection (DI) Container. The container should be able to register services and automatically resolve dependencies (auto-wiring) for Controller constructors in the Router.`

## 3. Database Query Builder Enhancements
**Description:** The base `Model` provides basic CRUD operations. Adding a Query Builder would allow for more complex queries (e.g., `WHERE`, `JOIN`, `ORDER BY`, `LIMIT`) using a fluent interface, without writing raw SQL.
**Prompt:** `Enhance the Database and Model classes by adding a fluent Query Builder. It should support chaining methods like ->where(), ->join(), ->orderBy(), and ->limit() to build complex SQL queries programmatically while continuing to strictly use PDO.`

## 4. Form Validation Helper
**Description:** Controller logic can become cluttered with data validation. A dedicated Validation class or helper would streamline checking POST data for required fields, minimum/maximum lengths, email formats, etc., and returning structured errors.
**Prompt:** `Create a robust Form Validation class. It should allow defining rules (e.g., required, email, min:5) for an array of input data, validate the data against those rules, and provide an easy way to retrieve validation error messages for the views.`

## 5. View Template Engine Enhancements
**Description:** Views currently use raw PHP (`extract($data)`). Implementing a simple, native PHP template engine with custom syntax (e.g., `{{ var }}` for safe output, `@if` statements) would make views cleaner and automatically handle XSS escaping.
**Prompt:** `Implement a lightweight Template Engine for the Views. It should parse syntax like {{ variable }} into htmlspecialchars($variable) and support basic control structures like @foreach and @if to make view files cleaner. Update the Controller's render method to use this engine.`

## 6. Database Migration System
**Description:** The project currently uses `database.sql` and an interactive installer. A migration system would allow version controlling database schema changes in PHP classes (`up()` and `down()` methods), making it easier to evolve the database over time.
**Prompt:** `Build a database migration system. Create a CLI script that can run pending migrations. Migrations should be PHP classes with up() and down() methods to modify the database schema, keeping track of executed migrations in a database table.`


## 8. Command Line Interface (CLI) Tool
**Description:** A CLI tool (similar to Artisan or Symfony Console) to quickly generate boilerplate code for Controllers, Models, and Views, improving developer productivity.
**Prompt:** `Create a CLI tool (e.g., a "console" PHP script in the root). It should support commands like "make:controller Name", "make:model Name", and "make:view Name" to automatically generate the necessary file stubs in the correct directories.`

## 9. Automated Testing Suite Setup
**Description:** The project currently lacks automated tests. Setting up PHPUnit and creating base test cases for Controllers and Models would encourage test-driven development and improve framework stability.
**Prompt:** `Set up an automated testing suite using PHPUnit. Configure phpunit.xml, create a tests/ directory, and write a base TestCase class. Add at least two example tests: one for the Router dispatching and one for Model CRUD operations using an in-memory SQLite database or a test MySQL database.`

## 10. Environment Variables (.env) Support
**Description:** Configuration is currently handled via a generated `config.php` array. Supporting `.env` files would follow modern best practices for managing environment-specific secrets and configurations outside of version control.
**Prompt:** `Implement support for .env files. Create a lightweight parser for .env files in the Core directory, and update the application initialization to load these variables into $_ENV or a Config class. Update the installation script and README to reflect this change.`
