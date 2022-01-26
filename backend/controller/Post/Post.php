<?php 

require_once __DIR__ . "/../../dao/User/User.php";

class Post extends DAOPost{

	public function selectPost( $params = array() ){
        $return = $this->select($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "id" => $data["id"],
                "category" => [
                    "id"=>$data["category_id"]
                ],
                "autor" => [
                    "id"=>$data["autor_id"]
                ],
                "slug" => $data["slug"],
                "title" => $data["title"],
                "description" => $data["description"],
                "body" => $data["body"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"]
            ];
        }

		return $formatted_return;
	}

    public function insertPost( $params = array() ){
		$return = $this->insert($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "id" => $data["id"],
                "category" => [
                    "id"=>$data["category_id"]
                ],
                "autor" => [
                    "id"=>$data["autor_id"]
                ],
                "slug" => $data["slug"],
                "title" => $data["title"],
                "description" => $data["description"],
                "body" => $data["body"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"]
            ];
        }

		return $formatted_return;
	}

    public function updatePost( $params = array() ){
		$return = $this->update($params);

        $formatted_return = [];
        foreach($return as $data){
            $formatted_return[] = [
                "id" => $data["id"],
                "category" => [
                    "id"=>$data["category_id"]
                ],
                "autor" => [
                    "id"=>$data["autor_id"]
                ],
                "slug" => $data["slug"],
                "title" => $data["title"],
                "description" => $data["description"],
                "body" => $data["body"],
                "creation_date" => $data["creation_date"],
                "update_date" => $data["update_date"]
            ];
        }

		return $formatted_return;
	}

    public function deletePost( $id ){
		$return = $this->delete($id);

		return $return;
	}
} 