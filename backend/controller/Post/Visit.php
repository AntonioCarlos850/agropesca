<?php 

require_once __DIR__ . "/../../dao/Post/Visit.php";
require_once __DIR__ . "/../../model/Post/Visit.php";

class VisitPost extends DAOVisitPost{

	public function selectVisit( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $user = new ModelVisitPost($data);
            $formatted_return[] = $user->getData();
        }

		return $formatted_return;
	}

    public function insertVisit( $params = array() ){
		$return = $this->insert($params);

        $formatted_return = [];
        if($return){
            $params = [
                [
                    "key"=>"user_id",
                    "reference"=>":USER_ID",
                    "value"=>$params["user_id"]
                ],
                [
                    "key"=>"post_id",
                    "reference"=>":POST_ID",
                    "value"=>$params["post_id"]
                ]
            ];
    
            $return = $this->select($params);

            $user = new ModelVisitPost($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function updateVisit( $params = array() ){
		$return = $this->update($params);

        $formatted_return = [];
        if($return){
            $params = [
                [
                    "key"=>"user_id",
                    "reference"=>":USER_ID",
                    "value"=>$params["user_id"]
                ],
                [
                    "key"=>"post_id",
                    "reference"=>":POST_ID",
                    "value"=>$params["post_id"]
                ]
            ];
    
            $return = $this->select($params);

            $user = new ModelVisitPost($return[0]);
            $formatted_return = $user->getData();
        }

		return $formatted_return;
	}

    public function deleteVisit( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 