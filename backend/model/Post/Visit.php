<?php

class ModelPost {
    private $user_id;
    private $post_id;
    private $creation_date;

    public function __construct($data){
        $this->setData($data);
    }

    public function __toString(){
        $data = $this->getData();

        return "user_id".$data["user_id"].","."post_id".$data["post_id"].","."creation_date".$data["creation_date"];
    }

    private function setData($data){
		$this->setUserId($data['user_id']);
		$this->setPostId($data['post_id']);
        $this->setCreationDate($data['creation_date']);
	}

    public function getData(){
		return [
            "user_id"=>$this->getUserId(),
            "post_id"=>$this->getPostId(),
            "creation_date"=> $this->getCreationDate()->format("d/m/Y H:i:s")
        ];
	}

    private function setUserId($user_id){
        $this->user_id = $user_id;
    }

    private function getUserId(){
        return $this->user_id;
    }

    private function setPostId($post_id){
        $this->post_id = $post_id;
    }

    private function getPostId(){
        return $this->post_id;
    }

    private function setCreationDate($creation_date){
        $this->creation_date = $creation_date;
    }

    private function getCreationDate(){
        return $this->creation_date;
    }
}