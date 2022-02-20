<?php
namespace App\Model\Repository;

class Repository {
    static $connection;

    public static function select(string $query, array $params = []){
        return self::$connection::select($query, $params);
    }

    public static function selectRow(string $query, array $params = []){
        return self::$connection::select($query, $params)[0];
    }

    public static function selectValue(string $query, array $params = []){
        return array_values(self::$connection::select($query, $params)[0])[0];
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