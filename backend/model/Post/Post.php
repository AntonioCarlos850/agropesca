<?php 

require_once __DIR__ . "/../Builder.php";
require_once __DIR__ . "/../../Sql.php";
require_once __DIR__ . "/../../../helpers/string.php";

class ModelPost extends Builder{

    private $bd;

    public function __construct(){
        $this->bd = "blg_post";
	}

	protected function select($params_query = []){

		$sql = new Sql();
		$params = [];
		$params2 = [];

		foreach($params_query as $param){
			$params[] = [
					"key"=>$param["key"],
					"reference"=>$param["reference"]
			];

			$params2[$param["reference"]] = $param["value"];
		}
		
		$query = $this->build_select($this->bd,$params);

		$sql = new Sql();
		$return = $sql->doQuery($query, $params2);

		return $return;
	}

	protected function insert($params_query){
		$salt = random_string(5);

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
		$salt = random_string(5);

		$params = [
			[
				"key"=>"email",
				"reference"=>":EMAIL"
			],
			[
				"key"=>"password",
				"reference"=>"SHA1(CONCAT(CONCAT(:PASSWORD,:SALT),'agropesca'))"
			],
			[
				"key"=>"password_salt",
				"reference"=>":SALT"
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
				':EMAIL'=>$params_query["email"],
				':PASSWORD'=>$params_query["password"],
				':SALT'=>$salt,
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