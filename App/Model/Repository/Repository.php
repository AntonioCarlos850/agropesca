<?php
namespace App\Model\Repository;

class Repository {
    static $connection;

    public static function select(string $query, array $params = []){
        return self::$connection::select($query, $params);
    }

    public static function selectRow(string $query, array $params = []){
        $result = self::$connection::select($query, $params);
        return isset($result[0]) ? $result[0] : null;
    }

    public static function selectValue(string $query, array $params = []){
        $result = self::$connection::select($query, $params);
        return isset($result[0]) ? array_values($result[0])[0] : null;
    }

    public static function insert(string $query, array $params = []){
        return self::$connection::insert($query, $params);
    }

    public static function update(string $query, array $params = []){
        return self::$connection::update($query, $params);
    }

    public static function delete(string $query, array $params = []){
        return self::$connection::delete($query, $params);
    }
}