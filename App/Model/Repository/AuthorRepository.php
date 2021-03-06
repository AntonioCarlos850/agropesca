<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;

class AuthorRepository extends Repository
{
    public function __construct()
    {
        parent::__construct('blg_user_author', 'user_id');
    }

    public function getAuthor(array $queryConditions = [], array $data = [])
    {
        return self::selectRow(
            "SELECT {$this->tableName}.*,
                blg_user.id,
                blg_user.name,
                blg_user.email,
                blg_user.password,
                blg_user.password_salt,
                blg_user.type_id,
                blg_user_type.name AS type_name,
                blg_user_type.creation_date AS type_creation_date,
                blg_user_type.update_date AS type_update_date,
                blg_image.id AS image_id,
                blg_image.path AS image_path,
                blg_image.filename AS image_filename,
                blg_image.alt AS image_alt,
                blg_image.creation_date AS image_creation_date
            FROM {$this->tableName}
                INNER JOIN blg_user ON blg_user.id = {$this->tableName}.user_id
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
                LEFT JOIN blg_image ON blg_image.id = blg_user.image_id
            WHERE " . join(" AND ", $queryConditions),
            $data
        );
    }

    public function getAuthors(array $queryConditions = [], array $queryOrders = [], $limit, $offset, array $data = [])
    {
        return self::select(
            "SELECT {$this->tableName}.*,
                blg_user.id,
                blg_user.name,
                blg_user.email,
                blg_user.password,
                blg_user.password_salt,
                blg_user.type_id,
                blg_user_type.name AS type_name,
                blg_user_type.creation_date AS type_creation_date,
                blg_user_type.update_date AS type_update_date,
                blg_image.id AS image_id,
                blg_image.path AS image_path,
                blg_image.filename AS image_filename,
                blg_image.alt AS image_alt,
                blg_image.creation_date AS image_creation_date
            FROM {$this->tableName}
                INNER JOIN blg_user ON blg_user.id = {$this->tableName}.user_id
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id 
                LEFT JOIN blg_image ON blg_image.id = blg_user.image_id
            " . (empty($queryConditions) ? "" : ("WHERE " . join(" AND ", $queryConditions))) . "
            ORDER BY " . (empty($queryOrders) ? "{$this->tableName}.{$this->columnReference} DESC" : join(", ", $queryOrders) . "
            " . ($limit ? "LIMIT $limit" : "") . "
            " . ($limit && $offset ? "OFFSET $offset" : "") . "
            "),
            $data
        );
    }

    public function getAuthorById($id)
    {
        return self::getAuthor(["{$this->tableName}.{$this->columnReference} = :id"], ["id" => $id]);
    }

    public function getAuthorBySlug($slug)
    {
        return self::getAuthor(["{$this->tableName}.slug = :slug"], ["slug" => $slug]);
    }

    public function getAuthorByEmail(string $email)
    {
        return self::getAuthor(["{$this->tableName}.email = :email"], ["email" => $email]);
    }
}
