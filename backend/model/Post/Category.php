<?php

class ModelCategoryPost {
    private $id;
    private $name;
    private $creation_date;
    private $update_date;

    public function __construct($data){
        $this->setData($data);
    }

    public function __toString(){
        $data = $this->getData();

        return "id".$data["id"].","."name".$data["name"].
        ","."creation_date".$data["creation_date"].","."update_date".$data["update_date"];
    }

    private function setData($data){
		$this->setId($data['id']);
		$this->setName($data['name']);
		$this->setCreationDate($data['creation_date']);
        $this->setUpdateDate($data['update_date']);
	}

    public function getData(){
		return [
            "id"=>$this->getId(),
            "name"=>$this->getName(),
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

    private function setName($name){
        $this->name = $name;
    }

    private function getName(){
        return $this->name;
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