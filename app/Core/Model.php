<?php

namespace App\Core;

abstract class Model {
    protected static string $table;
    protected static string $primaryKey = 'id';

    public static function all(string $orderBy = 'id DESC'): array {
        $table = static::$table;
        return Database::fetchAll("SELECT * FROM `{$table}` ORDER BY {$orderBy}");
    }

    public static function find($id): ?array {
        $table = static::$table;
        $pk = static::$primaryKey;
        return Database::fetch("SELECT * FROM `{$table}` WHERE `{$pk}` = :id LIMIT 1", ['id' => $id]);
    }

    public static function findBy(string $column, $val): ?array {
        $table = static::$table;
        return Database::fetch("SELECT * FROM `{$table}` WHERE `{$column}` = :val LIMIT 1", ['val' => $val]);
    }

    public static function where(string $column, $val, string $op = '='): array {
        $table = static::$table;
        return Database::fetchAll("SELECT * FROM `{$table}` WHERE `{$column}` {$op} :val", ['val' => $val]);
    }

    public static function create(array $data): int {
        $table = static::$table;
        return Database::insert($table, $data);
    }

    public static function update($id, array $data): int {
        $table = static::$table;
        $pk = static::$primaryKey;
        return Database::update($table, $data, "`{$pk}` = :pk_id", ['pk_id' => $id]);
    }

    public static function delete($id): int {
        $table = static::$table;
        $pk = static::$primaryKey;
        return Database::delete($table, "`{$pk}` = :pk_id", ['pk_id' => $id]);
    }

    public static function count(string $where = '1=1', array $params = []): int {
        $table = static::$table;
        $res = Database::fetch("SELECT COUNT(*) as total FROM `{$table}` WHERE {$where}", $params);
        return (int)($res['total'] ?? 0);
    }

    public static function paginate(int $page = 1, int $perPage = 12, string $where = '1=1', array $params = [], string $orderBy = 'id DESC'): array {
        $table = static::$table;
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $total = self::count($where, $params);
        $totalPages = (int)ceil($total / $perPage);

        $sql = "SELECT * FROM `{$table}` WHERE {$where} ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
        $items = Database::fetchAll($sql, $params);

        return [
            'data' => $items,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'has_prev' => $page > 1,
            'has_next' => $page < $totalPages,
        ];
    }
}
