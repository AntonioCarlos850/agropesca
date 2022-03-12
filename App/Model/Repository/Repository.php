<?php
namespace App\Model\Repository;

use Exception;

class Repository {
    static $connection;
    public $tableName;
    public $columnReference;
    public $codeManagementble;

    public function __construct(string $tableName, string $columnReference, bool $codeManagementble = true)
    {
        if(!$tableName || !$columnReference){
            throw new Exception("Necessary tableName and columnReference to create a Repository Instance", 500);
        }

        $this->codeManagementble = $codeManagementble;
        $this->tableName = $tableName;
        $this->columnReference = $columnReference;        
    }

    public function updateByColumnReference($id, array $data){
        if(!$this->codeManagementble){
            throw new Exception("Table can't be management by code", 500);
        }

        $querySets = array_map(function($key) {
            return "$key = :$key";
        }, array_keys($data));

        $stringfiedQuerySets = join(", ", $querySets);

        return self::update(
            "UPDATE {$this->tableName}
                SET $stringfiedQuerySets
            WHERE {$this->tableName}.{$this->columnReference} = :id", array_merge($data, ["id" => $id])
        );
    }

    public function create(array $data){
        if(!$this->codeManagementble){
            throw new Exception("Table can't be management by code", 500);
        }

        $keys = array_keys($data);

        $querySets = array_map(function($key) {
            return ":$key";
        }, $keys);

        $stringfiedQuerySets = join(", ", $querySets);
        $stringfiedFields = join(", ", $keys);

        return self::insert(
            "INSERT INTO {$this->tableName} 
                ($stringfiedFields)
            VALUES
                ($stringfiedQuerySets)", $data
        );
    }

    public function deleteByColumnReference($id){
        if(!$this->codeManagementble){
            throw new Exception("Table can't be management by code", 500);
        }
        
        return self::delete(
            "DELETE FROM {$this->tableName}
            WHERE {$this->tableName}.{$this->columnReference} = :id", ["id" => $id]
        );
    }

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