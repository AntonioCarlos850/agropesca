<?php
namespace App\Model\Repository;

use App\Model\Repository\Repository;

class UserAuthorRepository extends Repository {
    static function getUserAuthorById($id){
        return self::selectRow(
            "SELECT 
                blg_user.id,
                blg_user_author.name,
                blg_user.email,
                blg_user.password,
                blg_user.password_salt,
                blg_user_author.slug ,
                blg_user_author.description,
                blg_user_author.creation_date,
                blg_user_author.update_date,
                blg_user_type.name AS type_name
            FROM blg_user 
                INNER JOIN blg_user_author ON blg_user_author.user_id = blg_user.id
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
            WHERE blg_user.id = :id
                AND blg_user.type_id IN (1,3)", ["id" => $id]
        );
    }

    static function getUserAuthorByEmail(string $email){
        return self::selectRow(
            "SELECT 
                blg_user.id,
                blg_user_author.name,
                blg_user.email,
                blg_user.password,
                blg_user.password_salt,
                blg_user_author.slug ,
                blg_user_author.description,
                blg_user_author.creation_date,
                blg_user_author.update_date,
                blg_user_type.name AS type_name
            FROM blg_user
                INNER JOIN blg_user_author ON blg_user_author.user_id = blg_user.id
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
            WHERE blg_user.email = :email
                AND blg_user.type_id IN (1,3)", ["email" => $email]
        );
    }
    
    static function createUserAuthor(array $data){
        $keys = array_keys($data);

        $querySets = array_map(function($key) {
            return ":$key";
        }, $keys);

        $stringfiedQuerySets = join(", ", $querySets);
        $stringfiedFields = join(", ", $keys);
        
        return self::insert(
            "INSERT INTO blg_user_author 
                ($stringfiedFields)
            VALUES
                ($stringfiedQuerySets)", $data
        );
    }

    static function updateUserAuthorById($id, array $data){
        $querySets = array_map(function($key) {
            return "$key = :$key";
        }, array_keys($data));

        $stringfiedQuerySets = join(", ", $querySets);

        return self::update(
            "UPDATE blg_user_author
            SET $stringfiedQuerySets
            WHERE blg_user_author.user_id = :$id", $data
        );
    }

    static function deleteUserById($id){
        return self::delete(
            "DELETE FROM blg_user_author
            WHERE blg_user_author.user_id = :$id"
        );
    }
}