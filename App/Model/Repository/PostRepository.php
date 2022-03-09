<?php
namespace App\Model\Repository;

use App\Model\Repository\Repository;

class UserAuthorRepository extends Repository {
    public function __construct()
    {
        parent::__construct('blg_post', 'id');
        
    }

    public function getPostById(int $id){
        return self::selectRow(
            "SELECT {$this->tableName}.*,
                blg_post_category.name category_name
            FROM blg_post 
                INNER JOIN blg_post_category ON blg_post_category.id = {$this->tableName}.category_id
            WHERE {$this->tableName}.id = :id
            ", ["id" => $id]
        );
    }

    public function getPostsByAuthorId(int $authorId, string $order){
        return self::select(
            "SELECT {$this->tableName}.*,
                blg_post_category.name category_name
            FROM blg_post 
                INNER JOIN blg_post_category ON blg_post_category.id = {$this->tableName}.category_id
            WHERE {$this->tableName}.author_id = :authorId
            ", ["authorId" => $authorId]
        );
    }

    public function getPosts(int $authorId, string $order){
        return self::select(
            "SELECT {$this->tableName}.*,
                blg_post_category.name category_name
            FROM blg_post 
                INNER JOIN blg_post_category ON blg_post_category.id = {$this->tableName}.category_id
            WHERE {$this->tableName}.author_id = :authorId
            ".($order ? ("ORDER BY ".$order) : ""), ["authorId" => $authorId]
        );
    }
}