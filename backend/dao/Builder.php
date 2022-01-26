<?php 

class Builder {

	protected static function build_select( $table_name, $params = array() ) :string
	{
        $formatted_params = [];

        foreach($params as $param){
            $formatted_params[] = $param["key"].(isset($param["conector"]) ? $param["conector"] : " = ").$param["reference"];
        }

		$querry = "SELECT * FROM ".$table_name.($formatted_params? " WHERE ".join(" AND ", $formatted_params) : "");

		return $querry;
	}

	protected function build_insert( $table_name, $params = array() ) :string
	{
        $formatted_params = [];
        foreach($params as $param){
            $formatted_params[] = $param["reference"];
        }

        $formatted_columns = [];
        foreach($params as $param){
            $formatted_columns[] = $param["key"];
        }

		$querry = "INSERT INTO ".$table_name." (".join(", ",$formatted_columns).") "." VALUES (".join(", ", $formatted_params).")";
		return $querry;
	}

	protected function build_update($table_name, $params = array(), $conditions = array()) :string
	{
		$formatted_params = [];
        foreach($params as $param){
            $formatted_params[] = $param["key"]." = ".$param["reference"];
        }

        $formatted_conditions = [];
        foreach($conditions as $condition){
            $formatted_conditions[] = $condition["key"].(isset($condition["conector"]) ? $condition["conector"] : " = ").$condition["reference"];
        }

		$querry = "UPDATE ".$table_name." SET ".join(", ", $formatted_params).($formatted_params ? " WHERE ".join(" AND ", $formatted_conditions):"");

		return $querry;
	}

	protected function build_delete($table_name, $params = array()) :string
	{
		$formatted_params = [];
        foreach($params as $param){
            $formatted_params[] = $param["key"].(isset($param["conector"]) ? $param["conector"] : " = ").$param["reference"];
        }

		$querry = "DELETE FROM ".$table_name.($formatted_params?" WHERE ".join(" AND ", $formatted_params) : "");

		return $querry;

	}

}