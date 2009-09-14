<?php
final class moojon_primary_key extends moojon_base_column {
	const NAME = 'id';
	const LIMIT = 11;
	const NULL = 'NOT NULL';
	const TYPE = 'INTEGER';
	const OPTIONS = 'AUTO_INCREMENT, PRIMARY KEY(id)';
	
	public function __construct() {
		$this->name = self::NAME;
		$this->limit = self::LIMIT;
		$this->null = self::NULL;
		$this->type = self::TYPE;
		$this->options = self::OPTIONS;
	}
	
	static public function get_foreign_key($table) {
		if (substr($table, 0, 5) == 'base_') {
			$table = substr($table, 5);
		}
		return moojon_inflect::singularize($table).'_'.self::NAME;
	}
	
	static public function get_table($foreign_key) {
		return str_replace('_'.self::NAME, '', $foreign_key);
	}
	
	public function __toString() {
		return $this->name.' '.$this->type.'('.$this->limit.') '.$this->get_null_string().' '.$this->get_default_string().'  '.$this->options;
	}
	
	public function get_data_type() {
		return moojon_db::PARAM_INT;
	}
}
?>