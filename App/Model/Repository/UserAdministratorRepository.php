<?php
namespace App\Model\Repository;

use App\Model\Repository\Repository;

class UserAdministratorRepository extends UserRepository {
    public function getUserById($id){
        return self::selectRow(
            "SELECT 
                {$this->tableName}.*,
                blg_user_type.name AS type_name
            FROM {$this->tableName} 
                LEFT JOIN blg_user_author ON blg_user_author.user_id = {$this->tableName}.{$this->columnReference}
                INNER JOIN blg_user_type ON blg_user_type.{$this->columnReference} = {$this->tableName}.type_id
            WHERE {$this->tableName}.{$this->columnReference} = :id
                AND {$this->tableName}.type_id = 1", ["id" => $id]
        );
    }

    public function getUserByEmail(string $email){
        return self::selectRow(
            "SELECT 
                {$this->tableName}.*,
                blg_user_type.name AS type_name
            FROM blg_user 
                LEFT JOIN blg_user_author ON blg_user_author.user_id = {$this->tableName}.{$this->columnReference}
                INNER JOIN blg_user_type ON blg_user_type.id = {$this->tableName}.type_id
            WHERE {$this->tableName}.email = :email
                AND {$this->tableName}.type_id = 1", ["email" => $email]
        );
    }
}