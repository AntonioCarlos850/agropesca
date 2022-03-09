<?php
namespace App\Model\Entity;

use App\Model\Repository\UserAuthorRepository;
use App\Utils\Helpers;
use Exception;

class UserAuthorEntity extends UserEntity {
    // atributtes
    public string $slug;
    public ?string $description;

    public function __construct($userAuthorData)
    {
        $this->setAttributes($userAuthorData);
    }

    protected function setAttributes(array $userAuthorData){
        if(!isset($userAuthorData["description"])){
            throw new Exception("Descrição de autor necessária", 400);
        }

        parent::setAttributes($userAuthorData);

        if(isset($userAuthorData["slug"])){
            $this->setSlug($userAuthorData["slug"]);
        }else{
            $this->setSlug($userAuthorData["name"]);
        }

        $this->setDescription($userAuthorData["description"]);
    }

    public function setSlug(string $slug){
        $this->slug = str_replace(" ", "-", strtolower(Helpers::removeAccents($slug)));
    }

    public function setDescription(?string $description){
        $this->description = $description ? str_replace(" ", "-", strtolower(Helpers::removeAccents($description))) : null;
    }

    public static function getUserAuthorById($email){
        $userAuthorRepository = new UserAuthorRepository();
        $userData = $userAuthorRepository->getUserAuthorByEmail($email);

        if(!$userData){
            throw new Exception("Usuário não encontrado", 404);
        }else{
            $userInstance = new UserAuthorEntity($userData);

            return $userInstance;
        }
    }

    // Manage database Data
    public function create(){
        $userAuthorRepository = new UserAuthorRepository();
        $userAuthorRepository->create([
            "user_id" => $this->id,
            "slug" => $this->slug,
            "name" => $this->name,
            "description" => $this->description
        ]);
    }

    public function update(){
        $userAuthorRepository = new UserAuthorRepository();
        $userAuthorRepository->updateByColumnReference($this->id, [
            "slug" => $this->slug,
            "name" => $this->name,
            "description" => $this->description
        ]);
    }
}