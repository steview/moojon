<?php
final class moojon_connection {
	private static $instance;
	private $resource;
	
	private function __construct() {
		$this->resource = mysql_connect(moojon_config::get('db_host'), moojon_config::get('db_username'), moojon_config::get('db_password'));
		mysql_select_db(moojon_config::get('db'), $this->resource);
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
		$instance = self::get();
		mysql_close($instance->resource);
		return self::$instance;
	}
}
?>