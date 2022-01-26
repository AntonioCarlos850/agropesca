<?php 

require_once __DIR__ . "/../../dao/User/Autor.php";
require_once __DIR__ . "/../../model/User/Autor.php";

class AutorUser extends DAOAutorUser{

	public function selectAutor( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $user = new ModelAutorUser($data);
            $formatted_return[] = $user->getData();
        }

		return $formatted_return;
	}

    public function insertAutor( $params = array() ){
		$return = $this->insert($params);

        $formatted_return = [];
        if($return){
            $params = [
                [
                    "key"=>"user_id",
                    "reference"=>":USER_ID",
                    "value"=>$params["user_id"]
                ]
            ];
    
            $return = $this->select($params);

            $user = new ModelAutorUser($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function updateAutor( $params = array() ){
		$return = $this->update($params);

        $formatted_return = [];
        if($return){
            $params = [
                [
                    "key"=>"user_id",
                    "reference"=>":USER_ID",
                    "value"=>$params["user_id"]
                ]
            ];
    
            $return = $this->select($params);

            $user = new ModelAutorUser($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function deleteAutor( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 