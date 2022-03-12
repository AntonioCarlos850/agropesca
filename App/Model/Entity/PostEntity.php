<?php
namespace App\Model\Entity;

use App\Model\Repository\PostRepository;
use Exception;
use App\Utils\Helpers;
use DateTime;

class PostEntity{
    // Attributes
    public ?int $id;
    public AuthorEntity $author;
    public ?PostCategoryEntity $category;
    public string $slug;
    public string $title;
    public string $description;
    public string $body;
    public int $visits;
    public bool $active;
    public ?DateTime $creation_date;
    public ?DateTime $update_date;

    public function __construct(array $postData){
        $this->setAttributes($postData);
    }

    // Setters
    protected function setAttributes(array $postData){
        if(!isset($postData["title"]) || !isset($postData["author_id"])){
            throw new Exception("Título e id de autor necessários", 400);
        }

        $this->setId($postData["id"] ?? null);

        $this->setActive($postData["active"] ?? false);

        $this->setTitle($postData["title"]);
        $this->setSlug($postData["slug"] ?? null);
        $this->setDescription($postData["description"] ?? null);
        $this->setBody($postData["body"] ?? null);

        $this->setVisits($postData["visits"] ?? null);

        $this->setAuthor($postData);
        $this->setCategory($postData);
        
        $this->setCreationDate($postData["creation_date"]);
        $this->setUpdateDate($postData["update_date"]);
    }

    public function setTitle(string $title){
        if(strlen($title) < 15){
            throw new Exception("Título precisa ter mais que 15 caracteres", 400);
        }

        $this->title = $title;
    }

    public function setActive(bool $active){
        $this->active = $active;
    }

    public function setId($id){
        $this->id = $id ? intval($id) : null;
    }

    public function setSlug(?string $slug){
        $this->slug = str_replace(" ", "-", strtolower(Helpers::removeAccents($slug ?: $this->title)));
    }

    public function setDescription(?string $description){
        $this->description = $description;

        if(!$description){
            $this->setActive(false);
        }
    }

    public function setBody(?string $body){
        $this->body = $body;

        if(!$body){
            $this->setActive(false);
        }
    }

    public function setCreationDate(?string $creationDate){
        $this->creation_date = $creationDate ? new DateTime($creationDate) : null;
    }

    public function setUpdateDate(?string $updateDate){
        $this->update_date = $updateDate ? new DateTime($updateDate) : null;
    }

    public function setCategory(array $categoryData){
        try {
            if(Helpers::verifyArrayFields($categoryData, [
                "category_id", "category_name", "category_creation_date", "category_update_date"
            ])){
                $this->category = new PostCategoryEntity([
                    "id" => $categoryData["category_id"],
                    "name" => $categoryData["category_name"],
                    "creation_date" => $categoryData["category_creation_date"],
                    "update_date" => $categoryData["category_update_date"],
                ]);
            }else{
                $this->category = PostCategoryEntity::getCategoryById($categoryData["category_id"]);
            }            
        } catch (Exception $error){
            $this->category = null;
            $this->setActive(false);
        }
    }
    
    public function setVisits(?int $visits){
        $this->visits = intval($visits);
    }

    public function setAuthor(array $authorData){
        if(!isset($authorData["author_id"])){
            throw new Exception("Referência de Autor necessária", 400);
        }

        try {
            $this->author = new AuthorEntity([
                "id" => $authorData["author_id"],
                "name" => $authorData["author_name"],
                "email" => $authorData["author_email"],
                "password" => $authorData["author_password"],
                "password_salt" => $authorData["author_password_salt"],
                "type_id" => $authorData["author_type_id"],
                "type_name" => $authorData["author_type_name"],
                "type_creation_date" => $authorData["author_type_creation_date"],
                "type_update_date" => $authorData["author_type_update_date"],
                "slug" => $authorData["author_slug"],
                "description" => $authorData["author_description"],
                "creation_date" => $authorData["author_creation_date"],
                "update_date" => $authorData["author_update_date"],
            ]);
        } catch (Exception $exception) {
            $this->author = AuthorEntity::getAuthorById($authorData["author_id"]);
        }
    }

    // Manage database Data
    public function create(){
        $postRepository = new PostRepository();
        $id = $postRepository->create([
            "category_id" => $this->category ? $this->category->id : null,
            "author_id" => $this->author->id,
            "slug" => $this->slug,
            "active" => $this->active,
            "title" => $this->title,
            "description" => $this->description,
            "body" => $this->body,
        ]);
        
        $this->id = $id;
    }

    public function update(){
        $postRepository = new PostRepository();
        $postRepository->updateByColumnReference($this->id, [
            "category_id" => $this->category ? $this->category->id : null,
            "author_id" => $this->author->id,
            "slug" => $this->slug,
            "active" => $this->active,
            "title" => $this->title,
            "description" => $this->description,
            "body" => $this->body,
        ]);
    }

    public function delete(){
        $postRepository = new PostRepository();
        $postRepository->deleteByColumnReference($this->id);
    }

    public function createPostVisit(?int $userId){
        $postRepository = new PostRepository();
        $postRepository->createVisit($this->id, $userId);
        $postRepository->sincronizeVisits($this->id);
    }

    public static function createPost(array $data)
    {
        $postEntity = new PostEntity($data);
        $postEntity->create();

        return $postEntity;
    }

    public static function getPostById($id){
        $postRepository = new PostRepository();
        $postData = $postRepository->getPostById($id);

        if(!$postData){
            throw new Exception("Post não encontrado", 404);
        }else{
            $postInstance = new PostEntity($postData);

            return $postInstance;
        }
    }

    public static function getPostBySlug($postSlug){
        $postRepository = new PostRepository();
        $postData = $postRepository->getPostBySlug($postSlug);

        if(!$postData){
            throw new Exception("Post não encontrado", 404);
        }else{
            $postInstance = new PostEntity($postData);

            return $postInstance;
        }
    }

    public static function getPostsByAuthor($authorId, array $queryOrders = []){
        $postRepository = new PostRepository();
        $postsData = $postRepository->getPostsByAuthorId($authorId, $queryOrders);

        $postsEntities = array_map(function($postData){
            return new PostEntity($postData);
        }, $postsData);

        return $postsEntities;
    }

    public static function getActivePosts(array $queryOrders = [], ?int $limit = null, ?int $offset = null){
        $postRepository = new PostRepository();
        $postsData = $postRepository->getActivePosts($queryOrders, $limit, $offset);

        $postsEntities = array_map(function($postData){
            return new PostEntity($postData);
        }, $postsData);

        return $postsEntities;
    }

}