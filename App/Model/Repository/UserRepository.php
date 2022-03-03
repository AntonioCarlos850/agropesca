<?php
namespace App\Model\Repository;

use App\Model\Repository\Repository;

class UserRepository extends Repository {
    static function getUserById($id){
        return self::selectRow(
            "SELECT 
                blg_user.*,
                blg_user_type.name AS type_name
            FROM blg_user
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
            WHERE blg_user.id = :id", ["id" => $id]
        );
    }

    static function getUserByEmail(string $email){
        return self::selectRow(
            "SELECT blg_user.*,
                blg_user_type.name AS type_name
            FROM blg_user
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
            WHERE blg_user.email = :email", ["email" => $email]
        );
    }
    
    static function createUser(array $data){
        $keys = array_keys($data);

        $querySets = array_map(function($key) {
            return ":$key";
        }, $keys);

        $stringfiedQuerySets = join(", ", $querySets);
        $stringfiedFields = join(", ", $keys);
        
        return self::insert(
            "INSERT INTO blg_user 
                ($stringfiedFields)
            VALUES
                ($stringfiedQuerySets)", $data
        );
    }

    static function updateUserById($id, array $data){
        $querySets = array_map(function($key) {
            return "$key = :$key";
        }, array_keys($data));

        $stringfiedQuerySets = join(", ", $querySets);

        return self::update(
            "UPDATE blg_user
            SET $stringfiedQuerySets
            WHERE blg_user.id = :$id", $data
        );
    }

    static function deleteUserById($id){
        return self::delete(
            "DELETE FROM blg_user
            WHERE blg_user.id = :$id"
        );
    }
}