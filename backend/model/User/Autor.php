<?php

class ModelAutorUser {
    private $user_id;
    private $slug;
    private $name;
    private $description;
    private $creation_date;
    private $update_date;

    public function __construct($data){
        $this->setData($data);
    }

    public function __toString(){
        $data = $this->getData();

        return "user_id".$data["user_id"].","."slug".$data["slug"].","."name".$data["name"].","."description".$data["description"].
        ","."creation_date".$data["creation_date"].","."update_date".$data["update_date"];
    }

    private function setData($data){
		$this->setUserId($data['user_id']);
		$this->setSlug($data['slug']);
		$this->setName($data['name']);
		$this->setDescription($data['description']);
        $this->setCreationDate($data['creation_date']);
        $this->setUpdateDate($data['update_date']);
	}

    public function getData(){
		return [
            "user_id"=>$this->getUserId(),
            "slug"=>$this->getSlug(),
            "name"=>$this->getName(),
            "description"=>$this->getDescription(),
            "creation_date"=> $this->getCreationDate()->format("d/m/Y H:i:s"),
            "update_date"=>$this->getUpdateDate()->format("d/m/Y H:i:s")
        ];
	}

    private function setUserId($user_id){
        $this->user_id = $user_id;
    }

    private function getUserId(){
        return $this->user_id;
    }

    private function setSlug($slug){
        $this->slug = $slug;
    }

    private function getSlug(){
        return $this->slug;
    }

    private function setName($name){
        $this->name = $name;
    }

    private function getName(){
        return $this->name;
    }

    private function setDescription($description){
        $this->description = $description;
    }

    private function getDescription(){
        return $this->description;
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