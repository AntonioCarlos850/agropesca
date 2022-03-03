<?php
namespace App\Model\Repository;

use App\Model\Repository\Repository;

class UserAdministratorRepository extends UserRepository {
    static function getUserById($id){
        return self::selectRow(
            "SELECT 
                blg_user.*,
                blg_user_type.name AS type_name
            FROM blg_user 
                LEFT JOIN blg_user_author ON blg_user_author.user_id = blg_user.id
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
            WHERE blg_user.id = :id
                AND blg_user.type_id = 1", ["id" => $id]
        );
    }

    static function getUserByEmail(string $email){
        return self::selectRow(
            "SELECT 
                blg_user.*,
                blg_user_type.name AS type_name
            FROM blg_user 
                LEFT JOIN blg_user_author ON blg_user_author.user_id = blg_user.id
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
            WHERE blg_user.email = :email
                AND blg_user.type_id = 1", ["email" => $email]
        );
    }
}