<?php
abstract class moojon_singleton_mutable_collection extends moojon_singleton_immutable_collection {
	abstract static public function set($key, $value = null, $data = null);
	abstract static protected function post_set($key, $value = null, $data = null);
	abstract static public function clear();
	abstract static public function post_clear();
}
?>