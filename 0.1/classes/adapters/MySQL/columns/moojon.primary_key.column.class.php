<?php
final class moojon_primary_key extends moojon_base_column {
	const NAME = 'id';
	const LIMIT = 11;
	const NULL = 'NOT NULL';
	const TYPE = 'INTEGER';
	const OPTIONS = 'AUTO_INCREMENT';
	
	public function __construct() {
		$this->name = self::NAME;
	}
	
	public function get_foreign_key($obj) {
		if (substr($obj, 0, 5) == 'base_') {
			$obj = substr($obj, 5);
		}
		return moojon_inflect::singularize($obj).'_'.self::NAME;
	}
	
	public function create_table($name, $columns, $options = null) {
		if (!is_array($columns)) {
			$data = array($columns);
		} else {
			$data = $columns;
		}
		$type = self::TYPE;
		if (self::LIMIT) {
			$type .= '('.self::LIMIT.')';
		}
		array_unshift($data, self::NAME." $type ".self::NULL.' '.self::OPTIONS);
		$data[] = 'PRIMARY KEY('.self::NAME.')';
		moojon_query_runner::create_table($name, implode(', ', $data), $options);
	}
	
	public function __toString() {
		return $this->name.' INTEGER('.$this->limit.') '.$this->get_null_string().' '.$this->get_default_string().' AUTO_INCREMENT';
	}
}
?>