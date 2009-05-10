<?php
final class moojon_connection {
	private static $instance;
	private $host;
	private $username;
	private $password;
	private $database;
	private $resource;
	
	private function __construct() {}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_connection();
		}
		return self::$instance;
	}
	
	static public function init($host = null, $username = null, $password = null, $database = null) {
		$instance = self::get();
		if ($host != null) {
			self::$instance->set_host($host);
		}
		if ($username != null) {
			self::$instance->set_username($username);
		}
		if ($password != null) {
			self::$instance->set_password($password);
		}
		if ($database != null) {
			self::$instance->set_database($database);
		}
		if ($host != null && $username != null && $password != null && $database != null) {
			self::$instance->connect($host, $username, $password, $database);
		}
		return self::$instance;
	}
	
	public function connect() {
		$this->set_resource($this->get_host(), $this->get_username(), $this->get_password());
		$this->select_database($this->get_database());
		return $this;
	}
	
	public function set_resource($host, $username, $password) {
		$this->set_host($host);
		$this->set_username($username);
		$this->set_password($password);
		$this->resource = mysql_connect($host, $username, $password);
		return $this;
	}
	
	public function select_database($database) {
		mysql_select_db($database, $this->resource);
		return $this;
	}
	
	public function set_host($host) {
		$this->host = $host;
	}
	
	public function set_username($username) {
		$this->username = $username;
	}
	
	public function set_password($password) {
		$this->password = $password;
	}
	
	public function set_database($database) {
		$this->database = $database;
	}
	
	public function get_host() {
		return $this->host;
	}
	
	public function get_username() {
		return $this->username;
	}
	
	public function get_password() {
		return $this->password;
	}
	
	public function get_database() {
		return $this->database;
	}
	
	public function get_resource() {
		return $this->resource;
	}
	
	public function close() {
		$instance = self::get();
		mysql_close($instance->resource);
		return self::$instance;
	}
}
?>