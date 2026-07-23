<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Initialize PDO connection
     */
    public static function init(array $config)
    {
        if (self::$instance === null) {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            try {
                self::$instance = new PDO($dsn, $config['user'], $config['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
    }

    /**
     * Get a QueryBuilder instance for a specific table
     */
    public static function table(string $table): QueryBuilder
    {
        return new QueryBuilder($table);
    }

    /**
     * Get the PDO instance
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            die("Database not initialized.");
        }
        return self::$instance;
    }

    /**
     * Debug tool to emulate/interpolate SQL statements
     *
     * @param string $query The SQL query with placeholders
     * @param array $params The parameters bound to the query
     * @return string The emulated SQL query
     */
    public static function debugQuery(string $query, array $params): string
    {
        $keys = [];
        $values = [];

        // Handle associative and sequential arrays
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . ltrim($key, ':') . '/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_string($value)) {
                $values[] = "'" . addslashes($value) . "'";
            } elseif (is_int($value) || is_float($value)) {
                $values[] = (string) $value;
            } elseif ($value === null) {
                $values[] = 'NULL';
            } else {
                $values[] = $value;
            }
        }

        $query = preg_replace($keys, $values, $query, 1, $count);
        return $query;
    }
}
