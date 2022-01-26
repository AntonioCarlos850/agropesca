<?php 

require_once __DIR__ . "/../../dao/User/Autor.php";

class AutorUser extends DAOAutorUser{

	public function selectAutor( $params = array() ){
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

    public function insertAutor( $params = array() ){
		$return = $this->insert($params);

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

    public function updateAutor( $params = array() ){
		$return = $this->update($params);

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

    public function deleteAutor( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 