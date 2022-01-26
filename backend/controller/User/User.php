<?php 

require_once __DIR__ . "/../../dao/User/User.php";
require_once __DIR__ . "/../../model/User/User.php";

class User extends DAOUsuario{

    public function tryLogin($email, $password){

        $params = [
            [
                "key"=>"email",
                "reference"=>":EMAIL",
                "value"=>$email
            ]
        ];

        $return = $this->select($params);

        if($return){
            $return = $return[0];
            
            $params = [
                [
                    "key"=>"email",
                    "reference"=>":EMAIL",
                    "value"=>$email
                ],
                [
                    "key"=>"password",
                    "reference"=>"SHA1(CONCAT(CONCAT(:PASSWORD,:SALT),'agropesca'))"
                ],
                [
				    "reference"=>":SALT",
                    "value"=>$return["password_salt"]
                ],
                [
				    "reference"=>":PASSWORD",
                    "value"=>$password
                ]
            ];
    
            $return = $this->select($params);
    
            if ($return) {
                $data = new ModelUser($return[0]);

                return $data->getData();
            } else {
                return [];
            }

        } else{
            return [];
        }
	}

	public function selectUser( $params = array() ){
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
                "update_date" => $data["update_date"]
            ];
        }

		return $formatted_return;
	}

    public function insertUser( $params = array() ){
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

    public function updateUser( $params = array() ){
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

    public function deleteUser( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 