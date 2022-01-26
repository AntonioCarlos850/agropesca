<?php 

require_once __DIR__ . "/../Builder.php";
require_once __DIR__ . "/../../Sql.php";
require_once __DIR__ . "/../../../helpers/string.php";

class ModelUsuario extends Builder{

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
		
		$query = $this->build_select("blg_user",$params);

		$sql = new Sql();
		$return = $sql->doQuery($query, $params2);

		return $return;
	}

	protected function insert($params_query){
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
			],
			[
				"key"=>"name",
				"reference"=>":NAME"
			],
			[
				"key"=>"type_id",
				"reference"=>":TYPE_ID"
			],
		];

		$query = $this->build_insert("blg_user",$params);

		$sql = new Sql();
		$return = $sql->doQuery($query, array(
				':EMAIL'=>$params_query["email"],
				':PASSWORD'=>$params_query["password"],
				':SALT'=>$salt,
				':NAME'=>$params_query["name"],
				':TYPE_ID'=>$params_query["type_id"],
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

		$query = $this->build_update("blg_user",$params,$conditions);

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

		$query = $this->build_delete("blg_user",$params);

		$sql = new Sql();
		$return = $sql->doQuery($query, array(
			':ID'=>$id
		));

		return $return;
	}
} 