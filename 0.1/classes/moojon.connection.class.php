<?php
final class moojon_connection {
	private static $instance;
	private $resource;
	
	private function __construct() {
		$this->resource = mysql_connect(moojon_config::key('db_host'), moojon_config::key('db_username'), moojon_config::key('db_password'));
		mysql_select_db(moojon_config::key('db'), $this->resource);
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_connection();
		}
		return self::$instance;
	}
	
	static public function get_resource() {
		$instance = self::get();
		return $instance->resource;
	}
	
	static public function close() {
		if (self::$instance) {
			$instance = self::get();
			mysql_close($instance->resource);
		}
	}
}
?>