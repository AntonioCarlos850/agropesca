<?php
namespace App\Model\Entity;

use App\Model\Repository\PostCategoryRepository;
use DateTime;
use Exception;

class PostCategoryEntity{
    public int $id;
    public string $name;
    public ?DateTime $creation_date;
    public ?DateTime $update_date;

    public function __construct(array $categoryData)
    {
        $this->setAttributes($categoryData);
    }

    protected function setAttributes(array $categoryData){
        $this->id = intval($categoryData["id"]);
        $this->name = $categoryData["name"];
        $this->creation_date = $categoryData["creation_date"] ?? null;
        $this->update_date = $categoryData["update_date"] ?? null;
    }

    public function setCreationDate(?string $creationDate){
        $this->creation_date = $creationDate ? new DateTime($creationDate) : null;
    }

    public function setUpdateDate(?string $updateDate){
        $this->update_date = $updateDate ? new DateTime($updateDate) : null;
    }

    public static function getCategoryById($id){
        $postCategoryRepository = new PostCategoryRepository();
        $cateogoryData = $postCategoryRepository->getCategoryById($id);

        if(!$cateogoryData){
            throw new Exception("Categoria de Post não encontrada", 404);
        }else{
            $userInstance = new PostCategoryEntity($cateogoryData);

            return $userInstance;
        }
    }
}