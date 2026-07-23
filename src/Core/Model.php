<?php
namespace App\Core;

use PDO;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    /**
     * Get a QueryBuilder instance for this model
     */
    public static function query(): QueryBuilder
    {
        return Database::table(static::$table);
    }

    /**
     * Start a query with a select clause
     */
    public static function select($columns): QueryBuilder
    {
        return static::query()->select($columns);
    }

    /**
     * Start a query with a where clause
     */
    public static function where(string $column, $operator = null, $value = null): QueryBuilder
    {
        if (func_num_args() === 2) {
            return static::query()->where($column, $operator);
        }
        return static::query()->where($column, $operator, $value);
    }

    /**
     * Start a query with a join clause
     */
    public static function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): QueryBuilder
    {
        return static::query()->join($table, $first, $operator, $second, $type);
    }

    /**
     * Start a query with an orderBy clause
     */
    public static function orderBy(string $column, string $direction = 'ASC'): QueryBuilder
    {
        return static::query()->orderBy($column, $direction);
    }

    /**
     * Start a query with a limit clause
     */
    public static function limit(int $limit, ?int $offset = null): QueryBuilder
    {
        return static::query()->limit($limit, $offset);
    }

    /**
     * Get all records
     */
    public static function all(): array
    {
        return static::query()->get();
    }

    /**
     * Get paginated records
     */
    public static function paginate(int $perPage = 10): array
    {
        return static::query()->paginate($perPage);
    }

    /**
     * Find a record by primary key
     */
    public static function find(int $id): ?array
    {
        return static::query()->where(static::$primaryKey, $id)->first();
    }

    /**
     * Create a new record
     */
    public static function create(array $data): int
    {
        return static::query()->insert($data);
    }

    /**
     * Update an existing record
     */
    public static function update(int $id, array $data): bool
    {
        return static::query()->where(static::$primaryKey, $id)->update($data);
    }

    /**
     * Delete a record
     */
    public static function delete(int $id): bool
    {
        return static::query()->where(static::$primaryKey, $id)->delete();
    }
}
