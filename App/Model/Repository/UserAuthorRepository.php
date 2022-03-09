<?php
namespace App\Model\Repository;

use App\Model\Repository\Repository;

class UserAuthorRepository extends Repository {
    public function __construct()
    {
        parent::__construct('blg_user_author', 'user_id');
    }

    public function getUserAuthorById($id){
        return self::selectRow(
            "SELECT 
                blg_user.id,
                {$this->tableName}.name,
                blg_user.email,
                blg_user.password,
                blg_user.password_salt,
                {$this->tableName}.slug,
                {$this->tableName}.description,
                {$this->tableName}.creation_date,
                {$this->tableName}.update_date,
                blg_user_type.name AS type_name
            FROM blg_user 
                INNER JOIN {$this->tableName} ON {$this->tableName}.{$this->columnReference} = blg_user.id
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
            WHERE blg_user.id = :id
                AND blg_user.type_id IN (1,3)", ["id" => $id]
        );
    }

    public function getUserAuthorByEmail(string $email){
        return self::selectRow(
            "SELECT 
                blg_user.id,
                {$this->tableName}.name,
                blg_user.email,
                blg_user.password,
                blg_user.password_salt,
                {$this->tableName}.slug,
                {$this->tableName}.description,
                {$this->tableName}.creation_date,
                {$this->tableName}.update_date,
                blg_user_type.name AS type_name
            FROM blg_user
                INNER JOIN {$this->tableName} ON {$this->tableName}.{$this->columnReference} = blg_user.id
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
            WHERE blg_user.email = :email
                AND blg_user.type_id IN (1,3)", ["email" => $email]
        );
    }
}