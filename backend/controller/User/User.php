<?php 

require_once __DIR__ . "/../../dao/User/User.php";
require_once __DIR__ . "/../../model/User/User.php";

class User extends DAOUser{

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
            $user = new ModelUser($data);
            $formatted_return[] = $user->getData();
        }

		return $formatted_return;
	}

    public function insertUser( $params = array() ){
		$return = $this->insert($params);
        $formatted_return = [];

        if($return){
            $params = [
                [
                    "key"=>"email",
                    "reference"=>":EMAIL",
                    "value"=>$params["email"]
                ]
            ];
    
            $return = $this->select($params);

            $user = new ModelUser($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function updateUser( $params = array() ){
		$return = $this->update($params);

        $formatted_return = [];
        if($return){
            $params = [
                [
                    "key"=>"id",
                    "reference"=>":ID",
                    "value"=>$params["id"]
                ]
            ];
    
            $return = $this->select($params);
            $user = new ModelUser($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function deleteUser( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 