<?php
namespace App\Core;

use PDO;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    /**
     * Get all records
     */
    public static function all(): array
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll();
    }

    /**
     * Find a record by primary key
     */
    public static function find(int $id): ?array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Create a new record
     */
    public static function create(array $data): int
    {
        $db = Database::getInstance();
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array_values($data));

        return (int) $db->lastInsertId();
    }

    /**
     * Update an existing record
     */
    public static function update(int $id, array $data): bool
    {
        $db = Database::getInstance();
        $setClauses = [];
        foreach (array_keys($data) as $key) {
            $setClauses[] = "$key = ?";
        }
        $setString = implode(', ', $setClauses);

        $sql = "UPDATE " . static::$table . " SET $setString WHERE " . static::$primaryKey . " = ?";
        $stmt = $db->prepare($sql);

        $values = array_values($data);
        $values[] = $id;

        return $stmt->execute($values);
    }

    /**
     * Delete a record
     */
    public static function delete(int $id): bool
    {
        $db = Database::getInstance();
        $sql = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
