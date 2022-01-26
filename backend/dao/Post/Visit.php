<?php 

require_once __DIR__ . "/../Builder.php";
require_once __DIR__ . "/../../Sql.php";
require_once __DIR__ . "/../../../helpers/string.php";

class DAOVisitPost extends Builder{

    private $bd;

    public function __construct(){
        $this->bd = "blg_post_visit";
	}

	protected function select($params_query = []){

		$sql = new Sql();
		$params = [];
		$params2 = [];

		foreach($params_query as $param){

			if(isset($param["key"]) && $param["key"]){
				$params[] = [
						"key"=>$param["key"],
						"reference"=>$param["reference"]
				];	
			}

			if(isset($param["value"]) && $param["value"]){
				$params2[$param["reference"]] = $param["value"];
			}
		}
		
		$query = $this->build_select($this->bd,$params);

		$sql = new Sql();
		$return = $sql->doQuery($query, $params2);

		return $return;
	}

	protected function insert($params_query){
		$params = [
			[
				"key"=>"user_id",
				"reference"=>":USER_ID"
            ],
            [
				"key"=>"post_id",
				"reference"=>":POST_ID"
			]
		];

		$query = $this->build_insert($this->bd,$params);

		$sql = new Sql();
		$return = $sql->doQueryCount($query, array(
				':USER_ID'=>$params_query["user_id"],
                ':POST_ID'=>$params_query["post_id"]
			)
		);

		return $return;
	}

	protected function update($params_query){
		$params = [
			[
				"key"=>"user_id",
				"reference"=>":USER_ID"
			],
            [
				"key"=>"post_id",
				"reference"=>":POST_ID"
			]
		];

		$conditions=[
			[
				"key" => "id",
				"reference" => ":ID"
			]
		];

		$query = $this->build_update($this->bd,$params,$conditions);

		$sql = new Sql();
		$return = $sql->doQueryCount($query, array(
				':POST_ID'=>$params_query["post_id"],
                ':USER_ID'=>$params_query["user_id"],
				':ID'=>$params_query["id"]
			)
		);

		return $return;
	}

	protected function delete($id){
		$params = [
			[
				"key"=>"id",
				"reference"=>":ID"
			]
		];

		$query = $this->build_delete($this->bd,$params);

		$sql = new Sql();
		$sql->doQueryCount($query, array(
			':ID'=>$id
		));

		return $id;
	}
} 