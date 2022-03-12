<?php
namespace App\Model\Repository;

use App\Model\Repository\Repository;

class PostCategoryRepository extends Repository {
    public function __construct()
    {
        parent::__construct('blg_post_category', 'id');
        
    }

    public function getCategory(array $queryConditions = [], array $data = []){
        return self::selectRow(
            "SELECT {$this->tableName}.*
            FROM {$this->tableName}
            WHERE ".join(" AND ", $queryConditions), $data
        );
    }

    public function getCategories(array $queryConditions = [], array $queryOrders = [], array $queryJoins = [], ?int $limit = null, ?int $offset = null, array $data = []){
        return self::select(
            "SELECT {$this->tableName}.*
            FROM {$this->tableName}
            ".(join(" ", $queryJoins))."
            ".(empty($queryConditions) ? "" : ("WHERE ".join(" AND ", $queryConditions)))."
            ORDER BY ".(empty($queryOrders) ? "{$this->tableName}.{$this->columnReference} DESC" : join(", ", $queryOrders)."
            ".($limit ? "LIMIT $limit" : "")."
            ".($limit && $offset ? "OFFSET $offset" : "")."
            "), $data
        );
    }

    public function getCategoryById($id){
        return self::getCategory(["{$this->tableName}.{$this->columnReference} = :id"], ["id" => $id]);
    }

    public function getCategoriesByAuthorPosts(int $authorId){
        return self::getCategories(
            ["blg_post.author_id = :authorId"], 
            [], 
            ["INNER JOIN blg_post ON blg_post.category_id = blg_post_category.id"],
            null,
            null,
            ["authorId" => $authorId]
        );
    }
}