<?php
abstract class moojon_base_extended_number_column extends moojon_base_number_column
{
	private $auto_increment;
	
	public function __construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment, $unsigned, $zerofill, $auto_increment)
	{
		parent::__construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment, $unsigned, $zerofill);
		$this->auto_increment = $auto_increment;
	}
	
	final public function get_auto_increment()
	{
		return $this->auto_increment;
	}
}
?>