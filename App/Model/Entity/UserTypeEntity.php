<?php
namespace App\Model\Entity;

class UserTypeEntity{
    public int $id;
    public string $name;
    public ?string $creation_date;
    public ?string $update_date;

    public function __construct(array $typeData)
    {
        $this->setAttributes($typeData);
    }

    protected function setAttributes(array $typeData){
        $this->id = intval($typeData["id"]);
        $this->name = $typeData["name"];
        $this->creation_date = $typeData["creation_date"] ?? null;
        $this->update_date = $typeData["update_date"] ?? null;
    }
}