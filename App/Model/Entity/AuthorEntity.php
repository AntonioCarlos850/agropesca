<?php
namespace App\Model\Entity;

use App\Model\Repository\AuthorRepository;
use App\Utils\Helpers;
use Exception;

class AuthorEntity extends UserEntity {
    // atributtes
    public string $slug;
    public ?string $description;

    public function __construct($authorData)
    {
        $this->setAttributes($authorData);
    }

    protected function setAttributes(array $authorData){
        if(!isset($authorData["description"])){
            throw new Exception("Descrição de autor necessária", 400);
        }

        parent::setAttributes($authorData);

        $this->setSlug($authorData["slug"] ?? null);
        $this->setDescription($authorData["description"]);
    }

    public function setSlug(?string $slug){
        $this->slug = str_replace(" ", "-", strtolower(Helpers::removeAccents($slug ?: $this->name)));
    }

    public function setDescription(?string $description){
        $this->description = $description ? str_replace(" ", "-", strtolower(Helpers::removeAccents($description))) : null;
    }

    public static function getAuthorById(int $id){
        $authorRepository = new AuthorRepository();
        $authorData = $authorRepository->getAuthorById($id);

        if(!$authorData){
            throw new Exception("Autor não encontrado", 404);
        }else{
            $authorInstance = new AuthorEntity($authorData);

            return $authorInstance;
        }
    }

    // Manage database Data
    public function create(){
        $authorRepository = new AuthorRepository();
        $authorRepository->create([
            "user_id" => $this->id,
            "slug" => $this->slug,
            "name" => $this->name,
            "description" => $this->description
        ]);
    }

    public function update(){
        $authorRepository = new AuthorRepository();
        $authorRepository->updateByColumnReference($this->id, [
            "slug" => $this->slug,
            "name" => $this->name,
            "description" => $this->description
        ]);
    }
}