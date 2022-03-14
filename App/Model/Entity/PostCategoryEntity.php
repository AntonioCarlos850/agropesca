<?php

namespace App\Model\Entity;

use App\Model\Repository\PostCategoryRepository;
use DateTime;
use Exception;

class PostCategoryEntity
{
    public int $id;
    public string $name;
    public ?string $image_uri;
    public ?DateTime $creation_date;
    public ?DateTime $update_date;

    public function __construct(array $categoryData)
    {
        $this->setAttributes($categoryData);
    }

    protected function setAttributes(array $categoryData)
    {
        $this->id = intval($categoryData["id"]);
        $this->name = $categoryData["name"];
        $this->image_uri = $categoryData["image_uri"];
        $this->setCreationDate($categoryData["creation_date"]);
        $this->setUpdateDate($categoryData["update_date"]);
    }

    public function setCreationDate(?string $creationDate)
    {
        $this->creation_date = $creationDate ? new DateTime($creationDate) : null;
    }

    public function setUpdateDate(?string $updateDate)
    {
        $this->update_date = $updateDate ? new DateTime($updateDate) : null;
    }

    public function getImageUri()
    {
        return $this->image_uri;
    }

    public static function getCategoryById(int $id): PostCategoryEntity
    {
        $postCategoryRepository = new PostCategoryRepository();
        $cateogoryData = $postCategoryRepository->getCategoryById($id);

        if (!$cateogoryData) {
            throw new Exception("Categoria de Post não encontrada", 404);
        } else {
            $userInstance = new PostCategoryEntity($cateogoryData);

            return $userInstance;
        }
    }

    public static function getCategories(): array
    {
        $postCategoryRepository = new PostCategoryRepository();
        $categoriesData = $postCategoryRepository->getCategories();

        return array_map(function ($categoryData) {
            return new PostCategoryEntity($categoryData);
        }, $categoriesData);
    }

    public static function getCategoriesByAuthorPosts(int $authorId): array
    {
        $postCategoryRepository = new PostCategoryRepository();
        $categoriesData = $postCategoryRepository->getCategoriesByAuthorPosts($authorId);

        return array_map(function ($categoryData) {
            return new PostCategoryEntity($categoryData);
        }, $categoriesData);
    }
}
