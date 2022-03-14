<?php

namespace App\Model\Entity;

use App\Model\Repository\ImageRepository;
use Exception;
use App\Utils\Helpers;
use DateTime;

class ImageEntity
{
    // Attributes
    public ?int $id;
    public string $path;
    public string $filename;
    public ?string $alt;
    public ?DateTime $creation_date;

    public function __construct(array $imageData)
    {
        $this->setAttributes($imageData);
    }

    // Setters
    protected function setAttributes(array $imageData)
    {
        $this->setId($imageData["id"] ?? null);

        $this->setPath($imageData["path"]);
        $this->setFilename($imageData["filename"]);
        $this->setAlt($imageData["alt"] ?? null);

        $this->setCreationDate($imageData["creation_date"] ?? null);
    }

    public function setId($id)
    {
        $this->id = $id ? intval($id) : null;
    }

    public function setPath(?string $path)
    {
        $this->path = $path;
    }

    public function setFilename(?string $filename)
    {
        $this->filename = $filename;
    }

    public function setAlt(?string $alt)
    {
        $this->alt = $alt;
    }

    public function setCreationDate(?string $creationDate)
    {
        $this->creation_date = $creationDate ? new DateTime($creationDate) : null;
    }

    public function getUri()
    {
        return $this->path.$this->filename;
    }

    public function getAbsoluteFilename(){
        return __DIR__ . '/../../..'.$this->getUri();
    }

    // Manage database Data
    public function create()
    {
        $imageRepository = new ImageRepository();
        $id = $imageRepository->create([
            "path" => $this->path,
            "filename" => $this->filename,
            "alt" => $this->alt,
        ]);

        $this->id = $id;
    }

    public function update()
    {
        $imageRepository = new ImageRepository();
        $imageRepository->updateByColumnReference($this->id, [
            "path" => $this->path,
            "filename" => $this->filename,
            "alt" => $this->alt,
        ]);
    }

    public function delete()
    {
        $absoluteFilename = $this->getAbsoluteFilename();

        if(is_file($absoluteFilename)){
            unlink($absoluteFilename);
        }

        $imageRepository = new ImageRepository();
        $imageRepository->deleteByColumnReference($this->id);

    }

    public static function createImage(array $data): imageEntity
    {
        $imageEntity = new imageEntity($data);
        $imageEntity->create();

        return $imageEntity;
    }

    public static function getImageById(int $id): imageEntity
    {
        $imageRepository = new ImageRepository();
        $imageData = $imageRepository->getimageById($id);

        if (!$imageData) {
            throw new Exception("Imagem não encontrada", 404);
        } else {
            $imageInstance = new imageEntity($imageData);

            return $imageInstance;
        }
    }
}
