<?php
final class moojon_primary_key extends moojon_base_column {
	private $options;
	const NAME = 'id';
	const LIMIT = 11;
	const NULL = 'NOT NULL';
	const TYPE = 'INTEGER';
	
	public function __construct() {
		$this->data_type = moojon_db::PARAM_INT;
		$this->name = self::NAME;
		$this->limit = self::LIMIT;
		$this->null = self::NULL;
		$this->type = self::TYPE;
		$this->options = 'AUTO_INCREMENT, PRIMARY KEY(`'.self::NAME.'`)';
	}
	
	final public function get_options() {
		return $this->options;
	}
	
	static public function get_foreign_key($table) {
		return moojon_inflect::singularize(self::strip_base($table)).'_'.self::NAME;
	}
	
	static public function get_table($foreign_key) {
		return str_replace('_'.self::NAME, '', $foreign_key);
	}
}
?>