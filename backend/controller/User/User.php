<?php 

require_once __DIR__ . "/../../model/User/User.php";

class ControllerUsuario extends ModelUsuario{

	public function select_user( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "id" => $data["id"],
                "type" => [
                    "id"=>$data["type_id"]
                ],
                "name" => $data["name"],
                "email" => $data["email"],
                "password" => $data["password"],
                "salt" => $data["password_salt"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"]//->format("d/m/Y H:i:s")
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
                "type" => [
                    "id"=>$data["type_id"]
                ],
                "name" => $data["name"],
                "email" => $data["email"],
                "password" => $data["password"],
                "password_salt" => $data["password_salt"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"]//->format("d/m/Y H:i:s")
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
                "type" => [
                    "id"=>$data["type_id"]
                ],
                "name" => $data["name"],
                "email" => $data["email"],
                "password" => $data["password"],
                "password_salt" => $data["password_salt"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"]//->format("d/m/Y H:i:s")
            ];
        }

		return $formatted_return;
	}

    public function delete_user( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 