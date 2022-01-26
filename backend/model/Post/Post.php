<?php

class ModelPost {
    private $id;
    private $category_id;
    private $autor_id;
    private $slug;
    private $title;
    private $description;
    private $body;
    private $creation_date;
    private $update_date;

    public function __construct($data){
        $this->setData($data);
    }

    public function __toString(){
        $data = $this->getData();

        return "id".$data["id"].","."category_id".$data["category_id"].","."autor_id".$data["autor_id"].","."slug".$data["slug"].
        ","."title".$data["title"].","."description".$data["description"].","."body".$data["body"].
        ","."creation_date".$data["creation_date"].","."update_date".$data["update_date"];
    }

    private function setData($data){
		$this->setId($data['id']);
		$this->setCategoryId($data['category_id']);
		$this->setAutorId($data['autor_id']);
		$this->setSlug($data['slug']);
        $this->setTitle($data['title']);
        $this->setDescription($data['description']);
        $this->setBody($data['body']);
        $this->setCreationDate($data['creation_date']);
        $this->setUpdateDate($data['update_date']);
	}

    public function getData(){
		return [
            "id"=>$this->getId(),
            "category_id"=>$this->getCategoryId(),
            "autor_id"=>$this->getAutorId(),
            "slug"=>$this->getSlug(),
            "title"=>$this->getTitle(),
            "description"=>$this->getDescription(),
            "body"=>$this->getBody(),
            "creation_date"=> $this->getCreationDate()->format("d/m/Y H:i:s"),
            "update_date"=>$this->getUpdateDate()->format("d/m/Y H:i:s")
        ];
	}

    private function setId($id){
        $this->id = $id;
    }

    private function getId(){
        return $this->id;
    }

    private function setCategoryId($category_id){
        $this->category_id = $category_id;
    }

    private function getCategoryId(){
        return $this->category_id;
    }

    private function setAutorId($autor_id){
        $this->autor_id = $autor_id;
    }

    private function getAutorId(){
        return $this->autor_id;
    }

    private function setSlug($slug){
        $this->slug = $slug;
    }

    private function getSlug(){
        return $this->slug;
    }

    private function setTitle($title){
        $this->title = $title;
    }

    private function getTitle(){
        return $this->title;
    }

    private function setDescription($description){
        $this->description = $description;
    }

    private function getDescription(){
        return $this->description;
    }

    private function setBody($body){
        $this->body = $body;
    }

    private function getBody(){
        return $this->body;
    }

    private function setCreationDate($creation_date){
        $this->creation_date = $creation_date;
    }

    private function getCreationDate(){
        return $this->creation_date;
    }

    private function setUpdateDate($update_date){
        $this->update_date = $update_date;
    }

    private function getUpdateDate(){
        return $this->update_date;
    }
}