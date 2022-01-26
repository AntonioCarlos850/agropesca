<?php 

require_once __DIR__ . "/../../dao/User/Type.php";

class TypeUser extends DAOTypeUser{

	public function selectType( $params = array() ){
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

    public function insertType( $params = array() ){
		$return = $this->insert($params);

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

    public function updateType( $params = array() ){
		$return = $this->update($params);

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

    public function deleteType( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 