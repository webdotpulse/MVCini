# MVCini Improvement Proposals

This document outlines several proposals for improving the MVCini framework. Each proposal includes a description of the feature and a prompt that can be used to request its implementation by an AI assistant.

## 1. Middleware Support for Routing
**Description:** Currently, the Router directly instantiates controllers and calls methods. Adding middleware support would allow filtering HTTP requests entering the application (e.g., authentication checks, logging, CORS) before they reach the controller.
**Prompt:** `Implement a Middleware system for the Router. Middleware should be able to intercept requests before they reach the controller. Add an interface for Middleware and update the Router to accept and process an array of middleware classes for specific routes or globally.`

## 2. Dependency Injection (DI) Container
**Description:** The Router currently uses `new $controllerClass()` to instantiate controllers. Implementing a DI container would allow for auto-wiring dependencies into controllers and other classes via their constructors, improving testability and separation of concerns.
**Prompt:** `Create a lightweight Dependency Injection (DI) Container. The container should be able to register services and automatically resolve dependencies (auto-wiring) for Controller constructors in the Router.`

## 3. View Template Engine Enhancements
**Description:** Views currently use raw PHP (`extract($data)`). Implementing a simple, native PHP template engine with custom syntax (e.g., `{{ var }}` for safe output, `@if` statements) would make views cleaner and automatically handle XSS escaping.
**Prompt:** `Implement a lightweight Template Engine for the Views. It should parse syntax like {{ variable }} into htmlspecialchars($variable) and support basic control structures like @foreach and @if to make view files cleaner. Update the Controller's render method to use this engine.`

## 4. Database Migration System
**Description:** The project currently uses `database.sql` and an interactive installer. A migration system would allow version controlling database schema changes in PHP classes (`up()` and `down()` methods), making it easier to evolve the database over time.
**Prompt:** `Build a database migration system. Create a CLI script that can run pending migrations. Migrations should be PHP classes with up() and down() methods to modify the database schema, keeping track of executed migrations in a database table.`

## 5. Command Line Interface (CLI) Tool
**Description:** A CLI tool (similar to Artisan or Symfony Console) to quickly generate boilerplate code for Controllers, Models, and Views, improving developer productivity.
**Prompt:** `Create a CLI tool (e.g., a "console" PHP script in the root). It should support commands like "make:controller Name", "make:model Name", and "make:view Name" to automatically generate the necessary file stubs in the correct directories.`

## 6. Automated Testing Suite Setup
**Description:** The project currently lacks automated tests. Setting up PHPUnit and creating base test cases for Controllers and Models would encourage test-driven development and improve framework stability.
**Prompt:** `Set up an automated testing suite using PHPUnit. Configure phpunit.xml, create a tests/ directory, and write a base TestCase class. Add at least two example tests: one for the Router dispatching and one for Model CRUD operations using an in-memory SQLite database or a test MySQL database.`

## 9. Caching System
**Description:** The framework currently lacks a built-in caching mechanism. Implementing a simple file-based or Memcached/Redis caching system would significantly improve application performance for expensive database queries or rendered views.
**Prompt:** `Build a simple Caching system. Start with a file-based cache driver that allows storing, retrieving, and deleting key-value pairs with an expiration time. Create an interface so other drivers (like Redis) can be easily added later.`

## 10. Event Dispatcher
**Description:** Implementing an event-driven architecture allows components to communicate without being tightly coupled. An Event Dispatcher would let the application fire events and listeners to react to them (e.g., sending a welcome email when a UserRegistered event occurs).
**Prompt:** `Create a lightweight Event Dispatcher. It should support registering event listeners (callbacks or classes) to specific event names and a dispatch method to trigger those events and pass relevant data to the listeners.`