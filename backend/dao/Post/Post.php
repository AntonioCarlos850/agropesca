<?php 

require_once __DIR__ . "/../Builder.php";
require_once __DIR__ . "/../../Sql.php";
require_once __DIR__ . "/../../../helpers/string.php";

class DAOPost extends Builder{

    private $bd;

    public function __construct(){
        $this->bd = "blg_post";
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
				"key"=>"category_id",
				"reference"=>":CATEGORY_ID"
			],
			[
				"key"=>"autor_id",
				"reference"=>":AUTOR_ID"
			],
			[
				"key"=>"slug",
				"reference"=>":SLUG"
			],
			[
				"key"=>"title",
				"reference"=>":TITLE"
			],
			[
				"key"=>"description",
				"reference"=>":DESCRIPTION"
			],
			[
				"key"=>"body",
				"reference"=>":BODY"
			]
		];

		$query = $this->build_insert($this->bd,$params);

		$sql = new Sql();
		$return = $sql->doQuery($query, array(
				':CATEGORY_ID'=>$params_query["category_id"],
				':AUTOR_ID'=>$params_query["autor_id"],
				':SLUG'=>$params_query["slug"],
				':TITLE'=>$params_query["title"],
				':DESCRIPTION'=>$params_query["description"],
				':BODY'=>$params_query["body"],
			)
		);

		return $return;
	}

	protected function update($params_query){

		$params = [
			[
				"key"=>"title",
				"reference"=>":TITLE"
			],
			[
				"key"=>"description",
				"reference"=>":DESCRIPTION"
			],
			[
				"key"=>"body",
				"reference"=>":BODY"
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
		$return = $sql->doQuery($query, array(
				':TITLE'=>$params_query["title"],
				':DESCRIPTION'=>$params_query["description"],
				':BODY'=>$params_query["body"],
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
		$sql->doQuery($query, array(
			':ID'=>$id
		));

		return $id;
	}
} 