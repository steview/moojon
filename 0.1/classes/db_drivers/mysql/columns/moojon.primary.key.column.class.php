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
	
	public function get_foreign_key($table) {
		if (substr($table, 0, 5) == 'base_') {
			$table = substr($table, 5);
		}
		return moojon_inflect::singularize($table).'_'.self::NAME;
	}
	
	public function get_table($foreign_key) {
		return str_replace('_'.self::NAME, '', $foreign_key);
	}
	
	public function __toString() {
		return $this->name.' INTEGER('.$this->limit.') '.$this->get_null_string().' '.$this->get_default_string().' AUTO_INCREMENT';
	}
}
?>