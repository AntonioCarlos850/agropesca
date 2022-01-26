<?php 

require_once __DIR__ . "/../../dao/Post/Category.php";
require_once __DIR__ . "/../../model/Post/Category.php";

class CategoryPost extends DAOCategoryPost{

	public function selectCategory( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $user = new ModelCategoryPost($data);
            $formatted_return[] = $user->getData();
        }

		return $formatted_return;
	}

    public function insertCategory( $params = array() ){
		$return = $this->insert($params);

        $formatted_return = [];
        if($return){
            $params = [
                [
                    "key"=>"name",
                    "reference"=>":NAME",
                    "value"=>$params["name"]
                ]
            ];
    
            $return = $this->select($params);

            $user = new ModelCategoryPost($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function updateCategory( $params = array() ){
		$return = $this->update($params);

        $formatted_return = [];
        if($return){
            $params = [
                [
                    "key"=>"name",
                    "reference"=>":NAME",
                    "value"=>$params["name"]
                ]
            ];
    
            $return = $this->select($params);

            $user = new ModelCategoryPost($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function deleteCategory( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 