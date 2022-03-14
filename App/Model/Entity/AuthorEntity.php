<?php

namespace App\Model\Entity;

use App\Model\Repository\AuthorRepository;
use App\Utils\Helpers;
use Exception;

class AuthorEntity extends UserEntity
{
    // atributtes
    public string $slug;
    public ?string $description;

    public function __construct($authorData)
    {
        $this->setAttributes($authorData);
    }

    protected function setAttributes(array $authorData)
    {
        if (!isset($authorData["description"])) {
            throw new Exception("Descrição de autor necessária", 400);
        }

        $this->setId($authorData["id"] ?? null);
        $this->setName($authorData["name"]);
        $this->setEmail($authorData["email"]);
        $this->setPassword($authorData["password"], $authorData["password_salt"] ?? null);
        $this->setType($authorData);
        $this->setImage($authorData);
        $this->setCreationDate($authorData["creation_date"] ?? null);
        $this->setUpdateDate($authorData["update_date"] ?? null);
        $this->setDescription($authorData["description"]);
    }

    public function setSlug(?string $slug)
    {
        $this->slug = str_replace(" ", "-", strtolower(Helpers::removeAccents($slug ?: ("{$this->name} {$this->id}"))));
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function setName(string $name)
    {
        if (strlen($name) < 4) {
            throw new Exception("Nome muito curto", 400);
        }

        $this->name = $name;
        $this->setSlug("{$this->name} {$this->id}");
    }

    public static function getAuthorById(int $id)
    {
        $authorRepository = new AuthorRepository();
        $authorData = $authorRepository->getAuthorById($id);

        if (!$authorData) {
            throw new Exception("Autor não encontrado", 404);
        } else {
            $authorInstance = new AuthorEntity($authorData);

            return $authorInstance;
        }
    }

    public static function getAuthorBySlug(string $slug)
    {
        $authorRepository = new AuthorRepository();
        $authorData = $authorRepository->getAuthorBySlug($slug);

        if (!$authorData) {
            throw new Exception("Autor não encontrado", 404);
        } else {
            $authorInstance = new AuthorEntity($authorData);

            return $authorInstance;
        }
    }

    // Manage database Data
    public function create()
    {
        $authorRepository = new AuthorRepository();
        $authorRepository->create([
            "user_id" => $this->id,
            "slug" => $this->slug,
            "description" => $this->description
        ]);
    }

    public function update()
    {
        $authorRepository = new AuthorRepository();

        parent::update();
        $authorRepository->updateByColumnReference($this->id, [
            "slug" => $this->slug,
            "description" => $this->description
        ]);
    }
}
