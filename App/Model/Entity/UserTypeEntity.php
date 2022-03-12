<?php
namespace App\Model\Entity;

use DateTime;

class UserTypeEntity{
    public int $id;
    public string $name;
    public ?DateTime $creation_date;
    public ?DateTime $update_date;

    public function __construct(array $typeData)
    {
        $this->setAttributes($typeData);
    }

    protected function setAttributes(array $typeData){
        $this->setId($typeData["id"]);
        $this->setName($typeData["name"]);
        $this->setCreationDate($typeData["creation_date"]);
        $this->setUpdateDate($typeData["update_date"]);
    }

    public function setId($id){
        $this->id = $id ? intval($id) : null;
    }

    public function setName(?string $name){
        $this->name = $name;
    }

    public function setCreationDate(?string $creationDate){
        $this->creation_date = $creationDate ? new DateTime($creationDate) : null;
    }

    public function setUpdateDate(?string $updateDate){
        $this->update_date = $updateDate ? new DateTime($updateDate) : null;
    }
}