<?php
final class moojon_primary_key extends moojon_base_column {
	const NAME = 'id';
	
	public function __construct() {
		$this->name = 'id';
		$this->limit = 11;
		$this->null = false;
		$this->default = null;
	}
	
	public function get_foreign_key($obj) {
		if (substr($obj, 0, 5) == 'base_') {
			$obj = substr($obj, 5);
		}
		return moojon_inflect::singularize($obj).'_'.self::NAME;
	}
	
	public function __toString() {
		return $this->name.' INTEGER('.$this->limit.') '.$this->get_null_string().' '.$this->get_default_string().' AUTO_INCREMENT';
	}
}
?>