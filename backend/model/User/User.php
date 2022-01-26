<?php

class ModelUser {
    private $id;
    private $type_id;
    private $name;
    private $email;
    private $password;
    private $password_salt;
    private $creation_date;
    private $update_date;

    public function __construct($data){
        $this->setData($data);
    }

    public function __toString(){
        $data = $this->getData();

        return "id".$data["id"].","."type_id".$data["type_id"].","."name".$data["name"].","."email".$data["email"].
        ","."password".$data["password"].","."password_salt".$data["password_salt"].","."creation_date".$data["creation_date"].
        ","."update_date".$data["update_date"];
    }

    private function setData($data){
		$this->setId($data['id']);
		$this->setTypeId($data['type_id']);
		$this->setName($data['name']);
		$this->setEmail($data['email']);
        $this->setPassword($data['password']);
        $this->setPasswordSalt($data['password_salt']);
        $this->setCreationDate(new DateTime($data['creation_date']));
        $this->setUpdateDate(new DateTime($data['update_date']));
	}

    public function getData(){
		return [
            "id"=>$this->getId(),
            "type"=>[
                "id"=>$this->getTypeId()
            ],
            "name"=>$this->getName(),
            "email"=>$this->getEmail(),
            "password"=>$this->getPassword(),
            "password_salt"=>$this->getPasswordSalt(),
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

    private function setTypeId($type_id){
        $this->type_id = $type_id;
    }

    private function getTypeId(){
        return $this->type_id;
    }

    private function setName($name){
        $this->name = $name;
    }

    private function getName(){
        return $this->name;
    }

    private function setEmail($email){
        $this->email = $email;
    }

    private function getEmail(){
        return $this->email;
    }

    private function setPassword($password){
        $this->password = $password;
    }

    private function getPassword(){
        return $this->password;
    }

    private function setPasswordSalt($password_salt){
        $this->password_salt = $password_salt;
    }

    private function getPasswordSalt(){
        return $this->password_salt;
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