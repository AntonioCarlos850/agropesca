<?php 

require_once __DIR__ . "/../../dao/Post/Visit.php";

class VisitPost extends DAOVisitPost{

	public function selectVisit( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "user" => [
                    "id"=>$data["user_id"]
                ],
                "post" => [
                    "id"=>$data["post_id"]
                ],
                "creation_date" => $data["creation_date"],
            ];
        }

		return $formatted_return;
	}

    public function insertVisit( $params = array() ){
		$return = $this->insert($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "user" => [
                    "id"=>$data["user_id"]
                ],
                "post" => [
                    "id"=>$data["post_id"]
                ],
                "creation_date" => $data["creation_date"],
            ];
        }

		return $formatted_return;
	}

    public function updateVisit( $params = array() ){
		$return = $this->update($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "user" => [
                    "id"=>$data["user_id"]
                ],
                "post" => [
                    "id"=>$data["post_id"]
                ],
                "creation_date" => $data["creation_date"],
            ];
        }

		return $formatted_return;
	}

    public function deleteVisit( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 