<?php
namespace App\Core;

class Session
{
    /**
     * Start the session and manage flash messages
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Cycle flash messages
        if (isset($_SESSION['_flash_next'])) {
            $_SESSION['_flash'] = $_SESSION['_flash_next'];
            unset($_SESSION['_flash_next']);
        } else {
            $_SESSION['_flash'] = [];
        }
    }

    /**
     * Set a session variable
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if a session variable exists
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session variable
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Unset all session variables
     */
    public static function unset(): void
    {
        session_unset();
    }

    /**
     * Destroy the session
     */
    public static function destroy(): void
    {
        session_destroy();
    }

    /**
     * Regenerate session ID
     */
    public static function regenerate(bool $delete_old_session = false): bool
    {
        return session_regenerate_id($delete_old_session);
    }

    /**
     * Set a flash message for the next request
     */
    public static function setFlash(string $key, $value): void
    {
        $_SESSION['_flash_next'][$key] = $value;
    }

    /**
     * Get a flash message from the current request
     */
    public static function getFlash(string $key, $default = null)
    {
        return $_SESSION['_flash'][$key] ?? $default;
    }

    /**
     * Check if a flash message exists for the current request
     */
    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }
}
