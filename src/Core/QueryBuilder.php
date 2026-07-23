<?php
namespace App\Core;

use PDO;

class QueryBuilder
{
    protected string $table;
    protected array $selects = ['*'];
    protected array $wheres = [];
    protected array $joins = [];
    protected array $orderBys = [];
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $bindings = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function select($columns): self
    {
        $this->selects = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function where(string $column, $operator = null, $value = null, string $boolean = 'AND'): self
    {
        // If only 2 arguments are passed, we assume the operator is '='
        if ($value === null && func_num_args() <= 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = [
            'type' => 'Basic',
            'column' => $column,
            'operator' => $operator,
            'boolean' => $boolean
        ];

        $this->bindings[] = $value;

        return $this;
    }

    public function orWhere(string $column, $operator = null, $value = null): self
    {
        if ($value === null && func_num_args() <= 2) {
            $value = $operator;
            $operator = '=';
        }
        return $this->where($column, $operator, $value, 'OR');
    }

    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self
    {
        $this->joins[] = compact('table', 'first', 'operator', 'second', 'type');
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBys[] = [
            'column' => $column,
            'direction' => strtoupper($direction)
        ];
        return $this;
    }

    public function limit(int $limit, ?int $offset = null): self
    {
        $this->limit = $limit;
        if ($offset !== null) {
            $this->offset = $offset;
        }
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function toSql(): string
    {
        $sql = "SELECT " . implode(', ', $this->selects) . " FROM " . $this->table;

        foreach ($this->joins as $join) {
            $sql .= " {$join['type']} JOIN {$join['table']} ON {$join['first']} {$join['operator']} {$join['second']}";
        }

        if (!empty($this->wheres)) {
            $sql .= " WHERE ";
            foreach ($this->wheres as $i => $where) {
                if ($i > 0) {
                    $sql .= " {$where['boolean']} ";
                }
                $sql .= "{$where['column']} {$where['operator']} ?";
            }
        }

        if (!empty($this->orderBys)) {
            $sql .= " ORDER BY ";
            $orders = [];
            foreach ($this->orderBys as $order) {
                $orders[] = "{$order['column']} {$order['direction']}";
            }
            $sql .= implode(', ', $orders);
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT " . $this->limit;
            if ($this->offset !== null) {
                $sql .= " OFFSET " . $this->offset;
            }
        }

        return $sql;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }

    public function count(): int
    {
        $db = Database::getInstance();

        $sql = "SELECT COUNT(*) FROM " . $this->table;

        foreach ($this->joins as $join) {
            $sql .= " {$join['type']} JOIN {$join['table']} ON {$join['first']} {$join['operator']} {$join['second']}";
        }

        if (!empty($this->wheres)) {
            $sql .= " WHERE ";
            foreach ($this->wheres as $i => $where) {
                if ($i > 0) {
                    $sql .= " {$where['boolean']} ";
                }
                $sql .= "{$where['column']} {$where['operator']} ?";
            }
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($this->bindings);
        return (int) $stmt->fetchColumn();
    }

    public function paginate(int $perPage = 10): array
    {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }

        $total = $this->count();
        $totalPages = (int) ceil($total / $perPage);

        // Handle edge case where page > totalPages
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;

        $this->limit($perPage, $offset);
        $data = $this->get();

        return [
            'data' => $data,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'per_page' => $perPage,
            'total' => $total
        ];
    }

    public function get(): array
    {
        $db = Database::getInstance();
        $sql = $this->toSql();
        $stmt = $db->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll();
    }

    public function first(): ?array
    {
        $this->limit(1);
        $result = $this->get();
        return $result ? $result[0] : null;
    }

    public function insert(array $data): int
    {
        $db = Database::getInstance();
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array_values($data));

        return (int) $db->lastInsertId();
    }

    public function update(array $data): bool
    {
        $db = Database::getInstance();

        $setClauses = [];
        $updateBindings = [];

        foreach ($data as $key => $value) {
            $setClauses[] = "$key = ?";
            $updateBindings[] = $value;
        }

        $setString = implode(', ', $setClauses);
        $sql = "UPDATE {$this->table} SET $setString";

        if (!empty($this->wheres)) {
            $sql .= " WHERE ";
            foreach ($this->wheres as $i => $where) {
                if ($i > 0) {
                    $sql .= " {$where['boolean']} ";
                }
                $sql .= "{$where['column']} {$where['operator']} ?";
            }
            $updateBindings = array_merge($updateBindings, $this->bindings);
        }

        $stmt = $db->prepare($sql);
        return $stmt->execute($updateBindings);
    }

    public function delete(): bool
    {
        $db = Database::getInstance();
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE ";
            foreach ($this->wheres as $i => $where) {
                if ($i > 0) {
                    $sql .= " {$where['boolean']} ";
                }
                $sql .= "{$where['column']} {$where['operator']} ?";
            }
        }

        $stmt = $db->prepare($sql);
        return $stmt->execute($this->bindings);
    }
}
