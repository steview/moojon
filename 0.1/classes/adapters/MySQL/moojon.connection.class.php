<?php
final class moojon_connection
{
	private static $instance;
	private $host;
	private $username;
	private $password;
	private $database;
	private $resource;
	
	final private function __construct() {}
	
	final static public function init($host = null, $username = null, $password = null, $database = null) {
		if (empty(self::$instance))
		{
			self::$instance = new moojon_connection();
		}
		if ($host != null)
		{
			self::$instance->set_host($host);
		}
		if ($username != null)
		{
			self::$instance->set_username($username);
		}
		if ($password != null)
		{
			self::$instance->set_password($password);
		}
		if ($database != null)
		{
			self::$instance->set_database($database);
		}
		if ($host != null && $username != null && $password != null && $database != null)
		{
			self::$instance->connect($host, $username, $password, $database);
		}
		return self::$instance;
	}
	
	final public function connect() {
		$this->set_resource($this->get_host(), $this->get_username(), $this->get_password());
		$this->select_database($this->get_database());
		return $this;
	}
	
	final public function set_resource($host, $username, $password) {
		$this->set_host($host);
		$this->set_username($username);
		$this->set_password($password);
		$this->resource = mysql_connect($host, $username, $password);
		return $this;
	}
	
	final public function select_database($database) {
		mysql_select_db($database, $this->resource);
		return $this;
	}
	
	final public function set_host($host) {
		$this->host = $host;
	}
	
	final public function set_username($username) {
		$this->username = $username;
	}
	
	final public function set_password($password) {
		$this->password = $password;
	}
	
	final public function set_database($database) {
		$this->database = $database;
	}
	
	final public function get_host() {
		return $this->host;
	}
	
	final public function get_username() {
		return $this->username;
	}
	
	final public function get_password() {
		return $this->password;
	}
	
	final public function get_database() {
		return $this->database;
	}
	
	final public function get_resource() {
		return $this->resource;
	}
	
	final public function close() {
		mysql_close($this->resource);
		return $this;
	}
}
?>