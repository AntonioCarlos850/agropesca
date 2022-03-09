<?php
namespace App\Model\Repository;

use App\Model\Repository\Repository;

class UserRepository extends Repository {
    public function __construct()
    {
        parent::__construct('blg_user', 'id');
    }

    public function getUserById($id){
        return self::selectRow(
            "SELECT 
                blg_user.*,
                blg_user_type.name AS type_name
            FROM blg_user
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
            WHERE blg_user.id = :id", ["id" => $id]
        );
    }

    public function getUserByEmail(string $email){
        return self::selectRow(
            "SELECT blg_user.*,
                blg_user_type.name AS type_name
            FROM blg_user
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
            WHERE blg_user.email = :email", ["email" => $email]
        );
    }
}