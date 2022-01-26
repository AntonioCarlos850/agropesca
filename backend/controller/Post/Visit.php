<?php 

require_once __DIR__ . "/../../model/User/User.php";

class ControllerCategoryPost extends ModelCategoryPost{

	public function select_user( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "user" => [
                    "id"=>$data["user_id"]
                ],
                "post" => [
                    "id"=>$data["post_id"]
                ],
                "creation_date" => $data["creation_date"],
            ];
        }

		return $formatted_return;
	}

    public function insert_user( $params = array() ){
		$return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "user" => [
                    "id"=>$data["user_id"]
                ],
                "post" => [
                    "id"=>$data["post_id"]
                ],
                "creation_date" => $data["creation_date"],
            ];
        }

		return $formatted_return;
	}

    public function update_user( $params = array() ){
		$return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "user" => [
                    "id"=>$data["user_id"]
                ],
                "post" => [
                    "id"=>$data["post_id"]
                ],
                "creation_date" => $data["creation_date"],
            ];
        }

		return $formatted_return;
	}

    public function delete_user( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 