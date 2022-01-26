<?php 

require_once __DIR__ . "/../../model/User/User.php";

class ControllerPost extends ModelPost{

	public function select_user( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "id" => $data["id"],
                "category" => [
                    "id"=>$data["category_id"]
                ],
                "autor" => [
                    "id"=>$data["autor_id"]
                ],
                "slug" => $data["slug"],
                "title" => $data["title"],
                "description" => $data["description"],
                "body" => $data["body"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"]
            ];
        }

		return $formatted_return;
	}

    public function insert_user( $params = array() ){
		$return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "id" => $data["id"],
                "category" => [
                    "id"=>$data["category_id"]
                ],
                "autor" => [
                    "id"=>$data["autor_id"]
                ],
                "slug" => $data["slug"],
                "title" => $data["title"],
                "description" => $data["description"],
                "body" => $data["body"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"]
            ];
        }

		return $formatted_return;
	}

    public function update_user( $params = array() ){
		$return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "id" => $data["id"],
                "category" => [
                    "id"=>$data["category_id"]
                ],
                "autor" => [
                    "id"=>$data["autor_id"]
                ],
                "slug" => $data["slug"],
                "title" => $data["title"],
                "description" => $data["description"],
                "body" => $data["body"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"]
            ];
        }

		return $formatted_return;
	}

    public function delete_user( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 