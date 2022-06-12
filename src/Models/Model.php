<?php

namespace Shmidt\Framework\Models;

use Shmidt\Framework\DB;

abstract class Model
{
    protected DB $pdo;
    protected $table;
    protected string $pKey = 'id';

    public function __construct()
    {
        $this->pdo = DB::instance();
    }

    public function findAll(): array
    {
        $table = htmlspecialchars($this->table);
        $sql = 'SELECT * FROM ' . $table;
        return $this->pdo->query($sql);
    }

    public function where(string $key, string $simb, string $search): array
    {
        $table = htmlspecialchars($this->table);
        $key = htmlspecialchars($key);
        $simb = htmlspecialchars($simb);
        $search = htmlspecialchars($search);

        $str = $key . $simb . "'" . $search . "'";

        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $str;

        return $this->pdo->query($sql);
    }

    public function insertOne(array $array): array
    {
        $table = htmlspecialchars($this->table);

        $keys = implode(', ', array_keys($array));
        $values = array_map(function ($val) {
            return "'" . $val . "'";
        }, array_values($array));
        $valuesStr = implode(', ', $values);
        $sql = ' INSERT INTO ' . $table . ' (' . $keys . ') ' . ' VALUES( ' . $valuesStr . ')';

        return $this->pdo->query($sql);
    }

    public function update(array $set, array $where = []): array
    {
        $table = htmlspecialchars($this->table);

        $sql = 'UPDATE ' . $table . ' SET ';

        $setCondition = [];
        foreach ($set as $column => $value) {
            $setCondition[] .= $column . ' = "' . $value . '"';
        }
        $sql .= implode(', ', $setCondition);

        $whereCondition = [];
        if (!empty($where)) {
            foreach ($where as $column => $value) {
                $whereCondition[] .= $column . ' = "' . $value . '"';
            }
            $sql .= ' WHERE ' . implode(', ', $whereCondition);
        }

        return $this->pdo->query($sql);
    }

    public function delete(array $where = []): array
    {
        $table = htmlspecialchars($this->table);

        $sql = 'DELETE FROM ' . $table;

        if (!empty($where)) {
            $whereCondition = [];
            foreach ($where as $column => $value) {
                $whereCondition[] .= $column . ' = "' . $value . '"';
            }
            $sql .= ' WHERE ' . implode(', ', $whereCondition);
        }
        return $this->pdo->query($sql);
    }

    public function like(string $where, string $like): array
    {
        $table = htmlspecialchars($this->table);

        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $where . ' LIKE ';

        $likeCondition = ' "' . $like . '"';
        $sql .= $likeCondition;
        return $this->pdo->query($sql);
    }

    public function whereIn(string $where, array $in): array
    {
        $table = htmlspecialchars($this->table);

        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $where . ' IN ';

        $inCondition = [];
        foreach ($in as $value) {
            $inCondition[] .= '"' . $value . '"';
        }
        $sql .= '(' . implode(', ', $inCondition) . ')';
        return $this->pdo->query($sql);
    }

    public function orderBy(array $columns, array $order): array
    {
        $table = htmlspecialchars($this->table);

        $sql = 'SELECT ' . implode(', ', $columns) . ' FROM ' . $table . ' ORDER BY ';

        $orderCondition = [];
        foreach ($order as $column => $sortDirection) {
            $orderCondition[] .= $column . ' '. $sortDirection;
        }
        $sql .= implode(', ', $orderCondition);
        return $this->pdo->query($sql);
    }

    public function join(string $join, string $firstTable, string $secondTable, array $columns, array $on)
    {
        $table = htmlspecialchars($this->table);

        $sql = 'SELECT ' . implode(', ', $columns) .
            ' FROM ' . $firstTable . ' '. $join . ' ' . $secondTable . ' ON ';
        $onCondition = [];
        foreach ($on as $condition) {
            $firstColumn = implode('.', $condition[0]);
            $secondColumn = implode('.', $condition[1]);
            $onCondition[] = $firstColumn . ' = ' . $secondColumn;
        }
        $sql .= implode(' AND ', $onCondition);
        return $this->pdo->query($sql);
    }
}
