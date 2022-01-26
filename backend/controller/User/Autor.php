<?php 

require_once __DIR__ . "/../../model/User/Autor.php";

class ControllerAutorUsuario extends ModelAutorUsuario{

	public function select_user( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "user" => [
                    "id"=>$data["user_id"]
                ],
                "slug" => $data["slug"],
                "name" => $data["name"],
                "description" => $data["description"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"],
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
                "slug" => $data["slug"],
                "name" => $data["name"],
                "description" => $data["description"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"],
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
                "slug" => $data["slug"],
                "name" => $data["name"],
                "description" => $data["description"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"],
            ];
        }

		return $formatted_return;
	}

    public function delete_user( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 