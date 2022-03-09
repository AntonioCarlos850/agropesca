<?php
namespace App\Model\Entity;

use App\Model\Repository\PostRepository;
use Exception;
use App\Utils\Helpers;

class PostEntity{
    // Attributes
    public ?int $id;
    public ?UserAuthorEntity $author;
    public PostCategoryEntity $category;
    public string $slug;
    public string $title;
    public string $description;
    public string $body;
    public bool $active;
    public ?string $creation_date;
    public ?string $update_date;

    public function __construct(array $postData){
        $this->setAttributes($postData);
    }

    // Setters
    protected function setAttributes(array $postData){
        if(!isset($postData["title"]) || !isset($postData["author_id"])){
            throw new Exception("Título e id de autor necessários", 400);
        }

        $this->id = $postData["id"] ?? null;
        $this->author = UserAuthorEntity::getUserById($postData["author_id"]);
        $this->category = PostCategoryEntity::getPostCategoryById($postData["category_id"]);
        $this->title = $postData["title"];

        $this->setSlug($postData["slug"] ?? $postData["title"]);
        $this->setActive($postData["active"] ?? false);

        $this->description = $postData["description"] ?? null;
        $this->body = $postData["body"] ?? null;
        
        $this->creation_date = $postData["creation_date"] ?? null;
        $this->update_date = $postData["update_date"] ?? null;
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

    public function setSlug(string $slug){
        $this->slug = str_replace(" ", "-", strtolower(Helpers::removeAccents($slug)));
    }

    public function setDescription(string $description){
        $this->description = $description;
    }

    public function setBody(string $body){
        $this->body = $body;
    }

    // Manage database Data
    public function create(){
        $postRepository = new PostRepository();
        $id = $postRepository->create([
            "category_id" => $this->category->id,
            "author_id" => $this->author->id,
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
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
            "password_salt" => $this->password_salt,
        ]);
    }

    public function delete(){
        $postRepository = new PostRepository();
        $postRepository->deleteByColumnReference($this->id);
    }

    public function createPostVisit(?int $userId){
        $postRepository = new PostRepository();
        $postRepository->createVisit($userId, $this->id);
    }

    public static function createPost(array $data)
    {
        $userEntity = new UserEntity($data);
        $userEntity->create();

        return $userEntity;
    }

    public static function getPostById($id){
        $postRepository = new PostRepository();
        $postData = $postRepository->getPostById($id);

        if(!$postData){
            throw new Exception("Usuário não encontrado", 404);
        }else{
            $userInstance = new UserEntity($postData);

            return $userInstance;
        }
    }

    public static function getPostsByAuthor($authorId, string $order = "ID DESC"){
        $order = "blg_post.author_id DESC";
        $postRepository = new PostRepository();
        $postsData = $postRepository->getPostsByAuthorId($authorId, $order);

        $posts = array_map(function($postData){
            return new PostEntity($postData);
        }, $postsData);

        return $posts;
    }

    public static function getPosts(string $order = "ID DESC"){
        $order = "blg_post.author_id DESC";
        $postRepository = new PostRepository();
        $postsData = $postRepository->getPosts($order);

        $posts = array_map(function($postData){
            return new PostEntity($postData);
        }, $postsData);

        return $posts;
    }

}