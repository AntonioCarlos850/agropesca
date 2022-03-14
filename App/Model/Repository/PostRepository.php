<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;

class PostRepository extends Repository
{
    public function __construct()
    {
        parent::__construct('blg_post', 'id');
    }

    public function getPost(array $queryConditions = [], array $data = [])
    {
        return self::selectRow(
            "SELECT {$this->tableName}.*,
                blg_post_category.name category_name,
                blg_post_category.image_uri category_image_uri,
                blg_post_category.creation_date AS category_creation_date,
                blg_post_category.update_date AS category_update_date,
                blg_user.name AS author_name,
                blg_user_author.slug AS author_slug,
                blg_user_author.description AS author_description,
                blg_user.email AS author_email,
                blg_user.password AS author_password,
                blg_user.password_salt AS author_password_salt,
                blg_user_author.creation_date AS author_creation_date,
                blg_user_author.update_date AS author_update_date,
                blg_user.type_id AS author_type_id,
                blg_user_type.name AS author_type_name,
                blg_user_type.creation_date AS author_type_creation_date,
                blg_user_type.update_date AS author_type_update_date,
                blg_image.id AS image_id,
                blg_image.path AS image_path,
                blg_image.filename AS image_filename,
                blg_image.alt AS image_alt,
                blg_image.creation_date AS image_creation_date,
                author_image.id AS author_image_id,
                author_image.path AS author_image_path,
                author_image.filename AS author_image_filename,
                author_image.alt AS author_image_alt,
                author_image.creation_date AS author_image_creation_date
            FROM blg_post
                LEFT JOIN blg_image ON blg_image.id = blg_post.image_id
                LEFT JOIN blg_post_category ON blg_post_category.id = {$this->tableName}.category_id
                INNER JOIN blg_user ON blg_user.id = blg_post.author_id
                INNER JOIN blg_user_author ON blg_user_author.user_id = blg_user.id 
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
                LEFT JOIN blg_image AS author_image ON author_image.id = blg_user.image_id
            WHERE " . join(" AND ", $queryConditions),
            $data
        );
    }

    public function getPosts(array $queryConditions = [], array $queryOrders = [], $limit, $offset, array $data = [])
    {
        return self::select(
            "SELECT {$this->tableName}.*,
                blg_post_category.name category_name,
                blg_post_category.image_uri category_image_uri,
                blg_post_category.creation_date AS category_creation_date,
                blg_post_category.update_date AS category_update_date,
                blg_user.name AS author_name,
                blg_user_author.slug AS author_slug,
                blg_user_author.description AS author_description,
                blg_user.email AS author_email,
                blg_user.password AS author_password,
                blg_user.password_salt AS author_password_salt,
                blg_user_author.creation_date AS author_creation_date,
                blg_user_author.update_date AS author_update_date,
                blg_user.type_id AS author_type_id,
                blg_user_type.name AS author_type_name,
                blg_user_type.creation_date AS author_type_creation_date,
                blg_user_type.update_date AS author_type_update_date,
                blg_image.id AS image_id,
                blg_image.path AS image_path,
                blg_image.filename AS image_filename,
                blg_image.alt AS image_alt,
                blg_image.creation_date AS image_creation_date,
                author_image.id AS author_image_id,
                author_image.path AS author_image_path,
                author_image.filename AS author_image_filename,
                author_image.alt AS author_image_alt,
                author_image.creation_date AS author_image_creation_date
            FROM blg_post 
                LEFT JOIN blg_image ON blg_image.id = blg_post.image_id
                LEFT JOIN blg_post_category ON blg_post_category.id = {$this->tableName}.category_id
                INNER JOIN blg_user ON blg_user.id = blg_post.author_id
                INNER JOIN blg_user_author ON blg_user_author.user_id = blg_user.id 
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
                LEFT JOIN blg_image AS author_image ON author_image.id = blg_user.image_id
            " . (empty($queryConditions) ? "" : ("WHERE " . join(" AND ", $queryConditions))) . "
            ORDER BY " . (empty($queryOrders) ? "{$this->tableName}.{$this->columnReference} DESC" : join(", ", $queryOrders)) . "
            " . ($limit ? "LIMIT $limit" : "") . "
            " . ($limit && $offset ? "OFFSET $offset" : "") . "
            ",
            $data
        );
    }

    public function getPostCount(array $queryConditions = [], array $queryOrders = [], array $data = [])
    {
        return self::selectValue(
            "SELECT COUNT(DISTINCT blg_post.id)
            FROM blg_post 
                LEFT JOIN blg_post_category ON blg_post_category.id = {$this->tableName}.category_id
                INNER JOIN blg_user ON blg_user.id = blg_post.author_id
                INNER JOIN blg_user_author ON blg_user_author.user_id = blg_user.id 
                INNER JOIN blg_user_type ON blg_user_type.id = blg_user.type_id
            " . (empty($queryConditions) ? "" : ("WHERE " . join(" AND ", $queryConditions))) . "
            ORDER BY " . (empty($queryOrders) ? "{$this->tableName}.{$this->columnReference} DESC" : join(", ", $queryOrders)) . "
            ",
            $data
        );
    }

    public function getPostById(int $id)
    {
        return self::getPost(["{$this->tableName}.{$this->columnReference} = :id"], ["id" => $id]);
    }

    public function getPostBySlug(string $slug)
    {
        return self::getPost(["{$this->tableName}.slug = :slug"], ["slug" => $slug]);
    }

    public function getPostsByAuthorId(int $authorId, array $queryOrders = [], ?int $limit = null, ?int $offset = null, array $aditionalQueryConditions = [], array $aditionalParameters = [])
    {
        return self::getPosts(
            array_merge(["{$this->tableName}.author_id = :authorId"], $aditionalQueryConditions),
            $queryOrders,
            $limit,
            $offset,
            array_merge(["authorId" => $authorId], $aditionalParameters)
        );
    }

    public function getActivePosts(array $queryOrders = [], ?int $limit = null, ?int $offset = null, array $aditionalQueryConditions = [], array $aditionalParameters = [])
    {
        return self::getPosts(
            array_merge(["{$this->tableName}.active = 1"], $aditionalQueryConditions),
            $queryOrders,
            $limit,
            $offset,
            $aditionalParameters
        );
    }

    public function getActivePostCount(array $queryOrders = [], array $aditionalQueryConditions = [], array $aditionalParameters = [])
    {
        return self::getPostCount(
            array_merge(["{$this->tableName}.active = 1"], $aditionalQueryConditions),
            $queryOrders,
            $aditionalParameters
        );
    }

    public function createVisit(int $postId, ?int $userId = null)
    {
        return self::insert(
            "INSERT INTO blg_post_visit
                (blg_post_visit.user_id, blg_post_visit.post_id)
            VALUES (:userId, :postId)
        ",
            ["userId" => $userId, "postId" => $postId]
        );
    }

    public function sincronizeVisits(int $postId)
    {
        return self::update(
            "UPDATE blg_post
                SET blg_post.visits = (
                    SELECT COUNT(blg_post_visit.user_id) 
                    FROM blg_post_visit
                    WHERE blg_post_visit.post_id = :postId
                )
        ",
            ["postId" => $postId]
        );
    }
}
