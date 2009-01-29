<?php
abstract class moojon_base_binary_string_column extends moojon_base_extended_string_column
{
	private $binary;
	
	public function __construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment, $charset, $collation, $key_length, $binary) {
		parent::__construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment, $charset, $collation, $key_length);
		$this->binary = $binary;
	}	
	
	final public function get_binary() {
		return $this->binary;
	}
}
?>