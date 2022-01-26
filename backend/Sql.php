<?php 

class Sql extends PDO {

	private $conn;

	public function __construct(){

		$this->conn = new PDO("mysql:host=localhost;dbname=agropesca", "root", "");

	}

	private function setParams($statement, $parameters = array()){

		foreach ($parameters as $key => $value) {
			
			$this->setParam($statement, $key, $value);

		}

	}

	private function setParam($statement, $key, $value){

		$statement->bindParam($key, $value);

	}

	private function execQuerry($rawQuery, $params = array()){

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

		//var_dump($stmt->errorInfo());

		return $stmt;

	}

	public function doQuery($rawQuery, $params = array()):array
	{

		$stmt = $this->execQuerry($rawQuery, $params);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);

	}

	public function doQueryCount($rawQuery, $params = array()):int
	{
		$stmt = $this->execQuerry($rawQuery, $params);

		return $stmt->rowCount();
	}

}