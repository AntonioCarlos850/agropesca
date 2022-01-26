<?php 

require_once __DIR__ . "/../../dao/User/Type.php";
require_once __DIR__ . "/../../model/User/Type.php";

class TypeUser extends DAOTypeUser{

	public function selectType( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $user = new ModelTypeUser($data);
            $formatted_return[] = $user->getData();
        }

		return $formatted_return;
	}

    public function insertType( $params = array() ){
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

            $user = new ModelTypeUser($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function updateType( $params = array() ){
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

            $user = new ModelTypeUser($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function deleteType( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 