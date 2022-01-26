<?php 

require_once __DIR__ . "/../../dao/User/User.php";
require_once __DIR__ . "/../../model/User/User.php";

class Post extends DAOPost{

	public function selectPost( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $user = new ModelPost($data);
            $formatted_return[] = $user->getData();
        }

		return $formatted_return;
	}

    public function insertPost( $params = array() ){
		$return = $this->insert($params);

        $formatted_return = [];
        if($return){
            $params = [
                [
                    "key"=>"title",
                    "reference"=>":TITLE",
                    "value"=>$params["title"]
                ]
            ];
    
            $return = $this->select($params);

            $user = new ModelPost($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function updatePost( $params = array() ){
		$return = $this->update($params);

        $formatted_return = [];
        if($return){
            $params = [
                [
                    "key"=>"title",
                    "reference"=>":TITLE",
                    "value"=>$params["title"]
                ]
            ];
    
            $return = $this->select($params);

            $user = new ModelPost($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function deletePost( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 