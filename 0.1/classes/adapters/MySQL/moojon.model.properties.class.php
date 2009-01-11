<?php
class moojon_model_properties {
	
	const DEFAULT_PRIMARY_KEY = 'id';
	
	final private function __construct() {}
	
	final public function get_foreign_key($obj) {
		return moojon_inflect::singularize($obj).'_'.self::DEFAULT_PRIMARY_KEY;
	}
	
}
class model_properties extends moojon_model_properties {}
?>