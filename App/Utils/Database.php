<?php

namespace App\Utils;

use \PDO;
use \Exception;

class SqlConnection {
    private static $conn;
    
    public static function init($host, $user, $pass, $dbName){
        self::$conn = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass, [
            PDO::MYSQL_ATTR_FOUND_ROWS => true
        ]);
    }

    private static function setParams($statement, array $parameters = []){
        foreach ($parameters as $key => $value) {
            $statement->bindBaram($key, $value);
        }

        return $statement;
	}

    private static function prepareQuery(string $rawQuery, array $params = []){
        if(self::$conn instanceof PDO){
            $statement = self::$conn->prepare($rawQuery);

            $statement = self::setParams($statement, $params);
    
            $statement->execute();
    
            return $statement;
        }else{
            throw new Exception("Atributo estático conn não é uma instância de PDO", 500);
        }
	}

    public static function select(string $rawQuery, array $params = []){
        $statement = self::prepareQuery($rawQuery, $params);
        return $statement->execute();
	}

    public static function update(string $rawQuery, array $params = []){
        $statement = self::prepareQuery($rawQuery, $params);
        $statement->execute();
        return $statement->rowCount();
	}

    public static function delete(string $rawQuery, array $params = []){
        $statement = self::prepareQuery($rawQuery, $params);
        $statement->execute();
        return $statement->rowCount();
	}
}