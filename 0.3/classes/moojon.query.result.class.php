<?php
final class moojon_query_result extends ArrayObject {
	private $iterator;
	private $affected;
	private $non_query;
	
	public function __construct(PDOStatement $statement, $param_values = array(), $param_data_types = array(), $fetch_style = moojon_db::FETCH_ASSOC) {
		$this->iterator = $this->getIterator();
		$log = $statement->queryString;
		if (ENVIRONMENT == 'development') {
			$values = 'Param values(';
			foreach ($param_values as $key => $value) {
				$values .= "$key = $value, ";
			}
			if (count($values)) {
				$values = substr($values, 0, -2);
			}
			$values .= ')';
			$data_types = 'Param data types(';
			foreach ($param_data_types as $key => $value) {
				$data_types .= "$key = $value, ";
			}
			if (count($data_types)) {
				$data_types = substr($data_types, 0, -2);
			}
			$data_types .= ')';
			$log .= "\n$values\n$data_types";
		}
		moojon_base::log($log);
		foreach ($param_values as $key => $value) {
			if ($value !== null) {
				$data_type = (array_key_exists($key, $param_data_types)) ? $param_data_types[$key] : self::PARAM_STR;
				$statement->bindValue($key, $value, $data_type);
			}
		}
		$this->affected = $statement->execute();
		moojon_base::log('Affected rows: '.$this->affected);
		$this->non_query = ($statement->columnCount() > 0);
		if ($this->non_query) {
			foreach ($statement->fetchAll($fetch_style) as $record) {
				$this[] = $record;
			}
		}
	}
	
	public function __get($key) {
		if ($key == 'affected' || $key == 'non_query') {
			return $this->$key;
		} else {
			throw new moojon_exception("Unknown query_result property ($key)");
		}
	}
}
?>