<?php
abstract class moojon_singleton_immutable_collection extends moojon_singleton {
	protected $data = array();
	
	abstract static public function get_data($data = null);
	abstract static public function has($key, $data = null);
	abstract static public function get($key, $data = null);
	abstract static public function get_or_null($key, $data = null);
}
?>