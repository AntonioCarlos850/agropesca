<?php 

require_once __DIR__ . "/../../dao/Post/Category.php";

class ControllerCategoryPost extends DAOCategoryPost{

	public function selectCategory( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "id" => $data["id"],
                "name" => $data["name"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"],
            ];
        }

		return $formatted_return;
	}

    public function insertCategory( $params = array() ){
		$return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "id" => $data["id"],
                "name" => $data["name"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"],
            ];
        }

		return $formatted_return;
	}

    public function updateCategory( $params = array() ){
		$return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "id" => $data["id"],
                "name" => $data["name"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"],
            ];
        }

		return $formatted_return;
	}

    public function deleteCategory( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 