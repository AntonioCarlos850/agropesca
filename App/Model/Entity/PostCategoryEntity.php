<?php
namespace App\Model\Entity;

use App\Model\Repository\PostCategoryRepository;
use Exception;

class PostCategoryEntity{
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

    public static function getPostCategoryById($id){
        $postCategoryRepository = new PostCategoryRepository();
        $cateogoryData = $postCategoryRepository->getPostCategoryById($id);

        if(!$cateogoryData){
            throw new Exception("Usuário não encontrado", 404);
        }else{
            $userInstance = new PostCategoryEntity($cateogoryData);

            return $userInstance;
        }
    }
}