<?php
abstract class moojon_base_extended_string_column extends moojon_base_string_column
{
	private $charset;
	private $collation;
	
	protected function __construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment, $key_length, $charset, $collation)
	{
		parent::__construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment, $key_length);
		$this->charset = $charset;
		$this->collation = $collation;
	}
	
	final public function get_charset()
	{
		return $this->charset;
	}
	
	final public function get_collation()
	{
		return $this->collation;
	}
}
?>