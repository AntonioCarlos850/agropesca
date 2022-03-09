<?php
namespace App\Model\Repository;

use App\Model\Repository\Repository;

class PostCategoryRepository extends Repository {
    public function __construct()
    {
        parent::__construct('blg_post_category', 'id');
        
    }

    public function getPostCategoryById(int $id){
        return self::selectRow(
            "SELECT blg_post_category.*
            FROM blg_post_category
            WHERE {$this->tableName}.{$this->columnReference} = :id
            ", ["id" => $id]
        );
    }
}