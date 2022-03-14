<?php

namespace App\Utils;

use \PDO;
use \Exception;
use PDOException;
use PDOStatement;

class SqlConnection
{
    public static $conn;

    public static function init($host, $user, $pass, $dbName)
    {
        self::$conn = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private static function setParams($statement, array $parameters = [])
    {
        foreach ($parameters as $key => $value) {
            $statement->bindValue(":" . $key, $value);
        }

        return $statement;
    }

    private static function prepareQuery(string $rawQuery, array $params = [])
    {
        if (self::$conn instanceof PDO) {
            $statement = self::$conn->prepare($rawQuery);

            $statement = self::setParams($statement, $params);

            return $statement;
        } else {
            throw new Exception("Atributo estático conn não é uma instância de PDO", 500);
        }
    }

    public static function execute(string $rawQuery, array $params): PDOStatement
    {
        try {
            $statement = self::prepareQuery($rawQuery, $params);
            $statement->execute();
            return $statement;
        } catch (PDOException $pdoExeption) {
            var_dump('ERROR ' . $pdoExeption->getMessage());
        }
    }

    public static function select(string $rawQuery, array $params = [])
    {
        try {
            $statement = self::execute($rawQuery, $params);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $pdoExeption) {
            die('ERROR ' . $pdoExeption->getMessage());
        }
    }

    public static function insert(string $rawQuery, array $params = [])
    {
        self::execute($rawQuery, $params);
        return self::$conn->lastInsertId();
    }

    public static function update(string $rawQuery, array $params = [])
    {
        $statement = self::execute($rawQuery, $params);
        return $statement->rowCount();
    }

    public static function delete(string $rawQuery, array $params = [])
    {
        $statement = self::execute($rawQuery, $params);
        return $statement->rowCount();
    }
}
