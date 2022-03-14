<?php

namespace App\Model\Entity;

use App\Model\Repository\UserTypeRepository;
use DateTime;
use Exception;

class UserTypeEntity
{
    public int $id;
    public string $name;
    public ?DateTime $creation_date;
    public ?DateTime $update_date;

    public function __construct(array $typeData)
    {
        $this->setAttributes($typeData);
    }

    protected function setAttributes(array $typeData)
    {
        $this->setId($typeData["id"]);
        $this->setName($typeData["name"]);
        $this->setCreationDate($typeData["creation_date"] ?? null);
        $this->setUpdateDate($typeData["update_date"] ?? null);
    }

    public function setId($id)
    {
        $this->id = $id ? intval($id) : null;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function setCreationDate(?string $creationDate)
    {
        $this->creation_date = $creationDate ? new DateTime($creationDate) : null;
    }

    public function setUpdateDate(?string $updateDate)
    {
        $this->update_date = $updateDate ? new DateTime($updateDate) : null;
    }

    public static function getUserTypeById(int $id)
    {
        $userTypeRepository = new UserTypeRepository();
        $userTypeData = $userTypeRepository->getUserTypeById($id);

        if (!$userTypeData) {
            throw new Exception("Tipo de usuário não encontrado", 404);
        } else {
            $userTypeInstance = new UserTypeEntity($userTypeData);

            return $userTypeInstance;
        }
    }
}
