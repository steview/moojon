<?php
abstract class moojon_base_string_column extends moojon_base_column
{
	private $key_length;
	
	protected function __construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment, $key_length)
	{
		parent::__construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment);
		$this->key_length = $key_length;
	}
	
	final public function get_key_length()
	{
		return $this->key_length;
	}
}
?>